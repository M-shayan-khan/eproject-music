<?php
// admin_dashboard.php
session_start();
require_once __DIR__ . '/includes/db.php';
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit;
}

// counts
$totUsers = (int)$conn->query("SELECT COUNT(*) c FROM users")->fetch_assoc()['c'] ?? 0;
$totSongs = (int)$conn->query("SELECT COUNT(*) c FROM songs")->fetch_assoc()['c'] ?? 0;
$totReviews = (int)$conn->query("SELECT COUNT(*) c FROM reviews")->fetch_assoc()['c'] ?? 0;
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Dashboard | MegaPod</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root{--mp-purple:#6763fd}
    body{background:#070707;color:#fff;font-family:Arial,Helvetica,sans-serif;min-height:100vh;margin:0}
    .nav{background:#0f0f0f;border-bottom:1px solid rgba(255,255,255,.03);position:sticky;top:0;z-index:1000}
    .sidebar{background:#0f0f0f;padding:16px;min-height:calc(100vh - 56px)}
    .sidebar a{display:block;color:#cfcfcf;padding:8px;border-radius:8px;text-decoration:none}
    .sidebar a.active, .sidebar a:hover{background:rgba(103,99,253,.12);color:#fff}
    .card{background:#101010;border:1px solid rgba(255,255,255,.04)}
    .btn-purple{background:var(--mp-purple);border:none}
    @media (max-width: 767.98px) {
      .sidebar{padding:8px}
      .sidebar a{font-size:0.9rem;padding:6px}
      .nav .fs-5{font-size:1.1rem !important}
      .nav .btn-sm{font-size:0.8rem;padding:0.4rem 0.8rem}
      .container-fluid{padding:0 8px}
      main{padding:16px !important}
      .card.p-3{padding:12px}
      .h3{font-size:1.5rem}
      .h5{font-size:1.1rem}
      .btn{font-size:0.9rem;padding:6px 12px}
      .d-flex.gap-2{flex-wrap:wrap;gap:8px}
    }
  </style>
</head>
<body>
<nav class="nav d-flex align-items-center justify-content-between px-3 py-2">
  <div class="d-flex align-items-center gap-3">
    <a class="text-white fs-5 text-decoration-none" href="admin_dashboard.php"><i class="fa-solid fa-music"></i> MegaPod Admin</a>
  </div>
  <div class="d-flex align-items-center gap-2">
    <span class="text-secondary me-2">Hi, <?= htmlspecialchars($_SESSION['admin_username'] ?? 'Shayan (Admin)') ?></span>
    <a class="btn btn-sm btn-outline-light" href="admin_logout.php">Logout</a>
  </div>
</nav>

<div class="container-fluid">
  <div class="row">
    <aside class="col-12 col-md-3 sidebar">
      <a href="admin_dashboard.php" class="active"><i class="fa-solid fa-gauge me-2"></i> Dashboard</a>
      <a href="admin_songs.php"><i class="fa-solid fa-music me-2"></i> Songs</a>
      <a href="admin_reviews.php"><i class="fa-solid fa-comments me-2"></i> Reviews</a>
      <a href="admin_users.php"><i class="fa-solid fa-users me-2"></i> Users</a>
    </aside>

    <main class="col-12 col-md-9 p-4">
      <h3 style="color:var(--mp-purple)">Dashboard</h3>
      <div class="row g-3">
        <div class="col-12 col-md-4"><div class="card p-3"><small class="text-secondary">Users</small><div class="h3"><?= $totUsers ?></div></div></div>
        <div class="col-12 col-md-4"><div class="card p-3"><small class="text-secondary">Songs</small><div class="h3"><?= $totSongs ?></div></div></div>
        <div class="col-12 col-md-4"><div class="card p-3"><small class="text-secondary">Reviews</small><div class="h3"><?= $totReviews ?></div></div></div>
      </div>
      <div class="mt-4">
        <div class="card p-3">
          <h5>Quick Actions</h5>
          <div class="d-flex gap-2 mt-2">
            <a class="btn btn-purple" href="admin_songs.php"><i class="fa-solid fa-plus me-1"></i> Add / Manage Songs</a>
            <a class="btn btn-outline-light" href="admin_reviews.php"><i class="fa-solid fa-comments me-1"></i> Manage Reviews</a>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>
</body>
</html>