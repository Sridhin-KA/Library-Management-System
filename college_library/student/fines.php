<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

include("../config/db.php");

$student_id = $_SESSION['student_id'];

$sql = "SELECT
            fines.id AS fine_id,
            books.title AS book_title,
            return_books.return_date,
            fines.amount,
            fines.status
        FROM fines

        JOIN return_books
            ON fines.return_id = return_books.id

        JOIN issue_books
            ON return_books.issue_id = issue_books.id

        JOIN books
            ON issue_books.book_id = books.id

        WHERE issue_books.student_id = $student_id

        ORDER BY fines.id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Fines</title>
</head>

<body>

<h2>My Fines</h2>

<p>
    Welcome, <?php echo $_SESSION['student_name']; ?>
</p>

<table border="1" cellpadding="10">

    <tr>
        <th>Fine ID</th>
        <th>Book</th>
        <th>Return Date</th>
        <th>Fine Amount</th>
        <th>Status</th>
    </tr>

    <?php if (mysqli_num_rows($result) > 0) { ?>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>

        <tr>

            <td>
                <?php echo $row['fine_id']; ?>
            </td>

            <td>
                <?php echo $row['book_title']; ?>
            </td>

            <td>
                <?php echo $row['return_date']; ?>
            </td>

            <td>
                ₹<?php echo $row['amount']; ?>
            </td>

            <td>
                <?php echo $row['status']; ?>
            </td>

        </tr>

        <?php } ?>

    <?php } else { ?>

        <tr>
            <td colspan="5">
                You don't have any fines.
            </td>
        </tr>

    <?php } ?>

</table>

<br>

<a href="my_books.php">My Books</a>

<br><br>

<a href="dashboard.php">Back to Dashboard</a>

</body>
</html>