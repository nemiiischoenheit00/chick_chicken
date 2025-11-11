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
    <h2>Sign Up</h2>
    <form action="signup_process.php" method="POST">

      <label for="name">Name</label>
      <input type="text" id="name" name="name" placeholder="Enter username or email" required>

      <label for="login">Email</label>
      <input type="text" id="email" name="email" placeholder="Enter username or email" required>

      <label for="password">Password</label>
      <input type="password" id="password" name="password" placeholder="Enter password" required>

      <button type="submit">Sign Up</button>
    </form>
    <p>Already have an account? <a href="login.php">Login</a></p>
  </div>
</body>
</html>