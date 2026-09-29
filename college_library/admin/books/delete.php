<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../../login.php");
    exit();
}

require_once "../../config/db.php";

$id = $_GET['id'];

$sql = "DELETE FROM books WHERE id = $id";

if (mysqli_query($conn, $sql)) {

    header("Location: view.php");
    exit();

} else {

    echo "<h2>Cannot Delete Book</h2>";

    echo "<p>This book may already be associated with issue records.</p>";

    echo '<a href="view.php">Back to Books</a>';
}

?>