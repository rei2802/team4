<?php 
include 'config.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact Us | 2K2J</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" />
  <link rel="icon" type="image/jpg" href="assets/logo.jpg" />
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

    <?php if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] === true): ?>
        <!-- Admin logged in -->
<?php
    $role  = strtolower($_SESSION['adminRole'] ?? 'admin');
    $label = ($role === 'owner') ? 'Owner' : 'Admin';
?>
<a href="admin/dashboard.php" class="nav-username-btn">
    <?php echo $label; ?>
</a>        <button class="login-btn" onclick="adminLogout()">LOG OUT</button>

    <?php else: ?>
        <!-- Regular user / guest -->
        <a href="user_login.php" id="authLoggedOut" class="nav-login-btn">Login</a>
        <a href="my-bookings.php" id="authLoggedIn" class="nav-username-btn" style="display:none;">
            <span id="welcomeUser"></span>
        </a>
        <div class="book-btn" id="bookNowBtn">
            <a href="book.php">Book Now</a>
        </div>
    <?php endif; ?>
      </div>  <!-- closes .nav-links -->
    </div>    <!-- closes .nav-container -->
  </nav>

  <!-- CONTACT HERO -->
  <section class="contact-hero">
    <div class="overlay">
      <h1>GET IN TOUCH WITH US</h1>
      <p>Reach out today and let us help you move your business forward.</p>
    </div>
  </section>

  <!-- CONTACT CARDS -->
  <section class="contact-cards">
    <div class="card">
      <h3>📞 CALL US</h3>
      <p>0915-088-2795</p>
      <span>Always Open</span>
      <a href="tel:09150882795" class="btn">CALL NOW</a>
    </div>

    <div class="card">
      <h3>✉️ EMAIL US</h3>
      <p>joanbangcaya2k2j@gmail.com</p>
      <span>We respond within 24 hours</span>
      <a href="mailto:joanbangcaya2k2j@gmail.com" class="btn">SEND EMAIL</a>
    </div>

    <div class="card">
      <h3>📍 VISIT US</h3>
      <p style="margin-bottom: 15px;">
        87 L. Sandiego St, Canumay West,<br />
        Valenzuela City, Philippines, 1443
      </p>
      <a href="https://www.bing.com/maps/search?mepi=57%7E%7EEmbedded%7ELargeMapLink&ty=18&v=2&sV=1&qpvt=87+L.+Sandiego+St%2C+Canumay+West%2C++Valenzuela+City&FORM=MIRE&q=87+L+San+Diego+St%2C+Canumay%2C+Valenzuela%2C+1442+Metro+Manila&ppois=14.713128_120.989389_87+L+San+Diego+St%2C+Canumay%2C+Valenzuela%2C+1442+Metro+Manila_%7E&cp=14.669952%7E121.011261&lvl=12.6&style=r"
        class="btn" target="_blank">VIEW MAP</a>
    </div>
  </section>

  <script src="nav.js"></script>
  <script>
    function adminLogout() {
    fetch('logout.php').then(() => {
        sessionStorage.clear();
        window.location.href = 'index.php';
    });
}
    const hamburger = document.querySelector('.hamburger');
    const navLinks = document.querySelector('.nav-links');
    hamburger.addEventListener('click', () => {
      navLinks.classList.toggle('active');
    });
  </script>

</body>

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

</html>