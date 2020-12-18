<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/admin/settings.css'); ?>">

<div class="container-fluid d-flex flex-row mt-3 mr-3 ml-3">
	<i class=" fas fa-chevron-left" id="1"></i>
	<div class="tab_links d-flex flex-row bg-light" style="margin: 0 5px">
		<button class="btn text-dark tabbtn 1" id="gensett">General Settings</button>
		<button class="btn text-dark tabbtn 2" id="acctsett">Account Settings</button>
		<button class="btn text-dark tabbtn 3" id="staffsett">Staff Settings</button>
		<button class="btn text-dark tabbtn 4" id="studsett">Student Settings</button>
		<button class="btn text-dark tabbtn 5" id="actlog">Activity Log</button>
	</div>
	<i class="fas fa-chevron-right" id="1"></i>
</div>

<div class="gensett mr-3 ml-3 mt-3" id="gensett">
	<form action="<?php echo base_url('settings/save_options'); ?>" method="post" enctype="multipart/form-data">
		<input type="hidden" name="<?php echo $this->security->get_csrf_token_name() ?>" value="<?php echo $this->security->get_csrf_hash() ?>" class="csrf_token">
		<div class="form-group mt-3">
			<label>Website Name</label>
			<input type="text" name="webname" class="form-control webname" value="<?php echo $webname->value ?>" id="<?php echo $webname->id ?> webname">
		</div>
		<div class="form-group">
			<label>Website Logo</label>
			<?php if (!$weblogo->value) : ?>
				<input type="file" name="weblogo" size="20" class="form-control weblogo" id="<?php echo $weblogo->id ?> weblogo">
			<?php endif; ?>
			<?php if ($weblogo->value) : ?>
				<button class="ml-auto close dellogo" type="button" id="<?php echo $weblogo->id ?>" name="weblogo">&times;</button>
				<div class="text-center">
					<img src="<?php echo base_url('assets/options/') . $weblogo->value ?>" width="150px" class="weblogo">
				</div>
			<?php endif; ?>
		</div>
		<div class="form-group">
			<label>Website Favicon</label>
			<?php if (!$webfavicon->value) : ?>
				<input type="file" name="webfavicon" size="20" class="form-control webfavicon" id="<?php echo $webfavicon->id ?> webfavicon">
			<?php endif; ?>
			<?php if ($webfavicon->value) : ?>
				<button class="ml-auto close delfavicon" type="button" id="<?php echo $webfavicon->id ?>" name="webfavicon">&times;</button>
				<div class="text-center">
					<img src="<?php echo base_url('assets/options/') . $webfavicon->value ?>" width="100px" class="webfavicon">
				</div>
			<?php endif; ?>
		</div>
		<div class="form-group">
			<label>Website E-mail</label>
			<input type="email" name="webemail" class="form-control webemail" value="<?php echo $webemail->value ?>" id="<?php echo $webemail->id ?> webemail">
		</div>
		<div class="form-group">
			<label>Website Domain</label>
			<input type="text" name="webdomain" class="form-control webdomain" value="<?php echo $webdomain->value ?>" id="<?php echo $webdomain->id ?> webdomain">
		</div>
		<div class="btnsubmit text-left">
			<button class="btn text-light gensettsvbtn" type="submit" style="background-color: #00695C">
				<i class="fas fa-save mr-2"></i>Save</button>
		</div>
	</form>
</div>

<div class="acctsett mr-3 ml-3 mt-3" id="acctsett">
	<h6 class="text-danger form-weight-bolder text-center">*This should only be changed by Super Admin</h6>
	<form method="post" action="<?php echo base_url('settings/change_pwd'); ?>">
		<input type="hidden" name="<?php echo $this->security->get_csrf_token_name() ?>" value="<?php echo $this->security->get_csrf_hash() ?>" class="csrf_token">
		<div class="form-group">
			<label>Old Password</label>
			<input type="password" name="opwd" class="form-control opwd">
		</div>
		<div class="form-group">
			<label>New Passwor</label>
			<input type="password" name="npwd" class="form-control npwd">
			<span id="npwderr" class="text-danger font-weight-bolder"></span>
		</div>
		<div class="form-group">
			<label>Re-type Password</label>
			<input type="password" name="rpwd" class="form-control rpwd">
			<span id="rpwderr" class="text-danger font-weight-bolder"></span>
		</div>
		<div class="changebtn text-left">
			<button class="btn text-light acctsettsvbtn" style="background-color:#00695C"><i class="fas fa-save mr-2"></i>Save</button>
		</div>
	</form>
</div>

<div class="staffsett mr-3 ml-3 mt-3" id="staffsett">
	<p class="text-light">Staff settings page</p>
</div>

<div class="studsett mr-3 ml-3 mt-3" id="studsett">
	<p class="text-light">Student settings page</p>
</div>

<div class="actlog mr-3 ml-3 mt-3" id="actlog">
	<div class="btnaction d-flex justify-content-between mb-3">
		<a href="<?php echo base_url('settings/activity_logs_export_csv'); ?>" class="btn btn-primary">
			<i class="fas fa-file-csv mr-2"></i>Export as CSV</a>
		<a href="" class="btn btn-danger deletelogs_btn">
			<i class="fas fa-trash-alt mr-2"></i>Delete all logs
		</a>
	</div>
	<div class="table-responsive">
		<table class="table table-bordered table-light table-hover">
			<tr class="font-weight-bolder text-light" style="background-color: #00695C">
				<th>
					<div class="no">
						<i class="fas fa-arrows-alt-v" name="id" type="asc"></i>
						<span>No.</span>
					</div>
				</th>
				<th>
					<div class="dept">
						<i class="fas fa-arrows-alt-v" name="department" type="asc"></i>
						<span>Department</span>
					</div>
				</th>
				<th>
					<div class="ac">
						<i class="fas fa-arrows-alt-v" name="action" type="asc"></i>
						<span>Action</span>
					</div>
				</th>
				<th>
					<div class="time">
						<i class="fas fa-arrows-alt-v" name="created_at" type="asc"></i>
						<span>Time</span>
					</div>
				</th>
			</tr>
			<?php if ($logs->num_rows() == '0') : ?>
				<tr class="text-dark">
					<td colspan='4' class='font-weight-bolder text-dark text-center text-uppercase'>No logs found</td>
				</tr>
			<?php endif; ?>
			<?php foreach ($logs->result_array() as $log) : ?>
				<tr class="text-dark">
					<td class="font-weight-bolder"><?php echo $log['id'] ?></td>
					<td class="font-weight-bolder"><?php echo $log['department'] ?></td>
					<td class="font-weight-bolder"><?php echo $log['action'] ?></td>
					<td class="text-danger font-weight-bolder"><?php echo $log['created_at'] ?></td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
</div>

<script type="text/javascript" src="<?php echo base_url('assets/js/admin/settings.js') ?>"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('button.dellogo').click(function() {
			var conf = confirm('Are you sure you want to delete? This process cannot be undone');
			if (conf == true) {
				var id = $('button.dellogo').attr('id');
				var csrfName = $('.csrf_token').attr('name');
				var csrfHash = $('.csrf_token').val();

				$.ajax({
					url: "<?php echo base_url('settings/delete_logo_option') ?>",
					method: "post",
					data: {
						id: id,
						[csrfName]: csrfHash
					},
					success: function(data) {
						window.location.reload();
					},
					error: function(data) {
						alert('Error deleting image');
					}
				});
			} else {
				return false;
			}
		});

		$('button.delfavicon').click(function() {
			var conf = confirm('Are you sure you want to delete? This process cannot be undone');
			if (conf == true) {
				var id = $('button.delfavicon').attr('id');
				var csrfName = $('.csrf_token').attr('name');
				var csrfHash = $('.csrf_token').val();

				$.ajax({
					url: "<?php echo base_url('settings/delete_favicon_option') ?>",
					method: "post",
					data: {
						id: id,
						[csrfName]: csrfHash
					},
					success: function(data) {
						window.location.reload();
					},
					error: function(data) {
						alert('Error deleting icon');
					}
				});
			} else {
				return false;
			}
		});

		$(document).on('click', 'i.fa-arrows-alt-v', function() {
			var param = $(this).attr('name');
			var type = $(this).attr('type');

			$.ajax({
				url: "<?php echo base_url('settings/filter_param') ?>",
				method: "post",
				data: {
					param: param,
					type: type,
				},
				success: function(data) {
					$('.table').html(data);
					var type_two = $('.fas').attr('type');
					if (type == 'desc') {
						$('.fas').attr('type', 'asc');
					} else {
						$('.fas').attr('type', 'desc');
					}
				},
				error: function(data) {
					alert('Error filtering');
				}
			});
		});

		$('.deletelogs_btn').click(function(e) {
			e.preventDefault();
			var conf = confirm('Are you sure you want to delete? This process cannot be undone');
			if (conf == true) {
				var csrfName = $('.csrf_token').attr('name');
				var csrfHash = $('.csrf_token').val();
				$.ajax({
					url: "<?php echo base_url('settings/delete_activity_logs') ?>",
					method: "post",
					data: {
						[csrfName]: csrfHash
					},
					beforeSend: function(data) {
						$('.deletelogs_btn').html("Deleting...");
					},
					success: function(data) {
						window.location.reload();
					},
					error: function(data) {
						window.location.reload();
					}
				});
			} else {
				return false;
			}
		});

	})
</script>