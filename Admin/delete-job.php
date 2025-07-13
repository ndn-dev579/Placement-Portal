<?php
require_once '../auth-check.php';
checkAccess('admin');
require_once '../db-functions.php';

$success = false;
$message = '';

if (!isset($_GET['id'])) {
    $message = "❌ Job ID not provided.";
} else {
    $job_id = intval($_GET['id']);

    if (deleteJob($job_id)) {
        $success = true;
        $message = "✅ Job deleted successfully.";
    } else {
        $message = "❌ Failed to delete job.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Delete Job</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .message-box {
            background: white;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .message-box h2 {
            font-size: 20px;
            color:
                <?= $success ? '#2e7d32' : '#c62828' ?>
            ;
        }

        .message-box a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #5a80fb;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }

        .message-box a:hover {
            background-color: #3f66e0;
        }
    </style>
</head>

<body>

    <div class="message-box">
        <h2><?= $message ?></h2>
        <a href="job-list.php">🔙 Back to Job Listings</a>
    </div>

</body>

</html>