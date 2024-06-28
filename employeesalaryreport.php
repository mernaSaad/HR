<?php
// Includes
include_once "main/initial.php";
include_once "includes/head.php";
include_once "includes/nav.php";
include_once "includes/sidebar.php";



$employeeid = check($_SESSION['employeeid']);

$query = "SELECT * FROM employees WHERE id = :employeeid";
$stmt = $con->prepare($query);
$stmt->bindParam(':employeeid', $employeeid, PDO::PARAM_INT);
$stmt->execute();
$employee = $stmt->fetch(PDO::FETCH_ASSOC);


$query = "SELECT * FROM salarydetalis WHERE employee_id = :employeeid";
$stmt = $con->prepare($query);
$stmt->bindParam(':employeeid', $employeeid, PDO::PARAM_INT);
$stmt->execute();
$salaries = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>
<div class="app-content main-content">
    <div class="side-app main-container">
        <!--Page header-->
        <div class="page-header d-xl-flex d-block">
            <div class="page-leftheader">
                <div class="page-title">Salary<span class="font-weight-normal text-muted ms-2"></span></div>
            </div>
        </div>
        <!--End Page header-->
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <!-- Muze Main Content -->
                    <div class="main-content">
                        <div class="px-3 px-xxl-5 py-3 py-lg-4 border-bottom border-gray-200 after-header">
                            <div class="container-fluid px-0">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h1 class="h2 mb-0 lh-sm">Salary details</h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 p-xxl-5 after-header">
                            <div class="container-fluid px-0">
                                <div class="row">
                                <?php foreach ($salaries as $salary) : ?>
                                        <div class="col-12">
                                            <div class="card rounded-12 shadow-dark-80 border border-gray-50 mb-3 mb-xl-4 p-3">
                                                <div class="card-body p-0 p-md-4">
                                                    <div class="d-flex flex-wrap align-items-center">
                                                        <span class="mb-3 mb-md-0 me-1">
                                                            <img src="assets/images/brand/logo.jpg" class="header-brand-img desktop-logo" alt="zad90logo" width="80">
                                                        </span>
                                                        <div class="mb-3 mb-md-0 ms-auto d-flex flex-wrap align-items-center">
                                                            <div class="dropdown export-dropdown">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="pt-4 pt-md-5">
                                                        <h5 class="font-weight-semibold opensans-font"><?php echo strtoupper($employee['name']); ?></h5>
                                                        <p class="text-gray-700 mb-0"><?php echo $employee['phone'] ?></p>
                                                    </div>
                                                    <div class="border-top border-gray-200 pt-3 pt-sm-4 d-flex flex-wrap pb-2">
                                                        <div class="row">
                                                            <div class="col-auto mt-2 mt-sm-3 px-3 pe-xxl-5">
                                                                <span class="text-gray-600">Fixed Salary</span>
                                                                <h5 class="font-weight-semibold opensans-font mt-2"><?php echo $employee['salary'] ?> EGP</h5>
                                                            </div>
                                                            <div class="col-auto mt-2 mt-sm-3 px-3 px-xxl-5">
                                                                <span class="text-gray-600">Due Month</span>
                                                                <h5 class="font-weight-semibold opensans-font mt-2 "><?php echo  $salary['month']; ?></h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="border-top border-gray-200 pt-md-4 mt-md-5">
                                                        <h5 class="font-weight-semibold opensans-font mt-3 pb-md-4">Details</h5>
                                                        <div class="table-responsive">
                                                            <table class="table table-borderless card-table table-nowrap">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Action</th>
                                                                        <th>Attendance Days</th>
                                                                        <th>Absence Days</th>
                                                                        <th>Amount</th>
                                                                        <th>Date</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php if ($salary !== false) : ?>
                                                                        <tr>
                                                                            <td><?php echo $salary['action'] ?></td>
                                                                            <td><?php echo $salary['attendancedays'] ?></td>
                                                                            <td><?php echo $salary['absencedays'] ?></td>
                                                                            <td><?php echo $salary['amount'] ?> EGP</td>
                                                                            <td><?php echo $salary['date'] ?></td>
                                                                        </tr>
                                                                    <?php endif; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card rounded-12 shadow-dark-80 border border-gray-50 mb-3 mb-xl-5 p-3">
                                                <div class="card-body p-0 p-md-4 text-center">
                                                    <h2>Your Net Salary</h2>
                                                    <h3 class="h1 font-weight-normal pt-1">
                                                        <?php
                                                        if (!empty($salary)) {
                                                            echo $salary['netsalary'];
                                                        } else {
                                                            echo "No salary details found";
                                                        }
                                                        ?> / mo</h3>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>