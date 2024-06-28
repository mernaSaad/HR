<?php
// Includes
include_once "main/initial.php";
include_once "includes/head.php";
include_once "includes/nav.php";
include_once "includes/sidebar.php";

// Check if employee ID is provided in the URL
if (!isset($_GET['employee_id'])) {
    // Redirect to a salary page with an error message
    header("Location: salaryreport.php?msg=" . urlencode("Employee ID is required"));
    exit();
}

$employeeid = check($_GET['employee_id']);
$month = check($_GET['month']);

$query = "SELECT * FROM employees WHERE id = :employeeid";
$stmt = $con->prepare($query);
$stmt->bindParam(':employeeid', $employeeid, PDO::PARAM_INT);
$stmt->execute();
$employee = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$employee) {
    // If no employee found, show an alert and redirect
    echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Employee not found',
                text: 'Please select a valid employee',
                showConfirmButton: false,
                timer: 2000
            }).then(function() {
                window.location.href = 'salary.php';
            });
        </script>";
    exit();
}

// Assuming you have already retrieved the employee's salary and holiday information from the database
$salary = $employee['salary'];
$holiday = $employee['holiday'];

// Calculate the total number of working days in the selected month excluding holidays
$year = date("Y");
$month = check($_GET['month']);
$totalDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$totalWorkingDays = 0;
for ($day = 1; $day <= $totalDays; $day++) {
    $currentDay = date("l", mktime(0, 0, 0, $month, $day, $year));
    if ($currentDay !== $holiday) {
        $totalWorkingDays++;
    }
}

// Retrieve the attendance days for the selected month
$query = "SELECT COUNT(*) AS attendanceDays FROM attendance WHERE employeeid = :employeeid AND MONTH(date) = :month AND YEAR(date) = :year";
$stmt = $con->prepare($query);
$stmt->bindParam(':employeeid', $employeeid, PDO::PARAM_INT);
$stmt->bindParam(':month', $month, PDO::PARAM_INT);
$stmt->bindParam(':year', $year, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    $attendanceDays = $result['attendanceDays'];
    $attendancePercentage = ($attendanceDays / $totalWorkingDays) * 100;
    $absenceDays = $totalWorkingDays - $attendanceDays;

    // Calculate the deduction amount (assuming 1% deduction per day)
    // Calculate the worth of 1 day
    $worthOfDay = $salary / $totalWorkingDays;
    // Calculate the deduction for each absence day
    $deduction = $worthOfDay * $absenceDays;
    // $deduction = ($deductionRate / 100) * $salary * ($totalWorkingDays - $attendanceDays);

    // Calculate the net salary
    $netSalary = $salary - $deduction;

} else {
    echo "Error fetching attendance days";
}
if (isset($_POST['submit'])) {
    $employeeId = $_POST['employee_id'];
    $fixedSalary = $_POST['fixedsalary'];
    $month = $_POST['month'];
    $monthName = date("F", mktime(0, 0, 0, $month, 1));
    $attendanceDays = $_POST['attendancedays'];
    $absenceDays = $_POST['absencedays'];
    $action = "Deduction";
    $amount = $_POST['amount'];
    $netSalary = $_POST['netsalary'];
    $date = $_POST['date'];

    try {
        // Check if a row already exists for the employee_id and month
        $query = "SELECT * FROM salarydetalis WHERE employee_id = :employee_id AND month = :month";
        $stmt = $con->prepare($query);
        $stmt->bindParam(':employee_id', $employeeId, PDO::PARAM_INT);
        $stmt->bindParam(':month', $monthName, PDO::PARAM_STR);
        $stmt->execute();
        $existingRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingRow) {
            header("Location: salaryreport.php?do=exist");
        } else {
            // If no row exists, insert a new row
            $query = "INSERT INTO salarydetalis (employee_id, fixedsalary, month, attendancedays, absencedays, action, amount, netsalary, date)
            VALUES (:employee_id, :fixedsalary, :month, :attendancedays, :absencedays, :action, :amount, :netsalary, :date)";
        }

        $stmt = $con->prepare($query);
        $stmt->bindParam(':employee_id', $employeeId, PDO::PARAM_INT);
        $stmt->bindParam(':fixedsalary', $fixedSalary, PDO::PARAM_INT);
        $stmt->bindParam(':month', $monthName, PDO::PARAM_STR);
        $stmt->bindParam(':attendancedays', $attendanceDays, PDO::PARAM_INT);
        $stmt->bindParam(':absencedays', $absenceDays, PDO::PARAM_INT);
        $stmt->bindParam(':action', $action, PDO::PARAM_STR);
        $stmt->bindParam(':amount', $amount, PDO::PARAM_INT);
        $stmt->bindParam(':netsalary', $netSalary, PDO::PARAM_INT);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->execute();

        header("Location: salaryreport.php?do=done");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}


?>

<div class="app-content main-content">
    <div class="side-app main-container">
        <!-- Muze Main Content -->
        <div class="main-content">
            <div class="px-3 px-xxl-5 py-3 py-lg-4 border-bottom border-gray-200 after-header">
                <div class="container-fluid px-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <span class="text-uppercase tiny text-gray-600 Montserrat-font font-weight-semibold">Salary</span>
                            <h1 class="h2 mb-0 lh-sm">Salary details</h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-3 p-xxl-5 after-header">
                <div class="container-fluid px-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="card rounded-12 shadow-dark-80 border border-gray-50 mb-3 mb-xl-4 p-3">
                                <div class="card-body p-0 p-md-4">
                                    <div class="d-flex flex-wrap align-items-center">
                                        <span class="mb-3 mb-md-0 me-1">
                                            <img src="assets/images/brand/logo.jpg" class="header-brand-img desktop-logo" alt="zad90logo" width="80">
                                        </span>
                                        <div class="mb-3 mb-md-0 ms-auto d-flex flex-wrap align-items-center">
                                            <form method="POST" action="">
                                                <input type="hidden" name="employee_id" value="<?php echo $employee['id']; ?>">
                                                <input type="hidden" name="fixedsalary" value="<?php echo $salary; ?>">
                                                <input type="hidden" name="month" value="<?php echo $month; ?>">
                                                <input type="hidden" name="attendancedays" value="<?php echo $attendanceDays; ?>">
                                                <input type="hidden" name="absencedays" value="<?php echo $absenceDays; ?>">
                                                <input type="hidden" name="amount" value="<?php echo $deduction; ?>">
                                                <input type="hidden" name="netsalary" value="<?php echo $netSalary; ?>">
                                                <input type="hidden" name="date" value="<?php echo date('Y-m-d'); ?>">
                                                <button type="submit" name="submit" class="btn btn-primary btn-block">ADD</button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="pt-4 pt-md-5">
                                        <h5 class="font-weight-semibold opensans-font">Employee Name: <?php echo strtoupper($employee['name']); ?></h5>
                                        <p class="text-gray-700 mb-0">Employee Phone: <?php echo $employee['phone'] ?></p>
                                    </div>
                                    <div class="border-top border-gray-200 pt-3 pt-sm-4 d-flex flex-wrap pb-2">
                                        <div class="row">
                                            <div class="col-auto mt-2 mt-sm-3 px-3 pe-xxl-5">
                                                <span class="text-gray-600">Fixed Salary</span>
                                                <h5 class="font-weight-semibold opensans-font mt-2"><?php echo $employee['salary'] ?> EGP</h5>
                                            </div>
                                            <div class="col-auto mt-2 mt-sm-3 px-3 px-xxl-5">
                                                <span class="text-gray-600">Due Month</span>
                                                <h5 class="font-weight-semibold opensans-font mt-2 ">
                                                    <td><?php echo DateTime::createFromFormat('!m', $month)->format('F'); ?></td>
                                                </h5>
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
                                                        <th>Absence days</th>
                                                        <th>Attendance days</th>
                                                        <th>Amount</th>
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <tbody>
                                                    <tr>
                                                        <td><?php echo "Deduction" ?></td>
                                                        <td><?php echo $absenceDays ?> </td>
                                                        <td><?php echo $attendanceDays ?> </td>
                                                        <td><?php echo $deduction ?> EGP</td>
                                                        <td><?php echo date("Y-m-d"); ?></td>
                                                    </tr>
                                                </tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="border-top border-gray-200 mt-4 pt-3 p-lg-4 mb-md-5">
                                            <div class="row mt-md-2 py-1 py-md-2 pe-md-3">
                                                <div class="col text-end">
                                                    <span class="font-weight-semibold text-black-600">Total: <?php echo $netSalary ?> EGP</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card rounded-12 shadow-dark-80 border border-gray-50 mb-3 mb-xl-5 p-3">
                                <div class="card-body p-0 p-md-4 text-center">
                                    <h2>Employee Net Salary</h2>
                                    <h3 class="h1 font-weight-normal pt-1">
                                        <?php echo $netSalary ?> EGP</h3>
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