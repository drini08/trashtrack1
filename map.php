<?php
// Connect to DB and fetch reports
$mysqli = new mysqli("localhost", "root", "", "trashtrack");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
$sql = "SELECT description, location, photo, latitude, longitude FROM trash_reports WHERE is_cleaned = 0";

$result = $mysqli->query($sql);
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
<meta name="viewport" content="width=device-width, initial-scale=1" />
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<style>
  body {
    font-family: Arial, sans-serif;
    background: #f7f9fc;
    padding: 20px;
    margin: 0;
    text-align: center;
  }
  #map {
    height: 600px;
    width: 100%;
    max-width: 900px;
    margin: 0 auto;
    border: 1px solid #ccc;
    border-radius: 8px;
  }
  .back-button {
    position: fixed;
    bottom: 20px;
    left: 20px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background-color: #3a7d44;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    text-decoration: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    z-index: 1000;
  }
  .back-button:hover {
    background-color: #519b59;
  }
  .back-button svg {
    fill: white;
    width: 24px;
    height: 24px;
  }
  #toggleHeatmapBtn {
    margin-bottom: 15px;
  }
</style>
</head>
<body>

<a href="index.php" class="back-button" aria-label="Go back to homepage">
  <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <path d="M15 18l-6-6 6-6" stroke="white" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
  </svg>
</a>

<h1>Trash Reports Map</h1>

<button id="toggleHeatmapBtn" class="btn btn-primary">Show Heat Map</button>

<div id="map"></div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<!-- Leaflet.heat plugin -->
<script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>

<script>
  // Initialize map centered on Kosovo approx coords
  const map = L.map('map').setView([42.6, 20.9], 11);

  // Add OpenStreetMap tiles
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 18,
      attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);

  // Reports data from PHP to JS
  const reports = <?php echo json_encode($reports, JSON_HEX_TAG); ?>;

  // Create markers and add them to map, keep reference for toggling
  const markers = [];
  reports.forEach(report => {
    if (!report.latitude || !report.longitude) return;
    const marker = L.marker([parseFloat(report.latitude), parseFloat(report.longitude)]);
    marker.bindPopup(`
      <strong>Description:</strong> ${report.description}<br>
      <strong>Location:</strong> ${report.location}<br>
      <img src="${report.photo}" alt="Photo" style="max-width:150px; margin-top:5px;" />
    `);
    marker.addTo(map);
    markers.push(marker);
  });

  // Prepare heatmap data with some intensity variation
  const heatData = reports
    .filter(r => r.latitude && r.longitude)
    .map(r => [parseFloat(r.latitude), parseFloat(r.longitude), 1 + Math.random() * 2]);

  // Create heatmap layer (not added initially)
  const heatmapLayer = L.heatLayer(heatData, {
    radius: 35,
    blur: 25,
    maxZoom: 17,
    max: 20
  });

  // Toggle heatmap button logic
  const btn = document.getElementById('toggleHeatmapBtn');
  let heatmapVisible = false;

  btn.addEventListener('click', () => {
    if (!heatmapVisible) {
      // Remove markers
      markers.forEach(m => map.removeLayer(m));
      // Add heatmap
      heatmapLayer.addTo(map);
      btn.textContent = "Show Pins";
    } else {
      // Remove heatmap
      map.removeLayer(heatmapLayer);
      // Add markers
      markers.forEach(m => m.addTo(map));
      btn.textContent = "Show Heat Map";
    }
    heatmapVisible = !heatmapVisible;
  });
</script>

</body>
</html>
