<?php
// Function to geocode location string to lat/lon using Nominatim API
function geocodeLocation($location) {
    $location = urlencode($location);
    $url = "https://nominatim.openstreetmap.org/search?q={$location}&format=json&limit=1";

    $opts = [
        "http" => [
            "header" => "User-Agent: TrashTrackApp/1.0\r\n"
        ]
    ];
    $context = stream_context_create($opts);

    $response = file_get_contents($url, false, $context);
    $data = json_decode($response);

    if (!empty($data)) {
        return [
            'lat' => $data[0]->lat,
            'lon' => $data[0]->lon
        ];
    }
    return null;
}

$message = "";
$messageClass = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $description = htmlspecialchars(trim($_POST["description"]));
    $location = htmlspecialchars(trim($_POST["location"]));

    // Geocode location
    $coords = geocodeLocation($location);
    if (!$coords) {
        $message = "Could not find coordinates for that location. Please enter a valid location.";
        $messageClass = "error";
    } else {
        $latitude = $coords['lat'];
        $longitude = $coords['lon'];

        // Handle file upload
        if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] === UPLOAD_ERR_OK) {
            $uploadDir = "uploads/";
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = basename($_FILES["photo"]["name"]);
            $targetFile = $uploadDir . uniqid() . "_" . $fileName;

            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
                // Connect to DB
                $mysqli = new mysqli("localhost", "root", "", "trashtrack");
                if ($mysqli->connect_error) {
                    die("Connection failed: " . $mysqli->connect_error);
                }

                // Insert into DB
                $stmt = $mysqli->prepare("INSERT INTO trash_reports (description, location, photo, latitude, longitude) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $description, $location, $targetFile, $latitude, $longitude);

                if ($stmt->execute()) {
                    $message = "Report submitted successfully.";
                    $messageClass = "success";
                } else {
                    $message = "Error saving report: " . $stmt->error;
                    $messageClass = "error";
                }

                $stmt->close();
                $mysqli->close();
            } else {
                $message = "Failed to upload the photo.";
                $messageClass = "error";
            }
        } else {
            $message = "Please upload a valid image file.";
            $messageClass = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Submit Trash Report</title>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<!-- FontAwesome CDN -->
<link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
/>
<!-- Link to the external CSS -->
<link rel="stylesheet" href="report.css" />
</head>
<body>

<a href="index.php" class="back-button" aria-label="Go back to homepage">
  <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <path d="M15 18l-6-6 6-6" stroke="white" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
  </svg>
</a>

<div class="page-wrapper">
  <!-- Left side panel -->
  <aside class="side-panel left-panel" aria-label="Information about reporting trash">
    <h3><i class="fas fa-info-circle"></i> How to Report</h3>
    <p>
      Use the form to describe the trash or issue. Include a location and upload a photo if possible.
    </p>

    <h3><i class="fas fa-question-circle"></i> FAQ</h3>
    <ul class="faq-list">
      <li><strong>Can I report anonymously?</strong> Yes, no personal info is required.</li>
      <li><strong>What locations can I report?</strong> Any public places where trash is an issue.</li>
      <li><strong>Is photo upload mandatory?</strong> It helps, but you can still report without it.</li>
    </ul>

    <a href="index.php" class="call-to-action" aria-label="Return to homepage">
      <i class="fas fa-home"></i> Back to Home
    </a>
  </aside>

  <!-- Main form container -->
  <main class="container" role="main">
    <h1>Submit Trash Report</h1>

    <?php if ($message): ?>
      <div class="message <?= htmlspecialchars($messageClass); ?>">
        <?= htmlspecialchars($message); ?>
      </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" action="">
      <label for="description">Description</label>
      <textarea id="description" name="description" required placeholder="Describe the trash or issue..."></textarea>

      <label for="location">Location</label>
      <input type="text" id="location" name="location" required placeholder="Enter location (e.g. Ferizaj street)" />

      <label for="photo">Upload Photo</label>
      <input type="file" id="photo" name="photo" accept="image/*" />
      <img id="preview-image" alt="Image preview" />

      <input type="submit" value="Submit Report" />
    </form>
  </main>





  <!-- Right side panel -->
  <aside class="side-panel right-panel" aria-label="Join the zero waste movement">
    <h3><i class="fas fa-hands-helping"></i> Join the Movement</h3>
    <p>
      Whether you're a concerned citizen, organization, or municipality, you can make a difference. Report trash, spread the word, and be part of the zero-waste movement!
    </p>
    <h3><i class="fas fa-map-marked-alt"></i> Pin It to Clean It</h3>
<p>
  Mark a location on the map where trash has been spotted by creating a pin. Once pinned, our community can take action to help clean it up together.
</p>
    <a href="map.php" class="call-to-action" aria-label="Submit a trash report">
      <i class="fas fa-clipboard-check"></i> View Report Map
    </a>
  </aside>
</div>


<script>
  const fileInput = document.querySelector('input[type="file"]');
  const previewImage = document.getElementById('preview-image');

  fileInput.addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.addEventListener("load", function () {
        previewImage.setAttribute("src", this.result);
        previewImage.style.display = "block";
      });
      reader.readAsDataURL(file);
    } else {
      previewImage.style.display = "none";
    }
  });


    window.addEventListener('DOMContentLoaded', () => {
    const mapButtonWrapper = document.querySelector('.map-button-wrapper');
    if (mapButtonWrapper) {
      mapButtonWrapper.classList.add('visible');
    }
  });
</script>

</body>
</html>

