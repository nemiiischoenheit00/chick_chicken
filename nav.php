<?php
// nav.php — shared navigation bar
// Requires: session_start() already called in parent file
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&display=swap');

/* ── NAV ─────────────────────────────────────── */
header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 40px;
  height: 65px;
  background-color: #FFDE59;
  position: sticky;
  top: 0;
  z-index: 1000;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

header .logo h1 { margin: 0; line-height: 1; }
header .logo a  { display: flex; align-items: center; }

/* nav sits on the right */
header nav ul {
  list-style: none;
  margin: 0;
  padding: 0;
  display: flex;
  align-items: center;
  gap: 8px;
}

header nav ul li {
  position: relative;
  display: flex;
  align-items: center;
}

/* ── regular nav links ── */
header nav ul li a.header_button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  color: #D62828;
  font-size: 19px;
  font-family: 'Oswald', sans-serif;
  font-weight: 500;
  padding: 8px 16px;
  position: relative;
  white-space: nowrap;
  line-height: 1;
}

/* underline — pinned to the bottom of the link text */
header nav ul li a.header_button::after {
  content: '';
  position: absolute;
  bottom: 2px;
  left: 0;
  right: 0;
  margin: 0 14px;
  height: 2.5px;
  background: #D62828;
  border-radius: 2px;
  transform: scaleX(0);
  transform-origin: center;
  transition: transform 0.22s ease;
}
header nav ul li a.header_button:hover::after { transform: scaleX(1); }

/* ── Order Now pill ── */
header nav ul li a.ordernow_button {
  display: block;
  background-color: #D62828;
  color: #fff !important;
  padding: 9px 24px;
  border-radius: 40px;
  font-family: 'Oswald', sans-serif;
  font-size: 18px;
  font-weight: 600;
  text-decoration: none;
  white-space: nowrap;
  line-height: 1;
  margin-left: 6px;
  transition: box-shadow 0.2s, transform 0.2s;
}
header nav ul li a.ordernow_button:hover {
  box-shadow: 0 0 14px rgba(214,40,40,0.5);
  transform: translateY(-1px);
}

/* ── User dropdown wrapper ── */
.nav-user-wrap {
  position: relative;
  display: flex;
  align-items: center;
}

.nav-user-btn {
  display: flex;
  align-items: center;
  gap: 5px;
  background: none;
  border: none;
  color: #D62828;
  font-size: 19px;
  font-family: 'Oswald', sans-serif;
  font-weight: 500;
  padding: 8px 16px;
  cursor: pointer;
  white-space: nowrap;
  border-radius: 6px;
  line-height: 1;
  transition: background 0.15s;
}
.nav-user-btn:hover { background: rgba(214,40,40,0.07); }

/* chevron */
.nav-chevron {
  width: 11px;
  height: 11px;
  transition: transform 0.2s;
  flex-shrink: 0;
  margin-top: 1px;       /* optical alignment */
}
.nav-user-wrap.open .nav-chevron { transform: rotate(180deg); }

/* dropdown panel */
.nav-dropdown {
  display: none;
  position: absolute;
  top: calc(100% + 8px);
  right: 0;
  background: #fff;
  border: 1.5px solid #eee;
  border-radius: 10px;
  box-shadow: 0 8px 24px rgba(0,0,0,0.12);
  min-width: 150px;
  overflow: hidden;
  z-index: 2000;
}
.nav-user-wrap.open .nav-dropdown { display: block; }

.nav-dropdown a {
  display: block;
  padding: 11px 18px;
  font-family: 'Alegreya Sans', sans-serif;
  font-size: 15px;
  text-decoration: none;
  color: #333;
  transition: background 0.15s;
}
.nav-dropdown a:hover { background: #fff5f5; }
.nav-dropdown a.logout { color: #D62828; font-weight: 700; }

@media (max-width: 768px) {
  header { padding: 0 16px; }
  header nav ul { gap: 0; }
  header nav ul li a.header_button,
  .nav-user-btn { font-size: 15px; padding: 8px 10px; }
  header nav ul li a.header_button::after { left: 10px; right: 10px; }
  header nav ul li a.ordernow_button { font-size: 14px; padding: 8px 14px; margin-left: 2px; }
}
</style>

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

<script>
(function () {
  var wrap = document.getElementById('navUserWrap');
  var btn  = document.getElementById('navUserBtn');
  if (!wrap || !btn) return;

  btn.addEventListener('click', function (e) {
    e.stopPropagation();
    var isOpen = wrap.classList.toggle('open');
    btn.setAttribute('aria-expanded', String(isOpen));
  });

  document.addEventListener('click', function () {
    if (wrap.classList.contains('open')) {
      wrap.classList.remove('open');
      btn.setAttribute('aria-expanded', 'false');
    }
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      wrap.classList.remove('open');
      btn.setAttribute('aria-expanded', 'false');
    }
  });
})();
</script>
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 50px;
  height: 65px;
  background-color: #FFDE59;
  position: sticky;
  top: 0;
  z-index: 1000;
}

header .logo h1 { margin: 0; line-height: 1; }

header nav ul {
  list-style: none;
  margin: 0;
  padding: 0;
  display: flex;
  align-items: center;
  gap: 4px;
}

header nav ul li { position: relative; }

header nav ul li a.header_button,
header nav ul li span.header_button {
  text-decoration: none;
  color: #D62828;
  font-size: 20px;
  font-family: 'Oswald', sans-serif;
  padding: 6px 14px;
  display: block;
  position: relative;
  cursor: pointer;
  white-space: nowrap;
}

/* underline hover */
header nav ul li a.header_button::after {
  content: '';
  position: absolute;
  bottom: -2px;
  left: 14px;
  right: 14px;
  height: 3px;
  background: #D62828;
  transform: scaleX(0);
  transition: transform 0.25s ease;
  border-radius: 2px;
}
header nav ul li a.header_button:hover::after { transform: scaleX(1); }

/* Order Now button */
header nav ul li a.ordernow_button {
  background-color: #D62828;
  color: #fff;
  padding: 9px 22px;
  border-radius: 40px;
  font-family: 'Oswald', sans-serif;
  font-size: 18px;
  text-decoration: none;
  transition: box-shadow 0.2s;
  white-space: nowrap;
}
header nav ul li a.ordernow_button:hover {
  box-shadow: 0 0 12px rgba(214,40,40,0.5);
}

/* ── USER DROPDOWN ── */
.nav-user-wrap {
  position: relative;
}

.nav-user-btn {
  display: flex;
  align-items: center;
  gap: 6px;
  background: none;
  border: none;
  color: #D62828;
  font-size: 20px;
  font-family: 'Oswald', sans-serif;
  padding: 6px 14px;
  cursor: pointer;
  white-space: nowrap;
  border-radius: 6px;
  transition: background 0.15s;
}
.nav-user-btn:hover { background: rgba(214,40,40,0.08); }

/* chevron icon */
.nav-user-btn .nav-chevron {
  width: 12px;
  height: 12px;
  transition: transform 0.2s;
  flex-shrink: 0;
}
.nav-user-wrap.open .nav-chevron { transform: rotate(180deg); }

/* dropdown menu */
.nav-dropdown {
  display: none;
  position: absolute;
  top: calc(100% + 6px);
  right: 0;
  background: #fff;
  border: 1.5px solid #eee;
  border-radius: 10px;
  box-shadow: 0 8px 24px rgba(0,0,0,0.12);
  min-width: 160px;
  overflow: hidden;
  z-index: 999;
}
.nav-user-wrap.open .nav-dropdown { display: block; }

.nav-dropdown a {
  display: block;
  padding: 11px 18px;
  font-family: 'Alegreya Sans', sans-serif;
  font-size: 15px;
  text-decoration: none;
  color: #333;
  transition: background 0.15s;
}
.nav-dropdown a:hover { background: #fff5f5; }
.nav-dropdown a.logout { color: #D62828; font-weight: 700; }

@media (max-width: 768px) {
  header { padding: 0 20px; }
  header nav ul { gap: 0; }
  header nav ul li a.header_button { font-size: 16px; padding: 6px 10px; }
  header nav ul li a.ordernow_button { font-size: 15px; padding: 8px 14px; }
  .nav-user-btn { font-size: 16px; padding: 6px 10px; }
}
</style>

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
      <li><a href="aboutus.php" class="header_button">About Us</a></li>
      <li><a href="index.php#FAQS" class="header_button">FAQs</a></li>
      <li><a href="branch-locator.php" class="header_button">Branch Locator</a></li>

      <li>
        <?php if (isset($_SESSION['user_id'])): ?>
          <div class="nav-user-wrap" id="navUserWrap">
            <button class="nav-user-btn" id="navUserBtn" aria-expanded="false" aria-haspopup="true">
              <?= htmlspecialchars($_SESSION['first_name'] ?? 'Account') ?>
              <!-- chevron SVG -->
              <svg class="nav-chevron" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M1 1L6 7L11 1" stroke="#D62828" stroke-width="2" stroke-linecap="round"/>
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

<script>
(function () {
  var wrap = document.getElementById('navUserWrap');
  var btn  = document.getElementById('navUserBtn');
  if (!wrap || !btn) return;

  btn.addEventListener('click', function (e) {
    e.stopPropagation();
    var isOpen = wrap.classList.toggle('open');
    btn.setAttribute('aria-expanded', isOpen);
  });

  // Close on outside click
  document.addEventListener('click', function () {
    wrap.classList.remove('open');
    btn.setAttribute('aria-expanded', 'false');
  });

  // Close on Escape
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      wrap.classList.remove('open');
      btn.setAttribute('aria-expanded', 'false');
    }
  });
})();
</script>