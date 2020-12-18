<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/pages/index.css'); ?>">
<div class="container mt-5">
	<div class="indexbody">
		<h4 class="text-center text-dark">HOME PAGE OF IMS</h4>
		<hr>
		<span class="text-dark">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
			tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
			quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
			consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
			cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
			proident, sunt in culpa qui officia deserunt mollit anim id est laborum.Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
			tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
			quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
			consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
			cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
			proident, sunt in culpa qui officia deserunt mollit anim id est laborum.Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
			tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
			quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
			consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
			cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
			proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</span>
	</div>

	<div class="loginbtns text-center mt-3">
		<?php if (!$this->session->userdata('ims_logged_in')) : ?>
			<a href="<?php echo base_url('admin/login'); ?>" class="btn text-light" style="background-color:#00695C">Admin Login</a>
			<a href="<?php echo base_url('staff/login'); ?>" class="btn text-light" style="background-color:#00695C">Staff Login</a>
			<a href="<?php echo base_url('student/login'); ?>" class="btn text-light" style="background-color:#00695C">Student Login</a>
		<?php endif; ?>
	</div>
</div>