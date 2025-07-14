<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $title ?? 'Sentra Durian Tegal' ?></title>

  <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
</head>

<body>

  <?php
  // Display flash messages
  if (isset($_SESSION['success'])): ?>
    <div class="message">
      <span><?= $_SESSION['success'] ?></span>
      <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
    </div>
    <?php unset($_SESSION['success']); ?>
  <?php endif; ?>

  <?php if (isset($_SESSION['error'])): ?>
    <div class="message">
      <span><?= $_SESSION['error'] ?></span>
      <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
    </div>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <header class="header">
    <section class="flex">
      <a href="<?= BASE_URL ?>" class="logo">Sentra Durian Tegal</a>

      <nav class="navbar">
        <a href="<?= BASE_URL ?>">Beranda</a>
        <a href="<?= BASE_URL ?>/tentang">Tentang</a>
        <a href="<?= BASE_URL ?>/produk">Produk</a>
        <a href="<?= BASE_URL ?>/orders">Pesanan</a>
        <a href="<?= BASE_URL ?>/kontak">Kontak</a>
      </nav>

      <div class="icons">
        <a href="<?= BASE_URL ?>/search"><i class="fas fa-search"></i></a>
        <a href="<?= BASE_URL ?>/cart"><i class="fas fa-shopping-cart"></i>
          <?php if (isset($cartCount) && $cartCount > 0): ?>
            <span>(<?= $cartCount ?>)</span>
          <?php else: ?>
            <span>(0)</span>
          <?php endif; ?>
        </a>
        <div id="user-btn" class="fas fa-user"></div>
        <div id="menu-btn" class="fas fa-bars"></div>
      </div>

      <div class="profile">
        <?php
        if (isset($_SESSION['user_id'])) {
          $user_id = $_SESSION['user_id'];
          try {
            $conn = new PDO('mysql:host=localhost;dbname=food_db', 'root', '');
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if ($select_profile->rowCount() > 0) {
              $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
        ?>
              <p class="name"><?= htmlspecialchars($fetch_profile['name']); ?></p>
              <div class="flex">
                <a href="<?= BASE_URL ?>/profile.php" class="btn">Profil</a>
                <a href="<?= BASE_URL ?>/components/user_logout.php" onclick="return confirm('logout from this website?');" class="delete-btn">logout</a>
              </div>
              <p class="account">
                <a href="<?= BASE_URL ?>/login.php">login</a> atau
                <a href="<?= BASE_URL ?>/register.php">daftar</a>
              </p>
            <?php
            } else {
            ?>
              <p class="name">please login first!</p>
              <div class="flex">
                <a href="<?= BASE_URL ?>/login.php" class="btn">login</a>
                <a href="<?= BASE_URL ?>/register.php" class="btn">daftar</a>
              </div>
          <?php
            }
          } catch (PDOException $e) {
            echo "<p>Database error</p>";
          }
        } else {
          ?>
          <p class="name">please login first!</p>
          <div class="flex">
            <a href="<?= BASE_URL ?>/login.php" class="btn">login</a>
            <a href="<?= BASE_URL ?>/register.php" class="btn">daftar</a>
          </div>
        <?php } ?>
      </div>

    </section>
  </header>