<?php

session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

include("../config/db.php");

$student_id = $_SESSION['student_id'];

$sql = "SELECT
            fines.id AS fine_id,
            books.title AS book_title,
            return_books.return_date,
            fines.amount,
            fines.status
        FROM fines

        JOIN return_books
            ON fines.return_id = return_books.id

        JOIN issue_books
            ON return_books.issue_id = issue_books.id

        JOIN books
            ON issue_books.book_id = books.id

        WHERE issue_books.student_id = $student_id

        ORDER BY fines.id DESC";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>My Fines | College Library</title>

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
            gap: 10px;
        }

        .nav-links a {
            color: #cbd5e1;
            text-decoration: none;

            padding: 9px 14px;

            border-radius: 7px;

            font-size: 14px;

            transition: 0.3s;
        }

        .nav-links a:hover {
            background: #1e293b;
            color: white;
        }

        .logout {
            border: 1px solid #334155;
        }


        /* Main */

        .container {
            width: 88%;
            max-width: 1100px;

            margin: 45px auto;
        }


        .page-header {
            margin-bottom: 30px;
        }

        .page-header small {
            color: #f59e0b;

            font-size: 12px;
            font-weight: bold;

            letter-spacing: 1px;
        }

        .page-header h1 {
            margin-top: 7px;

            font-size: 32px;
        }

        .page-header p {
            margin-top: 8px;

            color: #64748b;

            font-size: 14px;
        }


        /* Summary */

        .summary {
            display: grid;

            grid-template-columns:
                repeat(2, 1fr);

            gap: 20px;

            margin-bottom: 25px;
        }

        .summary-card {
            background: white;

            border: 1px solid #e2e8f0;

            border-radius: 14px;

            padding: 22px;

            box-shadow:
                0 4px 15px rgba(15, 23, 42, 0.04);
        }

        .summary-card span {
            color: #64748b;

            font-size: 13px;
        }

        .summary-card h2 {
            margin-top: 8px;

            font-size: 25px;
        }


        /* Table */

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

        tbody tr:hover {
            background: #fffaf0;
        }

        .fine-id {
            color: #94a3b8;
        }

        .book-title {
            color: #0f172a;
            font-weight: bold;
        }

        .amount {
            font-weight: bold;
            color: #b45309;
        }


        /* Status */

        .status {
            display: inline-block;

            padding: 6px 12px;

            border-radius: 20px;

            font-size: 12px;

            font-weight: bold;
        }

        .pending {
            background: #fff7ed;
            color: #c2410c;
        }

        .paid {
            background: #ecfdf5;
            color: #047857;
        }


        /* Empty */

        .empty {
            text-align: center;

            padding: 50px 20px;

            color: #64748b;
        }

        .empty-icon {
            font-size: 40px;

            margin-bottom: 12px;
        }

        .empty h3 {
            color: #334155;

            margin-bottom: 7px;
        }


        /* Bottom */

        .bottom-nav {
            margin-top: 25px;
        }

        .bottom-nav a {
            color: #475569;

            text-decoration: none;

            font-size: 14px;
        }

        .bottom-nav a:hover {
            color: #f59e0b;
        }


        /* Responsive */

        @media (max-width: 700px) {

            .navbar {
                padding: 0 20px;
            }

            .container {
                width: 94%;
            }

            .summary {
                grid-template-columns: 1fr;
            }

            .table-card {
                overflow-x: auto;
            }

            table {
                min-width: 650px;
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


    <section class="page-header">

        <small>
            STUDENT ACCOUNT
        </small>

        <h1>
            My Fines
        </h1>

        <p>
            View your library fines and payment status.
        </p>

    </section>



    <?php

    $total_fine = 0;
    $pending_fine = 0;

    // Calculate totals
    mysqli_data_seek($result, 0);

    while ($row = mysqli_fetch_assoc($result)) {

        $total_fine += $row['amount'];

        if ($row['status'] == 'Pending') {
            $pending_fine += $row['amount'];
        }
    }

    // Reset result pointer
    mysqli_data_seek($result, 0);

    ?>


    <!-- Summary -->

    <section class="summary">

        <div class="summary-card">

            <span>
                Total Fines
            </span>

            <h2>
                ₹<?php echo $total_fine; ?>
            </h2>

        </div>


        <div class="summary-card">

            <span>
                Pending Amount
            </span>

            <h2>
                ₹<?php echo $pending_fine; ?>
            </h2>

        </div>

    </section>



    <!-- Fine Table -->

    <div class="table-card">

        <?php if (mysqli_num_rows($result) > 0) { ?>

            <table>

                <thead>

                    <tr>

                        <th>Fine ID</th>

                        <th>Book</th>

                        <th>Return Date</th>

                        <th>Fine Amount</th>

                        <th>Status</th>

                    </tr>

                </thead>


                <tbody>

                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                    <tr>

                        <td>

                            <span class="fine-id">

                                #<?php echo $row['fine_id']; ?>

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

                            <?php echo $row['return_date']; ?>

                        </td>


                        <td>

                            <span class="amount">

                                ₹<?php echo $row['amount']; ?>

                            </span>

                        </td>


                        <td>

                            <?php if ($row['status'] == 'Paid') { ?>

                                <span class="status paid">
                                    Paid
                                </span>

                            <?php } else { ?>

                                <span class="status pending">
                                    Pending
                                </span>

                            <?php } ?>

                        </td>

                    </tr>

                    <?php } ?>

                </tbody>

            </table>


        <?php } else { ?>


            <div class="empty">

                <div class="empty-icon">
                    ✓
                </div>

                <h3>
                    No Fines
                </h3>

                <p>
                    You currently don't have any library fines.
                </p>

            </div>


        <?php } ?>

    </div>



    <div class="bottom-nav">

        <a href="dashboard.php">
            ← Back to Dashboard
        </a>

    </div>


</main>


</body>

</html>