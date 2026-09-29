<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}

require_once "../config/db.php";


// Get students

$student_sql = "SELECT * FROM students";

$student_result = mysqli_query($conn, $student_sql);


// Get available books

$book_sql = "SELECT * FROM books
             WHERE available_quantity > 0";

$book_result = mysqli_query($conn, $book_sql);


// Issue book

if (isset($_POST['issue_book'])) {

    $student_id = $_POST['student_id'];
    $book_id = $_POST['book_id'];
    $issue_date = $_POST['issue_date'];
    $due_date = $_POST['due_date'];


    // Check book availability

    $check_sql = "SELECT available_quantity
                  FROM books
                  WHERE id = $book_id";

    $check_result = mysqli_query($conn, $check_sql);

    $book = mysqli_fetch_assoc($check_result);


    if ($book['available_quantity'] <= 0) {

        echo "Book is not available.";

    } else {

        // Insert issue record

        $sql = "INSERT INTO issue_books
                (student_id, book_id, issue_date, due_date)
                VALUES
                ($student_id, $book_id, '$issue_date', '$due_date')";

        if (mysqli_query($conn, $sql)) {

            // Decrease available quantity

            $update_sql = "UPDATE books
                           SET available_quantity =
                           available_quantity - 1
                           WHERE id = $book_id";

            mysqli_query($conn, $update_sql);

            header("Location: issue.php");
            exit();

        } else {

            echo "Error: " . mysqli_error($conn);

        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Issue Book</title>

</head>

<body>

<h1>Issue Book</h1>

<form method="POST">

    <label>Student</label>

    <br>

    <select name="student_id" required>

        <option value="">
            Select Student
        </option>

        <?php while ($student = mysqli_fetch_assoc($student_result)) { ?>

            <option value="<?php echo $student['id']; ?>">

                <?php echo $student['name']; ?>

            </option>

        <?php } ?>

    </select>

    <br><br>


    <label>Book</label>

    <br>

    <select name="book_id" required>

        <option value="">
            Select Book
        </option>

        <?php while ($book = mysqli_fetch_assoc($book_result)) { ?>

            <option value="<?php echo $book['id']; ?>">

                <?php echo $book['title']; ?>

                (Available:
                <?php echo $book['available_quantity']; ?>)

            </option>

        <?php } ?>

    </select>

    <br><br>


    <label>Issue Date</label>

    <br>

    <input
        type="date"
        name="issue_date"
        value="<?php echo date('Y-m-d'); ?>"
        required
    >

    <br><br>


    <label>Due Date</label>

    <br>

    <input
        type="date"
        name="due_date"
        required
    >

    <br><br>


    <button type="submit" name="issue_book">

        Issue Book

    </button>

</form>

<br>

<a href="dashboard.php">
    Back to Dashboard
</a>

</body>

</html>