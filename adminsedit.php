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

// Edit admins

if (isset($_GET['edit'])) {
    $adminId = $_GET['edit'];

    $adminQuery = $con->prepare("SELECT * FROM admins WHERE id = :id");
    $adminQuery->bindParam(':id', $adminId);
    $adminQuery->execute();
    $admin = $adminQuery->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        echo "Admin not found.";
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = check($_POST['username']);
    $newPassword = $_POST['new_password']; // New password provided in the form

    if (!empty($newPassword)) {
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    } else {
        // No new password provided, retain the existing hashed password
        $hashedPassword = $admin['password'];
    }

    // Update the admin's information in the database
    $updateQuery = $con->prepare("UPDATE admins SET username = :username, password = :password WHERE id = :id");

    $updateQuery->bindParam(':password', $hashedPassword);
    $updateQuery->bindParam(':id', $adminId);
    $updateQuery->bindParam(':username', $username);

    try {
        $updateQuery->execute();

        if ($updateQuery->rowCount() > 0) {
            // Update successful
            header("Location: adminsview.php?do=edit");
            exit();
        } else {
            echo "Update didn't affect any rows.";
        }
    } catch (PDOException $a) {
        echo "Update failed: " . $a->getMessage();
    }
}

?>
<div class="app-content main-content">
    <div class="side-app main-container">
        <!--Page header-->
        <div class="page-header d-xl-flex d-block">
            <div class="page-leftheader">
                <div class="page-title">Admins<span class="font-weight-normal text-muted ms-2">Edit</span></div>
            </div>
        </div>
        <!--End Page header-->
        <!-- contaner of form add admins -->
        <div class="container mt-5">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-bottom-0">
                            <h4 class="card-title">Edit Admin</h4>
                        </div>
                        <div class="card-body">
                            <form action="" method="post">
                                <div class="">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1" class="form-label">Username</label>
                                        <input type="text" name="username" class="form-control" id="exampleInputEmail1" placeholder="Enter username" value="<?php echo  $admin['username']; ?>" required maxlength="40">
                                    </div>
                                    <div class="form-group" id="Password-toggle">
                                        <label for="" class="form-label">Password </label>
                                        <div class="input-group">
                                            <a href="#" class="input-group-text" id="togglePassword">
                                                <i class="fe fe-eye-off" aria-hidden="true"></i>
                                            </a>
                                            <!-- Hidden input for the existing password (no name attribute) -->
                                            <input type="hidden" value="<?php echo $admin['password']; ?>">
                                            <!-- Password input for the new password (name attribute is "new_password") -->
                                            <input class="form-control" name="new_password" type="password" id="passwordInput" placeholder="Enter Password" minlength="8" maxlength="20">
                                        </div>
                                    </div>
                                </div>
                                <button name="update" type="submit" class="btn btn-primary btn-block">Update</button>
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