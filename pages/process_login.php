<?php
session_start();
include '../includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Retrieve user from the database
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $user['role'];
            if($user['role']=='Admin'){
                header("Location: index.php");}
            else{
            $_SESSION['error'] = "Your priviliges are limited, you can not access the dashboard";
            header("Location: login.php");}
        } else {
            $_SESSION['error'] = "Invalid password. Please try again.";
             header("Location: login.php");

        }
    } else {
        $_SESSION['error'] = "No user found with that username.";
        header("Location: login.php");
    }
}
?>
