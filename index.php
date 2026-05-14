<?php
session_start();
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
  <!-- Bootstrap only needed for review modal on this page -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <title>Chick Chicken</title>

  <style>
    /* ── FAQ FIX ─────────────────────────────────────── */
    .faq .answer {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.35s ease, padding 0.35s ease;
      padding: 0 20px;
    }

    .faq.active .answer {
      max-height: 400px;
      padding: 10px 20px 20px;
    }

    .faq .question svg {
      transition: transform 0.3s ease;
      flex-shrink: 0;
    }

    .faq.active .question svg {
      transform: rotate(180deg);
    }
  </style>
</head>


<body>
<?php include 'nav.php'; ?>
  <div class="welcomepicture">
    <img src="assets/pamilya.png">
  </div>

  <!--WELCOME BOX-->
  <section class="welcome-box">
    <div class="logo-welcome-box">
      <img src="assets/Logo3-big.png">
    </div>
    <div class="text-welcome-box">
      <h1>Welcome to Chick Chicken!</h1>
      <p>At Chick Chicken, we believe that chicken isn't just food — it's comfort, joy, and a reason to gather around the table. That's why every meal we make is crafted with care, seasoned to perfection, and cooked fresh to bring out that irresistible crunch and tenderness in every bite.</p>
      <p>Our mission is simple: to serve chicken that makes you smile. We keep it fresh, we keep it tasty, and we keep it fun. Pair it with our sides, share it with your friends, or keep it all to yourself (we won't judge).</p>
    </div>
  </section>
  <!--END OF WELCOME BOX-->

  <!--FAQ Section-->
  <section id="FAQS" class="FAQS">
    <h1 class="title">Frequently Asked Questions</h1>

    <div class="faq">
      <div class="question">
        <h2>Where are you located?</h2>
        <svg width="15" height="10" viewBox="0 0 42 25">
          <path d="M3 3L21 21L39 3" stroke="white" stroke-width="7" stroke-linecap="round"/>
        </svg>
      </div>
      <div class="answer">
        <p>Chick Chicken is currently located in Metro Manila, serving customers across the area, to find your nearest branch of chick chicken click here or the "Branch locator" at the top.</p>
      </div>
    </div>

    <div class="faq">
      <div class="question">
        <h2>What are your operating hours?</h2>
        <svg width="15" height="10" viewBox="0 0 42 25">
          <path d="M3 3L21 21L39 3" stroke="white" stroke-width="7" stroke-linecap="round"/>
        </svg>
      </div>
      <div class="answer">
        <p>Most branches are open daily from 10 AM – 9 PM, but hours may vary by location.</p>
      </div>
    </div>

    <div class="faq">
      <div class="question">
        <h2>What are your bestsellers?</h2>
        <svg width="15" height="10" viewBox="0 0 42 25">
          <path d="M3 3L21 21L39 3" stroke="white" stroke-width="7" stroke-linecap="round"/>
        </svg>
      </div>
      <div class="answer">
        <p>Our top picks are Chick Rice, Mac &amp; Chick, and the Super Chick combo — all fan favorites for their crispy flavor and hearty goodness!</p>
      </div>
    </div>

    <div class="faq">
      <div class="question">
        <h2>Good for how many pax per order?</h2>
        <svg width="15" height="10" viewBox="0 0 42 25">
          <path d="M3 3L21 21L39 3" stroke="white" stroke-width="7" stroke-linecap="round"/>
        </svg>
      </div>
      <div class="answer">
        <p>Most of our meals are good for 1 person, but they're hearty enough to share if you're not too hungry. Combo Tenders like the Super Chick are also great for 2 people if you pair them with sides!</p>
      </div>
    </div>
  </section>
  <!--End of FAQ Section-->

  <!--FEEDBACK-->
  <section style="height: 140vh; background-color: white;">
    <div class="position-relative w-100"
      style="height: 30vh; background: url('assets/BIG CHICKEN.png') center/cover no-repeat;">
      <div class="position-absolute top-50 start-50 translate-middle text-center text-white">
        <h1 class="fw-bold mb-2" style="font-size: 2.5rem;">WE'VE GOT YOU COVERED</h1>
        <p class="fw-medium mb-0" style="font-size: 1.5rem; color: white">A Delicious Treat For Any Hungry Day!</p>
      </div>
      <img src="assets/eeping chimcken.png" alt="Sleeping Chicken" class="position-absolute start-50 translate-middle-x"
        style="bottom: -50px; width: auto; max-width: 200px; height: auto;">
    </div>

    <div class="d-flex flex-column align-items-center justify-content-center mt-5">
      <h2 class="fw-bold mb-1 text-danger">💛 4.8/5</h2>
      <p class="mb-0 fw-medium">10 reviews</p>
    </div>

    <div class="container mt-4 d-flex flex-wrap justify-content-between gap-4">
      <div class="card" style="flex: 1 1 45%; min-width: 300px;">
        <div class="card-body">
          <h5 class="card-title">John Doe</h5>
          <h6 class="card-subtitle mb-2 text-muted">⭐⭐⭐⭐⭐</h6>
          <p class="card-text">"Amazing service! The food was delicious and arrived on time. Highly recommend to anyone!"</p>
        </div>
      </div>
      <div class="card" style="flex: 1 1 45%; min-width: 300px;">
        <div class="card-body">
          <h5 class="card-title">Jane Smith</h5>
          <h6 class="card-subtitle mb-2 text-muted">⭐⭐⭐⭐</h6>
          <p class="card-text">"Very tasty meals and friendly staff. Will definitely order again."</p>
        </div>
      </div>
      <div class="card" style="flex: 1 1 45%; min-width: 300px;">
        <div class="card-body">
          <h5 class="card-title">Alex Johnson</h5>
          <h6 class="card-subtitle mb-2 text-muted">⭐⭐⭐⭐⭐</h6>
          <p class="card-text">"The best experience I've had in a long time. Great flavors and presentation."</p>
        </div>
      </div>
      <div class="card" style="flex: 1 1 45%; min-width: 300px;">
        <div class="card-body">
          <h5 class="card-title">Emily Davis</h5>
          <h6 class="card-subtitle mb-2 text-muted">⭐⭐⭐⭐</h6>
          <p class="card-text">"Good food, quick delivery, and excellent customer service. Very satisfied!"</p>
        </div>
      </div>
    </div>

    <div class="text-center d-flex align-items-center justify-content-center" style="margin-top: 60px;">
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reviewModal"
        style="background-color:#FF4C61; color:white; padding:1rem 2rem; font-size:1.25rem; border-radius:50px; border:none; display:flex; align-items:center; justify-content:center;">
        Submit Your Review
        <img src="assets/PINCIL.png" alt="icon" style="width:24px; height:24px; margin-left:10px;">
      </button>
    </div>

    <!-- REVIEW MODAL -->
    <div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content" style="border-radius:16px; overflow:hidden;">
          <div class="modal-header" style="background:#D85A30; border:none; padding:1.1rem 1.5rem;">
            <h5 class="modal-title" style="color:#fff; font-weight:600; letter-spacing:0.04em;">Submit your review</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body" id="review-form-body" style="padding:1.5rem;">
            <div class="mb-3">
              <label class="form-label" style="font-size:12px; color:#888;">Your name</label>
              <input type="text" class="form-control" id="reviewerName" placeholder="Juan dela Cruz">
            </div>
            <div class="mb-1">
              <label class="form-label" style="font-size:12px; color:#888;">Your rating</label>
              <div id="star-row" style="display:flex; gap:8px; font-size:32px; cursor:pointer;">
                <span class="rev-star" data-val="1">★</span>
                <span class="rev-star" data-val="2">★</span>
                <span class="rev-star" data-val="3">★</span>
                <span class="rev-star" data-val="4">★</span>
                <span class="rev-star" data-val="5">★</span>
              </div>
              <p id="rev-star-label" style="font-size:13px; color:#888; margin-bottom:1rem;">Tap a star to rate</p>
            </div>
            <div class="mb-3">
              <label class="form-label" style="font-size:12px; color:#888;">Your review</label>
              <textarea class="form-control" id="reviewText" rows="4" placeholder="Tell us about your experience..."></textarea>
            </div>
          </div>
          <div class="modal-footer" style="border:none; padding:0 1.5rem 1.5rem;">
            <button id="rev-submit-btn" class="btn w-100" style="background:#D85A30; color:#fff; border-radius:8px; font-weight:600;">
              Submit review
            </button>
          </div>
          <div id="review-success-body" style="display:none; text-align:center; padding:2rem;">
            <div style="font-size:48px;">🎉</div>
            <h5>Thanks for your review!</h5>
            <p style="color:#888;">Your feedback helps us serve you better.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

  <!-- ── FAQ ACCORDION ── -->
  <script>
  document.querySelectorAll('.faq .question').forEach(function (question) {
    question.addEventListener('click', function () {
      var faq = this.closest('.faq');
      faq.classList.toggle('active');
    });
  });
  </script>

  <!-- ── REVIEW MODAL ── -->
  <script>
  (function () {
    const labels = ['', 'Poor', 'Fair', 'Good', 'Great', 'Excellent!'];
    let selected = 0;
    const stars  = document.querySelectorAll('.rev-star');
    const lbl    = document.getElementById('rev-star-label');

    function highlight(val) {
      stars.forEach(s => s.style.color = +s.dataset.val <= val ? '#F5A623' : '#ddd');
    }

    stars.forEach(s => {
      s.addEventListener('mouseenter', () => highlight(+s.dataset.val));
      s.addEventListener('mouseleave', () => highlight(selected));
      s.addEventListener('click', () => { selected = +s.dataset.val; highlight(selected); lbl.textContent = labels[selected]; });
    });

    document.getElementById('rev-submit-btn').addEventListener('click', async () => {
      const name   = document.getElementById('reviewerName').value.trim();
      const review = document.getElementById('reviewText').value.trim();
      if (!name || !selected || !review) { alert('Please fill in all fields and select a rating.'); return; }
      try {
        const res  = await fetch('submit-review.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ name, rating: selected, review }) });
        const data = await res.json();
        if (data.success) {
          document.getElementById('review-form-body').style.display    = 'none';
          document.querySelector('.modal-footer').style.display         = 'none';
          document.getElementById('review-success-body').style.display = 'block';
        } else { alert('Something went wrong: ' + data.error); }
      } catch (err) { alert('Network error. Please try again.'); }
    });

    document.getElementById('reviewModal').addEventListener('hidden.bs.modal', () => {
      selected = 0; highlight(0); lbl.textContent = 'Tap a star to rate';
      document.getElementById('reviewerName').value = '';
      document.getElementById('reviewText').value   = '';
      document.getElementById('review-form-body').style.display      = 'block';
      document.querySelector('.modal-footer').style.display           = '';
      document.getElementById('review-success-body').style.display   = 'none';
    });
  })();
  </script>

  <!-- ── ORDER TRACKER WIDGET ── -->
  <style>
  @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600&family=Alegreya+Sans:wght@400;700&display=swap');

  #ot-bubble {
      position: fixed;
      bottom: 28px;
      left: 28px;
      z-index: 9999;
      font-family: 'Alegreya Sans', 'Segoe UI', sans-serif;
  }

  #ot-toggle {
      display: flex;
      align-items: center;
      gap: 10px;
      background: #1a1a1a;
      color: #f5c800;
      border: none;
      border-radius: 50px;
      padding: 12px 20px;
      font-family: 'Oswald', sans-serif;
      font-size: 14px;
      font-weight: 500;
      letter-spacing: 0.5px;
      cursor: pointer;
      box-shadow: 0 4px 20px rgba(0,0,0,0.25);
      transition: transform 0.15s, box-shadow 0.15s;
      white-space: nowrap;
  }
  #ot-toggle:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 28px rgba(0,0,0,0.3);
  }

  #ot-panel {
      position: absolute;
      bottom: 60px;
      left: 0;
      width: 340px;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 8px 40px rgba(0,0,0,0.18);
      overflow: hidden;
      display: none;
      flex-direction: column;
      max-height: 520px;
  }
  #ot-panel.open { display: flex; }

  .ot-header {
      background: #1a1a1a;
      color: #f5c800;
      padding: 14px 18px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-family: 'Oswald', sans-serif;
      font-size: 15px;
      letter-spacing: 0.5px;
      flex-shrink: 0;
  }
  .ot-header-left {
      display: flex;
      align-items: center;
      gap: 8px;
  }
  .ot-close-btn {
      background: none;
      border: none;
      color: #f5c800;
      cursor: pointer;
      font-size: 20px;
      line-height: 1;
      padding: 0;
      opacity: 0.8;
      transition: opacity 0.15s;
      font-family: sans-serif;
  }
  .ot-close-btn:hover { opacity: 1; }

  #ot-panel-body {
      overflow-y: auto;
      flex: 1;
  }

  .ot-card { padding: 16px 18px 18px; }

  .ot-order-id {
      font-family: 'Oswald', sans-serif;
      font-size: 13px;
      color: #aaa;
      letter-spacing: 0.5px;
      margin-bottom: 4px;
  }
  .ot-order-meta {
      font-size: 13px;
      color: #777;
      margin-bottom: 14px;
      line-height: 1.5;
  }
  .ot-order-meta strong { color: #1a1a1a; font-weight: 700; }

  .ot-progress-track {
      position: relative;
      padding: 8px 0 20px;
      margin-bottom: 16px;
  }
  .ot-line {
      position: absolute;
      top: 18px;
      left: 18px;
      right: 18px;
      height: 3px;
      background: #eee;
      border-radius: 2px;
      z-index: 0;
  }
  .ot-line-fill {
      height: 100%;
      background: #f5c800;
      border-radius: 2px;
      transition: width 0.5s ease;
  }
  .ot-steps {
      position: relative;
      z-index: 1;
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
  }
  .ot-step {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 6px;
      flex: 1;
  }
  .ot-step-dot {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: #eee;
      border: 3px solid #eee;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 15px;
      transition: background 0.3s, border-color 0.3s;
      color: #bbb;
  }
  .ot-step.done .ot-step-dot {
      background: #f5c800;
      border-color: #f5c800;
      color: #1a1a1a;
  }
  .ot-step.active .ot-step-dot {
      background: #1a1a1a;
      border-color: #f5c800;
      color: #f5c800;
      animation: ot-pulse 2s infinite;
  }
  .ot-step-label {
      font-size: 10px;
      font-family: 'Oswald', sans-serif;
      letter-spacing: 0.3px;
      color: #bbb;
      text-align: center;
      line-height: 1.2;
      text-transform: uppercase;
  }
  .ot-step.done .ot-step-label,
  .ot-step.active .ot-step-label { color: #1a1a1a; }

  .ot-status-pill {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      font-size: 11px;
      font-weight: 800;
      padding: 3px 10px;
      border-radius: 20px;
      text-transform: uppercase;
      letter-spacing: 0.4px;
      font-family: 'Oswald', sans-serif;
      margin-bottom: 12px;
  }
  .pill-pending    { background: #fff8e1; color: #e65c00; }
  .pill-confirmed  { background: #e8f5e9; color: #2e7d32; }
  .pill-cooking    { background: #fff3e0; color: #e65100; }
  .pill-in_transit { background: #e3f2fd; color: #1565c0; }
  .pill-cancelled  { background: #fce4ec; color: #c62828; }

  .ot-items-label {
      font-family: 'Oswald', sans-serif;
      font-size: 11px;
      letter-spacing: 0.6px;
      text-transform: uppercase;
      color: #bbb;
      margin-bottom: 10px;
  }
  .ot-items-list {
      display: flex;
      flex-direction: column;
      gap: 10px;
      margin-bottom: 14px;
  }
  .ot-item {
      display: flex;
      align-items: center;
      gap: 12px;
  }
  .ot-item-img {
      width: 48px;
      height: 48px;
      border-radius: 10px;
      object-fit: cover;
      background: #f5f5f5;
      flex-shrink: 0;
      border: 1px solid #eee;
  }
  .ot-item-img-placeholder {
      width: 48px;
      height: 48px;
      border-radius: 10px;
      background: #f5f5f5;
      border: 1px solid #eee;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      font-size: 20px;
  }
  .ot-item-info {
      flex: 1;
      min-width: 0;
  }
  .ot-item-name {
      font-size: 14px;
      font-weight: 700;
      color: #1a1a1a;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
  }
  .ot-item-qty {
      font-size: 12px;
      color: #999;
      margin-top: 2px;
  }
  .ot-item-price {
      font-family: 'Oswald', sans-serif;
      font-size: 13px;
      font-weight: 600;
      color: #555;
      white-space: nowrap;
      flex-shrink: 0;
  }

  .ot-divider {
      border: none;
      border-top: 1px solid #f0f0f0;
      margin: 12px 0;
  }
  .ot-total-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-family: 'Oswald', sans-serif;
  }
  .ot-total-label {
      font-size: 13px;
      color: #888;
      letter-spacing: 0.4px;
  }
  .ot-total-value {
      font-size: 18px;
      font-weight: 600;
      color: #1a1a1a;
  }

  .ot-state {
      padding: 32px 18px;
      text-align: center;
      font-family: 'Alegreya Sans', sans-serif;
      color: #aaa;
      font-size: 14px;
      line-height: 1.6;
  }
  .ot-state svg {
      display: block;
      margin: 0 auto 10px;
  }

  @keyframes ot-pulse {
      0%, 100% { box-shadow: 0 0 0 4px rgba(245,200,0,0.2); }
      50%       { box-shadow: 0 0 0 8px rgba(245,200,0,0.05); }
  }
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
          { key: 'confirmed',  label: 'Confirmed',  icon: '&#x2713;'  },
          { key: 'cooking',    label: 'Cooking',    icon: '&#x1F373;' },
          { key: 'in_transit', label: 'In Transit', icon: '&#x1F6F5;' },
      ];

      window.otTogglePanel = function () {
          document.getElementById('ot-panel').classList.toggle('open');
      };

      async function fetchOrders() {
          try {
              var res  = await fetch('order_tracker.php?action=active_orders');
              var data = await res.json();

              if (data.error === 'not_logged_in') {
                  document.getElementById('ot-toggle').style.display = 'none';
                  return;
              }

              var orders = data.orders || [];
              var toggle = document.getElementById('ot-toggle');

              if (orders.length === 0) {
                  toggle.style.display = 'none';
                  return;
              }

              toggle.style.display = 'flex';
              renderCard(orders[0]);
          } catch (e) {
              console.error('Order tracker error:', e);
          }
      }

      function renderCard(o) {
          var body    = document.getElementById('ot-panel-body');
          var status  = o.status;
          var stepIdx = STEPS.findIndex(function(s) { return s.key === status; });
          var fillPct = stepIdx < 0 ? 0 : Math.round((stepIdx / (STEPS.length - 1)) * 100);

          var stepsHtml = STEPS.map(function(step, i) {
              var cls = i < stepIdx ? 'done' : (i === stepIdx ? 'active' : '');
              return '<div class="ot-step ' + cls + '">'
                  + '<div class="ot-step-dot">' + step.icon + '</div>'
                  + '<div class="ot-step-label">' + step.label + '</div>'
                  + '</div>';
          }).join('');

          var pillLabels = { pending: 'Pending', confirmed: 'Confirmed', cooking: 'Cooking', in_transit: 'In Transit' };
          var date    = new Date(o.created_at);
          var dateStr = date.toLocaleDateString('en-PH', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
          var total   = Number(o.total).toLocaleString('en-PH', { minimumFractionDigits: 2 });

          var items = o.items || [];
          var itemsHtml = items.map(function(item) {
              var imgHtml;
              if (item.product_image) {
                  imgHtml = '<img class="ot-item-img" src="' + escAttr(item.product_image) + '" alt="' + escAttr(item.product_name) + '" onerror="this.style.display=\'none\';this.nextSibling.style.display=\'flex\'">'
                          + '<div class="ot-item-img-placeholder" style="display:none;">&#x1F357;</div>';
              } else {
                  imgHtml = '<div class="ot-item-img-placeholder">&#x1F357;</div>';
              }
              var subtotal = Number(item.price * item.quantity).toLocaleString('en-PH', { minimumFractionDigits: 2 });
              return '<div class="ot-item">'
                  + imgHtml
                  + '<div class="ot-item-info">'
                  + '<div class="ot-item-name">' + escHtml(item.product_name || 'Item') + '</div>'
                  + '<div class="ot-item-qty">x' + item.quantity + '</div>'
                  + '</div>'
                  + '<div class="ot-item-price">&#x20B1;' + subtotal + '</div>'
                  + '</div>';
          }).join('');

          body.innerHTML = '<div class="ot-card">'
              + '<div class="ot-order-id">ORDER #' + String(o.id).padStart(7, '0') + '</div>'
              + '<span class="ot-status-pill pill-' + status + '">' + (pillLabels[status] || status) + '</span>'
              + '<div class="ot-order-meta">' + dateStr + '</div>'
              + '<div class="ot-progress-track">'
              + '<div class="ot-line"><div class="ot-line-fill" style="width:' + fillPct + '%;"></div></div>'
              + '<div class="ot-steps">' + stepsHtml + '</div>'
              + '</div>'
              + '<div class="ot-items-label">Your Items</div>'
              + '<div class="ot-items-list">' + itemsHtml + '</div>'
              + '<hr class="ot-divider">'
              + '<div class="ot-total-row">'
              + '<span class="ot-total-label">TOTAL</span>'
              + '<span class="ot-total-value">&#x20B1;' + total + '</span>'
              + '</div>'
              + '</div>';
      }

      function escHtml(str) {
          return String(str == null ? '' : str).replace(/[&<>"']/g, function(c) {
              return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
          });
      }

      function escAttr(str) {
          return String(str == null ? '' : str).replace(/["'<>&]/g, function(c) {
              return { '"': '&quot;', "'": '&#39;', '<': '&lt;', '>': '&gt;', '&': '&amp;' }[c];
          });
      }

      fetchOrders();
      setInterval(fetchOrders, 15000);
  })();
  </script>

</body>
</html>