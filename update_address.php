<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:home.php');
};

if (isset($_POST['submit'])) {

   $address = $_POST['jalan'] . ', RT ' . $_POST['rt'] . ', RW ' . $_POST['rw'] . ', ' . $_POST['kelurahan'] . ', ' . $_POST['kecamatan'] . ', ' . $_POST['kabupaten'] . ', ' . $_POST['provinsi'] . ' - ' . $_POST['kode_pos'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);

   $update_address = $conn->prepare("UPDATE `users` set address = ? WHERE id = ?");
   $update_address->execute([$address, $user_id]);

   $message[] = 'Alamat tersimpan!';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update address</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'components/user_header.php' ?>

   <section class="form-container">

      <form action="" method="post">
         <h3>alamat Anda</h3>
         <input type="text" class="box" placeholder="Jalan/Dusun" required maxlength="100" name="jalan">
         <input type="text" class="box" placeholder="RT" required maxlength="3" name="rt">
         <input type="text" class="box" placeholder="RW" required maxlength="3" name="rw">
         <input type="text" class="box" placeholder="Kelurahan/Desa" required maxlength="50" name="kelurahan">
         <input type="text" class="box" placeholder="Kecamatan" required maxlength="50" name="kecamatan">
         <input type="text" class="box" placeholder="Kabupaten/Kota" required maxlength="50" name="kabupaten">
         <input type="text" class="box" placeholder="Provinsi" required maxlength="50" name="provinsi">
         <input type="number" class="box" placeholder="Kode Pos" required max="99999" min="0" maxlength="5" name="kode_pos">
         <input type="submit" value="simpan alamat" name="submit" class="btn">
      </form>

   </section>










   <?php include 'components/footer.php' ?>







   <!-- custom js file link  -->
   <script src="js/script.js"></script>

</body>

</html>