<?php
// map.php example with back button

$mysqli = new mysqli("localhost", "root", "", "trashtrack");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$result = $mysqli->query("SELECT id, description, location, photo, latitude, longitude FROM trash_reports WHERE latitude IS NOT NULL AND longitude IS NOT NULL");

$reports = [];
while ($row = $result->fetch_assoc()) {
    $reports[] = $row;
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Trash Reports Map</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<style>
  body {
    font-family: Arial, sans-serif;
    background: #f7f9fc;
    padding: 20px;
    text-align: center;
  }

  h1 {
    margin-bottom: 10px;
  }

  #map {
    height: 600px;
    width: 100%;
    max-width: 900px;
    margin: 20px auto;
    border: 1px solid #ccc;
    border-radius: 8px;
  }

  /* Back button styles */
.back-button {
  position: fixed;
  bottom: 20px;
  left: 20px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background-color: #3a7d44; /* lighter green */
  width: 50px;
  height: 50px;
  border-radius: 50%;
  text-decoration: none;
  cursor: pointer;
  transition: background-color 0.3s ease;
  box-shadow: 0 2px 6px rgba(0,0,0,0.2);
  z-index: 1000; /* stay above other content */
}

.back-button:hover {
  background-color: #519b59; /* slightly lighter on hover */
}

.back-button svg {
  fill: white;
  width: 24px;
  height: 24px;
}
</style>

<a href="index.php" class="back-button" aria-label="Go back to homepage">
  <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <path d="M15 18l-6-6 6-6" stroke="white" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
  </svg>
</a>

<h1>Trash Reports Map</h1>
<div id="map"></div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
  // Initialize map centered on Kosovo approx coords
  const map = L.map('map').setView([42.6, 20.9], 11);

  // Add OpenStreetMap tiles
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 18,
  }).addTo(map);

  // Reports data from PHP to JS
  const reports = <?php echo json_encode($reports, JSON_HEX_TAG); ?>;

  reports.forEach(report => {
      const marker = L.marker([report.latitude, report.longitude]).addTo(map);
      const popupContent = `
          <strong>Description:</strong> ${report.description}<br>
          <strong>Location:</strong> ${report.location}<br>
          <img src="${report.photo}" alt="Photo" style="max-width:150px; margin-top:5px;" />
      `;
      marker.bindPopup(popupContent);
  });
</script>

</body>
</html>
