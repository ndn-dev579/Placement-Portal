<?php
require_once '../auth-check.php';
checkAccess('admin');
require_once '../db-functions.php';

// Check if an ID is provided in the URL
if (!isset($_GET['id'])) {
    // It's better to redirect or show a proper error page
    die("Error: No company ID provided. <a href='company-list.php'>Go back</a>");
}

$id = intval($_GET['id']);
$company = getCompanyById($id);

// Check if the company exists
if (!$company) {
    die("Error: Company not found. <a href='company-list.php'>Go back</a>");
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $website = $_POST['website'];
    
    // Start with the existing logo filename from the database
    $logo_path = $company['logo_path']; 

    // Check if a new logo file has been uploaded successfully
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0 && $_FILES['logo']['size'] > 0) {
        
        // Define the physical directory to save the file (from admin folder, go up one level)
        $uploadDir = "../uploads/logo/";
        
        // --- BEST PRACTICE: Create a unique filename to prevent overwrites ---
        $fileExtension = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        $uniqueLogoName = "company_" . $id . "_" . time() . "." . $fileExtension;
        
        $newLogoPath = $uploadDir . $uniqueLogoName;

        // Try to move the uploaded file to the destination
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $newLogoPath)) {
            // If successful, this is the FILENAME to store in the database
            $logo_path = $uniqueLogoName;
        } else {
            // Handle potential upload failure
            echo "❌ Error: Failed to upload the new logo.";
            exit;
        }
    }

    // Call the database function to update the company details
    if (updateCompany($id, $name, $description, $website, $logo_path)) {
        echo "✅ Company updated successfully. <a href='company-list.php'>Go back to company list</a>";
    } else {
        echo "❌ Failed to update company in the database.";
    }
    exit; // Stop script execution after processing
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Company</title>
    <!-- Simple styling for a clean look -->
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; padding: 2rem; }
        .container { max-width: 600px; margin: auto; background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; }
        form div { margin-bottom: 1rem; }
        label { display: block; font-weight: bold; margin-bottom: 0.5rem; }
        input[type="text"], input[type="url"], textarea { width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; }
        textarea { min-height: 120px; resize: vertical; }
        .logo-preview img { max-width: 100px; border-radius: 6px; border: 1px solid #ddd; }
        input[type="submit"] { width: 100%; padding: 0.75rem; background-color: #2563EB; color: white; font-weight: bold; border: none; border-radius: 6px; cursor: pointer; transition: background-color 0.3s; }
        input[type="submit"]:hover { background-color: #1D4ED8; }
    </style>
</head>
<body>

    <div class="container">
        <h2>Edit Company Details</h2>
        <form method="POST" enctype="multipart/form-data">
            
            <div>
                <label for="name">Company Name</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($company['name']) ?>" required>
            </div>

            <div>
                <label for="description">Description</label>
                <textarea id="description" name="description"><?= htmlspecialchars($company['description']) ?></textarea>
            </div>

            <div>
                <label for="website">Website</label>
                <input type="url" id="website" name="website" value="<?= htmlspecialchars($company['website']) ?>">
            </div>

            <div>
                <label>Current Logo</label>
                <div class="logo-preview">
                    <?php if (!empty($company['logo_path'])): ?>
                        <!-- This is the corrected image path, now manually built -->
                        <img src="../uploads/logo/<?= htmlspecialchars($company['logo_path']) ?>" alt="Company Logo">
                    <?php else: ?>
                        <p>No logo uploaded.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div>
                <label for="logo">Upload New Logo (Optional)</label>
                <input type="file" id="logo" name="logo">
            </div>

            <input type="submit" value="Update Company">
        </form>
    </div>

</body>
</html>

