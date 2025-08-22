<?php
//session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../db-functions.php"; 
require_once "../auth-check.php";// your DB functions file

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$student = getStudentByUserId($user_id); // check if profile exists

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $prn = $_POST['prn'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];
    $gpas = [
        $_POST['gpa_sem1'], $_POST['gpa_sem2'], $_POST['gpa_sem3'],
        $_POST['gpa_sem4'], $_POST['gpa_sem5'], $_POST['gpa_sem6']
    ];

    // File uploads (optional, handle carefully)
    $id_card = $student['id_card'] ?? null;
    $resume = $student['resume_path'] ?? null;

    if (!empty($_FILES['id_card']['name'])) {
        $id_card = "uploads/id_cards/" . basename($_FILES['id_card']['name']);
        move_uploaded_file($_FILES['id_card']['tmp_name'], $id_card);
    }

    if (!empty($_FILES['resume']['name'])) {
        $ext = strtolower(pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION));
$resume = "uploads/resumes/" . uniqid("resume_") . "." . $ext;

        move_uploaded_file($_FILES['resume']['tmp_name'], $resume);
    }

    // Insert or Update
    if ($student) {
        $success = updateStudentProfile($user_id, $prn, $name, $phone, $dob, $id_card, $resume, $gpas);
    } else {
        $success = createStudentProfile($user_id, $prn, $name, $phone, $dob, $id_card, $resume, $gpas);
    }

    if ($success) {
        header("Location: profile.php?success=1");
        exit();
    } else {
        $error = "Failed to save profile.";
    }
}

// Reload student after update
$student = getStudentByUserId($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Student Profile</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-4">
          <h3 class="text-center mb-4">Create Student Profile</h3>

          <form method="post" enctype="multipart/form-data">
            
            <div class="mb-3">
              <label class="form-label">PRN</label>
              <input type="text" name="prn" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Name</label>
              <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Phone Number</label>
              <input type="text" name="phone" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Date of Birth</label>
              <input type="date" name="dob" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Upload ID Card</label>
              <input type="file" name="id_card" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Upload Resume</label>
              <input type="file" name="resume" class="form-control" accept=".pdf,.doc,.docx" required>
            </div>

            <h5 class="mt-4">Semester GPAs</h5>
            <div class="row g-2">
              <?php for ($i = 1; $i <= 6; $i++): ?>
                <div class="col-md-4">
                  <input type="number" step="0.01" name="gpa_sem<?= $i ?>" class="form-control" placeholder="Sem <?= $i ?>" required>
                </div>
              <?php endfor; ?>
            </div>

            <div class="d-grid mt-4">
              <button type="submit" class="btn btn-primary btn-lg">Save Profile</button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
