<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include '../includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $address = $_POST['address'];
    $contact_info = $_POST['contact_info'];
    $needs_assessment = $_POST['needs_assessment'];

    $sql = "INSERT INTO beneficiaries (name, age, address, contact_info, needs_assessment) VALUES ('$name', $age, '$address', '$contact_info', '$needs_assessment')";

    if ($conn->query($sql) === TRUE) {
        echo "New beneficiary added successfully";
        header("Location: view_beneficiaries.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
