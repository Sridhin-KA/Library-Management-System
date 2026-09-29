<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../../login.php");
    exit();
}

require_once "../../config/db.php";

$sql = "SELECT books.*, 
               authors.name AS author_name,
               publishers.name AS publisher_name
        FROM books
        JOIN authors ON books.author_id = authors.id
        JOIN publishers ON books.publisher_id = publishers.id";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Books</title>

</head>

<body>

<h1>Books</h1>

<a href="add.php">Add Book</a>

<br><br>

<table border="1" cellpadding="10">

    <tr>

        <th>ID</th>
        <th>Title</th>
        <th>Author</th>
        <th>Publisher</th>
        <th>Quantity</th>
        <th>Available</th>
        <th>Action</th>

    </tr>

    <?php while ($book = mysqli_fetch_assoc($result)) { ?>

        <tr>

            <td>
                <?php echo $book['id']; ?>
            </td>

            <td>
                <?php echo $book['title']; ?>
            </td>

            <td>
                <?php echo $book['author_name']; ?>
            </td>

            <td>
                <?php echo $book['publisher_name']; ?>
            </td>

            <td>
                <?php echo $book['quantity']; ?>
            </td>

            <td>
                <?php echo $book['available_quantity']; ?>
            </td>

            <td>

                <a href="edit.php?id=<?php echo $book['id']; ?>">
                    Edit
                </a>

                |

                <a
                    href="delete.php?id=<?php echo $book['id']; ?>"
                    onclick="return confirm('Are you sure you want to delete this book?')"
                >
                    Delete
                </a>

            </td>

        </tr>

    <?php } ?>

</table>

<br>

<a href="../dashboard.php">
    Back to Dashboard
</a>

</body>

</html>