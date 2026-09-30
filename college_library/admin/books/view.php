<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../../login.php");
    exit();
}

require_once "../../config/db.php";

$sql = "SELECT books.*,
               authors.name AS author_name,
               publishers.name AS publisher_name
        FROM books
        JOIN authors ON books.author_id = authors.id
        JOIN publishers ON books.publisher_id = publishers.id
        ORDER BY books.id DESC";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Books | College Library</title>

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
            max-width: 1250px;

            margin: 45px auto;

            padding: 0 20px;
        }


        /* HEADER */

        .page-header {
            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 25px;
        }

        .page-header h1 {
            font-size: 30px;
            margin-bottom: 7px;
        }

        .page-header p {
            color: #94a3b8;
            font-size: 14px;
        }


        /* ADD BUTTON */

        .add-btn {
            text-decoration: none;

            background: #f5b942;

            color: #0b1120;

            padding: 12px 18px;

            border-radius: 8px;

            font-size: 13px;

            font-weight: bold;

            transition: 0.2s;
        }

        .add-btn:hover {
            background: #ffd166;

            transform: translateY(-1px);
        }


        /* TABLE CARD */

        .table-card {
            background: #111827;

            border: 1px solid #243044;

            border-radius: 16px;

            overflow: hidden;

            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.25);
        }

        .table-wrapper {
            width: 100%;

            overflow-x: auto;
        }

        table {
            width: 100%;

            border-collapse: collapse;

            min-width: 950px;
        }

        thead {
            background: #172033;
        }

        th {
            text-align: left;

            padding: 16px 18px;

            color: #94a3b8;

            font-size: 11px;

            text-transform: uppercase;

            letter-spacing: 0.7px;

            white-space: nowrap;
        }

        td {
            padding: 16px 18px;

            border-top: 1px solid #243044;

            color: #e2e8f0;

            font-size: 13px;

            white-space: nowrap;
        }

        tbody tr {
            transition: 0.2s;
        }

        tbody tr:hover {
            background: #151e2f;
        }


        /* ID */

        .id {
            color: #f5b942;

            font-weight: bold;
        }


        /* TITLE */

        .book-title {
            color: #ffffff;

            font-weight: 600;
        }


        /* AUTHOR / PUBLISHER */

        .muted {
            color: #cbd5e1;
        }


        /* QUANTITY */

        .quantity {
            color: #ffffff;

            font-weight: 600;
        }


        /* AVAILABLE */

        .available {
            display: inline-block;

            padding: 5px 10px;

            border-radius: 20px;

            font-size: 11px;

            font-weight: bold;
        }

        .available.good {
            background: rgba(34, 197, 94, 0.10);

            color: #86efac;

            border: 1px solid rgba(34, 197, 94, 0.20);
        }

        .available.low {
            background: rgba(245, 185, 66, 0.10);

            color: #f5b942;

            border: 1px solid rgba(245, 185, 66, 0.20);
        }

        .available.none {
            background: rgba(239, 68, 68, 0.10);

            color: #fca5a5;

            border: 1px solid rgba(239, 68, 68, 0.20);
        }


        /* ACTIONS */

        .actions {
            display: flex;

            align-items: center;

            gap: 8px;
        }

        .edit-btn,
        .delete-btn {
            text-decoration: none;

            padding: 7px 12px;

            border-radius: 6px;

            font-size: 12px;

            transition: 0.2s;
        }

        .edit-btn {
            color: #f5b942;

            border: 1px solid rgba(245, 185, 66, 0.35);

            background: rgba(245, 185, 66, 0.06);
        }

        .edit-btn:hover {
            background: rgba(245, 185, 66, 0.15);
        }

        .delete-btn {
            color: #fca5a5;

            border: 1px solid rgba(239, 68, 68, 0.30);

            background: rgba(239, 68, 68, 0.06);
        }

        .delete-btn:hover {
            background: rgba(239, 68, 68, 0.15);
        }


        /* EMPTY */

        .empty {
            text-align: center;

            padding: 50px 20px;

            color: #64748b;

            font-size: 14px;
        }


        /* BACK */

        .bottom {
            margin-top: 25px;
        }

        .back-btn {
            text-decoration: none;

            color: #94a3b8;

            border: 1px solid #334155;

            padding: 10px 17px;

            border-radius: 8px;

            font-size: 13px;

            transition: 0.2s;
        }

        .back-btn:hover {
            color: #ffffff;

            border-color: #64748b;
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

            .page-header {
                flex-direction: column;

                align-items: flex-start;

                gap: 20px;
            }

            .add-btn {
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


        <!-- HEADER -->

        <div class="page-header">

            <div>

                <h1>Books</h1>

                <p>
                    Manage books available in the college library.
                </p>

            </div>


            <a href="add.php" class="add-btn">
                + Add Book
            </a>

        </div>


        <!-- TABLE -->

        <div class="table-card">

            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Title</th>

                            <th>Author</th>

                            <th>Publisher</th>

                            <th>Quantity</th>

                            <th>Available</th>

                            <th>Action</th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php if (mysqli_num_rows($result) > 0) { ?>

                        <?php while ($book = mysqli_fetch_assoc($result)) { ?>

                            <tr>


                                <!-- ID -->

                                <td class="id">

                                    #<?php echo $book['id']; ?>

                                </td>


                                <!-- TITLE -->

                                <td class="book-title">

                                    <?php
                                    echo htmlspecialchars($book['title']);
                                    ?>

                                </td>


                                <!-- AUTHOR -->

                                <td class="muted">

                                    <?php
                                    echo htmlspecialchars($book['author_name']);
                                    ?>

                                </td>


                                <!-- PUBLISHER -->

                                <td class="muted">

                                    <?php
                                    echo htmlspecialchars($book['publisher_name']);
                                    ?>

                                </td>


                                <!-- QUANTITY -->

                                <td class="quantity">

                                    <?php
                                    echo $book['quantity'];
                                    ?>

                                </td>


                                <!-- AVAILABLE -->

                                <td>

                                    <?php

                                    $available = $book['available_quantity'];

                                    $quantity = $book['quantity'];

                                    if ($available == 0) {

                                        $class = "none";

                                    } elseif ($available <= ($quantity / 2)) {

                                        $class = "low";

                                    } else {

                                        $class = "good";

                                    }

                                    ?>

                                    <span class="available <?php echo $class; ?>">

                                        <?php echo $available; ?>

                                        available

                                    </span>

                                </td>


                                <!-- ACTION -->

                                <td>

                                    <div class="actions">

                                        <a
                                            href="edit.php?id=<?php echo $book['id']; ?>"
                                            class="edit-btn"
                                        >
                                            Edit
                                        </a>


                                        <a
                                            href="delete.php?id=<?php echo $book['id']; ?>"
                                            class="delete-btn"
                                            onclick="return confirm('Are you sure you want to delete this book?');"
                                        >
                                            Delete
                                        </a>

                                    </div>

                                </td>


                            </tr>

                        <?php } ?>


                    <?php } else { ?>


                        <tr>

                            <td colspan="7">

                                <div class="empty">

                                    No books found.

                                </div>

                            </td>

                        </tr>


                    <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>


        <!-- BACK -->

        <div class="bottom">

            <a href="../dashboard.php" class="back-btn">

                ← Back to Dashboard

            </a>

        </div>


    </div>

</body>

</html>