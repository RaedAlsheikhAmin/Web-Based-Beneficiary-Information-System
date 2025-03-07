<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include '../includes/db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the assistance record details
    $sql = "SELECT * FROM assistance_records WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $record = $result->fetch_assoc();
    } else {
        echo "Record not found";
        exit();
    }
}

// Fetch beneficiaries for the dropdown
$beneficiaries_sql = "SELECT id, name FROM beneficiaries";
$beneficiaries_result = $conn->query($beneficiaries_sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Assistance Record</title>
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

    <h2>Edit Assistance Record</h2>

    <!-- Edit Assistance Form -->
    <form action="update_assistance.php" method="POST" class="form-container">
        <input type="hidden" name="id" value="<?php echo $record['id']; ?>">

        <div class="form-group">
            <label>Beneficiary:</label>
            <select name="beneficiary_id" required>
                <?php
                if ($beneficiaries_result->num_rows > 0) {
                    while ($row = $beneficiaries_result->fetch_assoc()) {
                        $selected = ($row['id'] == $record['beneficiary_id']) ? "selected" : "";
                        echo "<option value='" . $row['id'] . "' $selected>" . $row['name'] . "</option>";
                    }
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Assistance Type:</label>
            <input type="text" name="assistance_type" value="<?php echo $record['assistance_type']; ?>" required>
        </div>

        <div class="form-group">
            <label>Date Provided:</label>
            <input type="date" name="date_provided" value="<?php echo $record['date_provided']; ?>" required>
        </div>

        <div class="form-group">
            <label>Quantity:</label>
            <input type="number" name="quantity" value="<?php echo $record['quantity']; ?>" required>
        </div>

        <div class="form-group">
            <label>Notes:</label>
            <textarea name="notes"><?php echo $record['notes']; ?></textarea>
        </div>

        <button type="submit" class="button">Update Record</button>
    </form>
</div>

</body>
</html>
