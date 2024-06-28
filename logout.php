<?php
session_start();

// Check if employeeid or is_admin session variables are set
if (isset($_SESSION['employeeid'])) {
    // Unset all session variables
    session_unset();
    // Destroy the session
    session_destroy();
    // Redirect to employee login page
    header('Location: loggin.php');
    exit();
} elseif (isset($_SESSION['is_admin'])) {
    // Unset all session variables
    session_unset();
    // Destroy the session
    session_destroy();
    // Redirect to admin login page
    header('Location: login.php');
    exit();
} else {
    // If no specific session variable is set, redirect to a default page
    header('Location: index.php');
    exit();
}
?>
