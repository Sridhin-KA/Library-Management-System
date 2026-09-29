<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../../login.php");
    exit();
}

require_once "../../config/db.php";

$id = $_GET['id'];

/* Check whether author is used by any book */

$check_sql = "SELECT * FROM books WHERE author_id = $id";

$check_result = mysqli_query($conn, $check_sql);

if (mysqli_num_rows($check_result) > 0) {

    echo "<h2>Cannot Delete Author</h2>";

    echo "<p>This author is already assigned to one or more books.</p>";

    echo '<a href="view.php">Back to Authors</a>';

    exit();
}


/* Delete author */

$sql = "DELETE FROM authors WHERE id = $id";

mysqli_query($conn, $sql);

header("Location: view.php");

exit();

?>