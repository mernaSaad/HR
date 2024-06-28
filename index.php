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
    header('Location: employeesindex.php'); // Redirect to your login page
    exit();
}
// total employess
$stmtEmployees = $con->prepare("SELECT COUNT(*) AS totalEmployees FROM employees");
$stmtEmployees->execute();
$resultEmployees = $stmtEmployees->fetch(PDO::FETCH_ASSOC);
$totalEmployees = $resultEmployees['totalEmployees'];

// total Males
$stmtEmployees = $con->prepare("SELECT COUNT(*) AS totalEmployees FROM employees WHERE gender = 'male'");
$stmtEmployees->execute();
$resultEmployees = $stmtEmployees->fetch(PDO::FETCH_ASSOC);
$totalMales = $resultEmployees['totalEmployees'];

// total Females
$stmtEmployees = $con->prepare("SELECT COUNT(*) AS totalEmployees FROM employees WHERE gender = 'female'");
$stmtEmployees->execute();
$resultEmployees = $stmtEmployees->fetch(PDO::FETCH_ASSOC);
$totalFemales = $resultEmployees['totalEmployees'];

//total active
$currentDate = date('Y-m-d');
$stmtActiveEmployees = $con->prepare("SELECT COUNT(*) AS totalActive FROM attendance WHERE date = :date AND clockin IS NOT NULL AND clockout IS NULL");
$stmtActiveEmployees->bindParam(':date', $currentDate);
$stmtActiveEmployees->execute();
$resultActiveEmployees = $stmtActiveEmployees->fetch(PDO::FETCH_ASSOC);
$totalActive = $resultActiveEmployees['totalActive'];

//total inactive
$currentDate = date('Y-m-d');
$stmtInactiveEmployees = $con->prepare("SELECT COUNT(*) AS totalInactive FROM employees WHERE id NOT IN (SELECT DISTINCT employeeid FROM attendance WHERE date = :date)");
$stmtInactiveEmployees->bindParam(':date', $currentDate);
$stmtInactiveEmployees->execute();
$resultInactiveEmployees = $stmtInactiveEmployees->fetch(PDO::FETCH_ASSOC);
$totalInactive = $resultInactiveEmployees['totalInactive'];

?>

<div class="app-content main-content">
    <div class="side-app main-container">
        <!--Page header-->
        <div class="page-header d-xl-flex d-block">
            <div class="page-leftheader">
                <div class="page-title">Admins<span class="font-weight-normal text-muted ms-2">Dashboard</span></div>
            </div>
        </div>
        <!--End Page header-->
        <!-- contaner of form add clients -->
        <div class="container mt-5">
            <div class="row">
                <div class="col-xl-9 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="mt-0 text-start"> <span class="fs-14 font-weight-semibold">Employees</span>
                                                <h3 class="mb-0 mt-1 mb-2"><?php echo $totalEmployees; ?></h3>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="icon1 bg-info my-auto  float-end"> <i class="feather feather-users"></i> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="mt-0 text-start"> <span class="fs-14 font-weight-semibold">Males</span>
                                                <h3 class="mb-0 mt-1 mb-2"><?php echo $totalMales; ?></h3>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="icon1 bg-info my-auto  float-end"> <i class="feather feather-users"></i> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="mt-0 text-start"> <span class="fs-14 font-weight-semibold">Females</span>
                                                <h3 class="mb-0 mt-1 mb-2"><?php echo $totalFemales; ?></h3>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="icon1 bg-info my-auto  float-end"> <i class="feather feather-users"></i> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="mt-0 text-start"> <span class="fs-14 font-weight-semibold">Active</span>
                                                <h3 class="mb-0 mt-1 mb-2"><?php echo $totalActive; ?></h3>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="icon1 bg-success my-auto  float-end"> <i class="feather feather-users"></i> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="mt-0 text-start"> <span class="fs-14 font-weight-semibold">Contract</span>
                                                <h3 class="mb-0 mt-1 mb-2"><?php echo 0 ?></h3>

                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="icon1 bg-primary my-auto  float-end"> <i class="feather feather-box"></i> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="mt-0 text-start"> <span class="fs-14 font-weight-semibold">Contract</span>
                                                <h3 class="mb-0 mt-1 mb-2"><?php echo 0 ?></h3>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="icon1 bg-primary my-auto  float-end"> <i class="feather feather-user"></i> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="mt-0 text-start"> <span class="fs-14 font-weight-semibold">Inactive</span>
                                                <h3 class="mb-0 mt-1 mb-2"><?php echo $totalInactive; ?></h3>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="icon1 bg-danger my-auto  float-end"> <i class="feather feather-user"></i> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="mt-0 text-start"> <span class="fs-14 font-weight-semibold">Permenant</span>
                                                <h3 class="mb-0 mt-1 mb-2"><?php echo 0 ?></h3>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="icon1 bg-secondary my-auto  float-end"> <i class="feather feather-user"></i> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="mt-0 text-start"> <span class="fs-14 font-weight-semibold">Contract</span>
                                                <h3 class="mb-0 mt-1 mb-2"><?php echo 0 ?></h3>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="icon1 bg-primary my-auto  float-end"> <i class="feather feather-user"></i> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <?php include_once "includes/footer.php"; ?>