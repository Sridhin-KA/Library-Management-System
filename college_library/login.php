<?php

session_start();

require_once "config/db.php";

// Ensure the database connection is available from the included config.
$conn = $conn ?? ($mysqli ?? null);

$error = "";

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {

        $_SESSION['admin'] = $username;

        header("Location: admin/dashboard.php");
        exit();

    } else {

        $error = "Invalid username or password";

    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Admin Login | College Library</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            min-height: 100vh;
            background: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .login-card {
            background: #111827;
            border: 1px solid #263244;
            border-radius: 18px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.35);
        }

        .logo {
            width: 60px;
            height: 60px;
            margin: 0 auto 20px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #f59e0b;
            color: #111827;

            border-radius: 15px;

            font-size: 28px;
        }

        .heading {
            text-align: center;
            margin-bottom: 30px;
        }

        .heading h2 {
            font-size: 26px;
            margin-bottom: 8px;
        }

        .heading p {
            color: #94a3b8;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: #cbd5e1;
        }

        .form-group input {
            width: 100%;
            padding: 13px 14px;

            background: #0f172a;
            color: white;

            border: 1px solid #334155;
            border-radius: 8px;

            outline: none;
            font-size: 15px;

            transition: 0.3s;
        }

        .form-group input:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
        }

        .error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;

            padding: 12px;
            border-radius: 8px;

            margin-bottom: 20px;

            font-size: 14px;
            text-align: center;
        }

        .login-btn {
            width: 100%;
            padding: 13px;

            border: none;
            border-radius: 8px;

            background: #f59e0b;
            color: #111827;

            font-size: 15px;
            font-weight: bold;

            cursor: pointer;

            transition: 0.3s;
        }

        .login-btn:hover {
            background: #fbbf24;
            transform: translateY(-1px);
        }

        .back {
            text-align: center;
            margin-top: 25px;
        }

        .back a {
            color: #94a3b8;
            text-decoration: none;
            font-size: 14px;
        }

        .back a:hover {
            color: #f59e0b;
        }

        .admin-label {
            display: inline-block;

            margin-bottom: 25px;

            padding: 6px 12px;

            border-radius: 20px;

            background: rgba(245, 158, 11, 0.1);
            color: #fbbf24;

            font-size: 12px;
            font-weight: bold;
        }

    </style>

</head>

<body>

<div class="login-wrapper">

    <div class="login-card">

        <div class="logo">
            📚
        </div>

        <div class="heading">

            <span class="admin-label">
                ADMIN PORTAL
            </span>

            <h2>Welcome Back</h2>

            <p>
                Sign in to manage the college library
            </p>

        </div>


        <?php if ($error != "") { ?>

            <div class="error">
                <?php echo $error; ?>
            </div>

        <?php } ?>


        <form method="POST">

            <div class="form-group">

                <label>
                    Username
                </label>

                <input
                    type="text"
                    name="username"
                    placeholder="Enter admin username"
                    required
                >

            </div>


            <div class="form-group">

                <label>
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    placeholder="Enter your password"
                    required
                >

            </div>


            <button
                type="submit"
                name="login"
                class="login-btn"
            >
                Sign In
            </button>

        </form>


        <div class="back">

            <a href="index.php">
                ← Back to Library
            </a>

        </div>

    </div>

</div>

</body>

</html>