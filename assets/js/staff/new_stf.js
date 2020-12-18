$(document).ready(function () {
	$('.pwd').keyup(function () {
		$("i.fa-eye").show();
	});

	$(document).on('click', 'a.profile_tab', function (e) {
		e.preventDefault();
		$("div.perm_div").hide();
		$("a.perm_tab").css('border-bottom', 'none');
		$("div.profile_div").show();
		$("a.profile_tab").css('border-bottom', '2px solid black');
	});

	$(document).on('click', 'button.gen_pwd', function () {
		var rand = Math.floor((Math.random() * 10000000) + 1);
		$(".pwd").val(rand);
		$(".pwd").attr("type", "password");
		$("i.fa-eye").show();
		$("i.fa-eye-slash").hide();
	});

	$(document).on('click', 'i.fa-eye', function () {
		$(".pwd").attr("type", "text");
		$("i.fa-eye").hide();
		$("i.fa-eye-slash").show();
	});

	$(document).on('click', 'i.fa-eye-slash', function () {
		$(".pwd").attr("type", "password");
		$("i.fa-eye-slash").hide();
		$("i.fa-eye").show();
	});

	$('select.course').click(function () {
		var course = $(this).val();
		if (course == "b.tech") {
			$(".branch_div").show();
		} else {
			$(".branch_div").hide();
			$(".branch").val("");
		}
	});

	$(document).on('click', 'button.new_stf_btn', function (e) {
		// e.preventDefault();
		var fname = $(".fname").val();
		var lname = $(".lname").val();
		var gender = $(".gender").val();
		var email = $(".email").val();
		var pwd = $(".pwd").val();


		if (fname == "" || fname == null) {
			$(".fname").css('border', '2px solid red');
			return false;
		} else {
			$(".fname").css('border', '1px solid #ced4da');
		} if (lname == "" || lname == null) {
			$(".lname").css('border', '2px solid red');
			return false;
		} else {
			$(".lname").css('border', '1px solid #ced4da');
		} if (gender == "" || gender == null) {
			$(".gender").css('border', '2px solid red');
			return false;
		} else {
			$(".gender").css('border', '1px solid #ced4da');
		} if (email == "" || email == null) {
			$(".email").css('border', '2px solid red');
			return false;
		} else {
			$(".email").css('border', '1px solid #ced4da');
		} if (pwd == "" || pwd == null) {
			$(".pwd").css('border', '2px solid red');
			return false;
		} if (pwd.length < 7) {
			$(".pwd").css('border', '2px solid red');
			$(".pwd_err").show();
			return false;
		} else {
			$(".pwd").css('border', '1px solid #ced4da');
			$(".pwd_err").hide();
		}

		$.ajax({
			beforeSend: function () {
				$('.new_stf_btn').html('Creating...');
				$('.new_stf_btn').css('cursor', 'not-allowed');
			}
		});
	});

});