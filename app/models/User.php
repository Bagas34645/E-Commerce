<?php

require_once __DIR__ . '/../../core/Model.php';

class User extends Model
{
  protected $table = 'users';

  public function create($data)
  {
    $sql = "INSERT INTO {$this->table} (name, email, number, password, address) VALUES (?, ?, ?, ?, ?)";
    return $this->query($sql, [
      $data['name'],
      $data['email'],
      $data['number'],
      $data['password'],
      $data['address']
    ]);
  }

  public function findByEmail($email)
  {
    $sql = "SELECT * FROM {$this->table} WHERE email = ?";
    return $this->query($sql, [$email])->fetch(PDO::FETCH_ASSOC);
  }

  public function authenticate($email, $password)
  {
    $user = $this->findByEmail($email);
    if ($user && sha1($password) === $user['password']) {
      return $user;
    }
    return false;
  }

  public function update($id, $data)
  {
    $sql = "UPDATE {$this->table} SET name = ?, email = ?, number = ?, address = ? WHERE id = ?";
    return $this->query($sql, [
      $data['name'],
      $data['email'],
      $data['number'],
      $data['address'],
      $id
    ]);
  }

  public function updatePassword($id, $newPassword)
  {
    $sql = "UPDATE {$this->table} SET password = ? WHERE id = ?";
    return $this->query($sql, [sha1($newPassword), $id]);
  }

  public function emailExists($email, $excludeId = null)
  {
    $sql = "SELECT COUNT(*) FROM {$this->table} WHERE email = ?";
    $params = [$email];

    if ($excludeId) {
      $sql .= " AND id != ?";
      $params[] = $excludeId;
    }

    $result = $this->query($sql, $params)->fetch();
    return $result[0] > 0;
  }
}
