<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include '../includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $beneficiary_id = $_POST['beneficiary_id'];
    $assistance_type = $_POST['assistance_type'];
    $date_provided = $_POST['date_provided'];
    $quantity = $_POST['quantity'];
    $notes = $_POST['notes'];

    // Insert assistance record into the database
    $sql = "INSERT INTO assistance_records (beneficiary_id, assistance_type, date_provided, quantity, notes) 
            VALUES ($beneficiary_id, '$assistance_type', '$date_provided', $quantity, '$notes')";

    if ($conn->query($sql) === TRUE) {
        echo "Assistance record added successfully! <a href='view_assistance.php'>View Assistance Records</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
