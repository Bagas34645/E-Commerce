<?php include __DIR__ . '/../layouts/header.php'; ?>

<section class="hero">

  <div class="swiper hero-slider">

    <div class="swiper-wrapper">

      <div class="swiper-slide slide">
        <div class="content">
          <span>Sentra Durian Tegal</span>
          <h3>Rajane Duren</h3>
          <a href="<?= BASE_URL ?>/produk" class="btn">Lihat Produk</a>
        </div>
        <div class="image">
          <img src="<?= BASE_URL ?>/images/home-img-1.jpg" alt="">
        </div>
      </div>

      <div class="swiper-slide slide">
        <div class="content">
          <span>Makan di Tempat</span>
          <h3>Rajane Duren</h3>
          <a href="<?= BASE_URL ?>/produk" class="btn">lihat menu</a>
        </div>
        <div class="image">
          <img src="<?= BASE_URL ?>/images/home-img-1.jpg" alt="">
        </div>
      </div>

      <div class="swiper-slide slide">
        <div class="content">
          <span>Pesan Di</span>
          <h3>Nomer Telp 087749790303</h3>
          <a href="<?= BASE_URL ?>/produk" class="btn">lihat menu</a>
        </div>
        <div class="image">
          <img src="<?= BASE_URL ?>/images/home-img-1.jpg" alt="">
        </div>
      </div>

    </div>

    <div class="swiper-pagination"></div>

  </div>

</section>

<section class="category">

  <h1 class="title">Kategori</h1>

  <div class="box-container">

    <a href="<?= BASE_URL ?>/category/Buah Durian" class="box">
      <img src="<?= BASE_URL ?>/images/cat-1.png" alt="">
      <h3>Buah Durian</h3>
    </a>

    <a href="<?= BASE_URL ?>/category/Minuman" class="box">
      <img src="<?= BASE_URL ?>/images/cat-2.png" alt="">
      <h3>Minuman</h3>
    </a>

    <a href="<?= BASE_URL ?>/category/Makanan" class="box">
      <img src="<?= BASE_URL ?>/images/cat-3.png" alt="">
      <h3>Makanan</h3>
    </a>

    <a href="<?= BASE_URL ?>/category/Dessert" class="box">
      <img src="<?= BASE_URL ?>/images/cat-4.png" alt="">
      <h3>Dessert</h3>
    </a>

  </div>

</section>

<section class="products">

  <h1 class="title">Produk Terbaru</h1>

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
          <img src="<?= BASE_URL ?>/uploaded_img/<?= $product['image']; ?>" alt="">
          <a href="<?= BASE_URL ?>/category/<?= $product['category']; ?>" class="cat"><?= $product['category']; ?></a>
          <div class="name"><?= $product['name']; ?></div>
          <div class="flex">
            <div class="price"><span>Rp</span><?= number_format($product['price'], 0, ',', '.'); ?></div>
            <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2">
          </div>
        </form>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="empty">no products added yet!</p>
    <?php endif; ?>

  </div>

  <div class="more-btn">
    <a href="<?= BASE_URL ?>/produk" class="btn">lihat semua</a>
  </div>

</section>

<section class="steps">

  <h1 class="title">Langkah Mudah</h1>

  <div class="box-container">

    <div class="box">
      <img src="<?= BASE_URL ?>/images/step-1.png" alt="">
      <h3>Pilih Pesanan</h3>
      <p>Pilih produk favorit Anda dari berbagai kategori yang tersedia di toko kami.</p>
    </div>

    <div class="box">
      <img src="<?= BASE_URL ?>/images/step-2.png" alt="">
      <h3>Pengiriman Cepat</h3>
      <p>Kami akan mengantarkan pesanan Anda dengan cepat dan aman ke lokasi tujuan.</p>
    </div>

    <div class="box">
      <img src="<?= BASE_URL ?>/images/step-3.png" alt="">
      <h3>Nikmati Produk</h3>
      <p>Selamat menikmati produk durian berkualitas terbaik dari Sentra Durian Tegal.</p>
    </div>

  </div>

</section>

<?php include __DIR__ . '/../layouts/footer.php'; ?>