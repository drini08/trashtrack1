<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Submit Trash Report</title>
<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    font-family: 'Poppins', sans-serif;
    background: #f0f7f4;
    margin: 0;
    padding: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    gap: 25px;
  }

  .container {
    background: #fff;
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    max-width: 600px;
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 25px;
    position: relative;
    overflow: hidden;
  }

  h1 {
    color: #2c3e50;
    text-align: center;
    font-size: 32px;
    margin-bottom: 0;
  }

  .form-group {
    position: relative;
    margin-bottom: 30px; /* Increased spacing */
  }

  label {
    font-weight: 600;
    color: #34495e;
    margin-bottom: 8px;
    display: block;
  }

  textarea,
  input[type="text"],
  input[type="file"] {
    width: 100%;
    padding: 12px 15px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    background: #f9fbfd;
    transition: all 0.3s ease;
    position: relative;
    z-index: 1;
  }

  textarea:focus,
  input[type="text"]:focus,
  input[type="file"]:focus {
    background: #e6f3e6;
    outline: none;
    box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.2);
  }

  /* Preview image styling */
  #photo-preview {
    display: none;
    margin-top: 10px;
    max-width: 100%;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  }

  /* Submit Button */
  input[type="submit"] {
    width: 100%;
    background: #28a745;
    color: #fff;
    border: none;
    padding: 14px;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    transition: transform 0.3s ease, background-color 0.3s ease;
    font-weight: 600;
    position: relative;
    overflow: hidden;
  }

  input[type="submit"]:hover {
    background: #218838;
    transform: scale(1.05);
  }

  input[type="submit"]:active {
    transform: scale(0.95);
  }

  /* Responsive */
  @media (max-width: 768px) {
    body {
      padding: 20px;
    }

    .container {
      padding: 25px;
    }

    h1 {
      font-size: 24px;
    }

    textarea,
    input[type="text"],
    input[type="file"] {
      padding: 10px;
    }
  }

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
</style>
</head>
<body>

<div class="container">
  <h1>Submit Trash Report</h1>
  <form action="report.php" method="POST" enctype="multipart/form-data">
    <div class="form-group">
      <label for="description">Description</label>
      <textarea id="description" name="description" rows="4" required></textarea>
    </div>

    <div class="form-group">
      <label for="location">Location</label>
      <input type="text" id="location" name="location" required />
    </div>

    <div class="form-group">
      <label for="photo">Choose Photo</label>
      <input type="file" id="photo" name="photo" accept="image/*" required />
      <img id="photo-preview" alt="Photo preview" />
    </div>

    <input type="submit" value="Submit Report" />
  </form>
</div>

<script>
  const photoInput = document.getElementById('photo');
  const photoPreview = document.getElementById('photo-preview');

  photoInput.addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        photoPreview.setAttribute('src', e.target.result);
        photoPreview.style.display = 'block';
      }
      reader.readAsDataURL(file);
    } else {
      photoPreview.style.display = 'none';
      photoPreview.setAttribute('src', '#');
    }
  });
</script>

</body>
</html>
