<?php
require_once 'auth-check.php';
require_once '../db-functions.php';

// Handle Approve
if (isset($_POST['approve_id'])) {
    $id = $_POST['approve_id'];
    if (approveStudentById($id)) {
        echo "<script>alert('✅ Student approved!'); window.location.href='';</script>";
        exit;
    } else {
        echo "<script>alert('❌ Failed to approve.');</script>";
    }
}

// Handle Reject
if (isset($_POST['reject_id'])) {
    $id = $_POST['reject_id'];
    if (rejectStudentById($id)) {
        echo "<script>alert('⚠️ Student rejected.'); window.location.href='';</script>";
        exit;
    } else {
        echo "<script>alert('❌ Failed to reject.');</script>";
    }
}

// Handle Delete Permanently
if (isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];
    if (deleteStudentById($id)) {
        echo "<script>alert('🗑️ Student permanently deleted.'); window.location.href='';</script>";
        exit;
    } else {
        echo "<script>alert('❌ Failed to delete.');</script>";
    }
}

$pending_students = getPendingStudents();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Pending Students Approval</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }

        img {
            height: 60px;
        }

        form {
            display: inline;
        }
    </style>
</head>

<body>
    <h2>Pending Students</h2>
    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>PRN</th>
            <th>DOB</th>
            <th>ID Card</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($pending_students as $student): ?>
            <tr>
                <td><?php echo htmlspecialchars($student['username']); ?></td>
                <td><?php echo htmlspecialchars($student['email']); ?></td>
                <td><?php echo htmlspecialchars($student['prn']); ?></td>
                <td><?php echo htmlspecialchars($student['dob']); ?></td>
                <td>
                    <a href="/Placement-Portal/<?php echo $student['id_card']; ?>" target="_blank">
                        <img src="/Placement-Portal/<?= htmlspecialchars($student['id_card']) ?>" width="120" />

                    </a>
                </td>
                <td><?php echo $student['status']; ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="approve_id" value="<?php echo $student['id']; ?>">
                        <button type="submit">✅ Approve</button>
                    </form>
                    <form method="POST">
                        <input type="hidden" name="reject_id" value="<?php echo $student['id']; ?>">
                        <button type="submit">❌ Reject</button>
                    </form>
                    <?php if ($student['status'] === 'rejected'): ?>
                        <form method="POST">
                            <input type="hidden" name="delete_id" value="<?php echo $student['id']; ?>">
                            <button type="submit" onclick="return confirm('Delete permanently?')">🗑️ Delete</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>