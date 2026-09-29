<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

include("../config/db.php");

$student_id = $_SESSION['student_id'];

$sql = "SELECT
            issue_books.id AS issue_id,
            books.title,
            issue_books.issue_date,
            issue_books.due_date,
            return_books.return_date,
            fines.amount,
            fines.status AS fine_status
        FROM issue_books

        JOIN books
            ON issue_books.book_id = books.id

        LEFT JOIN return_books
            ON issue_books.id = return_books.issue_id

        LEFT JOIN fines
            ON return_books.id = fines.return_id

        WHERE issue_books.student_id = $student_id

        ORDER BY issue_books.id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Books</title>
</head>

<body>

<h2>My Books</h2>

<p>
    Welcome, <?php echo $_SESSION['student_name']; ?>
</p>

<table border="1" cellpadding="10">

    <tr>
        <th>Book</th>
        <th>Issue Date</th>
        <th>Due Date</th>
        <th>Return Date</th>
        <th>Fine</th>
        <th>Fine Status</th>
        <th>Book Status</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>

    <tr>

        <td>
            <?php echo $row['title']; ?>
        </td>

        <td>
            <?php echo $row['issue_date']; ?>
        </td>

        <td>
            <?php echo $row['due_date']; ?>
        </td>

        <td>
            <?php
            if ($row['return_date'] != NULL) {
                echo $row['return_date'];
            } else {
                echo "-";
            }
            ?>
        </td>

        <td>
            <?php
            if ($row['amount'] != NULL) {
                echo "₹" . $row['amount'];
            } else {
                echo "₹0";
            }
            ?>
        </td>

        <td>
            <?php
            if ($row['fine_status'] != NULL) {
                echo $row['fine_status'];
            } else {
                echo "No Fine";
            }
            ?>
        </td>

        <td>

            <?php
            if ($row['return_date'] != NULL) {
                echo "Returned";
            } else {
                echo "Issued";
            }
            ?>

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