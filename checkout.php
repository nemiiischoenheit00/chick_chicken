<?php
session_start();
require 'db.php';

// ── Login wall: redirect guests before they see the checkout ──
$isLoggedIn = isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0;

// ── Pre-fill: fetch fresh user details from DB ──
$prefill = ['name' => '', 'phone' => '', 'email' => ''];
if ($isLoggedIn) {
    $stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $prefill['name']  = htmlspecialchars($row['name']  ?? '');
        $prefill['email'] = htmlspecialchars($row['email'] ?? '');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="icon" type="Image" href="assets/Logo.png"/>
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>@import url('https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap');</style>
    <style>@import url('https://fonts.googleapis.com/css2?family=Alegreya+Sans:ital,wght@0,100;0,300;0,400;0,500;0,700;0,800;0,900;1,100;1,300;1,400;1,500;1,700;1,800;1,900&family=Oswald:wght@200..700&display=swap');</style>
    <title>Chick Chicken</title>
  </head>

  <header>
    <div class="logo">
      <h1>
        <a href="index.html"><img src="assets/Logo2.png" alt="ChickChicken" style="width: auto; height: 45px"/></a>
      </h1>
    </div>
    <nav>
      <ul>
          <li><a href="aboutus.html" class="header_button">About Us</a></li>
          <li><a href="#FAQS" class="header_button">FAQs</a></li>
          <li><a href="branchloc.html" class="header_button">Branch Locator</a></li>

        <li class="nav-item dropdown" style="list-style: none;">
          <?php if (isset($_SESSION['username'])): ?>

          <a class="header_button dropdown-toggle" href="#" data-bs-toggle="dropdown">
            <?php echo $_SESSION['username']; ?>
          </a>

            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item text-danger" href="logout_process.php">Logout</a></li>
            </ul>

          <?php else: ?>

            <a href="login.php" class="header_button">Sign In</a>

          <?php endif; ?>
        </li>

        <li><a href="orders.html" class="ordernow_button">Order Now</a></li>
      </ul>
    </nav>
  </header>

<body>

<?php if (!$isLoggedIn): ?>
<!-- ── Not-logged-in modal ── -->
<div id="login-required-modal" style="
    position:fixed; inset:0; background:rgba(0,0,0,0.6);
    display:flex; align-items:center; justify-content:center; z-index:9999;">
  <div style="
      background:#fff; border-radius:16px; padding:40px 36px;
      text-align:center; max-width:380px; width:90%;
      box-shadow:0 8px 32px rgba(0,0,0,0.2);">
    <div style="font-size:52px; margin-bottom:10px;">🔒</div>
    <h2 style="margin:0 0 8px; font-size:1.4rem; color:#1a1a1a;">Sign In Required</h2>
    <p style="color:#666; font-size:0.95rem; margin:0 0 24px;">
      You need to be signed in to place an order. Please log in to continue.
    </p>
    <a href="login.php" style="
        display:block; background:#D85A30; color:#fff; border-radius:8px;
        padding:12px 32px; font-size:1rem; font-weight:600;
        text-decoration:none; margin-bottom:10px;">
      Sign In
    </a>
    <a href="index.php" style="
        display:block; color:#888; font-size:0.9rem; text-decoration:none;">
      Go back to Home
    </a>
  </div>
</div>
<?php endif; ?>
      <div class="disclaimer" id="disclaimer">
        <div class="overlay">
            <img src="assets/Logo3.png" alt="ChickChicken Logo" class="overlay-logo">
            <h1>Disclaimer!</h1>
            <p>
                The time slot you choose serves as your confirmed booking and pick-up time. Please note that deliveries 
                are self-arranged via Lalamove, so you'll need to book your own rider accordingly. Website orders are 
                accepted only from 10:30 AM to 7:00 PM; after 7:00 PM, only walk-in customers will be 
                accommodated in-store. If a time slot appears as unavailable, it means that slot is already full.
            </p>
            <button id="closeBtn">Close</button>
        </div>
    </div>

  
<!-- Checkout -->

<!-- Order Summary -->
  <main class="checkout-container">
    <h1>CHECKOUT</h1>

    <section class="branch-info">
      <div>
        <h3>Chosen Branch</h3>
        <p><strong>Chick Chicken - Amang Rodriguez Pasig</strong><br>
        224 Eulogio Amang Rodriguez Ave., Pasig, 1610 Metro Manila</p>
      </div>
      <div class="store-number">
        <h4>Store Number</h4>
        <p>+63 999 999 9999</p>
      </div>
    </section>

    <!-- Order Summary -->
    <section class="order-summary">
      <h3>Order Summary</h3>
      <div id="summary-items">
        <!-- populated by JS -->
      </div>
      <div class="summary-totals">
        <div class="summary-row"><span>Subtotal</span><span id="summary-subtotal">₱0.00</span></div>
        <div class="summary-row total"><span>Total</span><span id="summary-total">₱0.00</span></div>
      </div>
    </section>


    <form class="checkout-form">
      <h3>Your Details</h3>

      <label>Name<span>*</span></label>
      <input type="text" name="name" required value="<?php echo $prefill['name']; ?>">

      <label>Phone Number<span>*</span></label>
      <input type="tel" name="phone" required value="<?php echo $prefill['phone']; ?>" placeholder="e.g. 09171234567">

      <label>Email<span>*</span></label>
      <input type="email" name="email" required value="<?php echo $prefill['email']; ?>">

      <label>Home Address<span>*</span></label>
      <input type="text" name="address" required placeholder="Enter your delivery address">

      <h3>Payment Method</h3>
      <div class="payment-methods">

      <label class="method">
        <input type="radio" name="payment" value="online" hidden>
        <img src="assets/card.png" alt="Card Icon">
        <p><strong>ONLINE PAYMENT</strong><br>Debit, Credit, E-Wallet</p>
      </label>

      <label class="method">
        <input type="radio" name="payment" value="cod" hidden>
        <img src="assets/pouch.png" alt="Pouch">
        <p><strong>CASH ON DELIVERY</strong><br>Pay with cash upon arrival</p>
      </label>

    </div>
    <div id="card-field">
      <label for="cardnumber">Card Number</label>
      <input type="text" id="cardnumber" name="cardnumber"
        placeholder="1234 5678 9012 3456"
        inputmode="numeric"
        maxlength="19">
    </div>

    <div class="checkout-divider"></div>

      <button type="submit" class="place-order-button">Confirm Order</button>
    </form>
  </main>
<!-- End of Checkout -->


  <!-- footer ngani -->
<footer class="footer">
  <div class="footer-container">

  <div class="footer-logo">
      <img src="assets/Logo3.png" alt="Chick Chicken Logo" class="footer-logo-img">
    </div>

    <div class="footer-links">
      <h4>Quick Links</h4>
      <ul>
        <li><a href="#">Menu</a></li>
        <li><a href="#">Deals</a></li>
        <li><a href="#">Order Now</a></li>
      </ul>
    </div>

    <div class="footer-info">
      <h4>Information</h4>
      <ul>
                <li><a href="#about-us">About Us</a></li> 
                <li><a href="#FAQS">FAQs</a></li>
                <li><a href="branchloc.html">Branch Locator</a></li>
      </ul>
    </div>

    <div class="footer-section">
      <h4>Need help?</h4>
      <p>Contact us on:</p>
      <div class="social-icons">
        <a href="https://www.facebook.com/chickchickenph/"><img src="assets/facebook-icon.png" alt="Facebook"></a>
        <a href="https://www.instagram.com/chick.chickenph/?hl=en"><img src="assets/instagram-icon.png" alt="Instagram"></a>
        <a href="https://www.tiktok.com/@chickchickenph?lang=en"><img src="assets/tiktok-icon.png" alt="TikTok"></a>
      </div>
    </div>

  </div>

  <div class="footer-bottom">
    © 2025 Chick Chicken. All rights reserved.
  </div>
</footer>
<!-- end of footer ngani -->


<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="checkout.js"></script>
</body>
</html>