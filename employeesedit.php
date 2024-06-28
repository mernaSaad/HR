<?php
// includes
include_once "main/initial.php";
include_once "includes/head.php";
include_once "includes/nav.php";
include_once "includes/sidebar.php";

// login 
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

// Check if 'edit' parameter is present in URL
if (isset($_GET['edit'])) {
    $employeeId = $_GET['edit'];

    // Fetch employee information from the database
    $employeeQuery = $con->prepare("SELECT * FROM employees WHERE id = :id");
    $employeeQuery->bindParam(':id', $employeeId);
    $employeeQuery->execute();
    $employee = $employeeQuery->fetch(PDO::FETCH_ASSOC);

    if (!$employee) {
        echo "Employee not found.";
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve form data
    $name = check($_POST['name']);
    $email = check($_POST['email']);
    $new_password = $_POST['new_password'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $code = $_POST['code'];
    $position = $_POST['position'];
    $salary = $_POST['salary'];
    $designation = $_POST['designation'];
    $nationality = $_POST['nationality'];
    $holiday = check($_POST['holiday']);
    $joiningdate = check($_POST['joiningdate']);
    $martialstatus = check($_POST['martialstatus']);
    $expiredate = check($_POST['expiredate']);
    $startdate = check($_POST['startdate']);
    $dob = check($_POST['dob']);
    $address = check($_POST['address']);
    $status = check($_POST['status']);
    $education = check($_POST['education']);

    // Validate and hash the new password if it's not empty
    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $new_password = $hashed_password;
    } else {
        // If no new password provided, keep the existing password
        $new_password = $employee['password'];
    }

    // Prepare and execute the UPDATE query
    $updateQuery = $con->prepare("UPDATE employees SET name = :name, email = :email, phone = :phone,
    password = :password, gender = :gender, code = :code, position = :position, salary = :salary, designation = :designation, nationality = :nationality,
    holiday = :holiday, joiningdate = :joiningdate, martialstatus = :martialstatus, expiredate = :expiredate,
    dob = :dob, address = :address, status = :status, education = :education WHERE id = :id");

    $updateQuery->bindParam(':name', $name);
    $updateQuery->bindParam(':email', $email);
    $updateQuery->bindParam(':phone', $phone);
    $updateQuery->bindParam(':password', $new_password);
    $updateQuery->bindParam(':gender', $gender);
    $updateQuery->bindParam(':code', $code);
    $updateQuery->bindParam(':position', $position);
    $updateQuery->bindParam(':salary', $salary);
    $updateQuery->bindParam(':designation', $designation);
    $updateQuery->bindParam(':nationality', $nationality);
    $updateQuery->bindParam(':holiday', $holiday);
    $updateQuery->bindParam(':joiningdate', $joiningdate);
    $updateQuery->bindParam(':martialstatus', $martialstatus);
    $updateQuery->bindParam(':expiredate', $expiredate);
    $updateQuery->bindParam(':dob', $dob);
    $updateQuery->bindParam(':address', $address);
    $updateQuery->bindParam(':status', $status);
    $updateQuery->bindParam(':education', $education);
    $updateQuery->bindParam(':id', $employeeId);


    try {
        $updateQuery->execute();

        if ($updateQuery->rowCount() > 0) {
            // Update successful
            header("Location: employeesview.php?do=edit");
            exit();
        } else {
            echo "Update didn't affect any rows.";
        }
    } catch (PDOException $e) {
        echo "Update failed: " . $e->getMessage();
    }
}

?>
<div class="app-content main-content">
    <div class="side-app main-container">
        <!--Page header-->
        <div class="page-header d-xl-flex d-block">
            <div class="page-leftheader">
                <div class="page-title">Employees<span class="font-weight-normal text-muted ms-2">Edit</span></div>
            </div>
        </div>
        <!--End Page header-->
        <!-- contaner of form add admins -->
        <div class="container mt-5">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-bottom-0">
                            <h4 class="card-title">Edit Form</h4>
                        </div>
                        <div class="card-body">
                            <form action="" method="post">
                                <div class="">
                                    <div class="form-group">
                                        <label for="" class="form-label">Name</label>
                                        <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name" value="<?php echo  $employee['name']; ?>" maxlength="40">
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" id="email" placeholder="Enter Email" value="<?php echo  $employee['email']; ?>" maxlength="40">
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="form-label">Phone</label>
                                        <input type="text" name="phone" class="form-control" id="phone" placeholder="Enter phone" value="<?php echo  $employee['phone']; ?>" maxlength="11">
                                    </div>
                                    <div class="form-group" id="Password-toggle">
                                        <label for="" class="form-label">Password </label>
                                        <div class="input-group">
                                            <a href="#" class="input-group-text" id="togglePassword">
                                                <i class="fe fe-eye-off" aria-hidden="true"></i>
                                            </a>
                                            <!-- Hidden input for the existing password (no name attribute) -->
                                            <input type="hidden" value="<?php echo $employee['password']; ?>">
                                            <!-- Password input for the new password (name attribute is "new_password") -->
                                            <input class="form-control" name="new_password" type="password" id="passwordInput" placeholder="Enter Password" minlength="8" maxlength="20">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="form-label">Code</label>
                                        <input type="text" name="code" class="form-control" id="code" placeholder="Enter code" value="<?php echo  $employee['code']; ?>" maxlength="40">
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="form-label">Position</label>
                                        <input type="text" name="position" class="form-control" id="position" placeholder="Enter position" value="<?php echo  $employee['position']; ?>" maxlength="100">
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="form-label">Salary</label>
                                        <input type="text" name="salary" class="form-control" id="salary" placeholder="Enter Salary" value="<?php echo  $employee['salary']; ?>" maxlength="100">
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="form-label">Gender</label>
                                        <select id="gender" name="gender" class="form-control">
                                            <option value="<?php echo $employee['gender']; ?>"><?php echo $employee['gender']; ?></option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="form-label">Designation</label>
                                        <input type="text" name="designation" class="form-control" id="designation" placeholder="Enter designation" value="<?php echo  $employee['designation']; ?>" maxlength="40">
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="form-label">Nationality</label>
                                        <input type="text" name="nationality" class="form-control" id="nationality" placeholder="Enter National ID" value="<?php echo $employee['nationality']; ?>" maxlength="14">
                                    </div>
                                    <div class="form-group">
                                        <label for="holiday" class="form-label">Holiday</label>
                                        <select name="holiday" id="holiday" class="form-control" required>
                                            <option value="" selected disabled>Select a day</option>
                                            <option value="Monday" <?php if ($employee['holiday'] == 'Monday') echo 'selected'; ?>>Monday</option>
                                            <option value="Tuesday" <?php if ($employee['holiday'] == 'Tuesday') echo 'selected'; ?>>Tuesday</option>
                                            <option value="Wednesday" <?php if ($employee['holiday'] == 'Wednesday') echo 'selected'; ?>>Wednesday</option>
                                            <option value="Thursday" <?php if ($employee['holiday'] == 'Thursday') echo 'selected'; ?>>Thursday</option>
                                            <option value="Friday" <?php if ($employee['holiday'] == 'Friday') echo 'selected'; ?>>Friday</option>
                                            <option value="Saturday" <?php if ($employee['holiday'] == 'Saturday') echo 'selected'; ?>>Saturday</option>
                                            <option value="Sunday" <?php if ($employee['holiday'] == 'Sunday') echo 'selected'; ?>>Sunday</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="form-label">Joining-Date</label>
                                        <input type="date" name="joiningdate" class="form-control" id="joiningdate" placeholder="Enter Date Of Employment" value="<?php echo  $employee['joiningdate']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="martialstatus" class="form-label">Marital Status</label>
                                        <select name="martialstatus" id="martialstatus" class="form-control" required>
                                            <option value="" selected disabled>Select a marital status</option>
                                            <option value="Single" <?php if ($employee['martialstatus'] == 'Single') echo 'selected'; ?>>Single</option>
                                            <option value="Married" <?php if ($employee['martialstatus'] == 'Married') echo 'selected'; ?>>Married</option>
                                            <option value="Divorced" <?php if ($employee['martialstatus'] == 'Divorced') echo 'selected'; ?>>Divorced</option>
                                            <option value="Widowed" <?php if ($employee['martialstatus'] == 'Widowed') echo 'selected'; ?>>Widowed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="form-label">Expire-Date</label>
                                    <input type="date" name="expiredate" class="form-control" id="expiredate" placeholder="Enter Date Of Employment" value="<?php echo  $employee['expiredate']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="" class="form-label">Start-Date</label>
                                    <input type="date" name="startdate" class="form-control" id="startdate" placeholder="Enter Date Of Employment" value="<?php echo  $employee['startdate']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="" class="form-label">Date Of Birth</label>
                                    <input type="date" name="dob" class="form-control" id="dob" placeholder="Enter Date Of Employment" value="<?php echo  $employee['dob']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="" class="form-label">Address</label>
                                    <input type="text" name="address" class="form-control" id="address" placeholder="Enter Address" value="<?php echo  $employee['address']; ?>" maxlength="100">
                                </div>
                                <div class="form-group">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="" selected disabled>Select a status</option>
                                        <option value="Working" <?php if ($employee['status'] == 'Working') echo 'selected'; ?>>Working</option>
                                        <option value="Not Working" <?php if ($employee['status'] == 'Not Working') echo 'selected'; ?>>Not Working</option>
                                        <option value="On Leave" <?php if ($employee['status'] == 'On Leave') echo 'selected'; ?>>On Leave</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="" class="form-label">Education</label>
                                    <input type="text" name="education" class="form-control" id="education" placeholder="Enter education" value="<?php echo  $employee['education']; ?>" maxlength="100">
                                </div>
                        </div>
                        <button name="update" class="btn btn-primary btn-block" type="submit">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Row -->
    </div>
</div>
</div>
<script>
    document.getElementById('togglePassword').addEventListener('click', function(e) {
        // Prevent the default click behavior
        e.preventDefault();

        // Get the password input element
        var passwordInput = document.getElementById('passwordInput');

        // Get the eye icon element
        var eyeIcon = this.querySelector('i');

        // Toggle the type attribute of the password input
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('fe-eye-off');
            eyeIcon.classList.add('fe-eye');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('fe-eye');
            eyeIcon.classList.add('fe-eye-off');
        }
    });
</script>

<?php include_once "includes/footer.php"; ?>