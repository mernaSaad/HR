<?php
include_once "main/initial.php";

// Get the employee ID from the session
$employeeid = $_SESSION['employeeid'];

// Fetch employee data from the employees table
$stmt = $con->prepare('SELECT name, code, position FROM employees WHERE id = ?');
$stmt->execute([$employeeid]);
$employeeData = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if employee data was fetched successfully
if ($employeeData) {
    $name = $employeeData['name'];
    $code = $employeeData['code'];
    $position = $employeeData['position'];

    // Get the current date and time
    $date = date('Y-m-d');
    $day = date('l');
    $clockin = date('H:i:s');
    $clockout = NULL;
    $workhour = date('H:i:s');
    $locationid = $_SESSION['location_id'];

    // Insert the data into the attendance table
    $insertStmt = $con->prepare('INSERT INTO attendance (employeeid,name, code, position, date, day, clockin, clockout, workhour, locationid) VALUES (?,?, ?, ?, ?, ?, NOW(), ?, ?, ?)');
    $insertStmt->execute([$employeeid, $name, $code, $position, $date, $day, $clockout, $workhour, $locationid]);

    // Check if the insertion was successful
    if ($insertStmt->rowCount() > 0) {
        echo "Attendance recorded successfully";
    } else {
        echo "Failed to record attendance";
    }
} else {
    echo "Failed to fetch employee data";
}
