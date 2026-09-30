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
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Books | College Library</title>

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
            max-width: 1250px;
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
            min-width: 850px;
        }

        th {
            text-align: left;
            padding: 17px 18px;
            background: #172033;
            color: #94a3b8;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
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

        .book-title {
            color: #ffffff;
            font-weight: 600;
        }

        .date {
            color: #cbd5e1;
        }

        .no-data {
            text-align: center;
            padding: 50px 20px;
            color: #64748b;
        }

        /* Badges */

        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .issued {
            background: #172554;
            color: #93c5fd;
        }

        .returned {
            background: #052e16;
            color: #86efac;
        }

        .paid {
            background: #052e16;
            color: #86efac;
        }

        .pending {
            background: #422006;
            color: #fcd34d;
        }

        .no-fine {
            background: #1e293b;
            color: #94a3b8;
        }

        .fine {
            color: #f87171;
            font-weight: 600;
        }

        .no-fine-amount {
            color: #94a3b8;
        }

        /* Bottom links */

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

        <a href="fines.php">
            Fines
        </a>

        <a href="logout.php" class="logout">
            Logout
        </a>

    </div>

</nav>


<!-- Main Content -->

<div class="container">

    <div class="page-header">

        <h1>My Books</h1>

        <p>
            View your issued books, return dates and fine information.
        </p>

    </div>


    <div class="table-card">

        <table>

            <thead>

                <tr>
                    <th>Book</th>
                    <th>Issue Date</th>
                    <th>Due Date</th>
                    <th>Return Date</th>
                    <th>Fine</th>
                    <th>Fine Status</th>
                    <th>Book Status</th>
                </tr>

            </thead>

            <tbody>

                <?php if (mysqli_num_rows($result) > 0) { ?>

                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                        <tr>

                            <td>
                                <span class="book-title">
                                    <?php echo htmlspecialchars($row['title']); ?>
                                </span>
                            </td>

                            <td class="date">
                                <?php echo $row['issue_date']; ?>
                            </td>

                            <td class="date">
                                <?php echo $row['due_date']; ?>
                            </td>

                            <td class="date">

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

                                    echo '<span class="fine">
                                            ₹' . $row['amount'] . '
                                          </span>';

                                } else {

                                    echo '<span class="no-fine-amount">
                                            ₹0
                                          </span>';
                                }

                                ?>

                            </td>

                            <td>

                                <?php

                                if ($row['fine_status'] != NULL) {

                                    if ($row['fine_status'] == 'Paid') {

                                        echo '<span class="badge paid">
                                                Paid
                                              </span>';

                                    } else {

                                        echo '<span class="badge pending">
                                                Pending
                                              </span>';
                                    }

                                } else {

                                    echo '<span class="badge no-fine">
                                            No Fine
                                          </span>';
                                }

                                ?>

                            </td>

                            <td>

                                <?php

                                if ($row['return_date'] != NULL) {

                                    echo '<span class="badge returned">
                                            Returned
                                          </span>';

                                } else {

                                    echo '<span class="badge issued">
                                            Issued
                                          </span>';
                                }

                                ?>

                            </td>

                        </tr>

                    <?php } ?>

                <?php } else { ?>

                    <tr>

                        <td colspan="7">

                            <div class="no-data">

                                <h3>No Books Yet</h3>

                                <p>
                                    You don't have any issued books at the moment.
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