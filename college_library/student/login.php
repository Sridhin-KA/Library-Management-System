<?php
session_start();

include("../config/db.php");

$message = "";

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM students
            WHERE username = '$username'
            AND password = '$password'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {

        $student = mysqli_fetch_assoc($result);

        $_SESSION['student_id'] = $student['id'];
        $_SESSION['student_name'] = $student['name'];

        header("Location: dashboard.php");
        exit();

    } else {

        $message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Login | College Library</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            background: #0b1120;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #ffffff;
        }

        .login-container {
            width: 100%;
            max-width: 430px;
        }

        .logo {
            text-align: center;
            margin-bottom: 25px;
        }

        .logo-icon {
            width: 58px;
            height: 58px;
            margin: 0 auto 12px;
            border-radius: 16px;
            background: #f5b942;
            color: #0b1120;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            font-weight: bold;
        }

        .logo h1 {
            font-size: 25px;
            margin-bottom: 5px;
        }

        .logo p {
            color: #94a3b8;
            font-size: 14px;
        }

        .login-card {
            background: #111827;
            border: 1px solid #243044;
            border-radius: 18px;
            padding: 35px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.35);
        }

        .login-card h2 {
            font-size: 24px;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #94a3b8;
            font-size: 14px;
            margin-bottom: 28px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #dbe4f0;
            font-size: 14px;
            font-weight: 600;
        }

        input {
            width: 100%;
            padding: 13px 14px;
            border: 1px solid #334155;
            border-radius: 9px;
            background: #0f172a;
            color: #ffffff;
            font-size: 14px;
            outline: none;
            transition: 0.2s;
        }

        input:focus {
            border-color: #f5b942;
            box-shadow: 0 0 0 3px rgba(245, 185, 66, 0.1);
        }

        input::placeholder {
            color: #64748b;
        }

        .error {
            background: #3b1720;
            border: 1px solid #7f1d2d;
            color: #fda4af;
            padding: 12px 14px;
            border-radius: 9px;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .login-btn {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 9px;
            background: #f5b942;
            color: #0b1120;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
        }

        .login-btn:hover {
            background: #ffd166;
            transform: translateY(-1px);
        }

        .register-text {
            text-align: center;
            margin-top: 24px;
            color: #94a3b8;
            font-size: 14px;
        }

        .register-text a {
            color: #f5b942;
            text-decoration: none;
            font-weight: 600;
        }

        .register-text a:hover {
            text-decoration: underline;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 22px;
            color: #64748b;
            text-decoration: none;
            font-size: 13px;
        }

        .back-link:hover {
            color: #f5b942;
        }

        @media (max-width: 480px) {

            .login-card {
                padding: 25px 20px;
            }

            .logo h1 {
                font-size: 22px;
            }

        }

    </style>

</head>

<body>

<div class="login-container">

    <div class="logo">

        <div class="logo-icon">
            L
        </div>

        <h1>College Library</h1>

        <p>Student Portal</p>

    </div>


    <div class="login-card">

        <h2>Welcome Back</h2>

        <p class="subtitle">
            Login to access your library account.
        </p>


        <?php if ($message != "") { ?>

            <div class="error">
                <?php echo $message; ?>
            </div>

        <?php } ?>


        <form method="POST">

            <div class="form-group">

                <label>Username</label>

                <input
                    type="text"
                    name="username"
                    placeholder="Enter your username"
                    required
                >

            </div>


            <div class="form-group">

                <label>Password</label>

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
                Login
            </button>

        </form>


        <div class="register-text">

            Don't have an account?

            <a href="register.php">
                Create Account
            </a>

        </div>

    </div>


    <a href="../index.php" class="back-link">
        ← Back to Library Home
    </a>

</div>

</body>
</html>