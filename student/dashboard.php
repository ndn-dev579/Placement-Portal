<?php
require_once '../auth-check.php';
checkAccess('student');




// Include the header, which handles session, auth, and DB functions
require_once 'student_header.php';

// Get the student's ID from the session to fetch their specific data
$student_id = getStudentByUserId($_SESSION['user_id'])['id'] ?? 0;

// --- Fetch Dashboard Statistics ---
$conn = getConnection();

// 1. Get total available jobs (deadline not passed)
$total_jobs_res = mysqli_query($conn, "SELECT COUNT(id) as count FROM jobs WHERE last_date_to_apply >= CURDATE()");
$total_jobs = mysqli_fetch_assoc($total_jobs_res)['count'] ?? 0;

// 2. Get total applications submitted by this student
$apps_submitted_res = mysqli_query($conn, "SELECT COUNT(id) as count FROM job_applications WHERE student_id = " . intval($student_id));
$apps_submitted = mysqli_fetch_assoc($apps_submitted_res)['count'] ?? 0;

// 3. Get total shortlisted/accepted applications
$apps_accepted_res = mysqli_query($conn, "SELECT COUNT(id) as count FROM job_applications WHERE student_id = " . intval($student_id) . " AND status = 'Shortlisted'");
$apps_accepted = mysqli_fetch_assoc($apps_accepted_res)['count'] ?? 0;

// 4. Get 5 most recent applications for the activity table
$recent_apps = [];
$recent_apps_query = "
    SELECT j.title, c.name as company_name, ja.application_date, ja.status
    FROM job_applications ja
    JOIN jobs j ON ja.job_id = j.id
    JOIN companies c ON j.company_id = c.id
    WHERE ja.student_id = " . intval($student_id) . "
    ORDER BY ja.application_date DESC
    LIMIT 5";
$recent_apps_res = mysqli_query($conn, $recent_apps_query);
if ($recent_apps_res) {
    while ($row = mysqli_fetch_assoc($recent_apps_res)) {
        $recent_apps[] = $row;
    }
}
?>

<!-- Start of page-specific content -->
<div class="container-fluid">

    <!-- Stat Cards -->
    <div class="row mb-4">
        <!-- Available Jobs -->
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle me-3">
                        <i data-lucide="briefcase"></i>
                    </div>
                    <div>
                        <h6 class="card-subtitle mb-1 text-muted">Available Jobs</h6>
                        <h4 class="card-title mb-0 fw-bold"><?php echo $total_jobs; ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <!-- Applications Sent -->
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="p-3 rounded-circle me-3" style="background-color: #F3E8FF; color: #5B21B6;">
                        <i data-lucide="send"></i>
                    </div>
                    <div>
                        <h6 class="card-subtitle mb-1 text-muted">Applications Sent</h6>
                        <h4 class="card-title mb-0 fw-bold"><?php echo $apps_submitted; ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <!-- Shortlisted / Accepted -->
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 text-success p-3 rounded-circle me-3">
                        <i data-lucide="star"></i>
                    </div>
                    <div>
                        <h6 class="card-subtitle mb-1 text-muted">Shortlisted</h6>
                        <h4 class="card-title mb-0 fw-bold"><?php echo $apps_accepted; ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
     <div class="row mb-4">
        <div class="col-md-4">
             <a href="jobs.php" class="btn btn-primary w-100 p-4 text-decoration-none text-white d-flex flex-column align-items-center justify-content-center shadow-sm">
                <i data-lucide="search" class="mb-2" style="width:32px; height:32px;"></i>
                <span class="fw-semibold">Browse All Jobs</span>
            </a>
        </div>
         <div class="col-md-4">
             <a href="profile.php" class="btn btn-primary w-100 p-4 text-decoration-none text-white d-flex flex-column align-items-center justify-content-center shadow-sm">
                <i data-lucide="user-cog" class="mb-2" style="width:32px; height:32px;"></i>
                <span class="fw-semibold">Update Your Profile</span>
            </a>
        </div>
         <div class="col-md-4">
             <a href="job-applications.php" class="btn btn-primary w-100 p-4 text-decoration-none text-white d-flex flex-column align-items-center justify-content-center shadow-sm">
                <i data-lucide="history" class="mb-2" style="width:32px; height:32px;"></i>
                <span class="fw-semibold">View Application History</span>
            </a>
        </div>
    </div>

    <!-- Recent Activity Table -->
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="card-title mb-0">Recent Activity</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3 px-3">Job Title</th>
                            <th class="py-3 px-3">Company</th>
                            <th class="py-3 px-3">Date Applied</th>
                            <th class="py-3 px-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recent_apps)): ?>
                            <tr>
                                <td colspan="4" class="text-center p-4 text-muted">You haven't applied to any jobs yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recent_apps as $app): ?>
                                <tr>
                                    <td class="p-3"><?php echo htmlspecialchars($app['title']); ?></td>
                                    <td class="p-3 text-muted"><?php echo htmlspecialchars($app['company_name']); ?></td>
                                    <td class="p-3 text-muted"><?php echo date('M d, Y', strtotime($app['application_date'])); ?></td>
                                    <td class="p-3">
                                        <span class="badge 
                                            <?php 
                                                switch (strtolower($app['status'])) {
                                                    case 'shortlisted': echo 'bg-success'; break;
                                                    case 'rejected': echo 'bg-danger'; break;
                                                    default: echo 'bg-warning text-dark'; break;
                                                }
                                            ?>">
                                            <?php echo htmlspecialchars($app['status']); ?>
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
<!-- End of page-specific content -->

<?php require_once 'student_footer.php'; ?>

