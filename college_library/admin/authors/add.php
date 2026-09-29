<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../../login.php");
    exit();
}

require_once "../../config/db.php";

if (isset($_POST['add_author'])) {

    $name = $_POST['name'];

    $sql = "INSERT INTO authors (name)
            VALUES ('$name')";

    mysqli_query($conn, $sql);

    header("Location: view.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Author</title>
</head>

<body>

<h1>Add Author</h1>

<form method="POST">

    <label>Author Name</label>

    <br>

    <input
        type="text"
        name="name"
        required
    >

    <br><br>

    <button type="submit" name="add_author">
        Add Author
    </button>

</form>

<br>

<a href="view.php">Back</a>

</body>
</html>