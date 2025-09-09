<?php
require_once '../auth-check.php';
checkAccess('admin');
require_once '../db-functions.php';

$companies = getAllCompanies();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_id = $_POST['company_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $allowed_streams = $_POST['allowed_streams'];
    $salary = $_POST['salary'];
    $location = $_POST['location'];
    $deadline = $_POST['last_date_to_apply'];

    if (createJob($company_id, $title, $description, $allowed_streams, $salary, $location, $deadline)) {
        $message = "<div class='success-message'>✅ Job posted successfully. <a href='job-list.php'>View Jobs</a></div>";
    } else {
        $message = "<div class='error-message'>❌ Failed to post job.</div>";
    }
}

include 'admin_header.php'; // sidebar + topbar
?>

<style>
    .job-form-page {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #f0f4f8, #d9e4f5);
        padding: 40px;
        display: flex;
        flex-direction: column;
        align-items: center;
        min-height: calc(100vh - 100px); /* account for header */
    }

    .job-form-page h2 {
        margin-bottom: 20px;
        color: #333;
    }

    form.job-form {
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

    form.job-form label {
        font-weight: 500;
        color: #333;
    }

    form.job-form input[type="text"],
    form.job-form input[type="date"],
    form.job-form input[type="number"],
    form.job-form textarea,
    form.job-form select {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 16px;
        outline: none;
        width: 100%;
    }

    form.job-form input:focus,
    form.job-form textarea:focus,
    form.job-form select:focus {
        border-color: #5a80fb;
    }

    form.job-form input[type="submit"] {
        background-color: #5a80fb;
        color: white;
        padding: 12px;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    form.job-form input[type="submit"]:hover {
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

    .back-link {
        margin-bottom: 15px;
    }

    .back-link a {
        text-decoration: none;
        font-size: 14px;
        color: #5a80fb;
    }

    .back-link a:hover {
        text-decoration: underline;
    }
</style>

<div class="job-form-page">
    <h2>📝 Post a New Job</h2>

    <?= $message ?>

    <div class="back-link">
        <a href="job-list.php">⬅️ Back to Job List</a>
    </div>

    <form class="job-form" method="POST">
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
</div>

<?php include 'admin_footer.php'; ?>
