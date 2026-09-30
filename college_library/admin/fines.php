<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}

include("../config/db.php");

$message = "";
$message_type = "";


// ================= MARK FINE AS PAID =================

if (isset($_POST['pay_fine'])) {

    $fine_id = $_POST['fine_id'];

    $sql = "UPDATE fines
            SET status = 'Paid'
            WHERE id = $fine_id";

    if (mysqli_query($conn, $sql)) {

        $message = "Fine marked as paid.";
        $message_type = "success";
    }
}


// ================= GET ALL FINES =================

$sql = "SELECT
            fines.id,
            students.name AS student_name,
            books.title AS book_title,
            return_books.return_date,
            fines.amount,
            fines.status

        FROM fines

        JOIN return_books
            ON fines.return_id = return_books.id

        JOIN issue_books
            ON return_books.issue_id = issue_books.id

        JOIN students
            ON issue_books.student_id = students.id

        JOIN books
            ON issue_books.book_id = books.id

        ORDER BY fines.id DESC";

$result = mysqli_query($conn, $sql);


// ================= STATISTICS =================

// Total fines

$total_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM fines"
);

$total_fines = mysqli_fetch_assoc($total_result)['total'];


// Pending fines

$pending_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM fines
     WHERE status = 'Pending'"
);

$pending_fines = mysqli_fetch_assoc($pending_result)['total'];


// Paid fines

$paid_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM fines
     WHERE status = 'Paid'"
);

$paid_fines = mysqli_fetch_assoc($paid_result)['total'];


// Pending amount

$amount_result = mysqli_query(
    $conn,
    "SELECT COALESCE(SUM(amount), 0) AS total
     FROM fines
     WHERE status = 'Pending'"
);

$pending_amount = mysqli_fetch_assoc($amount_result)['total'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Fines Management | Admin</title>

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


        /* ================= STATISTICS ================= */

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

        .pending {
            color: #f5b942;
        }

        .paid {
            color: #86efac;
        }

        .amount {
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

        .fine-id {
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

        .fine-amount {
            color: #f87171;

            font-weight: 600;
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

        .badge-paid {
            background: #052e16;

            color: #86efac;
        }


        /* ================= PAY BUTTON ================= */

        .pay-btn {
            border: none;

            padding: 8px 14px;

            border-radius: 7px;

            background: #166534;

            color: #bbf7d0;

            font-size: 12px;

            font-weight: 600;

            cursor: pointer;

            transition: 0.2s;
        }

        .pay-btn:hover {
            background: #15803d;
        }

        .paid-text {
            color: #86efac;

            font-size: 13px;

            font-weight: 600;
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


        /* ================= BACK ================= */

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

        <h1>Fines Management</h1>

        <p>
            View, track and manage fines generated from returned books.
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
                Total Fines
            </div>

            <div class="stat-number">
                <?php echo $total_fines; ?>
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-title">
                Pending Fines
            </div>

            <div class="stat-number pending">
                <?php echo $pending_fines; ?>
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-title">
                Paid Fines
            </div>

            <div class="stat-number paid">
                <?php echo $paid_fines; ?>
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-title">
                Pending Amount
            </div>

            <div class="stat-number amount">
                ₹<?php echo number_format($pending_amount, 2); ?>
            </div>

        </div>


    </div>


    <!-- ================= TABLE ================= -->

    <div class="table-card">

        <table>

            <thead>

                <tr>

                    <th>Fine ID</th>

                    <th>Student</th>

                    <th>Book</th>

                    <th>Return Date</th>

                    <th>Fine Amount</th>

                    <th>Status</th>

                    <th>Action</th>

                </tr>

            </thead>


            <tbody>

                <?php if (mysqli_num_rows($result) > 0) { ?>

                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                        <tr>


                            <td>

                                <span class="fine-id">

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
                                    echo $row['return_date'];
                                    ?>

                                </span>

                            </td>


                            <td>

                                <span class="fine-amount">

                                    ₹<?php
                                    echo number_format(
                                        $row['amount'],
                                        2
                                    );
                                    ?>

                                </span>

                            </td>


                            <td>

                                <?php
                                if ($row['status'] == 'Pending') {
                                ?>

                                    <span class="badge badge-pending">
                                        Pending
                                    </span>

                                <?php
                                } else {
                                ?>

                                    <span class="badge badge-paid">
                                        Paid
                                    </span>

                                <?php
                                }
                                ?>

                            </td>


                            <td>

                                <?php
                                if ($row['status'] == 'Pending') {
                                ?>

                                    <form method="POST">

                                        <input
                                            type="hidden"
                                            name="fine_id"
                                            value="<?php
                                            echo $row['id'];
                                            ?>"
                                        >

                                        <button
                                            type="submit"
                                            name="pay_fine"
                                            class="pay-btn"
                                        >
                                            Mark as Paid
                                        </button>

                                    </form>

                                <?php
                                } else {
                                ?>

                                    <span class="paid-text">
                                        ✓ Paid
                                    </span>

                                <?php
                                }
                                ?>

                            </td>


                        </tr>

                    <?php } ?>

                <?php } else { ?>

                    <tr>

                        <td colspan="7">

                            <div class="empty">

                                <h3>
                                    No Fines
                                </h3>

                                <p>
                                    There are currently no fines in the system.
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