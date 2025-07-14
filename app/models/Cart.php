<?php

require_once __DIR__ . '/../../core/Model.php';

class Cart extends Model
{
  protected $table = 'cart';

  public function getByUserId($userId)
  {
    $sql = "SELECT c.*, p.name as product_name, p.category, p.image as product_image 
                FROM {$this->table} c 
                JOIN products p ON c.pid = p.id 
                WHERE c.user_id = ? 
                ORDER BY c.id DESC";
    return $this->query($sql, [$userId])->fetchAll(PDO::FETCH_ASSOC);
  }

  public function addItem($userId, $productId, $name, $price, $quantity, $image)
  {
    // Check if item already exists
    $existing = $this->query(
      "SELECT * FROM {$this->table} WHERE user_id = ? AND pid = ?",
      [$userId, $productId]
    )->fetch();

    if ($existing) {
      // Update quantity
      $sql = "UPDATE {$this->table} SET quantity = quantity + ? WHERE user_id = ? AND pid = ?";
      return $this->query($sql, [$quantity, $userId, $productId]);
    } else {
      // Insert new item
      $sql = "INSERT INTO {$this->table} (user_id, pid, name, price, quantity, image) VALUES (?, ?, ?, ?, ?, ?)";
      return $this->query($sql, [$userId, $productId, $name, $price, $quantity, $image]);
    }
  }

  public function updateQuantity($cartId, $quantity)
  {
    if ($quantity <= 0) {
      return $this->delete($cartId);
    }

    $sql = "UPDATE {$this->table} SET quantity = ? WHERE id = ?";
    return $this->query($sql, [$quantity, $cartId]);
  }

  public function removeItem($cartId)
  {
    return $this->delete($cartId);
  }

  public function clearByUserId($userId)
  {
    $sql = "DELETE FROM {$this->table} WHERE user_id = ?";
    return $this->query($sql, [$userId]);
  }

  public function getTotal($userId)
  {
    $sql = "SELECT SUM(price * quantity) as total FROM {$this->table} WHERE user_id = ?";
    $result = $this->query($sql, [$userId])->fetch();
    return $result['total'] ?? 0;
  }

  public function getItemCount($userId)
  {
    $sql = "SELECT SUM(quantity) as count FROM {$this->table} WHERE user_id = ?";
    $result = $this->query($sql, [$userId])->fetch();
    return $result['count'] ?? 0;
  }

  public function getCartSummary($userId)
  {
    $items = $this->getByUserId($userId);
    $total = $this->getTotal($userId);
    $count = $this->getItemCount($userId);

    return [
      'items' => $items,
      'total' => $total,
      'count' => $count
    ];
  }
}
