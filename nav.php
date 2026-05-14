<?php ?>

<style>
*, *::before, *::after { box-sizing: border-box; }
body, html { margin: 0; padding: 0; }

/* ── NAV ─────────────────────────────────────── */
header {
  all: unset;
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

header nav ul li a.header_button {
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

/* underline — hugs just the text, not the full padding */
header nav ul li a.header_button::after {
  content: '';
  position: absolute;
  bottom: -2px;
  left: 50%;
  transform: translateX(-50%) scaleX(0);
  width: calc(100% - 28px); /* subtract left+right padding */
  height: 3px;
  background: #D62828;
  transform-origin: center;
  transition: transform 0.25s ease;
  border-radius: 2px;
}
header nav ul li a.header_button:hover::after {
  transform: translateX(-50%) scaleX(1);
}

/* Order Now button */
header nav ul li a.ordernow_button {
  background-color: #D62828;
  color: #fff;
  padding: 10px 28px;
  border-radius: 40px;
  font-family: 'Oswald', sans-serif;
  font-size: 20px;
  text-decoration: none;
  transition: box-shadow 0.2s;
  white-space: nowrap;
  margin-left: 18px;
  display: block;
}
header nav ul li a.ordernow_button:hover {
  box-shadow: 0 0 14px rgba(214,40,40,0.5);
}

/* ── USER DROPDOWN ── */
.nav-user-wrap { position: relative; }

.nav-user-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: none;
  border: none;
  outline: none;
  color: #D62828;
  font-size: 20px;
  font-family: 'Oswald', sans-serif;
  padding: 6px 14px;
  cursor: pointer;
  white-space: nowrap;
  position: relative;
  -webkit-appearance: none;
  appearance: none;
  font-size: 20px;  
  font-weight: normal;
  -webkit-font-smoothing: antialiased;
}


.nav-user-btn:hover,
.nav-user-btn:focus,
.nav-user-btn:active,
.nav-user-wrap.open .nav-user-btn {
  background: none;
}

/* underline — same tight calc trick */
.nav-user-btn::after {
  content: '';
  position: absolute;
  bottom: -2px;
  left: 50%;
  transform: translateX(-50%) scaleX(0);
  width: calc(100% - 28px);
  height: 3px;
  background: #D62828;
  transform-origin: center;
  transition: transform 0.25s ease;
  border-radius: 2px;
}
.nav-user-btn:hover::after,
.nav-user-wrap.open .nav-user-btn::after {
  transform: translateX(-50%) scaleX(1);
}

.nav-chevron {
  width: 11px;
  height: 11px;
  transition: transform 0.22s ease;
  flex-shrink: 0;
}
.nav-user-wrap.open .nav-chevron { transform: rotate(180deg); }

/* dropdown */
.nav-dropdown {
  display: none;
  position: absolute;
  top: calc(100% + 10px);
  right: 0;
  background: #fff;
  border: 1.5px solid #eee;
  border-radius: 12px;
  box-shadow: 0 10px 28px rgba(0,0,0,0.13);
  min-width: 180px;
  overflow: hidden;
  z-index: 999;
  animation: dropIn 0.18s ease;
}
@keyframes dropIn {
  from { opacity: 0; transform: translateY(-6px); }
  to   { opacity: 1; transform: translateY(0); }
}
.nav-user-wrap.open .nav-dropdown { display: block; }

.nav-dropdown-greeting {
  padding: 12px 18px 8px;
  font-family: 'Alegreya Sans', sans-serif;
  font-size: 13px;
  color: #888;
  border-bottom: 1px solid #f0f0f0;
  pointer-events: none;
}
.nav-dropdown-greeting strong {
  display: block;
  font-family: 'Oswald', sans-serif;
  font-size: 15px;
  color: #222;
  margin-top: 2px;
}

.nav-dropdown a {
  display: flex;
  align-items: center;
  gap: 9px;
  padding: 11px 18px;
  font-family: 'Alegreya Sans', sans-serif;
  font-size: 15px;
  text-decoration: none;
  color: #333;
  transition: background 0.15s;
}
.nav-dropdown a:hover { background: #fff5f5; }
.nav-dropdown a.logout {
  color: #D62828;
  font-weight: 700;
  border-top: 1px solid #f0f0f0;
}

@media (max-width: 768px) {
  header { padding: 0 20px; }
  header nav ul { gap: 0; }
  header nav ul li a.header_button { font-size: 16px; padding: 6px 10px; }
  header nav ul li a.ordernow_button { font-size: 17px; padding: 9px 20px; margin-left: 10px; }
  .nav-user-btn { font-size: 16px; padding: 6px 10px; }
}
</style>

<header>
  <div class="logo">
    <h1>
      <a href="index.php">
        <img src="assets/Logo2.png" alt="ChickChicken" style="width:auto; height:45px; display:block; margin-top:15px;">
      </a>
    </h1>
  </div>

  <nav>
    <ul>
      <li><a href="aboutus.html" class="header_button">About Us</a></li>
      <li><a href="index.php#FAQS" class="header_button">FAQs</a></li>
      <li><a href="branch-locator.html" class="header_button">Branch Locator</a></li>

      <li>
        <?php if (isset($_SESSION['user_id'])): ?>
          <?php
            $fullDisplay = $_SESSION['username'] ?? 'Account';
            $firstName   = explode(' ', trim($fullDisplay))[0];
          ?>
          <div class="nav-user-wrap" id="navUserWrap">
            <button class="nav-user-btn" id="navUserBtn" aria-expanded="false" aria-haspopup="true">
              <?= htmlspecialchars($firstName) ?>
              <svg class="nav-chevron" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M1 1L6 7L11 1" stroke="#D62828" stroke-width="2" stroke-linecap="round"/>
              </svg>
            </button>

            <div class="nav-dropdown" role="menu">
              <div class="nav-dropdown-greeting">
                Logged in as
                <strong><?= htmlspecialchars($fullDisplay) ?></strong>
              </div>
              <a href="change_profile.php" role="menuitem">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Change Profile
              </a>
              <a href="logout_process.php" class="logout" role="menuitem">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                  <polyline points="16 17 21 12 16 7"/>
                  <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                Log Out
              </a>
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

  document.addEventListener('click', function () {
    if (!wrap) return;
    wrap.classList.remove('open');
    btn.setAttribute('aria-expanded', 'false');
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      wrap.classList.remove('open');
      btn.setAttribute('aria-expanded', 'false');
    }
  });
})();
</script>