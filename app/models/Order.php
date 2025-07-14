<?php

require_once __DIR__ . '/../../core/Model.php';

class Order extends Model
{
  protected $table = 'orders';

  public function create($data)
  {
    $sql = "INSERT INTO {$this->table} (user_id, name, number, email, method, address, total_products, total_price, placed_on) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    return $this->query($sql, [
      $data['user_id'],
      $data['name'],
      $data['number'],
      $data['email'],
      $data['method'],
      $data['address'],
      $data['total_products'],
      $data['total_price'],
      date('Y-m-d')
    ]);
  }

  public function getByUserId($userId)
  {
    $sql = "SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY placed_on DESC";
    return $this->query($sql, [$userId])->fetchAll(PDO::FETCH_ASSOC);
  }

  public function updateStatus($id, $status)
  {
    $sql = "UPDATE {$this->table} SET payment_status = ? WHERE id = ?";
    return $this->query($sql, [$status, $id]);
  }

  public function getAllOrders()
  {
    $sql = "SELECT o.*, u.name as user_name FROM {$this->table} o 
                JOIN users u ON o.user_id = u.id 
                ORDER BY o.placed_on DESC";
    return $this->query($sql)->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getOrderStats()
  {
    $sql = "SELECT 
                COUNT(*) as total_orders,
                SUM(total_price) as total_revenue,
                COUNT(CASE WHEN payment_status = 'pending' THEN 1 END) as pending_orders,
                COUNT(CASE WHEN payment_status = 'completed' THEN 1 END) as completed_orders
                FROM {$this->table}";
    return $this->query($sql)->fetch(PDO::FETCH_ASSOC);
  }
}
