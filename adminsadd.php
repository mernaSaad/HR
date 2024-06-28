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

//add admin
if (isset($_POST['submit'])) {
    $username = check($_POST['username']);
    $password = check($_POST['password']);

    // Check if the username already exists
    $checkQuery = $con->prepare("SELECT username FROM admins WHERE username = :username");
    $checkQuery->bindParam(':username', $username);
    $checkQuery->execute();
    $existingUsername = $checkQuery->fetch(PDO::FETCH_ASSOC);

    if ($existingUsername) {
        // If the username already exists, show a Swal alert
        echo "<script>
                Swal.fire({
                icon: 'error',
                title: ' This username is taken.Please try another!',
                text: '',
                });
              </script>";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        if (!empty($_POST['link'])) {
            $link = $_POST['link'];
        }

        $stmt = $con->prepare("INSERT INTO admins(username, password) VALUES (:username, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword); // Store the hashed password
        $stmt->execute();

        $lastInsertedId = $con->lastInsertId();
        $_SESSION['admin_username'] = $username;
        $_SESSION['admin_id'] = $lastInsertedId;

        if (isset($link)) {
            header("Location: " . $link . "&do=done");
        } else {
            header("Location: adminsview.php?do=done");
        }
        exit();
    }
}

?>

<div class="app-content main-content">
    <div class="side-app main-container">
        <!--Page header-->
        <div class="page-header d-xl-flex d-block">
            <div class="page-leftheader">
                <div class="page-title">Admins<span class="font-weight-normal text-muted ms-2">Add</span></div>
            </div>
        </div>
        <!--End Page header-->
        <!-- contaner of form add admins -->
        <div class="container mt-5">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-bottom-0">
                            <h4 class="card-title">adding Form</h4>
                        </div>
                        <div class="card-body">
                            <form action="" method="post">
                                <div class="">
                                    <div class="form-group">
                                        <label for="" class="form-label">Username</label>
                                        <input type="text" name="username" class="form-control" id="username" placeholder="Enter username" required maxlength="40">
                                    </div>
                                    <div class="form-group" id="Password-toggle">
                                        <label for="" class="form-label">Password</label>
                                        <div class="input-group">
                                            <a href="" class="input-group-text">
                                                <i class="fe fe-eye-off" aria-hidden="true"></i>
                                            </a>
                                            <input class="form-control" name="password" type="password" placeholder="Password" required minlength="8" maxlength="20">
                                        </div>
                                    </div>
                                </div>
                                <button name="submit" type="submit" class="btn btn-primary btn-block">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
            <!-- End Row -->
        </div>
    </div>



    <?php include_once "includes/footer.php"; ?>