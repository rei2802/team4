<?php 
include 'config.php'; 
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Privacy Policy | 2K2J</title>
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
        <h1>Privacy <span>Policy</span></h1>
        <p>How 2K2J Trucking Services collects, uses, and protects your personal information.</p>
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
          <a href="#info-collect">Information We Collect</a>
          <a href="#how-use">How We Use It</a>
          <a href="#data-security">Data Security</a>
          <a href="#third-party">Third-Party Sharing</a>
          <a href="#changes">Changes to Policy</a>
          <div class="legal-nav-divider"></div>
          <div class="legal-nav-other">Also see</div>
          <a href="terms.php">Terms of Use</a>
          <a href="contact.php">Contact Us</a>
        </aside>

        <!-- CONTENT -->
        <article class="legal-content">

          <div class="legal-section">
            <p>At <strong>2K2J Trucking Services</strong>, your privacy is important to us. We are committed to protecting your personal information and using it responsibly. This policy explains what we collect, how we use it, and your rights.</p>
          </div>

          <hr class="legal-divider" />

          <div class="legal-section" id="info-collect">
            <span class="section-number">Section 01</span>
            <h2>Information We Collect</h2>
            <p>When you use our website or book a service, we may collect the following:</p>
            <ul>
              <li>Personal details such as your name and contact information</li>
              <li>Booking details including pick-up location, delivery address, and service preferences</li>
              <li>Website usage data such as IP address and cookies for analytics</li>
            </ul>
          </div>

          <div class="legal-section" id="how-use">
            <span class="section-number">Section 02</span>
            <h2>How We Use Your Information</h2>
            <p>We use the information we collect for the following purposes:</p>
            <ul>
              <li>To process and fulfill your bookings and deliver services efficiently</li>
              <li>To improve our website experience and overall service quality</li>
              <li>To communicate promotions or service updates, only if you have opted in</li>
            </ul>
          </div>

          <div class="legal-section" id="data-security">
            <span class="section-number">Section 03</span>
            <h2>Data Security</h2>
            <p>We implement appropriate technical and organizational measures to protect your personal information from unauthorized access, disclosure, alteration, or destruction.</p>
            <div class="legal-highlight">
              While we strive to use commercially acceptable means to protect your data, no method of transmission over the Internet or electronic storage is 100% secure. We cannot guarantee absolute security.
            </div>
          </div>

          <div class="legal-section" id="third-party">
            <span class="section-number">Section 04</span>
            <h2>Third-Party Sharing</h2>
            <p>We do not sell, trade, or rent your personal information to third parties. We may share data with trusted service partners <strong>only</strong> to the extent necessary to fulfill the services you have requested from us.</p>
          </div>

          <div class="legal-section" id="changes">
            <span class="section-number">Section 05</span>
            <h2>Changes to this Policy</h2>
            <p>We may update this Privacy Policy from time to time to reflect changes in our practices or legal requirements. Any updates will be posted on this page with a revised date. We encourage you to review this page periodically.</p>
            <p>For questions or concerns about this policy, please reach out through our <a href="contact.php">Contact Us</a> page.</p>
          </div>

        </article>
      </div>
    </div>

    <!-- CTA -->
    <div class="legal-cta">
      <p>Have questions about your data or our practices?</p>
      <a href="contact.php">Get in Touch</a>
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