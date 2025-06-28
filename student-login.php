<?php
session_start();
 // adjust this if your DB config file path is different
/*
  <?php
// db/db.php

function getConnection() {
    static $conn = null; // Keeps connection alive across function calls

    if ($conn === null) {
        $host = "localhost";
        $user = "root";
        $pass = "";
        $dbname = "campus_placement";

        // Procedural connection
        $conn = mysqli_connect($host, $user, $pass, $dbname);

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    return $conn;
}
?>
*/



require_once 'db-functions.php';

 // Always start session before working with $_SESSION

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $role     = $_POST['role'];

    // Fetch from users table where role matches
    $user = getUserByEmailAndRole($email, $role);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            
            // Check if student is approved
            if ($role === "student") {
                $studentData = getStudentByUserId($user['id']);
                if (!$studentData || $studentData['status'] !== 'approved') {
                    echo "<script>alert('❌ Account not yet approved.'); history.back();</script>";
                    exit;
                }
            }

            // Store session data
            $_SESSION['logged_in'] = true;
            $_SESSION['email']     = $email;
            $_SESSION['role']      = $role;
            $_SESSION['user_id']   = $user['id'];

            // Redirect based on role
            if ($role === "admin") {
                header("Location: ../admin/dashboard.php");
            } else {
                header("Location: ../student/dashboard.php");
            }
            exit;

        } else {
            echo "<script>alert('❌ Incorrect password'); history.back();</script>";
            exit;
        }
    } else {
        echo "<script>alert('❌ No account found.'); history.back();</script>";
        exit;
    }
}
?>








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
      <input type="hidden" name="role" id="role" value="student">
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
    const role = document.getElementById("role");

    studentBtn.addEventListener("click", function () {
      studentBtn.classList.add("active");
      adminBtn.classList.remove("active");
      role.value="student";
    });

    adminBtn.addEventListener("click", function () {
      adminBtn.classList.add("active");
      studentBtn.classList.remove("active");
      role.value="admin";
    });
  </script>

  <?php

  ?>


</body>
</html>

</body>
</html>