<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// includes

include_once "main/initial.php";
include_once "includes/head.php";
include_once "includes/nav.php";
include_once "includes/sidebar.php";

// login required
if (!isset($_SESSION['username'])) {
    header("Location: login.php?msg=" . urlencode("Please Login First"));
    exit();
}

// Check if the user is not an admin, redirect to the login page
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: loggin.php');
    exit();
}

if (isset($_POST['submit'])) {
    $name = check($_POST['name']);
    $email = check($_POST['email']);
    $password = check($_POST['password']);
    $code = check($_POST['code']);
    $position = check($_POST['position']);
    $salary = check($_POST['salary']);
    $gender = check($_POST['gender']);
    $designation = check($_POST['designation']);
    $nationality = check($_POST['nationality']);
    $holiday = check($_POST['holiday']);
    $phone = check($_POST['phone']);
    $joiningdate = check($_POST['joiningdate']);
    $martialstatus = check($_POST['martialstatus']);
    $expiredate = check($_POST['expiredate']);
    $startdate = check($_POST['startdate']);
    $dob = check($_POST['dob']);
    $address = check($_POST['address']);
    $status = check($_POST['status']);
    $education = check($_POST['education']);

    // Check if the email already exists
    $checkQuery = $con->prepare("SELECT email FROM employees WHERE email = :email");
    $checkQuery->bindParam(':email', $email);
    $checkQuery->execute();
    $existingEmail = $checkQuery->fetch(PDO::FETCH_ASSOC);

    if ($existingEmail) {
        // If the email already exists, show a Swal alert
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'This email is already registered. Please use a different email!',
                    text: '',
                });
            </script>";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Example code for inserting data into a employees
        $stmt = $con->prepare("INSERT INTO employees(`name`, `email`, `password`, `code`, `position`, `salary`, `gender`, `designation`, `nationality`, `holiday`, `phone`, `joiningdate`, `martialstatus`, `expiredate`, `startdate`, `dob`, `address`, `status`, `education`)
            VALUES (:name, :email, :password, :code, :position, :salary, :gender, :designation, :nationality, :holiday, :phone, :joiningdate, :martialstatus, :expiredate, :startdate, :dob, :address, :status, :education)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':code', $code);
        $stmt->bindParam(':position', $position);
        $stmt->bindParam(':salary', $salary);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':designation', $designation);
        $stmt->bindParam(':nationality', $nationality);
        $stmt->bindParam(':holiday', $holiday);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':joiningdate', $joiningdate);
        $stmt->bindParam(':martialstatus', $martialstatus);
        $stmt->bindParam(':expiredate', $expiredate);
        $stmt->bindParam(':startdate', $startdate);
        $stmt->bindParam(':dob', $dob);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':education', $education);
        $stmt->execute();

        if (isset($link)) {
            header("Location: " . $link . "&do=done");
        } else {
            header("Location: employeesview.php?do=done");
        }
        exit();
    }
}
?>
<style>
    /* Hide the SmartWizard buttons */
    /* Hide the SmartWizard Finish and Cancel buttons */
    #smartwizard-3 .sw-btn-group-extra {
        display: none;
    }
</style>
<div class="app-content main-content">
    <div class="side-app main-container">
        <!--Page header-->
        <div class="page-header d-xl-flex d-block">
            <div class="page-leftheader">
                <div class="page-title">Employees<span class="font-weight-normal text-muted ms-2">Add</span></div>
            </div>
        </div>
        <!--End Page header-->
        <!-- contaner of form add admins -->
        <div class="container mt-5">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-bottom-0">
                            <h4 class="card-title">Adding Form</h4>
                        </div>
                        <div class="card-body">
                            <form method="post" action="">
                                <div id="smartwizard-3">
                                    <ul>
                                        <li><a href="#step-10">Employee Information</a></li>
                                        <li><a href="#step-11">Contact</a></li>
                                        <li><a href="#step-12">Employee Data</a></li>
                                        <li><a href="#step-13">Contract Data </a></li>
                                    </ul>
                                    <div>
                                        <div id="step-10" class="">
                                            <div class="form-group">
                                                <label for="" class="form-label">Employee Name</label>
                                                <input type="text" name="name" class="form-control" id="name" placeholder="Enter Employee Name" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="form-label">Gender</label>
                                                <select id="gender" name="gender" class="form-control" required>
                                                    <option value="">Choose Gender</option>
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="designation" class="form-label">designation</label>
                                                <input type="text" name="designation" class="form-control" id="designation" placeholder="Enter designation" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="form-label">Nationality</label>
                                                <input type="text" name="nationality" class="form-control" id="nationality" placeholder="Enter Nationality" required>
                                            </div>
                                            <div class="form-group">
                                                <label for=" " class="form-label">Birth Date</label>
                                                <input type="date" name="dob" class="form-control" id="dob" required>
                                            </div>
                                            <div class="form-group">
                                                <label for=" " class="form-label">Education Qualifcation</label>
                                                <input type="text" name="education" class="form-control" id="education" placeholder="Enter Education Qualifcation" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="martialstatus" class="form-label">Marital Status</label>
                                                <select name="martialstatus" id="martialstatus" class="form-control" required>
                                                    <option value="" selected disabled>Select a marital status</option>
                                                    <option value="Single">Single</option>
                                                    <option value="Married">Married</option>
                                                    <option value="Divorced">Divorced</option>
                                                    <option value="Widowed">Widowed</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="step-11" class="">
                                            <div class="form-group">
                                                <label for=" " class="form-label">Personal Phone</label>
                                                <input type="text" name="phone" class="form-control" id="phone" placeholder="Enter Phone Number" required minlength="11" maxlength="11">
                                            </div>
                                            <div class="form-group">
                                                <label for=" " class="form-label">E-mail</label>
                                                <input type="email" name="email" class="form-control" id="email" placeholder="Enter E-mail" required>
                                            </div>
                                            <div class="form-group" id="Password-toggle">
                                                <label for="" class="form-label">Password</label>
                                                <div class="input-group">
                                                    <a href="" class="input-group-text">
                                                        <i class="fe fe-eye-off" aria-hidden="true"></i>
                                                    </a>
                                                    <input class="form-control" name="password" type="password" placeholder="Password" required minlength="6" maxlength="20">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for=" " class="form-label">Address</label>
                                                <input type="text" name="address" class="form-control" id="address" placeholder="Enter Address" required>
                                            </div>
                                        </div>
                                        <div id="step-12" class="">
                                            <div class="form-group">
                                                <label for=" " class="form-label">Code</label>
                                                <input type="text" name="code" class="form-control" id="code" placeholder="Enter Code" required>
                                            </div>
                                            <div class="form-group">
                                                <label for=" " class="form-label">Position</label>
                                                <input type="text" name="position" class="form-control" id="position" placeholder="Enter Position" required>
                                            </div>
                                            <div class="form-group">
                                                <label for=" " class="form-label">Salary</label>
                                                <input type="text" name="salary" class="form-control" id="salary" placeholder="Enter Salary" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="status" class="form-label">Status</label>
                                                <select name="status" id="status" class="form-control" required>
                                                    <option value="" selected disabled>Select a status</option>
                                                    <option value="Working">Working</option>
                                                    <option value="Not Working">Not Working</option>
                                                    <option value="On Leave">On Leave</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="step-13" class="">
                                            <div class="form-group">
                                                <label for=" " class="form-label">Joining Date</label>
                                                <input type="date" name="joiningdate" class="form-control" id="joiningdate" required>
                                            </div>
                                            <div class="form-group">
                                                <label for=" " class="form-label">Start Date</label>
                                                <input type="date" name="startdate" class="form-control" id="startdate" required>
                                            </div>
                                            <div class="form-group">
                                                <label for=" " class="form-label">Contract Expiration Date</label>
                                                <input type="date" name="expiredate" class="form-control" id="expiredate" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="holiday" class="form-label">Holiday</label>
                                                <select name="holiday" id="holiday" class="form-control" required>
                                                    <option value="" selected disabled>Select a day</option>
                                                    <option value="Monday">Monday</option>
                                                    <option value="Tuesday">Tuesday</option>
                                                    <option value="Wednesday">Wednesday</option>
                                                    <option value="Thursday">Thursday</option>
                                                    <option value="Friday">Friday</option>
                                                    <option value="Saturday">Saturday</option>
                                                    <option value="Sunday">Sunday</option>
                                                </select>
                                            </div>
                                            <div class=" float-end" role="group">
                                                <button name="submit" type="submit" class="btn btn-primary btn-lg">Add</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Row -->
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize SmartWizard
        $('#smartwizard-3').smartWizard();

        // Submit the form when SmartWizard's Finish button is clicked
        $('#smartwizard-3 .sw-btn-group').on('click', '.btn-finish', function() {
            $('#addEmployeeForm').submit();
        });
    });
</script>