<?php

class Router
{
  private $routes = [];
  private $basePath;

  public function __construct($basePath = '')
  {
    $this->basePath = rtrim($basePath, '/');
  }

  public function get($path, $controller, $method)
  {
    $this->addRoute('GET', $path, $controller, $method);
  }

  public function post($path, $controller, $method)
  {
    $this->addRoute('POST', $path, $controller, $method);
  }

  private function addRoute($httpMethod, $path, $controller, $method)
  {
    $this->routes[$httpMethod][$path] = [
      'controller' => $controller,
      'method' => $method
    ];
  }

  public function dispatch()
  {
    $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $requestUri = $_SERVER['REQUEST_URI'] ?? '/';

    // Remove query string and base path
    $path = parse_url($requestUri, PHP_URL_PATH);

    // Remove E-Commerce folder from path if present
    $path = preg_replace('/^\/E-Commerce/', '', $path);

    // Remove index_mvc.php from path if present
    $path = preg_replace('/\/index_mvc\.php/', '', $path);

    // Remove simple_mvc.php from path if present
    $path = preg_replace('/\/simple_mvc\.php/', '', $path);

    $path = $path ?: '/';

    // Debug output - remove in production
    if (isset($_GET['debug'])) {
      echo "Request URI: " . $requestUri . "<br>";
      echo "Parsed Path: " . $path . "<br>";
      echo "Request Method: " . $requestMethod . "<br>";
      echo "Available routes for {$requestMethod}: <pre>";
      print_r($this->routes[$requestMethod] ?? []);
      echo "</pre>";
    }

    if (isset($this->routes[$requestMethod])) {
      foreach ($this->routes[$requestMethod] as $routePath => $route) {
        if ($this->matchRoute($routePath, $path, $matches)) {
          $this->executeRoute($route, $matches);
          return;
        }
      }
    }

    // Route not found
    http_response_code(404);
    echo "404 - Page Not Found<br>";
    echo "Requested path: " . htmlspecialchars($path) . "<br>";
    echo "Request method: " . $requestMethod;
  }

  private function matchRoute($routePath, $requestPath, &$matches)
  {
    // Convert route path to regex
    $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $routePath);
    $pattern = '#^' . $pattern . '$#';

    return preg_match($pattern, $requestPath, $matches);
  }

  private function executeRoute($route, $matches)
  {
    $controllerName = $route['controller'];
    $methodName = $route['method'];

    if (!class_exists($controllerName)) {
      throw new Exception("Controller {$controllerName} not found");
    }

    $controller = new $controllerName();

    if (!method_exists($controller, $methodName)) {
      throw new Exception("Method {$methodName} not found in {$controllerName}");
    }

    // Remove the full match from parameters
    array_shift($matches);

    call_user_func_array([$controller, $methodName], $matches);
  }
}
