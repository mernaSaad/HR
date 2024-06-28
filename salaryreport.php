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
// check if admin

// Check if the user is not an admin, redirect to the login page
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: loggin.php'); // Redirect to your login page
    exit();
}

// Retrieve data from the "employees" table
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
                title: ' Employee Salary Added Successfully ',
                text:'',
                });    
            </script>";
    }
}
if (isset($_GET['do'])) {
    if ($_GET['do'] == "exist") {
        echo "<script>
                Swal.fire({
                icon: 'error',
                title: '  Salary Is Already Inserted',
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
                <div class="page-title">Employees-Salary<span class="font-weight-normal text-muted ms-2">Table</span></div>
            </div>
        </div>
        <!--End Page header-->
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom" id="responsive-datatable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($employees as $emp) : ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($emp['id']); ?></td>
                                                <td><?php echo htmlspecialchars($emp['name']); ?></td>
                                                <td class="text-center">
                                                    <form method="GET" action="salarydetails.php" target="_blank" class="form-inline justify-content-between">
                                                        <input type="hidden" name="employee_id" value="<?php echo $emp['id']; ?>">
                                                        <select class="form-control custom-select mr-2" id="month" name="month">
                                                            <option value="">Choose Month</option>
                                                            <?php
                                                            // Get the current month as a number
                                                            $currentMonthNumber = date("n", strtotime("today"));
                                                            // Array of months in English
                                                            $months = array(
                                                                1 => "January", 2 => "February", 3 => "March", 4 => "April",
                                                                5 => "May", 6 => "June", 7 => "July", 8 => "August",
                                                                9 => "September", 10 => "October", 11 => "November", 12 => "December"
                                                            );
                                                            // Generate options for the months
                                                            foreach ($months as $monthNumber => $monthName) {
                                                                echo '<option value="' . $monthNumber . '"';
                                                                if ($monthNumber == $currentMonthNumber) {
                                                                    echo ' selected'; // Set the current month as the default selection
                                                                }
                                                                echo '>' . $monthName . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                        <button type="submit" class="btn btn-primary">View Salary</button>
                                                    </form>
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

<?php include_once "includes/footer.php"; ?>