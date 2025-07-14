<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="heading">
  <h3>Menu Produk</h3>
  <p><a href="<?= BASE_URL ?>">Beranda</a> <span> / Menu</span></p>
</div>

<!-- Category filter -->
<section class="category">
  <h1 class="title">Kategori Produk</h1>
  <div class="box-container">
    <a href="<?= BASE_URL ?>/produk" class="box">
      <h3>Semua</h3>
    </a>
    <?php if (!empty($categories)): ?>
      <?php foreach ($categories as $category): ?>
        <a href="<?= BASE_URL ?>/category/<?= urlencode($category) ?>" class="box">
          <h3><?= htmlspecialchars($category) ?></h3>
        </a>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</section>

<!-- Products section -->
<section class="products">
  <h1 class="title">Daftar Produk</h1>

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
    <?php else: ?>
      <p class="empty">no products found!</p>
    <?php endif; ?>
  </div>
</section>

<?php include __DIR__ . '/../layouts/footer.php'; ?>