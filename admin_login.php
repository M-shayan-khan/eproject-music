<?php
// admin_login.php
session_start();
require_once __DIR__ . '/includes/db.php';

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: admin_dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Please enter email and password.';
    } else {
        // ✅ Query the admins table (not users)
        $stmt = $conn->prepare("SELECT id, email, password FROM admins WHERE email = ? LIMIT 1");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            // ✅ Check password (plain or hashed)
            if (password_verify($password, $row['password']) || $password === $row['password']) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $row['id'];
                $_SESSION['admin_email'] = $row['email'];
                header('Location: admin_dashboard.php');
                exit;
            } else {
                $error = 'Invalid credentials.';
            }
        } else {
            $error = 'Invalid credentials.';
        }
        $stmt->close();
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Login | MegaPod</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root{--mp-purple:#6763fd}
    body{background:#060606;color:#fff;font-family:Arial,Helvetica,sans-serif;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;padding:15px;box-sizing:border-box}
    .card{background:#0f0f0f;border:1px solid rgba(255,255,255,.05);width:100%;max-width:420px;padding:24px;border-radius:12px;box-shadow:0 10px 40px rgba(91, 86, 224, 0.2);margin:0 auto}
    .form-control{background:#141414;border:1px solid #222;color:#fff}
    .form-label{color:white}
    .btn-purple{background:var(--mp-purple);border:none;color:#fff}
    .btn-purple:hover{background:#4e48d5;color:#fff}
    h3{color:var(--mp-purple);text-align:center;margin-bottom:18px}
    .error{color:#ff6b6b;margin-bottom:12px}
    @media (max-width: 576px) {
      .card{padding:16px;max-width:100%}
      h3{font-size:1.5rem}
      .form-control{font-size:0.9rem}
      .btn-purple{font-size:0.9rem;padding:8px}
    }
  </style>
</head>
<body>
  <div class="card">
    <h3>MegaPod Admin</h3>
    <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="post" novalidate>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input class="form-control" type="email" name="email" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input class="form-control" type="password" name="password" required>
      </div>
      <button class="btn btn-purple w-100">Sign in</button>
    </form>
  </div>
</body>
</html>