<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="heading">
  <h3>hasil pencarian</h3>
  <p><a href="<?= BASE_URL ?>">beranda</a> <span> / pencarian</span></p>
</div>

<section class="search-form">
  <form action="<?= BASE_URL ?>/search" method="get">
    <input type="text" name="search" placeholder="cari produk..." maxlength="100" class="box" value="<?= htmlspecialchars($keyword) ?>" required>
    <button type="submit" class="fas fa-search"></button>
  </form>
</section>

<section class="products" style="padding-top: 0;">

  <?php if (!empty($keyword)): ?>
    <h1 class="title">hasil pencarian untuk "<?= htmlspecialchars($keyword) ?>"</h1>
  <?php else: ?>
    <h1 class="title">cari produk</h1>
  <?php endif; ?>

  <div class="box-container">

    <?php if (!empty($products)): ?>
      <?php foreach ($products as $product): ?>
        <form action="" method="post" class="box">
          <input type="hidden" name="pid" value="<?= $product['id']; ?>">
          <input type="hidden" name="name" value="<?= $product['name']; ?>">
          <input type="hidden" name="price" value="<?= $product['price']; ?>">
          <input type="hidden" name="image" value="<?= $product['image']; ?>">

          <a href="<?= BASE_URL ?>/product/<?= $product['id'] ?>" class="fas fa-eye"></a>
          <input type="submit" class="fas fa-shopping-cart" name="add_to_cart" value="">

          <img src="<?= BASE_URL ?>/uploaded_img/<?= $product['image']; ?>" alt="<?= htmlspecialchars($product['name']) ?>">
          <a href="<?= BASE_URL ?>/category/<?= urlencode($product['category']) ?>" class="cat"><?= htmlspecialchars($product['category']); ?></a>
          <div class="name"><?= htmlspecialchars($product['name']); ?></div>
          <div class="flex">
            <div class="price"><span>Rp</span><?= number_format($product['price'], 0, ',', '.'); ?></div>
            <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2">
          </div>
        </form>
      <?php endforeach; ?>
    <?php elseif (!empty($keyword)): ?>
      <p class="empty">tidak ada produk yang ditemukan untuk "<?= htmlspecialchars($keyword) ?>"!</p>
    <?php else: ?>
      <p class="empty">cari produk menggunakan form di atas!</p>
    <?php endif; ?>

  </div>

  <?php if (empty($keyword) || empty($products)): ?>
    <div class="more-btn">
      <a href="<?= BASE_URL ?>/produk" class="btn">lihat semua produk</a>
    </div>
  <?php endif; ?>

</section>

<?php include __DIR__ . '/../layouts/footer.php'; ?>