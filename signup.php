<?php
if (isset($_GET['error'])) {
    $errors = [
        'invalid_email'     => 'Please enter a valid email address.',
        'password_mismatch' => 'Passwords do not match.',
        'weak_password'     => 'Password must be at least 6 characters and contain letters and numbers. No spaces allowed.',
        'email_exists'      => 'An account with that email already exists.',
        'server_error'      => 'Something went wrong. Please try again.',
    ];
    $error_msg = $errors[$_GET['error']] ?? 'An error occurred.';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="signup.css">
</head>

<body>
    <div class="login-container">
        <h2>Create an Account</h2>
        <p class="subtitle">Already have an account? <a href="login.php">Login</a></p>

        <?php if (isset($error_msg)): ?>
            <p class="error-msg"><?= htmlspecialchars($error_msg) ?></p>
        <?php endif; ?>

        <form action="signup_process.php" method="POST" onsubmit="return validatePhone()">

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
                    <!-- PH flag inlined as SVG — no external request, no lag -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="14" viewBox="0 0 900 600" aria-label="PH">
                        <rect width="900" height="600" fill="#0038A8"/>
                        <rect width="900" height="300" fill="#CE1126"/>
                        <polygon points="0,0 0,600 450,300" fill="#FCD116"/>
                        <!-- Sun -->
                        <circle cx="225" cy="300" r="60" fill="#FCD116"/>
                        <!-- Sun rays (8 rays) -->
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
                        <!-- 3 stars -->
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
            <span id="phone-error" style="display:none; color:#b00020; font-size:0.85em; margin-top:-8px; margin-bottom:8px; display:block;"></span>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email address" required>

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

            <p class="terms-notice">
                By continuing, you agree to our
                <a href="terms.php">Terms &amp; Conditions</a> and
                <a href="privacy.php">Privacy Policy</a>
            </p>

            <button type="submit">Sign Up</button>
        </form>
    </div>

    <script>
        const phoneInput = document.getElementById('phone');
        const phoneError = document.getElementById('phone-error');

        // Live: strip non-digits, remove leading zeros, cap at 10
        phoneInput.addEventListener('input', function () {
            let val = this.value.replace(/\D/g, '');   // digits only
            val = val.replace(/^0+/, '');               // no leading zeros
            if (val.length > 10) val = val.slice(0, 10); // max 10 digits
            this.value = val;
            phoneError.style.display = 'none';
        });

        // Block non-numeric key presses
        phoneInput.addEventListener('keydown', function (e) {
            const allowed = ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Home','End'];
            if (!allowed.includes(e.key) && !/^\d$/.test(e.key)) {
                e.preventDefault();
            }
        });

        // Prevent paste of non-numeric / leading-zero content
        phoneInput.addEventListener('paste', function (e) {
            e.preventDefault();
            let pasted = (e.clipboardData || window.clipboardData).getData('text');
            let clean  = pasted.replace(/\D/g, '').replace(/^0+/, '').slice(0, 10);
            this.value = clean;
        });

        // Final validation on submit
        function validatePhone() {
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