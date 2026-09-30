<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}

include("../config/db.php");

// Total students
$student_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM students"
);

$students = mysqli_fetch_assoc($student_result)['total'];


// Total books
$book_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM books"
);

$books = mysqli_fetch_assoc($book_result)['total'];


// Currently issued books
$issued_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM issue_books
     LEFT JOIN return_books
     ON issue_books.id = return_books.issue_id
     WHERE return_books.id IS NULL"
);

$issued = mysqli_fetch_assoc($issued_result)['total'];


// Pending requests
$request_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM book_requests
     WHERE status = 'Pending'"
);

$requests = mysqli_fetch_assoc($request_result)['total'];


// Pending fines
$fine_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM fines
     WHERE status = 'Pending'"
);

$fines = mysqli_fetch_assoc($fine_result)['total'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Admin Dashboard | College Library</title>

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

            background: #f5b942;
            color: #0b1120;

            border-radius: 10px;

            display: flex;
            align-items: center;
            justify-content: center;

            font-weight: bold;
            font-size: 18px;
        }

        .brand h2 {
            font-size: 19px;
        }

        .admin-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .admin-badge {
            color: #f5b942;
            font-size: 13px;
            font-weight: 600;
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


        /* ================= MAIN ================= */

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


        /* ================= STAT CARDS ================= */

        .stats {
            display: grid;

            grid-template-columns:
                repeat(5, 1fr);

            gap: 18px;

            margin-bottom: 40px;
        }

        .stat-card {
            background: #111827;

            border: 1px solid #243044;

            border-radius: 15px;

            padding: 22px;

            transition: 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            border-color: #3a475c;
        }

        .stat-title {
            color: #94a3b8;

            font-size: 13px;

            margin-bottom: 12px;
        }

        .stat-number {
            font-size: 30px;
            font-weight: bold;

            color: #ffffff;
        }

        .stat-description {
            color: #64748b;

            font-size: 12px;

            margin-top: 8px;
        }

        .highlight {
            color: #f5b942;
        }


        /* ================= MANAGEMENT ================= */

        .section-title {
            margin-bottom: 18px;
        }

        .section-title h2 {
            font-size: 20px;
        }

        .section-title p {
            color: #64748b;
            font-size: 13px;

            margin-top: 5px;
        }

        .management-grid {
            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 18px;
        }

        .management-card {
            background: #111827;

            border: 1px solid #243044;

            border-radius: 15px;

            padding: 22px;

            text-decoration: none;

            color: white;

            transition: 0.2s;
        }

        .management-card:hover {
            transform: translateY(-3px);

            border-color: #f5b942;

            background: #131d2d;
        }

        .management-icon {
            width: 42px;
            height: 42px;

            background: #172033;

            color: #f5b942;

            border-radius: 10px;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 18px;

            margin-bottom: 15px;
        }

        .management-card h3 {
            font-size: 16px;

            margin-bottom: 6px;
        }

        .management-card p {
            color: #64748b;

            font-size: 13px;
        }


        /* ================= QUICK ACTIONS ================= */

        .quick-actions {
            margin-top: 40px;

            background: #111827;

            border: 1px solid #243044;

            border-radius: 15px;

            padding: 25px;
        }

        .quick-actions h2 {
            font-size: 18px;
            margin-bottom: 18px;
        }

        .action-links {
            display: flex;

            flex-wrap: wrap;

            gap: 12px;
        }

        .action {
            padding: 11px 17px;

            border-radius: 8px;

            text-decoration: none;

            font-size: 13px;

            font-weight: 600;

            transition: 0.2s;
        }

        .primary {
            background: #f5b942;
            color: #0b1120;
        }

        .primary:hover {
            background: #ffd166;
        }

        .secondary {
            background: #172033;

            color: #cbd5e1;

            border: 1px solid #334155;
        }

        .secondary:hover {
            border-color: #f5b942;
            color: #f5b942;
        }


        /* ================= RESPONSIVE ================= */

        @media (max-width: 1000px) {

            .stats {
                grid-template-columns:
                    repeat(3, 1fr);
            }

            .management-grid {
                grid-template-columns:
                    repeat(2, 1fr);
            }

        }


        @media (max-width: 650px) {

            .navbar {
                padding: 0 20px;
            }

            .brand h2 {
                font-size: 16px;
            }

            .admin-badge {
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
                grid-template-columns: 1fr 1fr;
            }

            .management-grid {
                grid-template-columns: 1fr;
            }

        }


        @media (max-width: 400px) {

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


    <div class="admin-info">

        <span class="admin-badge">
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


    <!-- Header -->

    <div class="page-header">

        <h1>Admin Dashboard</h1>

        <p>
            Manage books, students, requests and library activities.
        </p>

    </div>


    <!-- ================= STATISTICS ================= -->

    <div class="stats">


        <div class="stat-card">

            <div class="stat-title">
                Total Students
            </div>

            <div class="stat-number">
                <?php echo $students; ?>
            </div>

            <div class="stat-description">
                Registered students
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-title">
                Total Books
            </div>

            <div class="stat-number">
                <?php echo $books; ?>
            </div>

            <div class="stat-description">
                Books in library
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-title">
                Issued Books
            </div>

            <div class="stat-number highlight">
                <?php echo $issued; ?>
            </div>

            <div class="stat-description">
                Currently issued
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-title">
                Pending Requests
            </div>

            <div class="stat-number highlight">
                <?php echo $requests; ?>
            </div>

            <div class="stat-description">
                Awaiting approval
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-title">
                Pending Fines
            </div>

            <div class="stat-number highlight">
                <?php echo $fines; ?>
            </div>

            <div class="stat-description">
                Fines awaiting payment
            </div>

        </div>


    </div>


    <!-- ================= MANAGEMENT ================= -->

    <div class="section-title">

        <h2>Library Management</h2>

        <p>
            Manage the main components of the library system.
        </p>

    </div>


    <div class="management-grid">


        <a
            href="students/view.php"
            class="management-card"
        >

            <div class="management-icon">
                S
            </div>

            <h3>Students</h3>

            <p>
                Add, edit and manage student accounts.
            </p>

        </a>


        <a
            href="authors/view.php"
            class="management-card"
        >

            <div class="management-icon">
                A
            </div>

            <h3>Authors</h3>

            <p>
                Manage book authors.
            </p>

        </a>


        <a
            href="publishers/view.php"
            class="management-card"
        >

            <div class="management-icon">
                P
            </div>

            <h3>Publishers</h3>

            <p>
                Manage book publishers.
            </p>

        </a>


        <a
            href="books/view.php"
            class="management-card"
        >

            <div class="management-icon">
                B
            </div>

            <h3>Books</h3>

            <p>
                Add and manage library books.
            </p>

        </a>


        <a
            href="book_requests.php"
            class="management-card"
        >

            <div class="management-icon">
                R
            </div>

            <h3>Book Requests</h3>

            <p>
                Review and manage student requests.
            </p>

        </a>


        <a
            href="issue.php"
            class="management-card"
        >

            <div class="management-icon">
                I
            </div>

            <h3>Issue Book</h3>

            <p>
                Issue books directly to students.
            </p>

        </a>


        <a
            href="issued_books.php"
            class="management-card"
        >

            <div class="management-icon">
                📖
            </div>

            <h3>Issued Books</h3>

            <p>
                View currently issued books.
            </p>

        </a>


        <a
            href="return.php"
            class="management-card"
        >

            <div class="management-icon">
                ↩
            </div>

            <h3>Return Book</h3>

            <p>
                Process returned books and fines.
            </p>

        </a>


        <a
            href="fines.php"
            class="management-card"
        >

            <div class="management-icon">
                ₹
            </div>

            <h3>Fines</h3>

            <p>
                View and manage student fines.
            </p>

        </a>


    </div>


    <!-- ================= QUICK ACTIONS ================= -->

    <div class="quick-actions">

        <h2>Quick Actions</h2>

        <div class="action-links">

            <a
                href="books/add.php"
                class="action primary"
            >
                + Add Book
            </a>

            <a
                href="students/add.php"
                class="action secondary"
            >
                + Add Student
            </a>

            <a
                href="authors/add.php"
                class="action secondary"
            >
                + Add Author
            </a>

            <a
                href="publishers/add.php"
                class="action secondary"
            >
                + Add Publisher
            </a>

            <a
                href="issue.php"
                class="action secondary"
            >
                Issue Book
            </a>

        </div>

    </div>


</div>

</body>

</html>