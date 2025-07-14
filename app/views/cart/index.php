<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="heading">
  <h3>keranjang belanja</h3>
  <p><a href="<?= BASE_URL ?>">beranda</a> <span> / keranjang</span></p>
</div>

<section class="products">
  <h1 class="title">keranjang anda</h1>

  <div class="box-container">
    <?php if (!empty($cartItems)): ?>
      <?php foreach ($cartItems as $item): ?>
        <form action="<?= BASE_URL ?>/cart/update" method="post" class="box">
          <input type="hidden" name="cart_id" value="<?= $item['id']; ?>">

          <button type="submit" name="delete" value="<?= $item['id']; ?>" class="fas fa-times" onclick="return confirm('hapus dari keranjang?');" formaction="<?= BASE_URL ?>/cart/remove"></button>

          <img src="<?= BASE_URL ?>/uploaded_img/<?= $item['image']; ?>" alt="">
          <div class="name"><?= $item['name']; ?></div>
          <div class="flex">
            <div class="price">Rp <?= number_format($item['price'], 0, ',', '.'); ?></div>
            <input type="number" name="qty" class="qty" min="1" max="99" value="<?= $item['quantity']; ?>" maxlength="2">
            <button type="submit" class="fas fa-edit"></button>
          </div>
          <div class="sub-total">sub total: <span>Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?></span></div>
        </form>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="empty">keranjang anda kosong</p>
    <?php endif; ?>
  </div>

  <?php if (!empty($cartItems)): ?>
    <div class="cart-total">
      <p>total keseluruhan: <span>Rp <?= number_format($total, 0, ',', '.'); ?></span></p>
      <div class="flex-btn">
        <a href="<?= BASE_URL ?>/produk" class="option-btn">lanjut belanja</a>
        <form action="<?= BASE_URL ?>/cart/clear" method="post" style="display: inline;">
          <button type="submit" class="delete-btn" onclick="return confirm('hapus semua dari keranjang?');">hapus semua</button>
        </form>
      </div>
      <a href="<?= BASE_URL ?>/checkout" class="btn <?= empty($cartItems) ? 'disabled' : '' ?>">lanjut ke checkout</a>
    </div>
  <?php endif; ?>
</section>

<?php include __DIR__ . '/../layouts/footer.php'; ?>