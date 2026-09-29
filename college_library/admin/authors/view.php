<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../../login.php");
    exit();
}

require_once "../../config/db.php";

$sql = "SELECT * FROM authors";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Authors</title>
</head>

<body>

<h1>Authors</h1>

<a href="add.php">Add Author</a>

<br><br>

<table border="1" cellpadding="10">

    <tr>
        <th>ID</th>
        <th>Author Name</th>
        <th>Action</th>
    </tr>

    <?php while ($author = mysqli_fetch_assoc($result)) { ?>

        <tr>

            <td><?php echo $author['id']; ?></td>

            <td><?php echo $author['name']; ?></td>

            <td>

                <a href="edit.php?id=<?php echo $author['id']; ?>">
                    Edit
                </a>

                |

                <a
                    href="delete.php?id=<?php echo $author['id']; ?>"
                    onclick="return confirm('Are you sure?')"
                >
                    Delete
                </a>

            </td>

        </tr>

    <?php } ?>

</table>

<br>

<a href="../dashboard.php">Back to Dashboard</a>

</body>
</html>