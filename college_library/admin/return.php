<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}

include("../config/db.php");

$message = "";

// Return book
if (isset($_POST['return_book'])) {

    $issue_id = $_POST['issue_id'];
    $return_date = $_POST['return_date'];

    // Get issue details
    $sql = "SELECT * FROM issue_books WHERE id = $issue_id";
    $result = mysqli_query($conn, $sql);
    $issue = mysqli_fetch_assoc($result);

    if ($issue) {

        $due_date = $issue['due_date'];
        $book_id = $issue['book_id'];

        // Calculate late days
        $due = new DateTime($due_date);
        $return = new DateTime($return_date);

        $difference = $due->diff($return);
        $late_days = $difference->days;

        // If returned before/on due date, no fine
        if ($return <= $due) {
            $late_days = 0;
        }

        // Fine = ₹10 per late day
        $fine = $late_days * 10;

        // Insert return record
        $sql = "INSERT INTO return_books (issue_id, return_date)
                VALUES ($issue_id, '$return_date')";

        if (mysqli_query($conn, $sql)) {

            $return_id = mysqli_insert_id($conn);

            // Add fine if late
            if ($fine > 0) {

                $fine_sql = "INSERT INTO fines (return_id, amount, status)
                             VALUES ($return_id, $fine, 'Pending')";

                mysqli_query($conn, $fine_sql);
            }

            // Increase available book quantity
            $update_book = "UPDATE books
                            SET available_quantity = available_quantity + 1
                            WHERE id = $book_id";

            mysqli_query($conn, $update_book);

            $message = "Book returned successfully.";

            if ($fine > 0) {
                $message .= " Fine: ₹" . $fine;
            }
        }
    }
}


// Get books which are currently issued
$sql = "SELECT 
            issue_books.id,
            students.name AS student_name,
            books.title,
            issue_books.issue_date,
            issue_books.due_date
        FROM issue_books
        JOIN students ON issue_books.student_id = students.id
        JOIN books ON issue_books.book_id = books.id
        LEFT JOIN return_books 
            ON issue_books.id = return_books.issue_id
        WHERE return_books.id IS NULL
        ORDER BY issue_books.id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Return Book</title>
</head>

<body>

<h2>Return Book</h2>

<?php
if ($message != "") {
    echo "<p>$message</p>";
}
?>

<table border="1" cellpadding="10">

    <tr>
        <th>ID</th>
        <th>Student</th>
        <th>Book</th>
        <th>Issue Date</th>
        <th>Due Date</th>
        <th>Return</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>

    <tr>

        <td><?php echo $row['id']; ?></td>

        <td><?php echo $row['student_name']; ?></td>

        <td><?php echo $row['title']; ?></td>

        <td><?php echo $row['issue_date']; ?></td>

        <td><?php echo $row['due_date']; ?></td>

        <td>

            <form method="POST">

                <input type="hidden"
                       name="issue_id"
                       value="<?php echo $row['id']; ?>">

                <input type="date"
                       name="return_date"
                       value="<?php echo date('Y-m-d'); ?>"
                       required>

                <button type="submit"
                        name="return_book">
                    Return
                </button>

            </form>

        </td>

    </tr>

    <?php } ?>

</table>

<br>

<a href="dashboard.php">Back to Dashboard</a>

</body>
</html>