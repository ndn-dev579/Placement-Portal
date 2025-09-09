<?php
require_once "../auth-check.php";
checkAccess("admin");
require_once "../db-functions.php";

$conn = getConnection();

// Pending count for badge (optional if you want to keep for stats only)
$pending_students = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM students WHERE status='pending'")
)['cnt'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Admin - Placement Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f5f7;
        }

        .sidebar {
            height: 100vh;
            background: #6f42c1;
            position: fixed;
            left: 0;
            top: 0;
            width: 240px;
            padding: 20px 12px;
        }

        .sidebar .brand {
            color: #fff;
            font-weight: 700;
            text-align: center;
            margin-bottom: 18px;
        }

        .nav-link {
            color: #eae0ff;
            border-radius: 8px;
            padding: 10px 12px;
            margin: 4px 0;
        }

        .nav-link.active,
        .nav-link:hover {
            background: #5a34a1;
            color: #fff;
        }

        .content {
            margin-left: 240px;
            padding: 20px;
        }

        .navbar {
            background: #fff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .05);
        }

        .card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .08);
        }

        .stat-card h6 {
            color: #6c757d;
            margin-bottom: 6px;
        }

        .stat-card h2 {
            margin: 0;
        }

        .badge-soft {
            background: #f1edfb;
            color: #6f42c1;
        }

        .table thead th {
            background: #faf9fe;
        }

        .collapse-inner .nav-link {
            padding-left: 30px;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">Placement Admin</div>
        <nav class="nav flex-column">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'admin_dashboard.php' ? 'active' : '' ?>"
                href="admin_dashboard.php">🏠 Dashboard</a>
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'manage_students.php' ? 'active' : '' ?>"
                href="manage_students.php">👨‍🎓 Students</a>

            <!-- Companies collapsible menu -->
            <?php $isCompanyPage = in_array(basename($_SERVER['PHP_SELF']), ['company-list.php', 'post-companies.php',]); ?>
            <a class="nav-link <?= $isCompanyPage ? 'active' : '' ?> collapsed" data-bs-toggle="collapse"
                href="#companiesMenu" role="button" aria-expanded="<?= $isCompanyPage ? 'true' : 'false' ?>"
                aria-controls="companiesMenu">
                🏢 Companies
            </a>
            <div class="collapse <?= $isCompanyPage ? 'show' : '' ?>" id="companiesMenu">
                <nav class="nav flex-column collapse-inner">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'company-list.php' ? 'active' : '' ?>"
                        href="company-list.php">Company List</a>
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'post-companies.php' ? 'active' : '' ?>"
                        href="post-companies.php">Add Company</a>

                </nav>
            </div>

            <!-- Jobs collapsible menu -->
            <?php $isJobPage = in_array(basename($_SERVER['PHP_SELF']), ['job-list.php', 'post-jobs.php']); ?>
            <a class="nav-link <?= $isJobPage ? 'active' : '' ?> collapsed" data-bs-toggle="collapse" href="#jobsMenu"
                role="button" aria-expanded="<?= $isJobPage ? 'true' : 'false' ?>" aria-controls="jobsMenu">
                💼 Jobs
            </a>
            <div class="collapse <?= $isJobPage ? 'show' : '' ?>" id="jobsMenu">
                <nav class="nav flex-column collapse-inner">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'job-list.php' ? 'active' : '' ?>"
                        href="job-list.php">Job List</a>
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'post-jobs.php' ? 'active' : '' ?>"
                        href="post-jobs.php">Post Job</a>
                </nav>
            </div>


            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'manage_applications.php' ? 'active' : '' ?>"
                href="manage_applications.php">📄 Applications</a>

            <a class="nav-link" href="../logout.php">🚪 Logout</a>
        </nav>
    </div>

    <!-- Main content -->
    <div class="content">
        <!-- Topbar -->
        <nav class="navbar mb-4">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h5"><?= ucfirst(basename($_SERVER['PHP_SELF'], ".php")) ?></span>
                <div>
                    <a href="../logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
                </div>
            </div>
        </nav>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>