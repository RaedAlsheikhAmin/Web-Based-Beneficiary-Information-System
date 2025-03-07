<?php 
session_start();
include '../includes/db_connect.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
</head>
<body class="register-body">
    <div class="register-container">
        <h2 class="register-title">Register</h2>
        <form action="process_register.php" method="POST" class="register-form">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label>Role:</label>
                <select name="role" required>
                    <option value="Volunteer">Volunteer</option>
                    <option value="Staff">Staff</option>
                    <option value="Admin">Admin</option>
                </select>
            </div>
            <button type="submit" class="button">Register</button>
        </form>
        <p class="login-link">Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
