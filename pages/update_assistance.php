<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include '../includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $beneficiary_id = $_POST['beneficiary_id'];
    $assistance_type = $_POST['assistance_type'];
    $date_provided = $_POST['date_provided'];
    $quantity = $_POST['quantity'];
    $notes = $_POST['notes'];

    // Update the assistance record in the database
    $sql = "UPDATE assistance_records SET 
            beneficiary_id = $beneficiary_id,
            assistance_type = '$assistance_type', 
            date_provided = '$date_provided', 
            quantity = $quantity, 
            notes = '$notes' 
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: view_assistance.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>
