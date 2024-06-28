<!--app-sidebar-->
<div class="sticky">
	<aside class="app-sidebar ">
		<div class="app-sidebar__logo" style="background-color: white;">
			<a class="" href="index.php">
				<img src="/zad90/assets/images/brand/login.png" class="header-brand-img desktop-logo" alt="zad90logo" width="120" height="">
			</a>
		</div>

		<div class="app-sidebar3">
			<div class="main-menu">
				<div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
						<path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
					</svg>
				</div>
				<ul class="side-menu">
					<!-- container of form add users -->
					<?php
					if (isset($_SESSION['location_id']) || isset($_SESSION['location_id'])) {
						$sql = "SELECT * FROM locations WHERE id=:location_id";
						$stmt = $con->prepare($sql);
						$stmt->bindParam(':location_id', $_SESSION['location_id'], PDO::PARAM_INT);
						$stmt->execute();
						$location = $stmt->fetch(PDO::FETCH_ASSOC);
					?>
						<div class="container ">
							<div class="card">
								<div class="card-body d-flex justify-content-center">
									<!-- <div class="col"> -->
									<!-- <div class=""> <span class="fs-14 font-weight-semibold"></span> -->
									<h4><?php echo $location['name']; ?></h4>
									<!-- </div> -->
									<!-- </div> -->
								</div>
							</div>
						</div>
					<?php
					}
					?>
					<?php
					if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == true) {
					?>
						<li class="side-item side-item-category mt-4">Dashboards</li>
						<li class="slide">
							<a class="side-menu__item" href="adminsview.php">
								<i class="feather feather-user sidemenu_icon"></i>
								<span class="side-menu__label">Admins</span>
							</a>
						</li>
						<li class="slide">
							<a class="side-menu__item" href="employeesview.php">
								<i class="feather feather-users sidemenu_icon"></i>
								<span class="side-menu__label">Staff List</span>
							</a>
						</li>
						<li class="slide">
							<a class="side-menu__item" href="attendanceview.php">
								<i class="ion  ion-clipboard sidemenu_icon"></i>
								<span class="side-menu__label">Attendance Report</span>
							</a>
						</li>
						<li class="slide">
							<a class="side-menu__item" href="departureview.php">
								<i class="fe fe-clipboard sidemenu_icon"></i>
								<span class="side-menu__label">Early Departure Report</span>
							</a>
						</li>
						<li class="slide">
							<a class="side-menu__item" href="salaryreport.php">
								<i class="fa fa-cc-visa sidemenu_icon"></i>
								<span class="side-menu__label">Salary-Report</span>
							</a>
						</li>
						<li class="slide">
							<a class="side-menu__item" href="locationsview.php">
								<i class="fe fe-globe sidemenu_icon"></i>
								<span class="side-menu__label">Locations</span>
							</a>
						</li>
					<?php } ?>
					<?php if (isset($_SESSION['employeeid'])) : ?>
						<li class="slide">
							<a class="side-menu__item" href="employeesalaryreport.php">
								<i class="fa fa-cc-visa sidemenu_icon"></i>
								<span class="side-menu__label">Salary-Report</span>
							</a>
						</li>
						<li class="slide">
							<a class="side-menu__item" href="exitlocation.php">
								<i class="si si-logout sidemenu_icon"></i>
								<span class="side-menu__label">Exit-Location</span>
							</a>
						</li>
					<?php endif; ?>
					<li class="slide">
						<a class="side-menu__item" href="logout.php">
							<i class="si si-logout sidemenu_icon"></i>
							<span class="side-menu__label">Logout</span>
						</a>
					</li>
				</ul>
			</div>
	</aside>
</div>
<!--app-sidebar closed-->