<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include 'config.php';

if (empty($_SESSION['user_id'])) {
    header("Location: user_login.php?msg=login_required");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings | 2K2J</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <link rel="icon" type="image/jpg" href="assets/logo.jpg">
</head>
<body>

<!-- NAV BAR -->
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
      <a href="services.php">Services</a>
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

<!-- PAGE HEADER -->
<section class="mybookings-section">
  <div class="mybookings-wrapper">

    <div class="mybookings-header">
      <div>
        <h2>📋 My Bookings</h2>
        <p id="mybookingsUser">Loading...</p>
      </div>
      <div class="mybookings-header-actions">
        <a href="book.php" class="new-booking-btn">+ New Booking</a>
        <button class="nav-logout-btn" onclick="logoutUser()">🚪 Logout</button>
      </div>
    </div>

    <!-- FILTER TABS -->
    <div class="filter-tabs">
      <button class="filter-tab active" onclick="filterBookings('all', this)">All</button>
      <button class="filter-tab" onclick="filterBookings('pending', this)">Pending</button>
      <button class="filter-tab" onclick="filterBookings('approved', this)">Approved</button>
      <button class="filter-tab" onclick="filterBookings('rejected', this)">Rejected</button>
      <button class="filter-tab" onclick="filterBookings('completed', this)">Completed</button>
    </div>

    <!-- BOOKINGS LIST -->
    <div id="bookingsList"></div>

    <!-- EMPTY STATE -->
    <div class="empty-state" id="emptyState" style="display:none;">
      <div class="empty-icon">📦</div>
      <h3>No bookings found</h3>
      <p>You haven't made any bookings yet.</p>
      <a href="book.php" class="new-booking-btn">Book Now</a>
    </div>

  </div>
</section>

<!-- CANCEL MODAL -->
<div class="modal-overlay" id="cancelModal" style="display:none;">
  <div class="modal-box">
    <div class="modal-icon">⚠️</div>
    <h3>Cancel Booking?</h3>
    <p>Are you sure you want to cancel this booking? This action cannot be undone.</p>
    <div class="modal-actions">
      <button class="modal-cancel-btn" onclick="closeModal()">Keep Booking</button>
      <button class="modal-confirm-btn" onclick="confirmCancel()">Yes, Cancel</button>
    </div>
  </div>
</div>

<script src="nav.js"></script>
<script src="my-bookings.js"></script>
<script>
  const hamburger = document.querySelector('.hamburger');
  const navLinks  = document.querySelector('.nav-links');
  hamburger.addEventListener('click', () => navLinks.classList.toggle('active'));
</script>

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

</body>
</html>