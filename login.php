<?php
// Start session
session_start();

// Include database connection
require_once 'includes/db.php';

// Initialize variables
$email = $password = "";
$errors = [];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($email)) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    }

    if (empty($password)) {
        $errors['password'] = "Password is required";
    }

    if (empty($errors)) {
        $query = "SELECT id, username, password, role FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $username, $hashed_password, $role);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;
                header("Location: index.php");
                exit();
            } else {
                $errors['password'] = "Incorrect password";
            }
        } else {
            $errors['email'] = "No user found with this email";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f2f2f2;
            display: flex;
            height: 100vh;
        }
        .container {
            display: flex;
            width: 100%;
            animation: fadeIn 1.2s ease;
        }
        .image-section {
            flex: 1;
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('./img/login-img.png') no-repeat center center/cover;
            animation: slideInLeft 1s ease;
        }
        .form-section {
            flex: 1;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            animation: slideInRight 1s ease;
        }
        .form-box {
            width: 100%;
            max-width: 400px;
        }
        .form-box h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .form-box input {
            width: 100%;
            padding: 12px;
            margin: 8px 0 4px;
            border: 1px solid #ccc;
            border-radius: 6px;
            transition: border-color 0.3s ease;
            outline: none;
        }
        .form-box input.success { border-color: green; }
        .form-box input.error { border-color: red; }
        .form-box .message {
            font-size: 0.85em;
            margin-bottom: 10px;
            color: red;
            display: none;
        }
        .form-box .message.visible { display: block; }
        .form-box button {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            background: #4e49e5;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s ease;
        }
        .form-box button:hover {
            background: #6763fd;
        }
        .login-link {
            text-align: center;
            margin-top: 10px;
        }
        .login-link a {
            color: #6763fd;
            text-decoration: none;
        }
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
        @keyframes slideInLeft {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(0); }
        }
        @keyframes slideInRight {
            0% { transform: translateX(100%); }
            100% { transform: translateX(0); }
        }

@media (max-width: 768px) {
    body {
        height: 100vh; /* Full screen height */
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0;
    }
    .container {
        flex-direction: column;
        align-items: center;
        width: 100%;
        padding: 0;
    }
    .image-section {
        display: none; /* Hide side image */
    }
    .form-section {
        width: 90%;
        max-width: 400px;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .form-box h2 {
        font-size: 1.5rem;
        text-align: center; /* Center heading */
    }
}

    </style>
</head>
<body>
<div class="container">
    <div class="image-section"></div>
    <div class="form-section">
        <div class="form-box">
            <h2>Login</h2>
            <form method="POST" action="" novalidate>
                <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email) ?>" id="email">
                <div class="message <?= isset($errors['email']) ? 'visible' : '' ?>" id="email-msg">
                    <?= $errors['email'] ?? '' ?>
                </div>

                <input type="password" name="password" placeholder="Password" id="password">
                <div class="message <?= isset($errors['password']) ? 'visible' : '' ?>" id="password-msg">
                    <?= $errors['password'] ?? '' ?>
                </div>

                <button type="submit">Login</button>
            </form>
            <div class="login-link">
                Don't have an account? <a href="register.php">Register here</a>
            </div>
        </div>
    </div>
</div>
<script>
    const fields = ["email", "password"];

    fields.forEach(id => {
        const input = document.getElementById(id);
        const msg = document.getElementById(id + '-msg');

        const validate = () => {
            let value = input.value.trim();
            let error = "";

            if (value === "") {
                error = `${id} is required`;
            } else if (id === "email" && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                error = "Invalid email format";
            }

            if (error) {
                input.classList.remove("success");
                input.classList.add("error");
                msg.textContent = error;
                msg.classList.add("visible");
            } else {
                input.classList.remove("error");
                input.classList.add("success");
                msg.textContent = "";
                msg.classList.remove("visible");
            }
        }

        input.addEventListener("input", validate);
        input.addEventListener("blur", validate);
    });
</script>
</body>
</html>
