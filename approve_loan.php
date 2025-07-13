<?php
session_start();
include "db.php";

if (!isset($_SESSION['investor_email'])) {
    header("Location: investor_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['loan_id'])) {
    $loan_id = $_POST['loan_id'];
    $investor_email = $_SESSION['investor_email'];

    $stmt = $conn->prepare("UPDATE investor_loans SET status = 'Approved', investor_email = ? WHERE id = ?");
    $stmt->bind_param("si", $investor_email, $loan_id);
    
    if ($stmt->execute()) {
        header("Location: investor_dashboard.php");
        exit();
    } else {
        echo "Error updating loan.";
    }
}
?>
