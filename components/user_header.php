<?php
if (isset($message)) {
   foreach ($message as $message) {
      echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">

   <section class="flex">

      <a href="/E-Commerce/" class="logo">Sentra Durian Tegal </a>

      <nav class="navbar">
         <a href="/E-Commerce/">Beranda</a>
         <a href="index_mvc.php?page=tentang">Tentang</a>
         <a href="index_mvc.php?page=produk">Produk</a>
         <a href="index_mvc.php?page=orders">Pesanan</a>
         <a href="index_mvc.php?page=kontak">Kontak</a>
      </nav>

      <div class="icons">
         <?php
         $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $count_cart_items->execute([$user_id]);
         $total_cart_items = $count_cart_items->rowCount();
         ?>
         <a href="search.php"><i class="fas fa-search"></i></a>
         <a href="index_mvc.php?page=cart"><i class="fas fa-shopping-cart"></i><span>(<?= $total_cart_items; ?>)</span></a>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="menu-btn" class="fas fa-bars"></div>
      </div>

      <div class="profile">
         <?php
         $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
         $select_profile->execute([$user_id]);
         if ($select_profile->rowCount() > 0) {
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
            <p class="name"><?= $fetch_profile['name']; ?></p>
            <div class="flex">
               <a href="profile.php" class="btn">Profil</a>
               <a href="components/user_logout.php" onclick="return confirm('logout from this website?');" class="delete-btn">logout</a>
            </div>
            <p class="account">
               <a href="login.php">login</a> atau
               <a href="register.php">daftar</a>
            </p>
         <?php
         } else {
         ?>
            <p class="name">please login first!</p>
            <a href="login.php" class="btn">login</a>
         <?php
         }
         ?>
      </div>

   </section>

</header>