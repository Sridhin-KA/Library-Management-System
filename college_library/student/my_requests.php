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
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Requests | College Library</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #0b1120;
            color: #ffffff;
            min-height: 100vh;
        }

        /* Navbar */

        .navbar {
            height: 70px;
            background: #111827;
            border-bottom: 1px solid #243044;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 6%;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 20px;
            font-weight: bold;
        }

        .logo-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #f5b942;
            color: #0b1120;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .nav-links a {
            color: #cbd5e1;
            text-decoration: none;
            font-size: 14px;
            transition: 0.2s;
        }

        .nav-links a:hover {
            color: #f5b942;
        }

        .logout {
            padding: 9px 16px;
            border: 1px solid #374151;
            border-radius: 8px;
        }

        .logout:hover {
            border-color: #f5b942;
        }

        /* Main */

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 45px auto;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 30px;
            margin-bottom: 8px;
        }

        .page-header p {
            color: #94a3b8;
            font-size: 15px;
        }

        /* Table */

        .table-card {
            background: #111827;
            border: 1px solid #243044;
            border-radius: 16px;
            overflow-x: auto;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.25);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 700px;
        }

        th {
            text-align: left;
            padding: 17px 18px;
            background: #172033;
            color: #94a3b8;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 17px 18px;
            border-top: 1px solid #243044;
            color: #dbe4f0;
            font-size: 14px;
        }

        tbody tr {
            transition: 0.2s;
        }

        tbody tr:hover {
            background: #151f31;
        }

        .request-id {
            color: #94a3b8;
            font-weight: 600;
        }

        .book-title {
            color: #ffffff;
            font-weight: 600;
        }

        .author {
            color: #94a3b8;
        }

        .date {
            color: #cbd5e1;
        }

        /* Status badges */

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .pending {
            background: #422006;
            color: #fcd34d;
        }

        .approved {
            background: #052e16;
            color: #86efac;
        }

        .rejected {
            background: #3b1720;
            color: #fda4af;
        }

        .unknown {
            background: #1e293b;
            color: #94a3b8;
        }

        /* Empty state */

        .no-data {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-icon {
            width: 55px;
            height: 55px;
            margin: 0 auto 15px;
            border-radius: 14px;
            background: #172033;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 23px;
        }

        .no-data h3 {
            margin-bottom: 8px;
            color: #e2e8f0;
        }

        .no-data p {
            color: #64748b;
            font-size: 14px;
        }

        /* Bottom buttons */

        .bottom-actions {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }

        .action-btn {
            display: inline-block;
            padding: 11px 17px;
            border-radius: 9px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: 0.2s;
        }

        .primary-btn {
            background: #f5b942;
            color: #0b1120;
        }

        .primary-btn:hover {
            background: #ffd166;
            transform: translateY(-1px);
        }

        .secondary-btn {
            border: 1px solid #334155;
            color: #cbd5e1;
            background: #111827;
        }

        .secondary-btn:hover {
            border-color: #f5b942;
            color: #f5b942;
        }

        /* Mobile */

        @media (max-width: 700px) {

            .navbar {
                padding: 0 20px;
            }

            .nav-links {
                gap: 10px;
            }

            .nav-links a:not(.logout) {
                display: none;
            }

            .container {
                width: 94%;
                margin: 30px auto;
            }

            .page-header h1 {
                font-size: 25px;
            }

            .bottom-actions {
                flex-direction: column;
            }

            .action-btn {
                text-align: center;
            }

        }

    </style>

</head>

<body>

<!-- Navbar -->

<nav class="navbar">

    <div class="logo">

        <div class="logo-icon">
            L
        </div>

        <span>College Library</span>

    </div>


    <div class="nav-links">

        <a href="dashboard.php">
            Dashboard
        </a>

        <a href="books.php">
            Books
        </a>

        <a href="my_books.php">
            My Books
        </a>

        <a href="fines.php">
            Fines
        </a>

        <a href="logout.php" class="logout">
            Logout
        </a>

    </div>

</nav>


<!-- Main -->

<div class="container">

    <div class="page-header">

        <h1>My Book Requests</h1>

        <p>
            Track the books you have requested from the library.
        </p>

    </div>


    <div class="table-card">

        <table>

            <thead>

                <tr>
                    <th>ID</th>
                    <th>Book</th>
                    <th>Author</th>
                    <th>Request Date</th>
                    <th>Status</th>
                </tr>

            </thead>


            <tbody>

                <?php if (mysqli_num_rows($result) > 0) { ?>

                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                        <tr>

                            <td>
                                <span class="request-id">
                                    #<?php echo $row['id']; ?>
                                </span>
                            </td>

                            <td>
                                <span class="book-title">
                                    <?php echo htmlspecialchars($row['book_title']); ?>
                                </span>
                            </td>

                            <td>
                                <span class="author">
                                    <?php echo htmlspecialchars($row['author_name']); ?>
                                </span>
                            </td>

                            <td>
                                <span class="date">
                                    <?php echo $row['request_date']; ?>
                                </span>
                            </td>

                            <td>

                                <?php

                                if ($row['status'] == 'Pending') {

                                    echo '<span class="badge pending">
                                            Pending
                                          </span>';

                                } elseif ($row['status'] == 'Approved') {

                                    echo '<span class="badge approved">
                                            Approved
                                          </span>';

                                } elseif ($row['status'] == 'Rejected') {

                                    echo '<span class="badge rejected">
                                            Rejected
                                          </span>';

                                } else {

                                    echo '<span class="badge unknown">'
                                        . htmlspecialchars($row['status']) .
                                        '</span>';
                                }

                                ?>

                            </td>

                        </tr>

                    <?php } ?>

                <?php } else { ?>

                    <tr>

                        <td colspan="5">

                            <div class="no-data">

                                <div class="empty-icon">
                                    📚
                                </div>

                                <h3>No Requests Yet</h3>

                                <p>
                                    You have not requested any books yet.
                                </p>

                            </div>

                        </td>

                    </tr>

                <?php } ?>

            </tbody>

        </table>

    </div>


    <div class="bottom-actions">

        <a href="books.php" class="action-btn primary-btn">
            Browse Available Books
        </a>

        <a href="dashboard.php" class="action-btn secondary-btn">
            Back to Dashboard
        </a>

    </div>

</div>

</body>

</html>