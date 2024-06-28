<!---Global-loader-->
<div id="global-loader">
	<img src="/zad90/assets/images/svgs/loader.svg" alt="loader">
</div>

<div class="page" id="mainNavShow">
	<div class="page-main">
		<!--app header-->
		<div class="app-header header sticky">
			<div class="container-fluid main-container">
				<div class="d-flex">
					<a class="header-brand" href="index.html">
						<!-- <img src="/zad90/assets/brand/logo.png" class="header-brand-img desktop-logo" alt="zad90logo"> -->
					</a>
					<div class="app-sidebar__toggle" data-bs-toggle="sidebar">
						<a class="open-toggle" href="javascript:void(0);">
							<i class="feather feather-menu"></i>
						</a>
						<a class="close-toggle" href="javascript:void(0);">
							<i class="feather feather-x"></i>
						</a>
					</div>

					<div class="d-flex order-lg-2 my-auto ms-auto">
						<button class="navbar-toggler nav-link icon navresponsive-toggler vertical-icon ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4" aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">
							<i class="fe fe-more-vertical header-icons navbar-toggler-icon"></i>
						</button>
						<div class="mb-0 navbar navbar-expand-lg navbar-nav-right responsive-navbar navbar-dark p-0">
							<div class="collapse navbar-collapse" id="navbarSupportedContent-4">
								<div class="d-flex ms-auto">
									<a class="nav-link  icon p-0 nav-link-lg d-lg-none navsearch" href="javascript:void(0);" data-bs-toggle="search">
										<i class="feather feather-search search-icon header-icon"></i>
									</a>
									<div class="dropdown  d-flex">
										<a class="nav-link icon theme-layout nav-link-bg layout-setting">
											<span class="dark-layout"><i class="fe fe-moon"></i></span>
											<span class="light-layout"><i class="fe fe-sun"></i></span>
										</a>
									</div>
									<div class="dropdown header-fullscreen">
										<a class="nav-link icon full-screen-link">
											<i class="feather feather-maximize fullscreen-button fullscreen header-icons"></i>
											<i class="feather feather-minimize fullscreen-button exit-fullscreen header-icons"></i>
										</a>
									</div>
									<div class="dropdown profile-dropdown">
										<a href="javascript:void(0);" class="nav-link pe-1 ps-0 leading-none" data-bs-toggle="dropdown">
											<span>
												<img src="/zad90/assets/images/svgs/support/license.svg" alt="img" class="avatar avatar-md bradius">
											</span>
										</a>
										<?php if (isset($_SESSION['username'])) { ?>
											<div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow animated">
												<div class="p-3 text-center border-bottom">
													<a href="" class="text-center user pb-0 font-weight-bold"><?php echo strtoupper($_SESSION['username']); ?></a>
													<?php if (isset($_SESSION['is_admin'])) { ?>
														<p class="text-center user-semi-title">Admin</p>
													<?php } else { ?>
														<p class="text-center user-semi-title">Employee</p>
													<?php } ?>
												</div>
												<a class="dropdown-item d-flex" href="logout.php">
													<i class="feather feather-power me-3 fs-16 my-auto"></i>
													<div class="mt-1">Sign Out</div>
												</a>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--/app header-->