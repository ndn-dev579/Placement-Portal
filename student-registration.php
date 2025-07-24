<?php
require_once 'db-functions.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['name'];
    $email = $_POST['email'];
    $prn = $_POST['prn'];
    $dob = $_POST['dob'];
    $password = $_POST['password'];
    $role = "student"; // Fixed role

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format ❗'); history.back();</script>";
        exit();
    }

    // Check for duplicates in users or students
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ? OR username = ?");
    mysqli_stmt_bind_param($stmt, "ss", $email, $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('⚠️ Email or Name already registered.'); history.back();</script>";
        exit();
    }
    mysqli_stmt_close($stmt);

    // Upload ID card
    $id_card_name = $_FILES['id_card']['name'];
    $id_card_tmp = $_FILES['id_card']['tmp_name'];
    $id_card_path = "uploads/IDcard/" . basename($id_card_name);
    move_uploaded_file($id_card_tmp, $id_card_path);

    // Register in users table
    $registered = registerUser($username, $email, $password, $role);

    if ($registered) {
        $user_id = mysqli_insert_id(getConnection());

        // Insert into students table
        $stmt2 = mysqli_prepare($conn, "INSERT INTO students (user_id, prn, name, dob, id_card, status)
                                        VALUES (?, ?, ?, ?, ?, 'pending')");
        mysqli_stmt_bind_param($stmt2, "issss", $user_id, $prn, $username, $dob, $id_card_path);
        $success = mysqli_stmt_execute($stmt2);
        mysqli_stmt_close($stmt2);

        if ($success) {
            echo "<script>alert('✅ Registered. Awaiting admin approval.'); window.location='login.php';</script>";
        } else {
            echo "<script>alert('❌ Error creating student profile.'); history.back();</script>";
        }
    } else {
        echo "<script>alert('❌ Failed to register user.'); history.back();</script>";
    }
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
        body {
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
                <p>Already have an account? <a href="login.php">Login here</a></p>
        </form>
    </div>
</body>

</html>