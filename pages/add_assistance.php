<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include '../includes/db_connect.php';

// Fetch beneficiaries to display in the dropdown
$beneficiaries_sql = "SELECT id, name FROM beneficiaries";
$beneficiaries_result = $conn->query($beneficiaries_sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Assistance Record</title>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
</head>
<body>

<div class="container">
    <div class="navbar">
    <a href="index.php">Dashboard</a>
        <a href="register_beneficiary.php">Register Beneficiary</a>
        <a href="add_assistance.php">Add Assistance </a>
        <a href="view_assistance.php">View Assistance Records</a>
        <a href="view_beneficiaries.php">View Beneficiaries</a>
        <a href="logout.php">Logout</a>
    </div>

    <h2>Add Assistance Record</h2>

    <form action="process_assistance.php" method="POST" class="form-container">
        <div class="form-group">
            <label>Beneficiary:</label>
            <select name="beneficiary_id" required>
                <option value="">Select a beneficiary</option>
                <?php
                // Populate dropdown with beneficiaries
                if ($beneficiaries_result->num_rows > 0) {
                    while ($row = $beneficiaries_result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                    }
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Assistance Type:</label>
            <input type="text" name="assistance_type" required>
        </div>

        <div class="form-group">
            <label>Date Provided:</label>
            <input type="date" name="date_provided" required>
        </div>

        <div class="form-group">
            <label>Quantity:</label>
            <input type="number" name="quantity" required>
        </div>

        <div class="form-group">
            <label>Notes:</label>
            <textarea name="notes"></textarea>
        </div>

        <button type="submit" class="button">Add Record</button>
    </form>
</div>

</body>
</html>
