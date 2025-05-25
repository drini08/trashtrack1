<?php
session_start();

if (isset($_SESSION['admin_username'])) {
    header("Location: admin_panel.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $mysqli = new mysqli("localhost", "root", "", "trashtrack");

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $stmt = $mysqli->prepare("SELECT password_hash FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($password_hash);
        $stmt->fetch();

        if (password_verify($password, $password_hash)) {
            $_SESSION['admin_username'] = $username;
            header("Location: admin_panel.php");
            exit();
        } else {
            $message = "Invalid password.";
        }
    } else {
        $message = "Admin user not found.";
    }

    $stmt->close();
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="login.css" rel="stylesheet"/>
  
</head>
<body>

  <div class="login-card text-center">
    <img src="images/logo.png" alt="Logo" class="logo mx-auto d-block" />
    <h3 class="mb-3">Admin Login</h3>
    <?php if ($message): ?>
      <div class="alert alert-danger py-1" role="alert">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>
    <form method="POST" class="text-start">
      <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control" placeholder="Enter username" required />
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Enter password" required />
      </div>
      <button type="submit" class="btn btn-green w-100">Login</button>
    </form>
  </div>

  <a href="index.php" class="back-button" aria-label="Go back to homepage">
  <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <path d="M15 18l-6-6 6-6" stroke="white" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
  </svg>
</a>

</body>
</html>
