<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
};

if (isset($_POST['send'])) {

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $msg = $_POST['msg'];
   $msg = filter_var($msg, FILTER_SANITIZE_STRING);

   $select_message = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND number = ? AND message = ?");
   $select_message->execute([$name, $email, $number, $msg]);

   if ($select_message->rowCount() > 0) {
      $message[] = 'already sent message!';
   } else {

      $insert_message = $conn->prepare("INSERT INTO `messages`(user_id, name, email, number, message) VALUES(?,?,?,?,?)");
      $insert_message->execute([$user_id, $name, $email, $number, $msg]);

      $message[] = 'sent message successfully!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>contact</title>

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
      <h3>Hubungi Kami</h3>
      <p><a href="/E-Commerce/">Beranda</a> <span> / Kontak</span></p>
   </div>

   <!-- contact section starts  -->

   <section class="contact">
      <div class="row">
         <div class="image">
            <img src="images/home-img-1.jpg" alt="">
         </div>

         <div class="contact-info">
            <h3>Kontak Kami</h3>
            <p><strong><i class="fa fa-phone"></i></strong> <a href="https://wa.me/6281234567890" target="_blank">+62 812-3456-7890</a></p>
            <p><strong><i class="fa-brands fa-instagram"></i></strong> <a href="https://www.instagram.com/rajaneduren_?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank">@sentraduriantegal</a></p>
            <p><strong><i class="fa fa-map-marker-alt"></i></strong> Kalikangkung Kulon, Kalikangkung, Kec. Pangkah,<br>
               Kabupaten Tegal, Jawa Tengah 52471</p>
         </div>
      </div>
   </section>


   <!-- contact section ends -->










   <!-- footer section starts  -->
   <?php include 'components/footer.php'; ?>
   <!-- footer section ends -->








   <!-- custom js file link  -->
   <script src="js/script.js"></script>

</body>

</html>