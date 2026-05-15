<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thank You – Chick Chicken</title>
  <link rel="icon" type="image/png" href="assets/Logo.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@400;600;700&family=Alegreya+Sans:ital,wght@0,400;0,700;0,900;1,400&display=swap');

    :root {
      --red:    #D85A30;
      --dark:   #1a1a1a;
      --yellow: #F5C800;
      --cream:  #FFF8F0;
      --muted:  #9e9e9e;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      min-height: 100vh;
      background: #ecd579;
      font-family: 'Alegreya Sans', Georgia, serif;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
      /* warm radial glow */
      background-image:
        radial-gradient(ellipse 60% 50% at 20% 10%, rgba(216,90,48,.12) 0%, transparent 60%),
        radial-gradient(ellipse 60% 60% at 80% 90%, rgba(245,200,0,.10) 0%, transparent 60%);
    }

    /* ── card ── */
    .ty-card {
      background: #fff;
      border-radius: 24px;
      box-shadow: 0 16px 64px rgba(0,0,0,.12), 0 2px 8px rgba(0,0,0,.06);
      overflow: hidden;
      width: 100%;
      max-width: 480px;
      position: relative;
    }

    /* ── hero banner ── */
    .ty-banner {
      background: var(--dark);
      padding: 36px 32px 28px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }
    .ty-banner::before {
      content: '';
      position: absolute;
      inset: 0;
      background: repeating-linear-gradient(
        45deg,
        transparent,
        transparent 18px,
        rgba(245,200,0,.04) 18px,
        rgba(245,200,0,.04) 36px
      );
    }
    .ty-banner .logo {
      width: 80px;
      position: relative;
      z-index: 1;
      filter: drop-shadow(0 4px 12px rgba(0,0,0,.4));
    }
    .ty-banner h1 {
      font-family: 'Oswald', sans-serif;
      font-size: clamp(1.4rem, 4vw, 1.9rem);
      font-weight: 700;
      color: #fff;
      letter-spacing: 1px;
      margin-top: 14px;
      line-height: 1.15;
      position: relative;
      z-index: 1;
    }
    .ty-banner h1 span { color: var(--yellow); }
    .ty-banner .subtitle {
      color: rgba(255,255,255,.55);
      font-size: .95rem;
      margin-top: 6px;
      position: relative;
      z-index: 1;
    }

    /* ── body ── */
    .ty-body {
      padding: 28px 32px 32px;
    }

    .ty-label {
      font-family: 'Oswald', sans-serif;
      font-size: .75rem;
      letter-spacing: 1.2px;
      text-transform: uppercase;
      color: var(--muted);
      margin-bottom: 6px;
    }

    /* ── inputs ── */
    .ty-input {
      width: 100%;
      border: 1.5px solid #e8e8e8;
      border-radius: 10px;
      padding: 10px 14px;
      font-family: 'Alegreya Sans', Georgia, serif;
      font-size: 1rem;
      color: var(--dark);
      background: #fafafa;
      transition: border-color .2s, box-shadow .2s;
      outline: none;
    }
    .ty-input:focus {
      border-color: var(--red);
      box-shadow: 0 0 0 3px rgba(216,90,48,.12);
      background: #fff;
    }
    textarea.ty-input { resize: vertical; min-height: 90px; }

    /* ── star rating ── */
    .star-row {
      display: flex;
      gap: 6px;
      margin-bottom: 4px;
    }
    .star-row span {
      font-size: 34px;
      color: #e0e0e0;
      cursor: pointer;
      transition: color .15s, transform .15s;
      line-height: 1;
      user-select: none;
    }
    .star-row span.lit   { color: var(--yellow); }
    .star-row span:hover { transform: scale(1.15); }
    .star-hint {
      font-size: .85rem;
      color: var(--muted);
      min-height: 1.2em;
      margin-bottom: 20px;
      transition: color .2s;
    }

    /* ── submit btn ── */
    .ty-btn {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      width: 100%;
      padding: 13px 20px;
      background: var(--red);
      color: #fff;
      font-family: 'Oswald', sans-serif;
      font-size: 1.05rem;
      font-weight: 600;
      letter-spacing: .6px;
      border: none;
      border-radius: 50px;
      cursor: pointer;
      transition: background .2s, transform .15s, box-shadow .2s;
      margin-top: 4px;
    }
    .ty-btn:hover {
      background: #bf4924;
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(216,90,48,.35);
    }
    .ty-btn:active { transform: translateY(0); }

    /* ── home link ── */
    .ty-home {
      display: block;
      text-align: center;
      margin-top: 16px;
      font-size: .9rem;
      color: var(--muted);
      text-decoration: none;
      transition: color .2s;
    }
    .ty-home:hover { color: var(--dark); }
    .ty-home svg { margin-right: 4px; vertical-align: -2px; }

    /* ── success state ── */
    .ty-success {
      display: none;
      padding: 40px 32px;
      text-align: center;
    }
    .ty-success .confetti { font-size: 52px; }
    .ty-success h2 {
      font-family: 'Oswald', sans-serif;
      font-size: 1.7rem;
      color: var(--dark);
      margin: 12px 0 6px;
    }
    .ty-success p { color: var(--muted); font-size: .95rem; margin-bottom: 24px; }

    /* ── divider ── */
    .ty-divider {
      display: flex;
      align-items: center;
      gap: 12px;
      margin: 20px 0;
      color: #ddd;
      font-size: .75rem;
      font-family: 'Oswald', sans-serif;
      letter-spacing: 1px;
      text-transform: uppercase;
    }
    .ty-divider::before,
    .ty-divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: #ebebeb;
    }

    /* ── field spacing ── */
    .ty-field { margin-bottom: 18px; }
  </style>
</head>
<body>

<div class="ty-card">
  <!-- BANNER -->
  <div class="ty-banner">
    <img src="assets/Logo3.png" alt="Chick Chicken" class="logo">
    <h1>THANK YOU FOR<br><span>ORDERING!</span></h1>
    <p class="subtitle">Your order is being prepared with love 🍗</p>
  </div>

  <!-- FORM -->
  <div class="ty-body" id="ty-form">
    <p style="font-size:1.05rem; color:#555; margin-bottom:20px; line-height:1.55;">
      How was your experience? Leave us a quick review — it helps us serve you even better!
    </p>

    <div class="ty-field">
      <div class="ty-label">Your Name</div>
      <input type="text" id="ty-name" class="ty-input" placeholder="Juan dela Cruz" value="<?= htmlspecialchars($_GET['name'] ?? '') ?>">
    </div>

    <div class="ty-field">
      <div class="ty-label">Your Rating</div>
      <div class="star-row" id="ty-stars">
        <span data-val="1">★</span>
        <span data-val="2">★</span>
        <span data-val="3">★</span>
        <span data-val="4">★</span>
        <span data-val="5">★</span>
      </div>
      <div class="star-hint" id="ty-hint">Tap a star to rate</div>
    </div>

    <div class="ty-field">
      <div class="ty-label">Your Review</div>
      <textarea id="ty-review" class="ty-input" placeholder="Tell us about your experience..."></textarea>
    </div>

    <button class="ty-btn" id="ty-submit">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
      Submit Review
    </button>

    <div class="ty-divider">or</div>

    <a href="index.php" class="ty-home">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
      Skip &amp; go back home
    </a>
  </div>

  <!-- SUCCESS -->
  <div class="ty-success" id="ty-success">
    <div class="confetti">🎉</div>
    <h2>Review Submitted!</h2>
    <p>Thanks for sharing your experience.<br>We really appreciate the feedback!</p>
    <a href="index.php" class="ty-btn" style="text-decoration:none; display:inline-flex; width:auto; padding:13px 32px;">
      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
      Back to Home
    </a>
  </div>
</div>

<script>
(function () {
  const labels = ['', 'Poor', 'Fair', 'Good', 'Great', 'Excellent!'];
  let selected = 0;

  const stars = document.querySelectorAll('#ty-stars span');
  const hint  = document.getElementById('ty-hint');

  function highlight(val) {
    stars.forEach(s => s.classList.toggle('lit', +s.dataset.val <= val));
  }

  stars.forEach(s => {
    s.addEventListener('mouseenter', () => highlight(+s.dataset.val));
    s.addEventListener('mouseleave', () => highlight(selected));
    s.addEventListener('click', () => {
      selected = +s.dataset.val;
      highlight(selected);
      hint.textContent = labels[selected];
      hint.style.color = '#F5C800';
    });
  });

  document.getElementById('ty-submit').addEventListener('click', async () => {
    const name   = document.getElementById('ty-name').value.trim();
    const review = document.getElementById('ty-review').value.trim();

    if (!name || !selected || !review) {
      alert('Please fill in all fields and pick a rating.');
      return;
    }

    const btn = document.getElementById('ty-submit');
    btn.disabled = true;
    btn.textContent = 'Submitting…';

    try {
      const res  = await fetch('submit-review.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ name, rating: selected, review })
      });
      const data = await res.json();

      if (data.success) {
        document.getElementById('ty-form').style.display    = 'none';
        document.getElementById('ty-success').style.display = 'block';
      } else {
        alert('Something went wrong: ' + (data.error || 'Unknown error'));
        btn.disabled = false;
        btn.textContent = 'Submit Review';
      }
    } catch (err) {
      alert('Network error. Please try again.');
      btn.disabled = false;
      btn.textContent = 'Submit Review';
    }
  });
})();
</script>

</body>
</html>