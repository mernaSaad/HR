<?php
// Includes
include_once "main/initial.php";
include_once "includes/head.php";

// Function to securely hash passwords
function hashPassword($password)
{
	return password_hash($password, PASSWORD_DEFAULT);
}

$loginError = ""; // Initialize the login error message

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$inputUsername = $_POST['username'];
	$inputPassword = $_POST['password'];

	// Fetch hashed password from the database based on the provided username
	$stmt = $con->prepare("SELECT password FROM admins WHERE username = :username");
	$stmt->bindParam(':username', $inputUsername);
	$stmt->execute();
	$admin = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($admin) {
		// Verify the entered password against the hashed password from the database
		if (password_verify($inputPassword, $admin['password'])) {

			// Password is correct, set the username in the session and redirect to index.php

			$_SESSION['username'] = $inputUsername;

			// Set a session variable to indicate admin status

			$_SESSION['is_admin'] = true;

			header("Location: index.php");

			exit();
		} else {
			header("Location: login.php?do=error");
		}
	} else {
		header("Location: login.php?do=error");
	}
}
if (isset($_GET['do'])) {
    if ($_GET['do'] == "error") {
        echo "<script>
                Swal.fire({
                icon: 'error',
                title: 'username or password incorrect',
                text:'',
                });    
            </script>";
    }
}
?>

<div class="page responsive-log relative error-page3">
	<div class="row no-gutters">
		<div class="col-xl-6  d-xl-block d-none log-image1">
			<div class="cover-image h-100vh" data-bs-image-src="assets/images/photos/bg-img4.jpg">
				<div class="container">
					<div class="customlogin-imgcontent">
						<h1 class="mb-3 fs-35 text-white">Welcome To ZAD90</h1>
                        <h2 class="text-white-50">Design and Constructions</h2>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-6 bg-white log-image1">
			<div class="container">
				<div class="customlogin-content pt-5 pt-xl-9">
					<div class="pt-4 pb-2 ps-2 w-50">
						<a class="header-brand" href="index.php">
							<img src="assets/images/brand/login.png" class="header-brand-img custom-logo" alt="zad90logo">
							<!-- <img src="assets/images/brand/logo-white.png" class="header-brand-img custom-logo-dark " alt="zad90logo"> -->
						</a>
					</div>
					<div class="p-4 pt-6">
						<h1 class="mb-2">Login</h1>
						<p class="text-muted">Sign In to your account</p>
					</div>
					<form action="" method="post" class="card-body pt-3" id="login" name="login">
						<div class="form-group">
							<label class="form-label"> Username</label>
							<div class="input-group mb-4">
								<div class="input-group">
									<a class="input-group-text"><i class="fe fe-user" aria-hidden="true"></i></a>
									<input class="form-control" name="username" type="text" placeholder="username" required>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="form-label">Password</label>
							<div class="input-group mb-4">
								<div class="input-group" id="Password-toggle">
									<a href="" class="input-group-text">
										<i class="fe fe-eye-off" aria-hidden="true"></i>
									</a>
									<input class="form-control" name="password" type="password" placeholder="Password" required>
								</div>
							</div>
						</div>
						<button name="login" type="submit" class="btn btn-primary btn-block">Login</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include_once "includes/footer.php"; ?>