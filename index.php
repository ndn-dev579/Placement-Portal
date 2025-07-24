<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>landing page</title>
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="login.php">


</head>

<body>
  <!-- Font Awesome CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Navbar HTML -->
  <header class="navbar">
    <div class="nav-container">
      <h1 class="nav-logo">CampusHire</h1>
      <nav>
        <a href="index.html"><i class="fas fa-home"></i> Home</a>
        <a href="view_jobs.html"><i class="fas fa-briefcase"></i> View Jobs</a>
        <div class="dropdown">
          <button class="dropbtn"><i class="fas fa-sign-in-alt"></i> Login <i class="fas fa-caret-down"></i></button>
          <div class="dropdown-content">
            <a href="login.php">Student Login</a>
            <a href="student-registration.php">Student Registratio</a>
          </div>
        </div>
        <a href="#contact"><i class="fas fa-envelope"></i> Contact</a>
      </nav>
    </div>
  </header>

  <section>
    <div class="hero-section">
      <div class="hero-content">
        <h1 class="main-heading">Find the right Opportunity</h1>
        <p class="sub-heading">Your gateway to internships and Placements</p>
      </div>
      <div class="login">
        <div class="login-content">
          <img class="login-image" src="pictures/student login.jpg" alt="student-picture">
          <a href="login.php" class="btn primary">Are you a Student?</a>
        </div>
        <div class="login-content">
          <img class="login-image" src="pictures/admin login.jpg" alt="admin-picture">
          <!-- <a href="admin-login.php"> <button>Admin only</button></a> -->
          <a href="#" class="btn primary">Admin only</a>
        </div>
      </div>
    </div>
  </section>

</body>

</html>