<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../../login.php");
    exit();
}

require_once "../../config/db.php";

$id = $_GET['id'];


/* Get current book */

$sql = "SELECT * FROM books WHERE id = $id";

$result = mysqli_query($conn, $sql);

$book = mysqli_fetch_assoc($result);


/* Get authors */

$author_sql = "SELECT * FROM authors";

$author_result = mysqli_query($conn, $author_sql);


/* Get publishers */

$publisher_sql = "SELECT * FROM publishers";

$publisher_result = mysqli_query($conn, $publisher_sql);


/* Update book */

if (isset($_POST['update_book'])) {

    $title = $_POST['title'];
    $author_id = $_POST['author_id'];
    $publisher_id = $_POST['publisher_id'];
    $quantity = $_POST['quantity'];

    $sql = "UPDATE books SET

            title = '$title',
            author_id = $author_id,
            publisher_id = $publisher_id,
            quantity = $quantity

            WHERE id = $id";

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

    <title>Edit Book</title>

</head>

<body>

<h1>Edit Book</h1>

<form method="POST">

    <label>Book Title</label>

    <br>

    <input
        type="text"
        name="title"
        value="<?php echo $book['title']; ?>"
        required
    >

    <br><br>


    <label>Author</label>

    <br>

    <select name="author_id" required>

        <?php while ($author = mysqli_fetch_assoc($author_result)) { ?>

            <option
                value="<?php echo $author['id']; ?>"
                <?php
                if ($author['id'] == $book['author_id']) {
                    echo "selected";
                }
                ?>
            >

                <?php echo $author['name']; ?>

            </option>

        <?php } ?>

    </select>

    <br><br>


    <label>Publisher</label>

    <br>

    <select name="publisher_id" required>

        <?php while ($publisher = mysqli_fetch_assoc($publisher_result)) { ?>

            <option
                value="<?php echo $publisher['id']; ?>"
                <?php
                if ($publisher['id'] == $book['publisher_id']) {
                    echo "selected";
                }
                ?>
            >

                <?php echo $publisher['name']; ?>

            </option>

        <?php } ?>

    </select>

    <br><br>


    <label>Quantity</label>

    <br>

    <input
        type="number"
        name="quantity"
        min="1"
        value="<?php echo $book['quantity']; ?>"
        required
    >

    <br><br>

    <button type="submit" name="update_book">
        Update Book
    </button>

</form>

<br>

<a href="view.php">
    Back
</a>

</body>

</html>