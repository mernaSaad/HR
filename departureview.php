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
    $deleteStmt = $con->prepare("DELETE FROM departures WHERE id = :id");
    $deleteStmt->bindParam(':id', $deleteid);
    $deleteStmt->execute();
    header("Location: departuresview.php?do=del");
}
// Retrieve data from the "departures" table and join with "locations" table
$query = "SELECT departures.*, locations.name AS location_name FROM departures
        JOIN locations ON departures.location = locations.id";
$stmt = $con->query($query);
$departures = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>
<?php
if (isset($_GET['do'])) {
    if ($_GET['do'] == "done") {
        echo "<script>
                Swal.fire({
                icon: 'info',
                title: ' departureloyee Added Successfully ',
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
                title: ' departureloyee Updated Successfully ',
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
                title: ' departureloyee Deleted Successfully ',
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
                <div class="page-title">Early Departure<span class="font-weight-normal text-muted ms-2">Report</span></div>
            </div>
        </div>
        <!--End Page header-->

        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h3 class="card-title">Daily Early Departure Report</h3>
                            <!-- <a class="btn btn-primary" href="departureloyeesadd.php">New departureloyee</a> -->
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
                                            <th>Reason</th>
                                            <th>Location</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($departures as $departure) : ?>
                                            <tr>
                                                <!-- Assuming $departure is an array containing data from your new_table -->
                                                <th><?php echo $departure['id']; ?></th>
                                                <td><?php echo $departure['name']; ?></td>
                                                <td><?php echo $departure['code']; ?></td>
                                                <td><?php echo $departure['position']; ?></td>
                                                <td><?php echo $departure['date']; ?></td>
                                                <td><?php echo $departure['day']; ?></td>
                                                <td><?php echo $departure['clockin']; ?></td>
                                                <td><?php echo $departure['clockout']; ?></td>
                                                <td><?php echo $departure['workhour']; ?></td>
                                                <td><?php echo $departure['reason']; ?></td>
                                                <td><?php echo $departure['location_name']; ?></td>
                                                <td>
                                                    <?php
                                                    if ($departure['status'] == null) {
                                                        // If status is null, show both buttons
                                                        echo '<button class="btn btn-success" onclick="updateStatus(' . $departure['id'] . ', 2, this)">Approve</button> - ';
                                                        echo '<button class="btn btn-danger" onclick="updateStatus(' . $departure['id'] . ', 1, this)">Deny</button>';
                                                    } else if ($departure['status'] == '1') {
                                                        // If status is '0', show deny button (disabled)
                                                        echo '<button class="btn btn-danger" disabled>Denied</button>';
                                                    } else if ($departure['status'] == '2') {
                                                        // If status is '1', show approve button (disabled)
                                                        echo '<button class="btn btn-success" disabled>Approved</button>';
                                                    }
                                                    ?>
                                                </td>

                                                <td>
                                                    <a class="btn btn-warning" href="departureloyeesedit.php?edit=<?php echo $departure['id']; ?>"><i class="feather feather-edit"></i></a>
                                                    <a class="btn btn-danger" href="#" onclick="showDeleteConfirmation('<?php echo urlencode($departure['id']); ?>');">
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
    function updateStatus(departureId, status, button) {
        $.ajax({
            url: 'updatestatus.php',
            method: 'POST',
            data: {
                id: departureId,
                status: status
            },
            success: function(response) {
                console.log(response);
                if (status === 1) {
                    // If status is 1 (approved), disable the approve button and enable the deny button
                    $(button).prop('disabled', true);
                    $(button).siblings('.btn-danger').prop('disabled', false);
                } else if (status === 0) {
                    // If status is 0 (denied), disable the deny button and enable the approve button
                    $(button).prop('disabled', true);
                    $(button).siblings('.btn-success').prop('disabled', false);
                }
                // Show a success message using SweetAlert
                Swal.fire({
                    icon: 'success',
                    title: 'Status Updated Successfully',
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    // Reload the page after success
                    window.location.reload();
                });
            },
            error: function(xhr, status, error) {
                console.error(xhr, status, error);
                alert('Failed to update status. Please try again later.');
            }
        });
    }
</script>


<script>
    function showDeleteConfirmation(departureid) {
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
                window.location.href = 'departureview.php?delete=' + departureid;
            }
        });
    }
</script>