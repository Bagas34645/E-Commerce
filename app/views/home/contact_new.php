<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="heading">
  <h3>Hubungi Kami</h3>
  <p><a href="<?= BASE_URL ?>">Beranda</a> <span> / Kontak</span></p>
</div>

<!-- contact section starts  -->

<section class="contact">
  <div class="row">
    <div class="image">
      <img src="<?= BASE_URL ?>/images/home-img-1.jpg" alt="">
    </div>

    <div class="contact-info">
      <h3>Kontak Kami</h3>
      <p><strong><i class="fa fa-phone"></i></strong> <a href="https://wa.me/6281234567890" target="_blank">+62 812-3456-7890</a></p>
      <p><strong><i class="fa-brands fa-instagram"></i></strong> <a href="https://www.instagram.com/rajaneduren_?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank">@sentraduriantegal</a></p>
      <p><strong><i class="fa fa-map-marker-alt"></i></strong> Kalikangkung Kulon, Kalikangkung, Kec. Pangkah,<br>
        Kabupaten Tegal, Jawa Tengah 52471</p>
    </div>
  </div>

  <div class="row">
    <div class="map-container">
      <h3>Lokasi Kami</h3>
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15837.123456789!2d109.1234567!3d-6.8765432!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6fb5a12b345678%3A0x9abcdef123456789!2sKalikangkung%20Kulon%2C%20Kalikangkung%2C%20Kec.%20Pangkah%2C%20Kabupaten%20Tegal%2C%20Jawa%20Tengah%2052471!5e0!3m2!1sid!2sid!4v1642123456789!5m2!1sid!2sid"
        width="100%"
        height="400"
        style="border:0; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);"
        allowfullscreen=""
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade">
      </iframe>
      <p style="margin-top: 15px; text-align: center; color: #666;">
        <strong>Alamat:</strong> Kalikangkung Kulon, Kalikangkung, Kec. Pangkah, Kabupaten Tegal, Jawa Tengah 52471
      </p>
    </div>
  </div>
</section>

<!-- contact section ends -->

<?php include __DIR__ . '/../layouts/footer.php'; ?>