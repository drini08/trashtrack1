<?php
// aboutus.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>About Us | TrashTrack Kosovo</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #f0f7f4;
    }

    .site-header, .site-footer {
      background-color:rgb(42, 59, 32);
      color: white;
      padding: 20px 0;
    }

    /* Logo container (optional, if used) */
.logo {
  display: flex;
  align-items: center;
}

/* Logo image */
.logo-image img {
  height: 40px;        /* Smaller height */
  width: auto;
  max-width: 120px;    /* Reduce max width */
  display: block;
}

    .container {
      width: 90%;
      max-width: 1100px;
      margin: 0 auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .main-nav ul {
      list-style: none;
      display: flex;
      gap: 20px;
    }

    .main-nav a {
  color: white;
  text-decoration: none;
  font-weight: bold;
  font-size: 18px;         /* Make nav text bigger */
  padding: 8px 12px;
  transition: all 0.2s ease;
}

.main-nav a:hover {
  background-color: rgba(255, 255, 255, 0.1);
  border-radius: 5px;
  transform: scale(1.05);  /* Slight zoom effect */
}

    .about-main {
      padding: 50px 40px;
      background-color: #ffffff;
    }

    .hero {
      text-align: center;
      margin-bottom: 40px;
    }

    .hero h1 {
      font-size: 36px;
      color: #2c3e50;
    }

    .hero p {
      font-size: 18px;
      color: #555;
    }

    .about-section {
      max-width: 900px;
      margin: 0 auto 40px auto;
      padding: 0 20px;
    }

    .about-section h2 {
      color: #3a7d44;
      margin-bottom: 10px;
    }

    .about-section p,
    .about-section ul,
    .about-section ol {
      color: #444;
      line-height: 1.6;
    }

    .footer-content {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      flex-wrap: wrap;
    }

    .footer-logo img {
      width: 120px;
    }

    .footer-links h3, .footer-social p {
      margin-bottom: 10px;
    }

    .footer-links ul {
      list-style: none;
      padding: 0;
    }

    .footer-links a {
      color: white;
      text-decoration: none;
      display: block;
      margin-bottom: 5px;
    }

    .social-icons a {
      color: white;
      margin-right: 10px;
      font-size: 20px;
    }
  </style>
</head>
<body>

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
        <li><a href="report.php">Report</a></li>
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
