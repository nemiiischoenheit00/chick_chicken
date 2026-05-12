<?php
session_start();
require 'db.php';

$user = ['full_name' => '', 'phone' => '', 'email' => ''];

if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT first_name, last_name, phone, email FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $user['full_name'] = htmlspecialchars(trim($row['first_name'] . ' ' . $row['last_name']));
        $user['phone']     = htmlspecialchars($row['phone'] ?? '');
        $user['email']     = htmlspecialchars($row['email'] ?? '');
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="icon" type="image/png" href="assets/Logo.png"/>
  <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css"/>
  <link rel="stylesheet" href="style.css">
  <style>@import url('https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap');</style>
  <style>@import url('https://fonts.googleapis.com/css2?family=Alegreya+Sans:ital,wght@0,100;0,300;0,400;0,500;0,700;0,800;0,900;1,100;1,300;1,400;1,500;1,700;1,800;1,900&family=Oswald:wght@200..700&display=swap');</style>
  <title>Chick Chicken - Checkout</title>

  <style>
    /* ── Font base ── */
    .checkout-container h1,
    .checkout-container h3,
    .checkout-form h3,
    .branch-selector-label {
      font-family: 'Oswald', sans-serif;
      letter-spacing: 0.5px;
    }
    .checkout-form label,
    .checkout-form input,
    .checkout-form p,
    .checkout-form select,
    .branch-detail,
    .branch-hours {
      font-family: 'Alegreya Sans', sans-serif;
    }

    /* ── Branch selector ── */
    .branch-selector-section { margin-bottom: 28px; }
    .branch-selector-label {
      font-size: 18px; font-weight: 600;
      color: #1a1a1a; margin-bottom: 10px; display: block;
    }
    .branch-select-wrap { position: relative; }
    .branch-select-wrap select {
      width: 100%; appearance: none; -webkit-appearance: none;
      background: #fff; border: 2px solid #e5e5e5; border-radius: 10px;
      padding: 13px 44px 13px 16px; font-family: 'Alegreya Sans', sans-serif;
      font-size: 15px; color: #1a1a1a; cursor: pointer; outline: none;
      transition: border-color 0.2s;
    }
    .branch-select-wrap select:focus { border-color: #f5c800; }
    .branch-select-wrap::after {
      content: ''; position: absolute; right: 16px; top: 50%;
      transform: translateY(-50%); width: 0; height: 0;
      border-left: 6px solid transparent; border-right: 6px solid transparent;
      border-top: 7px solid #888; pointer-events: none;
    }
    .branch-info-card {
      margin-top: 12px; background: #fffbea;
      border: 1.5px solid #f5c800; border-radius: 10px;
      padding: 14px 16px; display: none; gap: 10px; flex-direction: column;
    }
    .branch-info-card.visible { display: flex; }
    .branch-detail { font-size: 14px; color: #555; line-height: 1.5; margin: 0; }
    .branch-hours  { font-size: 13px; color: #e65c00; font-weight: 700; margin: 0; }
    .branch-phone  { font-size: 14px; color: #1a1a1a; font-weight: 700; margin: 0; }

    /* ── Pre-filled inputs ── */
    .checkout-form input[data-prefilled="true"] {
      background: #fafafa; color: #444; border-color: #ddd;
    }

    /* ── Payment section spacing ── */
    .checkout-form h3.payment-heading { margin-top: 36px; }
    #card-field { margin-top: 16px; }
  </style>
</head>

<?php include 'nav.php'; ?>

<body>
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

  <main class="checkout-container">
    <h1>CHECKOUT</h1>

    <!-- BRANCH SELECTOR -->
    <section class="branch-selector-section">
      <span class="branch-selector-label">Choose a Branch</span>
      <div class="branch-select-wrap">
        <select id="branchSelect">
          <option value="">— Select a branch —</option>
          <option value="Chick Chicken - Pasig"
            data-address="274 Eulogio Amang Rodriguez Ave, Pasig, 1610 Metro Manila"
            data-hours="Open 5AM – 6PM" data-phone="+63 999 999 9999">
            Chick Chicken – Pasig
          </option>
          <option value="Chick Chicken - Makati"
            data-address="3231 Zapote, Makati City, Metro Manila"
            data-hours="Open 5AM – 6PM" data-phone="+63 999 999 9999">
            Chick Chicken – Makati
          </option>
          <option value="Chick Chicken - Maginhawa, QC"
            data-address="193 Maginhawa, Diliman, Quezon City, Kalakhang Maynila"
            data-hours="Open 5AM – 6PM" data-phone="+63 999 999 9999">
            Chick Chicken – Maginhawa, QC
          </option>
          <option value="Chick Chicken - Marikina"
            data-address="14 Redwood, Marikina, 1800 Metro Manila"
            data-hours="Open 5AM – 6PM" data-phone="+63 999 999 9999">
            Chick Chicken – Marikina
          </option>
          <option value="Chick Chicken - Timog"
            data-address="80 Panay Ave, Diliman, Quezon City, 1103 Metro Manila"
            data-hours="Open 5AM – 6PM" data-phone="+63 999 999 9999">
            Chick Chicken – Timog
          </option>
        </select>
      </div>
      <div class="branch-info-card" id="branchInfoCard">
        <p class="branch-detail" id="branchAddress"></p>
        <p class="branch-phone"  id="branchPhone"></p>
        <p class="branch-hours"  id="branchHours"></p>
      </div>
    </section>

    <!-- Order Summary -->
    <section class="order-summary">
      <h3>Order Summary</h3>
      <div id="summary-items"></div>
      <div class="summary-totals">
        <div class="summary-row"><span>Subtotal</span><span id="summary-subtotal">₱0.00</span></div>
        <div class="summary-row total"><span>Total</span><span id="summary-total">₱0.00</span></div>
      </div>
    </section>

    <form class="checkout-form">
      <h3>Your Details</h3>

      <label>Name<span>*</span></label>
      <input type="text" name="name" required
        value="<?= $user['full_name'] ?>"
        <?= $user['full_name'] ? 'data-prefilled="true"' : '' ?>>

      <label>Phone Number<span>*</span></label>
      <input type="tel" name="phone" id="phoneInput" placeholder="+63 XXX XXX XXXX" required
        value="<?= $user['phone'] ?>"
        <?= $user['phone'] ? 'data-prefilled="true"' : '' ?>>

      <label>Email<span>*</span></label>
      <input type="email" name="email" required
        value="<?= $user['email'] ?>"
        <?= $user['email'] ? 'data-prefilled="true"' : '' ?>>

      <label>Home Address<span>*</span></label>
      <input type="text" name="address" required>

      <h3 class="payment-heading">Payment Method</h3>
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
          placeholder="1234 5678 9012 3456" inputmode="numeric" maxlength="19">
      </div>

      <div class="checkout-divider"></div>
      <button type="submit" class="place-order-button">Confirm Order</button>
    </form>
  </main>

  <footer class="footer">
    <div class="footer-container">
      <div class="footer-logo">
        <img src="assets/Logo3.png" alt="Chick Chicken Logo" class="footer-logo-img">
      </div>
      <div class="footer-links">
        <h4>Quick Links</h4>
        <ul>
          <li><a href="orders.php">Menu</a></li>
        </ul>
      </div>
      <div class="footer-info">
        <h4>Information</h4>
        <ul>
          <li><a href="aboutus.html">About Us</a></li>
          <li><a href="index.php#FAQS">FAQs</a></li>
          <li><a href="branch-locator.html">Branch Locator</a></li>
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
    <div class="footer-bottom">© 2025 Chick Chicken. All rights reserved.</div>
  </footer>

  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
  <script src="checkout.js"></script>

  <?php include 'order-tracker-widget.php'; ?>
</body>
</html>