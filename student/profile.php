<?php
// We must start the session and include the necessary PHP logic files first.
// These files do not output any HTML.
require_once '../auth-check.php';
checkAccess('student');
require_once '../db-functions.php';

// The user_id is set in the session from a successful login.
$user_id = $_SESSION['user_id'];
$error = '';
$success_message = '';

// --- FORM PROCESSING LOGIC (MOVED TO THE TOP) ---
// This entire block now runs BEFORE any HTML is sent to the browser.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $prn = $_POST['prn'];
  $name = $_POST['name'];
  $phone = $_POST['phone'];
  $dob = $_POST['dob'];
  $gpas = [
    $_POST['gpa_sem1'],
    $_POST['gpa_sem2'],
    $_POST['gpa_sem3'],
    $_POST['gpa_sem4'],
    $_POST['gpa_sem5'],
    $_POST['gpa_sem6']
  ];

  $current_student = getStudentByUserId($user_id);
  $id_card = $current_student['id_card'] ?? null;
  $resume = $current_student['resume_path'] ?? null;

  if (!empty($_FILES['id_card']['name'])) {
    $ext = strtolower(pathinfo($_FILES['id_card']['name'], PATHINFO_EXTENSION));
    $id_card = "uploads/IDcard/" . "id_" . $user_id . "." . $ext;
    $id_card_target_path = "../" . $id_card;
    move_uploaded_file($_FILES['id_card']['tmp_name'], $id_card_target_path);
  }

  if (!empty($_FILES['resume']['name'])) {
    $ext = strtolower(pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION));
    $resume = "uploads/resumes/" . "resume_" . $user_id . "." . $ext;
    $resume_target_path = "../" . $resume;
    move_uploaded_file($_FILES['resume']['tmp_name'], $resume_target_path);
  }

  if ($current_student) {
    $success = updateStudentProfile($user_id, $prn, $name, $phone, $dob, $id_card, $resume, $gpas);
  } else {
    $success = createStudentProfile($user_id, $prn, $name, $phone, $dob, $id_card, $resume, $gpas);
  }

  if ($success) {
    // Now this redirect will work perfectly.
    header("Location: profile.php?success=1");
    exit();
  } else {
    $error = "Failed to save profile. Please try again.";
  }
}


// --- START BUILDING THE PAGE ---
// Now that all backend work is done, we can include the header and start outputting HTML.
require_once 'student_header.php';

// Check for a success flag in the URL (after a redirect)
if (isset($_GET['success']) && $_GET['success'] == 1) {
  $success_message = "Your profile has been saved successfully!";
}

// Fetch the latest student data to display in the form
$student = getStudentByUserId($user_id);
?>

<!-- Page Content -->
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header">
      <h4 class="card-title mb-0">👤 My Profile</h4>
    </div>
    <div class="card-body">
      <p class="card-text text-muted">Keep your information up-to-date for recruiters.</p>

      <?php if ($success_message): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
      <?php endif; ?>
      <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
      <?php endif; ?>

      <form method="post" enctype="multipart/form-data" class="mt-4">
        <div class="row">
          <!-- Personal Details -->
          <div class="col-md-6 mb-3">
            <label for="name" class="form-label fw-semibold">Full Name</label>
            <input type="text" id="name" name="name" class="form-control"
              value="<?= htmlspecialchars($student['name'] ?? '') ?>" required>
          </div>
          <div class="col-md-6 mb-3">
            <label for="prn" class="form-label fw-semibold">PRN (Permanent Registration Number)</label>
            <input type="text" id="prn" name="prn" class="form-control"
              value="<?= htmlspecialchars($student['prn'] ?? '') ?>" required>
          </div>
          <div class="col-md-6 mb-3">
            <label for="phone" class="form-label fw-semibold">Phone Number</label>
            <input type="tel" id="phone" name="phone" class="form-control"
              value="<?= htmlspecialchars($student['phone_number'] ?? '') ?>" required>
          </div>
          <div class="col-md-6 mb-3">
            <label for="dob" class="form-label fw-semibold">Date of Birth</label>
            <input type="date" id="dob" name="dob" class="form-control"
              value="<?= htmlspecialchars($student['dob'] ?? '') ?>" required>
          </div>

          <!-- File Uploads -->
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">ID Card</label>
            <?php if (!empty($student['id_card'])): ?>
              <div class="alert alert-light p-2 d-flex justify-content-between align-items-center">
                <span>
                  <i data-lucide="file-image" class="me-2" style="width:16px; height:16px;"></i>
                  <strong><?= htmlspecialchars(basename($student['id_card'])) ?></strong>
                </span>
                <a href="../<?= htmlspecialchars($student['id_card']) ?>" target="_blank"
                  class="btn btn-outline-primary btn-sm">View</a>
              </div>
              <label for="id_card" class="form-label text-muted small">Upload a new file to replace the current
                one:</label>
            <?php endif; ?>
            <input type="file" id="id_card" name="id_card" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">Resume/CV</label>
            <?php if (!empty($student['resume_path'])): ?>
              <div class="alert alert-light p-2 d-flex justify-content-between align-items-center">
                <span>
                  <i data-lucide="file-text" class="me-2" style="width:16px; height:16px;"></i>
                  <strong><?= htmlspecialchars(basename($student['resume_path'])) ?></strong>
                </span>
                <a href="../<?= htmlspecialchars($student['resume_path']) ?>" target="_blank"
                  class="btn btn-outline-primary btn-sm">View / Download</a>
              </div>
              <label for="resume" class="form-label text-muted small">Upload a new file to replace the current
                one:</label>
            <?php endif; ?>
            <input type="file" id="resume" name="resume" class="form-control" accept=".pdf,.doc,.docx">
          </div>
        </div>

        <hr class="my-4">

        <!-- GPA Section -->
        <h5 class="mb-3 fw-semibold">Semester GPAs</h5>
        <div class="row g-3">
          <?php for ($i = 1; $i <= 6; $i++): ?>
            <div class="col-md-4 col-lg-2">
              <div class="input-group">
                <span class="input-group-text">Sem <?= $i ?></span>
                <input type="number" step="0.01" name="gpa_sem<?= $i ?>" class="form-control"
                  value="<?= htmlspecialchars($student['gpa_sem' . $i] ?? '') ?>" required>
              </div>
            </div>
          <?php endfor; ?>
        </div>

        <div class="d-grid mt-4">
          <button type="submit"
            class="btn btn-primary btn-lg"><?= $student ? 'Update Profile' : 'Save Profile' ?></button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php
// Includes the closing HTML tags and necessary JS.
require_once 'student_footer.php';
?>