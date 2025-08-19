<?php
// admin_reviews.php
session_start();
require_once __DIR__ . '/includes/db.php';
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php'); exit;
}

$msg = '';
if (isset($_GET['del'])) {
    $del = (int)$_GET['del'];
    $conn->query("DELETE FROM reviews WHERE id=$del");
    $msg = "Review deleted.";
}

$q = $conn->query("SELECT r.*, s.name AS song_name FROM reviews r LEFT JOIN songs s ON s.id=r.song_id ORDER BY r.created_at DESC");
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Manage Reviews</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>body{background:#070707;color:#fff;font-family:Arial}.card{background:#101010;border:1px solid rgba(255,255,255,.04)}:root{--mp-purple:#6763fd}</style>
</head>
<body>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 style="color:var(--mp-purple)">Reviews</h4>
    <a href="admin_dashboard.php" class="btn btn-outline-light">Back</a>
  </div>

  <?php if($msg): ?><div class="alert alert-success text-dark"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

  <div class="card p-3">
    <div class="table-responsive">
      <table class="table table-dark table-striped">
        <thead><tr><th>#</th><th>Song</th><th>User</th><th>Rating</th><th>Review</th><th>Date</th><th>Action</th></tr></thead>
        <tbody>
          <?php $i=1; while($r=$q->fetch_assoc()): ?>
            <tr>
              <td><?= $i++ ?></td>
              <td><?= htmlspecialchars($r['song_name'] ?? '—') ?></td>
              <td><?= htmlspecialchars($r['user_name'] ?? $r['username'] ?? 'Anon') ?></td>
              <td><?= htmlspecialchars($r['rating'] ?? '—') ?></td>
              <td style="max-width:420px;"><?= htmlspecialchars($r['review']) ?></td>
              <td><?= htmlspecialchars($r['created_at']) ?></td>
              <td><a onclick="return confirm('Delete?')" class="btn btn-sm btn-danger" href="?del=<?= $r['id'] ?>">Delete</a></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>
