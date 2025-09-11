<?php
// This single line includes the sidebar, auth checks, and DB functions.
require_once 'student_header.php';

// Fetch all available jobs.
$jobs = getAllJobs();
?>

<!-- Custom styles for this page, complementing Bootstrap -->
<style>
    .job-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .job-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
    }
    .company-logo {
        width: 50px;
        height: 50px;
        object-fit: contain;
    }
</style>

<!-- Page Content -->
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header">
            <h4 class="card-title mb-0">📄 Available Job Opportunities</h4>
        </div>
        <div class="card-body">
            <p class="card-text text-muted">Browse and apply for jobs posted by companies hiring from our campus.</p>

            <div class="list-group mt-4">
                <?php if (empty($jobs)): ?>
                    <div class="alert alert-info text-center">
                        There are currently no job postings available. Please check back later!
                    </div>
                <?php else: ?>
                    <?php foreach ($jobs as $job): ?>
                        <a href="job.php?id=<?= $job['id'] ?>" class="list-group-item list-group-item-action job-card p-4 mb-3 shadow-sm rounded-3">
                            <div class="d-flex w-100 justify-content-between">
                                <div>
                                    <h5 class="mb-1 fw-bold text-primary"><?= htmlspecialchars($job['title']) ?></h5>
                                    <p class="mb-1 text-secondary fw-semibold">🏢 <?= htmlspecialchars($job['company_name']) ?></p>
                                </div>
                                <small class="text-muted">Apply by: <?= date('M d, Y', strtotime($job['last_date_to_apply'])) ?></small>
                            </div>
                            <hr class="my-3">
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-light text-dark border"><i data-lucide="map-pin" class="me-1" style="width:14px; height:14px;"></i> <?= htmlspecialchars($job['location']) ?></span>
                                <span class="badge bg-light text-dark border"><i data-lucide="briefcase" class="me-1" style="width:14px; height:14px;"></i> <?= htmlspecialchars($job['allowed_streams']) ?></span>
                                <span class="badge bg-success bg-opacity-10 text-success-emphasis border border-success-subtle"><i data-lucide="indian-rupee" class="me-1" style="width:14px; height:14px;"></i> <?= htmlspecialchars($job['salary']) ?></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
// This single line includes the closing HTML tags and necessary JS.
require_once 'student_footer.php';
?>
