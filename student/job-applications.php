<?php

require_once '../auth-check.php';
checkAccess('student');
require_once '../db-functions.php';
// Includes the sidebar, auth checks, and DB functions.
require_once 'student_header.php';

// Get the current student's ID from the session.
$user_id = $_SESSION['user_id'];
$student = getStudentByUserId($user_id);

// Fetch all job applications for this student.
$job_applications = [];
if ($student) {
    $job_applications = getApplicationsByStudent($student['id']);
}
?>

<!-- Custom styles for the status badges -->
<style>
    .status-badge {
        font-size: 0.85em;
        padding: 0.5em 0.9em;
        font-weight: 600;
    }
</style>

<!-- Page Content -->
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header">
            <h4 class="card-title mb-0">📄 My Job Applications</h4>
        </div>
        <div class="card-body">
            <p class="card-text text-muted">Here you can track the status of all the jobs you've applied for.</p>

            <div class="table-responsive mt-4">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Job Title</th>
                            <th scope="col">Company</th>
                            <th scope="col">Date Applied</th>
                            <th scope="col" class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($job_applications)): ?>
                            <tr>
                                <td colspan="4">
                                    <div class="alert alert-primary text-center mt-3">
                                        You haven't applied for any jobs yet. <a href="jobs.php" class="alert-link">Click here to find opportunities!</a>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($job_applications as $application): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold"><?= htmlspecialchars($application['title']) ?></div>
                                    </td>
                                    <td>
                                        <div class="text-secondary"><?= htmlspecialchars($application['company_name']) ?></div>
                                    </td>
                                    <td><?= date('F j, Y', strtotime($application['application_date'])) ?></td>
                                    <td class="text-center">
                                        <?php
                                        $status = strtolower($application['status']);
                                        $badge_class = 'bg-secondary'; // Default
                                        if ($status == 'applied') {
                                            $badge_class = 'bg-primary';
                                        } elseif ($status == 'shortlisted') {
                                            $badge_class = 'bg-success';
                                        } elseif ($status == 'rejected') {
                                            $badge_class = 'bg-danger';
                                        }
                                        ?>
                                        <span class="badge rounded-pill status-badge <?= $badge_class ?>">
                                            <?= htmlspecialchars(ucfirst($application['status'])) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
// Includes the closing HTML tags and necessary JS.
require_once 'student_footer.php';
?>
