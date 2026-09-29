<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}

include("../config/db.php");

$message = "";

// Mark fine as paid
if (isset($_POST['pay_fine'])) {

    $fine_id = $_POST['fine_id'];

    $sql = "UPDATE fines
            SET status = 'Paid'
            WHERE id = $fine_id";

    if (mysqli_query($conn, $sql)) {
        $message = "Fine marked as paid.";
    }
}


// Get all fines
$sql = "SELECT
            fines.id,
            students.name AS student_name,
            books.title AS book_title,
            return_books.return_date,
            fines.amount,
            fines.status
        FROM fines
        JOIN return_books
            ON fines.return_id = return_books.id
        JOIN issue_books
            ON return_books.issue_id = issue_books.id
        JOIN students
            ON issue_books.student_id = students.id
        JOIN books
            ON issue_books.book_id = books.id
        ORDER BY fines.id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Fines</title>
</head>

<body>

<h2>Fines Management</h2>

<?php if ($message != "") { ?>

    <p><?php echo $message; ?></p>

<?php } ?>

<table border="1" cellpadding="10">

    <tr>
        <th>Fine ID</th>
        <th>Student</th>
        <th>Book</th>
        <th>Return Date</th>
        <th>Fine Amount</th>
        <th>Status</th>
        <th>Action</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>

    <tr>

        <td>
            <?php echo $row['id']; ?>
        </td>

        <td>
            <?php echo $row['student_name']; ?>
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

        <td>

            <?php if ($row['status'] == 'Pending') { ?>

                <form method="POST">

                    <input type="hidden"
                           name="fine_id"
                           value="<?php echo $row['id']; ?>">

                    <button type="submit"
                            name="pay_fine">
                        Mark as Paid
                    </button>

                </form>

            <?php } else { ?>

                Paid

            <?php } ?>

        </td>

    </tr>

    <?php } ?>

</table>

<br>

<a href="dashboard.php">Back to Dashboard</a>

</body>
</html>