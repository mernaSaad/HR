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
    $deleteStmt = $con->prepare("DELETE FROM employees WHERE id = :id");
    $deleteStmt->bindParam(':id', $deleteid);
    $deleteStmt->execute();
    header("Location: employeesview.php?do=del");
}
// Retrieve data from the "employees" table and join with "departments" table
$query = "SELECT * FROM employees";
$stmt = $con->query($query);
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<?php
if (isset($_GET['do'])) {
    if ($_GET['do'] == "done") {
        echo "<script>
                Swal.fire({
                icon: 'info',
                title: ' Employee Added Successfully ',
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
                title: ' Employee Updated Successfully ',
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
                title: ' Employee Deleted Successfully ',
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
                <div class="page-title">Staff<span class="font-weight-normal text-muted ms-2">List</span></div>
            </div>
        </div>
        <!--End Page header-->

        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h3 class="card-title">Employees DataTable</h3>
                            <a class="btn btn-primary" href="employeesadd.php">New Employee</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom" id="responsive-datatable">

                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>E-mail</th>
                                            <th>Phone</th>
                                            <th>Nationality</th>
                                            <th>dob</th>
                                            <th>Address</th>
                                            <th>Martial Status</th>
                                            <th>Education</th>
                                            <th>Code</th>
                                            <th>Position</th>
                                            <th>Salary</th>
                                            <th>Gender</th>
                                            <th>Designation</th>
                                            <th>Holiday</th>
                                            <th>Status</th>
                                            <th>Joining Date</th>
                                            <th>Expiration Date</th>
                                            <th>Starting Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($employees as $emp) : ?>
                                            <tr>
                                                <!-- Assuming $emp is an array containing data from your new_table -->
                                                <th><?php echo $emp['id']; ?></th>
                                                <td><?php echo $emp['name']; ?></td>
                                                <td><?php echo $emp['email']; ?></td>
                                                <td><?php echo $emp['phone']; ?></td>
                                                <td><?php echo $emp['nationality']; ?></td>
                                                <td><?php echo $emp['dob']; ?></td>
                                                <td><?php echo $emp['address']; ?></td>
                                                <td><?php echo $emp['martialstatus']; ?></td>
                                                <td><?php echo $emp['education']; ?></td>
                                                <td><?php echo $emp['code']; ?></td>
                                                <td><?php echo $emp['position']; ?></td>
                                                <td><?php echo $emp['salary']; ?></td>
                                                <td><?php echo $emp['gender']; ?></td>
                                                <td><?php echo $emp['designation']; ?></td>
                                                <td><?php echo $emp['holiday']; ?></td>
                                                <td><?php echo $emp['status']; ?></td>
                                                <td><?php echo $emp['joiningdate']; ?></td>
                                                <td><?php echo $emp['expiredate']; ?></td>
                                                <td><?php echo $emp['startdate']; ?></td>
                                                <td><?php echo $emp['status']; ?></td>
                                                <td>
                                                    <a class="btn btn-warning" href="employeesedit.php?edit=<?php echo $emp['id']; ?>"><i class="feather feather-edit"></i></a>|
                                                    <a class="btn btn-danger" href="#" onclick="showDeleteConfirmation('<?php echo urlencode($emp['id']); ?>');">
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
<script>
    function showDeleteConfirmation(employeeid) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to delete this Employee?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to the delete URL if the player confirms
                window.location.href = 'employeesview.php?delete=' + employeeid;
            }
        });
    }
</script>
<?php include_once "includes/footer.php"; ?>