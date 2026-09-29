<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

include("../config/db.php");

$student_id = $_SESSION['student_id'];
$message = "";

// Request a book
if (isset($_POST['request_book'])) {

    $book_id = $_POST['book_id'];

    // Check whether this student already has a pending request
    $check = "SELECT * FROM book_requests
              WHERE student_id = $student_id
              AND book_id = $book_id
              AND status = 'Pending'";

    $check_result = mysqli_query($conn, $check);

    if (mysqli_num_rows($check_result) > 0) {

        $message = "You already requested this book.";

    } else {

        // Check book availability
        $book_check = "SELECT available_quantity
                       FROM books
                       WHERE id = $book_id";

        $book_result = mysqli_query($conn, $book_check);
        $book = mysqli_fetch_assoc($book_result);

        if ($book['available_quantity'] > 0) {

            $sql = "INSERT INTO book_requests
                    (student_id, book_id, request_date, status)
                    VALUES
                    ($student_id, $book_id, CURDATE(), 'Pending')";

            if (mysqli_query($conn, $sql)) {
                $message = "Book request sent successfully.";
            }

        } else {

            $message = "This book is currently unavailable.";
        }
    }
}


// Get books
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

<?php if ($message != "") { ?>

    <p><?php echo $message; ?></p>

<?php } ?>

<table border="1" cellpadding="10">

    <tr>
        <th>ID</th>
        <th>Book</th>
        <th>Author</th>
        <th>Publisher</th>
        <th>Total</th>
        <th>Available</th>
        <th>Action</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>

    <tr>

        <td>
            <?php echo $row['id']; ?>
        </td>

        <td>
            <?php echo $row['title']; ?>
        </td>

        <td>
            <?php echo $row['author_name']; ?>
        </td>

        <td>
            <?php echo $row['publisher_name']; ?>
        </td>

        <td>
            <?php echo $row['quantity']; ?>
        </td>

        <td>
            <?php echo $row['available_quantity']; ?>
        </td>

        <td>

            <?php if ($row['available_quantity'] > 0) { ?>

                <form method="POST">

                    <input type="hidden"
                           name="book_id"
                           value="<?php echo $row['id']; ?>">

                    <button type="submit"
                            name="request_book">
                        Request Book
                    </button>

                </form>

            <?php } else { ?>

                Not Available

            <?php } ?>

        </td>

    </tr>

    <?php } ?>

</table>

<br>

<a href="dashboard.php">Back to Dashboard</a>

</body>
</html>