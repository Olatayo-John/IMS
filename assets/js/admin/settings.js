$('button#gensett').removeClass('text-dark').addClass('text-light').css('background-color', '#00695C');

$(document).ready(function () {
	$('button#gensett').click(function () {
		$('div#acctsett,div#staffsett,div#studsett,div#actlog').hide();
		$('.tabbtn').removeClass('text-light').addClass('text-dark').css('background-color', 'white');
		$(this).css('color', '#00695C');
		$('div#gensett').show();
	});

	$('button#acctsett').click(function () {
		$('div#gensett,div#staffsett,div#studsett,div#actlog').hide();
		$('.tabbtn').removeClass('text-light').addClass('text-dark').css('background-color', 'white');
		$('div#acctsett').show();
		$(this).removeClass('text-dark').addClass('text-light').css('background-color', '#00695C');
	});

	$('button#staffsett').click(function () {
		$('div#acctsett,div#gensett,div#studsett,div#actlog').hide();
		$('.tabbtn').removeClass('text-light').addClass('text-dark').css('background-color', 'white');
		$('div#staffsett').show();
		$(this).removeClass('text-dark').addClass('text-light').css('background-color', '#00695C');
	});

	$('button#studsett').click(function () {
		$('div#gensett,div#acctsett,div#staffsett,div#actlog').hide();
		$('.tabbtn').removeClass('text-light').addClass('text-dark').css('background-color', 'white');
		$('div#studsett').show();
		$(this).removeClass('text-dark').addClass('text-light').css('background-color', '#00695C');
	});

	$('button#actlog').click(function () {
		$('div#gensett,div#acctsett,div#studsett,div#staffsett').hide();
		$('.tabbtn').removeClass('text-light').addClass('text-dark').css('background-color', 'white');
		$('div#actlog').show();
		$(this).removeClass('text-dark').addClass('text-light').css('background-color', '#00695C');
	});

	$("i.fa-chevron-right").click(function () {
		$("i.fa-chevron-left").css('visibility', 'initial');
		var id = $(this).attr('id');
		new_id = parseInt(id) + 1;
		$(this).attr('id', new_id);
		$("i.fa-chevron-left").attr('id', new_id);
		$('button.' + id).hide();
		$('button.' + new_id).show();
		if (new_id == 5) {
			$("i.fa-chevron-right").css('visibility', 'hidden');
		}
	});

	$("i.fa-chevron-left").click(function () {
		$("i.fa-chevron-right").css('visibility', 'initial');
		var id = $(this).attr('id');
		new_id = parseInt(id) - 1;
		$(this).attr('id', new_id);
		$("i.fa-chevron-right").attr('id', new_id);
		$('button.' + id).hide();
		$('button.' + new_id).show();
		// $('i.fa-chevron-left').show();
		if (new_id == 1) {
			$("i.fa-chevron-left").css('visibility', 'hidden');
		}
	});

	$('button.gensettsvbtn').click(function (e) {
		// e.preventDefault();
		var webname = $('.webname').val();
		var webemail = $('.webemail').val();
		var webdomain = $('.webdomain').val();

		if (webname == "" || webname == null) {
			$('.webname').css('border', '2px solid red');
			return false;
		} else {
			$('.webname').css('border', '0px solid red');
		}
		if (webemail == "" || webemail == null) {
			$('.webemail').css('border', '2px solid red');
			return false;
		} else {
			$('.webemail').css('border', '0px solid red');
		}
		if (webdomain == "" || webdomain == null) {
			$('.webdomain').css('border', '2px solid red');
			// document.getElementById("webdomain").scrollIntoView();
			return false;
		} else {
			$('.webdomain').css('border', '0px solid red');
		}
	});

	$('button.acctsettsvbtn').click(function (e) {
		// e.preventDefault();
		var opwd = $('.opwd').val();
		var npwd = $('.npwd').val();
		var rpwd = $('.rpwd').val();

		if (opwd == "" || opwd == null) {
			$('.opwd').css('border', '2px solid red');
			return false;
		} else {
			$('.opwd').css('border', '0px solid red');
		}
		if (npwd == "" || npwd == null) {
			$('.npwd').css('border', '2px solid red');
			return false;
		} if (npwd.length < + 6) {
			$('#npwderr').html('Password length must be over 6chracters');
			return false;
		}
		else {
			$('.npwd').css('border', '0px solid red');
			$('#npwderr').html('');
		}
		if (rpwd == "" || rpwd == null) {
			$('.rpwd').css('border', '2px solid red');
			return false;
		} if (rpwd !== npwd) {
			$('#rpwderr').html('Password does not match!');
			return false;
		}
		else {
			$('.rpwd').css('border', '0px solid red');
			$('#rpwderr').html('');
		}
	});

})
