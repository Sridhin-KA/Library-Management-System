<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../../login.php");
    exit();
}

require_once "../../config/db.php";

$id = $_GET['id'];

$error = "";


/* Get publisher */

$sql = "SELECT * FROM publishers WHERE id = $id";

$result = mysqli_query($conn, $sql);

$publisher = mysqli_fetch_assoc($result);

if (!$publisher) {
    header("Location: view.php");
    exit();
}


/* Delete only after confirmation */

if (isset($_GET['confirm']) && $_GET['confirm'] == "yes") {

    /* Check whether publisher is used by books */

    $check_sql = "SELECT COUNT(*) AS total
                  FROM books
                  WHERE publisher_id = $id";

    $check_result = mysqli_query($conn, $check_sql);

    $check = mysqli_fetch_assoc($check_result);


    if ($check['total'] > 0) {

        $error = "This publisher cannot be deleted because it is assigned to existing books.";

    } else {

        $delete_sql = "DELETE FROM publishers WHERE id = $id";

        if (mysqli_query($conn, $delete_sql)) {

            header("Location: view.php");
            exit();

        } else {

            $error = mysqli_error($conn);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Delete Publisher | College Library</title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;

            background: #0b1120;

            color: #ffffff;

            min-height: 100vh;
        }

        .navbar {
            height: 72px;

            background: #111827;

            border-bottom: 1px solid #243044;

            display: flex;

            align-items: center;

            justify-content: space-between;

            padding: 0 45px;
        }

        .brand {
            display: flex;

            align-items: center;

            gap: 12px;
        }

        .logo {
            width: 40px;

            height: 40px;

            background: #f5b942;

            color: #0b1120;

            border-radius: 10px;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 20px;

            font-weight: bold;
        }

        .brand-text h2 {
            font-size: 18px;

            margin-bottom: 3px;
        }

        .brand-text span {
            font-size: 11px;

            color: #94a3b8;

            letter-spacing: 1px;
        }

        .nav-right {
            display: flex;

            align-items: center;

            gap: 20px;
        }

        .admin-badge {
            color: #f5b942;

            font-size: 13px;

            font-weight: bold;
        }

        .logout {
            text-decoration: none;

            color: #ffffff;

            border: 1px solid #334155;

            padding: 9px 16px;

            border-radius: 8px;

            font-size: 13px;
        }

        .container {
            max-width: 650px;

            margin: 70px auto;

            padding: 0 20px;
        }

        .card {
            background: #111827;

            border: 1px solid #243044;

            border-radius: 16px;

            padding: 35px;

            text-align: center;

            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
        }

        .warning-icon {
            width: 60px;

            height: 60px;

            margin: 0 auto 20px;

            border-radius: 50%;

            background: rgba(239, 68, 68, 0.1);

            border: 1px solid rgba(239, 68, 68, 0.25);

            display: flex;

            align-items: center;

            justify-content: center;

            color: #fca5a5;

            font-size: 25px;

            font-weight: bold;
        }

        h1 {
            font-size: 26px;

            margin-bottom: 10px;
        }

        .subtitle {
            color: #94a3b8;

            font-size: 14px;

            line-height: 1.6;

            margin-bottom: 25px;
        }

        .publisher-box {
            background: #0b1120;

            border: 1px solid #334155;

            border-radius: 10px;

            padding: 15px;

            margin-bottom: 20px;
        }

        .publisher-label {
            display: block;

            color: #64748b;

            font-size: 11px;

            text-transform: uppercase;

            letter-spacing: 0.8px;

            margin-bottom: 6px;
        }

        .publisher-name {
            color: #ffffff;

            font-size: 16px;

            font-weight: bold;
        }

        .error {
            background: rgba(239, 68, 68, 0.1);

            border: 1px solid rgba(239, 68, 68, 0.3);

            color: #fca5a5;

            padding: 14px 16px;

            border-radius: 8px;

            font-size: 13px;

            line-height: 1.5;

            margin-bottom: 20px;
        }

        .info {
            color: #64748b;

            font-size: 12px;

            line-height: 1.6;

            margin-bottom: 25px;
        }

        .actions {
            display: flex;

            justify-content: center;

            gap: 12px;
        }

        .back-btn,
        .delete-btn {
            text-decoration: none;

            padding: 11px 20px;

            border-radius: 8px;

            font-size: 13px;
        }

        .back-btn {
            color: #94a3b8;

            border: 1px solid #334155;
        }

        .back-btn:hover {
            color: #ffffff;

            border-color: #64748b;
        }

        .delete-btn {
            background: #dc2626;

            color: #ffffff;

            font-weight: bold;
        }

        .delete-btn:hover {
            background: #ef4444;
        }

        @media (max-width: 700px) {

            .navbar {
                padding: 0 20px;
            }

            .admin-badge {
                display: none;
            }

            .container {
                margin: 35px auto;
            }

            .card {
                padding: 25px 20px;
            }

            .actions {
                flex-direction: column;
            }

            .back-btn,
            .delete-btn {
                width: 100%;

                text-align: center;
            }

        }

    </style>

</head>

<body>


    <div class="navbar">

        <div class="brand">

            <div class="logo">
                L
            </div>

            <div class="brand-text">

                <h2>College Library</h2>

                <span>ADMIN PANEL</span>

            </div>

        </div>


        <div class="nav-right">

            <span class="admin-badge">
                ADMIN
            </span>

            <a
                href="../../logout.php"
                class="logout"
            >
                Logout
            </a>

        </div>

    </div>


    <div class="container">

        <div class="card">


            <div class="warning-icon">
                !
            </div>


            <h1>
                Delete Publisher
            </h1>


            <p class="subtitle">
                You are about to delete this publisher.
            </p>


            <div class="publisher-box">

                <span class="publisher-label">
                    Publisher
                </span>

                <div class="publisher-name">

                    <?php
                    echo htmlspecialchars($publisher['name']);
                    ?>

                </div>

            </div>


            <?php if ($error != "") { ?>

                <div class="error">

                    <?php
                    echo htmlspecialchars($error);
                    ?>

                </div>

                <p class="info">

                    This publisher is currently associated with one or
                    more books. Remove or update those books before
                    deleting the publisher.

                </p>


                <div class="actions">

                    <a
                        href="view.php"
                        class="back-btn"
                    >
                        ← Back to Publishers
                    </a>

                </div>


            <?php } else { ?>

                <p class="info">

                    This action cannot be undone. Are you sure you
                    want to delete this publisher?

                </p>


                <div class="actions">

                    <a
                        href="view.php"
                        class="back-btn"
                    >
                        Cancel
                    </a>


                    <a
                        href="delete.php?id=<?php echo $id; ?>&confirm=yes"
                        class="delete-btn"
                    >
                        Delete Publisher
                    </a>

                </div>

            <?php } ?>


        </div>

    </div>

</body>

</html>