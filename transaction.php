<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount = trim($_POST['amount']);
    $description = trim($_POST['description']);
    $user_id = $_SESSION['user_id'];

    try {
        $stmt = $pdo->prepare("INSERT INTO transactions (user_id, amount, description) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $amount, $description]);
        $success = "Transaction recorded successfully!";
    } catch (PDOException $e) {
        $error = "Transaction failed: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Transaction - FinMark Corporation</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="brand">FinMark Corporation</div>
        <nav>
            <a href="index.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <div class="container">
        <h2>New Transaction</h2>
        <?php if (isset($success)) echo "<div class='alert success'>$success</div>"; ?>
        <?php if (isset($error)) echo "<div class='alert'>$error</div>"; ?>
        <form method="POST" action="">
            <label>Amount ($)</label>
            <input type="number" name="amount" step="0.01" required>
            <label>Description</label>
            <input type="text" name="description" required>
            <button type="submit">Record Transaction</button>
        </form>
    </div>
</body>
</html>