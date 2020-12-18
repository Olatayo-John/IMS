<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/admin/request.css') ?>">
<div class="container-fluid mt-4">
	<h4 class="text-center text-light mt-2 mb-2">STAFF REQUESTS</h4>
	<div class="row mb-5">
		<div class="link_tab col-md-3 d-flex flex-column">
			<?php foreach ($stf_contacts->result_array() as $cnt) : ?>
				<div class="card bg-light" style="border-radius: 0;">
					<div class="card-body">
						<span class="font-weight-bolder text-danger card-title"><?php echo $cnt['subj'] ?></span>
						<?php if ($cnt['seen'] == 0) : ?>
							<i class="fas fa-envelope text-danger" id="<?php echo $cnt['id']; ?>"></i>
							<p class="card-text font-weight-bolder" id="<?php echo $cnt['id']; ?>"><?php echo word_limiter($cnt['msg'], 10); ?></p>
							<div class="d-flex justify-content-between mb-0 mt-0" style="padding: 0">
								<a href="" class="btn btn-primary rmbtn" id="<?php echo $cnt['id']; ?>">Read</a>
								<i class="fas fa-trash-alt text-danger delbtn" id="<?php echo $cnt['id']; ?>"></i>
							</div>
						<?php endif; ?>
						<?php if ($cnt['seen'] == 1) : ?>
							<i class="fas fa-envelope-open text-secondary"></i>
							<p class="card-text" id="<?php echo $cnt['id']; ?>"><?php echo word_limiter($cnt['msg'], 10); ?></p>
							<div class="d-flex justify-content-between mb-0 mt-0" style="padding: 0">
								<a href="" class="btn btn-dark rmbtn" id="<?php echo $cnt['id']; ?>">Read</a>
								<i class="fas fa-trash-alt text-danger delbtn" id="<?php echo $cnt['id']; ?>"></i>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="msg_tab col-md-9" id="msg_tab">
			<div class="card">
				<div class="card-title mb-0">
					<div class="text-right mr-3">
						<span class="font-weight-bolder">Department:</span>
						<span class="mb-0 reply_dept"></span>
					</div>
					<div class="text-right mr-3">
						<span class="font-weight-bolder">E-mail:</span>
						<span class="mb-0 reply_email"></span>
					</div>
					<div class="text-right mr-3">
						<span class="font-weight-bolder">User:</span>
						<span class="mb-0 reply_user"></span>
					</div>
					<div class="text-right mr-3">
						<span class="font-weight-bolder">Time:</span>
						<span class="mb-0 reply_time"></span>
					</div>
					<hr>
					<div class="text-center mr-3">
						<span class="font-weight-bolder">Subject:</span>
						<span class="mb-0 reply_subj text-danger font-weight-bolder"></span>
					</div>
				</div>
				<div class="card-body">
					<p class="card-text mt-0 reply_msg"></p>
				</div>
			</div><br>
			<div class="reply_in">
				<form action="<?php echo base_url('admin/staffrequest') ?>" method="post">
					<input type="hidden" name="<?php echo $this->security->get_csrf_token_name() ?>" value="<?php echo $this->security->get_csrf_hash() ?>" class="csrf_token">
					<div class="from-group">
						<input type="hidden" name="request_id" class="request_id">
						<input type="hidden" name="dept" class="dept">
						<input type="hidden" name="staff_id" class="staff_id">
						<input type="hidden" name="subj" class="subj">
						<textarea name="reply" class="form-control reply" id="reply" rows="3" placeholder="Your reply..."></textarea>
						<div class="subbtn mt-2 text-left">
							<button class="btn btn-success subbtn" type="submit">
								<i class="fas fa-paper-plane mr-2"></i>Send</button>
						</div>
					</div>
				</form>
			</div>

		</div>
	</div>
</div>

<script type="text/javascript">
	$('.msg_tab').hide();

	$(document).ready(function() {
		$('.rmbtn').click(function(e) {
			e.preventDefault();
			var id = $(this).attr('id');
			var csrfName = $('.csrf_token').attr('name');
			var csrfHash = $('.csrf_token').val();

			$.ajax({
				url: "<?php echo base_url('admin/show_request') ?>",
				method: "post",
				data: {
					id: id,
					[csrfName]: csrfHash
				},
				dataType: "json",
				success: function(data) {
					$('.msg_tab').show();
					$('.csrf_token').val(data.token);
					$('.reply_dept').html(data.department);
					$('.reply_email').html(data.email);
					$('.reply_user').html(data.uname);
					$('.reply_time').html(data.time);
					$('.reply_subj').html(data.subj);
					$('.reply_msg').html(data.msg);
					$('.request_id').val(data.id);
					$('.dept').val(data.department);
					$('.staff_id').val(data.staff_id);
					$('.subj').val(data.subj);
					$('a#' + id + '.rmbtn').removeClass('btn-info').addClass('btn-dark');
					$('p#' + id + '.card-text').removeClass('font-weight-bolder');
					$('i#' + id + '.fa-envelope').removeClass('fa-envelope text-danger').addClass('fa-envelope-open text-dark');
					// document.getElementById("msg_tab").scrollIntoView();
				},
				error: function(data) {
					window.location.reload()
				}
			});
		});

		$('i.delbtn').click(function(e) {
			var id = $(this).attr('id');
			var csrfName = $('.csrf_token').attr('name');
			var csrfHash = $('.csrf_token').val();
			console.log(id);
			$.ajax({
				url: "<?php echo base_url('admin/delete_request') ?>",
				method: "post",
				data: {
					id: id,
					[csrfName]: csrfHash
				},
				dataType: "json",
				success: function(data) {
					window.location.reload();
				},
				error: function(data) {
					window.location.reload();
				}
			});
		});

		$('.subbtn').click(function() {
			var reply = $('.reply').val();
			if (reply == "" || reply == null) {
				$('.reply').css('border', '2px solid red');
				document.getElementById("reply").scrollIntoView();
				return false;
			} else {
				$('.reply').css('border', '0px solid red');
			}
		});
	});
</script>