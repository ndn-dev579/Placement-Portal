<?php
require_once "admin_header.php"; // sidebar + topbar + session check
require_once "../db-functions.php";

$status = $_GET['status'] ?? 'all';
$toastMsg = '';
$toastType = ''; // success / warning / danger

// --- Handle POST Actions ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approve_id'])) {
        $id = (int)$_POST['approve_id'];
        if (approveStudentById($id)) {
            $toastMsg = "✅ Student approved!";
            $toastType = "success";
        } else {
            $toastMsg = "❌ Failed to approve.";
            $toastType = "danger";
        }
    }
    if (isset($_POST['reject_id'])) {
        $id = (int)$_POST['reject_id'];
        if (rejectStudentById($id)) {
            $toastMsg = "⚠️ Student rejected.";
            $toastType = "warning";
        } else {
            $toastMsg = "❌ Failed to reject.";
            $toastType = "danger";
        }
    }
    if (isset($_POST['delete_id'])) {
        $id = (int)$_POST['delete_id'];
        if (deleteStudentById($id)) {
            $toastMsg = "🗑️ Student permanently deleted.";
            $toastType = "danger";
        } else {
            $toastMsg = "❌ Failed to delete.";
            $toastType = "danger";
        }
    }

    // After action, redirect back with toast message in session
    if ($toastMsg) {
        session_start();
        $_SESSION['toastMsg'] = $toastMsg;
        $_SESSION['toastType'] = $toastType;
        header("Location: manage_students.php?status={$status}");
        exit;
    }
}

// --- Fetch students ---
$conn = getConnection();

if ($status === 'all') {
    $sql = "SELECT s.*, u.email, u.username FROM students s JOIN users u ON s.user_id = u.id ORDER BY s.id DESC";
    $rs = mysqli_query($conn, $sql);
} else {
    $stmt = mysqli_prepare($conn, "SELECT s.*, u.email, u.username FROM students s JOIN users u ON s.user_id = u.id WHERE s.status = ? ORDER BY s.id DESC");
    mysqli_stmt_bind_param($stmt, "s", $status);
    mysqli_stmt_execute($stmt);
    $rs = mysqli_stmt_get_result($stmt);
}

// --- Check for toast message ---
// session_start();
if (!empty($_SESSION['toastMsg'])) {
    $toastMsg = $_SESSION['toastMsg'];
    $toastType = $_SESSION['toastType'] ?? 'info';
    unset($_SESSION['toastMsg'], $_SESSION['toastType']);
}
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>👨‍🎓 Manage Students</h3>
        <form class="d-flex" method="get">
            <select class="form-select me-2" name="status" onchange="this.form.submit()">
                <option value="all" <?= $status==='all'?'selected':'' ?>>All</option>
                <option value="pending" <?= $status==='pending'?'selected':'' ?>>Pending</option>
                <option value="approved" <?= $status==='approved'?'selected':'' ?>>Approved</option>
                <option value="rejected" <?= $status==='rejected'?'selected':'' ?>>Rejected</option>
            </select>
        </form>
    </div>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light text-dark">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>PRN</th>
                        <th>Phone</th>
                        <th>DOB</th>
                        <th>Resume</th>
                        <th>ID Card</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($rs && mysqli_num_rows($rs) > 0): ?>
                    <?php while($s = mysqli_fetch_assoc($rs)): ?>
                        <tr>
                            <td><?= htmlspecialchars($s['username']) ?></td>
                            <td><?= htmlspecialchars($s['email']) ?></td>
                            <td><?= htmlspecialchars($s['prn'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($s['phone_number'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($s['dob'] ?? '-') ?></td>
                            <td>
                                <?php if (!empty($s['resume_path'])): ?>
                                    <a class="btn btn-sm btn-outline-secondary" target="_blank" href="/Placement-Portal/<?= htmlspecialchars($s['resume_path']) ?>">View</a>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($s['id_card'])): ?>
                                    <a href="/Placement-Portal/<?= htmlspecialchars($s['id_card']) ?>" target="_blank">
                                        <img src="/Placement-Portal/<?= htmlspecialchars($s['id_card']) ?>" height="50" class="rounded shadow-sm">
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-<?= $s['status']==='approved'?'success':($s['status']==='pending'?'warning':'danger') ?>">
                                    <?= htmlspecialchars(ucfirst($s['status'])) ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="approve_id" value="<?= $s['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                    </form>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="reject_id" value="<?= $s['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-warning">Reject</button>
                                    </form>
                                    <?php if ($s['status']==='rejected'): ?>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="delete_id" value="<?= $s['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete permanently?')">Delete</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">No students found.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Toast HTML -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="actionToast" class="toast align-items-center text-bg-<?= $toastType ?: 'info' ?> border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <?= htmlspecialchars($toastMsg) ?>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var toastEl = document.getElementById('actionToast');
    if (toastEl && toastEl.textContent.trim() !== '') {
        var toast = new bootstrap.Toast(toastEl, { delay: 3000 });
        toast.show();
    }
});
</script>

<?php require_once "admin_footer.php"; ?>
