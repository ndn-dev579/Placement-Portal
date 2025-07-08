<?php
$host = 'localhost';
$user = 'root';
$password = ''; // set your DB password
$dbname = 'campushire'; // change this if different

$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT id, logo_path FROM companies";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['id'];
        $oldPath = $row['logo_path'];
        $filename = basename($oldPath); // extracts just the file name

        // Update only if filename is different from old path
        if ($filename !== $oldPath) {
            $updateSql = "UPDATE companies SET logo_path = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $updateSql);
            mysqli_stmt_bind_param($stmt, "si", $filename, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            echo "Updated ID $id: $oldPath → $filename<br>";
        }
    }
    echo "✅ Logo paths updated successfully.";
} else {
    echo "No records found.";
}

mysqli_close($conn);
?>
