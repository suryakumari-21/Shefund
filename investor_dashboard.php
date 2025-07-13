<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: investor_login.php");
    exit();
}

$investor_email = $_SESSION['email'];
$name = $_SESSION['name'] ?? 'Investor';

// Handle loan approval
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['approve'])) {
    $loan_id = $_POST['loan_id'];
    $status = 'approved';

    $stmt = $conn->prepare("UPDATE loans SET status = ?, investor_email = ? WHERE id = ?");
    $stmt->bind_param("ssi", $status, $investor_email, $loan_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch pending loans
$pending_loans = [];
$result = $conn->query("SELECT * FROM loans WHERE status = 'pending'");
if ($result) {
    $pending_loans = $result->fetch_all(MYSQLI_ASSOC);
}

// Fetch loans funded by this investor
$funded_loans = [];
$stmt = $conn->prepare("SELECT * FROM loans WHERE investor_email = ?");
$stmt->bind_param("s", $investor_email);
$stmt->execute();
$result = $stmt->get_result();
$funded_loans = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Investor Dashboard - SheFund</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #f8d0e8, #fce4ec);
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logout {
            float: right;
        }

        .section {
            background: white;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }

        h2 {
            color: #d63384;
            margin-bottom: 20px;
        }

        .loan {
            background: #fcd3e1;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .loan p {
            margin: 5px 0;
        }

        .btn {
            background-color: #d63384;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 10px;
        }

        .btn:hover {
            background-color: #ad1457;
        }

        .logout button {
            background-color: #ff6b6b;
            border: none;
            padding: 10px 15px;
            color: white;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <div class="logout">
        <form method="post" action="logout.php">
            <button type="submit">Logout</button>
        </form>
    </div>

    <div class="header">
        <h1>Welcome, <?php echo htmlspecialchars($name); ?></h1>
    </div>

    <!-- Pending Loan Applications -->
    <div class="section">
        <h2>Pending Loan Applications</h2>
        <?php if (count($pending_loans) > 0): ?>
            <?php foreach ($pending_loans as $loan): ?>
                <div class="loan">
                    <p><strong>Applicant Email:</strong> <?php echo htmlspecialchars($loan['email']); ?></p>
                    <p><strong>Loan Amount:</strong> ₹<?php echo $loan['loan_amount']; ?></p>
                    <p><strong>Description:</strong> <?php echo htmlspecialchars($loan['description']); ?></p>
                    <p><strong>Date Applied:</strong> <?php echo $loan['created_at']; ?></p>
                    <form method="post">
                        <input type="hidden" name="loan_id" value="<?php echo $loan['id']; ?>">
                        <button type="submit" name="approve" class="btn">Approve</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No pending loans available for approval.</p>
        <?php endif; ?>
    </div>

    <!-- Funded Loans by Investor -->
    <div class="section">
        <h2>Loans You Approved</h2>
        <?php if (count($funded_loans) > 0): ?>
            <?php foreach ($funded_loans as $loan): ?>
                <div class="loan">
                    <p><strong>Applicant Email:</strong> <?php echo htmlspecialchars($loan['email']); ?></p>
                    <p><strong>Loan Amount:</strong> ₹<?php echo $loan['loan_amount']; ?></p>
                    <p><strong>Description:</strong> <?php echo htmlspecialchars($loan['description']); ?></p>
                    <p><strong>Status:</strong> <?php echo $loan['status']; ?></p>
                    <p><strong>Date Approved:</strong> <?php echo $loan['created_at']; ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>You haven’t approved any loans yet.</p>
        <?php endif; ?>
    </div>

</body>
</html>
