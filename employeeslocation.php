<?php
// includes
include_once "main/initial.php";
include_once "includes/head.php";
include_once "includes/nav.php";
include_once "includes/sidebar.php";

// Check if a location is already chosen
if (isset($_SESSION['location_id'])) {
    // Redirect to employeesindex.php
    header("Location: employeesindex.php");
    exit();
}

// Retrieve data from the "locations" table
$query = "SELECT * FROM locations";
$stmt = $con->query($query);
$locations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php
if (isset($_GET['do'])) {
    if ($_GET['do'] == "notwithin") {
        echo "<script>
                Swal.fire({
                icon: 'error',
                title: 'You are not within the location!',
                text:'',
                });    
            </script>";
    }
}
if (isset($_GET['do'])) {
    if ($_GET['do'] == "notfound") {
        echo "<script>
                Swal.fire({
                icon: 'error',
                title: 'Chosen location not found!',
                text:'',
                });    
            </script>";
    }
}
if (isset($_GET['do'])) {
    if ($_GET['do'] == "nochosen") {
        echo "<script>
                Swal.fire({
                icon: 'error',
                title: 'No Chosen location!',
                text:'',
                });    
            </script>";
    }
}
?>
<div class="app-content main-content">
    <div class="side-app main-container">
        <!--Page header-->
        <div class="page-header d-xl-flex d-block">
            <div class="page-leftheader">
                <div class="page-title">Employees<span class="font-weight-normal text-muted ms-2"> Dashboard</span></div>
            </div>
        </div>
        <!--End Page header-->
        <div class="container mt-5">
            <div class="row">
                <div class="col">
                    <div class="row">
                        <?php foreach ($locations as $location) : ?>
                            <div class="col-4">
                                <div class="card">
                                    <a href="#" onclick="getLocation(<?= $location['id'] ?>)">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-8">
                                                    <div class="mt-0 text-start"> <span class="fs-14 font-weight-semibold"> </span>
                                                        <h3 class="mb-0 mt-1 mb-2"><?php echo $location['name']; ?></h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>
<script>
    function getLocation(location_id) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var employeeLat = position.coords.latitude;
                var employeeLong = position.coords.longitude;

                // Send the current location to the server for validation
                window.location.href = 'validate_location.php?location_id=' + location_id + '&lat=' + employeeLat + '&long=' + employeeLong;
            });
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }
</script>