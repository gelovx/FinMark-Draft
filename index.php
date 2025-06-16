<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch transaction history
try {
    $stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC LIMIT 10");
    $stmt->execute([$_SESSION['user_id']]);
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching transactions: " . $e->getMessage();
}

// Fetch recent inquiries
try {
    $stmt = $pdo->prepare("SELECT * FROM service_inquiries WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
    $stmt->execute([$_SESSION['user_id']]);
    $inquiries = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching inquiries: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FinMark Corporation</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="brand">FinMark Corporation</div>
        <nav>
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
            <a href="transaction.php">New Transaction</a>
            <a href="service_inquiry.php">Service Inquiry</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <div class="tabs">
        <div class="tab active" onclick="showTab('dashboard')">Dashboard</div>
        <div class="tab" onclick="showTab('services')">Our Services</div>
        <div class="tab" onclick="showTab('industries')">Industries</div>
        <div class="tab" onclick="showTab('about')">About Us</div>
    </div>

    <div id="dashboard" class="tab-content active">
        <h2>Dashboard</h2>
        <p>Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>! Manage your transactions and inquiries seamlessly.</p>
        <?php if (isset($error)) echo "<div class='alert'>$error</div>"; ?>
        <div class="card">
            <h3>Recent Transactions</h3>
            <?php if (empty($transactions)): ?>
                <p>No transactions yet. <a href="transaction.php">Record your first transaction</a>.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $tx): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($tx['created_at']); ?></td>
                                <td>$<?php echo number_format($tx['amount'], 2); ?></td>
                                <td><?php echo htmlspecialchars($tx['description']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        <div class="card">
            <h3>Recent Service Inquiries</h3>
            <?php if (empty($inquiries)): ?>
                <p>No inquiries yet. <a href="service_inquiry.php">Submit an inquiry</a>.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Service</th>
                            <th>Industry</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inquiries as $inquiry): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($inquiry['created_at']); ?></td>
                                <td><?php echo htmlspecialchars($inquiry['service_type']); ?></td>
                                <td><?php echo htmlspecialchars($inquiry['industry']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <div id="services" class="tab-content">
        <h2>Our Services</h2>
        <div class="card">
            <h3>Financial Analysis</h3>
            <p>We deliver in-depth financial analysis to assess financial health, identify growth potential, and enhance operational efficiency.</p>
        </div>
        <div class="card">
            <h3>Marketing Analytics</h3>
            <p>Our cutting-edge marketing analytics equip businesses with data-backed strategies to improve customer engagement and maximize ROI.</p>
        </div>
        <div class="card">
            <h3>Business Intelligence</h3>
            <p>We transform raw data into valuable business intelligence with customized dashboards and detailed reports.</p>
        </div>
        <div class="card">
            <h3>Consulting Services for SMEs</h3>
            <p>Tailored consulting solutions designed to drive efficiency, scalability, and strategic growth for small and medium-sized enterprises.</p>
        </div>
        <button onclick="window.location.href='service_inquiry.php'">Inquire Now</button>
    </div>

    <div id="industries" class="tab-content">
        <h2>Industries We Serve</h2>
        <div class="card">
            <h3>Retail Businesses</h3>
            <p>Helping retailers expand market reach and optimize financial strategies.</p>
        </div>
        <div class="card">
            <h3>E-Commerce Companies</h3>
            <p>Supporting online businesses in tracking market trends and enhancing sales strategies.</p>
        </div>
        <div class="card">
            <h3>Healthcare Providers</h3>
            <p>Assisting healthcare organizations with financial assessments and business intelligence.</p>
        </div>
        <div class="card">
            <h3>Manufacturing Firms</h3>
            <p>Providing manufacturers with cost-saving insights and operational efficiency strategies.</p>
        </div>
    </div>

    <div id="about" class="tab-content">
        <h2>About Us</h2>
        <p>At FinMark Corporation, we provide comprehensive solutions that empower businesses to thrive in today’s competitive market.</p>
        <button onclick="toggleCollapse()">Read More</button>
        <div id="aboutMore" style="display: none; margin-top: 10px;">
            <p>By combining financial expertise, advanced analytics, and strategic insights, we help companies unlock new opportunities, optimize resources, and drive sustainable growth.</p>
        </div>
    </div>

    <footer>
        <p>© <?php echo date('Y'); ?> FinMark Corporation. All rights reserved.</p>
    </footer>

    <script src="fun.js"></script>
</body>
</html>