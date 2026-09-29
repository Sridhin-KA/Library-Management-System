<?php
include("../config/db.php");

$message = "";

if (isset($_POST['register'])) {

    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check username already exists
    $check = "SELECT * FROM students WHERE username = '$username'";
    $result = mysqli_query($conn, $check);

    if (mysqli_num_rows($result) > 0) {

        $message = "Username already exists.";

    } else {

        $sql = "INSERT INTO students (name, username, password)
                VALUES ('$name', '$username', '$password')";

        if (mysqli_query($conn, $sql)) {

            $message = "Registration successful. You can login now.";

        } else {

            $message = "Registration failed.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Registration</title>
</head>

<body>

<h2>Student Registration</h2>

<?php if ($message != "") { ?>
    <p><?php echo $message; ?></p>
<?php } ?>

<form method="POST">

    <label>Name</label><br>
    <input type="text" name="name" required>

    <br><br>

    <label>Username</label><br>
    <input type="text" name="username" required>

    <br><br>

    <label>Password</label><br>
    <input type="password" name="password" required>

    <br><br>

    <button type="submit" name="register">
        Register
    </button>

</form>

<br>

<a href="login.php">Already have an account? Login</a>

</body>
</html>