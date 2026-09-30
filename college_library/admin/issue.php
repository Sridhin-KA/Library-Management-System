<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}

require_once "../config/db.php";

$message = "";
$message_type = "";


// ================= ISSUE BOOK =================

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


    if (!$book || $book['available_quantity'] <= 0) {

        $message = "Book is not available.";
        $message_type = "error";

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


            header("Location: issue.php?success=1");
            exit();

        } else {

            $message = "Error: " . mysqli_error($conn);
            $message_type = "error";
        }
    }
}


// Success message after redirect

if (isset($_GET['success'])) {

    $message = "Book issued successfully.";
    $message_type = "success";
}


// ================= GET STUDENTS =================

$student_sql = "SELECT *
                FROM students
                ORDER BY name ASC";

$student_result = mysqli_query($conn, $student_sql);


// ================= GET AVAILABLE BOOKS =================

$book_sql = "SELECT *
             FROM books
             WHERE available_quantity > 0
             ORDER BY title ASC";

$book_result = mysqli_query($conn, $book_sql);


// Count available books

$available_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM books
     WHERE available_quantity > 0"
);

$available_books =
    mysqli_fetch_assoc($available_result)['total'];


// Count students

$student_count_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM students"
);

$total_students =
    mysqli_fetch_assoc($student_count_result)['total'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Issue Book | Admin</title>

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

            max-width: 1100px;

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
                repeat(2, 1fr);

            gap: 18px;

            margin-bottom: 30px;
        }


        .stat-card {
            background: #111827;

            border: 1px solid #243044;

            border-radius: 15px;

            padding: 20px;
        }


        .stat-title {
            color: #94a3b8;

            font-size: 13px;

            margin-bottom: 8px;
        }


        .stat-number {
            color: #f5b942;

            font-size: 27px;

            font-weight: bold;
        }


        /* ================= FORM CARD ================= */

        .form-card {
            background: #111827;

            border: 1px solid #243044;

            border-radius: 16px;

            padding: 32px;

            box-shadow:
                0 15px 40px
                rgba(0, 0, 0, 0.25);
        }


        .form-header {
            margin-bottom: 28px;
        }


        .form-header h2 {
            font-size: 20px;

            margin-bottom: 6px;
        }


        .form-header p {
            color: #64748b;

            font-size: 13px;
        }


        /* ================= FORM ================= */

        .form-group {
            margin-bottom: 22px;
        }


        label {
            display: block;

            margin-bottom: 8px;

            color: #dbe4f0;

            font-size: 14px;

            font-weight: 600;
        }


        select,
        input {
            width: 100%;

            padding: 13px 14px;

            border-radius: 9px;

            border: 1px solid #334155;

            background: #0f172a;

            color: #ffffff;

            font-size: 14px;

            outline: none;

            transition: 0.2s;
        }


        select:focus,
        input:focus {
            border-color: #f5b942;

            box-shadow:
                0 0 0 3px
                rgba(245, 185, 66, 0.1);
        }


        select option {
            background: #111827;

            color: #ffffff;
        }


        /* ================= DATE GRID ================= */

        .date-grid {
            display: grid;

            grid-template-columns:
                1fr 1fr;

            gap: 18px;
        }


        /* ================= ISSUE BUTTON ================= */

        .issue-btn {
            width: 100%;

            padding: 14px;

            border: none;

            border-radius: 9px;

            background: #f5b942;

            color: #0b1120;

            font-size: 15px;

            font-weight: bold;

            cursor: pointer;

            transition: 0.2s;

            margin-top: 5px;
        }


        .issue-btn:hover {
            background: #ffd166;

            transform: translateY(-1px);
        }


        /* ================= INFO ================= */

        .info-box {
            margin-top: 22px;

            padding: 14px 16px;

            border-radius: 9px;

            background: #172033;

            border: 1px solid #243044;

            color: #94a3b8;

            font-size: 13px;

            line-height: 1.6;
        }


        .info-box strong {
            color: #f5b942;
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

        @media (max-width: 650px) {

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


            .date-grid {
                grid-template-columns: 1fr;
            }


            .form-card {
                padding: 22px;
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

        <h1>Issue Book</h1>

        <p>
            Issue a library book directly to a registered student.
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
                Registered Students
            </div>

            <div class="stat-number">
                <?php echo $total_students; ?>
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-title">
                Books Currently Available
            </div>

            <div class="stat-number">
                <?php echo $available_books; ?>
            </div>

        </div>


    </div>


    <!-- ================= FORM ================= -->

    <div class="form-card">


        <div class="form-header">

            <h2>Book Issue Details</h2>

            <p>
                Select a student, book and issue period.
            </p>

        </div>


        <form method="POST">


            <!-- Student -->

            <div class="form-group">

                <label>
                    Student
                </label>


                <select
                    name="student_id"
                    required
                >

                    <option value="">
                        Select Student
                    </option>


                    <?php while (
                        $student =
                        mysqli_fetch_assoc($student_result)
                    ) { ?>

                        <option
                            value="<?php
                            echo $student['id'];
                            ?>"
                        >

                            <?php
                            echo htmlspecialchars(
                                $student['name']
                            );
                            ?>

                        </option>

                    <?php } ?>

                </select>

            </div>


            <!-- Book -->

            <div class="form-group">

                <label>
                    Book
                </label>


                <select
                    name="book_id"
                    required
                >

                    <option value="">
                        Select Book
                    </option>


                    <?php while (
                        $book =
                        mysqli_fetch_assoc($book_result)
                    ) { ?>

                        <option
                            value="<?php
                            echo $book['id'];
                            ?>"
                        >

                            <?php
                            echo htmlspecialchars(
                                $book['title']
                            );
                            ?>

                            — Available:
                            <?php
                            echo $book[
                                'available_quantity'
                            ];
                            ?>

                        </option>

                    <?php } ?>

                </select>

            </div>


            <!-- Dates -->

            <div class="date-grid">


                <div class="form-group">

                    <label>
                        Issue Date
                    </label>

                    <input
                        type="date"
                        name="issue_date"
                        value="<?php
                        echo date('Y-m-d');
                        ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>
                        Due Date
                    </label>

                    <input
                        type="date"
                        name="due_date"
                        required
                    >

                </div>


            </div>


            <!-- Button -->

            <button
                type="submit"
                name="issue_book"
                class="issue-btn"
            >
                Issue Book
            </button>


        </form>


        <div class="info-box">

            <strong>Library Policy:</strong>

            Make sure the selected book has available copies
            before issuing. The available quantity will
            automatically decrease after a successful issue.

        </div>


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