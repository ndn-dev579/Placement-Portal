
<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $conn = mysqli_connect("localhost", "root", "", "campushire");

  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  $name     = $_POST['name'];
  $email    = $_POST['email'];
  $prn      = $_POST['prn'];
  $dob      = $_POST['dob'];
  $password = $_POST['password'];

  //  Email validation
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Invalid email format❗‼️'); 
          history.back();</script>";
    exit();
  }

  //  Prevent duplicate registration
  $check_sql = "SELECT * FROM student WHERE email = '$email' OR prn = '$prn'";
  $check_result = mysqli_query($conn, $check_sql);

  if (mysqli_num_rows($check_result) > 0) {
    echo "<script>alert('⚠️ Email or PRN already registered'); 
          history.back();</script>";
    mysqli_close($conn);
    exit();
  }

  //  Hash password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  //  Upload ID card
  $id_card_name = $_FILES['id_card']['name'];
  $id_card_tmp  = $_FILES['id_card']['tmp_name'];
  $upload_path  = "uploads/" . basename($id_card_name);
  move_uploaded_file($id_card_tmp, $upload_path);

  //  Insert into DB with 'pending' status
  $sql = "INSERT INTO student (name, email, prn, dob, password_hash, id_card, status) 
          VALUES ('$name', '$email', '$prn', '$dob', '$hashed_password', '$id_card_name', 'pending')";

  if (mysqli_query($conn, $sql)) {
    echo "<script>alert('✅ Registered. Awaiting admin approval.'); window.location='student-login.php';</script>";
  } else {
    echo "<script>alert('❌ Error: " . mysqli_error($conn) . "');</script>";
  }

  mysqli_close($conn);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link rel="stylesheet" href="css/login_form.css"> 
    <style>
        body{
            background-image: url("pictures/signup.jpg");
        }
    </style>
</head>
<body>
    <div class="login-container">
    <h2>Sign Up</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="name">Enter Name:</label>
        <input type="text" name="name" id="name" placeholder="your Name" required>
        <label for="email">Enter Email:</label>
        <input type="email" name="email" id="email" placeholder="username@example.com" required>
        <label for="prn">Enter PRN:</label>
        <input type="text" name="prn" id="prn" placeholder="Permanent Register Number" required>
        <label for="dob">Enter DOB:</label>
        <input type="date" name="dob" id="dob" placeholder="dd-mm-yyyy" required>
        <div class="password-wrap">
        <label for="id_card">Upload ID Card:</label>
        <input type="file" name="id_card" id="id_card" accept="image/*,.pdf" required>
          
        <label for="password">Enter Password:</label>
        <input type="password" name="password" id="password" placeholder="Password" required>
        <!-- <span class="eye-icon">👁️</span> -->
        <button type="submit" class="login-btn" name="login-btn" id="login-btn"> Register </button>
        <p>Already have an account? <a href="student-login.php" >Login here</a></p>
    </form>
    </div>
</body>
</html>