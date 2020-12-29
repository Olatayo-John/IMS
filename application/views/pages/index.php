<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/pages/index.css'); ?>">
<div class="container mt-5">
	<div class="indexbody">
		<h4 class="text-center text-dark">HOME PAGE</h4>
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
			proident, sunt in culpa qui officia deserunt mollit anim id est laborum.Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
			tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
			quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
			consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
			cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
			proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</span>
	</div>

	<div class="loginbtns text-center mt-3" style="display:none">
		<?php if (!$this->session->userdata('ims_logged_in')) : ?>
			<a href="<?php echo base_url('admin/login'); ?>" class="btn text-light" style="background-color:#00695C">Admin Login</a>
			<a href="<?php echo base_url('staff/login'); ?>" class="btn text-light" style="background-color:#00695C">Staff Login</a>
			<a href="<?php echo base_url('student/login'); ?>" class="btn text-light" style="background-color:#00695C">Student Login</a>
		<?php endif; ?>
	</div>

	<div class="loginbtns text-center mt-3">
		<?php if (!$this->session->userdata('ims_logged_in')) : ?>
			<a href="" class="btn text-light adm_login" style="background-color:#00695C">Admin Login</a>
			<a href="" class="btn text-light stf_login" style="background-color:#00695C">Staff Login</a>
			<a href="" class="btn text-light std_login" style="background-color:#00695C">Student Login</a>
		<?php endif; ?>
	</div>


	<div class="modal adm_div">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<span class="close_x">
					<i class="fas fa-times adm_close"></i>
				</span>
				<div class="db_response_div text-danger text-center" style="display:none">
					<i class="fas fa-bell"></i>
					<span class="db_response"></span>
				</div>

				<form action="" method="post" class="login_form">
					<input type="hidden" name="<?php echo $this->security->get_csrf_token_name() ?>" value="<?php echo $this->security->get_csrf_hash() ?>" class="csrf_token">
					<div class="form-group">
						<label>Username</label>
						<input type="text" name="uname" class="form-control uname" value="<?php echo set_value('uname'); ?>" autofocus placeholder="Your username">
					</div>

					<div class="form-group">
						<label>Password</label>
						<input type="password" name="pwd" class="form-control pwd" placeholder="Your password">
					</div>
					<div class="logindiv text-center mt-4">
						<button class="btn text-light btn-block modal_login_btn mb-2" style="background-color: #00695C;">Login</button>
						<a href="<?php echo base_url(); ?>" class="fp_link text-primary">Forgot Password?</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo base_url('assets/js/pages/index.js'); ?>" type="text/javascript">
</script>
<script>
	$(document).ready(function() {
		$('a.adm_login').click(function(e) {
			e.preventDefault();
			var url = "<?php echo base_url('admin/modal_login'); ?>";
			$('.login_form').attr('action', url);
			$('.adm_div').fadeIn();
		});

		$('a.stf_login').click(function(e) {
			e.preventDefault();
			var url = "<?php echo base_url('staff/modal_login'); ?>";
			$('.login_form').attr('action', url);
			$('.adm_div').fadeIn();
		});

		$('a.std_login').click(function(e) {
			e.preventDefault();
			var url = "<?php echo base_url('student/modal_login'); ?>";
			$('.login_form').attr('action', url);
			$('.adm_div').fadeIn();
		});

		$('.modal_login_btn').click(function(e) {
			e.preventDefault();
			var uname = $('.uname').val();
			var pwd = $('.pwd').val();
			var ajax_form_url = $('.login_form').attr('action');
			var csrfName = $('.csrf_token').attr('name');
			var csrfHash = $('.csrf_token').val();

			if (uname == "" || uname == null) {
				$('.uname').css('border', '1px solid red');
				return false;
			} else {
				$('.uname').css('border', '1px solid #00695C');
			}
			if (pwd == "" || pwd == null) {
				$('.pwd').css('border', '1px solid red');
				return false;
			} else {
				$('.pwd').css('border', '1px solid #00695C');
			}

			$.ajax({
				method: "post",
				url: ajax_form_url,
				dataType: "json",
				data: {
					[csrfName]: csrfHash,
					uname: uname,
					pwd: pwd,
				},
				success: function(data) {
					$('.csrf_token').val(data.token);
					if (data.db_req == false) {
						$('.db_response').html("Incorect username/password");
						$('.db_response_div').fadeIn();
					} else if (data.db_req == 'inactive') {
						$('.db_response').html("Your account is inactive");
						$('.db_response_div').fadeIn();
					} else {
						// console.log(data.redirect_url);
						window.location.assign(data.redirect_url);
					}
				},
				error: function(data) {
					alert("error")
				}
			})
		});

	});
</script>