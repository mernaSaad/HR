<?php
// includes
include_once "main/initial.php";
include_once "includes/head.php";
include_once "includes/nav.php";
include_once "includes/sidebar.php";

// login required
if (!isset($_SESSION['username'])) {
    header("Location:login.php?msg=" . urlencode("Please Login First"));
    exit();
}
// 
// check if admin

// Check if the user is not an admin, redirect to the login page
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: loggin.php');
    exit();
}

// Edit locations

if (isset($_GET['edit'])) {
    $locationid = $_GET['edit'];

    $locationQuery = $con->prepare("SELECT * FROM locations WHERE id = :id");
    $locationQuery->bindParam(':id', $locationid);
    $locationQuery->execute();
    $location = $locationQuery->fetch(PDO::FETCH_ASSOC);

    if (!$location) {
        echo "location not found.";
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = check($_POST['name']);
    $longitude = check($_POST['longitude']);
    $latitude = check($_POST['latitude']);


    $updateQuery = $con->prepare("UPDATE locations SET name = :name,longitude = :longitude,latitude = :latitude WHERE id = :id ");

    $updateQuery->bindParam(':name', $name);
    $updateQuery->bindParam(':longitude', $longitude);
    $updateQuery->bindParam(':latitude', $latitude);
    $updateQuery->bindParam(':id', $locationid);

    try {
        $updateQuery->execute();

        if ($updateQuery->rowCount() > 0) {
            // Update successful
            header("Location: locationsview.php?do=edit");
            exit();
        } else {
            echo "Update didn't affect any rows.";
        }
    } catch (PDOException $d) {
        echo "Update failed: " . $d->getMessage();
    }
}

?>
<div class="app-content main-content">
    <div class="side-app main-container">
        <!--Page header-->
        <div class="page-header d-xl-flex d-block">
            <div class="page-leftheader">
                <div class="page-title">locations<span class="font-weight-normal text-muted ms-2">Edit</span></div>
            </div>
        </div>
        <!--End Page header-->
        <!-- contaner of form add locations -->
        <div class="container mt-5">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-bottom-0">
                            <h4 class="card-title">locations Form</h4>
                        </div>
                        <div class="card-body">
                            <form action="" method="post">
                                <div class="">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1" class="form-label">location</label>
                                        <input type="text" name="name" class="form-control" id="name" placeholder="Enter location" value="<?php echo  $location['name']; ?>" required maxlength="40">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1" class="form-label">Longitude</label>
                                        <input type="text" name="longitude" class="form-control" id="longitude" placeholder="Enter Longitude" value="<?php echo  $location['longitude']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1" class="form-label">Latitude</label>
                                        <input type="text" name="latitude" class="form-control" id="latitude" placeholder="Enter Latitude" value="<?php echo  $location['latitude']; ?>" required>
                                    </div>
                                </div>
                                <button name="update" type="submit" class="btn btn-primary btn-block">Update</button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
            <!-- End Row -->
        </div>
    </div>

    <?php include_once "includes/footer.php"; ?>