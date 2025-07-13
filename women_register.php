<?php
$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // DB connection
    $conn = new mysqli("localhost", "root", "", "shefund");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email exists
    $check = "SELECT * FROM women WHERE email='$email'";
    $res = $conn->query($check);

    if ($res->num_rows > 0) {
        $error = "Email already registered!";
    } else {
        $sql = "INSERT INTO women (name, email, password) VALUES ('$name', '$email', '$password')";
        if ($conn->query($sql)) {
            $success = "Registered Successfully!";
        } else {
            $error = "Registration Failed. Try again!";
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register - SheFund</title>
  <style>
    * {
      margin: 0; padding: 0; box-sizing: border-box;
      font-family: Arial, sans-serif;
    }
    body {
      background: linear-gradient(to right, #ffe5d9, #fad2e1);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .form-container {
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
      width: 350px;
      text-align: center;
    }
    .form-container h2 {
      margin-bottom: 20px;
      color: #d63384;
    }
    .input-group {
      margin-bottom: 15px;
      text-align: left;
    }
    .input-group label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
    }
    .input-group input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    .btn {
      background-color: #d63384;
      color: white;
      border: none;
      padding: 10px;
      width: 100%;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }
    .btn:hover {
      background-color: #b71e6a;
    }
    .link {
      margin-top: 10px;
      display: block;
      color: #d63384;
      text-decoration: none;
      font-size: 14px;
    }
    .link:hover {
      text-decoration: underline;
    }
    .message {
      padding: 10px;
      border-radius: 5px;
      margin-bottom: 15px;
      font-size: 15px;
    }
    .success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }
    .error {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }
  </style>
</head>
<body>

<div class="form-container">
  <h2>Register as Woman</h2>

  <?php if ($success): ?>
    <div class="message success"><?= $success ?></div>
  <?php elseif ($error): ?>
    <div class="message error"><?= $error ?></div>
  <?php endif; ?>

  <form method="POST" action="">
    <div class="input-group">
      <label for="name">Full Name</label>
      <input type="text" name="name" required>
    </div>
    <div class="input-group">
      <label for="email">Email ID</label>
      <input type="email" name="email" required>
    </div>
    <div class="input-group">
      <label for="password">Password</label>
      <input type="password" name="password" required>
    </div>
    <button type="submit" class="btn">Register</button>
  </form>

  <a href="women_login.php" class="link">Already have an account? Login here</a>
</div>

</body>
</html>
