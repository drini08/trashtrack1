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
<style>
  body {
    font-family: Arial, sans-serif;
    background: #f0f7f4;
    padding: 40px;
    display: flex;
    justify-content: center;
  }
  .container {
    background: white;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0px 6px 15px rgba(0,0,0,0.1);
    max-width: 650px;
    width: 100%;
    box-sizing: border-box;
  }
  h1 {
    text-align: center;
    margin-bottom: 25px;
    color: #2c3e50;
  }
  label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #34495e;
  }
  input[type="text"],
  textarea,
  input[type="file"] {
    width: 100%;
    max-width: 540px;
    padding: 15px 15px;
    border: 1px solid #ccc;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 16px;
    background: #f9fbfd;
    transition: border-color 0.3s ease;
  }
  input[type="text"]:focus,
  textarea:focus,
  input[type="file"]:focus {
    border-color: #28a745;
    outline: none;
  }
  textarea {
    resize: vertical;
    min-height: 80px;
  }
  input[type="submit"] {
    background: #28a745;
    border: none;
    color: white;
    font-weight: 700;
    font-size: 18px;
    padding: 14px;
    border-radius: 10px;
    cursor: pointer;
    width: 100%;
    transition: background-color 0.3s ease;
  }
  input[type="submit"]:hover {
    background: #218838;
  }
  .message {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 8px;
    text-align: center;
    font-weight: 600;
  }
  .success {
    background-color: #d4edda;
    color: #155724;
  }
  .error {
    background-color: #f8d7da;
    color: #721c24;
  }

  /* Back button styles */
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
</style>
</head>
<body>

<a href="index.php" class="back-button" aria-label="Go back to homepage">
  <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <path d="M15 18l-6-6 6-6" stroke="white" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
  </svg>
</a>


<div class="container">
  <h1>Submit Trash Report</h1>

  <?php if($message): ?>
    <div class="message <?= $messageClass; ?>">
      <?= htmlspecialchars($message); ?>
    </div>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data" action="">
    <label for="description">Description</label>
    <textarea id="description" name="description" required placeholder="Describe the trash or issue..."></textarea>

    <label for="location">Location</label>
    <input type="text" id="location" name="location" required placeholder="Enter location (e.g. Ferizaj street)" />

    <label for="photo">Upload Photo</label>
    <input type="file" id="photo" name="photo" accept="image/*" required />
    <img id="preview-image" style="display:none; max-width: 100%; margin-top: 10px; border-radius: 8px;" alt="Image preview">

    <input type="submit" value="Submit Report" />
  </form>
</div>

</body>
</html>

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
</script>

