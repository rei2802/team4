<?php 
include 'config.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Services — 2K2J Trucking</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
  <link rel="icon" type="image/jpg" href="assets/logo.jpg">
</head>
<body>

  <!-- NAVBAR -->
  <nav class="navbar">
    <div class="nav-container">
      <div class="logo">
        <a href="index.php"><img src="assets/logo.jpg" alt="2K2J Logo"></a>
      </div>
      <div class="hamburger">
        <span></span><span></span><span></span>
      </div>
      <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="index.php#about">About Us</a>
        <a href="index.php#services">Services</a>
        <a href="fleets.php">Fleets</a>
        <a href="contact.php">Contact Us</a>

        <?php if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] === true): ?>
          <a href="admin/dashboard.php" class="nav-username-btn">admin</a>
          <button class="login-btn" onclick="adminLogout()">LOG OUT</button>
        <?php else: ?>
          <a href="user_login.php" id="authLoggedOut" class="nav-login-btn">Login</a>
          <a href="my-bookings.php" id="authLoggedIn" class="nav-username-btn" style="display:none;">
            <span id="welcomeUser"></span>
          </a>
          <div class="book-btn">
            <a href="book.php">Book Now</a>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </nav>

  <!-- HERO -->
  <section class="contact-hero">
    <div class="overlay">
      <h1>OUR SERVICES</h1>
      <p>Dependable trucking and logistics solutions tailored to your needs.</p>
    </div>
  </section>

  <!-- SERVICES CARDS -->
  <section class="services-cards">
    <div class="card">
      <div class="service-icon">🚛</div>
      <h3>Freight Transport</h3>
      <p>Offering both local and long-distance hauling for various types of cargo.</p>
    </div>
    <div class="card">
      <div class="service-icon">📦</div>
      <h3>Delivery Services</h3>
      <p>Timely and reliable delivery of goods, from industrial equipment to consumer products.</p>
    </div>
    <div class="card">
      <div class="service-icon">🔒</div>
      <h3>Specialized Transport</h3>
      <p>Handling oversized or delicate shipments that require extra care.</p>
    </div>
    <div class="card">
      <div class="service-icon">📋</div>
      <h3>Logistics Solutions</h3>
      <p>Tailored services for inventory, scheduling, and delivery coordination.</p>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="footer">
    <div class="footer-top">
      <div class="footer-left">
        <img src="assets/logo.jpg" alt="2K2J Logo">
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
    <hr>
    <div class="footer-bottom">
      <p>Copyright © 2K2J. All Rights Reserved.</p>
    </div>
  </footer>

  <script src="nav.js"></script>
  <script>
    function adminLogout() {
      fetch('logout.php').then(() => {
        sessionStorage.clear();
        window.location.href = 'index.php';
      });
    }
    const hamburger = document.querySelector('.hamburger');
    const navLinks  = document.querySelector('.nav-links');
    hamburger.addEventListener('click', () => navLinks.classList.toggle('active'));
  </script>

</body>
</html>