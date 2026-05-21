<?php 
include 'config.php'; 
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Terms of Use | 2K2J</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" />
    <link rel="icon" type="image/jpg" href="assets/logo.jpg"/>
    <style>
      .legal-hero {
        background: #000;
        padding: 70px 20px 50px;
        text-align: center;
        border-bottom: 1px solid #1a1a1a;
      }
      .legal-hero-inner {
        max-width: 760px;
        margin: 0 auto;
      }
      .legal-hero-badge {
        display: inline-block;
        background: rgba(247, 147, 30, 0.12);
        color: #f7931e;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        padding: 6px 16px;
        border-radius: 99px;
        border: 1px solid rgba(247, 147, 30, 0.3);
        margin-bottom: 20px;
      }
      .legal-hero h1 {
        font-size: clamp(32px, 5vw, 50px);
        font-weight: 800;
        color: #fff;
        margin-bottom: 14px;
        letter-spacing: -0.01em;
      }
      .legal-hero h1 span { color: #f7931e; }
      .legal-hero p {
        font-size: 15px;
        color: rgba(255,255,255,0.5);
        line-height: 1.6;
      }
      .legal-meta {
        display: flex;
        justify-content: center;
        gap: 24px;
        margin-top: 24px;
        flex-wrap: wrap;
      }
      .legal-meta-item {
        font-size: 12px;
        color: rgba(255,255,255,0.35);
        display: flex;
        align-items: center;
        gap: 6px;
      }
      .legal-meta-item::before {
        content: '';
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: #f7931e;
        opacity: 0.6;
        flex-shrink: 0;
      }
      .legal-body {
        background: #f7f7f7;
        padding: 60px 20px 80px;
      }
      .legal-layout {
        max-width: 900px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 220px 1fr;
        gap: 40px;
        align-items: start;
      }
      .legal-nav {
        position: sticky;
        top: 100px;
        background: #fff;
        border-radius: 14px;
        padding: 22px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid #eee;
      }
      .legal-nav h4 {
        font-size: 11px;
        font-weight: 800;
        color: #aaa;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 14px;
      }
      .legal-nav a {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #666;
        text-decoration: none;
        padding: 8px 10px;
        border-radius: 7px;
        margin-bottom: 2px;
        transition: 0.2s ease;
        border-left: 2px solid transparent;
      }
      .legal-nav a:hover {
        background: #fff3e0;
        color: #f7931e;
        border-left-color: #f7931e;
      }
      .legal-nav-divider {
        height: 1px;
        background: #eee;
        margin: 14px 0;
      }
      .legal-nav-other {
        font-size: 11px;
        color: #bbb;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-weight: 700;
        margin-bottom: 8px;
      }
      .legal-content {
        background: #fff;
        border-radius: 14px;
        padding: 44px 48px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid #eee;
      }
      .legal-section {
        margin-bottom: 44px;
        scroll-margin-top: 100px;
      }
      .legal-section:last-child { margin-bottom: 0; }
      .section-number {
        display: inline-block;
        font-size: 11px;
        font-weight: 800;
        color: #f7931e;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 8px;
      }
      .legal-section h2 {
        font-size: 20px;
        font-weight: 800;
        color: #111;
        margin-bottom: 14px;
        padding-bottom: 12px;
        border-bottom: 1.5px solid #f0f0f0;
      }
      .legal-section p {
        font-size: 14px;
        color: #555;
        line-height: 1.85;
        margin-bottom: 12px;
      }
      .legal-section ul {
        list-style: none;
        padding: 0;
        margin: 0 0 12px;
        display: flex;
        flex-direction: column;
        gap: 10px;
      }
      .legal-section ul li {
        font-size: 14px;
        color: #555;
        line-height: 1.7;
        padding: 12px 16px;
        background: #fafafa;
        border-radius: 8px;
        border-left: 3px solid #f7931e;
      }
      .legal-highlight {
        background: #fff8f0;
        border: 1px solid rgba(247, 147, 30, 0.25);
        border-radius: 10px;
        padding: 16px 20px;
        font-size: 14px;
        color: #555;
        line-height: 1.75;
        margin-top: 12px;
      }
      .legal-content a {
        color: #f7931e;
        text-decoration: none;
        font-weight: 700;
        border-bottom: 1px dashed rgba(247, 147, 30, 0.4);
        transition: 0.2s;
      }
      .legal-content a:hover { border-bottom-style: solid; }
      .legal-divider {
        height: 1px;
        background: linear-gradient(to right, #f7931e22, #f7931e66, #f7931e22);
        margin: 0 0 44px;
        border: none;
      }
      .legal-cta {
        background: #000;
        text-align: center;
        padding: 50px 20px;
        border-top: 1px solid #1a1a1a;
      }
      .legal-cta p {
        color: rgba(255,255,255,0.5);
        font-size: 14px;
        margin-bottom: 18px;
      }
      .legal-cta a {
        display: inline-block;
        background: #f7931e;
        color: #000;
        font-weight: 800;
        font-size: 14px;
        padding: 12px 28px;
        border-radius: 8px;
        text-decoration: none;
        transition: 0.25s ease;
        letter-spacing: 0.04em;
        border-bottom: none;
      }
      .legal-cta a:hover {
        background: #ffad33;
        transform: translateY(-1px);
      }
      @media (max-width: 768px) {
        .legal-layout { grid-template-columns: 1fr; }
        .legal-nav {
          position: static;
          display: flex;
          flex-wrap: wrap;
          gap: 6px;
          padding: 16px;
        }
        .legal-nav h4,
        .legal-nav-divider,
        .legal-nav-other { display: none; }
        .legal-nav a {
          border: 1px solid #eee;
          border-left: 2px solid transparent;
          padding: 6px 12px;
          font-size: 12px;
        }
        .legal-content { padding: 28px 22px; }
      }
    </style>
  </head>
  <body>

   <!-- NAV BAR -->
<nav class="navbar">
  <div class="nav-container">
    <div class="logo">
      <a href="index.php"><img src="assets/logo.jpg" alt="2K2J Logo" /></a>
    </div>

    <div class="hamburger">
      <span></span>
      <span></span>
      <span></span>
    </div>

    <div class="nav-links">
      <a href="index.php">Home</a>
      <a href="index.php#about">About Us</a>
      <a href="index.php#services">Services</a>
      <a href="fleets.php">Fleets</a>
      <a href="contact.php">Contact Us</a>

      <!-- NOT logged in -->
      <a href="user_login.php" id="authLoggedOut" class="nav-login-btn">Login</a>

      <!-- Logged in: username links to my-bookings -->
      <a href="my-bookings.php" id="authLoggedIn" class="nav-username-btn" style="display:none;">
         <span id="welcomeUser"></span>
      </a>

      <div class="book-btn">
        <a href="book.php">Book Now</a>
      </div>
    </div>
  </div>
</nav>

    <!-- HERO -->
    <div class="legal-hero">
      <div class="legal-hero-inner">
        <div class="legal-hero-badge">Legal Document</div>
        <h1>Terms of <span>Use</span></h1>
        <p>Please read these terms carefully before using our website or booking our services.</p>
        <div class="legal-meta">
          <span class="legal-meta-item">Last updated: 2026</span>
          <span class="legal-meta-item">2K2J Trucking Services</span>
          <span class="legal-meta-item">Philippines</span>
        </div>
      </div>
    </div>

    <!-- BODY -->
    <div class="legal-body">
      <div class="legal-layout">

        <!-- SIDEBAR -->
        <aside class="legal-nav">
          <h4>On this page</h4>
          <a href="#use-services">Use of Services</a>
          <a href="#booking-payments">Booking & Payments</a>
          <a href="#liability">Limitation of Liability</a>
          <a href="#changes">Changes to Terms</a>
          <div class="legal-nav-divider"></div>
          <div class="legal-nav-other">Also see</div>
          <a href="policy.php">Privacy Policy</a>
          <a href="contact.php">Contact Us</a>
        </aside>

        <!-- CONTENT -->
        <article class="legal-content">

          <div class="legal-section">
            <p>Welcome to <strong>2K2J Trucking Services</strong>. By accessing or using our website and booking our services, you agree to comply with and be bound by the following Terms of Use. If you do not agree, please do not use our services.</p>
          </div>

          <hr class="legal-divider" />

          <div class="legal-section" id="use-services">
            <span class="section-number">Section 01</span>
            <h2>Use of Services</h2>
            <p>By using this website or any of our services, you agree to the following conditions:</p>
            <ul>
              <li>You must provide accurate, complete, and up-to-date information when booking services</li>
              <li>Our services are intended for lawful purposes only; any illegal use is strictly prohibited</li>
              <li>Unauthorized reproduction, distribution, or use of our website content is not permitted</li>
            </ul>
          </div>

          <div class="legal-section" id="booking-payments">
            <span class="section-number">Section 02</span>
            <h2>Booking and Payments</h2>
            <p>To ensure a smooth and fair experience for all customers, the following booking conditions apply:</p>
            <ul>
              <li>Bookings are considered confirmed only after payment has been received or our team has provided written acknowledgment</li>
              <li>We reserve the right to refuse or cancel service in cases of suspected fraud, misuse, or violations of these terms</li>
            </ul>
            <div class="legal-highlight">
              All pricing displayed on this website is for estimation purposes only. Final rates may vary based on actual distance, cargo details, and service requirements.
            </div>
          </div>

          <div class="legal-section" id="liability">
            <span class="section-number">Section 03</span>
            <h2>Limitation of Liability</h2>
            <p>2K2J Trucking Services shall not be held liable for any indirect, incidental, special, or consequential damages arising out of or in connection with the use of our website or services.</p>
            <p>Our total liability in any matter related to these terms or our services shall not exceed the amount paid by the customer for the specific service giving rise to the claim.</p>
          </div>

          <div class="legal-section" id="changes">
            <span class="section-number">Section 04</span>
            <h2>Changes to Terms</h2>
            <p>We reserve the right to modify these Terms of Use at any time. Changes will be effective immediately upon posting to this page. Your continued use of our services after any changes constitutes your acceptance of the updated terms.</p>
            <p>For any questions or inquiries regarding these terms, please visit our <a href="contact.php">Contact Us</a> page.</p>
          </div>

        </article>
      </div>
    </div>

    <!-- CTA -->
    <div class="legal-cta">
      <p>Questions about our terms or how we operate?</p>
      <a href="contact.php">Contact Our Team</a>
    </div>

    <!-- FOOTER -->
    <footer class="footer">
      <div class="footer-top">
        <div class="footer-left">
          <img src="assets/logo.jpg" alt="2K2J Logo" />
          <div><h2>2K2J Trucking services</h2></div>
        </div>
        <div class="footer-right">
          <div class="footer-column">
            <h4>Company</h4>
            <a href="terms.php">Terms of use</a>
            <a href="policy.php">Privacy policy</a>
          </div>
        </div>
      </div>
      <hr />
      <div class="footer-bottom">
        <p>Copyright © 2K2J. All Rights Reserved.</p>
      </div>
    </footer>

    <script>
      const hamburger = document.querySelector('.hamburger');
      const navLinks = document.querySelector('.nav-links');
      hamburger.addEventListener('click', () => navLinks.classList.toggle('active'));
    </script>
    <script src="nav.js"></script>
  </body>
</html>