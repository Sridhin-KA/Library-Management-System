<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>

    <title>Admin Dashboard</title>

</head>

<body>

    <h1>College Library Management System</h1>

    <h2>Admin Dashboard</h2>

    <p>
        Welcome, <?php echo $_SESSION['admin']; ?>
    </p>

    <hr>

    <h3>Library Management</h3>

    <ul>

        <li>
            <a href="students/view.php">
                Manage Students
            </a>
        </li>

        <li>
            <a href="authors/view.php">
                Manage Authors
            </a>
        </li>

        <li>
            <a href="publishers/view.php">
                Manage Publishers
            </a>
        </li>

        <li>
            <a href="books/view.php">
                Manage Books
            </a>
        </li>

        <li>
            <a href="issue.php">
                Issue Book
            </a>
        </li>
        <li>
    <a href="issued_books.php">
        Issued Books
    </a>
</li>

        <li>
            <a href="return.php">
                Return Book
            </a>
        </li>

        <li>
            <a href="fines.php">
                Fines
            </a>
        </li>

    </ul>

    <br>

    <a href="../logout.php">
        Logout
    </a>

</body>
</html>