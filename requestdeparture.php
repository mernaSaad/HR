<?php
// Connect to your database
include_once "main/initial.php";

// Get the employee ID from the session
$employeeid = $_SESSION['employeeid'] ?? null;

if (!$employeeid) {
    // If employee ID is not found in the session, return an error
    http_response_code(400);
    echo json_encode(['error' => 'Employee ID not found in session']);
    exit;
}

// Fetch employee data from the employees table
$stmt = $con->prepare('SELECT name, code, position FROM employees WHERE id = ?');
$stmt->execute([$employeeid]);
$employeeData = $stmt->fetch(PDO::FETCH_ASSOC);

if ($employeeData) {
    $name = $employeeData['name'];
    $code = $employeeData['code'];
    $position = $employeeData['position'];

    // Get the current date and day
    $date = date('Y-m-d');
    $day = date('l');

// Fetch clock-in information from the attendance table for the current date
$stmt = $con->prepare('SELECT clockin FROM attendance WHERE employeeid = :employeeid AND DATE(clockin) = :date');
$stmt->bindParam(':employeeid', $employeeid, PDO::PARAM_INT);
$stmt->bindParam(':date', $date);
$stmt->execute();
$attendanceData = $stmt->fetch(PDO::FETCH_ASSOC);

// If clock-in information is found, use it; otherwise, set clockin, clockout, and workhour to NULL
if ($attendanceData) {
    $clockin = $attendanceData['clockin'];
    $clockout = null; // Assuming clockout and workhour should be NULL initially
    $workhour = null;
} else {
    $clockin = null;
    $clockout = null;
    $workhour = null;
}

    // Get the reason from the request
    $reason = $_POST['reason'] ?? null;

    if (!$reason) {
        // If reason is missing, return an error
        http_response_code(400);
        echo json_encode(['error' => 'Reason is required']);
        exit;
    }

    // Get location from session
    $location = $_SESSION['location_id'] ?? null;

    if (!$location) {
        // If location is not found in the session, return an error
        http_response_code(400);
        echo json_encode(['error' => 'Location ID not found in session']);
        exit;
    }

    // Insert the data into the departures table
    $query = "INSERT INTO departures (employeeid, name, code, position, date, day, clockin, clockout, workhour, reason, location, status) 
        VALUES (:employeeid, :name, :code, :position, :date, :day, :clockin, :clockout, :workhour, :reason, :location, NULL)";
    $stmt = $con->prepare($query);
    $stmt->bindParam(':employeeid', $employeeid, PDO::PARAM_INT);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':code', $code);
    $stmt->bindParam(':position', $position);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':day', $day);
    $stmt->bindParam(':clockin', $clockin);
    $stmt->bindParam(':clockout', $clockout);
    $stmt->bindParam(':workhour', $workhour);
    $stmt->bindParam(':reason', $reason);
    $stmt->bindParam(':location', $location);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        http_response_code(200);
        echo json_encode(['message' => 'Request for early departure recorded successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to record request for early departure']);
        var_dump($stmt->errorInfo()); // Display any error information
    }
    
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch employee data']);
}
?>
