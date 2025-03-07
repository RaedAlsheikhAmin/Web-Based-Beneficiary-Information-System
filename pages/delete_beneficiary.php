<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include '../includes/db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM beneficiaries WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: view_beneficiaries.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>
