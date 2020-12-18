$(document).ready(function () {
	$('.readonlyname').click(function () {
		$('span.editname').toggle();
	});

	$('button.saveinfobtn').click(function () {
		// e.preventDefault();
		var fname = $('.fname').val();
		var lname = $('.lname').val();
		var uname = $('.uname').val();
		var email = $('.email').val();
		var mobile = $('.mobile').val();
		var gender = $('.gender').val();

		if (fname == "" || fname == null) {
			$('.fname').css('border', '2px solid red');
			return false;
		} else {
			$('.fname').css('border', '0px solid red');
		}
		if (lname == "" || lname == null) {
			$('.lname').css('border', '2px solid red');
			return false;
		} else {
			$('.lname').css('border', '0px solid red');
		}
		if (email == "" || email == null) {
			$('.email').css('border', '2px solid red');
			return false;
		} else {
			$('.email').css('border', '0px solid red');
		}
		if (mobile == "" || mobile == null) {
			$('.mobile').css('border', '2px solid red');
			return false;
		} if (mobile.length > 10 || mobile.length < 10) {
			$('span.mobileerr').show();
			return false;
		} else {
			$('.mobile').css('border', '0px solid red');
			$('span.mobileerr').hide();
		}
	});

	$('i.fa-edit').click(function () {
		window
	});

})