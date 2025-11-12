<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="login.css">
</head>
<body>
<section class="login">
    <div class="separator">
        <div class="greetings">
            <h1>GREETINGS, OUR BELOVED CUSTOMER!</h1>
            <p>Welcome to Chick Chicken! Sign in to be part of our growing family of chicken lovers. Whether you’re here to browse, order, or just check out what’s new, we’re happy to have you around. Go ahead — sign in and let’s make your day a little more delicious.</p>
        </div>

        <div class="login-form">
            <div class="form-box">
                <img src="assets\Logo2.png" alt="Chick Chicken Logo" class="logo">
                <h2>User Login</h2>
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