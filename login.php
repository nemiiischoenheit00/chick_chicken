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
                    <button type="submit">Login</button>
                </form>
                <p class="signup-text">No account? <a href="signup.php">Create one</a></p>
            </div>
        </div>
    </div>
</section>
</body>
</html>
