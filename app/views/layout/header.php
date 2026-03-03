<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Pizz_a64 – Artisan Pizza Hyderabad</title>
  <?php
  $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
  $host     = $_SERVER['HTTP_HOST'];
  $script   = str_replace('\\','/',dirname($_SERVER['SCRIPT_NAME']));
  $base_url = rtrim($protocol.'://'.$host.$script,'/');
  ?>
  <link rel="stylesheet" href="<?= $base_url ?>/public/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    /* ── Reset / base ────────────────────────────────────────────── */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Segoe UI', sans-serif; }
    .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
    a { text-decoration: none; }

    /* ── Promo banner ────────────────────────────────────────────── */
    .promo-bar {
      background: linear-gradient(90deg, #c0392b, #e74c3c);
      color: #fff;
      text-align: center;
      padding: 9px 48px;
      font-size: .85rem;
      font-weight: 500;
      letter-spacing: .3px;
      position: relative;
    }
    .promo-bar .promo-code {
      background: rgba(255,255,255,.25);
      border: 1px dashed rgba(255,255,255,.7);
      border-radius: 4px;
      padding: 1px 7px;
      font-weight: 700;
      letter-spacing: 1px;
      margin: 0 3px;
    }
    .promo-bar .promo-close {
      position: absolute;
      right: 14px; top: 50%;
      transform: translateY(-50%);
      background: none; border: none;
      color: #fff; cursor: pointer;
      font-size: .9rem; opacity: .8;
      transition: opacity .2s;
    }
    .promo-bar .promo-close:hover { opacity: 1; }

    /* ── Top bar ─────────────────────────────────────────────────── */
    .top-bar {
      background: #1a1a1a;
      color: #aaa;
      font-size: .78rem;
      padding: 7px 0;
    }
    .top-bar .container {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .tb-info { display: flex; gap: 20px; }
    .tb-info span { display: flex; align-items: center; gap: 6px; }
    .tb-info i { color: #e74c3c; font-size: .8rem; }
    .tb-right { display: flex; align-items: center; gap: 8px; }
    .tb-right a { color: #ccc; transition: color .2s; }
    .tb-right a:hover { color: #e74c3c; }
    .tb-right a i { margin-right: 3px; }
    .tb-sep { color: #444; }
    .tb-greeting { color: #ccc; opacity: .85; }
    @media(max-width:640px){ .hide-sm { display: none !important; } }

    /* ── Main header ─────────────────────────────────────────────── */
    .header {
      background: #fff;
      box-shadow: 0 2px 12px rgba(0,0,0,.10);
      position: sticky;
      top: 0;
      z-index: 1000;
      transition: box-shadow .3s;
    }
    .header.scrolled { box-shadow: 0 4px 20px rgba(0,0,0,.15); }

    .navbar-inner {
      display: flex;
      align-items: center;
      justify-content: space-between;
      height: 72px;
      gap: 16px;
    }

    /* Left nav */
    .nav-links { display: flex; gap: 4px; }
    .nav-links a {
      color: #333;
      font-weight: 600;
      font-size: .9rem;
      padding: 6px 14px;
      border-radius: 50px;
      transition: background .2s, color .2s;
    }
    .nav-links a:hover,
    .nav-links a.active {
      background: #e74c3c;
      color: #fff;
    }

    /* Center logo */
    .logo-block { text-align: center; flex-shrink: 0; }
    .logo-block a { text-decoration: none; }
    .logo-name {
      font-size: 1.55rem;
      font-weight: 800;
      color: #1a1a1a;
      line-height: 1.1;
    }
    .logo-name span { color: #e74c3c; }
    .logo-sub {
      font-size: .68rem;
      letter-spacing: 2.5px;
      text-transform: uppercase;
      color: #888;
      margin-top: 1px;
    }

    /* Right side */
    .nav-right {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .h-hours {
      font-size: .7rem;
      color: #888;
      line-height: 1.4;
      text-align: right;
    }
    .h-phone {
      font-size: .88rem;
      font-weight: 700;
      color: #1a1a1a;
      white-space: nowrap;
    }
    .icon-btn {
      position: relative;
      color: #333;
      font-size: 1.1rem;
      padding: 6px;
      border-radius: 50%;
      transition: color .2s, background .2s;
      display: flex;
    }
    .icon-btn:hover { color: #e74c3c; background: #ffeaea; }
    .dot {
      position: absolute;
      top: 0; right: 0;
      background: #e74c3c;
      color: #fff;
      font-size: .6rem;
      font-weight: 700;
      width: 16px; height: 16px;
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      border: 2px solid #fff;
    }

    /* Hamburger */
    .hamburger {
      display: none;
      flex-direction: column;
      justify-content: space-between;
      width: 24px; height: 18px;
      background: none; border: none;
      cursor: pointer; padding: 0;
    }
    .hamburger span {
      display: block;
      height: 2px;
      background: #333;
      border-radius: 2px;
      transition: transform .3s, opacity .3s, width .3s;
      transform-origin: left center;
    }
    /* Open state */
    .hamburger.open span:nth-child(1) { transform: rotate(45deg) translate(1px,-1px); }
    .hamburger.open span:nth-child(2) { opacity: 0; width: 0; }
    .hamburger.open span:nth-child(3) { transform: rotate(-45deg) translate(1px,1px); }

    /* ── Mobile nav ──────────────────────────────────────────────── */
    .mobile-nav {
      display: none;
      flex-direction: column;
      background: #fff;
      border-top: 1px solid #f0f0f0;
      padding: 12px 20px 16px;
      gap: 2px;
      /* slide animation */
      max-height: 0;
      overflow: hidden;
      transition: max-height .35s ease, padding .35s ease;
    }
    .mobile-nav.open {
      display: flex;
      max-height: 500px;
      padding: 12px 20px 16px;
    }
    .mobile-nav a {
      color: #333;
      font-weight: 600;
      font-size: .92rem;
      padding: 10px 12px;
      border-radius: 8px;
      transition: background .2s, color .2s;
    }
    .mobile-nav a:hover,
    .mobile-nav a.active { background: #ffeaea; color: #e74c3c; }
    .mobile-nav .mob-divider {
      height: 1px;
      background: #f0f0f0;
      margin: 6px 0;
    }

    /* ── Responsive breakpoints ──────────────────────────────────── */
    @media(max-width:900px) {
      .nav-links, .h-hours, .h-phone { display: none; }
      .hamburger { display: flex; }
    }
    @media(max-width:540px) {
      .logo-name { font-size: 1.25rem; }
      .logo-sub  { display: none; }
    }
  </style>
</head>
<body>

<!-- ═══ PROMO BANNER ═══════════════════════════════════════════════════════ -->
<div class="promo-bar" id="promoBanner">
  <i class="fas fa-fire"></i>
  Weekend Special — Buy 1 Large Pizza, Get 1 Medium Free!
  Use code: <span class="promo-code">PIZZA50</span>
  <button class="promo-close" onclick="this.closest('#promoBanner').style.display='none'" aria-label="Close">
    <i class="fas fa-times"></i>
  </button>
</div>

<!-- ═══ TOP BAR ════════════════════════════════════════════════════════════ -->
<div class="top-bar">
  <div class="container">
    <div class="tb-info">
      <span><i class="fas fa-phone"></i>+91 98765 43210</span>
      <span class="hide-sm"><i class="fas fa-clock"></i>10AM – 11PM Daily</span>
      <span class="hide-sm"><i class="fas fa-map-marker-alt"></i>Banjara Hills, Hyderabad</span>
    </div>
    <div class="tb-right">
      <?php if (isset($_SESSION['user_id'])): ?>
        <span class="tb-greeting"><i class="fas fa-user-circle"></i> Hi, <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?> 👋</span>
        <span class="tb-sep">|</span>
        <a href="?page=logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
      <?php else: ?>
        <a href="?page=login"><i class="fas fa-user"></i> Login</a>
        <span class="tb-sep">|</span>
        <a href="?page=signup">Sign Up</a>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- ═══ HEADER ═════════════════════════════════════════════════════════════ -->
<div class="header" id="mainHeader">
  <div class="container">
    <div class="navbar-inner">

      <!-- Left nav links -->
      <nav class="nav-links">
        <a href="<?= $base_url ?>/"              <?= ($page==='home')    ? 'class="active"' : '' ?>>Home</a>
        <a href="<?= $base_url ?>/?page=menu"    <?= ($page==='menu')    ? 'class="active"' : '' ?>>Menu</a>
        <a href="<?= $base_url ?>/?page=about"   <?= ($page==='about')   ? 'class="active"' : '' ?>>About Us</a>
        <a href="<?= $base_url ?>/?page=contact" <?= ($page==='contact') ? 'class="active"' : '' ?>>Contact</a>
      </nav>

      <!-- Center logo -->
      <div class="logo-block">
        <a href="<?= $base_url ?>/">
          <div class="logo-name">🍕 <span>Pizz</span>House</div>
          <div class="logo-sub">Artisan Pizza · Hyderabad</div>
        </a>
      </div>

      <!-- Right: hours + phone + icons + hamburger -->
      <div class="nav-right">
        <div class="h-hours">Open Every Day<br>10:00 AM – 11:00 PM</div>
        <div class="h-phone">+91 98765 43210</div>

        <a href="?page=wishlist" class="icon-btn" title="Wishlist">
          <i class="far fa-heart"></i>
          <span class="dot" id="wishlist-badge" style="<?= $wishlist_count > 0 ? '' : 'display:none' ?>"><?= $wishlist_count ?></span>
        </a>

        <a href="?page=cart" class="icon-btn" title="Cart">
          <i class="fas fa-shopping-cart"></i>
          <span class="dot" id="cart-badge" style="<?= $cart_count > 0 ? '' : 'display:none' ?>"><?= $cart_count ?></span>
        </a>

        <button class="hamburger" id="hamburger" aria-label="Toggle menu" aria-expanded="false">
          <span></span><span></span><span></span>
        </button>
      </div>

    </div>
  </div>

  <!-- Mobile nav -->
  <div class="mobile-nav" id="mobileNav">
    <a href="<?= $base_url ?>/"              <?= ($page==='home')    ? 'class="active"' : '' ?>>Home</a>
    <a href="<?= $base_url ?>/?page=menu"    <?= ($page==='menu')    ? 'class="active"' : '' ?>>Menu</a>
    <a href="<?= $base_url ?>/?page=about"   <?= ($page==='about')   ? 'class="active"' : '' ?>>About Us</a>
    <a href="<?= $base_url ?>/?page=contact" <?= ($page==='contact') ? 'class="active"' : '' ?>>Contact</a>
    <div class="mob-divider"></div>
    <a href="?page=wishlist"><i class="far fa-heart"></i> Wishlist <?= $wishlist_count > 0 ? "($wishlist_count)" : '' ?></a>
    <a href="?page=cart"><i class="fas fa-shopping-cart"></i> Cart <?= $cart_count > 0 ? "($cart_count)" : '' ?></a>
    <div class="mob-divider"></div>
    <?php if (isset($_SESSION['user_id'])): ?>
      <a href="?page=logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    <?php else: ?>
      <a href="?page=login"><i class="fas fa-user"></i> Login</a>
      <a href="?page=signup"><i class="fas fa-user-plus"></i> Sign Up</a>
    <?php endif; ?>
  </div>
</div>

<main>

<script>
  // ── Hamburger toggle ────────────────────────────────────────────────────
  const hamburger = document.getElementById('hamburger');
  const mobileNav = document.getElementById('mobileNav');

  hamburger.addEventListener('click', () => {
    const isOpen = mobileNav.classList.toggle('open');
    hamburger.classList.toggle('open', isOpen);
    hamburger.setAttribute('aria-expanded', isOpen);
  });

  // Close mobile nav when a link inside is clicked
  mobileNav.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
      mobileNav.classList.remove('open');
      hamburger.classList.remove('open');
      hamburger.setAttribute('aria-expanded', 'false');
    });
  });

  // ── Sticky header shadow on scroll ─────────────────────────────────────
  const header = document.getElementById('mainHeader');
  window.addEventListener('scroll', () => {
    header.classList.toggle('scrolled', window.scrollY > 10);
  });

  // ── Promo close (fix for closest with ID) ──────────────────────────────
  document.querySelector('.promo-close').addEventListener('click', () => {
    document.getElementById('promoBanner').style.display = 'none';
  });
</script>