<?php include 'config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | 2K2J</title>

    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/jpg" href="assets/logo.jpg">
</head>

<body class="login-page">

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

    <main class="login-content">
        <div class="overlay">
            <div class="login-box">

                <!-- TAB SWITCHER -->
                <div class="login-tabs">
                    <button class="login-tab active" id="tabLogin" onclick="switchTab('login')">Login</button>
                    <button class="login-tab" id="tabGuest" onclick="switchTab('guest')">Guest</button>
                </div>

                <!-- LOGIN PANEL -->
                <div id="loginPanel">
                    <form id="loginForm" onsubmit="event.preventDefault(); login();">
                        <div class="input-group">
                            <input type="text" id="username" placeholder="Phone Number" required>
                        </div>
                        <div class="input-group">
                            <input type="password" id="password" placeholder="Password" required>
                        </div>
                        <div class="remember">
                            <input type="checkbox" id="remember">
                            <label for="remember">Remember Me</label>
                        </div>
                        <button type="submit" class="login-btn">LOGIN</button>
                    </form>

                   
                    <div id="error" class="error-message"></div>

                    <!-- REGISTER PROMPT -->
                    <div class="register-divider">
                        <span>or</span>
                    </div>

                    <div class="register-prompt">
                        <p>Don't have an account yet?</p>
                        <button class="register-btn" onclick="switchTab('register')">CREATE ACCOUNT</button>
                    </div>
                </div>

                <!-- GUEST PANEL -->
                <div id="guestPanel" style="display:none;">
                    <p class="guest-note">Enter your details to continue as a guest. You can submit an inquiry.</p>
                    <form id="guestForm" onsubmit="event.preventDefault(); guestLogin();">
                        <div class="input-group">
                            <input type="text" id="guestName" placeholder="Full Name" required>
                        </div>
                       
<button type="submit" class="login-btn guest-btn">
    CONTINUE AS GUEST
</button>                    </form>
                    <div id="guestError" class="error-message"></div>
                </div>

                <!-- REGISTER PANEL -->
                <div id="registerPanel" style="display:none;">
                    <h2>CREATE ACCOUNT</h2>
                    <p class="guest-note">Register using your email to manage your bookings and track shipments.</p>
                    <form id="registerForm" onsubmit="event.preventDefault(); register();">
                        <div class="input-group">
                            <input type="text" id="regName" placeholder="Full Name" required>
                        </div>
                       
                        <div class="input-group">
                            <input type="tel" id="regPhone" placeholder="Contact Number" required>
                        </div>
                        <div class="input-group">
                            <input type="password" id="regPassword" placeholder="Password" required>
                        </div>
                        <div class="input-group">
                            <input type="password" id="regConfirm" placeholder="Confirm Password" required>
                        </div>
                        <button type="submit" class="login-btn">REGISTER</button>
                    </form>

                    <div id="registerError" class="error-message"></div>
                    <div id="registerSuccess" class="success-message"></div>

                    <div class="back-to-login">
                        Already have an account? <a href="#" onclick="switchTab('login')">Log in</a>
                    </div>
                </div>

            </div>
        </div>
    </main>

     <!-- Hamburger Menu Script -->
    <script>
         
(function () {
  const params = new URLSearchParams(window.location.search);
  if (params.get('msg') === 'login_required') {
    const errorEl = document.getElementById('error');
    if (errorEl) {
      errorEl.textContent = '⚠️ You must be logged in to book a service.';
      errorEl.style.display = 'block';
    }
  }
})();
 
  const hamburger = document.querySelector('.hamburger');
  const navLinks = document.querySelector('.nav-links');

  hamburger.addEventListener('click', () => {
    navLinks.classList.toggle('active');
  });
</script>

    <script src="login.js"></script>

    <script src="nav.js"></script>


</body>
<!-- FOOTER -->
<footer class="footer">

  <div class="footer-top">
    
    <!-- Left side -->
    <div class="footer-left">
      <img src="assets/logo.jpg" alt="2K2J Logo">
      <div>
        <h2>2K2J Trucking services</h2>
      </div>
    </div>

    <!-- Right side -->
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

</html>