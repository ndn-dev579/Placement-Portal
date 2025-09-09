<?php
require_once '../auth-check.php';
checkAccess('admin');
require_once '../db-functions.php';
$companies = getAllCompanies();
?>

<?php require_once "admin_header.php"; ?>

<h2 class="mb-4">📋 Company Directory</h2>

<?php if (isset($_GET['deleted']) && $_GET['deleted'] == '1'): ?>
    <div class="alert alert-success">✅ Company deleted successfully!</div>
<?php endif; ?>

<div class="row">
    <?php if (empty($companies)): ?>
        <p>No companies found.</p>
    <?php else: ?>
        <?php foreach ($companies as $company): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <img class="card-img-top p-3"
                         style="height:100px; object-fit:contain;"
                         src="/Placement-Portal/uploads/logo/<?= htmlspecialchars($company['logo_path']) ?>"
                         alt="Logo">

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($company['name']) ?></h5>
                        <p class="card-text flex-grow-1"><?= htmlspecialchars($company['description']) ?></p>
                        <a href="<?= htmlspecialchars($company['website']) ?>" target="_blank" class="btn btn-primary btn-sm mb-2">
                            🌐 Visit Website
                        </a>
                        <div class="d-flex justify-content-between">
                            <a class="btn btn-info btn-sm"
                               href="edit-company.php?id=<?= $company['id'] ?>">✏️ Edit</a>
                            <a class="btn btn-danger btn-sm"
                               href="delete-company.php?id=<?= $company['id'] ?>"
                               onclick="return confirm('Are you sure you want to delete this company?')">🗑️ Delete</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once "admin_footer.php"; ?>
