<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: women_login.php");
    exit();
}

$email = $_SESSION['email'];
$name = $_SESSION['name'] ?? 'User'; // Fallback if name isn't set

// Handle loan submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $loan_amount = $_POST['loan_amount'];
    $description = $_POST['description'];
    $status = 'pending'; // Set status to pending

    $stmt = $conn->prepare("INSERT INTO loans (email, loan_amount, description, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdss", $email, $loan_amount, $description, $status);
    $stmt->execute();
    $stmt->close();
}

// Fetch previous loans
$stmt = $conn->prepare("SELECT loan_amount, description, status, created_at FROM loans WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$loans = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Women Dashboard - SheFund</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: linear-gradient(to right, #ffe5d9, #fad2e1);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            padding: 15px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        .profile img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
        }

        .loan-history, .business-idea-form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            margin-bottom: 20px;
        }

        h3 {
            color: #d63384;
            margin-bottom: 10px;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        ul li {
            background: #ffe5d9;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 5px;
        }

        textarea, input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .btn {
            background-color: #d63384;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn:hover {
            background-color: #b71e6a;
        }

        .logout {
            width: 100%;
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
        }

        .logout form {
            margin-right: 20px;
        }

        .logout button {
            background-color: #ff6b6b;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- Logout Button -->
    <div class="logout">
        <form action="logout.php" method="post">
            <button type="submit">Logout</button>
        </form>
    </div>

    <!-- Profile Section -->
    <div class="profile">
        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Profile Icon">
        <h2>Welcome, <?php echo htmlspecialchars($name); ?></h2>
    </div>

    <!-- Loan History Section -->
    <div class="loan-history">
        <h3>Previous Loans</h3>
        <ul>
            <?php if (count($loans) > 0): ?>
                <?php foreach ($loans as $loan): ?>
                    <li>
                        <strong>Amount:</strong> ₹<?php echo $loan['loan_amount']; ?><br>
                        <strong>Description:</strong> <?php echo htmlspecialchars($loan['description']); ?><br>
                        <strong>Status:</strong> <?php echo $loan['status'] ?: 'Pending'; ?><br>
                        <small><strong>Applied On:</strong> <?php echo $loan['created_at']; ?></small>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>No previous loans found.</li>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Business Idea Submission -->
    <div class="business-idea-form">
        <h3>Submit Your Business Idea</h3>
        <form method="POST">
            <input type="number" name="loan_amount" placeholder="Enter loan amount (e.g. 5000)" required>
            <textarea name="description" placeholder="Describe your business idea..." required></textarea>
            <button type="submit" class="btn">Apply for Loan</button>
        </form>
    </div>

</body>
</html>
