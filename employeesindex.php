<?php
// includes
include_once "main/initial.php";
include_once "includes/head.php";
include_once "includes/nav.php";
include_once "includes/sidebar.php";

// Check if a location is already chosen
if (!isset($_SESSION['location_id'])) {
    // Redirect to the locations page
    header("Location: employeeslocation.php?do=nochosen");
    exit();
}

// login required
if (!isset($_SESSION['username'])) {
    header("Location: loggin.php?msg=" . urlencode("Please Login First"));
    exit();
}

if (isset($_GET['location_id'])) {
    $_SESSION['location_id'] = $_GET['location_id'];
    header("Location: employeesindex.php");
}
?>

<?php
if (isset($_GET['do'])) {
    if ($_GET['do'] == "within") {
        echo "<script>
                Swal.fire({
                icon: 'success',
                title: 'You are within the location!',
                text:'',
                });    
            </script>";
    }
} 
?>
<style>
    td {
        text-align: center;
    }
</style>

<div class="app-content main-content">
    <div class="side-app main-container">
        <!--Page header-->
        <div class="page-header d-xl-flex d-block">
            <div class="page-leftheader">
                <div class="page-title">Employees<span class="font-weight-normal text-muted ms-2">Dashboard</span></div>
            </div>
        </div>
        <!--End Page header-->
        <!-- contaner of form add clients -->
        <div class="container mt-5">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered " id="responsive">
                                    <thead>
                                        <tr>
                                            <th>Clock-IN</th>
                                            <td>
                                                <?php
                                                $employeeid = $_SESSION['employeeid'];
                                                $currentDate = date('Y-m-d');
                                                $query = "SELECT * FROM attendance WHERE employeeid = :employeeid AND date = :currentDate";
                                                $stmt = $con->prepare($query);
                                                $stmt->bindParam(':employeeid', $employeeid, PDO::PARAM_INT);
                                                $stmt->bindParam(':currentDate', $currentDate);
                                                $stmt->execute();
                                                $attendance = $stmt->fetch(PDO::FETCH_ASSOC);
                                                if ($attendance) {
                                                    // Coach has already been marked present for the selected date
                                                    echo '<button class="btn btn-info" disabled>Clocked IN</button>';
                                                } else {
                                                    // Coach has not been marked present for the selected date
                                                    echo '<a class="btn btn-info clock-in-btn" id="clock-in-btn"><i class="fa fa-clock-o"></i></a>
                                                    ';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Clock-OUT</th>
                                            <td>
                                                <?php
                                                $employeeid = $_SESSION['employeeid'];
                                                $currentDate = date('Y-m-d H:i:s');
                                                $query = "SELECT * FROM attendance WHERE employeeid = :employeeid AND DATE(clockout) = DATE(:currentDate)";
                                                $stmt = $con->prepare($query);
                                                $stmt->bindParam(':employeeid', $employeeid, PDO::PARAM_INT);
                                                $stmt->bindParam(':currentDate', $currentDate);
                                                $stmt->execute();
                                                $attendance = $stmt->fetch(PDO::FETCH_ASSOC);
                                                if ($attendance) {
                                                    // Employee has already been marked present for the selected date
                                                    echo '<button class="btn btn-success" disabled>Clocked OUT</button>';
                                                } else {
                                                    // Employee has not been marked present for the selected date
                                                    echo '<a class="btn btn-success clock-out-btn"><i class="fa fa-clock-o"></i></a>';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Request Early Departure</th>
                                            <td>
                                                <?php
                                                $employeeid = $_SESSION['employeeid'];
                                                $query = "SELECT status FROM departures WHERE employeeid = :employeeid";
                                                $stmt = $con->prepare($query);
                                                $stmt->bindParam(':employeeid', $employeeid, PDO::PARAM_INT);
                                                $stmt->execute();
                                                $departure = $stmt->fetch(PDO::FETCH_ASSOC);

                                                if ($departure) {
                                                    // If the row exists
                                                    if ($departure['status'] == 1) {
                                                        // If status is 1, display a disabled "Denied" button
                                                        echo '<button class="btn btn-danger" disabled>Denied</button>';
                                                    } elseif ($departure['status'] == 2) {
                                                        // If status is 2, display a disabled "Approved" button
                                                        echo '<button class="btn btn-success" disabled>Approved</button>';
                                                    } elseif ($departure['status'] === null) {
                                                        // If status is NULL, display a "Waiting for Approval" button
                                                        echo '<button class="btn btn-secondary" disabled>Waiting for Approval</button>';
                                                    }
                                                } else {
                                                    // If the row does not exist, display the input field and button
                                                    echo '
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" id="departure-reason" placeholder="Reason">
                                                                <button class="btn btn-warning request-departure-btn"><i class="fa fa-clock-o"></i> Request Early Departure</button>
                                                            </div>';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>
<script>
    // CLOCK-IN
    $(document).ready(function() {
        $('.clock-in-btn').click(function(e) {
            e.preventDefault();
            console.log('Clock-IN button clicked');
            $.ajax({
                url: 'clockin.php',
                method: 'POST',
                data: {
                    employeeid: '<?php echo $_SESSION['employeeid']; ?>',
                    name: '',
                    code: '',
                    position: '',
                    date: '<?php echo date('Y-m-d'); ?>',
                    day: '<?php echo date('l'); ?>',
                    clockin: '<?php echo date('H:i:s'); ?>',
                    clockout: '',
                    workhour: '',
                    locationid: '1'
                },
                success: function(response) {
                    console.log('Success:', response); // Log success message
                    // Display success message using SweetAlert
                    Swal.fire({
                        icon: 'success',
                        title: 'Clock-IN recorded successfully',
                        showConfirmButton: false,
                        timer: 1500
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error:', xhr, status, error); // Log error message
                    // Display error message using SweetAlert
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed to record Clock-IN',
                        text: 'An error occurred. Please try again later.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        });
    });

    // CLOCK-OUT
    $('.clock-out-btn').click(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'clockout.php',
            method: 'POST',
            data: {
                employeeid: '<?php echo $_SESSION['employeeid']; ?>',
                clockout: '<?php echo date('Y-m-d H:i:s'); ?>'
            },
            success: function(response) {
                console.log(response); // Log the response
                // Display success message using SweetAlert
                Swal.fire({
                    icon: 'success',
                    title: 'Clock-OUT recorded successfully',
                    showConfirmButton: false,
                    timer: 1500
                });
            },
            error: function(xhr, status, error) {
                console.error(xhr, status, error); // Log any errors
                // Display error message using SweetAlert
                Swal.fire({
                    icon: 'error',
                    title: 'Failed to record Clock-OUT',
                    text: 'An error occurred. Please try again later.',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
    });

    // Request Early Departure
    $(document).ready(function() {
        $('.request-departure-btn').click(function(e) {
            e.preventDefault();
            console.log('Request Early Departure button clicked');
            var reason = $('#departure-reason').val();
            $.ajax({
                url: 'requestdeparture.php',
                method: 'POST',
                data: {
                    reason: reason
                },
                success: function(response) {
                    console.log('Success:', response);
                    Swal.fire({
                        icon: 'success',
                        title: 'Request Early Departure recorded successfully',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        // Reload the page after success
                        window.location.reload();
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error:', xhr, status, error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed to record Request Early Departure',
                        text: 'An error occurred. Please try again later.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        });
    });
</script>