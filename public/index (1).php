<?php 
include 'config.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>2K2J</title>
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

  <!-- HOME -->
  <section class="home">
    <div class="hero">
      <h2>RELIABLE LOGISTICS</h2>
      <h2>TRUSTED PARTNERSHIPS</h2>
      <div class="fleets-btn">
        <a href="fleets.php">VIEW OUR FLEETS</a>
      </div>
    </div>
  </section>

  <!-- ABOUT -->
  <article class="about">
    <div class="who" id="about">
      <h2>Who We Are</h2>
      <p>
        2K2J Trucking Services was established in September 2023 by
        <strong>Joan Bangcaya</strong>, with a mission to provide reliable and
        efficient trucking and logistics solutions for businesses and
        individuals in need of transportation services. Our focus is on
        delivering exceptional customer service. Since our inception, we have
        built a reputation for professionalism, reliability, and flexibility
        in handling all deliveries, from small packages to large freight. Our
        team is dedicated to offering customized solutions that meet the
        diverse needs of our clients, ensuring that goods are transported
        safely and promptly.
      </p>
    </div>

    <div class="mission" id="mission">
      <h2>Our Mission</h2>
      <p>
        Our mission is to become a trusted partner for transportation
        services, offering safe, efficient, and cost-effective logistics
        solutions tailored to our clients' needs. We strive to deliver the
        highest level of service, ensuring that our clients' businesses run
        smoothly with minimal disruption.
      </p>
    </div>
  </article>


   <div class="services-hero" id="services">
    
  <h1>OUR SERVICES</h1>
      <p>Dependable trucking and logistics solutions tailored to your needs.</p>
      </div>
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

  <!-- CLIENTS -->
  <section class="clients">
    <h2>Trusted Connections, Reliable Deliveries.</h2>
    <div class="client-logos">
      <a href="https://www.zesto.com.ph/" target="_blank">
        <img src="assets/zesto.png" alt="Zesto">
      </a>
      <a href="https://lemonsquare.com.ph/main/" target="_blank">
        <img src="assets/lemonsquare.png" alt="Lemons Square">
      </a>
      <a href="https://jsunitrade.com/" target="_blank">
        <img src="assets/unitrade.png" alt="Unitrade">
      </a>
    </div>
  </section>
 
  <script src="nav.js"></script>
  <script>
   function adminLogout() {
    if (!confirm('Are you sure you want to log out?')) return;
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