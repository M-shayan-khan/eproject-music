<?php
// Start session
session_start();

// Include database connection
require_once 'includes/db.php';

// Initialize variables
$username = $email = $password = $confirm_password = "";
$errors = [];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Validate fields
    if (empty($username))
        $errors['username'] = "Username is required";
    if (empty($email)) {
        $errors['email'] = "Email is required";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    }
    if (empty($password)) {
        $errors['password'] = "Password is required";
    } else if (strlen($password) < 6) {
        $errors['password'] = "Password must be at least 6 characters long";
    }
    if ($password !== $confirm_password)
        $errors['confirm_password'] = "Passwords do not match";

    // Check if email already exists
    if (empty($errors)) {
        $checkQuery = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors['email'] = "Email already registered";
        }
        $stmt->close();
    }

    // Insert new user
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $role = "user";

        $insertQuery = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);
        if ($stmt->execute()) {
            $_SESSION["success"] = "Registration successful! Please log in.";
            header("Location: login.php");
            exit();
        } else {
            $errors['form'] = "Something went wrong. Please try again.";
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
    <title>Register</title>
    <style>
        * {
            box-sizing: border-box;
        }

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
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('./img/signup-img.png') no-repeat center center/cover;
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

        .form-box input.success {
            border-color: green;
        }

        .form-box input.error {
            border-color: red;
        }

        .form-box .message {
            font-size: 0.85em;
            margin-bottom: 10px;
            color: red;
            display: none;
        }

        .form-box .message.visible {
            display: block;
        }

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
            0% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        @keyframes slideInLeft {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            0% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(0);
            }
        }

        /* Responsive design for register.php */

/* Tablets and small laptops */
@media (max-width: 992px) {
    .image-section {
        display: none;
    }
    .form-section {
        flex: 1;
        justify-content: center;
        padding: 40px 20px;
    }
    .form-box {
        max-width: 500px;
        margin: auto;
    }
    .form-box h2 {
        text-align: center;
    }
}

/* Mobile devices */
@media (max-width: 768px) {
    body {
        padding: 0;
    }
    .container {
        flex-direction: column;
        height: auto;
        min-height: 100vh;
    }
    .form-section {
        width: 100%;
        padding: 30px 15px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .form-box {
        width: 70%;
        max-width: 100%;
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0px 0px 15px rgba(0,0,0,0.1);
    }
    .form-box h2 {
        text-align: center;
        margin-bottom: 20px;
    }
}

/* Extra small devices */
@media (max-width: 480px) {
    .form-box {
        padding: 15px;
    }
    .form-box input, 
    .form-box button {
        font-size: 14px;
        padding: 10px;
    }
}

    </style>
</head>

<body>
    <div class="container">
        <div class="image-section"></div>
        <div class="form-section">
            <div class="form-box">
                <h2>Create Account</h2>
                <?php if (!empty($errors['form']))
                    echo '<div class="message visible">' . $errors['form'] . '</div>'; ?>
                <form method="POST" action="" novalidate>
                    <input type="text" name="username" placeholder="Username" value="<?= htmlspecialchars($username) ?>"
                        id="username">
                    <div class="message <?= isset($errors['username']) ? 'visible' : '' ?>" id="username-msg">
                        <?= $errors['username'] ?? '' ?>
                    </div>

                    <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email) ?>"
                        id="email">
                    <div class="message <?= isset($errors['email']) ? 'visible' : '' ?>" id="email-msg">
                        <?= $errors['email'] ?? '' ?>
                    </div>

                    <input type="password" name="password" placeholder="Password" id="password">
                    <div class="message <?= isset($errors['password']) ? 'visible' : '' ?>" id="password-msg">
                        <?= $errors['password'] ?? '' ?>
                    </div>

                    <input type="password" name="confirm_password" placeholder="Confirm Password" id="confirm_password">
                    <div class="message <?= isset($errors['confirm_password']) ? 'visible' : '' ?>" id="confirm-msg">
                        <?= $errors['confirm_password'] ?? '' ?>
                    </div>

                    <button type="submit">Register</button>
                </form>
                <div class="login-link">
                    Already have an account? <a href="login.php">Login here</a>
                </div>
            </div>
        </div>
    </div>
    <script>
        const fields = ["username", "email", "password", "confirm_password"];

        fields.forEach(id => {
            const input = document.getElementById(id);
            const msg = document.getElementById(id + '-msg');

            const validate = () => {
                let value = input.value.trim();
                let error = "";

                if (value === "") {
                    error = `${id.replace('_', ' ')} is required`;
                } else if (id === "email" && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                    error = "Invalid email format";
                } else if (id === "password" && value.length < 6) {
                    error = "Password must be at least 6 characters";
                } else if (id === "confirm_password" && value !== document.getElementById("password").value) {
                    error = "Passwords do not match";
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