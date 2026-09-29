<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

include("../config/db.php");

$student_id = $_SESSION['student_id'];

$sql = "SELECT
            book_requests.id,
            books.title AS book_title,
            authors.name AS author_name,
            book_requests.request_date,
            book_requests.status
        FROM book_requests

        JOIN books
            ON book_requests.book_id = books.id

        JOIN authors
            ON books.author_id = authors.id

        WHERE book_requests.student_id = $student_id

        ORDER BY book_requests.id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>My Requests</title>
</head>

<body>

<h2>My Book Requests</h2>

<p>
    Welcome, <?php echo $_SESSION['student_name']; ?>
</p>

<table border="1" cellpadding="10">

    <tr>
        <th>ID</th>
        <th>Book</th>
        <th>Author</th>
        <th>Request Date</th>
        <th>Status</th>
    </tr>

    <?php if (mysqli_num_rows($result) > 0) { ?>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>

        <tr>

            <td>
                <?php echo $row['id']; ?>
            </td>

            <td>
                <?php echo $row['book_title']; ?>
            </td>

            <td>
                <?php echo $row['author_name']; ?>
            </td>

            <td>
                <?php echo $row['request_date']; ?>
            </td>

            <td>
                <?php echo $row['status']; ?>
            </td>

        </tr>

        <?php } ?>

    <?php } else { ?>

        <tr>
            <td colspan="5">
                You have not requested any books.
            </td>
        </tr>

    <?php } ?>

</table>

<br>

<a href="books.php">Available Books</a>

<br><br>

<a href="dashboard.php">Back to Dashboard</a>

</body>

</html>