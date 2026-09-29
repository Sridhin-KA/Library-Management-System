<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../../login.php");
    exit();
}

require_once "../../config/db.php";

$id = $_GET['id'];

$sql = "SELECT * FROM students WHERE id = $id";

$result = mysqli_query($conn, $sql);

$student = mysqli_fetch_assoc($result);


if (isset($_POST['update_student'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $department = $_POST['department'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "UPDATE students SET
            name = '$name',
            email = '$email',
            phone = '$phone',
            username = '$username',
            password = '$password',
            department = '$department'
            WHERE id = $id";

    mysqli_query($conn, $sql);

    header("Location: view.php");
    exit();
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Edit Student</title>

</head>

<body>

    <h1>Edit Student</h1>

    <form method="POST">

        <label>Name</label>
        <br>

        <input
            type="text"
            name="name"
            value="<?php echo $student['name']; ?>"
            required
        >

        <br><br>

        <label>Email</label>
        <br>

        <input
            type="email"
            name="email"
            value="<?php echo $student['email']; ?>"
        >

        <br><br>

        <label>Phone</label>
        <br>

        <input
            type="text"
            name="phone"
            value="<?php echo $student['phone']; ?>"
        >

        <br><br>
        <label>Username</label>
        <br>

        <input
            type="text"
            name="username"
            value="<?php echo $student['username']; ?>"
        >

        <br><br>
        <label>Password</label>
        <br>

        <input
            type="password"
            name="password"
            value="<?php echo $student['password']; ?>"
        >

        <br><br>

        <label>Department</label>
        <br>

        <input
            type="text"
            name="department"
            value="<?php echo $student['department']; ?>"
        >

        <br><br>

        <button type="submit" name="update_student">
            Update Student
        </button>

    </form>

    <br>

    <a href="view.php">
        Back
    </a>

</body>

</html>