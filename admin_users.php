<?php
// admin_users.php
session_start();
require_once __DIR__ . '/includes/db.php';
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php'); exit;
}

$msg = '';
if (isset($_GET['make']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $make = $_GET['make'] === 'admin' ? 'admin' : 'user';
    if ($id === (int)($_SESSION['admin_id'] ?? 0) && $make === 'user') {
        $msg = "You cannot demote your own account.";
    } else {
        $stmt = $conn->prepare("UPDATE users SET role=? WHERE id=?");
        $stmt->bind_param("si", $make, $id);
        $stmt->execute(); $stmt->close();
        $msg = "Role updated.";
    }
}

$list = $conn->query("SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC");
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Users</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>body{background:#070707;color:#fff;font-family:Arial}:root{--mp-purple:#6763fd}</style>
</head>
<body>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 style="color:var(--mp-purple)">Users</h4>
    <a href="admin_dashboard.php" class="btn btn-outline-light">Back</a>
  </div>

  <?php if($msg): ?><div class="alert alert-success text-dark"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

  <div class="card p-3">
    <div class="table-responsive">
      <table class="table table-dark table-striped">
        <thead><tr><th>#</th><th>Username</th><th>Email</th><th>Role</th><th>Joined</th><th>Actions</th></tr></thead>
        <tbody>
          <?php $i=1; while($u=$list->fetch_assoc()): ?>
            <tr>
              <td><?= $i++ ?></td>
              <td><?= htmlspecialchars($u['username']) ?></td>
              <td><?= htmlspecialchars($u['email']) ?></td>
              <td><?= htmlspecialchars($u['role']) ?></td>
              <td><?= htmlspecialchars($u['created_at']) ?></td>
              <td>
                <?php if($u['role'] !== 'admin'): ?>
                  <a class="btn btn-sm btn-outline-light" href="?make=admin&id=<?= $u['id'] ?>">Make Admin</a>
                <?php else: ?>
                  <a class="btn btn-sm btn-warning" href="?make=user&id=<?= $u['id'] ?>" onclick="return confirm('Demote this admin?')">Make User</a>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>
