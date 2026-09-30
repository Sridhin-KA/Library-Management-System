<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../../login.php");
    exit();
}

require_once "../../config/db.php";

$id = $_GET['id'];

$sql = "SELECT * FROM publishers WHERE id = $id";

$result = mysqli_query($conn, $sql);

$publisher = mysqli_fetch_assoc($result);

if (!$publisher) {
    header("Location: view.php");
    exit();
}

$error = "";

if (isset($_POST['update_publisher'])) {

    $name = $_POST['name'];

    $sql = "UPDATE publishers
            SET name = '$name'
            WHERE id = $id";

    if (mysqli_query($conn, $sql)) {

        header("Location: view.php");
        exit();

    } else {

        $error = mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Publisher | College Library</title>

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

        /* NAVBAR */

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

            transition: 0.2s;
        }

        .logout:hover {
            border-color: #f5b942;

            color: #f5b942;
        }

        /* CONTAINER */

        .container {
            max-width: 700px;

            margin: 45px auto;

            padding: 0 20px;
        }

        /* HEADER */

        .page-header {
            margin-bottom: 25px;
        }

        .page-header h1 {
            font-size: 30px;

            margin-bottom: 8px;
        }

        .page-header p {
            color: #94a3b8;

            font-size: 14px;
        }

        /* CARD */

        .card {
            background: #111827;

            border: 1px solid #243044;

            border-radius: 16px;

            padding: 30px;

            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.25);
        }

        .card-title {
            font-size: 18px;

            margin-bottom: 25px;
        }

        /* ERROR */

        .error {
            background: rgba(239, 68, 68, 0.10);

            border: 1px solid rgba(239, 68, 68, 0.35);

            color: #fca5a5;

            padding: 12px 15px;

            border-radius: 8px;

            font-size: 13px;

            margin-bottom: 22px;
        }

        /* FORM */

        .form-group {
            display: flex;

            flex-direction: column;
        }

        label {
            font-size: 13px;

            color: #cbd5e1;

            margin-bottom: 8px;

            font-weight: 600;
        }

        input {
            width: 100%;

            padding: 13px 14px;

            background: #0b1120;

            border: 1px solid #334155;

            border-radius: 8px;

            color: #ffffff;

            font-size: 14px;

            outline: none;

            transition: 0.2s;
        }

        input:focus {
            border-color: #f5b942;

            box-shadow:
                0 0 0 2px rgba(245, 185, 66, 0.08);
        }

        /* INFO */

        .info {
            margin-top: 20px;

            padding: 14px 16px;

            background: rgba(245, 185, 66, 0.06);

            border: 1px solid rgba(245, 185, 66, 0.15);

            border-radius: 8px;

            color: #94a3b8;

            font-size: 12px;

            line-height: 1.6;
        }

        .info strong {
            color: #f5b942;
        }

        /* ACTIONS */

        .actions {
            margin-top: 30px;

            padding-top: 22px;

            border-top: 1px solid #243044;

            display: flex;

            justify-content: space-between;

            align-items: center;
        }

        .back-btn {
            text-decoration: none;

            color: #94a3b8;

            border: 1px solid #334155;

            padding: 11px 20px;

            border-radius: 8px;

            font-size: 13px;

            transition: 0.2s;
        }

        .back-btn:hover {
            color: #ffffff;

            border-color: #64748b;
        }

        .update-btn {
            border: none;

            background: #f5b942;

            color: #0b1120;

            padding: 12px 24px;

            border-radius: 8px;

            font-size: 14px;

            font-weight: bold;

            cursor: pointer;

            transition: 0.2s;
        }

        .update-btn:hover {
            background: #ffd166;

            transform: translateY(-1px);
        }

        /* RESPONSIVE */

        @media (max-width: 700px) {

            .navbar {
                padding: 0 20px;
            }

            .admin-badge {
                display: none;
            }

            .container {
                margin: 30px auto;
            }

            .card {
                padding: 22px;
            }

            .actions {
                flex-direction: column-reverse;

                gap: 12px;

                align-items: stretch;
            }

            .update-btn,
            .back-btn {
                width: 100%;

                text-align: center;
            }

        }

    </style>

</head>

<body>


    <!-- NAVBAR -->

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

            <a href="../../logout.php" class="logout">
                Logout
            </a>

        </div>

    </div>


    <!-- CONTENT -->

    <div class="container">


        <div class="page-header">

            <h1>Edit Publisher</h1>

            <p>
                Update the publisher information in the library database.
            </p>

        </div>


        <div class="card">


            <div class="card-title">
                Publisher Information
            </div>


            <?php if ($error != "") { ?>

                <div class="error">

                    <?php echo htmlspecialchars($error); ?>

                </div>

            <?php } ?>


            <form method="POST">


                <div class="form-group">

                    <label>
                        Publisher Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="<?php echo htmlspecialchars($publisher['name']); ?>"
                        required
                    >

                </div>


                <div class="info">

                    <strong>Note:</strong>
                    Updating the publisher name will also update
                    how it appears when managing books.

                </div>


                <div class="actions">


                    <a
                        href="view.php"
                        class="back-btn"
                    >
                        ← Back to Publishers
                    </a>


                    <button
                        type="submit"
                        name="update_publisher"
                        class="update-btn"
                    >
                        ✓ Update Publisher
                    </button>


                </div>


            </form>


        </div>


    </div>

</body>

</html>