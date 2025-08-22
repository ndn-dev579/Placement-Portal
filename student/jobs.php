<?php
require_once '../auth-check.php';

checkAccess('student');
require_once '../db-functions.php';

$jobs = getAllJobs();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Listings</title>
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

        .job-container {
            max-width: 800px;
            margin: auto;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .job-card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            cursor: pointer;
            border: 1px solid #eee;
            transition: 0.2s;
        }

        .job-card:hover {
            border-color: #5a80fb;
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

        .job-info {
            font-size: 14px;
            color: #444;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .badge {
            background: #eef2ff;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 13px;
            color: #333;
        }

        .link-wrapper {
            text-decoration: none;
            color: inherit;
        }

        .no-jobs {
            text-align: center;
            color: #999;
            font-size: 16px;
            margin-top: 50px;
        }
    </style>
</head>

<body>

    <h2>📄 Available Job Opportunities</h2>

    <div class="job-container">
        <?php if (empty($jobs)): ?>
            <p class="no-jobs">No job postings found.</p>
        <?php else: ?>
            <?php foreach ($jobs as $job): ?>
                <a class="link-wrapper" href="job.php?id=<?= $job['id'] ?>">
                    <div class="job-card">
                        <div class="job-title">💼 <?= htmlspecialchars($job['title']) ?></div>
                        <div class="company">🏢 <?= htmlspecialchars($job['company_name']) ?></div>
                        <div class="job-info">
                            <div class="badge">📍 <?= htmlspecialchars($job['location']) ?></div>
                            <div class="badge">💰 <?= htmlspecialchars($job['salary']) ?></div>
                            <div class="badge">🕒 Apply by <?= htmlspecialchars($job['last_date_to_apply']) ?></div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</body>

</html>