<?php
require_once 'auth-check.php';
checkAccess('student');

require_once 'db-functions.php';

// At the top of student-resubmit.php


// Deny access if they weren't sent here from a rejected login
if (!isset($_SESSION['resubmit_user_id'])) {
    // Using die() is simple, or you can redirect
    die("Access Denied: You do not have permission to view this page.");
}

// Now, get the user ID from the special session variable
$user_id = $_SESSION['resubmit_user_id'];
$student = getStudentByUserId($user_id);
// ... rest of your code

if (!$student) {
    echo "<script>alert('Student record not found.'); window.location.href='../login.php';</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $prn = $_POST['prn'];
    $dob = $_POST['dob'];

    $id_card_path = $student['id_card'];

    if (!empty($_FILES['id_card']['name'])) {
        $id_card_name = $_FILES['id_card']['name'];
        $id_card_tmp = $_FILES['id_card']['tmp_name'];

        $unique_filename = uniqid() . '_' . basename($id_card_name);
        $id_card_path = "uploads/IDcard/" . $unique_filename;

        // --- THIS IS THE CORRECTED LINE ---
        $upload_target_path = __DIR__ . '/' . $id_card_path;

        if (!move_uploaded_file($id_card_tmp, $upload_target_path)) {
            // If it still fails, it's almost certainly a permissions issue.
            echo "<script>alert('❌ Failed to upload. Please check folder permissions.'); history.back();</script>";
            exit;
        }
    }

    $updated = updateRejectedStudentBasic(
        $student['id'],
        $prn,
        $dob,
        $id_card_path
    );

    if ($updated) {
        unset($_SESSION['resubmit_user_id']);
        echo "<script>alert('✅ Resubmission successful. Awaiting admin approval.'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('❌ Failed to resubmit. Try again.'); history.back();</script>";
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Resubmit Registration</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f1f3f6;
            padding: 20px;
        }

        form {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        }

        input,
        label {
            display: block;
            width: 100%;
            margin-bottom: 15px;
            font-size: 1rem;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 12px;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        h2 {
            text-align: center;
        }

        .preview {
            text-align: center;
            margin-top: 15px;
        }

        .preview img {
            max-width: 200px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
</head>

<body>

    <h2>Resubmit Your Registration</h2>

    <form action="" method="POST" enctype="multipart/form-data">
        <label for="prn">PRN:</label>
        <input type="text" name="prn" id="prn" value="<?php echo htmlspecialchars($student['prn']); ?>" required>

        <label for="dob">Date of Birth:</label>
        <input type="date" name="dob" id="dob" value="<?php echo htmlspecialchars($student['dob']); ?>" required>

        <label for="id_card">Upload ID Card (optional):</label>
        <input type="file" name="id_card" id="id_card">

        <div class="preview">
            <?php if (!empty($student['id_card'])): ?>
                <p>Current ID Card:</p>
                <img src="/Placement-Portal/<?php echo htmlspecialchars($student['id_card']); ?>" alt="ID Card">
            <?php endif; ?>
        </div>

        <input type="submit" value="Resubmit">
    </form>

</body>

</html>