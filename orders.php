<?php
session_start();
require 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Order Now — Chick Chicken</title>
  <link rel="icon" href="assets/Logo.png"/>
  <link rel="stylesheet" href="orders.css">
  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500;600;700&family=Barlow:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&display=swap" rel="stylesheet"/>
  <style>@import url('https://fonts.googleapis.com/css2?family=Alegreya+Sans:ital,wght@0,100;0,300;0,400;0,500;0,700;0,800;0,900;1,100;1,300;1,400;1,500;1,700;1,800;1,900&family=Oswald:wght@200..700&display=swap');</style>
  <style>
    /* ═══════════════════════════════════════════
       TOKENS & RESET
    ═══════════════════════════════════════════ */
    :root {
      --mustard:   #FFDE59;
      --mustard-d: #e8c73c;
      --red:       #D62828;
      --red-d:     #a81f1f;
      --black:     #111111;
      --offwhite:  #FAF8F3;
      --cream:     #FFF7E1;
      --mid:       #6b6b6b;
      --border:    rgba(0,0,0,0.09);
      --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
      --shadow-md: 0 8px 28px rgba(0,0,0,0.13);
      --shadow-lg: 0 20px 60px rgba(0,0,0,0.22);
      --r-card:    16px;
      --r-pill:    999px;
      --oswald:    'Oswald', sans-serif;
      --barlow:    'Barlow', sans-serif;
      --transition: 0.25s cubic-bezier(0.4,0,0.2,1);
      --wine-red: #9A0404;
    }

    *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
    html { scroll-behavior:smooth; font-size:16px; }
    body {
      font-family: var(--barlow);
      background: var(--offwhite);
      color: var(--black);
      overflow-x: hidden;
    }

    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: #f0f0f0; }
    ::-webkit-scrollbar-thumb { background: #ccc; border-radius: 3px; }
    ::-webkit-scrollbar-thumb:hover { background: #aaa; }

    /* ═══════════════════════════════════════════
       PAGE HERO STRIP
    ═══════════════════════════════════════════ */
    .page-hero {
      background: var(--mustard);
      border-bottom: 3px solid var(--black);
      padding: 10px 60px 20px;
      display: flex; align-items: flex-end; gap: 20px;
      position: sticky;
      top: 65px;
      z-index: 900;
    }
    .page-hero h1 {
      font-family: var(--oswald); font-weight:700; font-size: clamp(42px, 6vw, 72px);
      line-height: 1; letter-spacing: -1px;
      color: var(--black);
    }
    .page-hero span {
      font-family: var(--oswald); font-size: 18px; font-weight:400;
      color: var(--red); margin-bottom: 10px;
    }

    /* ═══════════════════════════════════════════
       LAYOUT — hero + sidebar locked, only main scrolls
    ═══════════════════════════════════════════ */
    .shop-layout {
      display: grid;
      grid-template-columns: 220px 1fr;
      overflow: hidden;
      min-height: calc(100vh - 65px - 130px);
      height: calc(100vh - 65px - 130px);
      /* height is also refreshed by JS so it matches the viewport */
    }

    /* sidebar remains fixed inside the layout while only the main column scrolls */
    .sidebar {
      background: #fff;
      border-right: 2px solid var(--border);
      padding: 32px 0;
      overflow-y: auto;
      position: sticky;
      top: 0;
      height: 100%;
    }
    .sidebar-label {
      font-family: var(--oswald); font-size: 11px; font-weight:600;
      letter-spacing: 2px; color: var(--mid);
      padding: 0 24px 12px; text-transform:uppercase;
    }
    .sidebar a {
      display: flex; align-items: center; gap: 10px;
      padding: 11px 24px;
      font-family: var(--oswald); font-size: 17px; font-weight:500;
      color: var(--black); text-decoration:none;
      border-left: 3px solid transparent;
      transition: all var(--transition);
    }
    .sidebar a:hover, .sidebar a.active {
      background: var(--cream);
      border-left-color: var(--red);
      color: var(--red);
    }
    .sidebar a .dot {
      width:8px; height:8px; border-radius:50%;
      background: var(--mustard); border:2px solid var(--black);
      flex-shrink:0;
    }
    .sidebar a.active .dot { background: var(--red); }

    /* main — ONLY this column scrolls */
    .main-content {
      padding: 48px 52px;
      overflow-y: auto;
      height: 100%;
      min-height: 0;
    }


    /* ═══════════════════════════════════════════
       MENU GRID
    ═══════════════════════════════════════════ */
    .menu-section { margin-bottom: 64px; scroll-margin-top: 80px; }

    .menu-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 20px;
    }

    .menu-card {
      background: #fff;
      border: 2px solid var(--border);
      border-radius: var(--r-card);
      overflow: hidden;
      cursor: pointer;
      transition: transform var(--transition), box-shadow var(--transition), border-color var(--transition);
      position: relative;
    }
    .menu-card:hover {
      transform: translateY(-4px);
      box-shadow: var(--shadow-md);
      border-color: var(--mustard-d);
    }
    .menu-card:active { transform: translateY(-1px); }

    .card-img {
      width:100%; height:170px;
      background: var(--cream);
      overflow:hidden;
    }
    .card-img img {
      width:100%; height:100%; object-fit:cover; object-position:center top;
      transition: transform 0.4s ease;
    }
    .menu-card:hover .card-img img { transform: scale(1.06); }

    .card-body { padding: 14px 16px 18px; }
    .card-body h3 {
      font-family: var(--oswald); font-size:17px; font-weight:600;
      margin-bottom: 4px; line-height:1.2;
    }
    .card-price {
      font-family: var(--oswald); font-size:20px; font-weight:700;
      color: var(--red);
    }

    .card-badge {
      position:absolute; top:10px; right:10px;
      background: var(--mustard); color: var(--black);
      font-family: var(--oswald); font-size:11px; font-weight:700;
      letter-spacing:1px; padding: 3px 8px; border-radius: 4px;
      border:1.5px solid rgba(0,0,0,0.15);
    }

    /* ═══════════════════════════════════════════
       POPUP MODAL
    ═══════════════════════════════════════════ */
    .popup-backdrop {
      display:none; position:fixed; inset:0;
      background: rgba(10,10,10,0.65);
      backdrop-filter: blur(3px);
      z-index: 2000;
      justify-content:center; align-items:center;
      padding: 20px;
    }
    .popup-backdrop.open { display:flex; }

    .popup-modal {
      background:#fff;
      width:100%; max-width:820px;
      border-radius: 20px;
      overflow:hidden;
      display:flex;
      box-shadow: var(--shadow-lg);
      animation: popIn 0.28s cubic-bezier(0.34,1.56,0.64,1) both;
      max-height: 90vh;
    }
    @keyframes popIn {
      from { opacity:0; transform:scale(0.88) translateY(16px); }
      to   { opacity:1; transform:scale(1)    translateY(0); }
    }

    .popup-img {
      width: 300px; flex-shrink:0;
      background: var(--cream);
    }
    .popup-img img {
      width:100%; height:100%; object-fit:cover; object-position:center top;
    }

    .popup-body {
      flex:1; overflow-y:auto; padding: 32px 36px;
      display:flex; flex-direction:column; gap:20px;
    }

    .popup-name {
      font-family: var(--oswald); font-size:32px; font-weight:700;
      line-height:1; margin-bottom:4px;
    }
    .popup-price {
      font-family: var(--oswald); font-size:22px; font-weight:600;
      color: var(--red);
    }
    .popup-divider { height:2px; background:var(--border); }

    .opt-label {
      font-family: var(--oswald); font-size:13px; font-weight:600;
      letter-spacing:1.5px; text-transform:uppercase; color:var(--mid);
      margin-bottom:10px;
    }
    .opt-group { display:flex; flex-wrap:wrap; gap:8px; }
    .opt-btn {
      font-family:var(--barlow); font-size:14px; font-weight:500;
      background:#fff; border:2px solid #ddd; border-radius:8px;
      padding: 8px 14px; cursor:pointer;
      transition: all var(--transition);
    }
    .opt-btn:hover { border-color:#bbb; background:#f8f8f8; }
    .opt-btn.selected {
      background: var(--red); color:#fff; border-color:var(--red);
    }

    .popup-row { display:flex; gap:28px; }
    .popup-row > div { flex:1; }

    .popup-controls {
      display:flex; align-items:center; justify-content:space-between;
      margin-top:4px;
    }
    .qty-wrap {
      display:flex; align-items:center; gap:0;
      border:2px solid var(--black); border-radius: 10px; overflow:hidden;
    }
    .qty-wrap button {
      background:none; border:none;
      width:40px; height:40px;
      font-size:22px; font-weight:700; cursor:pointer;
      display:flex; align-items:center; justify-content:center;
      transition: background var(--transition);
    }
    .qty-wrap button:hover { background: var(--cream); }
    .qty-val {
      font-family:var(--oswald); font-size:18px; font-weight:600;
      width:40px; text-align:center;
      border-left:2px solid var(--black); border-right:2px solid var(--black);
      height:40px; line-height:40px;
    }

    .btn-add {
      background: var(--red); color:#fff;
      font-family:var(--oswald); font-size:17px; font-weight:600;
      letter-spacing:1px;
      border:none; border-radius: var(--r-pill);
      padding:12px 32px; cursor:pointer;
      box-shadow: 3px 3px 0 var(--red-d);
      transition: transform var(--transition), box-shadow var(--transition);
    }
    .btn-add:hover { transform:translate(-1px,-1px); box-shadow:5px 5px 0 var(--red-d); }
    .btn-add:active { transform:translate(0,0); box-shadow:2px 2px 0 var(--red-d); }

    .popup-close {
      position:absolute; top:16px; right:20px;
      background:rgba(0,0,0,0.07); border:none;
      width:36px; height:36px; border-radius:50%;
      font-size:20px; cursor:pointer; z-index:1;
      display:flex; align-items:center; justify-content:center;
      transition: background var(--transition);
    }
    .popup-close:hover { background:rgba(0,0,0,0.15); }

    /* ═══════════════════════════════════════════
       CART BUTTON (FAB)
    ═══════════════════════════════════════════ */
    .fab-cart {
      position:fixed; bottom:28px; right:28px; z-index:1500;
      background: var(--red); color:#fff;
      width:62px; height:62px; border-radius:50%;
      border:none; cursor:pointer;
      display:flex; align-items:center; justify-content:center;
      box-shadow: 4px 4px 0 var(--red-d), 0 8px 24px rgba(214,40,40,0.35);
      transition: transform var(--transition), box-shadow var(--transition);
    }
    .fab-cart:hover { transform:translate(-2px,-2px); box-shadow:6px 6px 0 var(--red-d), 0 12px 32px rgba(214,40,40,0.4); }
    .fab-cart svg { width:26px; height:26px; fill:#fff; }
    .fab-badge {
      position:absolute; top:-4px; right:-4px;
      background:var(--mustard); color:var(--black);
      font-family:var(--oswald); font-size:12px; font-weight:700;
      width:22px; height:22px; border-radius:50%;
      display:flex; align-items:center; justify-content:center;
      border:2px solid #fff;
      display:none;
    }
    .fab-badge.show { display:flex; }

    /* ═══════════════════════════════════════════
       CART DRAWER
    ═══════════════════════════════════════════ */
    .cart-backdrop {
      display:none; position:fixed; inset:0;
      background:rgba(10,10,10,0.55);
      z-index:1800;
    }
    .cart-backdrop.open { display:block; }

    .cart-drawer {
      position:fixed; top:0; right:-440px; width:420px;
      height:100vh; background:#fff; z-index:1900;
      display:flex; flex-direction:column;
      box-shadow: -4px 0 40px rgba(0,0,0,0.18);
      transition: right 0.32s cubic-bezier(0.4,0,0.2,1);
      border-left: 3px solid var(--black);
    }
    .cart-drawer.open { right:0; }

    .cart-head {
      background: var(--mustard);
      padding:20px 24px;
      border-bottom:3px solid var(--black);
      display:flex; align-items:center; justify-content:space-between;
    }
    .cart-head h2 { font-family:var(--oswald); font-size:26px; font-weight:700; }
    .cart-close-btn {
      background:none; border:2px solid var(--black);
      width:36px; height:36px; border-radius:50%;
      font-size:20px; cursor:pointer;
      display:flex; align-items:center; justify-content:center;
      font-weight:700; transition:background var(--transition);
    }
    .cart-close-btn:hover { background:rgba(0,0,0,0.08); }

    .cart-items-wrap { flex:1; overflow-y:auto; padding:16px 24px; }

    .cart-empty {
      text-align:center; padding:60px 20px;
      color: var(--mid);
    }
    .cart-empty .empty-icon { font-size:52px; margin-bottom:12px; }
    .cart-empty p { font-family:var(--oswald); font-size:18px; }

    .cart-item {
      display:flex; gap:14px; align-items:flex-start;
      padding:14px 0; border-bottom:1.5px solid var(--border);
    }
    .cart-item-img {
      width:64px; height:64px; border-radius:10px; overflow:hidden;
      background:var(--cream); flex-shrink:0;
    }
    .cart-item-img img { width:100%; height:100%; object-fit:cover; }
    .cart-item-info { flex:1; }
    .cart-item-name {
      font-family:var(--oswald); font-size:16px; font-weight:600;
      margin-bottom:2px;
    }
    .cart-item-meta {
      font-size:12px; color:var(--mid); line-height:1.4;
    }
    .cart-item-price {
      font-family:var(--oswald); font-size:16px; font-weight:700;
      color:var(--red); white-space:nowrap;
    }
    .cart-item-del {
      background:none; border:none; cursor:pointer;
      color:#ccc; font-size:18px; padding:0 4px;
      transition:color var(--transition);
    }
    .cart-item-del:hover { color:var(--red); }

    .cart-footer-wrap {
      padding:20px 24px;
      border-top:3px solid var(--black);
      background:#fff;
    }
    .cart-total-row {
      display:flex; justify-content:space-between; align-items:center;
      margin-bottom:16px;
    }
    .cart-total-label { font-family:var(--oswald); font-size:16px; color:var(--mid); }
    .cart-total-val { font-family:var(--oswald); font-size:28px; font-weight:700; color:var(--red); }
    .btn-checkout {
      width:100%; background:var(--red); color:#fff;
      font-family:var(--oswald); font-size:18px; font-weight:600;
      letter-spacing:1px; border:none; border-radius:var(--r-pill);
      padding:14px; cursor:pointer;
      box-shadow: 3px 3px 0 var(--red-d);
      transition: transform var(--transition), box-shadow var(--transition);
    }
    .btn-checkout:hover { transform:translate(-1px,-1px); box-shadow:5px 5px 0 var(--red-d); }

    /* ═══════════════════════════════════════════
       TOAST
    ═══════════════════════════════════════════ */
    .toast-wrap {
      position:fixed; bottom:104px; right:28px; z-index:3000;
      display:flex; flex-direction:column; gap:10px; pointer-events:none;
    }
    .toast {
      background:var(--black); color:#fff;
      font-family:var(--barlow); font-size:14px; font-weight:500;
      padding:12px 20px; border-radius:10px;
      box-shadow:var(--shadow-md);
      animation: toastIn 0.3s ease both;
      display:flex; align-items:center; gap:10px;
    }
    .toast.success::before { content:'✓'; color:var(--mustard); font-weight:700; }
    .toast.error::before { content:'✕'; color:#ff6b6b; font-weight:700; }
    @keyframes toastIn { from{opacity:0;transform:translateX(20px)}to{opacity:1;transform:translateX(0)} }

    /* ═══════════════════════════════════════════
       FOOTER (matching style.css)
    ═══════════════════════════════════════════ */
    .footer {
      background-color: #FFD733;
      color: #000;
      padding: 50px 0 20px;
      font-size: 18px;
      width: 100%;
    }

    .footer-container {
      width: 90%;
      max-width: 1200px;
      margin: 0 auto;
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      align-items: flex-start;
      gap: 40px;
      text-align: left;
    }

    .footer-logo {
      display: block;
      margin-bottom: 10px;
      margin-right: 60px;
    }

    .footer-info,
    .footer-links,
    .footer-section,
    .footer-logo {
      flex: 1 1 250px;
      min-width: 200px;
    }

    .footer-info h4,
    .footer-links h4,
    .footer-section h4 {
      font-size: 22px !important;
      font-weight: bold;
      margin-bottom: 14px;
      line-height: 1.2;
      font-family: "Oswald", sans-serif;
    }

    .footer-logo-img {
      display: block;
    }

    .footer-info ul,
    .footer-links ul {
      list-style: none;
      padding: 0;
      margin: 0;
      font-family: "Alegreya Sans", sans-serif;
    }

    .footer-info ul li,
    .footer-links ul li {
      margin-bottom: 8px;
    }

    .footer-info ul li a,
    .footer-links ul li a {
      color: #000;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .footer-info ul li a:hover,
    .footer-links ul li a:hover {
      color: #E53935;
    }

    .footer-section p {
      margin: 0 0 10px 0;
      font-family: "Alegreya Sans", sans-serif;
    }

    .social-icons {
      display: flex;
      gap: 10px;
      align-items: center;
      margin-top: 10px;
    }

    .social-icons img {
      width: 30px;
      height: auto;
      transition: transform 0.3s ease;
    }

    .social-icons img:hover {
      transform: scale(1.15);
    }

    .footer-bottom {
      text-align: center;
      margin-top: 30px;
      font-size: 14px;
      border-top: 1px solid rgba(0,0,0,0.2);
      padding-top: 15px;
      font-family: "Oswald", sans-serif;
    }

    /* ═══════════════════════════════════════════
       RESPONSIVE
    ═══════════════════════════════════════════ */
    @media (max-width:900px) {
      .shop-layout { grid-template-columns:1fr; }
      .sidebar { display:none; }
      .main-content { padding:32px 24px; }
      .popup-modal { flex-direction:column; }
      .popup-img { width:100%; height:220px; }
      .popup-row { flex-direction:column; gap:16px; }
      .footer-container { grid-template-columns:1fr 1fr; }
      .page-hero { padding:16px 24px; }
    }
    @media (max-width:580px) {
      .cart-drawer { width:100%; right:-100%; }
      .menu-grid { grid-template-columns:repeat(auto-fill,minmax(155px,1fr)); gap:14px; }
      .footer-container { grid-template-columns:1fr; }
    }

    /* ════════════════════════════════════════════════════════ NAV STYLES ════ */

  </style>
</head>

<body>
<?php include 'nav.php'; ?>


<!-- ═══════════════════════════════════════
     PAGE HERO
══════════════════════════════════════════ -->
<div class="page-hero">
  <h1>Our Menu</h1>
</div>

<!-- ═══════════════════════════════════════
     SHOP LAYOUT
══════════════════════════════════════════ -->
<div class="shop-layout">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sidebar-label">Categories</div>
    <a href="#mains" class="active"><span class="dot"></span>Mains</a>
    <a href="#combos"><span class="dot"></span>Combo Tenders</a>
    <a href="#sauces"><span class="dot"></span>Sauces</a>
  </aside>

  <!-- MAIN -->
  <main class="main-content">

    <!-- MAINS -->
    <section class="menu-section" id="mains">
      <div class="section-head">
        <h2>Mains</h2>
        <div class="line"></div>
      </div>
      <div class="menu-grid">

        <div class="menu-card" data-popup="popup-1">
          <span class="card-badge">BESTSELLER</span>
          <div class="card-img"><img src="menuassets/Chick_Rice.png" alt="Chick Rice" loading="lazy"/></div>
          <div class="card-body">
            <h3>Chick Rice</h3>
            <div class="card-price">₱169+</div>
          </div>
        </div>

        <div class="menu-card" data-popup="popup-2">
          <div class="card-img"><img src="menuassets/Chick_Fries.png" alt="Chick Fries" loading="lazy"/></div>
          <div class="card-body">
            <h3>Chick Fries</h3>
            <div class="card-price">₱169+</div>
          </div>
        </div>

        <div class="menu-card" data-popup="popup-3">
          <div class="card-img"><img src="menuassets/Mac_Chick.png" alt="Mac & Chick" loading="lazy"/></div>
          <div class="card-body">
            <h3>Mac &amp; Chick</h3>
            <div class="card-price">₱189+</div>
          </div>
        </div>

        <div class="menu-card" data-popup="popup-4">
          <div class="card-img"><img src="menuassets/AddChickTender.png" alt="Additional Chicken Tender" loading="lazy"/></div>
          <div class="card-body">
            <h3>Additional Chicken Tender</h3>
            <div class="card-price">₱289+</div>
          </div>
        </div>

      </div>
    </section>

    <!-- COMBO TENDERS -->
    <section class="menu-section" id="combos">
      <div class="section-head">
        <h2>Combo Tenders</h2>
        <div class="line"></div>
      </div>
      <div class="menu-grid">

        <div class="menu-card" data-popup="popup-5">
          <div class="card-img"><img src="menuassets/SuperChick.png" alt="Super Chick" loading="lazy"/></div>
          <div class="card-body">
            <h3>Super Chick</h3>
            <div class="card-price">₱339</div>
          </div>
        </div>

        <div class="menu-card" data-popup="popup-6">
          <div class="card-img"><img src="menuassets/Chick_One.png" alt="Chick One" loading="lazy"/></div>
          <div class="card-body">
            <h3>Chick One</h3>
            <div class="card-price">₱289</div>
          </div>
        </div>

        <div class="menu-card" data-popup="popup-7">
          <div class="card-img"><img src="menuassets/Chick_Two.png" alt="Chick Two" loading="lazy"/></div>
          <div class="card-body">
            <h3>Chick Two</h3>
            <div class="card-price">₱299</div>
          </div>
        </div>

        <div class="menu-card" data-popup="popup-8">
          <div class="card-img"><img src="menuassets/Chick_Five.png" alt="Chick Five" loading="lazy"/></div>
          <div class="card-body">
            <h3>Chick Five</h3>
            <div class="card-price">₱319</div>
          </div>
        </div>

      </div>
    </section>

    <!-- SAUCES -->
    <section class="menu-section" id="sauces">
      <div class="section-head">
        <h2>Sauces</h2>
        <div class="line"></div>
      </div>
      <div class="menu-grid">

        <div class="menu-card" data-popup="popup-9">
          <div class="card-img"><img src="menuassets/Sauce2.png" alt="Extra Sauce" loading="lazy"/></div>
          <div class="card-body">
            <h3>Extra Sauce</h3>
            <div class="card-price">₱40</div>
          </div>
        </div>

        <div class="menu-card" data-popup="popup-10">
          <div class="card-img"><img src="menuassets/Sauce16.png" alt="Jumbo Sauce" loading="lazy"/></div>
          <div class="card-body">
            <h3>Jumbo Sauce (16oz)</h3>
            <div class="card-price">₱179</div>
          </div>
        </div>

      </div>
    </section>

  </main>
</div>

<?php
$products = [
  ['id'=>'popup-1', 'db_id'=>1, 'name'=>'Chick Rice',               'img'=>'menuassets/Chick_Rice.png',      'price'=>169, 'has_options'=>true,  'has_mix'=>true,  'has_extra'=>true],
  ['id'=>'popup-2', 'db_id'=>2, 'name'=>'Chick Fries',              'img'=>'menuassets/Chick_Fries.png',     'price'=>169, 'has_options'=>true,  'has_mix'=>true,  'has_extra'=>true],
  ['id'=>'popup-3', 'db_id'=>3, 'name'=>'Mac & Chick',              'img'=>'menuassets/Mac_Chick.png',       'price'=>189, 'has_options'=>true,  'has_mix'=>true,  'has_extra'=>true],
  ['id'=>'popup-4', 'db_id'=>4, 'name'=>'Additional Chicken Tender','img'=>'menuassets/AddChickTender.png',  'price'=>289, 'has_options'=>true,  'has_mix'=>true,  'has_extra'=>true],
  ['id'=>'popup-5', 'db_id'=>5, 'name'=>'Super Chick',              'img'=>'menuassets/SuperChick.png',      'price'=>339, 'has_options'=>true,  'has_mix'=>true,  'has_extra'=>true],
  ['id'=>'popup-6', 'db_id'=>6, 'name'=>'Chick One',               'img'=>'menuassets/Chick_One.png',       'price'=>289, 'has_options'=>true,  'has_mix'=>true,  'has_extra'=>true],
  ['id'=>'popup-7', 'db_id'=>7, 'name'=>'Chick Two',               'img'=>'menuassets/Chick_Two.png',       'price'=>299, 'has_options'=>true,  'has_mix'=>true,  'has_extra'=>true],
  ['id'=>'popup-8', 'db_id'=>8, 'name'=>'Chick Five',              'img'=>'menuassets/Chick_Five.png',      'price'=>319, 'has_options'=>true,  'has_mix'=>true,  'has_extra'=>true],
  ['id'=>'popup-9', 'db_id'=>9, 'name'=>'Extra Sauce',             'img'=>'menuassets/Sauce2.png',          'price'=>40,  'has_options'=>false, 'has_mix'=>false, 'has_extra'=>false],
  ['id'=>'popup-10','db_id'=>10,'name'=>'Jumbo Sauce (16oz)',       'img'=>'menuassets/Sauce16.png',         'price'=>179, 'has_options'=>false, 'has_mix'=>false, 'has_extra'=>false],
];

foreach ($products as $p):
?>
<div class="popup-backdrop" id="<?= $p['id'] ?>" data-db-id="<?= $p['db_id'] ?>" data-base-price="<?= $p['price'] ?>">
  <div class="popup-modal">
    <div class="popup-img">
      <img src="<?= $p['img'] ?>" alt="<?= htmlspecialchars($p['name']) ?>"/>
    </div>
    <div class="popup-body" style="position:relative;">
      <button class="popup-close" aria-label="Close">✕</button>

      <div>
        <div class="popup-name"><?= htmlspecialchars($p['name']) ?></div>
        <div class="popup-price">₱<?= $p['price'] ?>+</div>
      </div>
      <div class="popup-divider"></div>

      <?php if ($p['has_options']): ?>
      <div>
        <div class="opt-label">Size / Option</div>
        <div class="opt-group" data-group="option">
          <button class="opt-btn">Solo <small style="opacity:.6">(600ml)</small></button>
          <button class="opt-btn">Double <small style="opacity:.6">(1000ml)</small></button>
        </div>
      </div>
      <?php endif; ?>

      <div class="popup-row">
        <div>
          <div class="opt-label">Sauce</div>
          <div class="opt-group" data-group="sauce">
            <button class="opt-btn">Garlic Mayo</button>
            <button class="opt-btn">Chick Sauce</button>
            <button class="opt-btn">Cheese Sauce</button>
          </div>
        </div>
        <?php if ($p['has_extra']): ?>
        <div>
          <div class="opt-label">Extra Flavor <span style="color:var(--red);font-size:11px;">+₱20</span></div>
          <div class="opt-group" data-group="extra">
            <button class="opt-btn">Hot Buffalo</button>
            <button class="opt-btn">Salted Egg</button>
          </div>
        </div>
        <?php endif; ?>
      </div>

      <?php if ($p['has_mix']): ?>
      <div>
        <div class="opt-label">Sauce Preference</div>
        <div class="opt-group" data-group="mix">
          <button class="opt-btn">Mixed</button>
          <button class="opt-btn">Separate</button>
        </div>
      </div>
      <?php endif; ?>

      <div class="popup-divider"></div>

      <div class="popup-controls">
        <div class="qty-wrap">
          <button class="qty-minus">−</button>
          <div class="qty-val">1</div>
          <button class="qty-plus">+</button>
        </div>
        <button class="btn-add" data-db-id="<?= $p['db_id'] ?>">Add to Cart</button>
      </div>

    </div>
  </div>
</div>
<?php endforeach; ?>

<!-- ═══════════════════════════════════════
     FAB + CART DRAWER
══════════════════════════════════════════ -->
<button class="fab-cart" id="fab-cart" aria-label="View Cart">
  <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
    <path d="M7 18c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zM5.17 5H19l-1.68 8.39a2 2 0 0 1-1.97 1.61H8.65a2 2 0 0 1-1.97-1.68L5.17 5zM3 3H1V1H3v2zm0 2l1.5 7.5"/>
    <path d="M3 5H1" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
    <path d="M3 3h17l-1.68 8.39A2 2 0 0 1 16.35 13H8.65a2 2 0 0 1-1.97-1.68L4.82 3H3" stroke="#fff" stroke-width="1.5" fill="none" stroke-linejoin="round"/>
    <circle cx="9" cy="20" r="1.5" fill="#fff"/>
    <circle cx="17" cy="20" r="1.5" fill="#fff"/>
  </svg>
  <span class="fab-badge" id="fab-badge">0</span>
</button>

<div class="cart-backdrop" id="cart-backdrop"></div>
<div class="cart-drawer" id="cart-drawer">
  <div class="cart-head">
    <h2>My Cart</h2>
    <button class="cart-close-btn" id="cart-close">✕</button>
  </div>
  <div class="cart-items-wrap" id="cart-items-wrap">
    <div class="cart-empty" id="cart-empty">
      <div class="empty-icon">🛒</div>
      <p>Your cart is empty</p>
    </div>
  </div>
  <div class="cart-footer-wrap">
    <div class="cart-total-row">
      <span class="cart-total-label">Total</span>
      <span class="cart-total-val" id="cart-total">₱0</span>
    </div>
    <button class="btn-checkout" id="btn-checkout">CHECKOUT</button>
  </div>
</div>

<!-- Toast container -->
<div class="toast-wrap" id="toast-wrap"></div>

<!-- ═══════════════════════════════════════
     FOOTER (matching index.php)
══════════════════════════════════════════ -->
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
        <li><a href="aboutus.php">About Us</a></li>
        <li><a href="index.php#FAQS">FAQs</a></li>
        <li><a href="branch-locator.php">Branch Locator</a></li>
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

<!-- Scripts -->
<script>
document.addEventListener("DOMContentLoaded", () => {

  /* ─── POPUP OPEN/CLOSE ──────────────────── */
  document.querySelectorAll(".menu-card").forEach(card => {
    card.addEventListener("click", () => {
      const id = card.dataset.popup;
      const popup = document.getElementById(id);
      if (!popup) return;
      // reset
      popup.querySelectorAll(".opt-btn").forEach(b => b.classList.remove("selected"));
      popup.querySelector(".qty-val").textContent = "1";
      popup.classList.add("open");
      document.body.style.overflow = "hidden";
    });
  });

  document.querySelectorAll(".popup-backdrop").forEach(backdrop => {
    // close on bg click
    backdrop.addEventListener("click", e => {
      if (e.target === backdrop) closePopup(backdrop);
    });
    // close btn
    backdrop.querySelector(".popup-close").addEventListener("click", () => closePopup(backdrop));

    // single-select per group
    backdrop.querySelectorAll("[data-group]").forEach(group => {
      group.querySelectorAll(".opt-btn").forEach(btn => {
        btn.addEventListener("click", () => {
          group.querySelectorAll(".opt-btn").forEach(b => b.classList.remove("selected"));
          btn.classList.add("selected");
        });
      });
    });

    // qty
    const qtyVal  = backdrop.querySelector(".qty-val");
    backdrop.querySelector(".qty-minus").addEventListener("click", () => {
      const v = parseInt(qtyVal.textContent);
      if (v > 1) qtyVal.textContent = v - 1;
    });
    backdrop.querySelector(".qty-plus").addEventListener("click", () => {
      qtyVal.textContent = parseInt(qtyVal.textContent) + 1;
    });

    // add to cart
    backdrop.querySelector(".btn-add").addEventListener("click", () => {
      addToCart(backdrop);
    });
  });

  function closePopup(backdrop) {
    backdrop.classList.remove("open");
    document.body.style.overflow = "";
  }

  /* ─── CART DRAWER ────────────────────────── */
  const fabCart      = document.getElementById("fab-cart");
  const cartDrawer   = document.getElementById("cart-drawer");
  const cartBackdrop = document.getElementById("cart-backdrop");
  const cartClose    = document.getElementById("cart-close");

  fabCart.addEventListener("click", () => { openCart(); loadCart(); });
  cartClose.addEventListener("click", closeCart);
  cartBackdrop.addEventListener("click", closeCart);

  function openCart()  { cartDrawer.classList.add("open"); cartBackdrop.classList.add("open"); document.body.style.overflow="hidden"; }
  function closeCart() { cartDrawer.classList.remove("open"); cartBackdrop.classList.remove("open"); document.body.style.overflow=""; }

  /* ─── TOAST ──────────────────────────────── */
  function toast(msg, type="success") {
    const wrap = document.getElementById("toast-wrap");
    const el = document.createElement("div");
    el.className = `toast ${type}`;
    el.textContent = msg;
    wrap.appendChild(el);
    setTimeout(() => el.remove(), 3000);
  }

  /* ─── ADD TO CART (fetch → PHP) ─────────── */
  async function addToCart(popup) {
    const dbId     = parseInt(popup.dataset.dbId);
    const qty      = parseInt(popup.querySelector(".qty-val").textContent);
    const option   = popup.querySelector('[data-group="option"] .selected')?.textContent.trim().replace(/\s+/g,' ') || "";
    const sauce    = popup.querySelector('[data-group="sauce"] .selected')?.textContent.trim()  || "";
    const extra    = popup.querySelector('[data-group="extra"] .selected')?.textContent.trim()  || "";
    const mix      = popup.querySelector('[data-group="mix"] .selected')?.textContent.trim()    || "";

    try {
      const res  = await fetch("add_to_cart.php", {
        method: "POST",
        headers: {"Content-Type":"application/json"},
        body: JSON.stringify({ product_id:dbId, quantity:qty, option, sauce, extra, mix })
      });
      const data = await res.json();

      if (data.error === "not_logged_in") {
        toast("Please sign in to add items.", "error");
        setTimeout(() => window.location.href = "login.php", 1400);
        return;
      }
      if (data.success) {
        closePopup(popup);
        toast("Added to cart!");
        updateBadge();
      } else {
        toast(data.error || "Something went wrong.", "error");
      }
    } catch(e) {
      toast("Network error. Try again.", "error");
    }
  }

  /* ─── LOAD CART ITEMS ────────────────────── */
  async function loadCart() {
    const wrap  = document.getElementById("cart-items-wrap");
    const empty = document.getElementById("cart-empty");
    const totalEl = document.getElementById("cart-total");

    wrap.innerHTML = '<div style="padding:40px;text-align:center;color:#bbb;font-family:var(--oswald);">Loading…</div>';

    try {
      const res   = await fetch("get_cart.php");
      const items = await res.json();

      if (!items.length) {
        wrap.innerHTML = `<div class="cart-empty"><div class="empty-icon">🛒</div><p>Your cart is empty</p></div>`;
        totalEl.textContent = "₱0";
        updateBadge(0);
        return;
      }

      let total = 0;
      wrap.innerHTML = "";

      items.forEach(item => {
        const extraCost  = item.extra_flavor ? 20 : 0;
        const lineTotal  = (parseFloat(item.price) + extraCost) * parseInt(item.quantity);
        total += lineTotal;

        const meta = [item.option_selected, item.sauce, item.extra_flavor, item.mix_preference]
                       .filter(Boolean).join(" · ");

        const div = document.createElement("div");
        div.className = "cart-item";
        div.innerHTML = `
          <div class="cart-item-img">
            <img src="${item.image || 'menuassets/Chick_Rice.png'}" alt="${item.name}" loading="lazy"/>
          </div>
          <div class="cart-item-info">
            <div class="cart-item-name">${item.name} × ${item.quantity}</div>
            ${meta ? `<div class="cart-item-meta">${meta}</div>` : ""}
          </div>
          <div style="display:flex;flex-direction:column;align-items:flex-end;gap:8px;">
            <div class="cart-item-price">₱${lineTotal}</div>
            <button class="cart-item-del" data-id="${item.id}" title="Remove">✕</button>
          </div>
        `;
        div.querySelector(".cart-item-del").addEventListener("click", () => removeItem(item.id));
        wrap.appendChild(div);
      });

      totalEl.textContent = `₱${total.toFixed(0)}`;
      updateBadge(items.reduce((s,i) => s + parseInt(i.quantity), 0));
    } catch(e) {
      wrap.innerHTML = `<div style="padding:30px;text-align:center;color:#bbb;">Could not load cart.</div>`;
    }
  }

  /* ─── REMOVE ITEM ────────────────────────── */
  async function removeItem(cartId) {
    try {
      await fetch("remove_from_cart.php", {
        method:"POST",
        headers:{"Content-Type":"application/json"},
        body: JSON.stringify({cart_id: cartId})
      });
      loadCart();
    } catch(e) { toast("Error removing item.", "error"); }
  }

  /* ─── BADGE COUNT ────────────────────────── */
  async function updateBadge(count) {
    const badge = document.getElementById("fab-badge");
    if (count === undefined) {
      try {
        const res   = await fetch("get_cart.php");
        const items = await res.json();
        count = items.reduce((s,i) => s + parseInt(i.quantity), 0);
      } catch(e) { return; }
    }
    badge.textContent = count;
    badge.classList.toggle("show", count > 0);
  }

  /* ─── CHECKOUT ───────────────────────────── */
  document.getElementById("btn-checkout").addEventListener("click", () => {
    window.location.href = "checkout.php";
  });

  /* ─── SIDEBAR ACTIVE STATE ON SCROLL ──────── */
  const sections = document.querySelectorAll(".menu-section");
  const sideLinks = document.querySelectorAll(".sidebar a");
  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        sideLinks.forEach(a => a.classList.remove("active"));
        const link = document.querySelector(`.sidebar a[href="#${entry.target.id}"]`);
        if (link) link.classList.add("active");
      }
    });
  }, { rootMargin: "-40% 0px -50% 0px" });
  sections.forEach(s => observer.observe(s));

  /* ─── INIT ───────────────────────────────── */
  updateBadge();

  /* ─── LOCK LAYOUT HEIGHT (hero + sidebar fixed, only main scrolls) ─── */
  function lockLayout() {
    const header = document.querySelector('header');
    const hero   = document.querySelector('.page-hero');
    const layout = document.querySelector('.shop-layout');
    if (!layout) return;
    const headerH = header ? header.offsetHeight : 65;
    const heroH   = hero   ? hero.offsetHeight   : 0;
    layout.style.height = (window.innerHeight - headerH - heroH) + 'px';
  }
  lockLayout();
  window.addEventListener('resize', lockLayout);
});
</script>

<!-- ORDER TRACKER WIDGET -->
<!-- Drop this snippet just before </body> on any page. Zero dependencies. -->

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

/* Scrollable body */
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

/* Progress bar */
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

/* Status pill */
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

/* Items list */
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

/* Divider + total */
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

/* Loading / empty state */
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

          // 🔥 FILTER OUT COMPLETED ORDERS
          orders = orders.filter(order => order.status !== 'completed');

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

        // Build items list
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
<!-- END ORDER TRACKER WIDGET -->

</body>
</html>