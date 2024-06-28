<?php
// Includes
include_once "main/initial.php";

// Get the employee's code based on the employeeid from the session
$employeeid = $_POST['employeeid'];

// Update the clockout column in the attendance table for the employee with the fetched employeeid
$updateAttendanceQuery = "UPDATE attendance SET clockout = ? WHERE employeeid = ? AND DATE(date) = CURDATE()";
$updateAttendanceStmt = $con->prepare($updateAttendanceQuery);
$clockout = $_POST['clockout'];
$updateAttendanceStmt->execute([$clockout, $employeeid]);

// Update the departures table for the employee with the fetched employeeid and current date
$updateDeparturesQuery = "UPDATE departures SET clockout = ? WHERE employeeid = ? AND DATE(date) = CURDATE()";
$updateDeparturesStmt = $con->prepare($updateDeparturesQuery);
$updateDeparturesStmt->execute([$clockout, $employeeid]);

if ($updateAttendanceStmt->rowCount() > 0 && $updateDeparturesStmt->rowCount() > 0) {
    echo "Clock-OUT recorded successfully";
} else {
    echo "Failed to record Clock-OUT";
}

// Close the database connection
$con = null;
