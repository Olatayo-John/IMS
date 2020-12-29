$(document).ready(function () {
	$(document).on('click', '.export_std_csv', function () {
		$.ajax({
			beforeSend: function () {
				$('.export_std_csv').html('Exporting...');
				$('.export_std_csv').removeClass('btn-outline-dark').addClass('btn-danger');
				$('.export_std_csv').css('cursor', 'not-allowed');
			},
			success: function () {
				$('.export_std_csv').html('Exported');
				$('.export_std_csv').removeClass('btn-danger').addClass('btn-success');
				$('.export_std_csv').css('cursor', 'pointer');
				setTimeout(function () {
					$('.export_std_csv').removeClass('btn-success').addClass('btn-outline-dark');
				});
			},
		});
	});

	$(document).on('click', 'i.course_ftr_i', function () {
		$(".course_ftr_inp").val("");
		$(".course_ftr_text, .course_ftr_i").hide();
		$(".brnch_ftr_inp").val("");
		$(".branch_ftr_text, .branch_ftr_i").hide();
		$(".branch_div").hide();
		$(".brnch_ftr_inp").val("");
	});

	$(document).on('click', 'i.branch_ftr_i', function () {
		$(".brnch_ftr_inp").val("");
		$(".branch_ftr_text, .branch_ftr_i").hide();
	});

	$(document).on('click', 'i.year_ftr_i', function () {
		$(".year_ftr_inp").val("");
		$(".year_ftr_text, .year_ftr_i").hide();
	});

});