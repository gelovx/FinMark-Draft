<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $service_type = trim($_POST['service_type']);
    $industry = trim($_POST['industry']);
    $message = trim($_POST['message']);
    $user_id = $_SESSION['user_id'];

    try {
        $stmt = $pdo->prepare("INSERT INTO service_inquiries (user_id, service_type, industry, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $service_type, $industry, $message]);
        $success = "Inquiry submitted successfully!";
    } catch (PDOException $e) {
        $error = "Inquiry failed: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Service Inquiry - FinMark Corporation</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
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
        <h2>Service Inquiry</h2>
        <?php if (isset($success)) echo "<div class='alert success'>$success</div>"; ?>
        <?php if (isset($error)) echo "<div class='alert'>$error</div>"; ?>
        <form method="POST" action="">
            <label>Service Type</label>
            <select name="service_type" required>
                <option value="Financial Analysis">Financial Analysis</option>
                <option value="Marketing Analytics">Marketing Analytics</option>
                <option value="Business Intelligence">Business Intelligence</option>
                <option value="Consulting Services">Consulting Services</option>
            </select>
            <label>Industry</label>
            <select name="industry" required>
                <option value="Retail">Retail</option>
                <option value="E-Commerce">E-Commerce</option>
                <option value="Healthcare">Healthcare</option>
                <option value="Manufacturing">Manufacturing</option>
                <option value="Other">Other</option>
            </select>
            <label>Message</label>
            <textarea name="message" rows="5"></textarea>
            <button type="submit">Submit Inquiry</button>
        </form>
    </div>
</body>
</html>