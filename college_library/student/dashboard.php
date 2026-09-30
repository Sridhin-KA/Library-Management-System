<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Student Dashboard | College Library</title>

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

        .logout {
            text-decoration: none;
            color: #cbd5e1;

            padding: 9px 16px;

            border: 1px solid #334155;
            border-radius: 7px;

            font-size: 14px;

            transition: 0.3s;
        }

        .logout:hover {
            background: #1e293b;
            color: white;
        }


        /* Main */

        .container {
            width: 88%;
            max-width: 1200px;

            margin: 45px auto;
        }


        .welcome {
            margin-bottom: 35px;
        }

        .welcome small {
            color: #64748b;
            font-size: 14px;
        }

        .welcome h1 {
            font-size: 32px;
            margin-top: 7px;
        }

        .welcome h1 span {
            color: #f59e0b;
        }

        .welcome p {
            color: #64748b;
            margin-top: 10px;
        }


        /* Dashboard cards */

        .dashboard-grid {
            display: grid;

            grid-template-columns:
                repeat(4, 1fr);

            gap: 20px;
        }


        .dashboard-card {
            background: white;

            border: 1px solid #e2e8f0;

            border-radius: 14px;

            padding: 28px;

            text-decoration: none;

            color: #0f172a;

            transition: 0.25s;

            box-shadow:
                0 4px 15px rgba(15, 23, 42, 0.04);
        }

        .dashboard-card:hover {
            transform: translateY(-5px);

            border-color: #f59e0b;

            box-shadow:
                0 12px 30px rgba(15, 23, 42, 0.08);
        }


        .card-icon {
            width: 48px;
            height: 48px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 12px;

            background: #fff7ed;

            font-size: 22px;

            margin-bottom: 20px;
        }


        .dashboard-card h3 {
            font-size: 17px;

            margin-bottom: 8px;
        }

        .dashboard-card p {
            color: #64748b;

            font-size: 13px;

            line-height: 1.5;
        }


        .arrow {
            display: block;

            margin-top: 20px;

            color: #f59e0b;

            font-size: 14px;

            font-weight: bold;
        }


        /* Quick information */

        .info-section {
            margin-top: 35px;

            background: #0f172a;

            color: white;

            border-radius: 16px;

            padding: 30px;

            display: flex;

            justify-content: space-between;

            align-items: center;

            gap: 30px;
        }

        .info-section h2 {
            font-size: 21px;

            margin-bottom: 8px;
        }

        .info-section p {
            color: #94a3b8;

            font-size: 14px;
        }

        .browse-btn {
            background: #f59e0b;

            color: #111827;

            text-decoration: none;

            padding: 12px 22px;

            border-radius: 8px;

            font-weight: bold;

            white-space: nowrap;

            transition: 0.3s;
        }

        .browse-btn:hover {
            background: #fbbf24;
        }


        /* Responsive */

        @media (max-width: 900px) {

            .dashboard-grid {
                grid-template-columns:
                    repeat(2, 1fr);
            }

        }


        @media (max-width: 600px) {

            .navbar {
                padding: 0 20px;
            }

            .container {
                width: 92%;
                margin: 30px auto;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .welcome h1 {
                font-size: 26px;
            }

            .info-section {
                flex-direction: column;
                align-items: flex-start;
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


    <a href="logout.php" class="logout">
        Logout
    </a>

</nav>



<!-- Dashboard -->

<main class="container">


    <section class="welcome">

        <small>
            STUDENT PORTAL
        </small>

        <h1>
            Welcome,
            <span>
                <?php echo htmlspecialchars($_SESSION['student_name']); ?>
            </span>
        </h1>

        <p>
            Manage your library activities from one place.
        </p>

    </section>



    <section class="dashboard-grid">


        <!-- Available Books -->

        <a href="books.php"
           class="dashboard-card">

            <div class="card-icon">
                📖
            </div>

            <h3>
                Available Books
            </h3>

            <p>
                Browse the books currently available
                in the college library.
            </p>

            <span class="arrow">
                Browse Books →
            </span>

        </a>



        <!-- Requests -->

        <a href="my_requests.php"
           class="dashboard-card">

            <div class="card-icon">
                📋
            </div>

            <h3>
                My Requests
            </h3>

            <p>
                View the books you have requested
                and check their status.
            </p>

            <span class="arrow">
                View Requests →
            </span>

        </a>



        <!-- My Books -->

        <a href="my_books.php"
           class="dashboard-card">

            <div class="card-icon">
                📚
            </div>

            <h3>
                My Books
            </h3>

            <p>
                Track your currently issued and
                previously returned books.
            </p>

            <span class="arrow">
                View My Books →
            </span>

        </a>



        <!-- Fines -->

        <a href="fines.php"
           class="dashboard-card">

            <div class="card-icon">
                💰
            </div>

            <h3>
                My Fines
            </h3>

            <p>
                Check your library fines and
                their payment status.
            </p>

            <span class="arrow">
                View Fines →
            </span>

        </a>


    </section>



    <!-- Bottom Information -->

    <section class="info-section">

        <div>

            <h2>
                Looking for a book?
            </h2>

            <p>
                Explore the library collection and
                request an available book.
            </p>

        </div>


        <a href="books.php"
           class="browse-btn">

            Explore Library

        </a>

    </section>


</main>


</body>

</html>