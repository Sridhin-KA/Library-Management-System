<?php

session_start();

require_once "config/db.php";

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
<html>
<head>
    <title>Admin Login</title>
</head>

<body>

    <h2>College Library</h2>

    <h3>Admin Login</h3>

    <?php if ($error != "") { ?>
        <p><?php echo $error; ?></p>
    <?php } ?>

    <form method="POST">

        <label>Username</label>
        <br>
        <input type="text" name="username" required>

        <br><br>

        <label>Password</label>
        <br>
        <input type="password" name="password" required>

        <br><br>

        <button type="submit" name="login">
            Login
        </button>

    </form>

</body>
</html>