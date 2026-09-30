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
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Available Books | College Library</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f8fafc;
            color: #0f172a;
        }

        /* Navbar */

        .navbar {
            height: 72px;
            background: #0f172a;
            color: white;

            padding: 0 6%;

            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;

            font-size: 20px;
            font-weight: bold;
        }

        .logo-icon {
            width: 38px;
            height: 38px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #f59e0b;
            color: #111827;

            border-radius: 9px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-links a {
            text-decoration: none;
            color: #cbd5e1;

            padding: 9px 14px;

            border-radius: 7px;

            font-size: 14px;

            transition: 0.3s;
        }

        .nav-links a:hover {
            background: #1e293b;
            color: white;
        }

        .nav-links .logout {
            border: 1px solid #334155;
        }


        /* Main */

        .container {
            width: 88%;
            max-width: 1200px;

            margin: 40px auto;
        }


        .page-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;

            gap: 20px;

            margin-bottom: 25px;
        }

        .page-header small {
            color: #f59e0b;

            font-size: 12px;
            font-weight: bold;

            letter-spacing: 1px;
        }

        .page-header h1 {
            margin-top: 6px;

            font-size: 30px;
        }

        .page-header p {
            color: #64748b;

            margin-top: 7px;

            font-size: 14px;
        }


        /* Message */

        .message {
            background: #fff7ed;

            border: 1px solid #fed7aa;

            color: #9a3412;

            padding: 13px 16px;

            border-radius: 8px;

            margin-bottom: 20px;
        }


        /* Book table */

        .table-card {
            background: white;

            border: 1px solid #e2e8f0;

            border-radius: 14px;

            overflow: hidden;

            box-shadow:
                0 5px 20px rgba(15, 23, 42, 0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f8fafc;

            color: #475569;

            text-align: left;

            padding: 15px 18px;

            font-size: 12px;

            text-transform: uppercase;

            letter-spacing: 0.5px;

            border-bottom: 1px solid #e2e8f0;
        }

        td {
            padding: 17px 18px;

            border-bottom: 1px solid #f1f5f9;

            color: #475569;

            font-size: 14px;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tbody tr {
            transition: 0.2s;
        }

        tbody tr:hover {
            background: #fffaf0;
        }

        .book-title {
            color: #0f172a;

            font-weight: bold;
        }

        .book-id {
            color: #94a3b8;

            font-size: 13px;
        }


        /* Quantity */

        .quantity {
            font-weight: bold;
            color: #0f172a;
        }

        .available {
            display: inline-block;

            padding: 5px 10px;

            border-radius: 20px;

            background: #ecfdf5;

            color: #047857;

            font-size: 12px;

            font-weight: bold;
        }

        .unavailable {
            display: inline-block;

            padding: 5px 10px;

            border-radius: 20px;

            background: #fef2f2;

            color: #b91c1c;

            font-size: 12px;

            font-weight: bold;
        }


        /* Request button */

        .request-btn {
            background: #f59e0b;

            color: #111827;

            border: none;

            padding: 9px 14px;

            border-radius: 7px;

            font-size: 13px;

            font-weight: bold;

            cursor: pointer;

            transition: 0.25s;
        }

        .request-btn:hover {
            background: #fbbf24;

            transform: translateY(-1px);
        }


        /* Back */

        .bottom-nav {
            margin-top: 25px;
        }

        .back-btn {
            display: inline-block;

            text-decoration: none;

            color: #475569;

            font-size: 14px;

            transition: 0.2s;
        }

        .back-btn:hover {
            color: #f59e0b;
        }


        /* Responsive */

        @media (max-width: 800px) {

            .navbar {
                padding: 0 20px;
            }

            .container {
                width: 94%;
            }

            .page-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .table-card {
                overflow-x: auto;
            }

            table {
                min-width: 750px;
            }

        }

        @media (max-width: 500px) {

            .logo span {
                display: none;
            }

            .nav-links a {
                padding: 8px;
            }

            .page-header h1 {
                font-size: 25px;
            }

        }

    </style>

</head>


<body>


<!-- Navbar -->

<nav class="navbar">

    <div class="logo">

        <div class="logo-icon">
            📚
        </div>

        <span>
            College Library
        </span>

    </div>


    <div class="nav-links">

        <a href="dashboard.php">
            Dashboard
        </a>

        <a href="logout.php" class="logout">
            Logout
        </a>

    </div>

</nav>



<!-- Main -->

<main class="container">


    <div class="page-header">

        <div>

            <small>
                LIBRARY COLLECTION
            </small>

            <h1>
                Available Books
            </h1>

            <p>
                Browse the collection and request a book
                from the library.
            </p>

        </div>

    </div>



    <?php if ($message != "") { ?>

        <div class="message">

            <?php echo htmlspecialchars($message); ?>

        </div>

    <?php } ?>



    <div class="table-card">

        <table>

            <thead>

                <tr>

                    <th>ID</th>

                    <th>Book</th>

                    <th>Author</th>

                    <th>Publisher</th>

                    <th>Total</th>

                    <th>Available</th>

                    <th>Action</th>

                </tr>

            </thead>


            <tbody>

                <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                <tr>

                    <td>

                        <span class="book-id">

                            #<?php echo $row['id']; ?>

                        </span>

                    </td>


                    <td>

                        <span class="book-title">

                            <?php echo htmlspecialchars($row['title']); ?>

                        </span>

                    </td>


                    <td>

                        <?php echo htmlspecialchars($row['author_name']); ?>

                    </td>


                    <td>

                        <?php echo htmlspecialchars($row['publisher_name']); ?>

                    </td>


                    <td>

                        <span class="quantity">

                            <?php echo $row['quantity']; ?>

                        </span>

                    </td>


                    <td>

                        <?php if ($row['available_quantity'] > 0) { ?>

                            <span class="available">

                                <?php echo $row['available_quantity']; ?>
                                Available

                            </span>

                        <?php } else { ?>

                            <span class="unavailable">

                                Not Available

                            </span>

                        <?php } ?>

                    </td>


                    <td>

                        <?php if ($row['available_quantity'] > 0) { ?>

                            <form method="POST">

                                <input
                                    type="hidden"
                                    name="book_id"
                                    value="<?php echo $row['id']; ?>"
                                >

                                <button
                                    type="submit"
                                    name="request_book"
                                    class="request-btn"
                                >
                                    Request Book
                                </button>

                            </form>

                        <?php } else { ?>

                            <span class="unavailable">
                                Unavailable
                            </span>

                        <?php } ?>

                    </td>

                </tr>

                <?php } ?>

            </tbody>

        </table>

    </div>



    <div class="bottom-nav">

        <a href="dashboard.php" class="back-btn">
            ← Back to Dashboard
        </a>

    </div>


</main>


</body>

</html>