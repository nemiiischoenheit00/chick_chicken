<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="Image" href="assets/Logo.png"/>
    <title>Admin Sign Up | Chick Chicken</title>
    <style>@import url('https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap');</style>
    <style>@import url('https://fonts.googleapis.com/css2?family=Alegreya+Sans:ital,wght@0,100;0,300;0,400;0,500;0,700;0,800;0,900;1,100;1,300;1,400;1,500;1,700;1,800;1,900&family=Oswald:wght@200..700&display=swap');</style>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --mustard: #FFDE59;
            --red:     #FF0000;
            --black:   #000;
            --wine-red:#9A0404;
            --white:   #ffffff;
        }

        body {
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-container {
            width: 90%;
            max-width: 620px;
            background-color: #fff;
            margin: 50px auto;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        /* Admin badge at top */
        .admin-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .admin-badge {
            display: inline-block;
            background: #1a1a1a;
            color: var(--mustard);
            font-family: 'Oswald', sans-serif;
            font-size: 0.78em;
            letter-spacing: 3px;
            padding: 5px 16px;
            border-radius: 4px;
            margin-bottom: 14px;
        }

        .login-container h2 {
            text-align: center;
            font-size: 1.8rem;
            color: #222;
            margin-bottom: 8px;
            font-family: 'Oswald', sans-serif;
        }

        .subtitle {
            text-align: center;
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 30px;
        }

        .subtitle a {
            color: var(--red);
            text-decoration: none;
            font-weight: bold;
        }

        .subtitle a:hover { text-decoration: underline; }

        /* Error message */
        .error-msg {
            background: #ffe5e5;
            color: #b00020;
            border: 1px solid #f5c2c2;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }

        /* Side-by-side row */
        .name-row {
            display: flex;
            gap: 15px;
            width: 100%;
            margin-bottom: 20px;
        }

        .input-group {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* Labels */
        .login-container label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
            color: #333;
            font-size: 0.9rem;
        }

        /* Inputs */
        .login-container input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
            box-sizing: border-box;
            outline: none;
            transition: border-color 0.2s;
        }

        .login-container input:focus {
            border-color: #1a1a1a;
        }

        input[type="email"] {
            margin-bottom: 20px;
        }

        /* Phone wrapper */
        .phone-input-wrapper {
            display: flex;
            align-items: center;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .phone-input-wrapper:focus-within {
            border-color: #1a1a1a;
        }

        .country-code {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 0 12px;
            background-color: #f9f9f9;
            border-right: 1px solid #ccc;
            height: 100%;
            min-height: 44px;
            white-space: nowrap;
        }

        .country-code span {
            font-size: 0.9rem;
            font-weight: bold;
            color: #333;
        }

        .phone-input-wrapper input[type="text"] {
            border: none;
            border-radius: 0;
            flex: 1;
        }

        .phone-input-wrapper input[type="text"]:focus {
            border-color: transparent;
            box-shadow: none;
        }

        .phone-error {
            display: none;
            color: #b00020;
            font-size: 0.85em;
            margin-top: -14px;
            margin-bottom: 12px;
        }

        /* Admin key field */
        .admin-key-wrap {
            margin-bottom: 20px;
        }

        .admin-key-note {
            font-size: 0.8rem;
            color: #888;
            margin-top: 4px;
        }

        /* Terms */
        .terms-notice {
            text-align: center;
            font-size: 0.82rem;
            color: #666;
            margin-bottom: 24px;
        }

        .terms-notice a {
            color: var(--red);
            text-decoration: none;
            font-weight: bold;
        }

        .terms-notice a:hover { text-decoration: underline; }

        /* Submit button */
        button[type="submit"] {
            width: 100%;
            padding: 14px;
            margin-top: 8px;
            background-color: #1a1a1a;
            color: var(--mustard);
            font-size: 1rem;
            font-weight: bold;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-family: 'Oswald', sans-serif;
            letter-spacing: 1px;
            transition: background-color 0.2s;
        }

        button[type="submit"]:hover {
            background-color: #333;
        }

        /* Divider */
        .divider {
            border: none;
            border-top: 1px solid #eee;
            margin: 24px 0;
        }
    </style>
</head>
<body>

<?php
if (isset($_GET['error'])) {
    $errors = [
        'invalid_email'     => 'Please enter a valid email address.',
        'password_mismatch' => 'Passwords do not match.',
        'weak_password'     => 'Password must be at least 6 characters and contain letters and numbers. No spaces allowed.',
        'email_exists'      => 'An account with that email already exists.',
        'invalid_key'       => 'Invalid admin registration key.',
        'server_error'      => 'Something went wrong. Please try again.',
    ];
    $error_msg = $errors[$_GET['error']] ?? 'An error occurred.';
}
?>

<div class="login-container">

    <div class="admin-header">
        <div class="admin-badge">⚙ ADMIN PORTAL</div>
    </div>

    <h2>Create Admin Account</h2>
    <p class="subtitle">Already have an account? <a href="admin_login.php">Login</a></p>

    <?php if (isset($error_msg)): ?>
        <p class="error-msg"><?= htmlspecialchars($error_msg) ?></p>
    <?php endif; ?>

    <form action="admin_signup_process.php" method="POST" onsubmit="return validateForm()">

        <div class="name-row">
            <div class="input-group">
                <label for="Fname">First name</label>
                <input type="text" id="Fname" name="Fname" placeholder="Enter first name" required>
            </div>
            <div class="input-group">
                <label for="Lname">Last name</label>
                <input type="text" id="Lname" name="Lname" placeholder="Enter last name" required>
            </div>
        </div>

        <label for="phone">Mobile number</label>
        <div class="phone-input-wrapper">
            <div class="country-code">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="14" viewBox="0 0 900 600" aria-label="PH">
                    <rect width="900" height="600" fill="#0038A8"/>
                    <rect width="900" height="300" fill="#CE1126"/>
                    <polygon points="0,0 0,600 450,300" fill="#FCD116"/>
                    <circle cx="225" cy="300" r="60" fill="#FCD116"/>
                    <g fill="#FCD116">
                        <rect x="219" y="220" width="12" height="50" rx="5" transform="rotate(0,225,300)"/>
                        <rect x="219" y="220" width="12" height="50" rx="5" transform="rotate(45,225,300)"/>
                        <rect x="219" y="220" width="12" height="50" rx="5" transform="rotate(90,225,300)"/>
                        <rect x="219" y="220" width="12" height="50" rx="5" transform="rotate(135,225,300)"/>
                        <rect x="219" y="220" width="12" height="50" rx="5" transform="rotate(180,225,300)"/>
                        <rect x="219" y="220" width="12" height="50" rx="5" transform="rotate(225,225,300)"/>
                        <rect x="219" y="220" width="12" height="50" rx="5" transform="rotate(270,225,300)"/>
                        <rect x="219" y="220" width="12" height="50" rx="5" transform="rotate(315,225,300)"/>
                    </g>
                    <polygon points="225,130 231,150 252,150 236,162 242,182 225,170 208,182 214,162 198,150 219,150" fill="#FCD116" transform="scale(0.5) translate(225,100)"/>
                    <polygon points="100,450 106,470 127,470 111,482 117,502 100,490 83,502 89,482 73,470 94,470" fill="#FCD116" transform="scale(0.5) translate(-30,-80)"/>
                    <polygon points="350,450 356,470 377,470 361,482 367,502 350,490 333,502 339,482 323,470 344,470" fill="#FCD116" transform="scale(0.5) translate(-30,-80)"/>
                </svg>
                <span>+63</span>
            </div>
            <input
                type="text"
                id="phone"
                name="phone"
                placeholder="9171234567"
                inputmode="numeric"
                maxlength="10"
                autocomplete="tel"
                required>
        </div>
        <span id="phone-error" class="phone-error"></span>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Enter admin email address" required>

        <div class="name-row">
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter password" required>
            </div>
            <div class="input-group">
                <label for="confirm_password">Confirm password</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password" required>
            </div>
        </div>

        <hr class="divider">

        <!-- Admin registration key -->
        <div class="admin-key-wrap">
            <label for="admin_key">Admin Registration Key</label>
            <input type="password" id="admin_key" name="admin_key" placeholder="Enter the admin key" required>
            <p class="admin-key-note">Contact the system owner to obtain this key.</p>
        </div>

        <p class="terms-notice">
            By continuing, you agree to our
            <a href="terms.php">Terms &amp; Conditions</a> and
            <a href="privacy.php">Privacy Policy</a>
        </p>

        <button type="submit">Create Admin Account</button>
    </form>
</div>

<script>
    const phoneInput = document.getElementById('phone');
    const phoneError = document.getElementById('phone-error');

    phoneInput.addEventListener('input', function () {
        let val = this.value.replace(/\D/g, '');
        val = val.replace(/^0+/, '');
        if (val.length > 10) val = val.slice(0, 10);
        this.value = val;
        phoneError.style.display = 'none';
    });

    phoneInput.addEventListener('keydown', function (e) {
        const allowed = ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Home','End'];
        if (!allowed.includes(e.key) && !/^\d$/.test(e.key)) e.preventDefault();
    });

    phoneInput.addEventListener('paste', function (e) {
        e.preventDefault();
        let pasted = (e.clipboardData || window.clipboardData).getData('text');
        let clean  = pasted.replace(/\D/g, '').replace(/^0+/, '').slice(0, 10);
        this.value = clean;
    });

    function validateForm() {
        const val = phoneInput.value;
        if (!/^[1-9]\d{9}$/.test(val)) {
            phoneError.textContent = 'Please enter a valid 10-digit number with no leading zero.';
            phoneError.style.display = 'block';
            phoneInput.focus();
            return false;
        }
        return true;
    }
</script>

</body>
</html>