document.addEventListener("DOMContentLoaded", function () {

  const elOut     = document.getElementById('authLoggedOut');
  const elIn      = document.getElementById('authLoggedIn');
  const elWelcome = document.getElementById('welcomeUser');

  const isMobile    = window.innerWidth <= 768;
  const mobileExtra = isMobile ? 'width:70%; justify-content:center; margin:15px 0;' : '';

  // ── SESSION CHECK via PHP ──────────────────────────────────
  fetch('/whoami.php')
    .then(r => r.json())
    .then(data => {
      if (data.logged_in) {
        const isAdmin   = data.is_admin   || false;
        const adminRole = data.admin_role || 'admin'; // 'owner' or 'admin'

        sessionStorage.setItem('regName',   data.name);
        sessionStorage.setItem('username',  data.name);
        sessionStorage.setItem('isGuest',   'false');
        sessionStorage.setItem('isAdmin',   isAdmin ? 'true' : 'false');
        sessionStorage.setItem('adminRole', adminRole);

        if (elOut) elOut.setAttribute('style', 'display:none !important');
        if (elIn)  elIn.setAttribute('style', `display:inline-flex !important; ${mobileExtra}`);

        // ── Label logic ──────────────────────────────────────
        // Owner  → "Owner"
        // Admin  → "Admin"
        // User   → their company/username from DB
        if (elWelcome) {
          if (isAdmin) {
            elWelcome.textContent = adminRole === 'owner' ? 'Owner' : 'Admin';
          } else {
            elWelcome.textContent = data.name;
          }
        }

        if (elIn) {
          elIn.href = isAdmin
            ? '/dashboard.php'
            : '/my-bookings.php';
        }

      } else {
        sessionStorage.clear();
        if (elOut) elOut.setAttribute('style', `display:inline-flex !important; ${mobileExtra}`);
        if (elIn)  elIn.setAttribute('style', 'display:none !important');
      }
    })
    .catch(() => {
      if (elOut) elOut.setAttribute('style', `display:inline-flex !important; ${mobileExtra}`);
      if (elIn)  elIn.setAttribute('style', 'display:none !important');
    });

  // ── LOGOUT ─────────────────────────────────────────────────
  window.logoutUser = function () {
    if (!confirm('Are you sure you want to log out?')) return;
    fetch('/logout.php').then(() => {
      sessionStorage.clear();
      window.location.href = '/index.php';
    });
};

});