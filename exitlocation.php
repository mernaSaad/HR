<?php
// Start the session
session_start();

// Check if the location_id is set in the session
if (isset($_SESSION['location_id'])) {
    // Unset the location_id from the session
    unset($_SESSION['location_id']);
}

// Redirect to a desired page
header("Location: employeesindex.php");
exit();
