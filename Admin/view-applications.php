<?php
require_once "../auth-check.php";
checkAccess("admin");
require_once "../db-functions.php";

// Backend logic to handle status update
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['application_id'])) {
    $application_id = $_POST['application_id'];
    $new_status = $_POST['new_status'];
    updateApplicationStatus($application_id, $new_status);
    header("Location: view-applications.php");
    exit;
}

require_once 'admin_header.php';
$applications = getAllJobApplications();
?>

<style>
    .table { margin-top: 25px; box-shadow: 0 2px 8px rgba(0, 0, 0, .08); }
    .status-badge { display: inline-block; padding: 6px 12px; border-radius: 20px; color: white; font-size: 12px; font-weight: 500; text-transform: capitalize; }
    .status-applied { background-color: #0d6efd; } /* Blue for Applied */
    .status-shortlisted { background-color: #198754; } /* Green for Shortlisted */
    .status-rejected { background-color: #dc3545; } /* Red for Rejected */
</style>

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title mb-0">Student Job Applications</h4>
        </div>
        <div class="card-body">
            <p class="card-text">A comprehensive list of all applications submitted through the portal.</p>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>App ID</th>
                            <th>Student Name</th>
                            <th>PRN</th>
                            <th>Job Title</th>
                            <th>Company</th>
                            <th>Date Applied</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($applications)): ?>
                            <tr>
                                <td colspan="8" class="text-center p-4">No job applications submitted yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($applications as $app): ?>
                                <tr>
                                    <td>#<?php echo htmlspecialchars($app['application_id']); ?></td>
                                    <td><?php echo htmlspecialchars($app['student_name']); ?></td>
                                    <td><?php echo htmlspecialchars($app['prn']); ?></td>
                                    <td><?php echo htmlspecialchars($app['job_title']); ?></td>
                                    <td><?php echo htmlspecialchars($app['company_name']); ?></td>
                                    <td><?php echo date('F j, Y', strtotime($app['application_date'])); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo strtolower(htmlspecialchars($app['status'])); ?>">
                                            <?php echo htmlspecialchars($app['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <form action="" method="POST" class="d-flex">
                                            <input type="hidden" name="application_id" value="<?php echo $app['application_id']; ?>">
                                            <select name="new_status" class="form-select form-select-sm me-2" style="width: 120px;">
                                                <option value="Applied" <?php if ($app['status'] == 'Applied') echo 'selected'; ?>>Applied</option>
                                                <option value="Shortlisted" <?php if ($app['status'] == 'Shortlisted') echo 'selected'; ?>>Shortlisted</option>
                                                <option value="Rejected" <?php if ($app['status'] == 'Rejected') echo 'selected'; ?>>Rejected</option>
                                            </select>
                                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                        </form>
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
require_once 'admin_footer.php';
?>