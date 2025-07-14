<footer class="footer">

  <section class="grid">

    <div class="box">
      <img src="<?= BASE_URL ?>/images/email-icon.png" alt="">
      <h3>email</h3>
      <a href="mailto:javatani00@gmail.com">javatani00@gmail.com</a>
    </div>

    <div class="box">
      <img src="<?= BASE_URL ?>/images/clock-icon.png" alt="">
      <h3>Buka Dari</h3>
      <p>08:00am - 11:00pm</p>
    </div>

    <div class="box">
      <img src="<?= BASE_URL ?>/images/phone-icon.png" alt="">
      <h3>Nomer Telp</h3>
      <a href="tel:087749790303">087749790303</a>
    </div>

  </section>

  <div class="credit">&copy; copyright @ <?= date('Y') ?> by <span>Sentra Durian Tegal</span> | all rights reserved!</div>

</footer>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
<script src="<?= BASE_URL ?>/js/script.js"></script>

<script>
  var swiper = new Swiper(".hero-slider", {
    loop: true,
    grabCursor: true,
    effect: "flip",
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
  });

  // Add to cart functionality
  function addToCart(productId, quantity = 1) {
    fetch('<?= BASE_URL ?>/cart/add', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `pid=${productId}&qty=${quantity}`
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Update cart count
          const cartBtn = document.getElementById('cart-btn');
          if (cartBtn) {
            let countSpan = cartBtn.querySelector('.cart-count');
            if (!countSpan) {
              countSpan = document.createElement('span');
              countSpan.className = 'cart-count';
              cartBtn.appendChild(countSpan);
            }
            countSpan.textContent = data.cartCount;
          }

          // Show success message
          showMessage(data.message, 'success');
        } else {
          showMessage(data.message, 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showMessage('Something went wrong', 'error');
      });
  }

  function showMessage(message, type) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${type}`;
    messageDiv.innerHTML = `
      <span>${message}</span>
      <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
   `;
    document.body.appendChild(messageDiv);

    setTimeout(() => {
      messageDiv.remove();
    }, 5000);
  }
</script>

</body>

</html>