<?php
require_once '../auth-check.php';
checkAccess('admin');
session_start();

echo "Entered admin dashboard";
?>


<p>
    welcome, <?php
    echo $_SESSION['username'];
    ?>
</p>