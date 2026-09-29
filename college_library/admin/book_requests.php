<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}

include("../config/db.php");

$message = "";


// Approve request
if (isset($_POST['approve'])) {

    $request_id = $_POST['request_id'];

    // Get request details
    $sql = "SELECT * FROM book_requests
            WHERE id = $request_id";

    $result = mysqli_query($conn, $sql);
    $request = mysqli_fetch_assoc($result);

    if ($request && $request['status'] == 'Pending') {

        $student_id = $request['student_id'];
        $book_id = $request['book_id'];

        // Check availability
        $book_sql = "SELECT available_quantity
                     FROM books
                     WHERE id = $book_id";

        $book_result = mysqli_query($conn, $book_sql);
        $book = mysqli_fetch_assoc($book_result);

        if ($book['available_quantity'] > 0) {

            // Set due date = 7 days from today
            $issue_date = date('Y-m-d');
            $due_date = date('Y-m-d', strtotime('+7 days'));

            // Create issue
            $issue_sql = "INSERT INTO issue_books
                          (student_id, book_id, issue_date, due_date)
                          VALUES
                          ($student_id, $book_id,
                           '$issue_date', '$due_date')";

            if (mysqli_query($conn, $issue_sql)) {

                // Reduce available quantity
                $update_sql = "UPDATE books
                               SET available_quantity =
                               available_quantity - 1
                               WHERE id = $book_id";

                mysqli_query($conn, $update_sql);

                // Update request status
                $request_sql = "UPDATE book_requests
                                SET status = 'Approved'
                                WHERE id = $request_id";

                mysqli_query($conn, $request_sql);

                $message = "Book request approved and book issued.";

            }

        } else {

            $message = "Book is no longer available.";
        }

    }
}


// Reject request
if (isset($_POST['reject'])) {

    $request_id = $_POST['request_id'];

    $sql = "UPDATE book_requests
            SET status = 'Rejected'
            WHERE id = $request_id";

    if (mysqli_query($conn, $sql)) {
        $message = "Book request rejected.";
    }
}


// Get pending requests
$sql = "SELECT
            book_requests.id,
            students.name AS student_name,
            books.title AS book_title,
            book_requests.request_date,
            book_requests.status
        FROM book_requests

        JOIN students
            ON book_requests.student_id = students.id

        JOIN books
            ON book_requests.book_id = books.id

        ORDER BY book_requests.id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Book Requests</title>
</head>

<body>

<h2>Book Requests</h2>

<?php if ($message != "") { ?>

    <p><?php echo $message; ?></p>

<?php } ?>

<table border="1" cellpadding="10">

    <tr>
        <th>ID</th>
        <th>Student</th>
        <th>Book</th>
        <th>Request Date</th>
        <th>Status</th>
        <th>Action</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>

    <tr>

        <td><?php echo $row['id']; ?></td>

        <td><?php echo $row['student_name']; ?></td>

        <td><?php echo $row['book_title']; ?></td>

        <td><?php echo $row['request_date']; ?></td>

        <td><?php echo $row['status']; ?></td>

        <td>

            <?php if ($row['status'] == 'Pending') { ?>

                <form method="POST" style="display:inline;">

                    <input type="hidden"
                           name="request_id"
                           value="<?php echo $row['id']; ?>">

                    <button type="submit"
                            name="approve">
                        Approve
                    </button>

                </form>

                <form method="POST" style="display:inline;">

                    <input type="hidden"
                           name="request_id"
                           value="<?php echo $row['id']; ?>">

                    <button type="submit"
                            name="reject">
                        Reject
                    </button>

                </form>

            <?php } else { ?>

                No Action

            <?php } ?>

        </td>

    </tr>

    <?php } ?>

</table>

<br>

<a href="dashboard.php">Back to Dashboard</a>

</body>

</html>