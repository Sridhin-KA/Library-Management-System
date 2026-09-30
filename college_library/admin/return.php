<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}

include("../config/db.php");

$message = "";
$message_type = "";


// ================= RETURN BOOK =================

if (isset($_POST['return_book'])) {

    $issue_id = $_POST['issue_id'];
    $return_date = $_POST['return_date'];


    // Get issue details

    $sql = "SELECT *
            FROM issue_books
            WHERE id = $issue_id";

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

        $sql = "INSERT INTO return_books
                (issue_id, return_date)
                VALUES
                ($issue_id, '$return_date')";


        if (mysqli_query($conn, $sql)) {

            $return_id = mysqli_insert_id($conn);


            // Add fine if late

            if ($fine > 0) {

                $fine_sql = "INSERT INTO fines
                             (return_id, amount, status)
                             VALUES
                             ($return_id, $fine, 'Pending')";

                mysqli_query($conn, $fine_sql);
            }


            // Increase available book quantity

            $update_book = "UPDATE books
                            SET available_quantity =
                            available_quantity + 1
                            WHERE id = $book_id";

            mysqli_query($conn, $update_book);


            $message = "Book returned successfully.";

            if ($fine > 0) {

                $message .=
                    " Fine: ₹" . $fine .
                    " (" . $late_days . " late days).";
            }

            $message_type = "success";
        }
    }
}


// ================= GET CURRENTLY ISSUED BOOKS =================

$sql = "SELECT

            issue_books.id,

            students.name AS student_name,

            books.title,

            issue_books.issue_date,

            issue_books.due_date

        FROM issue_books

        JOIN students
            ON issue_books.student_id = students.id

        JOIN books
            ON issue_books.book_id = books.id

        LEFT JOIN return_books
            ON issue_books.id = return_books.issue_id

        WHERE return_books.id IS NULL

        ORDER BY issue_books.id DESC";


$result = mysqli_query($conn, $sql);


// ================= STATISTICS =================

// Currently issued

$issued_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM issue_books

     LEFT JOIN return_books
        ON issue_books.id = return_books.issue_id

     WHERE return_books.id IS NULL"
);

$issued_count =
    mysqli_fetch_assoc($issued_result)['total'];


// Overdue

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

$overdue_count =
    mysqli_fetch_assoc($overdue_result)['total'];


// Returned books

$returned_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM return_books"
);

$returned_count =
    mysqli_fetch_assoc($returned_result)['total'];


// Total fines generated

$fine_result = mysqli_query(
    $conn,
    "SELECT COALESCE(SUM(amount), 0) AS total
     FROM fines"
);

$total_fines =
    mysqli_fetch_assoc($fine_result)['total'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Return Book | Admin</title>


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


        /* ================= MESSAGE ================= */

        .message {
            padding: 14px 16px;

            border-radius: 9px;

            margin-bottom: 25px;

            font-size: 14px;
        }


        .success {
            background: #052e16;

            border: 1px solid #166534;

            color: #86efac;
        }


        .error {
            background: #3b1720;

            border: 1px solid #7f1d2d;

            color: #fda4af;
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


        .issued {
            color: #f5b942;
        }


        .overdue {
            color: #f87171;
        }


        .returned {
            color: #86efac;
        }


        .fine {
            color: #fcd34d;
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

            min-width: 900px;
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


        .badge-active {
            background: #422006;

            color: #fcd34d;
        }


        .badge-overdue {
            background: #3b1720;

            color: #fda4af;
        }


        /* ================= RETURN FORM ================= */

        .return-form {
            display: flex;

            align-items: center;

            gap: 8px;
        }


        .return-date {
            padding: 8px 10px;

            border-radius: 7px;

            border: 1px solid #334155;

            background: #0f172a;

            color: #ffffff;

            font-size: 12px;

            outline: none;
        }


        .return-date:focus {
            border-color: #f5b942;
        }


        .return-btn {
            border: none;

            padding: 8px 13px;

            border-radius: 7px;

            background: #f5b942;

            color: #0b1120;

            font-size: 12px;

            font-weight: bold;

            cursor: pointer;

            transition: 0.2s;
        }


        .return-btn:hover {
            background: #ffd166;

            transform: translateY(-1px);
        }


        /* ================= POLICY ================= */

        .policy {
            margin-top: 25px;

            background: #111827;

            border: 1px solid #243044;

            border-radius: 12px;

            padding: 18px;

            color: #94a3b8;

            font-size: 13px;

            line-height: 1.6;
        }


        .policy strong {
            color: #f5b942;
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


            .return-form {
                flex-direction: column;

                align-items: stretch;
            }


            .return-date,
            .return-btn {
                width: 100%;
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

        <h1>Return Book</h1>

        <p>
            Process returned books and automatically calculate late fines.
        </p>

    </div>


    <!-- ================= MESSAGE ================= -->

    <?php if ($message != "") { ?>

        <div class="message <?php echo $message_type; ?>">

            <?php echo $message; ?>

        </div>

    <?php } ?>


    <!-- ================= STATS ================= -->

    <div class="stats">


        <div class="stat-card">

            <div class="stat-title">
                Currently Issued
            </div>

            <div class="stat-number issued">
                <?php echo $issued_count; ?>
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-title">
                Overdue Books
            </div>

            <div class="stat-number overdue">
                <?php echo $overdue_count; ?>
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-title">
                Returned Books
            </div>

            <div class="stat-number returned">
                <?php echo $returned_count; ?>
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-title">
                Total Fines Generated
            </div>

            <div class="stat-number fine">
                ₹<?php echo number_format($total_fines, 2); ?>
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

                    <th>Return</th>

                </tr>

            </thead>


            <tbody>

                <?php if (mysqli_num_rows($result) > 0) { ?>

                    <?php while (
                        $row =
                        mysqli_fetch_assoc($result)
                    ) { ?>


                        <tr>


                            <td>

                                <span class="issue-id">

                                    #<?php
                                    echo $row['id'];
                                    ?>

                                </span>

                            </td>


                            <td>

                                <span class="student-name">

                                    <?php
                                    echo htmlspecialchars(
                                        $row['student_name']
                                    );
                                    ?>

                                </span>

                            </td>


                            <td>

                                <span class="book-title">

                                    <?php
                                    echo htmlspecialchars(
                                        $row['title']
                                    );
                                    ?>

                                </span>

                            </td>


                            <td>

                                <span class="date">

                                    <?php
                                    echo $row['issue_date'];
                                    ?>

                                </span>

                            </td>


                            <td>

                                <span class="date">

                                    <?php
                                    echo $row['due_date'];
                                    ?>

                                </span>

                            </td>


                            <td>


                                <?php

                                if (
                                    $row['due_date'] < $today
                                ) {

                                    echo '
                                    <span class="badge badge-overdue">
                                        Overdue
                                    </span>';

                                } else {

                                    echo '
                                    <span class="badge badge-active">
                                        Issued
                                    </span>';
                                }

                                ?>

                            </td>


                            <td>


                                <form
                                    method="POST"
                                    class="return-form"
                                >


                                    <input
                                        type="hidden"
                                        name="issue_id"
                                        value="<?php
                                        echo $row['id'];
                                        ?>"
                                    >


                                    <input
                                        type="date"
                                        name="return_date"
                                        value="<?php
                                        echo date('Y-m-d');
                                        ?>"
                                        class="return-date"
                                        required
                                    >


                                    <button
                                        type="submit"
                                        name="return_book"
                                        class="return-btn"
                                    >
                                        Return
                                    </button>


                                </form>


                            </td>


                        </tr>


                    <?php } ?>

                <?php } else { ?>


                    <tr>

                        <td colspan="7">

                            <div class="empty">

                                <h3>
                                    No Books to Return
                                </h3>

                                <p>
                                    There are currently no active book issues.
                                </p>

                            </div>

                        </td>

                    </tr>


                <?php } ?>

            </tbody>

        </table>

    </div>


    <!-- ================= POLICY ================= -->

    <div class="policy">

        <strong>Fine Policy:</strong>

        Books returned after the due date are charged
        <strong>₹10 per late day</strong>.

        Returning the book on or before the due date
        will not generate a fine.

    </div>


    <!-- ================= BACK ================= -->

    <div class="bottom-actions">

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