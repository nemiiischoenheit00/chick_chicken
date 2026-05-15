<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="Image" href="assets/Logo.png"/>
    <title>Login | Chick Chicken</title>
    <link rel="stylesheet" href="login.css">
    <style>@import url('https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap');</style>
    <style>@import url('https://fonts.googleapis.com/css2?family=Alegreya+Sans:ital,wght@0,100;0,300;0,400;0,500;0,700;0,800;0,900;1,100;1,300;1,400;1,500;1,700;1,800;1,900&family=Oswald:wght@200..700&display=swap');</style>
    <style>
        .alert-msg {
            width: 100%;
            max-width: 600px;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 10px;
            font-size: 0.95em;
            text-align: left;
        }
        .alert-error   { background: #ffe5e5; color: #b00020; border: 1px solid #f5c2c2; }
        .alert-success { background: #e6f4ea; color: #1e6e34; border: 1px solid #b7dfc4; }
    </style>
</head>
<body>
<section class="login">
    <div class="separator">
        <div class="greetings">
            <h1>GREETINGS, OUR BELOVED CUSTOMER!</h1>
            <p>Welcome to Chick Chicken! Sign in to be part of our growing family of chicken lovers. Whether you're here to browse, order, or just check out what's new, we're happy to have you around. Go ahead — sign in and let's make your day a little more delicious.</p>
        </div>

        <div class="login-form">
            <div class="form-box">
                <img src="assets/Logo2.png" alt="Chick Chicken Logo" class="logo">
                <h2>User Login</h2>

                <?php
                // Show error messages
                if (isset($_GET['error'])) {
                    $errors = [
                        'missing_fields'      => 'Please fill in all fields.',
                        'invalid_email'       => 'Please enter a valid email address.',
                        'invalid_credentials' => 'Incorrect email or password.',
                    ];
                    $msg = $errors[$_GET['error']] ?? 'An error occurred. Please try again.';
                    echo '<div class="alert-msg alert-error">' . htmlspecialchars($msg) . '</div>';
                }
                // Show success message after registration
                if (isset($_GET['registered'])) {
                    echo '<div id="signup-success-popup" style="
                        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
                        background: rgba(0,0,0,0.45); display: flex;
                        align-items: center; justify-content: center; z-index: 9999;">
                        <div style="
                            background: #fff; border-radius: 16px; padding: 40px 36px;
                            text-align: center; max-width: 360px; width: 90%;
                            box-shadow: 0 8px 32px rgba(0,0,0,0.18);">
                            <div style="font-size: 52px; margin-bottom: 10px;">🎉</div>
                            <h2 style="margin: 0 0 8px; font-size: 1.4rem; color: #1a1a1a;">Account Created!</h2>
                            <p style="color: #666; font-size: 0.95rem; margin: 0 0 24px;">
                                You\'re all set. Sign in to start ordering.
                            </p>
                            <button onclick="document.getElementById(\'signup-success-popup\').remove()" style="
                                background: #D85A30; color: #fff; border: none;
                                border-radius: 8px; padding: 12px 32px;
                                font-size: 1rem; font-weight: 600; cursor: pointer;
                                width: 100%;">
                                Sign In Now
                            </button>
                        </div>
                    </div>';
                }
                ?>

                <form action="login_process.php" method="POST">
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    <div style="text-align:right; width:100%; max-width:600px; margin-top:-8px; margin-bottom:10px;">
                        <a href="#" onclick="document.getElementById('forgot-modal').style.display='flex'; return false;"
                           style="font-size:0.9em; color:#FF0000; text-decoration:none; font-family:'Alegreya Sans',sans-serif;">
                            Forgot password?
                        </a>
                    </div>
                    <button type="submit">Login</button>
                </form>
                <p class="signup-text">No account? <a href="signup.php">Create one</a></p>

                <!-- Forgot Password Modal -->
                <div id="forgot-modal" style="
                    display:none; position:fixed; inset:0;
                    background:rgba(0,0,0,0.45); z-index:9999;
                    align-items:center; justify-content:center;">
                    <div style="
                        background:#fff; border-radius:16px; padding:36px 36px 40px;
                        width:90%; max-width:420px; text-align:center;
                        box-shadow:0 8px 32px rgba(0,0,0,0.18); position:relative;">
                        <button onclick="document.getElementById('forgot-modal').style.display='none'"
                            style="position:absolute; top:14px; right:16px; background:none; border:none;
                                   font-size:20px; cursor:pointer; color:#aaa; line-height:1;
                                   width:30px; height:30px; display:flex; align-items:center; justify-content:center;
                                   border-radius:50%; transition:background 0.15s;"
                            onmouseover="this.style.background='#f0f0f0'; this.style.color='#555'"
                            onmouseout="this.style.background='none'; this.style.color='#aaa'">&#x2715;</button>
                        <h2 style="font-family:'Oswald',sans-serif; font-size:1.6rem; margin:0 0 10px; color:#1a1a1a;">Forgot Password?</h2>
                        <p style="font-family:'Alegreya Sans',sans-serif; color:#666; font-size:0.95rem; margin-bottom:24px;">
                            Enter your email and we'll send you a reset link.
                        </p>
                        <div id="forgot-msg" style="display:none; margin-bottom:16px; padding:10px 14px;
                            border-radius:8px; font-size:0.9rem; text-align:left;"></div>
                        <form onsubmit="submitForgot(event)">
                            <input type="email" id="forgot-email" placeholder="Enter your email address" required style="
                                width:100%; padding:14px 16px; border:1.5px solid #ddd; border-radius:10px;
                                font-size:1rem; font-family:'Alegreya Sans',sans-serif; box-sizing:border-box;
                                margin-bottom:16px; outline:none; transition:border-color 0.2s;"
                                onfocus="this.style.borderColor='#FF0000'" onblur="this.style.borderColor='#ddd'">
                            <button type="submit" style="
                                width:100%; padding:14px; background:#FF0000; color:#fff; border:none;
                                border-radius:10px; font-size:1rem; font-weight:bold; cursor:pointer;
                                font-family:'Alegreya Sans',sans-serif; transition:background 0.2s;">
                                Send Reset Link
                            </button>
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
                        const res  = await fetch('forgot_password.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: 'email=' + encodeURIComponent(email)
                        });
                        const data = await res.json();
                        msgEl.style.display = 'block';
                        if (data.success) {
                            msgEl.style.background = '#e6f4ea';
                            msgEl.style.color = '#1e6e34';
                            msgEl.style.border = '1px solid #b7dfc4';
                            msgEl.textContent = '✓ Reset link sent! Check your inbox.';
                            document.getElementById('forgot-email').value = '';
                        } else {
                            msgEl.style.background = '#ffe5e5';
                            msgEl.style.color = '#b00020';
                            msgEl.style.border = '1px solid #f5c2c2';
                            msgEl.textContent = data.error || 'Something went wrong. Please try again.';
                        }
                    } catch (err) {
                        msgEl.style.display = 'block';
                        msgEl.style.background = '#ffe5e5';
                        msgEl.style.color = '#b00020';
                        msgEl.style.border = '1px solid #f5c2c2';
                        msgEl.textContent = 'Network error. Please try again.';
                    }
                    btn.disabled = false;
                    btn.textContent = 'Send Reset Link';
                }
                </script>
            </div>
        </div>
    </div>
</section>
</body>
</html>
