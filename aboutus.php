<?php
// aboutus.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>About Us | TrashTrack Kosovo</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="aboutus.css" rel="stylesheet"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="icon" type="image/png" href="images/icon.png"/>
  
</head>
<body class="about">


<!-- HEADER -->
<header class="site-header">
  <div class="container">
    <div class="logo">
      <a href="index.php"><img src="images/logo.png" alt="TrashTrack Logo" /></a>
    </div>
    <nav class="main-nav">
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="aboutus.php" class="active">About Us</a></li>
        <li><a href="map.php">Map</a></li>
        <li><a href="report.php"> Submit Report</a></li>
        <li><a href="login.php" class="btn login-btn">Login</a></li>
        <li class="signup"><a href="signup.php" class="btn signup-btn">Sign Up</a></li>
      </ul>
    </nav>
  </div>
</header>

<!-- MAIN CONTENT -->
<main class="about-main">
  <section class="hero">
    <h1>About TrashTrack Kosovo</h1>
    <p>Empowering communities to report, track, and eliminate waste.</p>
  </section>

  <section class="about-section">
    <h2>Our Mission</h2>
    <p>TrashTrack Kosovo is dedicated to building a cleaner, greener Kosovo by leveraging technology to fight pollution. Our mission is to make waste reporting simple, accessible, and transparent for everyone.</p>
  </section>

  <section class="about-section">
    <h2>What We Do</h2>
    <ul>
      <li>Allow citizens to report illegal dumping and trash spots via a simple online form.</li>
      <li>Display all reported trash on an interactive map with location, photos, and descriptions.</li>
      <li>Collaborate with municipalities and NGOs to act on reports and organize clean-up events.</li>
      <li>Raise awareness and educate the public on waste reduction, recycling, and environmental responsibility.</li>
    </ul>
  </section>

  <section class="about-section">
    <h2>Why It Matters</h2>
    <p>Illegal dumping and unmanaged waste harm public health, water sources, and local ecosystems. TrashTrack Kosovo bridges the gap between awareness and action by giving power to the people to report, visualize, and follow progress on trash-related issues.</p>
  </section>

  <section class="about-section">
    <h2>How It Works</h2>
    <ol>
      <li>Users fill out a report with a location, description, and optional photo.</li>
      <li>The report appears as a pin on the live map with its details.</li>
      <li>Local authorities or volunteers can access the data and respond to reports.</li>
    </ol>
  </section>

  <section class="about-section">
    <h2>Our Vision</h2>
    <p>We envision a future where transparency and community participation eliminate illegal waste sites across Kosovo. A connected, informed population can hold institutions accountable and drive real change.</p>
  </section>

  <section class="about-section">
    <h2>Join the Movement</h2>
    <p>Whether you're a concerned citizen, organization, or municipality, you can make a difference. Report trash, spread the word, and be a part of the zero-waste movement!</p>
    <a href="report.php" class="join-button">Join</a>
  </section>
</main>

<!-- FOOTER -->
<footer class="site-footer">
  <div class="container footer-content">
    <div class="footer-logo">
      <img src="images/logo.png" alt="TrashTrack Footer Logo" />
    </div>

    <div class="footer-links">
      <h3>Quick Links</h3>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="aboutus.php">About</a></li>
        <li><a href="map.php">Map</a></li>
        <li><a href="report.php">Submit Report</a></li>
      </ul>
    </div>

    <div class="footer-social">
      <p>Follow Us</p>
      <div class="social-icons">
        <a href="#"><i class="fab fa-facebook-f"></i></a>
        <a href="#"><i class="fab fa-twitter"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-linkedin-in"></i></a>
      </div>
    </div>
    <p class="copyright">© 2025 TrashTrack Kosovo. All rights reserved.</p>
  </div>
</footer>



</body>
</html>

<script>

</script>