navbar = document.querySelector('.header .flex .navbar');

document.querySelector('#menu-btn').onclick = () =>{
   navbar.classList.toggle('active');
   profile.classList.remove('active');
}

profile = document.querySelector('.header .flex .profile');

document.querySelector('#user-btn').onclick = () =>{
   profile.classList.toggle('active');
   navbar.classList.remove('active');
}

window.onscroll = () =>{
   navbar.classList.remove('active');
   profile.classList.remove('active');
   if (shoppingCart) {
      shoppingCart.classList.remove('active');
   }
}

document.querySelectorAll('input[type="number"]').forEach(numberInput => {
   numberInput.oninput = () =>{
      if(numberInput.value.length > numberInput.maxLength) numberInput.value = numberInput.value.slice(0, numberInput.maxLength);
   };
});

// MVC Cart Functions
function addToCart(productId, quantity = 1) {
   fetch('/cart/add', {
      method: 'POST',
      headers: {
         'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `pid=${productId}&qty=${quantity}`
   })
   .then(response => response.json())
   .then(data => {
      if (data.success) {
         // Update cart count in header
         updateCartCount(data.cartCount);
         showMessage(data.message, 'success');
      } else {
         if (data.message.includes('login')) {
            window.location.href = '/login.php';
         } else {
            showMessage(data.message, 'error');
         }
      }
   })
   .catch(error => {
      console.error('Error:', error);
      showMessage('Something went wrong', 'error');
   });
}

// Cart count is now handled directly in the header template

function showMessage(message, type) {
   // Remove existing messages
   document.querySelectorAll('.message').forEach(msg => msg.remove());
   
   const messageDiv = document.createElement('div');
   messageDiv.className = `message ${type}`;
   messageDiv.innerHTML = `
      <span>${message}</span>
      <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
   `;
   
   // Insert after header
   const header = document.querySelector('.header');
   if (header) {
      header.insertAdjacentElement('afterend', messageDiv);
   } else {
      document.body.prepend(messageDiv);
   }
   
   // Auto remove after 5 seconds
   setTimeout(() => {
      if (messageDiv.parentNode) {
         messageDiv.remove();
      }
   }, 5000);
}

// Search functionality
const searchForm = document.querySelector('.search-form form');
if (searchForm) {
   searchForm.addEventListener('submit', function(e) {
      const searchInput = this.querySelector('input[name="search_box"]');
      if (searchInput && searchInput.value.trim()) {
         this.action = '/search';
         this.method = 'GET';
         searchInput.name = 'search';
      }
   });
}

// Remove cart dropdown functionality since cart is now a direct link

// Load cart items when cart is opened
function loadCartItems() {
   fetch('/E-Commerce/cart/items')
   .then(response => response.json())
   .then(data => {
      const cartItemsContainer = document.getElementById('cart-items');
      if (cartItemsContainer && data.success && data.items) {
         // Update cart items display
         cartItemsContainer.innerHTML = '';
         if (data.items.length > 0) {
            data.items.forEach(item => {
               const itemElement = document.createElement('div');
               itemElement.className = 'box';
               itemElement.innerHTML = `
                  <img src="/E-Commerce/uploaded_img/${item.image}" alt="${item.name}">
                  <div class="name">${item.name}</div>
                  <div class="price">Rp ${new Intl.NumberFormat('id-ID').format(item.price)}</div>
                  <div class="qty">qty: ${item.quantity}</div>
               `;
               cartItemsContainer.appendChild(itemElement);
            });
         } else {
            cartItemsContainer.innerHTML = '<p class="empty">Keranjang kosong</p>';
         }
         
         // Update total
         const grandTotal = document.querySelector('.grand-total span');
         if (grandTotal) {
            grandTotal.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(data.total)}`;
         }
      }
   })
   .catch(error => {
      console.error('Error loading cart items:', error);
      const cartItemsContainer = document.getElementById('cart-items');
      if (cartItemsContainer) {
         cartItemsContainer.innerHTML = '<p class="empty">Error loading cart</p>';
      }
   });
}