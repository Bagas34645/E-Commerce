<?php include __DIR__ . '/../layouts/header.php'; ?>

<?php
// Display messages if any
if (isset($_SESSION['success'])) {
  echo '<div class="message"><span>' . $_SESSION['success'] . '</span><i class="fas fa-times" onclick="this.parentElement.remove();"></i></div>';
  unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
  echo '<div class="message"><span>' . $_SESSION['error'] . '</span><i class="fas fa-times" onclick="this.parentElement.remove();"></i></div>';
  unset($_SESSION['error']);
}
?>

<div class="heading">
  <h3>Hubungi Kami</h3>
  <p><a href="<?= BASE_URL ?>">Beranda</a> <span> / Kontak</span></p>
</div>

<!-- contact section starts  -->

<section class="contact">
  <div class="row">

    <div class="contact-info">
      <h3>Kontak Kami</h3>
      <p><strong><i class="fa fa-phone"></i></strong> <a href="https://wa.me/6281234567890" target="_blank" style="color: black; text-decoration: none;">+62 812-3456-7890</a></p>
      <p><strong><i class="fa-brands fa-instagram"></i></strong> <a href="https://www.instagram.com/rajaneduren_?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank" style="color: black; text-decoration: none;">@sentraduriantegal</a></p>
      <p><strong><i class="fa fa-map-marker-alt"></i></strong> Kalikangkung Kulon, Kalikangkung, Kec. Pangkah,<br>
        Kabupaten Tegal, Jawa Tengah 52471</p>
    </div>

    <form action="<?= BASE_URL ?>/kontak" method="post">
      <h3>Kirim Pesan Kepada Kami!</h3>
      <input type="text" name="name" maxlength="50" class="box" placeholder="masukkan nama anda" required>
      <input type="number" name="number" min="0" max="9999999999" class="box" placeholder="masukkan nomor telepon anda" required maxlength="10">
      <input type="email" name="email" maxlength="50" class="box" placeholder="masukkan email anda" required>
      <textarea name="message" class="box" required placeholder="masukkan pesan anda" maxlength="500" cols="30" rows="10"></textarea>
      <input type="submit" value="kirim pesan" name="send" class="btn">
    </form>
  </div>

  <div class="row">
    <div class="map-container" style="width: 100%; max-width: 1200px; margin: 0 auto;">
      <h3>Lokasi Kami</h3>
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d247.53193207251573!2d109.16426669434816!3d-6.948898566468411!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6fbf953faa2dcd%3A0x6956291070d3eec8!2sSentra%20Durian%20Tegal!5e0!3m2!1sen!2sid!4v1752518628369!5m2!1sen!2sid"
        width="100%"
        height="600"
        style="border:0; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); min-height: 500px;"
        allowfullscreen=""
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade">
      </iframe>
      <p style="margin-top: 15px; text-align: center; color: #666;">
        <strong>Alamat:</strong> Sentra Durian Tegal, Kabupaten Tegal, Jawa Tengah
      </p>
    </div>
  </div>
</section>

<!-- contact section ends -->

<style>
  .contact .row .map-container {
    width: 100%;
    margin: 2rem 0;
  }

  .contact .row .map-container iframe {
    width: 100%;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: box-shadow 0.3s ease;
  }

  .contact .row .map-container iframe:hover {
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
  }

  @media (max-width: 768px) {
    .contact .row .map-container iframe {
      height: 400px !important;
      min-height: 350px !important;
    }
  }

  @media (min-width: 769px) {
    .contact .row .map-container iframe {
      height: 600px !important;
      min-height: 500px !important;
    }
  }
</style>

<?php include __DIR__ . '/../layouts/footer.php'; ?>