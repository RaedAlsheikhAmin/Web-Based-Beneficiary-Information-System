<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
</head>
<body>

<div class="container">
    <!-- Dashboard Header -->
    <header class="dashboard-header">
        <h2>Welcome, <?php echo $_SESSION['username']; ?></h2>
        <p>Role: <?php echo $_SESSION['role']; ?></p>
    </header>

    <!-- Navigation Cards -->
    <div class="dashboard-nav">
        <div class="card">
            <a href="register_beneficiary.php" class="card-link">
                <h3>Register Beneficiary</h3>
                <p>Add new beneficiaries to the system.</p>
            </a>
        </div>
        <div class="card">
            <a href="view_beneficiaries.php" class="card-link">
                <h3>View Beneficiaries</h3>
                <p>See the list of all registered beneficiaries.</p>
            </a>
        </div>
        <div class="card">
            <a href="add_assistance.php" class="card-link">
                <h3>Add Assistance Record</h3>
                <p>Record new assistance provided.</p>
            </a>
        </div>
        <div class="card">
            <a href="view_assistance.php" class="card-link">
                <h3>View Assistance Records</h3>
                <p>Check assistance records and details.</p>
            </a>
        </div>
        <div class="card logout-card">
            <a href="logout.php" class="card-link">
                <h3>Logout</h3>
                <p>Log out of the system.</p>
            </a>
        </div>
    </div>
</div>

</body>
</html>
