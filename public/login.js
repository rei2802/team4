// TAB SWITCHER
function switchTab(tab) {
    const panels = ['loginPanel', 'guestPanel', 'registerPanel'];
    panels.forEach(id => {
        document.getElementById(id).style.display = 'none';
    });
    if (tab === 'login') {
        document.getElementById('loginPanel').style.display = 'block';
        document.getElementById('tabLogin').classList.add('active');
        document.getElementById('tabGuest').classList.remove('active');
    } else if (tab === 'guest') {
        document.getElementById('guestPanel').style.display = 'block';
        document.getElementById('tabGuest').classList.add('active');
        document.getElementById('tabLogin').classList.remove('active');
    } else if (tab === 'register') {
        document.getElementById('registerPanel').style.display = 'block';
        document.getElementById('tabLogin').classList.remove('active');
        document.getElementById('tabGuest').classList.remove('active');
    }
}

// ── SHOW NOTICE if redirected from Book Now ──────────────────
(function () {
    const params = new URLSearchParams(window.location.search);
    if (params.get('msg') === 'login_required') {
        // Switch to Guest tab — guests can book without logging in
        switchTab('guest');
        const guestErr = document.getElementById('guestError');
        if (guestErr) {
            guestErr.style.color = '#f7931e';
            guestErr.textContent = '💡 Enter your name below to continue as guest, or login above.';
        }
    }
})();

// LOGIN
async function login() {
    const phone    = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;
    const err      = document.getElementById('error');
    err.textContent = '';

    if (!phone || !password) {
        err.textContent = '⚠️ Please enter your details.';
        return;
    }

    try {
        const res  = await fetch('login_process.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ phone, password })
        });
        const data = await res.json();

        if (!data.success) {
            err.textContent = '⚠️ ' + data.message;
            return;
        }

        // ── ADMIN or OWNER ──
        if (data.isAdmin) {
            sessionStorage.setItem('regName',   data.name);
            sessionStorage.setItem('username',  data.name);
            sessionStorage.setItem('isGuest',   'false');
            sessionStorage.setItem('isAdmin',   'true');
            sessionStorage.setItem('adminRole', data.admin_role);
            window.location.href = '/admin/dashboard.php';
            return;
        }

        // ── REGULAR USER ──
        sessionStorage.setItem('regName',  data.name);
        sessionStorage.setItem('username', data.phone);
        sessionStorage.setItem('user_id',  data.user_id);
        sessionStorage.setItem('isGuest',  'false');
        sessionStorage.setItem('isAdmin',  'false');
        sessionStorage.removeItem('adminRole');

        const redirectTo = sessionStorage.getItem('redirectAfterLogin');
        sessionStorage.removeItem('redirectAfterLogin');
        if (redirectTo && redirectTo !== 'null' && redirectTo !== 'undefined') {
            window.location.href = redirectTo;
        } else {
            window.location.href = '/book.php';
        }

    } catch (e) {
        err.textContent = '⚠️ Server error. Please try again.';
    }
}

// GUEST LOGIN
function guestLogin() {
    const name = document.getElementById('guestName').value.trim();
    const err  = document.getElementById('guestError');

    if (!name) {
        err.style.color  = '#e74c3c';
        err.textContent  = '⚠️ Please enter your name.';
        return;
    }

    err.textContent = '';
    sessionStorage.setItem('guestName', name);
    sessionStorage.setItem('regName',   name);
    sessionStorage.setItem('isGuest',   'true');
    sessionStorage.setItem('isAdmin',   'false');

    // Go to book.php directly — no login needed
    const redirectTo = sessionStorage.getItem('redirectAfterLogin') || '/book.php';
    sessionStorage.removeItem('redirectAfterLogin');
    window.location.href = redirectTo;
}

// REGISTER
async function register() {
    const name     = document.getElementById('regName').value.trim();
    const phone    = document.getElementById('regPhone').value.trim();
    const password = document.getElementById('regPassword').value;
    const confirm  = document.getElementById('regConfirm').value;
    const err      = document.getElementById('registerError');
    const success  = document.getElementById('registerSuccess');

    err.textContent     = '';
    success.textContent = '';

    if (!name || !phone || !password || !confirm) { err.textContent = '⚠️ Please fill in all fields.'; return; }
    if (phone.length < 10)                        { err.textContent = '⚠️ Invalid phone number.'; return; }
    if (password.length < 6)                      { err.textContent = '⚠️ Password must be at least 6 characters.'; return; }
    if (password !== confirm)                     { err.textContent = '⚠️ Passwords do not match.'; return; }

    try {
        const res  = await fetch('register.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ name, phone, password })
        });
        const data = await res.json();

        if (!data.success) {
            err.textContent = '⚠️ ' + data.message;
            return;
        }

        success.textContent = '✅ ' + data.message;
        document.getElementById('registerForm').reset();
        setTimeout(() => switchTab('login'), 2000);

    } catch (e) {
        err.textContent = '⚠️ Server error. Please try again.';
    }
}