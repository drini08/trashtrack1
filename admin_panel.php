<?php
session_start();
if (!isset($_SESSION["admin_username"])) {
    header("Location: login.php");
    exit;
}

$mysqli = new mysqli("localhost", "root", "", "trashtrack");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Delete logic
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $mysqli->query("DELETE FROM trash_reports WHERE id = $id");
}

// Reply logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["reply"], $_POST["report_id"])) {
    $reply = trim($_POST["reply"]);
    $report_id = intval($_POST["report_id"]);
    $stmt = $mysqli->prepare("UPDATE trash_reports SET admin_reply = ? WHERE id = ?");
    $stmt->bind_param("si", $reply, $report_id);
    $stmt->execute();
    $stmt->close();
}

// Clean-up logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cleanup_id'])) {
    $id = intval($_POST['cleanup_id']);
    $mysqli->query("UPDATE trash_reports SET is_cleaned = 1 WHERE id = $id");
}

$uncleaned = $mysqli->query("SELECT * FROM trash_reports WHERE is_cleaned = 0 ORDER BY created_at DESC");
$cleaned = $mysqli->query("SELECT * FROM trash_reports WHERE is_cleaned = 1 ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="icon" type="image/png" href="images/icon.png"/>
  <style>
    .btn-green {
      background-color: #28a745;
      color: white;
    }
    .btn-green:hover {
      background-color: #218838;
    }
    .table-wrapper {
      margin: 30px auto;
      max-width: 95%;
    }
  </style>
</head>
<body class="bg-light">

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Welcome Admin</h2>
    <div>
      <a href="map.php" class="btn btn-green me-2" onclick="return confirm('You will be logged out. Continue?')">View Report Map</a>
      <a href="logout.php" class="btn btn-green">Logout</a>
    </div>
  </div>

  <div class="table-wrapper">
    <h4>Uncleaned Reports</h4>
    <table class="table table-bordered table-striped">
      <thead class="table-success">
        <tr>
          <th>ID</th>
          <th>Description</th>
          <th>Location</th>
          <th>Photo</th>
          <th>Reply</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $uncleaned->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['description']) ?></td>
          <td><?= htmlspecialchars($row['location']) ?></td>
          <td><img src="<?= $row['photo'] ?>" width="100" class="img-thumbnail"></td>
          <td>
            <form method="POST" class="d-flex flex-column">
              <input type="hidden" name="report_id" value="<?= $row['id'] ?>">
              <input type="text" name="reply" class="form-control mb-1" placeholder="Write reply..." value="<?= htmlspecialchars($row['admin_reply'] ?? '') ?>" required>
              <button type="submit" class="btn btn-success btn-sm">Send Reply</button>
              <?php if (!empty($row['admin_reply'])): ?>
                <small class="text-success mt-1">Replied: <?= htmlspecialchars($row['admin_reply']) ?></small>
              <?php endif; ?>
            </form>
          </td>
          <td>
            <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger w-100 mt-1" onclick="return confirm('Are you sure?')">Delete</a>
            <form method="POST">
              <input type="hidden" name="cleanup_id" value="<?= $row['id'] ?>">
              <button type="submit" class="btn btn-warning btn-sm w-100">Clean Up</button>
            </form>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <div class="table-wrapper">
    <h4>Cleaned Reports</h4>
    <table class="table table-bordered table-striped">
      <thead class="table-secondary">
        <tr>
          <th>ID</th>
          <th>Description</th>
          <th>Location</th>
          <th>Photo</th>
          <th>Reply</th>
          <th>Delete</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $cleaned->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['description']) ?></td>
          <td><?= htmlspecialchars($row['location']) ?></td>
          <td><img src="<?= $row['photo'] ?>" width="100" class="img-thumbnail"></td>
          <td><?= htmlspecialchars($row['admin_reply']) ?></td>
          <td>
            <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger w-100 mt-1" onclick="return confirm('Are you sure?')">Delete</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>