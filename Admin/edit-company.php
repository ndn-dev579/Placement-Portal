<?php
// All backend logic must come before any HTML output.
require_once '../auth-check.php';
checkAccess('admin');
require_once '../db-functions.php';

// Check if an ID is provided in the URL, this is needed for both GET and POST
if (!isset($_GET['id'])) {
    die("Error: No company ID provided. <a href='company-list.php'>Go back</a>");
}
$id = intval($_GET['id']);

$error = '';
$success_message = '';

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $website = $_POST['website'];
    
    // Fetch the current company data to get the existing logo path
    $company = getCompanyById($id);
    $logo_path = $company['logo_path']; 

    // Check if a new logo file has been uploaded successfully
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0 && $_FILES['logo']['size'] > 0) {
        $uploadDir = "../uploads/logo/";
        $fileExtension = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        $uniqueLogoName = "company_" . $id . "_" . time() . "." . $fileExtension;
        $newLogoPath = $uploadDir . $uniqueLogoName;

        if (move_uploaded_file($_FILES['logo']['tmp_name'], $newLogoPath)) {
            // If successful, this is the FILENAME to store in the database
            $logo_path = $uniqueLogoName;
        } else {
            $error = "Error: Failed to upload the new logo.";
        }
    }

    // If there was no upload error, proceed to update the database
    if (empty($error)) {
        if (updateCompany($id, $name, $description, $website, $logo_path)) {
            // Redirect to the same page with a success flag
            header("Location: edit-company.php?id=$id&success=1");
            exit;
        } else {
            $error = "Failed to update company in the database.";
        }
    }
}

// Now we can start outputting the HTML page
require_once 'admin_header.php';

// Check for a success flag from the redirect
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $success_message = "Company details have been updated successfully!";
}

// Fetch the latest company data to display in the form
$company = getCompanyById($id);
if (!$company) {
    // A better way to handle "not found" inside the page layout
    echo "<div class='alert alert-danger'>Error: Company not found. <a href='company-list.php'>Go back</a></div>";
    require_once 'admin_footer.php';
    exit;
}
?>

<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">Edit Company Details</h4>
            <a href="company-list.php" class="btn btn-outline-secondary">← Back to Company List</a>
        </div>
        <div class="card-body">
            
            <!-- Display Success or Error Messages -->
            <?php if ($success_message): ?>
                <div class="alert alert-success"><?= $success_message ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="mt-3">
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Company Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($company['name']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="4"><?= htmlspecialchars($company['description']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="website" class="form-label fw-semibold">Website</label>
                    <input type="url" id="website" name="website" class="form-control" value="<?= htmlspecialchars($company['website']) ?>">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Current Logo</label>
                        <div class="logo-preview">
                            <?php if (!empty($company['logo_path'])): ?>
                                <img src="../uploads/logo/<?= htmlspecialchars($company['logo_path']) ?>" alt="Company Logo" class="img-thumbnail" style="max-width: 120px;">
                            <?php else: ?>
                                <p class="text-muted">No logo currently uploaded.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="logo" class="form-label fw-semibold">Upload New Logo (Optional)</label>
                        <input type="file" id="logo" name="logo" class="form-control">
                        <small class="form-text text-muted">Uploading a new file will replace the current logo.</small>
                    </div>
                </div>

                <div class="d-grid mt-3">
                    <button type="submit" class="btn btn-primary btn-lg">Update Company</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require_once 'admin_footer.php';
?>

