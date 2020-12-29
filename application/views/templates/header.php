<!DOCTYPE html>
<html>

<head>
	<title>IMS</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/templates/header.css') ?>">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<script src="https://kit.fontawesome.com/ca92620e44.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
	<script type="text/javascript">
		setTimeout(() => document.querySelector('.alert').remove(), 5000);
		document.onreadystatechange = function() {
			if (document.readyState !== "complete") {
				$(".spinnerdiv").show();
			} else {
				$(".spinnerdiv").fadeOut();
			}
		};
	</script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/templates/header.js'); ?>"></script>
	<?php if (!$webfavicon->value) : ?>
	<?php endif; ?>
	<?php if ($webfavicon->value) : ?>
		<link rel="icon" href="<?php echo base_url('assets/options/') . $webfavicon->value ?>">
	<?php endif; ?>
</head>
<div class="spinnerdiv">
	<div class="spinner-border text-danger"></div>
</div>

<body class="content" id="content">
	<nav class="navbar navbar-expand-lg navbar-light fixed-top" style="padding-left: 0;padding-right: 0;justify-content:initial">
		<i class="fa fa-bars menubtn ml-3 mr-3 text-light" status="true" style="cursor:pointer;font-size: 15px;">
			<?php if ($this->session->userdata('ims_role') == '1' && $this->session->userdata('ims_logged_in')) : ?>
				<?php if ($contacts->num_rows() > "0") : ?>
					<span class="badge badge-danger span_left_coll"><?php echo $contacts->num_rows(); ?>
					</span>
				<?php endif; ?>
			<?php endif; ?>
			<?php if ($this->session->userdata('ims_role') == '0' && $this->session->userdata('ims_logged_in')) : ?>
				<?php if ($adm_contacts->num_rows() > "0") : ?>
					<span class="badge badge-danger span_left_coll"><?php echo $adm_contacts->num_rows(); ?>
					</span>
				<?php endif; ?>
			<?php endif; ?>
			<?php if ($this->session->userdata('ims_role') == 'Staff' && $this->session->userdata('ims_logged_in')) : ?>
				<?php if ($stf_contacts->num_rows() > "0") : ?>
					<span class="badge badge-danger span_left_coll"><?php echo $stf_contacts->num_rows(); ?>
					</span>
				<?php endif; ?>
			<?php endif; ?>
			<?php if ($this->session->userdata('ims_role') == 'Student' && $this->session->userdata('ims_logged_in')) : ?>
				<?php if ($std_contacts->num_rows() > "0") : ?>
					<span class="badge badge-danger span_left_coll"><?php echo $std_contacts->num_rows(); ?>
					</span>
				<?php endif; ?>
			<?php endif; ?>
		</i>

		<?php if ($weblogo->value) : ?>
			<div class="navbar-brand" style="padding: 0;margin: 0">
				<img src="<?php echo base_url('assets/options/') . $weblogo->value ?>" id="<?php echo $weblogo->id ?>" width='50px' class="weblogo_nav">
				<h1 class="navbar-brand text-light text-uppercase webname_nav" style="margin: 0"><?php echo $webname->value ?></h1>
			</div>
		<?php endif; ?>
		<?php if (!$weblogo->value) : ?>
			<h1 class="navbar-brand text-light text-uppercase webname_nav" style="margin: 0"><?php echo $webname->value ?></h1>
		<?php endif; ?>

		<?php if ($this->session->userdata('ims_logged_in')) : ?>
			<button class="navbar-toggler ml-auto" data-target="#coll" data-toggle="collapse">
				<i class="fas fa-chevron-circle-down text-light" style="outline: none;"></i>
			</button>
		<?php endif; ?>

		<div class="navbar-collapse collapse" id="coll">
			<?php if ($this->session->userdata('ims_role') === '1' && $this->session->userdata('ims_logged_in')) : ?>
				<ul class="navbar-nav ml-auto">
					<a href="javascript:void(0)" class="request request_hide text-light a_right_nav mr-3" id="request">
						<i class="fas fa-envelope i_right_nav"></i>
						<span class="badge badge-danger span_right_nav">
							<?php echo $contacts->num_rows(); ?>
						</span>
					</a>
					<a href="javascript:void(0)" class="request upnav_request mr-3 text-light" id="request" style="display:none">
						<i class="fas fa-envelope"></i>Requests
					</a>
					<div class="upnav_user_profile_div request_hide mr-3">
						<a href="<?php echo base_url("admin/profile"); ?>">
							<img src="<?php echo base_url('assets/adm_uploads/') . $this->session->userdata('ims_profile_img') ?>" class="upnav_user_profile img-responsive">
						</a>
					</div>
					<a href="<?php echo base_url('admin/profile'); ?>" class="upnav_request mr-3 text-light">
						<i class="fas fa-user"></i>My Profile
					</a><a href="<?php echo base_url('admin/edit'); ?>" class="upnav_request mr-3 text-light">
						<i class="fas fa-user-edit"></i>Edit Profile
					</a>
					<a href="<?php echo base_url('admin/logout'); ?>" class="text-danger font-weight-bolder mr-3">
						<i class="fas fa-sign-out-alt"></i>Logout
					</a>
				</ul>
			<?php endif; ?>

			<?php if ($this->session->userdata('ims_role') === '0' && $this->session->userdata('ims_logged_in')) : ?>
				<ul class="navbar-nav ml-auto">
					<a href="<?php echo base_url('admin/response'); ?>" class="response response_hide mr-3 text-light a_right_nav" id="response">
						<i class="fas fa-envelope i_right_nav"></i>
						<?php if ($adm_contacts->num_rows() > "0") : ?>
							<span class="badge badge-danger span_right_nav">
								<?php echo $adm_contacts->num_rows(); ?>
							</span>
						<?php endif; ?>
					</a>
					<a href="<?php echo base_url('admin/response'); ?>" class="response upnav_response mr-3 text-light" id="response" style="display:none">
						<i class="fas fa-envelope"></i>Response
					</a>
					<div class="upnav_user_profile_div request_hide mr-3">
						<a href="<?php echo base_url("admin/profile"); ?>">
							<img src="<?php echo base_url('assets/adm_uploads/') . $this->session->userdata('ims_profile_img') ?>" class="upnav_user_profile img-responsive">
						</a>
					</div>
					<a href="<?php echo base_url('admin/profile'); ?>" class="upnav_response mr-3 text-light">
						<i class="fas fa-user"></i>My Profile
					</a>
					<a href="<?php echo base_url('admin/edit'); ?>" class="upnav_response mr-3 text-light">
						<i class="fas fa-user-edit"></i>Edit Profile
					</a>
					<a href="<?php echo base_url('admin/logout'); ?>" class="text-danger font-weight-bolder mr-3">
						<i class="fas fa-sign-out-alt"></i>Logout
					</a>
				</ul>
			<?php endif; ?>

			<?php if ($this->session->userdata('ims_role') === 'Staff' && $this->session->userdata('ims_logged_in')) : ?>
				<ul class="navbar-nav ml-auto">
					<a href="<?php echo base_url('staff/response'); ?>" class="response response_hide mr-3 text-light a_right_nav" id="response">
						<i class="fas fa-envelope i_right_nav"></i>
						<?php if ($stf_contacts->num_rows() > "0") : ?>
							<span class="badge badge-danger span_right_nav">
								<?php echo $stf_contacts->num_rows(); ?>
							</span>
						<?php endif; ?>
					</a>
					<a href="<?php echo base_url('staff/response'); ?>" class="response upnav_response mr-3 text-light" id="response" style="display:none">
						<i class="fas fa-envelope"></i>Response
					</a>
					<div class="upnav_user_profile_div response_hide mr-3">
						<a href="<?php echo base_url("staff/profile"); ?>">
							<img src="<?php echo base_url('assets/stf_uploads/') . $this->session->userdata('ims_profile_img') ?>" class="upnav_user_profile img-responsive">
						</a>
					</div>
					<a href="<?php echo base_url('staff/profile'); ?>" class="upnav_response mr-3 text-light">
						<i class="fas fa-user"></i>My Profile
					</a>
					<a href="<?php echo base_url('staff/edit'); ?>" class="upnav_response mr-3 text-light">
						<i class="fas fa-user-edit"></i>Edit Profile
					</a>
					<a href="<?php echo base_url('staff/logout'); ?>" class="text-danger font-weight-bolder mr-3">
						<i class="fas fa-sign-out-alt"></i>Logout
					</a>
				</ul>
			<?php endif; ?>

			<?php if ($this->session->userdata('ims_role') === 'Student' && $this->session->userdata('ims_logged_in')) : ?>
				<ul class="navbar-nav ml-auto">
					<a href="<?php echo base_url('student/response'); ?>" class="response response_hide text-light a_right_nav" id="response">
						<i class="fas fa-envelope i_right_nav"></i>
						<?php if ($std_contacts->num_rows() > "0") : ?>
							<span class="badge badge-danger span_right_nav">
								<?php echo $std_contacts->num_rows(); ?>
							</span>
						<?php endif; ?>
					</a>
					<a href="<?php echo base_url('student/response'); ?>" class="response upnav_response mr-3 text-light" id="response" style="display:none">
						<i class="fas fa-envelope"></i>Response
					</a>
					<div class="upnav_user_profile_div response_hide mr-3 ml-3">
						<a href="<?php echo base_url("student/profile"); ?>">
							<img src="<?php echo base_url('assets/std_uploads/') . $this->session->userdata('ims_profile_img') ?>" class="upnav_user_profile img-responsive">
						</a>
					</div>
					<a href="<?php echo base_url('student/profile'); ?>" class="upnav_response mr-3 text-light">
						<i class="fas fa-user"></i>My Profile
					</a><a href="<?php echo base_url('student/edit'); ?>" class="upnav_response mr-3 text-light">
						<i class="fas fa-user-edit"></i>Edit Profile
					</a>
					<a href="<?php echo base_url('student/logout'); ?>" class="text-danger font-weight-bolder mr-3">
						<i class="fas fa-sign-out-alt"></i>Logout
					</a>
				</ul>
			<?php endif; ?>
		</div>

		<div class="side-nav" id="side-nav">
			<ul class="side-nav-ul">
				<?php if ($this->session->userdata('ims_profile_img')) : ?>
					<?php if ($this->session->userdata('ims_role') == "1") : ?>
						<div class="user_profile_div mb-3">
							<a href="<?php echo base_url("admin/profile"); ?>">
								<img src="<?php echo base_url('assets/uploads/') . $this->session->userdata('ims_profile_img') ?>" class="user_profile">
							</a>
						</div>
					<?php endif; ?>
					<?php if ($this->session->userdata('ims_role') !== "1") : ?>
						<div class="user_profile_div mb-3">
							<a href="<?php echo base_url() . $this->session->userdata('ims_role') . "/profile"; ?>">
								<img src="<?php echo base_url('assets/uploads/') . $this->session->userdata('ims_profile_img') ?>" class="user_profile">
							</a>
						</div>
					<?php endif; ?>
					<li class="nav-item user_profile_name" style="display:none">
						<a href="" class="nav-link user_profile_name">
							<i class="fas fa-power-off text-success user_profile_name"></i>
							Welcome <?php echo $this->session->userdata('ims_uname') ?>
						</a>
					</li>
				<?php endif; ?>
				<?php if (!$this->session->userdata('ims_logged_in')) : ?>
					<li class="nav-item"><a href="<?php echo base_url('pages/index'); ?>" class="nav-link home">
							<i class="fas fa-university"></i>Home</a></li>
					<li class="nav-item"><a href="<?php echo base_url('admin/login'); ?>" class="nav-link">
							<i class="fas fa-user-shield"></i>Admin</a>
					</li>
					<li class="nav-item"><a href="<?php echo base_url('staff/login'); ?>" class="nav-link">
							<i class="fas fa-user-tie"></i>Staff</a>
					</li>
					<li class="nav-item">
						<a href="" class="nav-link dropdown-toggle" data-toggle="dropdown">
							<i class="fas fa-graduation-cap"></i>Student</a>
						<div class="dropdown">
							<div class="dropdown-menu" style="border-radius:0;border:none;margin:0;float:none;position:relative">
								<a href="<?php echo base_url('student/login'); ?>" class="dropdown-item student">Student Login</a>
								<a href="<?php echo base_url('student/register'); ?>" class="dropdown-item student">Student Register</a>
							</div>
						</div>
					</li>
				<?php endif; ?>

				<?php if ($this->session->userdata('ims_role') === '1' && $this->session->userdata('ims_logged_in')) : ?>
					<li class="nav-item"><a href="<?php echo base_url('admin/index'); ?>" class="nav-link">
							<i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
					<li class="nav-item">
						<a href="" class="nav-link dropdown-toggle" data-toggle="dropdown">
							<i class="fas fa-user-tie"></i>Staff
						</a>
						<div class="dropdown">
							<div class="dropdown-menu" style="border-radius:0;border:none;margin:0;float:none;position:relative">
								<a href="<?php echo base_url('staff'); ?>" class="dropdown-item drp_items">All Staffs</a>
								<a href="<?php echo base_url('staff/new'); ?>" class="dropdown-item drp_items">New Staff</a>
							</div>
						</div>
					</li>

					<li class="nav-item">
						<a href="" class="nav-link dropdown-toggle" data-toggle="dropdown">
							<i class="fas fa-graduation-cap"></i>Student
						</a>
						<div class="dropdown">
							<div class="dropdown-menu" style="border-radius:0;border:none;margin:0;float:none;position:relative">
								<a href="<?php echo base_url('student'); ?>" class="dropdown-item drp_items">All Students</a>
								<a href="<?php echo base_url('student/new'); ?>" class="dropdown-item drp_items">New Student</a>
							</div>
						</div>
					</li>
					<li class="nav-item">
						<a href="javascript:void(0)" class="nav-link request" id="request">
							<i class="fas fa-envelope"></i>Requests
							<span class="badge badge-danger sidenav_badge">
								<?php echo $contacts->num_rows(); ?>
							</span>
						</a>
					</li>
					<li class="nav-item"><a href="<?php echo base_url('admin/profile'); ?>" class="nav-link">
							<i class="fas fa-user"></i>Profile</a>
					</li>
					<li class="nav-item"><a href="<?php echo base_url('settings/index'); ?>" class="nav-link">
							<i class="fas fa-cog"></i>Settings</a>
					</li>
				<?php endif; ?>

				<?php if ($this->session->userdata('ims_role') === "Staff") : ?>
					<li class="nav-item"><a href="<?php echo base_url('staff/index'); ?>" class="nav-link">
							<i class="fas fa-tachometer-alt"></i>Dashboard</a>
					</li>
					<li class="nav-item">
						<a href="" class="nav-link dropdown-toggle" data-toggle="dropdown">
							<i class="fas fa-graduation-cap"></i>Student
						</a>
						<div class="dropdown">
							<div class="dropdown-menu" style="border-radius:0;border:none;margin:0;float:none;position:relative">
								<a href="<?php echo base_url('student'); ?>" class="dropdown-item drp_items">All Students
								</a>
								<a href="<?php echo base_url('student/new'); ?>" class="dropdown-item drp_items">New Student
								</a>
							</div>
						</div>
					</li>
					<li class="nav-item">
						<a href="<?php echo base_url('staff/response'); ?>" class="nav-link response" id="response">
							<i class="fas fa-envelope"></i>Response
							<span class="badge badge-danger sidenav_badge">
								<?php echo $stf_contacts->num_rows(); ?>
							</span>
						</a>
					</li>
					<li class="nav-item"><a href="<?php echo base_url('staff/profile'); ?>" class="nav-link">
							<i class="fas fa-user"></i>Profile</a>
					</li>
					<li class="nav-item"><a href="<?php echo base_url('settings/index'); ?>" class="nav-link">
							<i class="fas fa-cog"></i>Settings</a>
					</li>
				<?php endif; ?>

				<?php if ($this->session->userdata('ims_role') === "0") : ?>
					<li class="nav-item"><a href="<?php echo base_url('admin/index'); ?>" class="nav-link">
							<i class="fas fa-tachometer-alt"></i>Dashboard</a>
					</li>
					<li class="nav-item">
						<a href="" class="nav-link dropdown-toggle" data-toggle="dropdown">
							<i class="fas fa-user-tie"></i>Staff
						</a>
						<div class="dropdown">
							<div class="dropdown-menu" style="border-radius:0;border:none;margin:0;float:none;position:relative">
								<a href="<?php echo base_url('staff'); ?>" class="dropdown-item drp_items">All Staffs</a>
								<a href="<?php echo base_url('staff/new'); ?>" class="dropdown-item drp_items">New Staff</a>
							</div>
						</div>
					</li>
					<li class="nav-item">
						<a href="" class="nav-link dropdown-toggle" data-toggle="dropdown">
							<i class="fas fa-graduation-cap"></i>Student
						</a>
						<div class="dropdown">
							<div class="dropdown-menu" style="border-radius:0;border:none;margin:0;float:none;position:relative">
								<a href="<?php echo base_url('student'); ?>" class="dropdown-item drp_items">All Students</a>
								<a href="<?php echo base_url('student/new'); ?>" class="dropdown-item drp_items">New Student</a>
							</div>
						</div>
					</li>
					<li class="nav-item">
						<a href="<?php echo base_url('admin/response'); ?>" class="nav-link response" id="response">
							<i class="fas fa-envelope"></i>Response
							<span class="badge badge-danger sidenav_badge">
								<?php echo $adm_contacts->num_rows(); ?>
							</span>
						</a>
					</li>
					<li class="nav-item"><a href="<?php echo base_url('admin/profile'); ?>" class="nav-link">
							<i class="fas fa-user"></i>Profile</a>
					</li>
					<li class="nav-item"><a href="<?php echo base_url('settings/index'); ?>" class="nav-link">
							<i class="fas fa-cog"></i>Settings</a>
					</li>
				<?php endif; ?>

				<?php if ($this->session->userdata('ims_role') === "Student") : ?>
					<li class="nav-item"><a href="<?php echo base_url('student/dashboard'); ?>" class="nav-link">
							<i class="fas fa-tachometer-alt"></i>Dashboard</a>
					</li>
					<li class="nav-item">
						<a href="<?php echo base_url('student/response'); ?>" class="nav-link response" id="response">
							<i class="fas fa-envelope"></i>Response
							<span class="badge badge-danger sidenav_badge">
								<?php echo $std_contacts->num_rows(); ?>
							</span>
						</a>
					</li>
					<li class="nav-item"><a href="<?php echo base_url('student/profile'); ?>" class="nav-link">
							<i class="fas fa-user"></i>Profile</a>
					</li>
					<li class="nav-item"><a href="<?php echo base_url('settings/index'); ?>" class="nav-link">
							<i class="fas fa-cog"></i>Settings</a>
					</li>
				<?php endif; ?>

				<li class="nav-item"><a href="<?php echo base_url('pages/support'); ?>" class="nav-link">
						<i class="fas fa-question-circle"></i>Support</a>
				</li>
				<?php if ($this->session->userdata('ims_role') == "1" || $this->session->userdata('ims_role') == "0") : ?>
					<li class="nav-item"><a href="<?php echo base_url('admin/logout'); ?>" class="nav-link text-danger">
							<i class="fas fa-power-off"></i>Logout</a>
					</li>
				<?php endif; ?>
				<?php if ($this->session->userdata('ims_role') == "Staff") : ?>
					<li class="nav-item"><a href="<?php echo base_url('staff/logout'); ?>" class="nav-link text-danger">
							<i class="fas fa-power-off"></i>Logout</a>
					</li>
				<?php endif; ?>
				<?php if ($this->session->userdata('ims_role') == "Student") : ?>
					<li class="nav-item"><a href="<?php echo base_url('student/logout'); ?>" class="nav-link text-danger">
							<i class="fas fa-power-off"></i>Logout</a></li>
				<?php endif; ?>
			</ul>
		</div>
	</nav>

	<div class="container modal_div col-md-4">
		<form action="<?php echo base_url('admin/reach_admin'); ?>" method="post" class="reachadmin">
			<input type="hidden" class="csrf_token" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
			<div class="form-group mt-4">
				<h6 class="text-danger text-center form-group">Please fill this form in a decent manner</h6>
				<label class="font-weight-bolder" style="color: black">
					<span class="text-danger font-weight-bolder">* </span>Subject</label>
				<input type="text" name="subj" class="form-control subj" placeholder="Subject">
			</div>
			<div class="form-group">
				<label class="font-weight-bolder" style="color: black">
					<span class="text-danger font-weight-bolder">* </span>Department</label>
				<?php if ($this->session->userdata('ims_role') == "0") : ?>
					<input type="text" name="dept" class="form-control dept" value="Admin" readonly>
				<?php endif; ?>
				<?php if ($this->session->userdata('ims_role') == "Staff") : ?>
					<input type="text" name="dept" class="form-control dept" value="Staff" readonly>
				<?php endif; ?>
				<?php if ($this->session->userdata('ims_role') == "Student") : ?>
					<input type="text" name="dept" class="form-control dept" value="Student" readonly>
				<?php endif; ?>
			</div>
			<div class="form-group">
				<label class="font-weight-bolder" style="color: black">
					<span class="text-danger font-weight-bolder">* </span>Message</label>
				<textarea class="form-control msg" rows="5" name="msg" placeholder="Your message..."></textarea>
			</div>
			<div class="form-group d-flex justify-content-between mt-3">
				<button class="btn btn-dark closemodalbtn" type="button">Close</button>
				<button class="btn sendmodalbtn text-light" style="background-color: #0B3954;">Submit</button>
			</div>
		</form>
	</div>

	<div class="container">
		<div id="requestdiv" class="container">
			<button class="close requestbtn text-dark" style="outline: none;">&times;</button>
			<h5 class="text-center font-weight-bolder mt-2" style="color :#00695C">All Requests</h5>
			<?php if ($contacts->num_rows() == "0") : ?>
				<div class="noti text-center" style="margin: 8px;">
					<label class="font-weight-bolder noti_head">No new notifications</label>
				</div>
			<?php endif; ?>
			<?php foreach ($contacts->result_array() as $cnt) : ?>
				<div class="noti" style="margin: 8px;">
					<?php if ($cnt['department'] == "Admin") : ?>
						<label class="font-weight-bolder noti_head"><?php echo $cnt['department'] ?></label>
						<span class="font-weight-bolder text-danger">(<?php echo $cnt['subj'] ?>)</span>
						<a href="<?php echo base_url('admin/adminrequest/'); ?>" class="noti_link" id="<?php echo $cnt['id']; ?>">
							<p><?php echo word_limiter($cnt['msg'], 10); ?></p>
						</a>
					<?php endif; ?>
					<?php if ($cnt['department'] == "Staff") : ?>
						<label class="font-weight-bolder noti_head"><?php echo $cnt['department'] ?></label>
						<span class="font-weight-bolder text-danger">(<?php echo $cnt['subj'] ?>)</span>
						<a href="<?php echo base_url('admin/staffrequest/'); ?>" class="noti_link" id="<?php echo $cnt['id']; ?>">
							<p><?php echo word_limiter($cnt['msg'], 10); ?></p>
						</a>
					<?php endif; ?>
					<?php if ($cnt['department'] == "Student") : ?>
						<label class="font-weight-bolder noti_head"><?php echo $cnt['department'] ?></label>
						<span class="font-weight-bolder text-danger">(<?php echo $cnt['subj'] ?>)</span>
						<a href="<?php echo base_url('admin/studentrequest/'); ?>" class="noti_link" id="<?php echo $cnt['id']; ?>">
							<p><?php echo word_limiter($cnt['msg'], 10); ?></p>
						</a>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>

		<?php if (form_error('uname') || form_error('pwd') || form_error('fname') || form_error('lname') || form_error('mobile')) : ?>
			<div class="alert alert-danger">
				<button class="close" data-dismiss="alert">&times;</button>
				<i class="fas fa-exclamation-circle"></i>
				<strong>Please fill in the required fields</strong>
			</div>
		<?php endif; ?>
		<?php if (form_error('email')) : ?>
			<div class="alert alert-danger">
				<button class="close" data-dismiss="alert">&times;</button>
				<i class="fas fa-exclamation-circle"></i>
				<strong><?php echo form_error('email') ?></strong>
			</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('logo_upload_err')) : ?>
			<div class="alert alert-danger">
				<button class="close" data-dismiss="alert">&times;</button>
				<i class="fas fa-exclamation-circle"></i>
				<strong><?php echo $this->session->flashdata('logo_upload_err'); ?></strong>
			</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('favicon_upload_err')) : ?>
			<div class="alert alert-danger">
				<button class="close" data-dismiss="alert">&times;</button>
				<i class="fas fa-exclamation-circle"></i>
				<strong><?php echo $this->session->flashdata('favicon_upload_err'); ?></strong>
			</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('invalid_login')) : ?>
			<div class="alert alert-danger">
				<button class="close" data-dismiss="alert">&times;</button>
				<i class="fas fa-exclamation-circle"></i>
				<strong><?php echo $this->session->flashdata('invalid_login'); ?></strong>
			</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('inactive_acct')) : ?>
			<div class="alert alert-danger">
				<button class="close" data-dismiss="alert">&times;</button>
				<i class="fas fa-exclamation-circle"></i>
				<strong><?php echo $this->session->flashdata('inactive_acct'); ?></strong>
			</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('acc_denied')) : ?>
			<div class="alert alert-danger">
				<button class="close" data-dismiss="alert">&times;</button>
				<i class="fas fa-exclamation-circle"></i>
				<strong><?php echo $this->session->flashdata('acc_denied'); ?></strong>
			</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('login_first')) : ?>
			<div class="alert alert-danger">
				<button class="close" data-dismiss="alert">&times;</button>
				<i class="fas fa-exclamation-circle"></i>
				<strong><?php echo $this->session->flashdata('login_first'); ?></strong>
			</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('valid_login')) : ?>
			<div class="alert alert-success">
				<button class="close" data-dismiss="alert">&times;</button>
				<i class="fas fa-check-circle"></i>
				<strong><?php echo $this->session->flashdata('valid_login'); ?></strong>
			</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('logout')) : ?>
			<div class="alert alert-danger">
				<button class="close" data-dismiss="alert">&times;</button>
				<i class="fas fa-exclamation-circle"></i>
				<strong><?php echo $this->session->flashdata('logout'); ?></strong>
			</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('formerr')) : ?>
			<div class="alert alert-danger">
				<button class="close" data-dismiss="alert">&times;</button>
				<i class="fas fa-exclamation-circle"></i>
				<strong><?php echo $this->session->flashdata('formerr'); ?></strong>
			</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('formsucc')) : ?>
			<div class="alert alert-success">
				<button class="close" data-dismiss="alert">&times;</button>
				<i class="fas fa-check-circle"></i>
				<strong><?php echo $this->session->flashdata('formsucc'); ?></strong>
			</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('logodel')) : ?>
			<div class="alert alert-danger">
				<button class="close" data-dismiss="alert">&times;</button>
				<i class="fas fa-exclamation-circle"></i>
				<strong><?php echo $this->session->flashdata('logodel'); ?></strong>
			</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('icondel')) : ?>
			<div class="alert alert-danger">
				<button class="close" data-dismiss="alert">&times;</button>
				<i class="fas fa-exclamation-circle"></i>
				<strong><?php echo $this->session->flashdata('icondel'); ?></strong>
			</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('invpwdchange')) : ?>
			<div class="alert alert-danger">
				<button class="close" data-dismiss="alert">&times;</button>
				<i class="fas fa-exclamation-circle"></i>
				<strong><?php echo $this->session->flashdata('invpwdchange'); ?></strong>
			</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('succpwdchange')) : ?>
			<div class="alert alert-success">
				<button class="close" data-dismiss="alert">&times;</button>
				<i class="fas fa-check-circle"></i>
				<strong><?php echo $this->session->flashdata('succpwdchange'); ?></strong>
			</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('p_update_err')) : ?>
			<div class="alert alert-danger">
				<button class="close" data-dismiss="alert">&times;</button>
				<i class="fas fa-exclamation-circle"></i>
				<strong><?php echo $this->session->flashdata('p_update_err'); ?></strong>
			</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('p_update_succ')) : ?>
			<div class="alert alert-success">
				<button class="close" data-dismiss="alert">&times;</button>
				<i class="fas fa-check-circle"></i>
				<strong><?php echo $this->session->flashdata('p_update_succ'); ?></strong>
			</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('pwd_update_err')) : ?>
			<div class="alert alert-danger">
				<button class="close" data-dismiss="alert">&times;</button>
				<i class="fas fa-exclamation-circle"></i>
				<strong><?php echo $this->session->flashdata('pwd_update_err'); ?></strong>
			</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('pwd_update_succ')) : ?>
			<div class="alert alert-success">
				<button class="close" data-dismiss="alert">&times;</button>
				<i class="fas fa-check-circle"></i>
				<strong><?php echo $this->session->flashdata('pwd_update_succ'); ?></strong>
			</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('msg_send_fail')) : ?>
			<div class="alert alert-danger">
				<button class="close" data-dismiss="alert">&times;</button>
				<i class="fas fa-exclamation-circle"></i>
				<strong><?php echo $this->session->flashdata('msg_send_fail'); ?></strong>
			</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('msg_send_succ')) : ?>
			<div class="alert alert-success">
				<button class="close" data-dismiss="alert">&times;</button>
				<i class="fas fa-check-circle"></i>
				<strong><?php echo $this->session->flashdata('msg_send_succ'); ?></strong>
			</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('reply_failed')) : ?>
			<div class="alert alert-danger">
				<button class="close" data-dismiss="alert">&times;</button>
				<i class="fas fa-exclamation-circle"></i>
				<strong><?php echo $this->session->flashdata('reply_failed'); ?></strong>
			</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('reply_sent')) : ?>
			<div class="alert alert-success">
				<button class="close" data-dismiss="alert">&times;</button>
				<i class="fas fa-check-circle"></i>
				<strong><?php echo $this->session->flashdata('reply_sent'); ?></strong>
			</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('activity_log_del_err')) : ?>
			<div class="alert alert-danger">
				<button class="close" data-dismiss="alert">&times;</button>
				<i class="fas fa-exclamation-circle"></i>
				<strong><?php echo $this->session->flashdata('activity_log_del_err'); ?></strong>
			</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('activity_log_del_succ')) : ?>
			<div class="alert alert-success">
				<button class="close" data-dismiss="alert">&times;</button>
				<i class="fas fa-check-circle"></i>
				<strong><?php echo $this->session->flashdata('activity_log_del_succ'); ?></strong>
			</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('request_del_err')) : ?>
			<div class="alert alert-danger">
				<button class="close" data-dismiss="alert">&times;</button>
				<i class="fas fa-exclamation-circle"></i>
				<strong><?php echo $this->session->flashdata('request_del_err'); ?></strong>
			</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('request_del_succ')) : ?>
			<div class="alert alert-success">
				<button class="close" data-dismiss="alert">&times;</button>
				<i class="fas fa-check-circle"></i>
				<strong><?php echo $this->session->flashdata('request_del_succ'); ?></strong>
			</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('std_del_succ')) : ?>
			<div class="alert alert-success">
				<button class="close" data-dismiss="alert">&times;</button>
				<i class="fas fa-check-circle"></i>
				<strong><?php echo $this->session->flashdata('std_del_succ'); ?></strong>
			</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('std_del_err')) : ?>
			<div class="alert alert-danger">
				<button class="close" data-dismiss="alert">&times;</button>
				<i class="fas fa-exclamation-circle"></i>
				<strong><?php echo $this->session->flashdata('std_del_err'); ?></strong>
			</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('std_stat_succ')) : ?>
			<div class="alert alert-success">
				<button class="close" data-dismiss="alert">&times;</button>
				<i class="fas fa-check-circle"></i>
				<strong><?php echo $this->session->flashdata('std_stat_succ'); ?></strong>
			</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('std_stat_err')) : ?>
			<div class="alert alert-danger">
				<button class="close" data-dismiss="alert">&times;</button>
				<i class="fas fa-exclamation-circle"></i>
				<strong><?php echo $this->session->flashdata('std_stat_err'); ?></strong>
			</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('new_std_succ')) : ?>
			<div class="alert alert-success">
				<button class="close" data-dismiss="alert">&times;</button>
				<i class="fas fa-check-circle"></i>
				<strong><?php echo $this->session->flashdata('new_std_succ'); ?></strong>
			</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('new_std_err')) : ?>
			<div class="alert alert-danger">
				<button class="close" data-dismiss="alert">&times;</button>
				<i class="fas fa-exclamation-circle"></i>
				<strong><?php echo $this->session->flashdata('new_std_err'); ?></strong>
			</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('mail_err')) : ?>
			<div class="alert alert-danger">
				<button class="close" data-dismiss="alert">&times;</button>
				<i class="fas fa-exclamation-circle"></i>
				<strong><?php echo $this->session->flashdata('mail_err'); ?></strong>
			</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('deact_account_err')) : ?>
			<div class="alert alert-danger">
				<button class="close" data-dismiss="alert">&times;</button>
				<i class="fas fa-exclamation-circle"></i>
				<strong><?php echo $this->session->flashdata('deact_account_err'); ?></strong>
			</div>
		<?php endif; ?>

	</div>

	<form method="post" action="">
		<input type="hidden" name="<?php echo $this->security->get_csrf_token_name() ?>" value="<?php echo $this->security->get_csrf_hash() ?>" class="csrf_token">
	</form>

	<div class="web_content" id="web_content">