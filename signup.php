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

        <?php
        if (isset($_GET['error'])) {
            $errors = [
                'invalid_email'     => 'Please enter a valid email address.',
                'password_mismatch' => 'Passwords do not match.',
                'weak_password'     => 'Password must be at least 6 characters and contain letters and numbers. No spaces allowed.',
                'email_exists'      => 'An account with that email already exists.',
                'server_error'      => 'Something went wrong. Please try again.',
            ];
            $msg = $errors[$_GET['error']] ?? 'An error occurred.';
            echo '<p class="error-msg">' . htmlspecialchars($msg) . '</p>';
        }
        ?>

        <form action="signup_process.php" method="POST">

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
                    <img src="https://flagcdn.com/ph.svg" width="20" alt="PH">
                    <span>+63</span>
                </div>
                <input type="tel" id="phone" name="phone" placeholder="9171234567" required>
            </div>

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
</body>

</html>
