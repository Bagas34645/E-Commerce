<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="heading">
  <h3>orders</h3>
  <p><a href="<?= BASE_URL ?>">home</a> <span> / orders</span></p>
</div>

<section class="orders">

  <h1 class="title">your orders</h1>

  <div class="box-container">

    <?php if (!empty($orders)): ?>
      <?php foreach ($orders as $order): ?>
        <div class="box">
          <p>placed on : <span><?= $order['placed_on']; ?></span></p>
          <p>name : <span><?= htmlspecialchars($order['name']); ?></span></p>
          <p>email : <span><?= htmlspecialchars($order['email']); ?></span></p>
          <p>number : <span><?= htmlspecialchars($order['number']); ?></span></p>
          <p>address : <span><?= htmlspecialchars($order['address']); ?></span></p>
          <p>payment method : <span><?= htmlspecialchars($order['method']); ?></span></p>
          <p>your orders : <span><?= htmlspecialchars($order['total_products']); ?></span></p>
          <p>total price : <span>$<?= $order['total_price']; ?>/-</span></p>
          <p>payment status : <span style="color:<?= $order['payment_status'] == 'pending' ? 'red' : 'green'; ?>"><?= $order['payment_status']; ?></span></p>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="empty">no orders placed yet!</p>
    <?php endif; ?>

  </div>

</section>

<?php include __DIR__ . '/../layouts/footer.php'; ?>