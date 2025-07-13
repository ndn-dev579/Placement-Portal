<?php
require_once '../db-functions.php';

if (!isset($_GET['id'])) {
    echo "<div class='error-message'>❌ Job ID not provided.</div>";
    exit;
}

$job_id = intval($_GET['id']);
$job = getJobById($job_id);
$companies = getAllCompanies();

if (!$job) {
    echo "<div class='error-message'>❌ Job not found.</div>";
    exit;
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_id = $_POST['company_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $allowed_streams = $_POST['allowed_streams'];
    $salary = $_POST['salary'];
    $location = $_POST['location'];
    $deadline = $_POST['deadline'];

    if (updateJob($job_id, $company_id, $title, $description, $allowed_streams, $salary, $location, $deadline)) {
        echo "<div class='success-message'>✅ Job updated successfully. <a href='job-list.php'>View Jobs</a></div>";
    } else {
        echo "<div class='error-message'>❌ Failed to update job.</div>";
    }
}
?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f2f4f8;
            padding: 40px;
        }

        form {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        label {
            display: block;
            font-weight: 500;
            margin-top: 15px;
            margin-bottom: 6px;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        textarea,
        select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        button {
            margin-top: 20px;
            padding: 12px 20px;
            background-color: #5a80fb;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        button:hover {
            background-color: #3a60e0;
        }
    </style>
</head>

<body>

    <h2>✏️ Edit Job Posting</h2>

    <form method="POST">
        <label>Company</label>
        <select name="company_id" required>
            <?php foreach ($companies as $comp): ?>
                <option value="<?= $comp['id'] ?>" <?= $comp['id'] == $job['company_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($comp['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Job Title</label>
        <input type="text" name="title" value="<?= htmlspecialchars($job['title']) ?>" required>

        <label>Description</label>
        <textarea name="description" rows="4"><?= htmlspecialchars($job['description']) ?></textarea>

        <label>Allowed Streams</label>
        <textarea name="allowed_streams" rows="3"><?= htmlspecialchars($job['allowed_streams']) ?></textarea>

        <label>Salary (₹)</label>
        <input type="text" name="salary" value="<?= htmlspecialchars($job['salary']) ?>" required>

        <label>Location</label> 
        <input type="text" name="location" value="<?= htmlspecialchars($job['location']) ?>" required>

        <label>Last Date to Apply</label>
        <input type="date" name="deadline" value="<?= htmlspecialchars($job['last_date_to_apply']) ?>" required>

        <button type="submit">Update Job</button>
    </form>

</body>

</html>