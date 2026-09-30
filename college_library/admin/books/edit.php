<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../../login.php");
    exit();
}

require_once "../../config/db.php";

$id = $_GET['id'];

$error = "";


/* Get current book */

$sql = "SELECT * FROM books WHERE id = $id";

$result = mysqli_query($conn, $sql);

$book = mysqli_fetch_assoc($result);

if (!$book) {
    header("Location: view.php");
    exit();
}


/* Get authors */

$author_sql = "SELECT * FROM authors";

$author_result = mysqli_query($conn, $author_sql);


/* Get publishers */

$publisher_sql = "SELECT * FROM publishers";

$publisher_result = mysqli_query($conn, $publisher_sql);


/* Update book */

if (isset($_POST['update_book'])) {

    $title = $_POST['title'];
    $author_id = $_POST['author_id'];
    $publisher_id = $_POST['publisher_id'];
    $quantity = $_POST['quantity'];

    $sql = "UPDATE books SET

            title = '$title',

            author_id = $author_id,

            publisher_id = $publisher_id,

            quantity = $quantity

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

    <title>Edit Book | College Library</title>

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
            max-width: 850px;

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

        .form-grid {
            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 22px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .full {
            grid-column: span 2;
        }

        label {
            font-size: 13px;

            color: #cbd5e1;

            margin-bottom: 8px;

            font-weight: 600;
        }

        input,
        select {
            width: 100%;

            padding: 12px 14px;

            background: #0b1120;

            border: 1px solid #334155;

            border-radius: 8px;

            color: #ffffff;

            font-size: 14px;

            outline: none;

            transition: 0.2s;
        }

        input:focus,
        select:focus {
            border-color: #f5b942;

            box-shadow:
                0 0 0 2px rgba(245, 185, 66, 0.08);
        }

        select {
            cursor: pointer;
        }

        select option {
            background: #111827;
            color: #ffffff;
        }


        /* INFO */

        .info {
            margin-top: 22px;

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

            .form-grid {
                grid-template-columns: 1fr;
            }

            .full {
                grid-column: span 1;
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

            <h1>Edit Book</h1>

            <p>
                Update the book information in the library collection.
            </p>

        </div>


        <div class="card">


            <div class="card-title">
                Book Information
            </div>


            <?php if ($error != "") { ?>

                <div class="error">

                    <?php echo htmlspecialchars($error); ?>

                </div>

            <?php } ?>


            <form method="POST">


                <div class="form-grid">


                    <!-- TITLE -->

                    <div class="form-group full">

                        <label>
                            Book Title
                        </label>

                        <input
                            type="text"
                            name="title"
                            value="<?php echo htmlspecialchars($book['title']); ?>"
                            required
                        >

                    </div>


                    <!-- AUTHOR -->

                    <div class="form-group">

                        <label>
                            Author
                        </label>

                        <select
                            name="author_id"
                            required
                        >

                            <?php while ($author = mysqli_fetch_assoc($author_result)) { ?>

                                <option
                                    value="<?php echo $author['id']; ?>"

                                    <?php
                                    if ($author['id'] == $book['author_id']) {
                                        echo "selected";
                                    }
                                    ?>
                                >

                                    <?php echo htmlspecialchars($author['name']); ?>

                                </option>

                            <?php } ?>

                        </select>

                    </div>


                    <!-- PUBLISHER -->

                    <div class="form-group">

                        <label>
                            Publisher
                        </label>

                        <select
                            name="publisher_id"
                            required
                        >

                            <?php while ($publisher = mysqli_fetch_assoc($publisher_result)) { ?>

                                <option
                                    value="<?php echo $publisher['id']; ?>"

                                    <?php
                                    if ($publisher['id'] == $book['publisher_id']) {
                                        echo "selected";
                                    }
                                    ?>
                                >

                                    <?php echo htmlspecialchars($publisher['name']); ?>

                                </option>

                            <?php } ?>

                        </select>

                    </div>


                    <!-- QUANTITY -->

                    <div class="form-group">

                        <label>
                            Total Quantity
                        </label>

                        <input
                            type="number"
                            name="quantity"
                            min="1"
                            value="<?php echo $book['quantity']; ?>"
                            required
                        >

                    </div>


                    <!-- AVAILABLE -->

                    <div class="form-group">

                        <label>
                            Available Quantity
                        </label>

                        <input
                            type="number"
                            value="<?php echo $book['available_quantity']; ?>"
                            disabled
                        >

                    </div>


                </div>


                <div class="info">

                    <strong>Important:</strong>
                    Available quantity is managed automatically when books
                    are issued and returned.

                </div>


                <div class="actions">


                    <a
                        href="view.php"
                        class="back-btn"
                    >
                        ← Back to Books
                    </a>


                    <button
                        type="submit"
                        name="update_book"
                        class="update-btn"
                    >
                        ✓ Update Book
                    </button>


                </div>


            </form>


        </div>


    </div>

</body>

</html>