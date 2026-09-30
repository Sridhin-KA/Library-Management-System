<?php
require_once "config/db.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>College Library Management System</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #0f172a;
            color: white;
            min-height: 100vh;
        }

        .navbar {
            height: 75px;
            padding: 0 7%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .logo {
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .logo span {
            color: #f59e0b;
        }

        .nav-links {
            display: flex;
            gap: 12px;
        }

        .nav-links a {
            text-decoration: none;
            color: white;
            padding: 10px 18px;
            border-radius: 7px;
            transition: 0.3s;
        }

        .nav-links a:hover {
            background: rgba(255,255,255,0.1);
        }

        .nav-links .admin-btn {
            background: #f59e0b;
            color: #111827;
            font-weight: bold;
        }

        .hero {
            min-height: calc(100vh - 75px);
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 40px 20px;
        }

        .hero-content {
            max-width: 850px;
        }

        .badge {
            display: inline-block;
            padding: 8px 16px;
            border: 1px solid rgba(245,158,11,0.4);
            color: #fbbf24;
            border-radius: 30px;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .hero h1 {
            font-size: clamp(40px, 6vw, 72px);
            line-height: 1.05;
            margin-bottom: 22px;
        }

        .hero h1 span {
            color: #f59e0b;
        }

        .hero p {
            color: #94a3b8;
            font-size: 18px;
            line-height: 1.7;
            max-width: 650px;
            margin: 0 auto 35px;
        }

        .buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn {
            text-decoration: none;
            padding: 14px 28px;
            border-radius: 8px;
            font-weight: bold;
            transition: 0.3s;
        }

        .student-btn {
            background: #f59e0b;
            color: #111827;
        }

        .student-btn:hover {
            background: #fbbf24;
            transform: translateY(-2px);
        }

        .register-btn {
            border: 1px solid #475569;
            color: white;
        }

        .register-btn:hover {
            background: #1e293b;
            transform: translateY(-2px);
        }

        .features {
            margin-top: 55px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .feature {
            padding: 20px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 12px;
        }

        .feature h3 {
            margin-bottom: 8px;
            font-size: 16px;
        }

        .feature p {
            font-size: 13px;
            margin: 0;
            color: #94a3b8;
        }

        @media (max-width: 700px) {

            .navbar {
                padding: 0 20px;
            }

            .nav-links a {
                padding: 8px 10px;
                font-size: 13px;
            }

            .hero {
                min-height: auto;
                padding: 80px 20px;
            }

            .features {
                grid-template-columns: 1fr;
            }

        }

    </style>

</head>

<body>

    <!-- Navbar -->

    <nav class="navbar">

        <div class="logo">
            <span>📚</span> College Library
        </div>

        <div class="nav-links">

            <a href="student/login.php">
                Student Login
            </a>

            <a href="login.php" class="admin-btn">
                Admin Login
            </a>

        </div>

    </nav>


    <!-- Hero -->

    <section class="hero">

        <div class="hero-content">

            <div class="badge">
                College Library Management System
            </div>

            <h1>
                Your Library,
                <span>Smarter.</span>
            </h1>

            <p>
                Discover books, request what you need, track your borrowed
                books and stay updated with your library activities —
                all in one place.
            </p>


            <div class="buttons">

                <a href="student/login.php"
                   class="btn student-btn">
                    Student Login
                </a>

                <a href="student/register.php"
                   class="btn register-btn">
                    Create Student Account
                </a>

            </div>


            <div class="features">

                <div class="feature">

                    <h3>📖 Discover Books</h3>

                    <p>
                        Browse the available books in the college library.
                    </p>

                </div>


                <div class="feature">

                    <h3>📋 Request Books</h3>

                    <p>
                        Request books and track your request status.
                    </p>

                </div>


                <div class="feature">

                    <h3>🔔 Track Activities</h3>

                    <p>
                        Keep track of issued books, returns and fines.
                    </p>

                </div>

            </div>

        </div>

    </section>

</body>

</html>