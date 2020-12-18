<link rel="stylesheet" href="<?php echo base_url('assets/css/student/index.css'); ?>">
<input type="hidden" name="<?php echo $this->security->get_csrf_token_name() ?>" value="<?php echo $this->security->get_csrf_hash() ?>" class="csrf_token">

<div class="ml-3 mr-3 pt-3 pb-3 row new_srch_std">
	<div class="col">
		<a href="<?php echo base_url('student/new'); ?>" class="btn text-light" style="background-color: #00695C">
			<i class="fas fa-plus-circle mr-2"></i>New Student</a>
		</div>
		<div class="ml-auto col d-flex flex-row" style="border-bottom: 1px solid #00695C">
			<span class="" style="display: inline-flex;"><i class="fas fa-search"></i></span>
			<input type="text" name="search_std" class="search_std form-control" id="search_std" placeholder="Search...">
		</div>
	</div>

	<div class="pt-3 pb-3 mr-3 ml-3 mt-3 mb-3 filterdiv">
		<div class="d-flex flex-row">
			<div class="">
				<label class="text-dark text-left font-weight-bolder pl-3 mr-2">Filter by</label>
			</div>
			<div class="filter_text">
				<span class="course_ftr_text text-info mr-1"></span><i class="fas fa-minus-circle text-danger course_ftr_i mr-2"></i>
				<span class="branch_ftr_text text-info mr-1"></span><i class="fas fa-minus-circle text-danger branch_ftr_i mr-2"></i>
				<span class="year_ftr_text text-info"></span><sup class="text-lowercase year_ftr_i text-info mr-1"></sup><i class="fas fa-minus-circle text-danger year_ftr_i"></i>
			</div>
		</div>

		<div class="row" style="margin:0">
			<div class="col course_div">
				<input type="text" name="course_ftr_inp" data-toggle="dropdown" placeholder="Course" class="form-control course_ftr_inp" readonly>
				<div class="dropdown-menu dropdown-menu-left">
					<?php if ($course->num_rows() == "0") : ?>
						<p class="text_tran font-weight-bolder mb-0 text-dark">no data found</p>
					<?php endif; ?>
					<?php if ($course->num_rows() > "0") : ?>
						<?php foreach ($course->result_array() as $crs) : ?>
							<p class="text-center text_tran course_list" style="cursor: pointer" course="<?php echo $crs['name'] ?>"><?php echo $crs['name'] ?></p>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</div>
			<div class="col dropdown branch_div">
				<input type="text" name="brnch_ftr_inp" data-toggle="dropdown" placeholder="Branch" class="form-control brnch_ftr_inp" readonly>
				<div class="dropdown-menu">
					<?php if ($branches->num_rows() == "0") : ?>
						<p class="text_tran font-weight-bolder mb-0 text-dark">no data found</p>
					<?php endif; ?>
					<?php if ($branches->num_rows() > "0") : ?>
						<?php foreach ($branches->result_array() as $brnc) : ?>
							<p class="text-center text_tran branch_list" style="cursor: pointer" branch="<?php echo $brnc['name'] ?>"><?php echo $brnc['name'] ?></p>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</div>
			<div class="col year_div">
				<input type="text" name="year_ftr_inp" data-toggle="dropdown" placeholder="Current Course Year" class="form-control year_ftr_inp" readonly>
				<div class="dropdown-menu">
					<?php if ($years->num_rows() == "0") : ?>
						<p class="text_tran font-weight-bolder mb-0 text-dark">no data found</p>
					<?php endif; ?>
					<?php if ($years->num_rows() > "0") : ?>
						<?php foreach ($years->result_array() as $year) : ?>
							<?php if ($year['current_course_year'] == "1") : ?>
								<p class="text-center year_list" style="cursor: pointer" year="<?php echo $year['current_course_year'] ?>"><?php echo $year['current_course_year'] ?><sup class="text-lowercase">st</sup></p>
							<?php endif; ?>
							<?php if ($year['current_course_year'] == "2") : ?>
								<p class="text-center year_list" style="cursor: pointer" year="<?php echo $year['current_course_year'] ?>"><?php echo $year['current_course_year'] ?><sup class="text-lowercase">nd</sup></p>
							<?php endif; ?>
							<?php if ($year['current_course_year'] == "3") : ?>
								<p class="text-center year_list" style="cursor: pointer" year="<?php echo $year['current_course_year'] ?>"><?php echo $year['current_course_year'] ?><sup class="text-lowercase">rd</sup></p>
							<?php endif; ?>
							<?php if ($year['current_course_year'] == "4") : ?>
								<p class="text-center year_list" style="cursor: pointer" year="<?php echo $year['current_course_year'] ?>"><?php echo $year['current_course_year'] ?><sup class="text-lowercase">th</sup></p>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<div class="row pt-3 pb-3 pl-3">
		<div class="col">
			<button class="btn text-light refresh_table_btn" style="background-color: #00695C">
				<i class="fas fa-sync"></i>
			</button>
			<a href="<?php echo base_url('student/export_std_csv'); ?>" class="btn text-light export_std_csv mr-0 tran" style="background-color: #00695C">Export</a>
			<button class="btn text-light dropdown-toggle ml-0 bact_btn tran" data-toggle="dropdown" style="background-color: #00695C">bulk actions</button>
			<div class="dropdown-menu">
				<button class="dropdown-item text-dark btn btn-light blk_act_csv">
					<i class="fas fa-file-csv mr-2"></i>CSV
				</button>
				<button class="dropdown-item text-danger btn btn-light blk_act_del">
					<i class="fas fa-trash-alt mr-2"></i>Delete
				</button>
			</div>
		</div>
	<!-- to be able to choose table row to show
		<div class="col mt-3">
			<select class="form-control">
				<?php if ($years->num_rows() == "0") : ?>
					<option class="text_tran font-weight-bolder mb-0 text-dark">no data found</option>
				<?php endif; ?>
				<?php if ($years->num_rows() > "0") : ?>
					<?php foreach ($years->result_array() as $year) : ?>
						<option class="text-center text_tran course_list" style="cursor: pointer"><?php echo $year['started_course_year'] ?></option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
		</div> -->
	</div>


	<div class="table-responsive std_table_div pr-3 pl-3">
		<table class="table table-light std_table table-sm">
			<thead class="text-light" style="background-color: #00695C">
				<tr>
					<th>
						<input type="checkbox" name="chk_allbox" id="chk_allbox" class="chk_allbox">
					</th>
					<th>
						<div class="fn">First Name<i class="fas fa-sort-amount-down" param="asc" name="fname"></i></div>
					</th>
					<th>
						<div class="fn">Last Name<i class="fas fa-sort-amount-down" param="asc" name="lname"></i></div>
					</th>
					<th>
						<div class="fn">Course<i class="fas fa-sort-amount-down" param="asc" name="course"></i></div>
					</th>
					<th>
						<div class="fn">Email<i class="fas fa-sort-amount-down" param="asc" name="email"></i></div>
					</th>
					<th>
						<div class="fn">Mobile<i class="fas fa-sort-amount-down" param="asc" name="mobile"></i></div>
					</th>
					<th>
						<div class="fn">Status<i class="fas fa-sort-amount-down" param="asc" name="active"></i></div>
					</th>
				</tr>
			</thead>

			<?php if ($students->num_rows() == '0') : ?>
				<tr class="text-dark">
					<td colspan='7' class='font-weight-bolder text-center text-uppercase'>No data found</td>
				</tr>
			<?php endif; ?>

			<?php foreach ($students->result_array() as $std) : ?>
				<tr id="<?php echo $std['id'] ?>">
					<td>
						<input type="checkbox" name="chk_onebox" id="chk_onebox" class="chk_onebox">
					</td>
					<td><?php echo $std['fname'] ?>
					<div class="<?php echo $std['id'] ?> action_div">
						<small>
							<a href="" class="text-info action_div_a view_std" id="<?php echo $std['id'] ?>">View</a>
							<a href="" class="text-info action_div_a edit edit_std" id="<?php echo $std['id'] ?>">Edit</a>
							<a href="" class="text-danger action_div_a delete_std" id="<?php echo $std['id'] ?>">Delete</a>
						</small>
					</div>
				</td>
				<td><?php echo $std['lname'] ?></td>
				<td class="text-uppercase"><?php echo $std['course'] ?></td>
				<td><?php echo $std['email'] ?></td>
				<td><?php echo $std['mobile'] ?></td>
				<td class="text-center">
					<?php if ($std['active'] == '0') : ?>
						<i class="fas fa-toggle-off text-danger status_icon" id="<?php echo $std['id'] ?>" status="1"></i>
					<?php endif; ?>
					<?php if ($std['active'] == '1') : ?>
						<i class="fas fa-toggle-on text-success status_icon" id="<?php echo $std['id'] ?>" status="0"></i>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</table><span class="pag_links"><?php echo $links ?></span>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('#search_std').keyup(function() {
			var search = $(this).val();
			if (search == "" || search == null) {
				reload_table();
			} else {
				load_data(search);
			}
		});

		function load_data(query) {
			var csrfName = $('.csrf_token').attr('name');
			var csrfHash = $('.csrf_token').val();
			$(".course_ftr_inp, .brnch_ftr_inp, .year_ftr_inp").val("");
			$(".course_ftr_text, .branch_ftr_text,.year_ftr_text").hide();
			$(".course_ftr_i, .branch_ftr_i,.year_ftr_i").hide();

			$.ajax({
				method: "POST",
				url: "<?php echo base_url('student/search_student') ?>",
				data: {
					query: query,
					[csrfName]: csrfHash
				},
				success: function(data) {
					$('.std_table_div').html(data);
					$('.bact_btn').hide();
				}
			})
		}

		function filter_by(course, branch, year) {
			var csrfName = $('.csrf_token').attr('name');
			var csrfHash = $('.csrf_token').val();

			var param = [];
			var flt_key = [];
			var flt_val = [];

			param['course'] = course;
			param['branch'] = branch;
			param['current_course_year'] = year;


			for (var key in param) {
				value = param[key];

				if (value !== null) {
					console.log("key: " + key + "value: " + value);

					flt_key.push(key);
					flt_val.push(value);

					// console.log(flt_key);
					// console.log(flt_val);
				}
			}

			$.ajax({
				method: "POST",
				url: "<?php echo base_url('student/filter_by') ?>",
				data: {
					flt_key: flt_key,
					flt_val: flt_val,
					[csrfName]: csrfHash
				},
				success: function(data) {
					$('.std_table_div').html(data);
					$('.bact_btn').hide();
				}
			});
		}

		$(document).on('click', 'p.course_list', function() {
			var course = $(this).attr('course');
			$(".course_ftr_inp").val(course.toUpperCase());
			$(".course_ftr_text, .course_ftr_i").show();
			$(".course_ftr_text").html(course.toUpperCase());
			var crs_inp_val = $(".course_ftr_inp").val();
			// console.log(crs_inp_val);
			if (course == "b.tech") {
				$(".branch_div").show();
			} else {
				$(".branch_div").hide();
				$(".brnch_ftr_inp").val("");
				$(".branch_ftr_text, .branch_ftr_i").hide();
				var branch = null;
				var year = $(".year_ftr_inp").val();
				if (year == "" || year == null) {
					year = null;
				}
				filter_by(course, branch, year);
			}
		});

		$(document).on('click', 'p.branch_list', function() {
			var crs_inp_val = $(".course_ftr_inp").val();
			if (crs_inp_val !== "B.TECH") {
				$(".branch_div").hide();
				$(".brnch_ftr_inp").val("");
			} else {
				$(".branch_div").show();
				var branch = $(this).attr('branch');
				$(".brnch_ftr_inp").val(branch.toUpperCase());
				var brnch_ftr_inp = $(".brnch_ftr_inp").val();
				$(".branch_ftr_text, .branch_ftr_i").show();
				$(".branch_ftr_text").html(branch.toUpperCase());
				// console.log(brnch_ftr_inp);
				var course = crs_inp_val;
				var year = $(".year_ftr_inp").val();
				if (year == "" || year == null) {
					year = null;
				}
				filter_by(course, branch, year);
			}
		});

		$(document).on('click', 'p.year_list', function() {
			var year = $(this).attr('year');
			$(".year_ftr_inp").val(year);
			$(".year_ftr_text").html(year);
			if (year == '1') {
				$("sup.year_ftr_i").html("st");
			} else if (year == "2") {
				$("sup.year_ftr_i").html("nd");
			} else if (year == "3") {
				$("sup.year_ftr_i").html("rd");
			} else if (year == "4") {
				$("sup.year_ftr_i").html("th");
			}
			$(".year_ftr_text, .year_ftr_i").show();

			var year_ftr_inp = $(".year_ftr_inp").val();
			var crs_inp_val = $(".course_ftr_inp").val();
			var brnch_ftr_inp = $(".brnch_ftr_inp").val();
			if (crs_inp_val !== "B.TECH") {
				$(".branch_div").hide();
				$(".brnch_ftr_inp").val("");
			}
			if (crs_inp_val == "" || crs_inp_val == null) {
				course = null;
			} else {
				var course = $(".course_ftr_inp").val();
			}
			if (brnch_ftr_inp == "" || brnch_ftr_inp == null) {
				branch = null;
			} else {
				var branch = $(".brnch_ftr_inp").val();
			}
			filter_by(course, branch, year);
		});

		function reload_table() {
			var csrfName = $('.csrf_token').attr('name');
			var csrfHash = $('.csrf_token').val();

			$.ajax({
				url: '<?php echo base_url('student/refresh_std_table'); ?>',
				method: 'post',
				data: {
					[csrfName]: csrfHash
				},
				success: function(data) {
					$('.std_table_div').html(data);
					$('.bact_btn').hide();
					$(".course_ftr_inp, .brnch_ftr_inp, .year_ftr_inp").val("");
					$(".course_ftr_text, .branch_ftr_text,.year_ftr_text").hide();
					$(".course_ftr_i, .branch_ftr_i,.year_ftr_i").hide();
				},
			});
		}

		$(document).on('click', 'button.refresh_table_btn', function() {
			var csrfName = $('.csrf_token').attr('name');
			var csrfHash = $('.csrf_token').val();

			$.ajax({
				url: '<?php echo base_url('student/refresh_std_table'); ?>',
				method: 'post',
				data: {
					[csrfName]: csrfHash
				},
				success: function(data) {
					$('.std_table_div').html(data);
					$('.bact_btn').hide();
					$(".course_ftr_inp, .brnch_ftr_inp, .year_ftr_inp").val("");
					$(".course_ftr_text, .branch_ftr_text,.year_ftr_text").hide();
					$(".course_ftr_i, .branch_ftr_i,.year_ftr_i").hide();
				},
			});
		});

		$(document).on('click', '.fa-sort-amount-down', function() {
			var param = $(this).attr('param');
			var col_name = $(this).attr('name');
			var csrfName = $('.csrf_token').attr('name');
			var csrfHash = $('.csrf_token').val();

			$.ajax({
				url: '<?php echo base_url('student/filter_students'); ?>',
				method: 'post',
				data: {
					param: param,
					col_name: col_name,
					[csrfName]: csrfHash
				},
				success: function(data) {
					//window.location.reload();
					$('.std_table_div').html(data);
					$('.bact_btn').hide();
					if (param == 'desc') {
						$('.fas').attr('param', 'asc');
					} else {
						$('.fas').attr('param', 'desc');
					}
				},
			});
		});

		$(document).on('click', 'i.status_icon', function() {
			var id = $(this).attr('id');
			var status = $(this).attr('status');
			var csrfName = $('.csrf_token').attr('name');
			var csrfHash = $('.csrf_token').val();

			$.ajax({
				url: '<?php echo base_url('student/change_std_status'); ?>',
				method: 'post',
				dataType: "json",
				data: {
					id: id,
					status: status,
					[csrfName]: csrfHash
				},
				success: function(data) {
					if (status == "0") {
						$('i#' + id + '.fas').removeClass('fa-toggle-on').addClass('fa-toggle-off');
						$('i#' + id + '.fas').removeClass('text-success').addClass('text-danger');
						$('i#' + id + '.fas').attr('status', '1');
						$('.csrf_token').val(data);
						console.log(data);
					} else if (status == "1") {
						$('i#' + id + '.fas').removeClass('fa-toggle-off').addClass('fa-toggle-on');
						$('i#' + id + '.fas').removeClass('text-danger').addClass('text-success');
						$('i#' + id + '.fas').attr('status', '0');
						$('.csrf_token').val(data);
					}
				},
				error: function(data) {
					window.location.reload();
				}
			});
		});

		$(document).on('mouseover', 'tr', function() {
			var id = $(this).attr('id');
			$('div.' + id).css('visibility', 'visible');
		});

		$(document).on('mouseout', 'tr', function() {
			var id = $(this).attr('id');
			$('div.' + id).css('visibility', 'hidden');
		});

		$(document).on('click', 'a.view_std', function(e) {
			e.preventDefault();
			var id = $(this).attr('id');
			console.log(id);
		});

		$(document).on('click', 'a.edit_std', function(e) {
			e.preventDefault();
			var id = $(this).attr('id');
			console.log(id);
		});

		$(document).on('click', 'a.delete_std', function(e) {
			e.preventDefault();
			var id = $(this).attr('id');
			var csrfName = $('.csrf_token').attr('name');
			var csrfHash = $('.csrf_token').val();
			var con = confirm("Delete this student and all of its data? This action cannot be undone");
			if (con == false) {
				return false;
			} else {
				$.ajax({
					url: "<?php echo base_url('student/delete_student') ?>",
					method: "post",
					data: {
						id: id,
						[csrfName]: csrfHash
					},
					success: function(data) {
						window.location.reload();
					},
					error: function(data) {
						window.location.reload();
					}
				});
			}
		});

	});
</script>
<script type="text/javascript" src="<?php echo base_url('assets/js/student/index.js'); ?>"></script>