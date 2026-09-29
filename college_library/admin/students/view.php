<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../../login.php");
    exit();
}

require_once "../../config/db.php";

$sql = "SELECT * FROM students";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html>

<head>
    <title>Students</title>
</head>

<body>

    <h1>Students</h1>

    <a href="add.php">Add Student</a>

    <br><br>

    <table border="1" cellpadding="10">

        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Username</th>
            <th>Department</th>
            <th>Password</th>
            <th>Action</th>
        </tr>

        <?php while ($student = mysqli_fetch_assoc($result)) { ?>

            <tr>

                <td>
                    <?php echo $student['id']; ?>
                </td>

                <td>
                    <?php echo $student['name']; ?>
                </td>

                <td>
                    <?php echo $student['email']; ?>
                </td>

                <td>
                    <?php echo $student['phone']; ?>
                </td>
                <td>
                    <?php echo $student['username']?>
                </td>

                <td>
                    <?php echo $student['department']; ?>
                </td>
                <td>
                    *******
                </td>

                <td>

                    <a href="edit.php?id=<?php echo $student['id']; ?>">
                        Edit
                    </a>

                    |

                    <a href="delete.php?id=<?php echo $student['id']; ?>"
                       onclick="return confirm('Are you sure you want to delete this student?');">
                        Delete
                    </a>

                </td>

            </tr>

        <?php } ?>

    </table>

    <br>

    <a href="../dashboard.php">
        Back to Dashboard
    </a>

</body>

</html>