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


// Total issued records

$total_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM issue_books"
);

$total_issued =
    mysqli_fetch_assoc($total_result)['total'];


// Currently active books

$active_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM issue_books

     LEFT JOIN return_books
        ON issue_books.id = return_books.issue_id

     WHERE return_books.id IS NULL"
);

$active_books =
    mysqli_fetch_assoc($active_result)['total'];


// Returned books

$returned_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM return_books"
);

$returned_books =
    mysqli_fetch_assoc($returned_result)['total'];


// Overdue books

$today = date('Y-m-d');

$overdue_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM issue_books

     LEFT JOIN return_books
        ON issue_books.id = return_books.issue_id

     WHERE return_books.id IS NULL
     AND issue_books.due_date < '$today'"
);

$overdue_books =
    mysqli_fetch_assoc($overdue_result)['total'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Issued Books | Admin</title>


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


        /* ================= NAVBAR ================= */

        .navbar {
            height: 70px;

            background: #111827;

            border-bottom: 1px solid #243044;

            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 0 5%;
        }


        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }


        .brand-icon {
            width: 40px;
            height: 40px;

            border-radius: 10px;

            background: #f5b942;

            color: #0b1120;

            display: flex;
            align-items: center;
            justify-content: center;

            font-weight: bold;
        }


        .brand h2 {
            font-size: 19px;
        }


        .nav-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }


        .admin-label {
            color: #f5b942;

            font-size: 12px;

            font-weight: bold;
        }


        .logout {
            text-decoration: none;

            color: #cbd5e1;

            border: 1px solid #334155;

            padding: 9px 16px;

            border-radius: 8px;

            font-size: 13px;

            transition: 0.2s;
        }


        .logout:hover {
            color: #f5b942;

            border-color: #f5b942;
        }


        /* ================= CONTAINER ================= */

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

            font-size: 14px;
        }


        /* ================= STATS ================= */

        .stats {
            display: grid;

            grid-template-columns:
                repeat(4, 1fr);

            gap: 18px;

            margin-bottom: 35px;
        }


        .stat-card {
            background: #111827;

            border: 1px solid #243044;

            border-radius: 15px;

            padding: 22px;
        }


        .stat-title {
            color: #94a3b8;

            font-size: 13px;

            margin-bottom: 10px;
        }


        .stat-number {
            font-size: 28px;

            font-weight: bold;
        }


        .active {
            color: #f5b942;
        }


        .returned {
            color: #86efac;
        }


        .overdue {
            color: #f87171;
        }


        /* ================= TABLE ================= */

        .table-card {
            background: #111827;

            border: 1px solid #243044;

            border-radius: 16px;

            overflow-x: auto;

            box-shadow:
                0 15px 40px
                rgba(0, 0, 0, 0.25);
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


        /* ================= TABLE CONTENT ================= */

        .issue-id {
            color: #94a3b8;

            font-weight: 600;
        }


        .student-name {
            color: #ffffff;

            font-weight: 600;
        }


        .book-title {
            color: #cbd5e1;
        }


        .date {
            color: #94a3b8;
        }


        /* ================= STATUS ================= */

        .badge {
            display: inline-block;

            padding: 6px 11px;

            border-radius: 20px;

            font-size: 12px;

            font-weight: 600;
        }


        .badge-issued {
            background: #422006;

            color: #fcd34d;
        }


        .badge-overdue {
            background: #3b1720;

            color: #fda4af;
        }


        /* ================= EMPTY ================= */

        .empty {
            text-align: center;

            padding: 60px 20px;

            color: #64748b;
        }


        .empty h3 {
            color: #cbd5e1;

            margin-bottom: 7px;
        }


        /* ================= BOTTOM ================= */

        .bottom-actions {
            margin-top: 25px;

            display: flex;

            gap: 12px;
        }


        .back-btn {
            display: inline-block;

            padding: 11px 17px;

            border-radius: 9px;

            text-decoration: none;

            background: #172033;

            border: 1px solid #334155;

            color: #cbd5e1;

            font-size: 13px;

            font-weight: 600;

            transition: 0.2s;
        }


        .back-btn:hover {
            border-color: #f5b942;

            color: #f5b942;
        }


        .issue-btn {
            display: inline-block;

            padding: 11px 17px;

            border-radius: 9px;

            text-decoration: none;

            background: #f5b942;

            color: #0b1120;

            font-size: 13px;

            font-weight: 600;

            transition: 0.2s;
        }


        .issue-btn:hover {
            background: #ffd166;

            transform: translateY(-1px);
        }


        /* ================= RESPONSIVE ================= */

        @media (max-width: 900px) {

            .stats {
                grid-template-columns:
                    repeat(2, 1fr);
            }

        }


        @media (max-width: 600px) {

            .navbar {
                padding: 0 20px;
            }


            .brand h2 {
                font-size: 16px;
            }


            .admin-label {
                display: none;
            }


            .container {
                width: 94%;

                margin: 30px auto;
            }


            .page-header h1 {
                font-size: 25px;
            }


            .stats {
                grid-template-columns: 1fr;
            }


            .bottom-actions {
                flex-direction: column;
            }


            .back-btn,
            .issue-btn {
                text-align: center;
            }

        }

    </style>

</head>


<body>


<!-- ================= NAVBAR ================= -->

<nav class="navbar">

    <div class="brand">

        <div class="brand-icon">
            L
        </div>

        <h2>College Library</h2>

    </div>


    <div class="nav-right">

        <span class="admin-label">
            ADMIN PANEL
        </span>

        <a
            href="../logout.php"
            class="logout"
        >
            Logout
        </a>

    </div>

</nav>


<!-- ================= MAIN ================= -->

<div class="container">


    <div class="page-header">

        <h1>Issued Books</h1>

        <p>
            View all books that have been issued to students.
        </p>

    </div>


    <!-- ================= STATS ================= -->

    <div class="stats">


        <div class="stat-card">

            <div class="stat-title">
                Total Issues
            </div>

            <div class="stat-number">
                <?php echo $total_issued; ?>
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-title">
                Currently Issued
            </div>

            <div class="stat-number active">
                <?php echo $active_books; ?>
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-title">
                Returned Books
            </div>

            <div class="stat-number returned">
                <?php echo $returned_books; ?>
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-title">
                Overdue Books
            </div>

            <div class="stat-number overdue">
                <?php echo $overdue_books; ?>
            </div>

        </div>


    </div>


    <!-- ================= TABLE ================= -->

    <div class="table-card">

        <table>

            <thead>

                <tr>

                    <th>ID</th>

                    <th>Student</th>

                    <th>Book</th>

                    <th>Issue Date</th>

                    <th>Due Date</th>

                    <th>Status</th>

                </tr>

            </thead>


            <tbody>

                <?php if (mysqli_num_rows($result) > 0) { ?>

                    <?php while (
                        $issue =
                        mysqli_fetch_assoc($result)
                    ) { ?>


                        <tr>


                            <td>

                                <span class="issue-id">

                                    #<?php
                                    echo $issue['id'];
                                    ?>

                                </span>

                            </td>


                            <td>

                                <span class="student-name">

                                    <?php
                                    echo htmlspecialchars(
                                        $issue['student_name']
                                    );
                                    ?>

                                </span>

                            </td>


                            <td>

                                <span class="book-title">

                                    <?php
                                    echo htmlspecialchars(
                                        $issue['book_title']
                                    );
                                    ?>

                                </span>

                            </td>


                            <td>

                                <span class="date">

                                    <?php
                                    echo $issue['issue_date'];
                                    ?>

                                </span>

                            </td>


                            <td>

                                <span class="date">

                                    <?php
                                    echo $issue['due_date'];
                                    ?>

                                </span>

                            </td>


                            <td>


                                <?php

                                // Check whether book has been returned

                                $issue_id = $issue['id'];

                                $return_check = mysqli_query(
                                    $conn,
                                    "SELECT id
                                     FROM return_books
                                     WHERE issue_id = $issue_id
                                     LIMIT 1"
                                );


                                if (
                                    mysqli_num_rows(
                                        $return_check
                                    ) > 0
                                ) {

                                    echo '
                                    <span class="badge">
                                        Returned
                                    </span>';

                                } elseif (
                                    $issue['due_date'] < $today
                                ) {

                                    echo '
                                    <span class="badge badge-overdue">
                                        Overdue
                                    </span>';

                                } else {

                                    echo '
                                    <span class="badge badge-issued">
                                        Issued
                                    </span>';
                                }

                                ?>

                            </td>


                        </tr>


                    <?php } ?>

                <?php } else { ?>


                    <tr>

                        <td colspan="6">

                            <div class="empty">

                                <h3>
                                    No Issued Books
                                </h3>

                                <p>
                                    There are currently no book issue records.
                                </p>

                            </div>

                        </td>

                    </tr>


                <?php } ?>

            </tbody>

        </table>

    </div>


    <!-- ================= ACTIONS ================= -->

    <div class="bottom-actions">

        <a
            href="issue.php"
            class="issue-btn"
        >
            + Issue Book
        </a>


        <a
            href="dashboard.php"
            class="back-btn"
        >
            ← Back to Dashboard
        </a>

    </div>


</div>

</body>

</html>