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
    $deleteStmt = $con->prepare("DELETE FROM attendance WHERE id = :id");
    $deleteStmt->bindParam(':id', $deleteid);
    $deleteStmt->execute();
    header("Location: attendanceview.php?do=del");
}
$query = "SELECT attendance.*, locations.name AS location_name 
          FROM attendance 
          JOIN locations ON attendance.locationid = locations.id";
$stmt = $con->query($query);
$attendance = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>
<?php
if (isset($_GET['do'])) {
    if ($_GET['do'] == "done") {
        echo "<script>
                Swal.fire({
                icon: 'info',
                title: ' attendloyee Added Successfully ',
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
                title: ' attendloyee Updated Successfully ',
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
                title: ' attendloyee Deleted Successfully ',
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
                <div class="page-title">Attendance<span class="font-weight-normal text-muted ms-2">Report</span></div>
            </div>
        </div>
        <!--End Page header-->

        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h3 class="card-title">Daily Attendance Report</h3>
                            <!-- <a class="btn btn-primary" href="attendanceadd.php">New attendloyee</a> -->
                        </div>
                        <!-- Add the filter form -->
                        <div class="row mt-3 ">
                            <div class="col-md-6 mb-2 mb-md-0">
                                <form method="POST" class=" mx-5">
                                    <div class="form-row ">
                                        <div class="form-group col-md-6">
                                            <label for="name">Name</label>
                                            <input type="text" class="form-control" id="name" name="name">
                                        </div>
                                        <div class="form-group col-md-6 align-self-end">
                                            <button type="submit" class="btn btn-primary" name="filter">Filter</button>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="startdate">Start Date</label>
                                            <input type="date" class="form-control" id="startdate" name="startdate">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="enddate">End Date</label>
                                            <input type="date" class="form-control" id="enddate" name="enddate">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3 ">
                                        <button type="submit" class="btn btn-primary" name="get">Get</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom" id="responsive-datatable">

                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Code</th>
                                            <th>Position</th>
                                            <th>Date</th>
                                            <th>Day</th>
                                            <th>Clock-In</th>
                                            <th>Clock-Out</th>
                                            <th>Workhour</th>
                                            <th>Location</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($attendance as $attend) : ?>
                                            <tr>
                                                <!-- Assuming $attend is an array containing data from your new_table -->
                                                <th><?php echo $attend['id']; ?></th>
                                                <td><?php echo $attend['name']; ?></td>
                                                <td><?php echo $attend['code']; ?></td>
                                                <td><?php echo $attend['position']; ?></td>
                                                <td><?php echo $attend['date']; ?></td>
                                                <td><?php echo $attend['day']; ?></td>
                                                <td><?php echo $attend['clockin']; ?></td>
                                                <td><?php echo $attend['clockout']; ?></td>
                                                <th><?php echo $attend['workhour']; ?></th>
                                                <th><?php echo $attend['location_name']; ?></th>
                                                <td>
                                                    <a class="btn btn-warning" href="attendanceedit.php?edit=<?php echo $attend['id']; ?>"><i class="feather feather-edit"></i></a>|
                                                    <a class="btn btn-danger" href="#" onclick="showDeleteConfirmation('<?php echo urlencode($attend['id']); ?>');">
                                                        <i class="feather feather-trash-2"></i>
                                                    </a>
                                                </td>
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
<?php include_once "includes/footer.php"; ?>
<script>
    function showDeleteConfirmation(attendid) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to delete this row?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to the delete URL if the player confirms
                window.location.href = 'attendanceview.php?delete=' + attendid;
            }
        });
    }
</script>