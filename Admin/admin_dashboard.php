<?php
require_once "../auth-check.php";
checkAccess("admin");
require_once "../db-functions.php";

$conn = getConnection();

// Stats
$total_students     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM students"))['cnt'];
$approved_students  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM students WHERE status='approved'"))['cnt'];
$pending_students   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM students WHERE status='pending'"))['cnt'];
$total_companies    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM companies"))['cnt'];
$total_jobs         = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM jobs"))['cnt'];
$total_applications = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM job_applications"))['cnt'];

// Recent applications
$recent_apps_sql = "
    SELECT ja.id, s.name AS student_name, j.title AS job_title, c.name AS company_name
    FROM job_applications ja
    JOIN students  s ON ja.student_id = s.id
    JOIN jobs      j ON ja.job_id = j.id
    JOIN companies c ON j.company_id = c.id
    ORDER BY ja.id DESC
    LIMIT 5
";
$recent_apps_rs = mysqli_query($conn, $recent_apps_sql);

include 'admin_header.php';
?>

<!-- Stat cards -->
<div class="row g-3">
  <div class="col-12 col-md-4 col-xl-2">
    <div class="card p-3 stat-card text-center">
      <h6>Total Students</h6>
      <h2><?= (int)$total_students ?></h2>
    </div>
  </div>
  <div class="col-12 col-md-4 col-xl-2">
    <div class="card p-3 stat-card text-center">
      <h6>Approved</h6>
      <h2><?= (int)$approved_students ?></h2>
    </div>
  </div>
  <div class="col-12 col-md-4 col-xl-2">
    <div class="card p-3 stat-card text-center">
      <h6>Pending</h6>
      <h2><?= (int)$pending_students ?></h2>
    </div>
  </div>





  <div class="col-12 col-md-4 col-xl-2">
    <div class="card p-3 stat-card text-center">
      <h6>Companies</h6>
      <h2><?= (int)$total_companies ?></h2>
    </div>
  </div>
  <div class="col-12 col-md-4 col-xl-2">
    <div class="card p-3 stat-card text-center">
      <h6>Jobs</h6>
      <h2><?= (int)$total_jobs ?></h2>
    </div>
  </div>
  <div class="col-12 col-md-4 col-xl-2">
    <div class="card p-3 stat-card text-center">
      <h6>Applications</h6>
      <h2><?= (int)$total_applications ?></h2>
    </div>
  </div>
</div>

<!-- Recent Applications -->
<div class="card mt-4 p-3">
  <div class="d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Recent Applications</h5>
    <a href="manage_applications.php" class="btn btn-sm btn-outline-primary">View all</a>
  </div>
  <div class="table-responsive mt-3">
    <table class="table align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Student</th>
          <th>Job Title</th>
          <th>Company</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      <?php if ($recent_apps_rs && mysqli_num_rows($recent_apps_rs) > 0): ?>
        <?php while ($r = mysqli_fetch_assoc($recent_apps_rs)): ?>
          <tr>
            <td><?= (int)$r['id'] ?></td>
            <td><?= htmlspecialchars($r['student_name']) ?></td>
            <td><?= htmlspecialchars($r['job_title']) ?></td>
            <td><?= htmlspecialchars($r['company_name']) ?></td>
            <td>
              <a class="btn btn-sm btn-primary" href="job.php?id=<?= (int)$r['id'] ?>">Open</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="5" class="text-muted">No applications yet.</td></tr>
      <?php endif; ?>



    



      </tbody>
    </table>
  </div>
</div>

<?php include 'admin_footer.php'; ?>
