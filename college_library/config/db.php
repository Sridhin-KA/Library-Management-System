<?php

$host = "localhost";
$username = "library_user";
$password = "library123";
$database = "college_library";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

?>