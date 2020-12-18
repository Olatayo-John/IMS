
$(document).ready(function () {
	$('a.request').click(function () {
		$('#requestdiv').fadeIn('show');
	});
	$('button.requestbtn').click(function () {
		$('#requestdiv').fadeOut('show');
	});

});