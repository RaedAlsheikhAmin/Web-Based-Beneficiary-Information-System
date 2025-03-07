<?php 
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include '../includes/db_connect.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register Beneficiary</title>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
</head>
<body>

<div class="container">
    <!-- Navbar -->
    <div class="navbar">
    <a href="index.php">Dashboard</a>
        <a href="register_beneficiary.php">Register Beneficiary</a>
        <a href="add_assistance.php">Add Assistance </a>
        <a href="view_assistance.php">View Assistance Records</a>
        <a href="view_beneficiaries.php">View Beneficiaries</a>
        <a href="logout.php">Logout</a>
    </div>

    <h2>Register Beneficiary</h2>

    <!-- Register Beneficiary Form -->
    <form action="process_beneficiary.php" method="POST" class="form-container">
        <div class="form-group">
            <label>Name:</label>
            <input type="text" name="name" required>
        </div>

        <div class="form-group">
            <label>Age:</label>
            <input type="number" name="age" required>
        </div>

        <div class="form-group">
            <label>Address:</label>
            <input type="text" name="address" required>
        </div>

        <div class="form-group">
            <label>Contact Info:</label>
            <input type="text" name="contact_info" required>
        </div>

        <div class="form-group">
            <label>Needs Assessment:</label>
            <textarea name="needs_assessment" required></textarea>
        </div>

        <button type="submit" class="button">Register Beneficiary</button>
    </form>
</div>

</body>
</html>
