<?php
session_start();
require 'db.php';
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
    <title>Chick Chicken</title>
  </head>

<header>
  <div class="logo">
    <h1>
      <a href="index.php">
        <img src="assets/Logo2.png" alt="ChickChicken" style="width:auto;height:45px;">
      </a>
    </h1>
  </div>

  <nav>
    <ul>
      <li><a href="aboutus.php"        class="header_button">About Us</a></li>
      <li><a href="index.php#FAQS"     class="header_button">FAQs</a></li>
      <li><a href="branch-locator.php" class="header_button">Branch Locator</a></li>

      <li>
        <?php if (isset($_SESSION['user_id'])): ?>
          <div class="nav-user-wrap" id="navUserWrap">
            <button class="nav-user-btn" id="navUserBtn" aria-expanded="false" aria-haspopup="true">
              <?= htmlspecialchars($_SESSION['first_name'] ?? $_SESSION['username'] ?? 'Account') ?>
              <svg class="nav-chevron" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M1 1L6 7L11 1" stroke="#D62828" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </button>
            <div class="nav-dropdown" role="menu">
              <a href="logout_process.php" class="logout" role="menuitem">Log Out</a>
            </div>
          </div>
        <?php else: ?>
          <a href="login.php" class="header_button">Sign In</a>
        <?php endif; ?>
      </li>

      <li><a href="orders.php" class="ordernow_button">Order Now</a></li>
    </ul>
  </nav>
</header>

<!-- About Us Section -->
  <section class="about">
    <img src="assets/Logo3.png" alt="Chick Chicken Logo" class="about-logo">
    <h1>MADE WITH <span class="red">HEART</span>. SERVED WITH <span class="red">PRIDE</span>.</h1>
    <hr>
    <div class="background">
      <img src="assets/chicken dab.png" alt="logo">
      <p>
        Chick Chicken began in 2023 as a small food stall run by a group of friends who shared one simple 
        goal — to make fried chicken that actually hits different. What started as a weekend passion project 
        quickly became a crowd favorite at local food fairs, thanks to its crispy coating, juicy meat, and 
        secret homemade seasoning that turned first-timers into regulars.
        <br><br>
        As word spread, so did our ambition. We officially became Chick Chicken, a brand built on the belief 
        that good food should bring people together — whether it’s a quick lunch, a late-night snack, or a 
        barkadahan hangout meal.
      </p>
    </div>
    <div class="vm-container">
      <div class="vision">
        <h2>Our Vision</h2>
        <p>
          To become a trusted local brand known for redefining fried chicken — blending tradition, innovation, 
          and community to create a place where everyone can share good food and good moments.
        </p>
      </div>

      <div class="mission">
        <h2>Our Mission</h2>
        <p>
          To serve freshly made, flavor-packed fried chicken that brings comfort and joy to every bite. We aim to 
          deliver quality food, warm service, and a dining experience that makes every customer feel at home — one 
          crispy piece at a time.
        </p>
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


</body>

</html>
