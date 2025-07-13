<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $amount = $_POST['amount'];
    $purpose = $_POST['purpose'];
    $woman_id = $_SESSION['woman_id'];

    $stmt = $conn->prepare("INSERT INTO loans (woman_id, loan_amount, purpose, status) VALUES (?, ?, ?, 'Pending')");
    $stmt->bind_param("ids", $woman_id, $amount, $purpose);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Loan request submitted!'); window.location.href='women_dashboard.php';</script>";
    } else {
        echo "<script>alert('Failed to submit request.'); window.location.href='women_dashboard.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
