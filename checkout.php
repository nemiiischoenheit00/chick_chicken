<?php
session_start();
require 'db.php';

$user = ['full_name' => '', 'phone' => '', 'email' => ''];

$discountInfo = [
    'status'       => 'none',
    'type'         => '',
    'rate'         => 0,
    'label'        => '',
];

if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT first_name, last_name, phone, email FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $user['full_name'] = htmlspecialchars(trim($row['first_name'] . ' ' . $row['last_name']));
        $user['phone']     = htmlspecialchars($row['phone'] ?? '');
        $user['email']     = htmlspecialchars($row['email'] ?? '');
    }

    $dStmt = $pdo->prepare(
        "SELECT type, status, notes FROM discount_applications
         WHERE user_id = ?
         ORDER BY created_at DESC LIMIT 1"
    );
    $dStmt->execute([$_SESSION['user_id']]);
    $dRow = $dStmt->fetch(PDO::FETCH_ASSOC);

    if ($dRow) {
        $discountInfo['status'] = $dRow['status'];
        $discountInfo['type']   = $dRow['type'];

        if ($dRow['status'] === 'approved') {
            $rates = [
                'Senior Citizen' => 0.20,
                'PWD'            => 0.20,
                'Student'        => 0.10,
            ];
            $discountInfo['rate']  = $rates[$dRow['type']] ?? 0;
            $discountInfo['label'] = $dRow['type'] . ' (' . (($discountInfo['rate'] * 100)) . '% off)';
        }
    }
}

$discountJson = json_encode($discountInfo);
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

    .checkout-form input[data-prefilled="true"] {
      background: #fafafa; color: #444; border-color: #ddd;
    }

    .checkout-form h3.payment-heading { margin-top: 36px; }
    #gcash-field { margin-top: 16px; }

    #discount-banner {
      display: none;
      align-items: center;
      gap: 12px;
      background: #e8f5e9;
      border: 1.5px solid #a5d6a7;
      border-radius: 12px;
      padding: 14px 18px;
      margin-bottom: 18px;
      font-family: 'Alegreya Sans', sans-serif;
      font-size: 14px;
      color: #2e7d32;
    }
    #discount-banner.pending-banner {
      background: #fff8e1;
      border-color: #f5c800;
      color: #b45309;
    }
    #discount-banner svg { flex-shrink: 0; }
    #discount-banner strong { font-weight: 700; }
    .discount-badge-pill {
      display: inline-flex; align-items: center; gap: 5px;
      background: #2e7d32; color: #fff;
      border-radius: 20px; padding: 3px 10px;
      font-family: 'Oswald', sans-serif;
      font-size: 11px; font-weight: 600;
      text-transform: uppercase; letter-spacing: 0.4px;
      margin-left: 6px;
    }
    .pending-banner .discount-badge-pill {
      background: #f5c800; color: #1a1a1a;
    }

    .summary-row.discount-row { color: #2e7d32; font-weight: 700; }
    .summary-row.discount-row span:last-child { color: #2e7d32; }

    .summary-row.vat-row { color: #555; font-size: 13px; }
    .summary-row.vat-row span:last-child { color: #555; }

    .discount-profile-link {
      font-family: 'Alegreya Sans', sans-serif;
      font-size: 13px; color: #888;
      display: block; margin-bottom: 20px; margin-top: -6px;
    }
    .discount-profile-link a {
      color: #D62828; text-decoration: underline; font-weight: 600;
    }

    .place-order-button {
      display: block; width: 100%;
      padding: 16px 32px; margin-top: 28px;
      background: #D62828; color: #fff;
      font-family: 'Oswald', sans-serif;
      font-size: 18px; font-weight: 600;
      letter-spacing: 1.5px; text-transform: uppercase;
      border: none; border-radius: 10px; cursor: pointer;
      transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
      box-shadow: 0 4px 16px rgba(214, 40, 40, 0.30);
    }
    .place-order-button:hover {
      background: #b71c1c; transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(214, 40, 40, 0.40);
    }
    .place-order-button:active {
      transform: translateY(0);
      box-shadow: 0 2px 8px rgba(214, 40, 40, 0.25);
    }

    /* GCash reference input */
    #gcash-ref-wrap { margin-top: 14px; }
    #gcash-ref-wrap label {
      display: block;
      font-family: 'Alegreya Sans', sans-serif;
      font-size: 15px;
      color: #1a1a1a;
      margin-bottom: 6px;
    }
    #gcash-ref-wrap label span { color: #D62828; margin-left: 2px; }
    #gcash-ref {
      display: block; width: 100%; padding: 13px 16px;
      box-sizing: border-box;
      border: 2px solid #e5e5e5; border-radius: 10px;
      font-family: 'Alegreya Sans', sans-serif;
      font-size: 15px; color: #1a1a1a;
      outline: none; transition: border-color 0.2s;
      letter-spacing: 1px;
    }
    #gcash-ref:focus { border-color: #f5c800; }
    #gcash-ref-hint {
      margin-top: 5px;
      font-family: 'Alegreya Sans', sans-serif;
      font-size: 12px;
      color: #aaa;
    }
  </style>
</head>

<body>
  <?php include 'nav.php'; ?>
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

    <!-- DISCOUNT BANNER -->
    <?php if ($discountInfo['status'] === 'approved'): ?>
      <div id="discount-banner" style="display:flex;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
          <line x1="7" y1="7" x2="7.01" y2="7"/>
        </svg>
        <div>
          <strong><?= htmlspecialchars($discountInfo['type']) ?> discount applied!</strong>
          <span class="discount-badge-pill">✔ <?= round($discountInfo['rate'] * 100) ?>% OFF</span>
          <br>
          <span style="font-size:13px; opacity:0.85;">Your approved discount will be automatically deducted from your order total.</span>
        </div>
      </div>
    <?php elseif ($discountInfo['status'] === 'pending'): ?>
      <div id="discount-banner" class="pending-banner" style="display:flex;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
        </svg>
        <div>
          <strong><?= htmlspecialchars($discountInfo['type']) ?> discount pending.</strong>
          <span class="discount-badge-pill">⏳ Under Review</span>
          <br>
          <span style="font-size:13px; opacity:0.85;">Your application is still being reviewed. Discount will not be applied until approved.</span>
        </div>
      </div>
    <?php elseif ($discountInfo['status'] === 'none'): ?>
      <p class="discount-profile-link">
        Eligible for a discount?
        <a href="profile.php">Apply on your profile page →</a>
      </p>
    <?php endif; ?>

    <!-- ORDER SUMMARY -->
    <section class="order-summary">
      <h3>Order Summary</h3>
      <div id="summary-items"></div>
      <div class="summary-totals">
        <div class="summary-row"><span>Subtotal</span><span id="summary-subtotal">₱0.00</span></div>
        <div class="summary-row discount-row" id="summary-discount-row" style="display:none;">
          <span id="summary-discount-label">Discount</span>
          <span id="summary-discount-amount">-₱0.00</span>
        </div>
        <div class="summary-row vat-row" id="summary-vatable-row" style="display:none;">
          <span>VATable Sales</span><span id="summary-vatable">₱0.00</span>
        </div>
        <div class="summary-row vat-row" id="summary-vat-row" style="display:none;">
          <span>VAT (12%)</span><span id="summary-vat">₱0.00</span>
        </div>
        <div class="summary-row total"><span>Total</span><span id="summary-total">₱0.00</span></div>
      </div>
    </section>

    <form class="checkout-form" enctype="multipart/form-data">
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
          <input type="radio" name="payment" value="gcash" hidden>
          <img src="assets/card.png" alt="GCash Icon">
          <p><strong>GCASH</strong><br>Pay via GCash e-wallet</p>
        </label>
        <label class="method">
          <input type="radio" name="payment" value="cod" hidden>
          <img src="assets/pouch.png" alt="Pouch">
          <p><strong>CASH ON DELIVERY</strong><br>Pay with cash upon arrival</p>
        </label>
      </div>

      <div id="gcash-field" style="display:none; margin-top:16px;">
        <div style="
          background:#fffbea; border:1.5px solid #f5c800; border-radius:10px;
          padding:16px; margin-bottom:14px;
          font-family:'Alegreya Sans',sans-serif; font-size:14px; color:#555; line-height:1.6;
        ">
          <p style="margin:0 0 14px 0;">Scan the QR code below or send your GCash payment to <strong style="color:#1a1a1a;">09XX XXX XXXX</strong>, then upload a screenshot of your receipt.</p>
          <div style="text-align:center;">
            <p style="
              font-family:'Oswald',sans-serif; font-size:13px; font-weight:600;
              letter-spacing:0.5px; color:#1a1a1a; text-transform:uppercase;
              margin:0 0 10px 0;
            ">QR Code</p>
            <img src="assets/gcash.png" alt="GCash QR Code"
              style="
                width:200px; height:200px; object-fit:contain;
                border:2px solid #f5c800; border-radius:12px;
                background:#fff; padding:8px; box-sizing:border-box;
              ">
          </div>
        </div>

        <!-- GCash Reference Number -->
        <div id="gcash-ref-wrap">
          <label for="gcash-ref">GCash Reference Number<span>*</span></label>
          <input
            type="text"
            id="gcash-ref"
            name="gcash_reference"
            placeholder="e.g. 1234567890123"
            maxlength="13"
            inputmode="numeric"
            autocomplete="off"
          >
          <p id="gcash-ref-hint">Enter the 13-digit reference number from your GCash receipt.</p>
        </div>

        <!-- Proof of Payment -->
        <label for="gcash-proof" style="display:block; margin-top:14px;">Proof of Payment<span>*</span></label>
        <input type="file" id="gcash-proof" name="gcash_proof"
          accept="image/png, image/jpeg, image/jpg, image/webp"
          style="
            display:block; width:100%; padding:14px 16px; box-sizing:border-box;
            border:2px dashed #f5c800; border-radius:10px; background:#fffbea;
            font-family:'Alegreya Sans',sans-serif; font-size:14px; color:#555;
            cursor:pointer; transition:border-color 0.2s;
          ">
        <div id="gcash-preview" style="margin-top:12px; display:none;">
          <img id="gcash-preview-img"
            style="max-width:100%; border-radius:10px; border:2px solid #f5c800; max-height:220px; object-fit:contain; display:block;"
            alt="Payment proof preview">
        </div>
      </div>

      <input type="hidden" name="discount_type" value="<?= htmlspecialchars($discountInfo['status'] === 'approved' ? $discountInfo['type'] : '') ?>">
      <input type="hidden" name="discount_rate" value="<?= $discountInfo['status'] === 'approved' ? $discountInfo['rate'] : 0 ?>">

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

  <script>
    window.DISCOUNT = <?= $discountJson ?>;
  </script>

  <script src="checkout.js?v=<?= time() ?>"></script>

  <script>
    (function () {
      var discount = window.DISCOUNT || {};

      function formatPHP(amount) {
        return '₱' + Number(amount).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
      }

      function applyDiscount() {
        var subtotalEl  = document.getElementById('summary-subtotal');
        var totalEl     = document.getElementById('summary-total');
        var discRow     = document.getElementById('summary-discount-row');
        var discLabel   = document.getElementById('summary-discount-label');
        var discAmt     = document.getElementById('summary-discount-amount');
        var vatableEl   = document.getElementById('summary-vatable');
        var vatEl       = document.getElementById('summary-vat');
        var vatableRow  = document.getElementById('summary-vatable-row');
        var vatRow      = document.getElementById('summary-vat-row');

        if (!subtotalEl || !totalEl) return;

        var raw      = subtotalEl.textContent.replace(/[^\d.]/g, '');
        var subtotal = parseFloat(raw) || 0;
        var total    = subtotal;

        if (discount.status === 'approved' && discount.rate > 0) {
          var savings = subtotal * discount.rate;
          total       = subtotal - savings;
          discLabel.textContent = discount.label || (discount.type + ' Discount');
          discAmt.textContent   = '-' + formatPHP(savings);
          discRow.style.display = 'flex';
        } else {
          discRow.style.display = 'none';
        }

        // VAT breakdown — prices are VAT-inclusive
        var vat     = total / 1.12 * 0.12;
        var vatable = total / 1.12;

        vatableEl.textContent    = formatPHP(vatable);
        vatEl.textContent        = formatPHP(vat);
        vatableRow.style.display = 'flex';
        vatRow.style.display     = 'flex';
        totalEl.textContent      = formatPHP(total);
      }

      applyDiscount();

      var observer   = new MutationObserver(applyDiscount);
      var subtotalEl = document.getElementById('summary-subtotal');
      if (subtotalEl) {
        observer.observe(subtotalEl, { childList: true, characterData: true, subtree: true });
      }

      window.applyDiscount = applyDiscount;
    })();

    // GCash toggle
    document.querySelectorAll('input[name="payment"]').forEach(function(radio) {
      radio.addEventListener('change', function() {
        var gcashField = document.getElementById('gcash-field');
        gcashField.style.display = this.value === 'gcash' ? 'block' : 'none';
        if (this.value !== 'gcash') {
          document.getElementById('gcash-proof').value = '';
          document.getElementById('gcash-ref').value = '';
          document.getElementById('gcash-preview').style.display = 'none';
        }
      });
    });

    // GCash proof preview
    document.getElementById('gcash-proof').addEventListener('change', function() {
      var preview = document.getElementById('gcash-preview');
      var img     = document.getElementById('gcash-preview-img');
      if (this.files && this.files[0]) {
        img.src = URL.createObjectURL(this.files[0]);
        preview.style.display = 'block';
      } else {
        preview.style.display = 'none';
      }
    });

    // GCash reference — digits only
    document.getElementById('gcash-ref').addEventListener('input', function() {
      this.value = this.value.replace(/\D/g, '').substring(0, 13);
    });
  </script>

  <!-- ORDER TRACKER WIDGET -->
  <style>
  @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600&family=Alegreya+Sans:wght@400;700&display=swap');

  #ot-bubble {
      position: fixed; bottom: 28px; left: 28px; z-index: 9999;
      font-family: 'Alegreya Sans', 'Segoe UI', sans-serif;
  }
  #ot-toggle {
      display: flex; align-items: center; gap: 10px;
      background: #1a1a1a; color: #f5c800; border: none;
      border-radius: 50px; padding: 12px 20px;
      font-family: 'Oswald', sans-serif; font-size: 14px; font-weight: 500;
      letter-spacing: 0.5px; cursor: pointer;
      box-shadow: 0 4px 20px rgba(0,0,0,0.25);
      transition: transform 0.15s, box-shadow 0.15s; white-space: nowrap;
  }
  #ot-toggle:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(0,0,0,0.3); }
  #ot-panel {
      position: absolute; bottom: 60px; left: 0; width: 340px;
      background: #fff; border-radius: 16px;
      box-shadow: 0 8px 40px rgba(0,0,0,0.18);
      overflow: hidden; display: none; flex-direction: column; max-height: 520px;
  }
  #ot-panel.open { display: flex; }
  .ot-header {
      background: #1a1a1a; color: #f5c800; padding: 14px 18px;
      display: flex; align-items: center; justify-content: space-between;
      font-family: 'Oswald', sans-serif; font-size: 15px; letter-spacing: 0.5px; flex-shrink: 0;
  }
  .ot-header-left { display: flex; align-items: center; gap: 8px; }
  .ot-close-btn {
      background: none; border: none; color: #f5c800; cursor: pointer;
      font-size: 20px; line-height: 1; padding: 0; opacity: 0.8;
      transition: opacity 0.15s; font-family: sans-serif;
  }
  .ot-close-btn:hover { opacity: 1; }
  #ot-panel-body { overflow-y: auto; flex: 1; }
  .ot-card { padding: 16px 18px 18px; }
  .ot-order-id { font-family: 'Oswald', sans-serif; font-size: 13px; color: #aaa; letter-spacing: 0.5px; margin-bottom: 4px; }
  .ot-order-meta { font-size: 13px; color: #777; margin-bottom: 14px; line-height: 1.5; }
  .ot-order-meta strong { color: #1a1a1a; font-weight: 700; }
  .ot-progress-track { position: relative; padding: 8px 0 20px; margin-bottom: 16px; }
  .ot-line { position: absolute; top: 18px; left: 18px; right: 18px; height: 3px; background: #eee; border-radius: 2px; z-index: 0; }
  .ot-line-fill { height: 100%; background: #f5c800; border-radius: 2px; transition: width 0.5s ease; }
  .ot-steps { position: relative; z-index: 1; display: flex; justify-content: space-between; align-items: flex-start; }
  .ot-step { display: flex; flex-direction: column; align-items: center; gap: 6px; flex: 1; }
  .ot-step-dot { width: 36px; height: 36px; border-radius: 50%; background: #eee; border: 3px solid #eee; display: flex; align-items: center; justify-content: center; font-size: 15px; transition: background 0.3s, border-color 0.3s; color: #bbb; }
  .ot-step.done .ot-step-dot { background: #f5c800; border-color: #f5c800; color: #1a1a1a; }
  .ot-step.active .ot-step-dot { background: #1a1a1a; border-color: #f5c800; color: #f5c800; animation: ot-pulse 2s infinite; }
  .ot-step-label { font-size: 10px; font-family: 'Oswald', sans-serif; letter-spacing: 0.3px; color: #bbb; text-align: center; line-height: 1.2; text-transform: uppercase; }
  .ot-step.done .ot-step-label, .ot-step.active .ot-step-label { color: #1a1a1a; }
  .ot-status-pill { display: inline-flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 800; padding: 3px 10px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.4px; font-family: 'Oswald', sans-serif; margin-bottom: 12px; }
  .pill-pending    { background: #fff8e1; color: #e65c00; }
  .pill-confirmed  { background: #e8f5e9; color: #2e7d32; }
  .pill-preparing  { background: #fff3e0; color: #e65100; }
  .pill-in_transit { background: #e3f2fd; color: #1565c0; }
  .pill-cancelled  { background: #fce4ec; color: #c62828; }
  .ot-items-label { font-family: 'Oswald', sans-serif; font-size: 11px; letter-spacing: 0.6px; text-transform: uppercase; color: #bbb; margin-bottom: 10px; }
  .ot-items-list { display: flex; flex-direction: column; gap: 10px; margin-bottom: 14px; }
  .ot-item { display: flex; align-items: center; gap: 12px; }
  .ot-item-img { width: 48px; height: 48px; border-radius: 10px; object-fit: cover; background: #f5f5f5; flex-shrink: 0; border: 1px solid #eee; }
  .ot-item-img-placeholder { width: 48px; height: 48px; border-radius: 10px; background: #f5f5f5; border: 1px solid #eee; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 20px; }
  .ot-item-info { flex: 1; min-width: 0; }
  .ot-item-name { font-size: 14px; font-weight: 700; color: #1a1a1a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .ot-item-qty { font-size: 12px; color: #999; margin-top: 2px; }
  .ot-item-price { font-family: 'Oswald', sans-serif; font-size: 13px; font-weight: 600; color: #555; white-space: nowrap; flex-shrink: 0; }
  .ot-divider { border: none; border-top: 1px solid #f0f0f0; margin: 12px 0; }
  .ot-total-row { display: flex; justify-content: space-between; align-items: center; font-family: 'Oswald', sans-serif; }
  .ot-total-label { font-size: 13px; color: #888; letter-spacing: 0.4px; }
  .ot-total-value { font-size: 18px; font-weight: 600; color: #1a1a1a; }
  .ot-state { padding: 32px 18px; text-align: center; font-family: 'Alegreya Sans', sans-serif; color: #aaa; font-size: 14px; line-height: 1.6; }
  .ot-state svg { display: block; margin: 0 auto 10px; }
  @keyframes ot-pulse { 0%, 100% { box-shadow: 0 0 0 4px rgba(245,200,0,0.2); } 50% { box-shadow: 0 0 0 8px rgba(245,200,0,0.05); } }
  </style>

  <div id="ot-bubble">
    <button id="ot-toggle" style="display:none;" onclick="otTogglePanel()">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
      My Order
    </button>
    <div id="ot-panel">
      <div class="ot-header">
        <div class="ot-header-left">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
          Order Tracker
        </div>
        <button class="ot-close-btn" onclick="otTogglePanel()" aria-label="Close">&#x2715;</button>
      </div>
      <div id="ot-panel-body">
        <div class="ot-state">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#ddd" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 11-2.12-9.36L23 10"/></svg>
          Loading your order…
        </div>
      </div>
    </div>
  </div>

  <script>
  (function () {
    var STEPS = [
      { key: 'pending',    label: 'Pending',    icon: '&#x23F3;' },
      { key: 'confirmed',  label: 'Confirmed',  icon: '&#x2713;' },
      { key: 'preparing',  label: 'Preparing',  icon: '&#x1F373;' },
      { key: 'in_transit', label: 'In Transit', icon: '&#x1F6F5;' },
    ];
    window.otTogglePanel = function () {
      document.getElementById('ot-panel').classList.toggle('open');
    };
    async function fetchOrders() {
      try {
        var res  = await fetch('order_tracker.php?action=active_orders');
        var data = await res.json();
        if (data.error === 'not_logged_in') { document.getElementById('ot-toggle').style.display = 'none'; return; }
        var orders = (data.orders || []).filter(o => o.status !== 'completed' && o.status !== 'cancelled');
        var toggle = document.getElementById('ot-toggle');
        if (orders.length === 0) { toggle.style.display = 'none'; document.getElementById('ot-panel').classList.remove('open'); return; }
        toggle.style.display = 'flex';
        renderCard(orders[0]);
      } catch(e) { console.error('Order tracker error:', e); }
    }
    function renderCard(o) {
      var body    = document.getElementById('ot-panel-body');
      var status  = o.status;
      var stepIdx = STEPS.findIndex(s => s.key === status);
      var fillPct = stepIdx < 0 ? 0 : Math.round((stepIdx / (STEPS.length - 1)) * 100);
      var stepsHtml = STEPS.map((step, i) => {
        var cls = i < stepIdx ? 'done' : (i === stepIdx ? 'active' : '');
        return '<div class="ot-step ' + cls + '"><div class="ot-step-dot">' + step.icon + '</div><div class="ot-step-label">' + step.label + '</div></div>';
      }).join('');
      var pillLabels = { pending: 'Pending', confirmed: 'Confirmed', preparing: 'Preparing', in_transit: 'In Transit' };
      var date    = new Date(o.created_at);
      var dateStr = date.toLocaleDateString('en-PH', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
      var total   = Number(o.total).toLocaleString('en-PH', { minimumFractionDigits: 2 });
      var itemsHtml = (o.items || []).map(item => {
        var imgHtml = item.product_image
          ? '<img class="ot-item-img" src="' + escAttr(item.product_image) + '" alt="' + escAttr(item.product_name) + '" onerror="this.style.display=\'none\';this.nextSibling.style.display=\'flex\'"><div class="ot-item-img-placeholder" style="display:none;">&#x1F357;</div>'
          : '<div class="ot-item-img-placeholder">&#x1F357;</div>';
        var subtotal = Number(item.price * item.quantity).toLocaleString('en-PH', { minimumFractionDigits: 2 });
        return '<div class="ot-item">' + imgHtml + '<div class="ot-item-info"><div class="ot-item-name">' + escHtml(item.product_name || 'Item') + '</div><div class="ot-item-qty">x' + item.quantity + '</div></div><div class="ot-item-price">&#x20B1;' + subtotal + '</div></div>';
      }).join('');
      body.innerHTML = '<div class="ot-card"><div class="ot-order-id">ORDER #' + String(o.id).padStart(7, '0') + '</div><span class="ot-status-pill pill-' + status + '">' + (pillLabels[status] || status) + '</span><div class="ot-order-meta">' + dateStr + '</div><div class="ot-progress-track"><div class="ot-line"><div class="ot-line-fill" style="width:' + fillPct + '%;"></div></div><div class="ot-steps">' + stepsHtml + '</div></div><div class="ot-items-label">Your Items</div><div class="ot-items-list">' + itemsHtml + '</div><hr class="ot-divider"><div class="ot-total-row"><span class="ot-total-label">TOTAL</span><span class="ot-total-value">&#x20B1;' + total + '</span></div></div>';
    }
    function escHtml(str) { return String(str == null ? '' : str).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c])); }
    function escAttr(str) { return String(str == null ? '' : str).replace(/["'<>&]/g, c => ({ '"': '&quot;', "'": '&#39;', '<': '&lt;', '>': '&gt;', '&': '&amp;' }[c])); }
    fetchOrders();
    setInterval(fetchOrders, 15000);
  })();
  </script>

</body>
</html>