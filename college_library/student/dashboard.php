<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
</head>

<body>

<h2>Student Dashboard</h2>

<h3>
    Welcome, <?php echo $_SESSION['student_name']; ?>
</h3>

<ul>

    <li>
        <a href="books.php">
            Available Books
        </a>
    </li>

    <li>
        <a href="my_books.php">
            My Books
        </a>
    </li>

    <li>
        <a href="logout.php">
            Logout
        </a>
    </li>

</ul>

</body>
</html>