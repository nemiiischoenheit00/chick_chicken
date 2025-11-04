# chick_chicken

// HTML //
<!-- Cart Button -->
<button id="cart-btn" class="cart-btn"><img src="assets/cart.png" alt="my_cart"></button>


// CSS // (KET SAN NA ILAGAY)
.sauce {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  padding: 40px;
  text-align: center;
  margin-left: 20%;
  margin-bottom: 100px;
}

// CSS // (CHANGE THE .cart-panel into this)
.cart-panel {
  background: white;
  width: 350px;
  max-width: 95%;
  max-height: 75%;
  padding: 20px;
  overflow-y: auto;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
  border-top-left-radius: 0;
  border-bottom-left-radius: 0;
  border-top-right-radius: 12px; 
  border-bottom-right-radius: 12px; 
  position: relative;
  display: flex;
  flex-direction: column;
  gap: 12px;
  outline: 1px solid black;
}
