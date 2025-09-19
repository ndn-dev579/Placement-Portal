<?php
// This includes the sidebar, auth checks, and all DB functions.
require_once 'student_header.php';

// 1. Get the Job ID from the URL.
if (!isset($_GET['id'])) {
    // A simple way to handle missing ID, can be improved with a proper error page.
    die("<div class='alert alert-danger'>Error: No Job ID provided. <a href='jobs.php'>Go back to jobs list</a>.</div>");
}
$job_id = intval($_GET['id']);

// 2. Get the current student's ID from their profile.
$user_id = $_SESSION['user_id'];
$student = getStudentByUserId($user_id);

// Check if the student has a profile, which is required to apply.
if (!$student) {
    die("<div class='alert alert-warning'>Please complete your profile before applying for jobs. <a href='profile.php'>Go to Profile</a>.</div>");
}
$student_id = $student['id'];

// 3. Handle the "Apply Now" form submission.
$success_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_for_job'])) {
    
    // Call the function from db-functions.php to save the application.
    $success = applyToJob($job_id, $student_id);

    if ($success) {
        $success_message = "You have successfully applied for this job!";
    } else {
        // This could happen if there's a database constraint error (e.g., already applied).
        $error_message = "There was an error submitting your application. You may have already applied for this job.";
    }
}

// 4. Fetch the full job details.
$job = getJobById($job_id);

if (!$job) {
    die("<div class='alert alert-danger'>Error: Job not found.</div>");
}

// 5. Check if the student has already applied for this job to update the button status.
$has_applied = hasStudentAppliedForJob($job_id, $student_id);
?>

<!-- Page Content -->
<div class="container-fluid">
    
    <!-- Display Success or Error Messages -->
    <?php if ($success_message): ?>
        <div class="alert alert-success"><?= $success_message ?></div>
    <?php endif; ?>
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?= $error_message ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h4 class="card-title mb-0"><?= htmlspecialchars($job['title']) ?></h4>
                <p class="card-text text-muted mb-0">at <?= htmlspecialchars($job['company_name']) ?></p>
            </div>
            <a href="jobs.php" class="btn btn-outline-secondary">← Back to All Jobs</a>
        </div>
        <div class="card-body p-4">
            
            <h5 class="fw-semibold">Job Description</h5>
            <p><?= nl2br(htmlspecialchars($job['description'])) ?></p>

            <div class="row mt-4">
                <div class="col-md-6">
                    <h5 class="fw-semibold">Details</h5>
                    <ul class="list-unstyled">
                        <li><strong>Location:</strong> <?= htmlspecialchars($job['location']) ?></li>
                        <li><strong>Salary:</strong> <?= htmlspecialchars($job['salary']) ?></li>
                        <li><strong>Allowed Streams:</strong> <?= htmlspecialchars($job['allowed_streams']) ?></li>
                        <li><strong>Apply By:</strong> <?= date('F j, Y', strtotime($job['last_date_to_apply'])) ?></li>
                    </ul>
                </div>
            </div>

            <hr class="my-4">

            <!-- Application Button Logic -->
            <div class="text-center">
                <?php if ($has_applied || $success_message): ?>
                    <!-- If student has applied, show a disabled button -->
                    <button class="btn btn-success btn-lg" disabled>
                        <i data-lucide="check-circle" class="me-2"></i> Applied Successfully
                    </button>
                <?php else: ?>
                    <!-- Otherwise, show the application form with the "Apply Now" button -->
                    <form method="POST" action="">
                        <input type="hidden" name="apply_for_job" value="1">
                        <button type="submit" class="btn btn-primary btn-lg">
                            Apply Now
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
// This includes the closing HTML tags and necessary JS.
require_once 'student_footer.php';
?>
