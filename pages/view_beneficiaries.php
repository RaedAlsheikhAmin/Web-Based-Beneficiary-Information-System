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
    <title>View Beneficiaries</title>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
</head>
<body>

<div class="container">
    <!-- Navigation Bar -->
    <div class="navbar">
        <a href="index.php">Dashboard</a>
        <a href="register_beneficiary.php">Register Beneficiary</a>
        <a href="add_assistance.php">Add Assistance </a>
        <a href="view_assistance.php">View Assistance Records</a>
        <a href="view_beneficiaries.php">View Beneficiaries</a>
        <a href="logout.php">Logout</a>
    </div>


    <h2>Beneficiary List</h2>

    <!-- Beneficiaries Table -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Age</th>
                <th>Address</th>
                <th>Contact Info</th>
                <th>Needs Assessment</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch beneficiaries from the database
            $sql = "SELECT * FROM beneficiaries";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["name"] . "</td>";
                    echo "<td>" . $row["age"] . "</td>";
                    echo "<td>" . $row["address"] . "</td>";
                    echo "<td>" . $row["contact_info"] . "</td>";
                    echo "<td>" . $row["needs_assessment"] . "</td>";
                    echo "<td>
                            <a href='edit_beneficiary.php?id=" . $row["id"] . "' class='button'>Edit</a> |
                            <a href='delete_beneficiary.php?id=" . $row["id"] . "' class='delete-button' onclick=\"return confirm('Are you sure you want to delete this beneficiary?')\">Delete</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No beneficiaries found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
