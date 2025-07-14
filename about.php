<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
};

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>about</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <!-- header section starts  -->
   <?php include 'components/user_header.php'; ?>
   <!-- header section ends -->

   <div class="heading">
      <h3>Sentra Durian Tegal</h3>
      <p><strong><i class="fa fa-map-marker-alt"></i></strong> Kalikangkung Kulon, Kalikangkung, Kec. Pangkah,
         Kabupaten Tegal, Jawa Tengah 52471</p>
      <p><a href="/E-Commerce/">Beranda</a> <span> / Tentang</span></p>
   </div>

   <!-- about section starts  -->

   <section class="about">

      <div class="row">

         <div class="image">
            <img src="images/home-img-1.jpg" alt="">
         </div>

         <div class="content">
            <h3>Pusat Penjualan Durian Berkualitas</h3>
            <p>Sentra Durian Tegal adalah pusat informasi dan distribusi durian unggulan langsung dari kebun terbaik di Tegal. Kami berkomitmen menyediakan durian berkualitas tinggi untuk konsumsi pribadi maupun kebutuhan bisnis Anda.</p>
            <p class="section-title"><strong>Produk Kami:</strong></p>
            <div class="box-container">
               <a href="category.php?category=Buah Durian" class="box">
                  <img src="images/cat-1.png" alt="Buah Durian">
                  <h3>Buah Durian</h3>
               </a>
               <a href="category.php?category=Bibit" class="box">
                  <img src="images/cat-2.png" alt="Bibit">
                  <h3>Bibit</h3>
               </a>
               <a href="category.php?category=Makanan" class="box">
                  <img src="images/cat-3.png" alt="Makanan">
                  <h3>Makanan</h3>
               </a>
               <a href="category.php?category=Minuman" class="box">
                  <img src="images/cat-4.png" alt="Minuman">
                  <h3>Minuman</h3>
               </a>
            </div>
            <a href="menu.php" class="btn">Lihat Produk</a>
         </div>

      </div>

   </section>

   <!-- about section ends -->

   <!-- steps section starts  -->

   <section class="steps">

      <h1 class="title">Proses Pemesanan Praktis</h1>

      <div class="box-container">

         <div class="box">
            <img src="images/step-1.png" alt="">
            <h3>Pilih Pesanan</h3>
            <p>Pilih durian atau produk favorit Anda dengan mudah melalui website kami.</p>
         </div>

         <div class="box">
            <img src="images/step-2.png" alt="">
            <h3>Pengiriman Cepat</h3>
            <p>Pesanan Anda akan segera diproses dan dikirim dengan layanan pengiriman yang cepat dan aman.</p>
         </div>

         <div class="box">
            <img src="images/step-3.png" alt="">
            <h3>Nikmati Makanan</h3>
            <p>Terima pesanan di rumah dan nikmati durian serta produk olahan berkualitas bersama keluarga.</p>
         </div>

      </div>

   </section>

   <!-- steps section ends -->

   <!-- reviews section starts  -->

   <section class="reviews">

      <h1 class="title">Ulasan Pelanggan</h1>

      <div class="swiper reviews-slider">

         <div class="swiper-wrapper">

            <div class="swiper-slide slide">
               <img src="images/foto profile.jpg" alt="">
               <p>Duriannya Mantap</p>
               <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
               </div>
               <h3>Budi Santoso</h3>
            </div>

            <div class="swiper-slide slide">
               <img src="images/foto profile.jpg" alt="">
               <p>Saya sangat puas dengan kualitas durian yang dikirim, daging buahnya tebal dan manis.</p>
               <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
               </div>
               <h3>Siti Aminah</h3>
            </div>

            <div class="swiper-slide slide">
               <img src="images/foto profile.jpg" alt="">
               <p>Proses pemesanan mudah, produk sampai dengan aman. Sangat direkomendasikan untuk pecinta durian!</p>
               <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
               </div>
               <h3>Agus Pratama</h3>
            </div>

            <div class="swiper-slide slide">
               <img src="images/foto profile.jpg" alt="">
               <p>Bibit durian yang saya beli tumbuh dengan baik, terima kasih atas konsultasinya.</p>
               <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
               </div>
               <h3>Rina Wulandari</h3>
            </div>

            <div class="swiper-slide slide">
               <img src="images/foto profile.jpg" alt="">
               <p>Es durian segar mantap.</p>
               <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
               </div>
               <h3>Dewi Lestari</h3>
            </div>

            <div class="swiper-slide slide">
               <img src="images/foto profile.jpg" alt="">
               <p>Tempat sangat nyaman untuk menikmati durian.</p>
               <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
               </div>
               <h3>Andi Wijaya</h3>
            </div>

         </div>

         <div class="swiper-pagination"></div>

      </div>

   </section>

   <!-- reviews section ends -->



















   <!-- footer section starts  -->
   <?php include 'components/footer.php'; ?>
   <!-- footer section ends -->=






   <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

   <!-- custom js file link  -->
   <script src="js/script.js"></script>

   <script>
      var swiper = new Swiper(".reviews-slider", {
         loop: true,
         grabCursor: true,
         spaceBetween: 20,
         pagination: {
            el: ".swiper-pagination",
            clickable: true,
         },
         breakpoints: {
            0: {
               slidesPerView: 1,
            },
            700: {
               slidesPerView: 2,
            },
            1024: {
               slidesPerView: 3,
            },
         },
      });
   </script>

</body>

</html>