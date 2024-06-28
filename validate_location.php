<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "main/initial.php";

// Function definition
function isEmployeeInLocation($employeeLat, $employeeLong, $chosenLat, $chosenLong, $radius)
{
    // Earth's radius in kilometers
    $earthRadius = 6371;

    // Convert latitude and longitude from degrees to radians
    $employeeLat = deg2rad($employeeLat);
    $employeeLong = deg2rad($employeeLong);
    $chosenLat = deg2rad($chosenLat);
    $chosenLong = deg2rad($chosenLong);

    // Calculate the distance between the two points using the Haversine formula
    $distance = 2 * $earthRadius * asin(sqrt(
        pow(sin(($chosenLat - $employeeLat) / 2), 2) +
            cos($employeeLat) * cos($chosenLat) * pow(sin(($chosenLong - $employeeLong) / 2), 2)
    ));

    // Check if the distance is within the specified radius
    return $distance <= $radius;
}

// Retrieve the current location's latitude and longitude from the URL
$employeeLat = $_GET['lat'];
$employeeLong = $_GET['long'];

// Retrieve the chosen location's latitude and longitude from the database based on the location ID
$chosenLocationId = $_GET['location_id'];
$chosenLocationQuery = $con->prepare("SELECT * FROM locations WHERE id = :id");
$chosenLocationQuery->bindParam(':id', $chosenLocationId);
$chosenLocationQuery->execute();
$chosenLocation = $chosenLocationQuery->fetch(PDO::FETCH_ASSOC);

if ($chosenLocation) {
    // Get the latitude and longitude of the chosen location
    $chosenLat = $chosenLocation['latitude'];
    $chosenLong = $chosenLocation['longitude'];

    $radius = 50; // Radius in kilometers

    if (isEmployeeInLocation($employeeLat, $employeeLong, $chosenLat, $chosenLong, $radius)) {
        // Employee is within the specified location, add the location ID to the session
        $_SESSION['location_id'] = $chosenLocationId;

        // Redirect to employeesindex.php with the location ID and do=within
        header('Location: employeesindex.php?location_id=' . $chosenLocationId . '&do=within');
        exit();
    } else {
        // Employee is not within the specified location, display a SweetAlert alert and redirect to employeeslocation.php with do=notwithin
        echo "<script>
                console.log('Not within location');
                window.location.href = 'employeeslocation.php?do=notwithin';
            </script>";
        exit();
    }
} else {
    // Chosen location not found, display a SweetAlert alert and redirect to employeeslocation.php with do=notfound
    echo "<script>
            console.log('Chosen location not found');
            window.location.href = 'employeeslocation.php?do=notfound';
        </script>";
    exit();
}
