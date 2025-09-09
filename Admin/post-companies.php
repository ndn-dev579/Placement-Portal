<?php
require_once '../auth-check.php';
checkAccess('admin');
require_once '../db-functions.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['compName'];
    $description = $_POST['description'];
    $website = $_POST['compURL'];

    $logo_name = basename($_FILES['compLogo']['name']);
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

<?php require_once "admin_header.php"; ?>

<div class="d-flex justify-content-center align-items-start" style="min-height:70vh;">
    <div class="card shadow-sm p-4" style="width: 100%; max-width: 600px;">
        <h2 class="mb-4 text-center">➕ Add New Company</h2>

        <form action="" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="compName" class="form-label">Company Name</label>
                <input type="text" class="form-control" name="compName" id="compName" placeholder="eg: Google" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea rows="3" class="form-control" name="description" id="description" required></textarea>
            </div>

            <div class="mb-3">
                <label for="compURL" class="form-label">Website URL</label>
                <input type="url" class="form-control" name="compURL" id="compURL" placeholder="eg: https://www.google.com" required>
            </div>

            <div class="mb-3">
                <label for="compLogo" class="form-label">Company Logo (Image File)</label>
                <input type="file" class="form-control" name="compLogo" id="compLogo" required>
            </div>

            <button type="submit" name="addComp" id="addComp" class="btn btn-primary w-100">Add Company</button>
        </form>
    </div>
</div>

<?php require_once "admin_footer.php"; ?>
