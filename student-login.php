<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">    
  <title>Login</title>
  <!-- <link rel="stylesheet" href="css/styles.css"> -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/login_form.css"> 

  
</head>
<body class="login-page">
<img src="pictures/login-illustration.png" alt="decor" class="bg-illustration">


  <div class="login-container">
    <h2>Login</h2>
    <form action="php/student_auth.php" method="POST">
      <div class="role-toggle">
        <button class="toggle-btn active" id="studentBtn">🧑‍🎓Student</button>
        <button class="toggle-btn" id="adminBtn">🔐Admin</button>
      </div>
      <label for="email">Enter Email:</label>
      <input type="email" name="email" id="email" placeholder="username@example.com" required>
      <div class="password-wrap">
        <label for="password">Enter Password:</label>
        <input type="password" name="password" id="password" placeholder="Password" required>
        <!-- <span class="eye-icon">👁️</span> -->
      </div>
      <button type="submit" class="login-btn" name="login-btn" id="login-btn"> Login </button>
    </form>
    <p>New user? <a href="student-registration.php">Register here</a></p>
  </div>

  <script>
    const studentBtn = document.getElementById("studentBtn");
    const adminBtn = document.getElementById("adminBtn");

    studentBtn.addEventListener("click", function () {
      studentBtn.classList.add("active");
      adminBtn.classList.remove("active");
    });

    adminBtn.addEventListener("click", function () {
      adminBtn.classList.add("active");
      studentBtn.classList.remove("active");
    });
  </script>

  <?php

  ?>


</body>
</html>

</body>
</html>