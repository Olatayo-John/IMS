<link rel="stylesheet" href="<?php echo base_url('assets/css/admin/index.css'); ?>">
<input type="hidden" name="<?php echo $this->security->get_csrf_token_name() ?>" value="<?php echo $this->security->get_csrf_hash() ?>" class="csrf_token">
<div class="row mt-3 mr-3 ml-3 mb-5">
	<div class="col-md-4 admin">
		<h4 class="text-danger">ADMIN</h4>
		<div class="form-group">
			<label>Total Admin</label>
			<div class="progress">
				<div class="progress-bar t_ad" role="progressbar"><span class="t_ad"></span></div>
			</div>
		</div>
		<div class="form-group">
			<label>Active Admin</label>
			<div class="progress progress-bar-success">
				<div class="progress-bar bg-success ac_ad" role="progressbar"><span class="ac_ad"></span></div>
			</div>
		</div>
		<div class="form-group">
			<label>Inactive Admin</label>
			<div class="progress">
				<div class="progress-bar in_ac_ad bg-danger" role="progressbar"><span class="in_ac_ad"></span></div>
			</div>
		</div>
	</div>
	<div class="col-md-4 staff">
		<h4 class="text-info">STAFF</h4>
		<div class="form-group">
			<label>Total Staff</label>
			<div class="progress">
				<div class="progress-bar t_stf" role="progressbar"><span class="t_stf"></span></div>
			</div>
		</div>
		<div class="form-group">
			<label>Active Staff</label>
			<div class="progress">
				<div class="progress-bar ac_stf bg-success" role="progressbar"><span class="ac_stf"></span></div>
			</div>
		</div>
		<div class="form-group">
			<label>Inactive Staff</label>
			<div class="progress">
				<div class="progress-bar in_ac_stf bg-danger" role="progressbar"><span class="in_ac_stf"></span></div>
			</div>
		</div>
	</div>
	<div class="col-md-4 student">
		<h4 class="text-success">STUDENT</h4>
		<div class="form-group">
			<label>Total Student</label>
			<div class="progress">
				<div class="progress-bar t_std" role="progressbar"><span class="t_std"></span></div>
			</div>
		</div>
		<div class="form-group">
			<label>Active Student</label>
			<div class="progress">
				<div class="progress-bar ac_std bg-success" role="progressbar"><span class="ac_std"></span></div>
			</div>
		</div>
		<div class="form-group">
			<label>Inactive Student</label>
			<div class="progress">
				<div class="progress-bar in_ac_std bg-danger " role="progressbar"><span class="in_ac_std"></span></div>
			</div>
		</div>
	</div>
</div>

<?php if ($this->session->userdata('ims_role') == "1") : ?>
	<!-- <h4 class="text-center mr-3 ml-3 bg-light">Latest Activity</h4> -->
	<div class="row mr-3 ml-3 mb-5 activity">
		<div class="col-md-4 mb-3 adminact">
			<?php if ($ad_logs->num_rows() == "0") : ?>
				<div class="list-group-item text-center">
					<p class="mb-0 font-weight-bolder">No Record</p>
				</div>
			<?php elseif ($ad_logs->num_rows() > "0") : ?>
				<?php foreach ($ad_logs->result_array() as $log) : ?>
					<div class="list-group-item d-flex justify-content-row">
						<p class="mb-0 font-weight-bolder"><?php echo $log['action'] ?></p>
						<small class="ml-auto font-weight-bolder text-danger"><?php echo $log['created_at'] ?></small>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>

		<div class="col-md-4 mb-3 staffact">
			<?php if ($stf_logs->num_rows() == "0") : ?>
				<div class="list-group-item text-center">
					<p class="mb-0 font-weight-bolder">No Record</p>
				</div>
			<?php elseif ($stf_logs->num_rows() > "0") : ?>
				<?php foreach ($stf_logs->result_array() as $log) : ?>
					<div class="list-group-item d-flex justify-content-row">
						<p class="mb-0 font-weight-bolder"><?php echo $log['action'] ?></p>
						<small class="ml-auto font-weight-bolder text-danger"><?php echo $log['created_at'] ?></small>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>

		<div class="col-md-4 mb-3 studentact">
			<?php if ($std_logs->num_rows() == "0") : ?>
				<div class="list-group-item text-center">
					<p class="mb-0 font-weight-bolder">No Record</p>
				</div>
			<?php elseif ($std_logs->num_rows() > "0") : ?>
				<?php foreach ($std_logs->result_array() as $log) : ?>
					<div class="list-group-item d-flex justify-content-row">
						<p class="mb-0 font-weight-bolder"><?php echo $log['action'] ?></p>
						<small class="ml-auto font-weight-bolder text-danger"><?php echo $log['created_at'] ?></small>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
<?php endif; ?>

</div>


<script type="text/javascript">
	$(document).ready(function() {
		var csrfName = $('.csrf_token').attr('name');
		var csrfHash = $('.csrf_token').val();
		$.ajax({
			url: "<?php echo base_url('admin/bar_data'); ?>",
			method: "post",
			dataType: "json",
			data: {
				[csrfName]: csrfHash,
			},
			success: function(data) {
				$('.t_ad').css('width', data.t_ad + '%');
				$('.ac_ad').css('width', data.ac_ad + '%');
				$('.in_ac_ad').css('width', data.in_ac_ad + '%');
				$('.t_stf').css('width', data.t_stf + '%');
				$('.ac_stf').css('width', data.ac_stf + '%');
				$('.in_ac_stt').css('width', data.in_ac_stt + '%');
				$('.t_std').css('width', data.t_std + '%');
				$('.ac_std').css('width', data.ac_std + '%');
				$('.in_ac_std').css('width', data.in_ac_std + '%');
				$('.csrf_token').val(data.token);
			},
			error: function(data) {
				alert('Error showing');
			}
		});

	});
</script>
<script type="text/javascript" src="<?php echo base_url('assets/js/admin/index.js'); ?>"></script>