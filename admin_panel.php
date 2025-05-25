<?php
session_start();
if (!isset($_SESSION["admin_username"])) {
    header("Location: login.php");
    exit;
}

$mysqli = new mysqli("localhost", "root", "", "trashtrack");

// Delete logic
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $mysqli->query("DELETE FROM trash_reports WHERE id = $id");
}

// Add cleanup reply logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["reply"])) {
        $reply = trim($_POST["reply"]);
        $report_id = intval($_POST["report_id"]);
        $stmt = $mysqli->prepare("UPDATE trash_reports SET admin_reply = ? WHERE id = ?");
        $stmt->bind_param("si", $reply, $report_id);
        $stmt->execute();
    } elseif (isset($_POST["clean_up"])) {
        // Mark the report as cleaned
        $report_id = intval($_POST["report_id"]);
        $stmt = $mysqli->prepare("UPDATE trash_reports SET is_cleaned = 1 WHERE id = ?");
        $stmt->bind_param("i", $report_id);
        $stmt->execute();
    }
}

// Fetch uncleaned reports
$uncleaned_result = $mysqli->query("SELECT * FROM trash_reports WHERE is_cleaned = 0 ORDER BY created_at DESC");

// Fetch cleaned reports
$cleaned_result = $mysqli->query("SELECT * FROM trash_reports WHERE is_cleaned = 1 ORDER BY created_at DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Panel</title>
    <link href="apanel.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

</head>
<body>
<h2>Welcome Admin</h2>

<div class="mb-3">
  <a href="logout_and_map.php" class="btn btn-green me-2">View Report Map</a>
  <a href="logout.php" class="btn btn-green">Logout</a>
</div>


<h3>Uncleaned Trash Reports</h3>
<table border="1">
  <tr>
    <th>ID</th>
    <th>Description</th>
    <th>Location</th>
    <th>Photo</th>
    <th>Reply</th>
    <th>Actions</th>
  </tr>
  <?php while ($row = $uncleaned_result->fetch_assoc()): ?>
  <tr>
    <td><?= $row['id'] ?></td>
    <td><?= htmlspecialchars($row['description']) ?></td>
    <td><?= htmlspecialchars($row['location']) ?></td>
    <td><img src="<?= $row['photo'] ?>" width="100"></td>
    <td>
      <form method="POST">
        <input type="hidden" name="report_id" value="<?= $row['id'] ?>">
        <input type="text" name="reply" value="<?= htmlspecialchars($row['admin_reply'] ?? '') ?>">
        <button type="submit">Reply</button>
      </form>
    </td>
    <td>
      <form method="POST" style="display:inline-block;">
        <input type="hidden" name="report_id" value="<?= $row['id'] ?>">
        <button type="submit" name="clean_up" onclick="return confirm('Mark this report as cleaned?')">Clean Up</button>
      </form>
      <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
    </td>
  </tr>
  <?php endwhile; ?>
</table>

<h3>Cleaned Trash Reports</h3>
<table border="1">
  <tr>
    <th>ID</th>
    <th>Description</th>
    <th>Location</th>
    <th>Photo</th>
    <th>Actions</th>
  </tr>
  <?php while ($row = $cleaned_result->fetch_assoc()): ?>
  <tr>
    <td><?= $row['id'] ?></td>
    <td><?= htmlspecialchars($row['description']) ?></td>
    <td><?= htmlspecialchars($row['location']) ?></td>
    <td><img src="<?= $row['photo'] ?>" width="100"></td>
    <td>
      <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
    </td>
  </tr>
  <?php endwhile; ?>
</table>

</body>
</html>
