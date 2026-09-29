<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../../login.php");
    exit();
}

require_once "../../config/db.php";


/* Get authors */

$author_sql = "SELECT * FROM authors";

$author_result = mysqli_query($conn, $author_sql);


/* Get publishers */

$publisher_sql = "SELECT * FROM publishers";

$publisher_result = mysqli_query($conn, $publisher_sql);


/* Add book */

if (isset($_POST['add_book'])) {

    $title = $_POST['title'];
    $author_id = $_POST['author_id'];
    $publisher_id = $_POST['publisher_id'];
    $quantity = $_POST['quantity'];

    $available_quantity = $quantity;

    $sql = "INSERT INTO books
            (title, author_id, publisher_id, quantity, available_quantity)
            VALUES
            ('$title', $author_id, $publisher_id, $quantity, $available_quantity)";

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

    <title>Add Book</title>

</head>

<body>

<h1>Add Book</h1>

<form method="POST">

    <label>Book Title</label>

    <br>

    <input
        type="text"
        name="title"
        required
    >

    <br><br>


    <label>Author</label>

    <br>

    <select name="author_id" required>

        <option value="">
            Select Author
        </option>

        <?php while ($author = mysqli_fetch_assoc($author_result)) { ?>

            <option value="<?php echo $author['id']; ?>">

                <?php echo $author['name']; ?>

            </option>

        <?php } ?>

    </select>

    <br><br>


    <label>Publisher</label>

    <br>

    <select name="publisher_id" required>

        <option value="">
            Select Publisher
        </option>

        <?php while ($publisher = mysqli_fetch_assoc($publisher_result)) { ?>

            <option value="<?php echo $publisher['id']; ?>">

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
        required
    >

    <br><br>

    <button type="submit" name="add_book">
        Add Book
    </button>

</form>

<br>

<a href="view.php">
    Back
</a>

</body>

</html>