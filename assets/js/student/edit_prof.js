$(document).ready(function () {
	$(document).on('click', 'a.prof_a', function (e) {
		e.preventDefault();
		$('div.cnt_div, div.course_div,div.ac_div').hide();
		$('a.cnt_a, a.course_a,a.ac_a').css('border-bottom', 'initial');
		$(this).css('border-bottom', '2px solid #00695C');
		$('div.prof_div').show();
	});

	$(document).on('click', 'a.cnt_a', function (e) {
		e.preventDefault();
		$('div.prof_div, div.course_div,div.ac_div').hide();
		$('a.prof_a, a.course_a,a.ac_a').css('border-bottom', 'initial');
		$(this).css('border-bottom', '2px solid #00695C');
		$('div.cnt_div').show();
	});

	$(document).on('click', 'a.course_a', function (e) {
		e.preventDefault();
		$('div.prof_div, div.cnt_div,div.ac_div').hide();
		$('a.prof_a, a.cnt_a,a.ac_a').css('border-bottom', 'initial');
		$(this).css('border-bottom', '2px solid #00695C');
		$('div.course_div').show();
	});

	$(document).on('click', 'a.ac_a', function (e) {
		e.preventDefault();
		$('div.prof_div,div.course_div, div.cnt_div').hide();
		$('a.prof_a,a.course_a, a.cnt_a').css('border-bottom', 'initial');
		$(this).css('border-bottom', '2px solid #00695C');
		$('div.ac_div').show();
	});


	$('button.savepi_btn').click(function (e) {
		// e.preventDefault();
		var uname = $('.uname').val();
		var fname = $('.fname').val();
		var gender = $('.gender').val();

		if (uname == "" || uname == null) {
			$('.uname').css('border', '2px solid red');
			return false;
		} else {
			$('.uname').css('border', '1px solid #ced4da');
		}
		if (fname == "" || fname == null) {
			$('.fname').css('border', '2px solid red');
			return false;
		} else {
			$('.fname').css('border', '1px solid #ced4da');
		}
		if (gender == "" || gender == null) {
			$('.gender').css('border', '2px solid red');
			return false;
		} else {
			$('.gender').css('border', '1px solid #ced4da');
		}
	});

	$('button.savecnt_btn').click(function (e) {
		// e.preventDefault();
		var email = $('.email').val();
		var mobile = $('.mobile').val();

		if (email == "" || email == null) {
			$('.email').css('border', '2px solid red');
			return false;
		} else {
			$('.email').css('border', '1px solid #ced4da');
		}
		if (mobile !== "" || mobile !== null) {
			if (mobile.length > 10 || mobile.length < 10) {
				$('span.mobileerr').show();
				return false;
			} else {
				$('.mobile').css('border', '1px solid #ced4da');
				$('span.mobileerr').hide();
			}
		}
	});

	$('button.savecourse_btn').click(function (e) {
		// e.preventDefault();
		var course = $('.course').val();
		var branch = $('.branch').val();
		var started_course_year = $('.started_course_year').val();
		var end_course_year = $('.end_course_year').val();
		var current_course_year = $('.current_course_year').val();

		if (course == "" || course == null) {
			$('.course').css('border', '2px solid red');
			return false;
		} else {
			$('.course').css('border', '1px solid #ced4da');
		}
		if (course.toLowerCase() == "b.tech" || course.toLowerCase() == "btech") {
			if (branch == "" || branch == null) {
				$('.branch').css('border', '2px solid red');
				return false;
			} else {
				$('.branch').css('border', '1px solid #ced4da');
			}
		}
		if (started_course_year !== "") {
			var d = new Date();
			if (started_course_year.length > 4 || started_course_year.length < 4 || started_course_year > d.getFullYear()) {
				$('.started_course_year').css('border', '2px solid red');
				$('.started_course_year_err').show();
				return false;
			} else {
				$('.started_course_year').css('border', '1px solid #ced4da');
				$('.started_course_year_err').hide();
			}
		}
		if (end_course_year !== "") {
			var c = new Date();
			if (end_course_year.length > 4 || end_course_year.length < 4 || end_course_year < c.getFullYear()) {
				$('.end_course_year').css('border', '2px solid red');
				$('.end_course_year_err').show();
				return false;
			} else {
				$('.end_course_year').css('border', '1px solid #ced4da');
				$('.end_course_year_err').hide();
			}
		}
		if (current_course_year == "" || current_course_year == null) {
			$('.current_course_year').css('border', '2px solid red');
			return false;
		} if (current_course_year > 5 || current_course_year <= 0) {
			$('.current_course_year').css('border', '2px solid red');
			$('.current_course_year_err').show();
			return false;
		} else {
			$('.current_course_year').css('border', '1px solid #ced4da');
			$('.current_course_year_err').hide();
		}
	});

	$('button.saveact_btn').click(function (e) {
		// e.preventDefault();
		var c_pwd = $('.c_pwd').val();
		var n_pwd = $('.n_pwd').val();
		var rtn_pwd = $('.rtn_pwd').val();

		if (c_pwd == "" || c_pwd == null) {
			$('.c_pwd').css('border', '2px solid red');
			return false;
		} else {
			$('.c_pwd').css('border', '1px solid #ced4da');
		}
		if (n_pwd == "" || n_pwd == null) {
			$('.n_pwd').css('border', '2px solid red');
			return false;
		}if (n_pwd.length < 7) {
			$('.n_pwd').css('border', '2px solid red');
			$('span.n_pwd_err').show();
			return false;
		} else {
			$('span.n_pwd_err').hide();
			$('.n_pwd').css('border', '1px solid #ced4da');
		}
		if (rtn_pwd == "" || rtn_pwd == null) {
			$('.rtn_pwd').css('border', '2px solid red');
			return false;
		}if (n_pwd !== rtn_pwd) {
			$('.rtn_pwd').css('border', '2px solid red');
			$('span.rtn_pwd_err').show();
			return false;
		} else {
			$('span.rtn_pwd_err').hide();
			$('.rtn_pwd').css('border', '1px solid #ced4da');
		}
	});

	$(document).on('click', 'button.deact_btn', function () {
		$('.deact_div').fadeIn();
	});

	$(document).on('click', 'button.deact_close_btn', function () {
		$('.deact_div').fadeOut();
	});

})