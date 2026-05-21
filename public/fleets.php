<?php 
include 'config.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Fleets | 2K2J</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" />
  <link rel="icon" type="image/jpg" href="assets/logo.jpg" />
  <style>
/* FLEET PAGE SCOPED STYLES — overrides style.css conflicts */
.fleets-section {
    padding: 20px;
    box-sizing: border-box;
    width: 100%;
    overflow-x: hidden;
}

.fleet-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    padding: 24px;
    margin: 0 auto 32px;
    max-width: 1100px;
    width: 100%;
    box-sizing: border-box;
    overflow: hidden;
}

.fleet-card-top {
    display: flex;
    align-items: center;
    gap: 32px;
    margin-bottom: 24px;
    flex-wrap: wrap;
}

.fleet-card-top.reverse {
    flex-direction: row-reverse;
}

.fleet-img-wrap {
    flex: 0 0 40%;
    max-width: 40%;
    background: #f9f9f9;
    border-radius: 14px;
    padding: 20px;
    box-sizing: border-box;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.fleet-img {
    width: 100%;
    height: auto;
    max-height: 280px;
    object-fit: contain;
    display: block;
    transform: rotate(-1deg);
    transition: transform 0.4s ease;
}

.fleet-img-wrap:hover .fleet-img {
    transform: scale(1.05) rotate(-3deg);
}

.fleet-info {
    flex: 1;
    min-width: 0;
}

.fleet-specs {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin-bottom: 20px;
}

.spec-item {
    background: #f9f9f9;
    border-radius: 10px;
    padding: 12px;
    border-left: 3px solid #f7931e;
    display: flex;
    flex-direction: column;
    gap: 4px;
    min-width: 0;
    word-break: break-word;
    box-sizing: border-box;
}

.fleet-views {
    display: flex;
    gap: 16px;
    border-top: 1px solid #f0f0f0;
    padding-top: 20px;
    flex-wrap: wrap;
}

.fleet-view-item {
    flex: 1;
    min-width: 200px;
}

.fleets-cta {
    max-width: 1100px;
    width: 100%;
    margin: 0 auto 32px;
    box-sizing: border-box;
}

/* TABLET */
@media (max-width: 900px) {
    .fleet-card-top,
    .fleet-card-top.reverse {
        flex-direction: column !important;
    }

    .fleet-img-wrap {
        flex: none !important;
        max-width: 100% !important;
        width: 100% !important;
    }

    .fleet-img {
        max-height: 240px;
        transform: none;
    }

    .fleet-img-wrap:hover .fleet-img {
        transform: none;
    }
}

/* MOBILE */
@media (max-width: 600px) {
    .fleets-section {
        padding: 12px;
    }

    .fleet-card {
        padding: 16px;
        margin-bottom: 16px;
        border-radius: 12px;
    }

    .fleet-img-wrap {
        padding: 12px;
    }

    .fleet-img {
        max-height: 180px;
    }

    .fleet-specs {
        grid-template-columns: 1fr 1fr !important;
        gap: 8px;
    }

    .fleet-views {
        flex-direction: column;
    }

    .fleet-view-item {
        min-width: unset;
        width: 100%;
    }

    .fleets-cta {
        padding: 24px 16px;
        border-radius: 12px;
    }
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
        <span></span><span></span><span></span>
      </div>
      <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="index.php#about">About Us</a>
        <a href="index.php#services">Services</a>
        <a href="fleets.php">Fleets</a>
        <a href="contact.php">Contact Us</a>
        <a href="user_login.php" id="authLoggedOut" class="nav-login-btn">Login</a>
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
  <div class="fleets-hero">
    <div class="fleets-hero-overlay">
      <h1>OUR VEHICLE FLEETS</h1>
      <p>Reliable trucks for every logistics need — city or provincial.</p>
    </div>
  </div>

  <!-- FLEET CARDS -->
  <section class="fleets-section">

    <!-- 6-Wheeler -->
    <div class="fleet-card">
      <div class="fleet-card-top">
        <div class="fleet-img-wrap">
          <img src="assets/fleets/6w.png" alt="6-Wheeler Truck" class="fleet-img" />
        </div>
        <div class="fleet-info">
          <span class="fleet-badge">Heavy Duty</span>
          <h2>6-Wheeler Truck</h2>
          <p class="fleet-desc">
            A heavy-duty transport solution designed for large-scale hauling,
            industrial deliveries, and full-house relocations. Built to handle
            the toughest loads across any terrain.
          </p>
          <div class="fleet-specs">
            <div class="spec-item">
              <span class="spec-icon">⚖️</span>
              <span class="spec-label">Capacity</span>
              <span class="spec-value">Up to 10 tons</span>
            </div>
            <div class="spec-item">
              <span class="spec-icon">📦</span>
              <span class="spec-label">Cargo Type</span>
              <span class="spec-value">Industrial / Bulk</span>
            </div>
            <div class="spec-item">
              <span class="spec-icon">🛣️</span>
              <span class="spec-label">Best For</span>
              <span class="spec-value">Long Haul</span>
            </div>
          </div>
          <div class="fleet-tags">
            <span class="fleet-tag">Heavy Cargo</span>
            <span class="fleet-tag">Relocation</span>
            <span class="fleet-tag">Industrial</span>
            <span class="fleet-tag">Provincial Routes</span>
          </div>
        </div>
      </div>
      <div class="fleet-views">
        <div class="fleet-view-item">
          <img src="assets/fleets/6w-side.png" alt="6-Wheeler Side View" />
          <span>Side View</span>
        </div>
        <div class="fleet-view-item">
          <img src="assets/fleets/6w-back.png" alt="6-Wheeler Back View" />
          <span>Back View</span>
        </div>
      </div>
    </div>

    <!-- 4-Wheeler Traviz -->
    <div class="fleet-card">
      <div class="fleet-card-top reverse">
        <div class="fleet-img-wrap">
          <img src="assets/fleets/travis.png" alt="4-Wheeler Traviz" class="fleet-img" />
        </div>
        <div class="fleet-info">
          <span class="fleet-badge medium">Medium Duty</span>
          <h2>4-Wheeler Traviz</h2>
          <p class="fleet-desc">
            Ideal for small to medium deliveries, city logistics,
            and quick transport of goods. Agile enough for urban routes
            while still carrying a solid payload.
          </p>
          <div class="fleet-specs">
            <div class="spec-item">
              <span class="spec-icon">⚖️</span>
              <span class="spec-label">Capacity</span>
              <span class="spec-value">Up to 2 tons</span>
            </div>
            <div class="spec-item">
              <span class="spec-icon">📦</span>
              <span class="spec-label">Cargo Type</span>
              <span class="spec-value">General Goods</span>
            </div>
            <div class="spec-item">
              <span class="spec-icon">🛣️</span>
              <span class="spec-label">Best For</span>
              <span class="spec-value">City Routes</span>
            </div>
          </div>
          <div class="fleet-tags">
            <span class="fleet-tag">City Delivery</span>
            <span class="fleet-tag">Medium Loads</span>
            <span class="fleet-tag">Fast Dispatch</span>
          </div>
        </div>
      </div>
    </div>

    <!-- L300 Cargo Van -->
    <div class="fleet-card">
      <div class="fleet-card-top">
        <div class="fleet-img-wrap">
          <img src="assets/fleets/l3.png" alt="L300 Cargo Van" class="fleet-img" />
        </div>
        <div class="fleet-info">
          <span class="fleet-badge light">Light Duty</span>
          <h2>L300 Cargo Van</h2>
          <p class="fleet-desc">
            Reliable and secure cargo van perfect for medium loads,
            business deliveries, and protected transport. Great for
            fragile or high-value items that need an enclosed space.
          </p>
          <div class="fleet-specs">
            <div class="spec-item">
              <span class="spec-icon">⚖️</span>
              <span class="spec-label">Capacity</span>
              <span class="spec-value">Up to 1 ton</span>
            </div>
            <div class="spec-item">
              <span class="spec-icon">📦</span>
              <span class="spec-label">Cargo Type</span>
              <span class="spec-value">Fragile / Secure</span>
            </div>
            <div class="spec-item">
              <span class="spec-icon">🛣️</span>
              <span class="spec-label">Best For</span>
              <span class="spec-value">Business Delivery</span>
            </div>
          </div>
          <div class="fleet-tags">
            <span class="fleet-tag">Enclosed Cargo</span>
            <span class="fleet-tag">Fragile Items</span>
            <span class="fleet-tag">Business Use</span>
            <span class="fleet-tag">High-Value Goods</span>
          </div>
        </div>
      </div>
    </div>

    <!-- CTA -->
    <div class="fleets-cta">
      <h3>Ready to Book a Vehicle?</h3>
      <p>Choose the right fleet for your cargo and get a price estimate instantly.</p>
      <a href="book.php" class="fleets-cta-btn">Book Now</a>
    </div>

  </section>

  <script src="nav.js"></script>
  <script>
    const hamburger = document.querySelector('.hamburger');
    const navLinks  = document.querySelector('.nav-links');
    hamburger.addEventListener('click', () => navLinks.classList.toggle('active'));
  </script>

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

</body>
</html>