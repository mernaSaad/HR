<?php
// Connect to your database
include_once "main/initial.php";

// Get the departure ID and status from the POST data
$departureId = $_POST['id'] ?? null;
$status = $_POST['status'] ?? null;

if (!$departureId || !is_numeric($departureId) || !in_array($status, ['2', '1'])) {
    // Check for missing or invalid inputs
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

// Update the status in the departures table
$updateQuery = "UPDATE departures SET status = ? WHERE id = ?";
$stmt = $con->prepare($updateQuery);
$stmt->execute([$status, $departureId]);

if ($stmt->rowCount() > 0) {
    // If the update was successful, return a success message
    http_response_code(200);
    echo json_encode(['message' => 'Status updated successfully']);
} else {
    // If the update failed, return an error message
    http_response_code(500);
    echo json_encode(['error' => 'Failed to update status']);
}

// Close the database connection
$con = null;
?>
