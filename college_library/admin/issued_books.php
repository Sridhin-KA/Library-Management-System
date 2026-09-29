<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}

require_once "../config/db.php";


$sql = "SELECT
            issue_books.*,
            students.name AS student_name,
            books.title AS book_title
        FROM issue_books
        JOIN students
            ON issue_books.student_id = students.id
        JOIN books
            ON issue_books.book_id = books.id
        ORDER BY issue_books.id DESC";


$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Issued Books</title>

</head>

<body>

<h1>Issued Books</h1>

<table border="1" cellpadding="10">

    <tr>

        <th>ID</th>
        <th>Student</th>
        <th>Book</th>
        <th>Issue Date</th>
        <th>Due Date</th>

    </tr>


    <?php while ($issue = mysqli_fetch_assoc($result)) { ?>

        <tr>

            <td>
                <?php echo $issue['id']; ?>
            </td>

            <td>
                <?php echo $issue['student_name']; ?>
            </td>

            <td>
                <?php echo $issue['book_title']; ?>
            </td>

            <td>
                <?php echo $issue['issue_date']; ?>
            </td>

            <td>
                <?php echo $issue['due_date']; ?>
            </td>

        </tr>

    <?php } ?>

</table>

<br>

<a href="dashboard.php">
    Back to Dashboard
</a>

</body>

</html>