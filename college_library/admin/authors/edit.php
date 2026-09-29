<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../../login.php");
    exit();
}

require_once "../../config/db.php";

$id = $_GET['id'];

$sql = "SELECT * FROM authors WHERE id = $id";

$result = mysqli_query($conn, $sql);

$author = mysqli_fetch_assoc($result);


if (isset($_POST['update_author'])) {

    $name = $_POST['name'];

    $sql = "UPDATE authors
            SET name = '$name'
            WHERE id = $id";

    mysqli_query($conn, $sql);

    header("Location: view.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Author</title>
</head>

<body>

<h1>Edit Author</h1>

<form method="POST">

    <label>Author Name</label>

    <br>

    <input
        type="text"
        name="name"
        value="<?php echo $author['name']; ?>"
        required
    >

    <br><br>

    <button type="submit" name="update_author">
        Update Author
    </button>

</form>

<br>

<a href="view.php">Back</a>

</body>
</html>