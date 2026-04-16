<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="icon" type="Image" href="assets/Logo.png"/>
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="style.css">
    <style>@import url('https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap');</style>
    <style>@import url('https://fonts.googleapis.com/css2?family=Alegreya+Sans:ital,wght@0,100;0,300;0,400;0,500;0,700;0,800;0,900;1,100;1,300;1,400;1,500;1,700;1,800;1,900&family=Oswald:wght@200..700&display=swap');</style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>Chick Chicken</title>
  </head>

<header>
  <div class="logo">
    <h1>
      <a href="index.php"><img src="assets/Logo2.png" alt="ChickChicken" style="width: auto; height: 45px" /></a>
    </h1>
  </div>

  <div class="navi--header">
    <nav>
      <ul>
        <li><a href="aboutus.html" class="header_button">About Us</a></li>
        <li><a href="#FAQS" class="header_button">FAQs</a></li>
        <li><a href="branch-locator.html" class="header_button">Branch Locator</a></li>

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

        <li><a href="orders.php" class="ordernow_button">Order Now</a></li>
      </ul>
    </nav>
  </div>
</header>

<body>

  <div class="welcomepicture">
    <img src="assets\pamilya.png">
  </div>


<!--WELCOME BOX-->

<section class="welcome-box">
  <div class="logo-welcome-box">
    <img src="assets\Logo3-big.png">
  </div>
  <div class="text-welcome-box">
    <h1>Welcome to Chick Chicken!</h1>
    <p>At Chick Chicken, we believe that chicken isn’t just food — it’s comfort, joy, and a reason to gather around the table. That’s why every meal we make is crafted with care, seasoned to perfection, and cooked fresh to bring out that irresistible crunch and tenderness in every bite.</p>
    <p>Our mission is simple: to serve chicken that makes you smile. We keep it fresh, we keep it tasty, and we keep it fun. Pair it with our sides, share it with your friends, or keep it all to yourself (we won’t judge).</p>
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
        <p>
          Chick Chicken is currently located in Metro Manila, serving customers 
          across the area, to find your nearest branch of chick chicken click here 
          or the “Branch locator” at the top.
        </p>
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
        <p>
          Most branches are open daily from 10 AM – 9 PM, but hours may vary by location.
        </p>
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
        <p>
          Our top picks are Chick Rice, Mac & Chick, and the Super Chick combo — all 
          fan favorites for their crispy flavor and hearty goodness!
        </p>
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
        <p>
          Most of our meals are good for 1 person, but they're hearty enough to 
          share if you're not too hungry. Combo Tenders like the Super Chick are 
          also great for 2 people if you pair them with sides!
        </p>
      </div>
    </div>
</section>
    
<!--End of FAQ Section-->

  <!--FEEDBACK-->  
<section style="height: 140vh; background-color: white;">
  <div class="position-relative w-100"
    style="height: 30vh; background: url('assets/BIG CHICKEN.png') center/cover no-repeat;">
    <div class="position-absolute top-50 start-50 translate-middle text-center text-white">
      <h1 class="fw-bold mb-2" style="font-size: 2.5rem;">WE’VE GOT YOU COVERED</h1>
      <p class="fw-medium mb-0 " style="font-size: 1.5rem; color: white">A Delicious Treat For Any Hungry Day!</p>
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
        <p class="card-text">"Amazing service! The food was delicious and arrived on time. Highly recommend to anyone!"
        </p>
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
      style="background-color:#FF4C61; color:white; padding:1rem 2rem; font-size:1.25rem; border-radius:50px; border:none; display: flex; align-items: center; justify-content: center;">
      Submit Your Review
      <img src="assets/PINCIL.png" alt="icon" style="width:24px; height:24px; margin-left: 10px;">
    </button>
  </div>


  <!-- MODAL -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    
    <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
      
      <!-- HEADER -->
      <div class="modal-header" style="background: #D85A30; border: none; padding: 1.1rem 1.5rem;">
        <h5 class="modal-title" style="color: #fff; font-weight: 600; letter-spacing: 0.04em;">
          Submit your review
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- FORM BODY -->
      <div class="modal-body" id="review-form-body" style="padding: 1.5rem;">
        
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

          <p id="rev-star-label" style="font-size:13px; color:#888; margin-bottom:1rem;">
            Tap a star to rate
          </p>
        </div>

        <div class="mb-3">
          <label class="form-label" style="font-size:12px; color:#888;">Your review</label>
          <textarea class="form-control" id="reviewText" rows="4"
            placeholder="Tell us about your experience..."></textarea>
        </div>

      </div>

      <!-- FOOTER -->
      <div class="modal-footer" style="border:none; padding: 0 1.5rem 1.5rem;">
        <button id="rev-submit-btn" class="btn w-100"
          style="background:#D85A30; color:#fff; border-radius:8px; font-weight:600;">
          Submit review
        </button>
      </div>

      <!-- SUCCESS STATE -->
      <div id="review-success-body"
        style="display:none; text-align:center; padding:2rem;">
        <div style="font-size:48px;">🎉</div>
        <h5>Thanks for your review!</h5>
        <p style="color:#888;">Your feedback helps us serve you better.</p>
      </div>

    </div>

  </div>
</div>
</section>




  <!-- footer ngani -->
<footer class="footer">
  <div class="footer-container">

  <div class="footer-logo">
      <img src="assets/Logo3.png" alt="Chick Chicken Logo" class="footer-logo-img">
    </div>

    <div class="footer-links">
      <h4>Quick Links</h4>
      <ul>
        <li><a href="orders.html">Menu</a></li>
      </ul>
    </div>

    <div class="footer-info">
      <h4>Information</h4>
      <ul>
                <li><a href="aboutus.html">About Us</a></li> 
                <li><a href="index.html#FAQS">FAQs</a></li>
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

  <div class="footer-bottom">
    © 2025 Chick Chicken. All rights reserved.
  </div>
</footer>
<!-- end of footer ngani -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
<script>
(function () {
  const labels  = ['', 'Poor', 'Fair', 'Good', 'Great', 'Excellent!'];
  let selected  = 0;
  const stars   = document.querySelectorAll('.rev-star');
  const lbl     = document.getElementById('rev-star-label');

  function highlight(val) {
    stars.forEach(s => s.style.color = +s.dataset.val <= val ? '#F5A623' : '#ddd');
  }

  stars.forEach(s => {
    s.addEventListener('mouseenter', () => highlight(+s.dataset.val));
    s.addEventListener('mouseleave', () => highlight(selected));
    s.addEventListener('click', () => {
      selected = +s.dataset.val;
      highlight(selected);
      lbl.textContent = labels[selected];
    });
  });

  document.getElementById('rev-submit-btn').addEventListener('click', async () => {
    const name   = document.getElementById('reviewerName').value.trim();
    const review = document.getElementById('reviewText').value.trim();

    if (!name || !selected || !review) {
      alert('Please fill in all fields and select a rating.');
      return;
    }

    try {
      const res  = await fetch('submit-review.php', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify({ name, rating: selected, review })
      });
      const data = await res.json();

      if (data.success) {
        document.getElementById('review-form-body').style.display = 'none';
        document.querySelector('.modal-footer').style.display      = 'none';
        document.getElementById('review-success-body').style.display = 'block';
      } else {
        alert('Something went wrong: ' + data.error);
      }
    } catch (err) {
      alert('Network error. Please try again.');
    }
  });

  // reset form when modal is closed
  document.getElementById('reviewModal').addEventListener('hidden.bs.modal', () => {
    selected = 0;
    highlight(0);
    lbl.textContent = 'Tap a star to rate';
    document.getElementById('reviewerName').value = '';
    document.getElementById('reviewText').value   = '';
    document.getElementById('review-form-body').style.display      = 'block';
    document.querySelector('.modal-footer').style.display           = '';
    document.getElementById('review-success-body').style.display   = 'none';
  });
})();
</script>
<script src="script.js"></script>
</body>
</html>