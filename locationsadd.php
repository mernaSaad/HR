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
    header('Location: loggin.php'); // Redirect to your login page
    exit();
}

//add location

if (isset($_POST['submit'])) {
    $name = check($_POST['name']);
    $longitude = check($_POST['longitude']);
    $latitude = check($_POST['latitude']);

    if (!empty($_POST['link'])) {
        $link = $_POST['link'];
    }

    $stmt = $con->prepare("INSERT INTO locations(name,longitude,latitude) VALUES (:name,:longitude,:latitude)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':longitude', $longitude);
    $stmt->bindParam(':latitude', $latitude);
    $stmt->execute();

    if (isset($link)) {
        header("Location: " . $link . "&do=done");
    } else {
        header("Location: locationsview.php?do=done");
    }
    exit();
}

?>

<div class="app-content main-content">
    <div class="side-app main-container">
        <!--Page header-->
        <div class="page-header d-xl-flex d-block">
            <div class="page-leftheader">
                <div class="page-title">locations<span class="font-weight-normal text-muted ms-2">Add</span></div>
            </div>
        </div>
        <!--End Page header-->
        <!-- contaner of form add admins -->
        <div class="container mt-5">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-bottom-0">
                            <h4 class="card-title">location Form</h4>
                        </div>
                        <div class="card-body">
                            <form action="" method="post">
                                <div class="">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1" class="form-label">Name</label>
                                        <input type="text" name="name" class="form-control" id="name" placeholder="Enter Location's Name" required maxlength="60">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1" class="form-label">Longitude</label>
                                        <input type="text" name="longitude" class="form-control" id="longitude" placeholder="Enter Longitude" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1" class="form-label">Latitude</label>
                                        <input type="text" name="latitude" class="form-control" id="latitude" placeholder="Enter Latitude" required>
                                    </div>
                                </div>
                                <button name="submit" type="submit" class="btn btn-primary btn-block">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
            <!-- End Row -->
        </div>
    </div>


    <?php include_once "includes/footer.php"; ?>