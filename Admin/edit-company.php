<?php
require_once '../db-functions.php';

if (!isset($_GET['id'])) {
    echo "No company ID provided.";
    exit;
}

$id = intval($_GET['id']);
$company = getCompanyById($id);

if (!$company) {
    echo "Company not found.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    // $title = $_POST['title'];
    $description = $_POST['description'];
    $website = $_POST['website'];
    $existing_logo = $_POST['existing_logo'];

    // Handle new logo upload (optional)
    $logo_path = $existing_logo; // default to existing
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
        $uploadDir = "../uploads/logo/";
        $newLogoName = basename($_FILES['logo']['name']);
        $newLogoPath = $uploadDir . $newLogoName;

        if (move_uploaded_file($_FILES['logo']['tmp_name'], $newLogoPath)) {
            $logo_path = "uploads/logo/" . $newLogoName;
        }
    }

    if (updateCompany($id, $name, $description, $website, $logo_path)) {
        echo "✅ Company updated successfully. <a href='company-list.php'>Go back</a>";
    } else {
        echo "❌ Failed to update company.";
    }
    exit;
}
?>

<!-- HTML form -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update company</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f0f4f8, #d9e4f5);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px;

        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        form {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            display: flex;
            flex-direction: column;
            /* gap: 15px; */

        }

        label {
            font-weight: 500;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="text"],
        input[type="url"],
        input[type="file"],
        textarea {
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            outline: none;
            transition: border 0.3s;
            width: 100%;
        }

        input[type="text"]:focus,
        input[type="url"]:focus,
        input[type="file"]:focus,
        textarea:focus {
            border-color: #5a80fb;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        img {
            max-width: 100px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            margin-top: 10px;
            padding: 12px;
            background-color: #5a80fb;
            color: white;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #163cb0;
        }
    </style>

</head>

<body>


    <h2>Edit Company</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= htmlspecialchars($company['id']) ?>">
        <input type="hidden" name="existing_logo" value="<?= htmlspecialchars($company['logo_path']) ?>">

        <label>Company Name</label><br>
        <input type="text" name="name" value="<?= htmlspecialchars($company['name']) ?>" required><br><br>

        <label>Description</label><br>
        <textarea name="description"><?= htmlspecialchars($company['description']) ?></textarea><br><br>

        <label>Website</label><br>
        <input type="url" name="website" value="<?= htmlspecialchars($company['website']) ?>"><br><br>

        <label>Current Logo:</label><br>
        <?php if (!empty($company['logo_path'])): ?>
            <img src="../uploads/logo/<?= htmlspecialchars($company['logo_path']) ?>" alt="Company Logo" width="100"><br>
        <?php endif; ?>
        <input type="file" name="logo"><br><br>

        <input type="submit" value="Update Company">
    </form>
</body>

</html>