<?php
require_once 'auth-check.php';
require_once '../db-functions.php';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['compName'];
    $description = $_POST['description'];
    $website = $_POST['compURL'];

    // $logo_name = $_FILES['compLogo']['name'];
    // $logo_tmp = $_FILES['compLogo']['tmp_name'];
    // $upload_path = "../uploads/logo/" . basename($logo_name);
    $logo_name = basename($_FILES['compLogo']['name']); // Get just the file name
    $logo_tmp = $_FILES['compLogo']['tmp_name'];
    $upload_dir = "../uploads/logo/";
    $upload_path = $upload_dir . $logo_name;


    if (move_uploaded_file($logo_tmp, $upload_path)) {

        $success = createCompany($name, $description, $website, $logo_name);

        if ($success) {
            echo "<script>alert('✅ Company added successfully!'); window.location.href='company-list.php';</script>";
        } else {
            echo "<script>alert('❌ Failed to add company.'); history.back();</script>";
        }

    } else {
        echo "<script>alert('❌ Failed to upload logo.'); history.back();</script>";
    }

}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>post company</title>
    <!-- <link rel="stylesheet" href="../css/login_form.css"> -->
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
            gap: 15px;
        }

        label {
            font-weight: 500;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="text"],
        input[type="url"],
        input[type="file"] {
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            outline: none;
            transition: border 0.3s;
        }

        input[type="text"]:focus,
        input[type="url"]:focus,
        input[type="file"]:focus {
            border-color: #5a80fb;
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
    <h2>Add New Company</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="compName">Company Name:</label>
        <input type="text" name="compName" id="compName" placeholder="eg:Google" required>

        <label for="description">Description:</label>
        <textarea rows="3" cols="3" name="description" id="description" required></textarea>

        <label for="compURL">Website URL:</label>
        <input type="url" name="compURL" id="compURL" placeholder="eg:https://www.google.com" required>

        <label for="compLogo">Company Logo (Image File):</label>
        <input type="file" name="compLogo" id="compLogo" required>

        <input type="submit" name="addComp" id="addComp" value="Add Company">
    </form>

</body>

</html>