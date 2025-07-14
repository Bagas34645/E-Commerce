<?php

/**
 * Base Model Class
 * Provides database connectivity and basic CRUD operations
 */
abstract class Model
{
  protected $conn;
  protected $table;

  public function __construct()
  {
    $this->connect();
  }

  /**
   * Establish database connection using configuration
   */
  private function connect()
  {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;

    try {
      $this->conn = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
      ]);
    } catch (PDOException $e) {
      if (APP_DEBUG) {
        die("Connection failed: " . $e->getMessage());
      } else {
        die("Database connection error");
      }
    }
  }

  protected function query($sql, $params = [])
  {
    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->execute($params);
      return $stmt;
    } catch (PDOException $e) {
      throw new Exception("Query failed: " . $e->getMessage());
    }
  }

  public function findAll()
  {
    $sql = "SELECT * FROM {$this->table}";
    return $this->query($sql)->fetchAll(PDO::FETCH_ASSOC);
  }

  public function findById($id)
  {
    $sql = "SELECT * FROM {$this->table} WHERE id = ?";
    return $this->query($sql, [$id])->fetch(PDO::FETCH_ASSOC);
  }

  public function delete($id)
  {
    $sql = "DELETE FROM {$this->table} WHERE id = ?";
    return $this->query($sql, [$id]);
  }
}
