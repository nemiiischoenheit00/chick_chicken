<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="Image" href="assets/Logo.png"/>
    <title>Admin Login | Chick Chicken</title>
    <style>@import url('https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap');</style>
    <style>@import url('https://fonts.googleapis.com/css2?family=Alegreya+Sans:ital,wght@0,100;0,300;0,400;0,500;0,700;0,800;0,900;1,100;1,300;1,400;1,500;1,700;1,800;1,900&family=Oswald:wght@200..700&display=swap');</style>
    <style>
        /* ── Reset & base ── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --mustard: #FFDE59;
            --red:     #FF0000;
            --black:   #000;
            --wine-red:#9A0404;
            --white:   #ffffff;
        }

        html, body {
            height: 100%;
            font-family: Arial, sans-serif;
        }

        /* ── Layout ── */
        .login {
            display: flex;
            height: 100vh;
        }

        .separator {
            display: flex;
            width: 100%;
        }

        /* ── Left panel — dark admin theme ── */
        .greetings {
            flex: 1;
            background-color: #1a1a1a;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px 50px;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        /* subtle grid texture */
        .greetings::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,222,89,.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,222,89,.06) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }

        .admin-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--mustard);
            color: #000;
            font-family: 'Oswald', sans-serif;
            font-size: 0.85em;
            font-weight: 600;
            letter-spacing: 2px;
            padding: 6px 16px;
            border-radius: 4px;
            margin-bottom: 28px;
            width: fit-content;
        }

        .greetings h1 {
            font-size: 3.6em;
            font-weight: bold;
            margin-bottom: 20px;
            font-family: 'Oswald', sans-serif;
            color: var(--mustard);
            line-height: 1.1;
        }

        .greetings p {
            font-size: 1.6em;
            line-height: 1.8;
            font-family: "Alegreya Sans", sans-serif;
            color: #ccc;
        }

        /* ── Right panel ── */
        .login-form {
            flex: 1;
            background-color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-box {
            width: 100%;
            max-width: 600px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0 40px;
        }

        .logo {
            width: 150px;
            margin-bottom: 20px;
        }

        /* Admin tag under logo */
        .admin-tag {
            display: inline-block;
            background: #1a1a1a;
            color: var(--mustard);
            font-family: 'Oswald', sans-serif;
            font-size: 0.78em;
            letter-spacing: 3px;
            padding: 4px 14px;
            border-radius: 4px;
            margin-bottom: 22px;
        }

        .login-form h2 {
            margin-bottom: 20px;
            font-weight: bold;
            font-size: 2em;
            font-family: 'Oswald', sans-serif;
            color: #1a1a1a;
        }

        /* Alert messages */
        .alert-msg {
            width: 100%;
            max-width: 600px;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 10px;
            font-size: 0.95em;
            text-align: left;
        }
        .alert-error   { background:#ffe5e5; color:#b00020; border:1px solid #f5c2c2; }
        .alert-success { background:#e6f4ea; color:#1e6e34; border:1px solid #b7dfc4; }

        /* Inputs */
        .login-form input {
            width: 100%;
            max-width: 600px;
            padding: 25px 45px;
            margin: 15px 0;
            font-size: 1.5em;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 10px;
            outline: none;
            transition: border-color 0.2s;
        }

        .login-form input:focus {
            border-color: #1a1a1a;
        }

        /* Forgot password link */
        .forgot-wrap {
            text-align: right;
            width: 100%;
            max-width: 600px;
            margin-top: -8px;
            margin-bottom: 10px;
        }

        .forgot-wrap a {
            font-size: 0.9em;
            color: var(--wine-red);
            text-decoration: none;
            font-family: 'Alegreya Sans', sans-serif;
        }

        /* Button */
        .login-form button[type="submit"] {
            width: 100%;
            max-width: 600px;
            padding: 20px;
            background-color: #1a1a1a;
            color: var(--mustard);
            border: none;
            cursor: pointer;
            font-weight: bold;
            border-radius: 25px;
            font-size: 1.5em;
            margin-top: 10px;
            font-family: 'Oswald', sans-serif;
            letter-spacing: 1px;
            transition: background 0.2s;
        }

        .login-form button[type="submit"]:hover {
            background-color: #333;
        }

        .back-text {
            margin-top: 20px;
            font-size: 1.1em;
            color: #666;
        }

        .back-text a {
            color: var(--red);
            text-decoration: none;
            font-weight: bold;
        }

        /* ── Forgot Password Modal ── */
        #forgot-modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.55);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .modal-box {
            background: #fff;
            border-radius: 16px;
            padding: 36px 36px 40px;
            width: 90%;
            max-width: 420px;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
            position: relative;
        }

        .modal-close {
            position: absolute;
            top: 14px; right: 16px;
            background: none; border: none;
            font-size: 20px; cursor: pointer;
            color: #aaa; line-height: 1;
            width: 30px; height: 30px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 50%;
            transition: background 0.15s;
        }
        .modal-close:hover { background: #f0f0f0; color: #555; }

        .modal-box h2 {
            font-family: 'Oswald', sans-serif;
            font-size: 1.6rem;
            margin: 0 0 10px;
            color: #1a1a1a;
        }

        .modal-box p {
            font-family: 'Alegreya Sans', sans-serif;
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 24px;
        }

        #forgot-msg {
            display: none;
            margin-bottom: 16px;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 0.9rem;
            text-align: left;
        }

        .modal-box input[type="email"] {
            width: 100%;
            padding: 14px 16px;
            border: 1.5px solid #ddd;
            border-radius: 10px;
            font-size: 1rem;
            font-family: 'Alegreya Sans', sans-serif;
            box-sizing: border-box;
            margin-bottom: 16px;
            outline: none;
            transition: border-color 0.2s;
        }
        .modal-box input[type="email"]:focus { border-color: #1a1a1a; }

        .modal-box button[type="submit"] {
            width: 100%;
            padding: 14px;
            background: #1a1a1a;
            color: var(--mustard);
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            font-family: 'Alegreya Sans', sans-serif;
            transition: background 0.2s;
            border-radius: 25px;
        }
        .modal-box button[type="submit"]:hover { background: #333; }
    </style>
</head>
<body>
<section class="login">
    <div class="separator">

        <!-- Left panel -->
        <div class="greetings">
            <div class="admin-badge">⚙ ADMIN PORTAL</div>
            <h1>WELCOME BACK, ADMIN!</h1>
            <p>Access the Chick Chicken management dashboard. Monitor orders, manage the menu, handle customer accounts, and keep everything running smoothly.</p>
        </div>

        <!-- Right panel -->
        <div class="login-form">
            <div class="form-box">
                <img src="assets/Logo2.png" alt="Chick Chicken Logo" class="logo">
                <span class="admin-tag">ADMIN ACCESS</span>
                <h2>Admin Login</h2>

                <?php
                if (isset($_GET['error'])) {
                    $errors = [
                        'missing_fields'      => 'Please fill in all fields.',
                        'invalid_email'       => 'Please enter a valid email address.',
                        'invalid_credentials' => 'Incorrect email or password.',
                        'unauthorized'        => 'You do not have admin access.',
                    ];
                    $msg = $errors[$_GET['error']] ?? 'An error occurred. Please try again.';
                    echo '<div class="alert-msg alert-error">' . htmlspecialchars($msg) . '</div>';
                }
                ?>

                <form action="admin_login_process.php" method="POST">
                    <input type="email" id="email" name="email" placeholder="Enter admin email" required>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    <div class="forgot-wrap">
                        <a href="#" onclick="document.getElementById('forgot-modal').style.display='flex'; return false;">
                            Forgot password?
                        </a>
                    </div>
                    <button type="submit">Login</button>
                </form>

                <p class="back-text">Not an admin? <a href="login.php">Go to user login</a></p>
            </div>
        </div>

    </div>
</section>

<!-- Forgot Password Modal -->
<div id="forgot-modal">
    <div class="modal-box">
        <button class="modal-close" onclick="document.getElementById('forgot-modal').style.display='none'">&#x2715;</button>
        <h2>Forgot Password?</h2>
        <p>Enter your admin email and we'll send you a reset link.</p>
        <div id="forgot-msg"></div>
        <form onsubmit="submitForgot(event)">
            <input type="email" id="forgot-email" placeholder="Enter your admin email address" required>
            <button type="submit">Send Reset Link</button>
        </form>
    </div>
</div>

<script>
async function submitForgot(e) {
    e.preventDefault();
    const email = document.getElementById('forgot-email').value.trim();
    const msgEl = document.getElementById('forgot-msg');
    const btn   = e.target.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.textContent = 'Sending…';
    try {
        const res  = await fetch('admin_forgot_password.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'email=' + encodeURIComponent(email)
        });
        const data = await res.json();
        msgEl.style.display = 'block';
        if (data.success) {
            msgEl.style.cssText = 'display:block; background:#e6f4ea; color:#1e6e34; border:1px solid #b7dfc4; margin-bottom:16px; padding:10px 14px; border-radius:8px; font-size:0.9rem; text-align:left;';
            msgEl.textContent = '✓ Reset link sent! Check your inbox.';
            document.getElementById('forgot-email').value = '';
        } else {
            msgEl.style.cssText = 'display:block; background:#ffe5e5; color:#b00020; border:1px solid #f5c2c2; margin-bottom:16px; padding:10px 14px; border-radius:8px; font-size:0.9rem; text-align:left;';
            msgEl.textContent = data.error || 'Something went wrong. Please try again.';
        }
    } catch (err) {
        msgEl.style.cssText = 'display:block; background:#ffe5e5; color:#b00020; border:1px solid #f5c2c2; margin-bottom:16px; padding:10px 14px; border-radius:8px; font-size:0.9rem; text-align:left;';
        msgEl.textContent = 'Network error. Please try again.';
    }
    btn.disabled = false;
    btn.textContent = 'Send Reset Link';
}
</script>
</body>
</html>