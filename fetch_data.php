<?php
include "db.php";
session_start();

$woman_id = $_SESSION['woman_id'];
$result = $conn->query("SELECT * FROM loans WHERE woman_id=$woman_id");

while ($row = $result->fetch_assoc()) {
    echo "<p><strong>₹" . $row['amount'] . "</strong> - " . $row['purpose'] . " - <em>Status: " . $row['status'] . "</em></p>";
}
?>
