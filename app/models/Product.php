<?php

require_once __DIR__ . '/../../core/Model.php';

class Product extends Model
{
  protected $table = 'products';

  public function getAll($limit = null)
  {
    $sql = "SELECT * FROM {$this->table} ORDER BY id DESC";
    if ($limit) {
      $sql .= " LIMIT {$limit}";
    }
    return $this->query($sql)->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getByCategory($category)
  {
    $sql = "SELECT * FROM {$this->table} WHERE category = ? ORDER BY id DESC";
    return $this->query($sql, [$category])->fetchAll(PDO::FETCH_ASSOC);
  }

  public function search($keyword)
  {
    $sql = "SELECT * FROM {$this->table} WHERE name LIKE ? OR category LIKE ? ORDER BY id DESC";
    $searchTerm = "%{$keyword}%";
    return $this->query($sql, [$searchTerm, $searchTerm])->fetchAll(PDO::FETCH_ASSOC);
  }

  public function create($data)
  {
    $sql = "INSERT INTO {$this->table} (name, category, price, image) VALUES (?, ?, ?, ?)";
    return $this->query($sql, [
      $data['name'],
      $data['category'],
      $data['price'],
      $data['image']
    ]);
  }

  public function update($id, $data)
  {
    $sql = "UPDATE {$this->table} SET name = ?, category = ?, price = ?";
    $params = [$data['name'], $data['category'], $data['price']];

    if (isset($data['image']) && !empty($data['image'])) {
      $sql .= ", image = ?";
      $params[] = $data['image'];
    }

    $sql .= " WHERE id = ?";
    $params[] = $id;

    return $this->query($sql, $params);
  }

  public function getCategories()
  {
    $sql = "SELECT DISTINCT category FROM {$this->table} ORDER BY category";
    return $this->query($sql)->fetchAll(PDO::FETCH_COLUMN);
  }
}
