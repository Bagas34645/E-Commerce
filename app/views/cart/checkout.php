<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="heading">
  <h3>checkout</h3>
  <p><a href="<?= BASE_URL ?>">home</a> <span> / checkout</span></p>
</div>

<section class="checkout">

  <h1 class="title">order summary</h1>

  <form action="<?= BASE_URL ?>/checkout" method="post">

    <div class="cart-items">
      <h3>cart items</h3>
      <?php if (!empty($cartItems)): ?>
        <?php foreach ($cartItems as $item): ?>
          <p><span class="name"><?= htmlspecialchars($item['name']) ?></span><span class="price">$<?= $item['price'] ?> x <?= $item['quantity'] ?></span></p>
        <?php endforeach; ?>
        <p class="grand-total"><span class="name">grand total :</span><span class="price">$<?= $total ?>/-</span></p>
        <a href="<?= BASE_URL ?>/cart" class="btn">view cart</a>
      <?php else: ?>
        <p class="empty">your cart is empty</p>
      <?php endif; ?>
    </div>

    <?php if (!empty($cartItems)): ?>
      <div class="user-info">
        <h3>your info</h3>
        <p><i class="fas fa-user"></i><span><?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></span></p>
        <p><i class="fas fa-phone"></i><span><?= htmlspecialchars($_SESSION['user_number'] ?? '') ?></span></p>
        <p><i class="fas fa-envelope"></i><span><?= htmlspecialchars($_SESSION['user_email'] ?? '') ?></span></p>
        <a href="<?= BASE_URL ?>/update_profile.php" class="btn">update info</a>

        <h3>delivery address</h3>
        <p><i class="fas fa-map-marker-alt"></i><span>
            <?php if (isset($_SESSION['user_address']) && !empty($_SESSION['user_address'])): ?>
              <?= htmlspecialchars($_SESSION['user_address']) ?>
            <?php else: ?>
              <span class="empty">address not added</span>
            <?php endif; ?>
          </span></p>
        <a href="<?= BASE_URL ?>/update_address.php" class="btn">update address</a>

        <h3>payment method</h3>
        <select name="method" class="box" required>
          <option value="" disabled selected>select payment method --</option>
          <option value="cash on delivery">cash on delivery</option>
          <option value="credit card">credit card</option>
          <option value="paytm">paytm</option>
          <option value="paypal">paypal</option>
        </select>

        <input type="submit" value="place order" class="btn <?= empty($cartItems) ? 'disabled' : '' ?>" style="width:100%; background:var(--red); color:white;" name="submit">
      </div>
    <?php endif; ?>

  </form>

</section>

<?php include __DIR__ . '/../layouts/footer.php'; ?>