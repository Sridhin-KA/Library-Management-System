<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}

include("../config/db.php");

$message = "";
$message_type = "";


// ================= APPROVE REQUEST =================

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

            $due_date = date(
                'Y-m-d',
                strtotime('+7 days')
            );


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
                $message_type = "success";

            }

        } else {

            $message = "Book is no longer available.";
            $message_type = "error";
        }

    }

}


// ================= REJECT REQUEST =================

if (isset($_POST['reject'])) {

    $request_id = $_POST['request_id'];

    $sql = "UPDATE book_requests
            SET status = 'Rejected'
            WHERE id = $request_id";

    if (mysqli_query($conn, $sql)) {

        $message = "Book request rejected.";
        $message_type = "success";
    }

}


// ================= GET REQUESTS =================

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


// ================= COUNTS =================

$total_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM book_requests"
);

$total_requests = mysqli_fetch_assoc($total_result)['total'];


$pending_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM book_requests
     WHERE status = 'Pending'"
);

$pending_requests = mysqli_fetch_assoc($pending_result)['total'];


$approved_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM book_requests
     WHERE status = 'Approved'"
);

$approved_requests = mysqli_fetch_assoc($approved_result)['total'];


$rejected_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM book_requests
     WHERE status = 'Rejected'"
);

$rejected_requests = mysqli_fetch_assoc($rejected_result)['total'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Book Requests | Admin</title>


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
            padding: 13px 16px;

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


        .pending-number {
            color: #f5b942;
        }


        .approved-number {
            color: #86efac;
        }


        .rejected-number {
            color: #fda4af;
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

            min-width: 800px;
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


        /* ================= BADGES ================= */

        .badge {
            display: inline-block;

            padding: 6px 11px;

            border-radius: 20px;

            font-size: 12px;

            font-weight: 600;
        }


        .badge-pending {
            background: #422006;

            color: #fcd34d;
        }


        .badge-approved {
            background: #052e16;

            color: #86efac;
        }


        .badge-rejected {
            background: #3b1720;

            color: #fda4af;
        }


        /* ================= ACTION BUTTONS ================= */

        .actions {
            display: flex;

            gap: 8px;

            align-items: center;
        }


        .action-btn {
            border: none;

            padding: 8px 13px;

            border-radius: 7px;

            font-size: 12px;

            font-weight: 600;

            cursor: pointer;

            transition: 0.2s;
        }


        .approve {
            background: #166534;

            color: #bbf7d0;
        }


        .approve:hover {
            background: #15803d;
        }


        .reject {
            background: #7f1d1d;

            color: #fecaca;
        }


        .reject:hover {
            background: #991b1b;
        }


        .no-action {
            color: #64748b;

            font-size: 13px;
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

        <h1>Book Requests</h1>

        <p>
            Review and manage book requests submitted by students.
        </p>

    </div>


    <!-- ================= MESSAGE ================= -->

    <?php if ($message != "") { ?>

        <div class="message <?php echo $message_type; ?>">

            <?php echo $message; ?>

        </div>

    <?php } ?>


    <!-- ================= STATISTICS ================= -->

    <div class="stats">


        <div class="stat-card">

            <div class="stat-title">
                Total Requests
            </div>

            <div class="stat-number">
                <?php echo $total_requests; ?>
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-title">
                Pending
            </div>

            <div class="stat-number pending-number">
                <?php echo $pending_requests; ?>
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-title">
                Approved
            </div>

            <div class="stat-number approved-number">
                <?php echo $approved_requests; ?>
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-title">
                Rejected
            </div>

            <div class="stat-number rejected-number">
                <?php echo $rejected_requests; ?>
            </div>

        </div>


    </div>


    <!-- ================= REQUEST TABLE ================= -->

    <div class="table-card">

        <table>

            <thead>

                <tr>

                    <th>ID</th>

                    <th>Student</th>

                    <th>Book</th>

                    <th>Request Date</th>

                    <th>Status</th>

                    <th>Action</th>

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
                                        $row['book_title']
                                    );
                                    ?>

                                </span>

                            </td>


                            <td>

                                <span class="date">

                                    <?php
                                    echo $row['request_date'];
                                    ?>

                                </span>

                            </td>


                            <td>

                                <?php

                                if ($row['status'] == 'Pending') {

                                    echo '
                                    <span class="badge badge-pending">
                                        Pending
                                    </span>';

                                } elseif (
                                    $row['status'] == 'Approved'
                                ) {

                                    echo '
                                    <span class="badge badge-approved">
                                        Approved
                                    </span>';

                                } else {

                                    echo '
                                    <span class="badge badge-rejected">
                                        Rejected
                                    </span>';
                                }

                                ?>

                            </td>


                            <td>

                                <?php
                                if ($row['status'] == 'Pending') {
                                ?>

                                    <div class="actions">


                                        <!-- Approve -->

                                        <form
                                            method="POST"
                                            style="display:inline;"
                                        >

                                            <input
                                                type="hidden"
                                                name="request_id"
                                                value="<?php
                                                echo $row['id'];
                                                ?>"
                                            >

                                            <button
                                                type="submit"
                                                name="approve"
                                                class="action-btn approve"
                                            >
                                                Approve
                                            </button>

                                        </form>


                                        <!-- Reject -->

                                        <form
                                            method="POST"
                                            style="display:inline;"
                                        >

                                            <input
                                                type="hidden"
                                                name="request_id"
                                                value="<?php
                                                echo $row['id'];
                                                ?>"
                                            >

                                            <button
                                                type="submit"
                                                name="reject"
                                                class="action-btn reject"
                                            >
                                                Reject
                                            </button>

                                        </form>


                                    </div>

                                <?php
                                } else {
                                ?>

                                    <span class="no-action">
                                        No Action
                                    </span>

                                <?php
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
                                    No Book Requests
                                </h3>

                                <p>
                                    There are currently no requests from students.
                                </p>

                            </div>

                        </td>

                    </tr>

                <?php } ?>

            </tbody>

        </table>

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