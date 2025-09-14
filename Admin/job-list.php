<?php
// Includes the sidebar, auth checks, and all DB functions
require_once 'admin_header.php';

// Get search and filter parameters from the URL (using the GET method)
$searchTerm = $_GET['search'] ?? '';
$location = $_GET['location'] ?? '';
$stream = $_GET['stream'] ?? '';

// Fetch the jobs using our new filtering function
$jobs = getFilteredJobs($searchTerm, $location, $stream);
?>

<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">💼 Manage Job Postings</h4>
            <a href="post-jobs.php" class="btn btn-primary">Post New Job</a>
        </div>
        <div class="card-body">
            
            <!-- Search and Filter Form -->
            <form action="job-list.php" method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search by Title or Company..." value="<?= htmlspecialchars($searchTerm) ?>">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="location" class="form-control" placeholder="Filter by Location..." value="<?= htmlspecialchars($location) ?>">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="stream" class="form-control" placeholder="Filter by Stream..." value="<?= htmlspecialchars($stream) ?>">
                    </div>
                    <div class="col-md-2 d-flex">
                        <button type="submit" class="btn btn-info flex-grow-1">Search</button>
                        <!-- Link to clear filters -->
                        <a href="job-list.php" class="btn btn-outline-secondary ms-2" title="Clear Filters">
                           <i data-lucide="rotate-cw" style="width:16px; height:16px;"></i>
                        </a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Job Title</th>
                            <th>Company</th>
                            <th>Location</th>
                            <th>Streams</th>
                            <th>Salary</th>
                            <th>Apply By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($jobs)): ?>
                            <tr>
                                <td colspan="7" class="text-center p-4">
                                    No jobs found matching your criteria. <a href="job-list.php">Clear filters</a> to see all jobs.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($jobs as $job): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($job['title']) ?></strong></td>
                                    <td><?= htmlspecialchars($job['company_name']) ?></td>
                                    <td><?= htmlspecialchars($job['location']) ?></td>
                                    <td><?= htmlspecialchars($job['allowed_streams']) ?></td>
                                    <td><?= htmlspecialchars($job['salary']) ?></td>
                                    <td><?= date('d M, Y', strtotime($job['last_date_to_apply'])) ?></td>
                                    <td>
                                        <!-- Actions like Edit and Delete would go here -->
                                        <a href="edit-job.php?id=<?= $job['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <a href="delete-job.php?id=<?= $job['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this job?')">Delete</a>
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
// Includes the closing HTML tags and necessary JS
require_once 'admin_footer.php';
?>
