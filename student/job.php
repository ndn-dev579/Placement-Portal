<?php
require_once '../auth-check.php';
checkAccess('student');
require_once '../db-functions.php';

// session_start();
$user_id = $_SESSION['user_id']; // Assuming user_id is stored in session after login
$student = getStudentByUserId($user_id);

if (!isset($_GET['id'])) {
    echo "❌ Job ID not provided.";
    exit;
}

$job_id = intval($_GET['id']);
$job = getJobById($job_id);

if (!$job) {
    echo "❌ Job not found.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (applyToJob($job_id, $student['id'])) {
        echo "✅ Successfully applied for the job!";
    } else {
        echo "❌ Failed to apply for the job. Please try again.";
    }
    exit;
}

$already_applied = hasStudentAppliedForJob($job_id, $student['id']); // Check if the student already applied
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($job['title']) ?> - Job Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f9f9fb;
            padding: 40px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            padding: 30px;
        }

        h2 {
            margin-top: 0;
            color: #2c3e50;
        }

        .meta {
            margin-bottom: 10px;
            font-size: 15px;
            color: #555;
        }

        .label {
            font-weight: 600;
            color: #333;
        }

        .value {
            color: #444;
        }

        .section {
            margin: 20px 0;
        }

        .back {
            display: inline-block;
            margin-top: 20px;
            color: #5a80fb;
            text-decoration: none;
            font-weight: 500;
        }

        .back:hover {
            text-decoration: underline;
        }

        .btn {
            display: inline-block;
            padding: 10px 16px;
            margin-right: 10px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: background 0.2s;
        }

        .btn.edit {
            background-color: #5a80fb;
            color: white;
        }

        .btn.edit:hover {
            background-color: #3a60e0;
        }

        .btn.delete {
            background-color: #ff4d4f;
            color: white;
        }

        .btn.delete:hover {
            background-color: #d9363e;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>💼 <?= htmlspecialchars($job['title']) ?></h2>

        <div class="meta"><span class="label">Company:</span> <?= htmlspecialchars($job['company_name']) ?></div>
        <div class="meta"><span class="label">Location:</span> <?= htmlspecialchars($job['location']) ?></div>
        <div class="meta"><span class="label">Salary:</span> <?= htmlspecialchars($job['salary']) ?></div>
        <div class="meta"><span class="label">Last Date to Apply:</span>
            <?= htmlspecialchars($job['last_date_to_apply']) ?></div>
        <div class="meta"><span class="label">Allowed Streams:</span>
            <?= nl2br(htmlspecialchars($job['allowed_streams'])) ?></div>

        <div class="section">
            <h3>📝 Job Description</h3>
            <p><?= nl2br(htmlspecialchars($job['description'])) ?></p>
        </div>

        <div class="section">
            <?php if ($already_applied): ?>
                <p style="color: green; font-weight: bold;">✅ You have already applied for this job.</p>
            <?php else: ?>
                <form method="POST">
                    <button type="submit" class="btn edit">✅ Apply</button>
                </form>
            <?php endif; ?>
        </div>

        <a href="jobs.php" class="back">← Back to Job Listings</a>
    </div>

</body>

</html>