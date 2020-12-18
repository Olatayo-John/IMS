<button onclick="topFunction()" id="scrollbtn" class="btn btn-dark" style="width: 70px"><i class="fas fa-caret-up mr-2"></i>Top</button>
<?php if ($this->session->userdata('ims_role') !== "1" && $this->session->userdata('ims_logged_in')) : ?>
	<div class="rsa_div">
		<button class="btn btn-dark msgadminbtn" style="width: 220px">
			<i class="fas fa-comment-alt mr-2"></i>Contact Super Admin</button>
	</div>
<?php endif; ?>


<style type="text/css">
	div.closescsa {
		display: all;
		position: fixed;
		z-index: 992;
		border: none;
		outline: none;
		cursor: pointer;
		font-size: 25px;
	}

	#scrollbtn {
		display: none;
		position: fixed;
		bottom: 60px;
		right: 15px;
		z-index: 99;
		border: none;
		outline: none;
		cursor: pointer;
		font-size: 17px;
	}

	.msgadminbtn {
		display: all;
		position: fixed;
		bottom: 20px;
		right: 15px;
		z-index: 99;
		border: none;
		outline: none;
		cursor: pointer;
		font-size: 17px;
	}

	button.msgadminbtn:hover,
	button#scrollbtn:hover {
		transform: scale(1.1);
		cursor: pointer;
		transition: transform .3s;
	}

	div.closescsa:hover {
		transform: scale(1.1);
		cursor: pointer;
		transition: transform .3s;
		font-weight: bolder;
	}
</style>

<script type="text/javascript">
	mybutton = document.getElementById("scrollbtn");
	window.onscroll = function() {
		scrollFunction();
	};

	function scrollFunction() {
		if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
			mybutton.style.display = "block";
		} else {
			mybutton.style.display = "none";
		}
	}

	function topFunction() {
		document.body.scrollTop = 0;
		document.documentElement.scrollTop = 0;
	}

	function opennav() {
		// document.getElementById('side-nav').style.width = "200px";
		document.getElementById('side-nav').style.width = "0";
		document.getElementById('web_content').style.marginLeft = "0";
	}

	function navChange(curr_width) {
		if (curr_width.matches) {
			$('.side-nav,.request_hide,.response_hide').hide();
			$('.upnav_request,.upnav_response').show();
			$('.web_content').css('margin-left', '0');
			$('i.menubtn').attr('status', 'false');
		} else {
			$('.side-nav,.request_hide,.response_hide').show();
			$('.upnav_request,.upnav_response').hide();
			$('.web_content').css('margin-left', '170px');
			$('i.menubtn').attr('status', 'true');
		}
	}

	var curr_width = window.matchMedia("(max-width: 991px)")
	navChange(curr_width);
	curr_width.addListener(navChange);

	$(document).ready(function() {
		$(document).on('click', 'i.menubtn', function() {
			var status = $(this).attr('status');
			var curr_width = screen.width;

			if (status == "true") {
				$('.side-nav').hide();
				$('.web_content').css('margin-left', '0');
				$('i.menubtn').attr('status', 'false');
				// $('.web_content').css('opacity', '1');
			} else if (status == "false") {
				$('.side-nav').show();
				$('.web_content').css('margin-left', '170px');
				$('i.menubtn').attr('status', 'true');
				// $('.web_content').css('opacity', '.1');
			}
		});

		$('button.msgadminbtn').click(function() {
			$('.modal_div').fadeIn('slow');
		});

		$('.closemodalbtn').click(function() {
			$('.modal_div').fadeOut('slow');
		});

		$(document).on('click', '.user_profile_name', function(e) {
			e.preventDefault();
		});

		$('button.sendmodalbtn').click(function() {
			// e.preventDefault();
			var subj = $('.subj').val();
			var msg = $('.msg').val();

			// console.log(subj);
			// console.log(msg);

			if (subj == "" || subj == null) {
				$('.subj').css('border', '2px solid red');
				return false;
			} else {
				$('.subj').css('border', '0px solid red');
			}
			if (msg == "" || msg == null) {
				$('.msg').css('border', '2px solid red');
				return false;
			} else {
				$('.msg').css('border', '0px solid red');
			}
		});
	})
</script>

</div>
</body>

</html>