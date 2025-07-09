<?php
require_once '../db-functions.php';

$companies = getAllCompanies();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_id = $_POST['company_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $allowed_streams = $_POST['allowed_streams'];
    $salary = $_POST['salary'];
    $location = $_POST['location'];
    $deadline = $_POST['last_date_to_apply'];

    if (createJob($company_id, $title, $description, $allowed_streams, $salary, $location, $deadline)) {
        echo "<div class='success-message'>✅ Job posted successfully. <a href='job-list.php'>View Jobs</a></div>";
    } else {
        echo "<div class='error-message'>❌ Failed to post job.</div>";
    }
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Job</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f0f4f8, #d9e4f5);
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        form {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: 500;
            color: #333;
        }

        input[type="text"],
        input[type="date"],
        input[type="number"],
        textarea,
        select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            outline: none;
            width: 100%;
        }

        input[type="text"]:focus,
        input[type="date"]:focus,
        textarea:focus,
        select:focus {
            border-color: #5a80fb;
        }

        input[type="submit"] {
            background-color: #5a80fb;
            color: white;
            padding: 12px;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #163cb0;
        }

        .success-message,
        .error-message {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .success-message {
            color: green;
        }

        .error-message {
            color: red;
        }
    </style>
</head>

<body>

    <h2>📝 Post a New Job</h2>

    <form method="POST">
        <label>Company</label>
        <select name="company_id" required>
            <option value="">-- Select Company --</option>
            <?php foreach ($companies as $company): ?>
                <option value="<?= $company['id'] ?>"><?= htmlspecialchars($company['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Job Title</label>
        <input type="text" name="title" required>

        <label>Description</label>
        <textarea name="description" rows="4"></textarea>

        <label>Allowed Streams (comma separated)</label>
        <input type="text" name="allowed_streams" placeholder="e.g., BCA,BSc">

        <label>Salary</label>
        <input type="text" name="salary" placeholder="e.g., ₹30,000/month">

        <label>Location</label>
        <input type="text" name="location" placeholder="e.g., Bangalore, Remote">

        <label>Last Date to Apply</label>
        <input type="date" name="last_date_to_apply" required>

        <input type="submit" value="Post Job">
    </form>

</body>

</html>