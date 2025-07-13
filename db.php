<?php
$conn = new mysqli("localhost", "root", "", "shefund");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
