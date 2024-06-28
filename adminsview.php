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
    $deleteStmt = $con->prepare("DELETE FROM admins WHERE id = :id");
    $deleteStmt->bindParam(':id', $deleteid);
    $deleteStmt->execute();
    header("Location: adminsview.php?do=del");
}

// Retrieve data from the "admins" table
$query = "SELECT * FROM admins";
$stmt = $con->query($query);
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<?php
if (isset($_GET['do'])) {
    if ($_GET['do'] == "done") {
        echo "<script>
                Swal.fire({
                icon: 'succuss',
                title: ' Admin Inserted Successfully ',
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
                title: ' Admin Updated Successfully ',
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
                title: ' Admin Deleted ',
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
                <div class="page-title">Admins<span class="font-weight-normal text-muted ms-2">Table</span></div>
            </div>
        </div>
        <!--End Page header-->

        <div class="container">
            <div class="row">
                <div class="col">
                    <!-- Row -->
                    <div class="row row-sm">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <h3 class="card-title">Admins DataTable</h3>
                                    <a class="btn btn-primary" href="adminsadd.php">Add Admin</a>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered text-nowrap border-bottom" id="responsive-datatable">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Controls</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($admins as $admin) : ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($admin['id']); ?></td>
                                                        <td><?php echo htmlspecialchars($admin['username']); ?></td>
                                                        <td>
                                                            <a class="btn btn-warning" href="adminsedit.php?edit=<?php echo urlencode($admin['id']); ?>"><i class="feather feather-edit"></i></a>
                                                            <a class="btn btn-danger" href="#" onclick="showDeleteConfirmation('<?php echo urlencode($admin['id']); ?>');">
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
</div>

<?php include_once "includes/footer.php"; ?>
<script>
    function showDeleteConfirmation(adminid) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to delete this admin?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to the delete URL if the player confirms
                window.location.href = 'adminsview.php?delete=' + adminid;
            }
        });
    }
</script>