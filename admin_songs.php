<?php
// admin_songs.php
session_start();
require_once __DIR__ . '/includes/db.php';
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php'); exit;
}

$msg = '';

// Handle create/update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $artist = trim($_POST['artist'] ?? '');
    $album = trim($_POST['album'] ?? '');
    $year = trim($_POST['year'] ?? '');
    $file_path = trim($_POST['file_path'] ?? '');
    $image_path = trim($_POST['image_path'] ?? '');

    if ($id > 0) {
        $stmt = $conn->prepare("UPDATE songs SET name=?, artist=?, album=?, year=?, file_path=?, image_path=? WHERE id=?");
        $stmt->bind_param("ssssssi", $name, $artist, $album, $year, $file_path, $image_path, $id);
        $stmt->execute(); $stmt->close();
        $msg = "Song updated.";
    } else {
        $stmt = $conn->prepare("INSERT INTO songs (name,artist,album,year,file_path,image_path) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("ssssss", $name, $artist, $album, $year, $file_path, $image_path);
        $stmt->execute(); $stmt->close();
        $msg = "Song added.";
    }
}

// Delete
if (isset($_GET['del'])) {
    $d = (int)$_GET['del'];
    $conn->query("DELETE FROM songs WHERE id=$d");
    $msg = "Song deleted.";
}

// Edit fetch
$edit = null;
if (isset($_GET['edit'])) {
    $eid = (int)$_GET['edit'];
    $res = $conn->query("SELECT * FROM songs WHERE id=$eid LIMIT 1");
    $edit = $res ? $res->fetch_assoc() : null;
}

// list
$list = $conn->query("SELECT * FROM songs ORDER BY created_at DESC");
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Manage Songs</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>:root{--mp-purple:#6763fd}body{background:#070707;color:#fff;font-family:Arial}</style>
</head>
<body>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 style="color:var(--mp-purple)">Songs</h4>
    <a href="admin_dashboard.php" class="btn btn-outline-light">Back</a>
  </div>

  <?php if($msg): ?><div class="alert alert-success text-dark"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

  <div class="card mb-3 p-3">
    <form method="post" class="row g-2">
      <input type="hidden" name="id" value="<?= htmlspecialchars($edit['id'] ?? 0) ?>">
      <div class="col-md-6"><input name="name" class="form-control bg-dark text-white" placeholder="Song name" required value="<?= htmlspecialchars($edit['name'] ?? '') ?>"></div>
      <div class="col-md-6"><input name="artist" class="form-control bg-dark text-white" placeholder="Artist" value="<?= htmlspecialchars($edit['artist'] ?? '') ?>"></div>
      <div class="col-md-6"><input name="album" class="form-control bg-dark text-white" placeholder="Album" value="<?= htmlspecialchars($edit['album'] ?? '') ?>"></div>
      <div class="col-md-3"><input name="year" class="form-control bg-dark text-white" placeholder="Year" value="<?= htmlspecialchars($edit['year'] ?? '') ?>"></div>
      <div class="col-md-6"><input name="file_path" class="form-control bg-dark text-white" placeholder="Audio file path (uploads/...)" value="<?= htmlspecialchars($edit['file_path'] ?? '') ?>"></div>
      <div class="col-md-6"><input name="image_path" class="form-control bg-dark text-white" placeholder="Image path (uploads/...)" value="<?= htmlspecialchars($edit['image_path'] ?? '') ?>"></div>
      <div class="col-12"><button class="btn btn-primary" style="background:var(--mp-purple);border:none"><?= $edit ? 'Update':'Add' ?> Song</button></div>
    </form>
  </div>

  <div class="card p-3">
    <div class="table-responsive">
      <table class="table table-dark table-striped">
        <thead><tr><th>#</th><th>Cover</th><th>Name</th><th>Artist</th><th>Year</th><th>Audio</th><th>Actions</th></tr></thead>
        <tbody>
          <?php $i=1; while($r=$list->fetch_assoc()): ?>
            <tr>
              <td><?= $i++ ?></td>
              <td><?php if(!empty($r['image_path'])): ?><img src="<?= htmlspecialchars($r['image_path']) ?>" style="height:40px;border-radius:6px"><?php endif; ?></td>
              <td><?= htmlspecialchars($r['name']) ?></td>
              <td><?= htmlspecialchars($r['artist']) ?></td>
              <td><?= htmlspecialchars($r['year']) ?></td>
              <td style="max-width:200px;"><?= htmlspecialchars($r['file_path']) ?></td>
              <td>
                <a class="btn btn-sm btn-outline-light" href="?edit=<?= $r['id'] ?>">Edit</a>
                <a class="btn btn-sm btn-danger" onclick="return confirm('Delete this?')" href="?del=<?= $r['id'] ?>">Delete</a>
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
