<?php 
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include '../includes/db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch beneficiary details
    $sql = "SELECT * FROM beneficiaries WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $beneficiary = $result->fetch_assoc();
    } else {
        echo "Beneficiary not found";
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $address = $_POST['address'];
    $contact_info = $_POST['contact_info'];
    $needs_assessment = $_POST['needs_assessment'];

    // Update the beneficiary details in the database
    $sql = "UPDATE beneficiaries SET 
            name = '$name', 
            age = $age, 
            address = '$address', 
            contact_info = '$contact_info', 
            needs_assessment = '$needs_assessment' 
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: view_beneficiaries.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Beneficiary</title>
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

    <h2>Edit Beneficiary</h2>

    <!-- Edit Beneficiary Form -->
    <form action="edit_beneficiary.php" method="POST" class="form-container">
        <input type="hidden" name="id" value="<?php echo $beneficiary['id']; ?>">

        <div class="form-group">
            <label>Name:</label>
            <input type="text" name="name" value="<?php echo $beneficiary['name']; ?>" required>
        </div>

        <div class="form-group">
            <label>Age:</label>
            <input type="number" name="age" value="<?php echo $beneficiary['age']; ?>" required>
        </div>

        <div class="form-group">
            <label>Address:</label>
            <input type="text" name="address" value="<?php echo $beneficiary['address']; ?>" required>
        </div>

        <div class="form-group">
            <label>Contact Info:</label>
            <input type="text" name="contact_info" value="<?php echo $beneficiary['contact_info']; ?>" required>
        </div>

        <div class="form-group">
            <label>Needs Assessment:</label>
            <textarea name="needs_assessment" required><?php echo $beneficiary['needs_assessment']; ?></textarea>
        </div>

        <button type="submit" class="button">Update Beneficiary</button>
    </form>
</div>

</body>
</html>
