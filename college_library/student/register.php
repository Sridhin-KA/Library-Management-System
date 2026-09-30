<?php

include("../config/db.php");

$message = "";
$message_type = "";

if (isset($_POST['register'])) {

    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check username already exists

    $check = "SELECT * FROM students WHERE username = '$username'";

    $result = mysqli_query($conn, $check);

    if (mysqli_num_rows($result) > 0) {

        $message = "Username already exists.";
        $message_type = "error";

    } else {

        $sql = "INSERT INTO students (name, username, password)
                VALUES ('$name', '$username', '$password')";

        if (mysqli_query($conn, $sql)) {

            $message = "Registration successful. You can login now.";
            $message_type = "success";

        } else {

            $message = "Registration failed.";
            $message_type = "error";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Registration | College Library</title>

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

        .register-container {
            width: 100%;
            max-width: 450px;
        }

        /* Logo */

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

        /* Card */

        .register-card {
            background: #111827;
            border: 1px solid #243044;
            border-radius: 18px;
            padding: 35px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.35);
        }

        .register-card h2 {
            font-size: 24px;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #94a3b8;
            font-size: 14px;
            margin-bottom: 28px;
        }

        /* Form */

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

        /* Messages */

        .message {
            padding: 12px 14px;
            border-radius: 9px;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .success {
            background: #052e16;
            border: 1px solid #166534;
            color: #86efac;
        }

        .error {
            background: #3b1720;
            border: 1px solid #7f1d2d;
            color: #fda4af;
        }

        /* Button */

        .register-btn {
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

        .register-btn:hover {
            background: #ffd166;
            transform: translateY(-1px);
        }

        /* Login */

        .login-text {
            text-align: center;
            margin-top: 24px;
            color: #94a3b8;
            font-size: 14px;
        }

        .login-text a {
            color: #f5b942;
            text-decoration: none;
            font-weight: 600;
        }

        .login-text a:hover {
            text-decoration: underline;
        }

        /* Back */

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

        /* Mobile */

        @media (max-width: 480px) {

            .register-card {
                padding: 25px 20px;
            }

            .logo h1 {
                font-size: 22px;
            }

        }

    </style>

</head>

<body>

<div class="register-container">

    <!-- Logo -->

    <div class="logo">

        <div class="logo-icon">
            L
        </div>

        <h1>College Library</h1>

        <p>Student Portal</p>

    </div>


    <!-- Registration Card -->

    <div class="register-card">

        <h2>Create Account</h2>

        <p class="subtitle">
            Register to access the college library.
        </p>


        <?php if ($message != "") { ?>

            <div class="message <?php echo $message_type; ?>">

                <?php echo $message; ?>

            </div>

        <?php } ?>


        <form method="POST">

            <div class="form-group">

                <label>Name</label>

                <input
                    type="text"
                    name="name"
                    placeholder="Enter your full name"
                    required
                >

            </div>


            <div class="form-group">

                <label>Username</label>

                <input
                    type="text"
                    name="username"
                    placeholder="Choose a username"
                    required
                >

            </div>


            <div class="form-group">

                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    placeholder="Create a password"
                    required
                >

            </div>


            <button
                type="submit"
                name="register"
                class="register-btn"
            >
                Create Account
            </button>

        </form>


        <div class="login-text">

            Already have an account?

            <a href="login.php">
                Login
            </a>

        </div>

    </div>


    <a href="../index.php" class="back-link">
        ← Back to Library Home
    </a>

</div>

</body>

</html>