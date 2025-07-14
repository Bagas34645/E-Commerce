<?php

require_once __DIR__ . '/Session.php';

abstract class Controller
{

  protected function view($view, $data = [])
  {
    extract($data);
    $viewPath = __DIR__ . "/../app/views/{$view}.php";

    if (file_exists($viewPath)) {
      require_once $viewPath;
    } else {
      throw new Exception("View {$view} not found");
    }
  }

  protected function redirect($url)
  {
    header("Location: {$url}");
    exit;
  }

  protected function json($data)
  {
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
  }

  protected function isPost()
  {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
  }

  protected function isGet()
  {
    return $_SERVER['REQUEST_METHOD'] === 'GET';
  }

  protected function getUserId()
  {
    return getUserId();
  }

  protected function requireAuth()
  {
    if (!$this->getUserId()) {
      $this->redirect('/E-Commerce/login.php');
    }
  }
}
