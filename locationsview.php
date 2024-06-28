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

// Delete action
if (isset($_GET['delete'])) {
    $deleteid = $_GET['delete'];
    $deleteStmt = $con->prepare("DELETE FROM locations WHERE id = :id");
    $deleteStmt->bindParam(':id', $deleteid);
    $deleteStmt->execute();
    header("Location: locationsview.php?do=del");
}

// Retrieve data from the "admins" table
$query = "SELECT * FROM locations";
$stmt = $con->query($query);
$locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<?php
if (isset($_GET['do'])) {
    if ($_GET['do'] == "done") {
        echo "<script>
                Swal.fire({
                icon: 'info',
                title: ' location Inserted Successfully ',
                text:'',
                });    
            </script>";
    }
}
if (isset($_GET['do'])) {
    if ($_GET['do'] == "edit") {
        echo "<script>
                Swal.fire({
                icon: 'info',
                title: ' location Updated Successfully ',
                text:'',
                });    
            </script>";
    }
}
if (isset($_GET['do'])) {
    if ($_GET['do'] == "del") {
        echo "<script>
                Swal.fire({
                icon: 'error',
                title: ' location Deleted ',
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
                <div class="page-title">locations<span class="font-weight-normal text-muted ms-2">Table</span></div>
            </div>
        </div>
        <!--End Page header-->

        <div class="container">
            <div class="row">
                <div class="col">
                    <!-- Row -->
                    <div class="row row-sm">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <h3 class="card-title">locations DataTable</h3>
                                    <a class="btn btn-primary" href="locationsadd.php">New location</a>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered text-nowrap border-bottom" id="responsive-datatable">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Longitude</th>
                                                    <th>Latitude</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($locations as $loc) : ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($loc['id']); ?></td>
                                                        <td><?php echo htmlspecialchars($loc['name']); ?></td>
                                                        <td><?php echo htmlspecialchars($loc['longitude']); ?></td>
                                                        <td><?php echo htmlspecialchars($loc['latitude']); ?></td>
                                                        <td>
                                                            <a class="btn btn-warning" href="locationsedit.php?edit=<?php echo urlencode($loc['id']); ?>"><i class="feather feather-edit"></i></a>
                                                            <a class="btn btn-danger" href="#" onclick="showDeleteConfirmation('<?php echo urlencode($loc['id']); ?>');">
                                                                <i class="feather feather-trash-2"></i>
                                                            </a>
                                                        <td>
                                                            <!--<td>-->
                                                            <!--    <a class="btn btn-orange" href=""><i class="zmdi zmdi-assignment-check"></i></a>-->
                                                            <!--</td>-->
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Row -->

                </div>
            </div>
        </div>

    </div>
</div>
<?php include_once "includes/footer.php"; ?>
<script>
    function showDeleteConfirmation(locationid) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to delete this location?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to the delete URL if the player confirms
                window.location.href = 'locationsview.php?delete=' + locationid;
            }
        });
    }
</script>