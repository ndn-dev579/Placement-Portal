<?php
require_once '../auth-check.php';
checkAccess('admin');

echo "Entered admin dashboard";
?>


<p>
    welcome, <?php
    echo $_SESSION['username'];
    ?>
</p>