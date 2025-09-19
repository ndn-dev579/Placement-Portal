<?php
// session_start();
// This will redirect a user to the login page if they are not logged in as a student
require_once "../auth-check.php";
checkAccess("student");
// We'll need the db functions for any page that uses this header
require_once "../db-functions.php";

// --- LOGIC TO FETCH THE CORRECT STUDENT NAME ---

// Get user ID from session
$user_id = $_SESSION['user_id'] ?? 0;

// Fetch the full student profile to get their actual name
$student_profile = getStudentByUserId($user_id);

// Use the name from the student's profile if it exists, otherwise fall back to the username
if ($student_profile && !empty($student_profile['name'])) {
    $student_name = $student_profile['name'];
} else {
    // Fallback for students who haven't created a profile yet
    $student_name = $_SESSION['username'] ?? 'Student';
}

// Create an initial for the avatar using the determined name
$student_initial = strtoupper(substr($student_name, 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Student Dashboard - CampusHire</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 260px; /* Slightly wider for better spacing */
            background: linear-gradient(180deg, #4c1d95 0%, #5b21b6 100%); /* Rich purple gradient */
            color: #f9fafb;
            display: flex;
            flex-direction: column;
        }

        .main-content {
            margin-left: 260px; /* Match new sidebar width */
            padding: 1.5rem;
        }

        .sidebar-brand-container {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-brand {
            font-size: 1.75rem;
            font-weight: 800;
            color: #ffffff;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .sidebar-brand:hover {
            color: #e9d5ff;
        }

        /* Navigation Links */
        .sidebar .nav-link {
            color: #d8b4fe; /* Lighter purple text */
            font-weight: 500;
            font-size: 1rem;
            padding: 0.8rem 1.5rem;
            margin: 0.25rem 0.5rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            transition: all 0.2s ease-in-out;
            border-left: 4px solid transparent; /* Placeholder for active state */
        }

        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }

        /* Active Link Style - a key visual upgrade */
        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            font-weight: 600;
            border-left: 4px solid #ffffff; /* Prominent active indicator */
        }

        .sidebar .nav-link i {
            width: 20px;
            height: 20px;
            margin-right: 1rem;
            stroke-width: 2.5; /* Bolder icons */
        }

        /* Footer section of the sidebar */
        .sidebar-footer {
            margin-top: auto; /* Pushes content to the bottom */
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .user-profile {
            display: flex;
            align-items: center;
            padding: 0.5rem 0;
            margin-bottom: 0.5rem;
        }

        .user-profile .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #a78bfa;
            color: #4c1d95;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.25rem;
            margin-right: 1rem;
        }

        .user-profile .user-info .name {
            font-weight: 600;
            color: #ffffff;
            line-height: 1.2;
        }
         .user-profile .user-info .role {
            font-size: 0.8rem;
            color: #d8b4fe;
        }

        .sidebar-footer .logout-link {
            padding: 0.8rem 0;
            border-left: none; /* No border for logout */
        }
        .sidebar-footer .logout-link:hover {
            background: none;
            border-left: none;
            color: #ffffff; /* Just brighten on hover */
        }

    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand-container">
            <a href="../index.php" class="sidebar-brand">
                CampusHire
            </a>
        </div>
        
        <ul class="nav nav-pills flex-column mt-3">
            <?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
            <li class="nav-item">
                <a href="dashboard.php" class="nav-link <?= $currentPage == 'dashboard.php' ? 'active' : '' ?>">
                    <i data-lucide="layout-dashboard"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="jobs.php" class="nav-link <?= $currentPage == 'jobs.php' ? 'active' : '' ?>">
                    <i data-lucide="search"></i> Find Jobs
                </a>
            </li>
            <li>
                <a href="job-applications.php" class="nav-link <?= $currentPage == 'job-applications.php' ? 'active' : '' ?>">
                    <i data-lucide="file-check-2"></i> My Applications
                </a>
            </li>
            <li>
                <a href="profile.php" class="nav-link <?= $currentPage == 'profile.php' ? 'active' : '' ?>">
                    <i data-lucide="user"></i> My Profile
                </a>
            </li>
            <li>
                <a href="resumes.php" class="nav-link <?= $currentPage == 'resumes.php' ? 'active' : '' ?>">
                    <i data-lucide="file-text"></i> Resume Templates
                </a>
            </li>
        </ul>
        
        <div class="sidebar-footer">
            <div class="user-profile">
                <div class="avatar"><?php echo htmlspecialchars($student_initial); ?></div>
                <div class="user-info">
                    <div class="name"><?php echo htmlspecialchars($student_name); ?></div>
                    <div class="role">Student</div>
                </div>
            </div>
            <a href="../logout.php" class="nav-link logout-link d-flex align-items-center">
                <i data-lucide="log-out"></i> Logout
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- The header bar that says "Welcome back" is no longer needed, as that info is now in the sidebar profile. -->
        <!-- You can keep it if you like, but the design is cleaner without it. -->
        <main>
            <!-- Page-specific content will go here -->

