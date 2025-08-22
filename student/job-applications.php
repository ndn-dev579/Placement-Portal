<?php
require_once '../auth-check.php';
checkAccess('student');
require_once '../db-functions.php';

//session_start();
$user_id = $_SESSION['user_id']; // Assuming user_id is stored in session after login
$student = getStudentByUserId($user_id);

// Fetch all job applications for the student using the existing function
$job_applications = getApplicationsByStudent($student['id']); // Fetch applications

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Job Applications</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fc;
            padding: 40px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        .applications-container {
            max-width: 800px;
            margin: auto;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .application-card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border: 1px solid #eee;
        }

        .job-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #2c3e50;
        }

        .company {
            color: #777;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .status {
            font-size: 14px;
            font-weight: bold;
            margin-top: 10px;
        }

        .status.pending {
            color: #f39c12;
        }

        .status.accepted {
            color: #27ae60;
        }

        .status.rejected {
            color: #e74c3c;
        }

        .no-applications {
            text-align: center;
            color: #999;
            font-size: 16px;
            margin-top: 50px;
        }
    </style>
</head>

<body>

    <h2>📄 My Job Applications</h2>

    <div class="applications-container">
        <?php if (empty($job_applications)): ?>
            <p class="no-applications">You have not applied for any jobs yet.</p>
        <?php else: ?>
            <?php foreach ($job_applications as $application): ?>
                <div class="application-card">
                    <div class="job-title">💼 <?= htmlspecialchars($application['title']) ?></div>
                    <div class="company">🏢 <?= htmlspecialchars($application['company_name']) ?></div>
                    <div class="status <?= htmlspecialchars(strtolower($application['status'])) ?>">
                        Status: <?= htmlspecialchars(ucfirst($application['status'])) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</body>

</html>