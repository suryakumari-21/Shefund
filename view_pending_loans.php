<?php
include "db.php";
session_start();
$investor_id = $_SESSION['investor_id'];

$result = $conn->query("SELECT loans.id, women.name, amount, purpose, description 
                        FROM loans 
                        JOIN women ON loans.woman_id = women.id 
                        WHERE loans.status='pending'");

while ($row = $result->fetch_assoc()) {
    echo "<div style='padding:10px; border-bottom:1px solid #ccc'>";
    echo "<strong>" . $row['name'] . "</strong><br>";
    echo "₹" . $row['amount'] . " - " . $row['purpose'] . "<br>";
    echo "<em>" . $row['description'] . "</em><br>";
    echo "<form method='POST' action='approve_loan.php'>
            <input type='hidden' name='loan_id' value='" . $row['id'] . "'>
            <button type='submit' class='btn'>Approve</button>
          </form>";
    echo "</div>";
}
?>
