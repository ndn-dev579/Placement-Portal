<?php
// All backend logic must come before any HTML output.
require_once "../auth-check.php";
checkAccess("admin");
require_once "../db-functions.php";

// Backend logic to handle status update (POST request)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['application_id'])) {
    $application_id = $_POST['application_id'];
    $new_status = $_POST['new_status'];
    updateApplicationStatus($application_id, $new_status);
    
    // Preserve search filters after update by adding them to the redirect URL
    $queryString = http_build_query($_GET);
    header("Location: view-applications.php?" . $queryString);
    exit;
}

// Now we can start outputting the HTML page
require_once 'admin_header.php';

// Get search and filter parameters from the URL (GET request)
$searchTerm = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';

// Fetch applications using our filtering function
$applications = getFilteredJobApplications($searchTerm, $status);
?>

<style>
    .table { margin-top: 25px; box-shadow: 0 2px 8px rgba(0, 0, 0, .08); }
    .status-badge { display: inline-block; padding: 6px 12px; border-radius: 20px; color: white; font-size: 12px; font-weight: 500; text-transform: capitalize; }
    .status-applied { background-color: #0d6efd; } /* Blue for Applied */
    .status-shortlisted { background-color: #ffc107; color: #000; } /* Yellow for Shortlisted */
    .status-accepted { background-color: #198754; } /* Green for Accepted (Offer) */
    .status-rejected { background-color: #dc3545; } /* Red for Rejected */
</style>

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title mb-0">Student Job Applications</h4>
        </div>
        <div class="card-body">
            <p class="card-text text-muted">Search and manage all applications submitted through the portal.</p>
            
            <!-- Search and Filter Form -->
            <form action="view-applications.php" method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-5">
                        <input type="text" name="search" class="form-control" placeholder="Search by Student, Job, or Company..." value="<?= htmlspecialchars($searchTerm) ?>">
                    </div>
                    <div class="col-md-5">
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="applied" <?= $status == 'applied' ? 'selected' : '' ?>>Applied</option>
                            <option value="shortlisted" <?= $status == 'shortlisted' ? 'selected' : '' ?>>Shortlisted</option>
                            <option value="accepted" <?= $status == 'accepted' ? 'selected' : '' ?>>Accepted</option>
                            <option value="rejected" <?= $status == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex">
                        <button type="submit" class="btn btn-info flex-grow-1">Filter</button>
                        <a href="view-applications.php" class="btn btn-outline-secondary ms-2" title="Clear Filters">
                           <i data-lucide="rotate-cw" style="width:16px; height:16px;"></i>
                        </a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
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
                                <td colspan="8" class="text-center p-4">No applications found matching your criteria. <a href="view-applications.php">Clear filters</a> to see all applications.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($applications as $app): ?>
                                <tr>
                                    <td>#<?= htmlspecialchars($app['application_id']); ?></td>
                                    <td><?= htmlspecialchars($app['student_name']); ?></td>
                                    <td><?= htmlspecialchars($app['prn']); ?></td>
                                    <td><?= htmlspecialchars($app['job_title']); ?></td>
                                    <td><?= htmlspecialchars($app['company_name']); ?></td>
                                    <td><?= date('F j, Y', strtotime($app['application_date'])); ?></td>
                                    <td>
                                        <span class="status-badge status-<?= strtolower(htmlspecialchars($app['status'])); ?>">
                                            <?= htmlspecialchars($app['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <!-- The form action now includes the current filters -->
                                        <form action="view-applications.php?<?= http_build_query($_GET) ?>" method="POST" class="d-flex">
                                            <input type="hidden" name="application_id" value="<?= $app['application_id']; ?>">
                                            <select name="new_status" class="form-select form-select-sm me-2" style="width: 120px;">
                                                <option value="applied" <?php if ($app['status'] == 'applied') echo 'selected'; ?>>Applied</option>
                                                <option value="shortlisted" <?php if ($app['status'] == 'shortlisted') echo 'selected'; ?>>Shortlisted</option>
                                                <option value="accepted" <?php if ($app['status'] == 'accepted') echo 'selected'; ?>>Accepted</option>
                                                <option value="rejected" <?php if ($app['status'] == 'rejected') echo 'selected'; ?>>Rejected</option>
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

