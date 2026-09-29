<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../../login.php");
    exit();
}

require_once "../../config/db.php";

if (isset($_POST['add_student'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $department = $_POST['department'];

    $sql = "INSERT INTO students
            (name, email, phone, department,username,password)
            VALUES
            ('$name', '$email', '$phone', '$department','$username','$password')";

    if (mysqli_query($conn, $sql)) {

        header("Location: view.php");
        exit();

    } else {

        echo "Error: " . mysqli_error($conn);

    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Student</title>
</head>

<body>

    <h1>Add Student</h1>

    <form method="POST">

        <label>Name</label>
        <br>

        <input type="text" name="name" required>

        <br><br>

        <label>Email</label>
        <br>

        <input type="email" name="email">

        <br><br>

        <label>Phone</label>
        <br>

        <input type="text" name="phone">

        <br><br>
        <label>Username</label>
        <br>

        <input type="text" name="username">

        <br><br>
        <label>Password</label>
        <br>

        <input type="text" name="password">

        <br><br>

        <label>Department</label>
        <br>

        <input type="text" name="department">

        <br><br>

        <button type="submit" name="add_student">
            Add Student
        </button>

    </form>

    <br>

    <a href="view.php">
        Back
    </a>

</body>

</html>