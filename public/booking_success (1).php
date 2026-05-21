<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include 'config.php';

// Pre-fill form only if logged in — guests can still access
$userInfo = [];
if (!empty($_SESSION['user_id'])) {
    $uStmt = mysqli_prepare($conn,
        "SELECT company_name, contact_person, contact_number, email FROM users WHERE user_id = ?"
    );
    mysqli_stmt_bind_param($uStmt, 'i', $_SESSION['user_id']);
    mysqli_stmt_execute($uStmt);
    $uResult  = mysqli_stmt_get_result($uStmt);
    $userInfo = mysqli_fetch_assoc($uResult) ?? [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Now | 2K2J</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <link rel="icon" type="image/jpg" href="assets/logo.jpg">
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

<!-- BOOKING SECTION -->
<section class="booking-section">
  <div class="booking-wrapper">

    <div class="booking-top-bar">
      <h2>BOOK A SERVICE</h2>
    </div>

    <form class="booking-form" id="bookingForm" action="submitbooking.php" method="POST">

      <!-- COLUMN 1: Company Info -->
      <div class="form-column">
        <h3>Company Info</h3>
        <div class="input-group">
          <label for="companyName">Company Name</label>
          <input type="text" id="companyName" name="company_name" placeholder="Company Name"
                 value="<?php echo htmlspecialchars($userInfo['company_name'] ?? ''); ?>">
        </div>
        <div class="input-group">
          <label for="contactPersonName">Contact Person Name</label>
          <input type="text" id="contactPersonName" name="contact_person" placeholder="Contact Person Name"
                 value="<?php echo htmlspecialchars($userInfo['contact_person'] ?? ''); ?>" required>
        </div>
        <div class="input-group">
          <label for="contactNumber">Contact Number</label>
          <input type="tel" id="contactNumber" name="contact_number" placeholder="Contact Number"
                 value="<?php echo htmlspecialchars($userInfo['contact_number'] ?? ''); ?>" required>
        </div>
        <div class="input-group">
          <label for="contactEmail">Email</label>
          <input type="email" id="contactEmail" name="email" placeholder="Email"
                 value="<?php echo htmlspecialchars($userInfo['email'] ?? ''); ?>" required>
        </div>
      </div>

      <!-- COLUMN 2: Logistics Details -->
      <div class="form-column">
        <h3>Logistics Details</h3>
        <div class="input-group">
          <label for="pickup">Pickup Location</label>
          <input type="text" id="pickup" name="pickup_location" placeholder="Pickup Location" required>
        </div>
        <div class="input-group">
          <label for="delivery">Delivery Location</label>
          <input type="text" id="delivery" name="delivery_location" placeholder="Delivery Location" required>
        </div>
        <div class="input-group distance-group">
          <label class="distance-label" for="distanceKm">
            Estimated Distance
            <span class="distance-hint">(one-way km — we'll compute 2-way)</span>
          </label>
          <div class="distance-row">
            <input type="number" id="distanceKm" name="distance" placeholder="e.g. 30" min="1" step="0.1" required>
            <span class="distance-unit">km</span>
          </div>
        </div>
        <div class="input-group">
          <label for="frequency">Frequency</label>
          <select name="frequency" id="frequency">
            <option value="once">Once</option>
            <option value="daily">Daily</option>
            <option value="weekly">Weekly</option>
            <option value="monthly">Monthly</option>
          </select>
        </div>
        <div class="date-row">
          <div class="input-group">
            <label for="start_date">Start Date</label>
            <input type="date" id="start_date" name="start_date" required>
          </div>
          <div class="input-group">
            <label for="end_date">End Date</label>
            <input type="date" id="end_date" name="end_date" required>
          </div>
        </div>
      </div>

      <!-- COLUMN 3: Fleet & Cargo -->
      <div class="form-column">
        <h3>Fleet & Cargo</h3>
        <div class="input-group">
          <label for="truckType">Truck Type</label>
          <select id="truckType" name="truck_type" required>
            <option value="">Select Truck Type</option>
            <option value="L300 Cargo Van">L300 Cargo Van</option>
            <option value="4-wheeler">4-Wheeler Traviz</option>
            <option value="6-wheeler">6-Wheeler</option>
          </select>
        </div>
        <div class="input-group">
          <label for="specialTransport">Specialized Transport</label>
          <select id="specialTransport" name="special_transport">
            <option value="">Select Specialized Transport</option>
            <option value="None">None</option>
            <option value="Fragile">Fragile Items</option>
            <option value="Hazardous">Hazardous Materials</option>
            <option value="Perishable">Perishable Items</option>
            <option value="High value">High value Goods</option>
          </select>
        </div>
        <div class="input-group">
          <label for="cargoWeight">Cargo Weight</label>
          <div style="display:flex; gap:10px;">
            <input type="number" id="cargoWeight" name="cargo_weight" placeholder="e.g. 500"
                   min="0" step="0.01" style="flex:1;" required>
            <select id="weightUnit" name="weight_unit" style="width:90px; flex-shrink:0;">
              <option value="kg">kg</option>
              <option value="tons">tons</option>
            </select>
          </div>
        </div>
        <div class="special-requirements">
          <h3>Special Requirements</h3>
          <label for="details">Custom Handling &amp; Special Instructions</label>
          <textarea id="details" name="details" placeholder="Custom Handling & Special Instructions"></textarea>
        </div>
      </div>

      <!-- TRUCK IMAGE -->
      <div class="truck-sticker">
        <img src="assets/truck-sticker.png" alt="Truck Illustration">
      </div>

      <!-- ESTIMATE BUTTON -->
      <button type="button" class="estimate-btn" onclick="calculateEstimate()">
        🧮 CALCULATE ESTIMATED PRICE
      </button>

      <!-- ESTIMATED PRICE RESULT -->
      <div class="estimate-result" id="estimateResult" style="display:none;">
        <div class="estimate-header">📋 Estimated Price Breakdown</div>
        <div class="estimate-grid">
          <div class="estimate-row"><span>Pickup Location</span><span id="estPickup">—</span></div>
          <div class="estimate-row"><span>Delivery Location</span><span id="estDelivery">—</span></div>
          <div class="estimate-row"><span>One-Way Distance</span><span id="estOneWay">—</span></div>
          <div class="estimate-row"><span>Total Distance (2-way)</span><span id="estDistance">—</span></div>
          <div class="estimate-row"><span>Truck Type</span><span id="estTruck">—</span></div>
          <div class="estimate-row"><span>Cargo Weight</span><span id="estWeight">—</span></div>
          <div class="estimate-row"><span>Fuel Needed</span><span id="estFuelNeeded">—</span></div>
          <div class="estimate-row"><span>Fuel Cost <small>(@ ₱65/L)</small></span><span id="estFuelCost">—</span></div>
          <div class="estimate-row"><span>Operational Fee</span><span id="estOpFee">—</span></div>
          <div class="estimate-row"><span>Weight Surcharge</span><span id="estWeightCharge">—</span></div>
          <div class="estimate-row"><span>Special Transport</span><span id="estSpecial">—</span></div>
          <div class="estimate-row"><span>Cost per Trip</span><span id="estSubtrip">—</span></div>
          <div class="estimate-row"><span>Frequency</span><span id="estTrips">—</span></div>
          <div class="estimate-row"><span>Subtotal</span><span id="estSubtotal">—</span></div>
          <div class="estimate-row"><span>VAT (12%)</span><span id="estVat">—</span></div>
          <div class="estimate-divider"></div>
          <div class="estimate-row estimate-total"><span>ESTIMATED TOTAL</span><span id="estTotal">—</span></div>
        </div>
        <p class="estimate-disclaimer">⚠️ This is an estimate only. Final rate will be confirmed by 2K2J Services via email.</p>
      </div>

      <!-- CONFIRM BUTTON -->
      <button type="submit" class="booking-btn">CONFIRM BOOKING</button>

    </form>
  </div>
</section>

<script src="script.js"></script>
<script src="nav.js"></script>

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