<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="heading">
  <h3>product details</h3>
  <p><a href="<?= BASE_URL ?>">home</a> <span> / menu / <?= htmlspecialchars($product['name']) ?></span></p>
</div>

<section class="quick-view">

  <h1 class="title">product details</h1>

  <form action="<?= BASE_URL ?>/cart/add" method="post" class="box">
    <input type="hidden" name="pid" value="<?= $product['id']; ?>">
    <input type="hidden" name="name" value="<?= $product['name']; ?>">
    <input type="hidden" name="price" value="<?= $product['price']; ?>">
    <input type="hidden" name="image" value="<?= $product['image']; ?>">

    <div class="row">
      <div class="image-container">
        <div class="main-image">
          <img src="<?= BASE_URL ?>/uploaded_img/<?= $product['image']; ?>" alt="<?= htmlspecialchars($product['name']) ?>">
        </div>
      </div>
      <div class="content">
        <div class="name"><?= htmlspecialchars($product['name']); ?></div>
        <div class="flex">
          <div class="price"><span>$</span><?= $product['price']; ?><span>/-</span></div>
          <input type="number" name="qty" class="qty" min="1" max="99" maxlength="2" value="1">
        </div>
        <div class="category">Category: <span><?= htmlspecialchars($product['category']); ?></span></div>
        <button type="button" onclick="addToCart(<?= $product['id'] ?>, this.closest('form').querySelector('input[name=qty]').value)" class="cart-btn">add to cart</button>
      </div>
    </div>
  </form>

</section>

<?php if (!empty($relatedProducts)): ?>
  <section class="products">

    <h1 class="title">similar products</h1>

    <div class="box-container">

      <?php foreach ($relatedProducts as $relatedProduct): ?>
        <form action="" method="post" class="box">
          <input type="hidden" name="pid" value="<?= $relatedProduct['id']; ?>">
          <input type="hidden" name="name" value="<?= $relatedProduct['name']; ?>">
          <input type="hidden" name="price" value="<?= $relatedProduct['price']; ?>">
          <input type="hidden" name="image" value="<?= $relatedProduct['image']; ?>">
          <a href="<?= BASE_URL ?>/product/<?= $relatedProduct['id'] ?>" class="fas fa-eye"></a>
          <button type="button" class="fas fa-shopping-cart" onclick="addToCart(<?= $relatedProduct['id'] ?>, this.closest('form').querySelector('input[name=qty]').value)"></button>
          <img src="<?= BASE_URL ?>/uploaded_img/<?= $relatedProduct['image']; ?>" alt="">
          <a href="<?= BASE_URL ?>/category/<?= urlencode($relatedProduct['category']) ?>" class="cat"><?= htmlspecialchars($relatedProduct['category']); ?></a>
          <div class="name"><?= htmlspecialchars($relatedProduct['name']); ?></div>
          <div class="flex">
            <div class="price"><span>$</span><?= $relatedProduct['price']; ?><span>/-</span></div>
            <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2">
          </div>
        </form>
      <?php endforeach; ?>

    </div>

  </section>
<?php endif; ?>

<?php include __DIR__ . '/../layouts/footer.php'; ?>