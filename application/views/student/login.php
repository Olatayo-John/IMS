<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/student/login.css'); ?>">
<div class="container col-md-4 stdlogin_con">
	<form method="post" action="<?php echo base_url('student/login'); ?>" class="mt-4">
		<input type="hidden" name="<?php echo $this->security->get_csrf_token_name() ?>" value="<?php echo $this->security->get_csrf_hash() ?>">
		<h4 class="text-center text-dark mb-4 adminlogin">
			<i class="fas fa-graduation-cap mr-2" style="font-size: 23px;"></i>LOGIN</h4>
		<div class="form-group">
			<label>Username</label>
			<input type="text" name="uname" class="form-control uname" value="<?php echo set_value('uname'); ?>" autofocus placeholder="Your username">
		</div>

		<div class="form-group">
			<label>Password</label>
			<input type="password" name="pwd" class="form-control pwd" placeholder="Your password">
		</div>
		<div class="logindiv text-center mt-4">
			<button class="btn text-light btn-block loginbtn mb-2" style="background-color: #00695C;">Login</button>
			<a href="<?php echo base_url(); ?>" class="fp_link text-danger">Forgot Password?</a>
		</div>
	</form>

	<div class="loginbtns text-center mt-3">
		<?php if (!$this->session->userdata('ims_logged_in')) : ?>
			<a href="<?php echo base_url('admin/login'); ?>" class="btn text-light adm_login mr-2" style="background-color: #00695C;">Admin Login</a>
			<a href="<?php echo base_url('staff/login'); ?>" class="btn text-light stf_login mr-2" style="background-color: #00695C;">Staff Login</a>
		<?php endif; ?>
	</div>
</div>