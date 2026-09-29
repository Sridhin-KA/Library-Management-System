<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

include("../config/db.php");

$sql = "SELECT
            books.id,
            books.title,
            authors.name AS author_name,
            publishers.name AS publisher_name,
            books.quantity,
            books.available_quantity
        FROM books
        JOIN authors
            ON books.author_id = authors.id
        JOIN publishers
            ON books.publisher_id = publishers.id
        ORDER BY books.id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Available Books</title>
</head>

<body>

<h2>Available Books</h2>

<p>
    Welcome, <?php echo $_SESSION['student_name']; ?>
</p>

<table border="1" cellpadding="10">

    <tr>
        <th>ID</th>
        <th>Book Title</th>
        <th>Author</th>
        <th>Publisher</th>
        <th>Total Quantity</th>
        <th>Available</th>
        <th>Status</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>

    <tr>

        <td><?php echo $row['id']; ?></td>

        <td><?php echo $row['title']; ?></td>

        <td><?php echo $row['author_name']; ?></td>

        <td><?php echo $row['publisher_name']; ?></td>

        <td><?php echo $row['quantity']; ?></td>

        <td><?php echo $row['available_quantity']; ?></td>

        <td>

            <?php
            if ($row['available_quantity'] > 0) {
                echo "Available";
            } else {
                echo "Not Available";
            }
            ?>

        </td>

    </tr>

    <?php } ?>

</table>

<br>

<a href="dashboard.php">Back to Dashboard</a>

</body>
</html>