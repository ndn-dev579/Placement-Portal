<?php
require_once 'auth-check.php';
require_once '../db-functions.php';
if (!isset($_GET['id'])) {
    echo "No company ID provided.";
    exit;
}

$id = intval($_GET['id']);

$company = getCompanyById($id);
if ($company && !empty($company['logo_path'])) {
    $logoFilePath = '../uploads/logo/' . $company['logo_path'];
    if (file_exists($logoFilePath)) {
        unlink($logoFilePath); // deletes file
    }
}

if (deleteCompany($id)) {
    header("Location: company-list.php?deleted=1");
    exit;
} else {
    echo "❌ Failed to delete company.";
}
?>