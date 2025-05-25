<?php
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];

    if ($password !== $confirmPassword) {
        $message = "Passwords do not match.";
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $mysqli = new mysqli("localhost", "root", "", "trashtrack");

        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Check if username already exists
        $check = $mysqli->prepare("SELECT id FROM admins WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "Username already taken.";
        } else {
            // Insert admin
            $stmt = $mysqli->prepare("INSERT INTO admins (username, password_hash, created_at) VALUES (?, ?, NOW())");
            $stmt->bind_param("ss", $username, $password_hash);
            if ($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $message = "Error creating account.";
            }
            $stmt->close();
        }

        $check->close();
        $mysqli->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Signup</title>
  <!-- Bootstrap CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="signup.css" rel="stylesheet"/>
  <link rel="icon" type="image/png" href="images/icon.png"/>
  <style>
   
  </style>
</head>
<body>

  <div class="signup-card text-center">
    <img src="images/logo.png" alt="Logo" class="logo mx-auto d-block" />
    <h3 class="mb-3">Admin Sign Up</h3>
    <?php if ($message): ?>
      <div class="alert alert-danger py-1" role="alert">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>
    <form method="POST" class="text-start" novalidate>
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input id="username" type="text" name="username" class="form-control" placeholder="Enter username" required />
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input id="password" type="password" name="password" class="form-control" placeholder="Enter password" required />
      </div>
      <div class="mb-3">
        <label for="confirm_password" class="form-label">Confirm Password</label>
        <input id="confirm_password" type="password" name="confirm_password" class="form-control" placeholder="Confirm password" required />
      </div>
      <button type="submit" class="btn btn-green w-100">Sign Up</button>
    </form>
  </div>

  <a href="index.php" class="back-button" aria-label="Go back to homepage">
    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
      <path d="M15 18l-6-6 6-6" stroke="white" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
  </a>

</body>
</html>