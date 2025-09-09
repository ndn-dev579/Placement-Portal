<?php
require_once "admin_header.php"; // sidebar + topbar
require_once "../db-functions.php";

// Handle Approve
if (isset($_POST['approve_id'])) {
    $id = $_POST['approve_id'];
    if (approveStudentById($id)) {
        echo "<script>alert('✅ Student approved!'); window.location.href='view-students.php';</script>";
        exit;
    } else {
        echo "<script>alert('❌ Failed to approve.');</script>";
    }
}

// Handle Reject
if (isset($_POST['reject_id'])) {
    $id = $_POST['reject_id'];
    if (rejectStudentById($id)) {
        echo "<script>alert('⚠️ Student rejected.'); window.location.href='view-students.php';</script>";
        exit;
    } else {
        echo "<script>alert('❌ Failed to reject.');</script>";
    }
}

// Handle Delete Permanently
if (isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];
    if (deleteStudentById($id)) {
        echo "<script>alert('🗑️ Student permanently deleted.'); window.location.href='view-students.php';</script>";
        exit;
    } else {
        echo "<script>alert('❌ Failed to delete.');</script>";
    }
}

$pending_students = getPendingStudents();
?>

<div class="content">
  <div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Pending Student Approvals</h4>
    </div>

    <!-- Student Table -->
    <div class="card shadow-sm">
      <div class="card-body table-responsive">
        <table class="table align-middle">
          <thead class="table-light">
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>PRN</th>
              <th>DOB</th>
              <th>ID Card</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
          <?php if (!empty($pending_students)): ?>
            <?php foreach ($pending_students as $student): ?>
              <tr>
                <td><?= htmlspecialchars($student['username']); ?></td>
                <td><?= htmlspecialchars($student['email']); ?></td>
                <td><?= htmlspecialchars($student['prn']); ?></td>
                <td><?= htmlspecialchars($student['dob']); ?></td>
                <td>
                  <a href="/Placement-Portal/<?= htmlspecialchars($student['id_card']); ?>" target="_blank">
                    <img src="/Placement-Portal/<?= htmlspecialchars($student['id_card']); ?>" height="60" class="rounded shadow-sm"/>
                  </a>
                </td>
                <td>
                  <span class="badge bg-<?= $student['status'] === 'pending' ? 'warning' : ($student['status'] === 'approved' ? 'success' : 'danger'); ?>">
                    <?= htmlspecialchars(ucfirst($student['status'])); ?>
                  </span>
                </td>
                <td>
                  <div class="btn-group" role="group">
                    <form method="POST" style="display:inline;">
                      <input type="hidden" name="approve_id" value="<?= $student['id']; ?>">
                      <button type="submit" class="btn btn-sm btn-success">Approve</button>
                    </form>
                    <form method="POST" style="display:inline;">
                      <input type="hidden" name="reject_id" value="<?= $student['id']; ?>">
                      <button type="submit" class="btn btn-sm btn-warning">Reject</button>
                    </form>
                    <?php if ($student['status'] === 'rejected'): ?>
                      <form method="POST" style="display:inline;">
                        <input type="hidden" name="delete_id" value="<?= $student['id']; ?>">
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete permanently?')">Delete</button>
                      </form>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="text-center text-muted">No pending students 🎉</td>
            </tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php require_once "admin_footer.php"; ?>
