<link rel="stylesheet" href="<?php echo base_url('assets/css/student/my_profile.css'); ?>">
<?php foreach ($infos as $info) : ?>
	<div class="con mt-3 mr-3 ml-3 mb-3">
		<h4 class="text-dark"><?php echo $info['fname'] . " " . $info['lname'] ?></h4>
	</div>
	<div class="row con_both mt-3 mr-3 ml-3 mb-3">
		<div class="con col con_left">
			<span>
				<h4 class="text-dark">Profile <a href="<?php echo base_url('student/edit'); ?>"><i class="fas fa-edit float-right"></i></a></h4>
			</span>
			<hr>
			<div class="prof_img_div mb-2">
				<img src="<?php echo base_url('assets/std_uploads/' . $info['profile_img']); ?>" class="">
			</div>
			<div class="d-flex flex-column">
				<span><i class="fas fa-envelope mr-2"></i><?php echo $info['email'] ?></span>
				<span><i class="fas fa-phone mr-2"></i><?php echo $info['mobile'] ?></span>
			</div>
		</div>
		<div class="con col con_right">
			<div class="form-group row">
				<span class="col-md-6">
					<label>First Name</label></span>
					<span class="col-md-6">
						<p><?php echo $info['fname'] ?></p>
					</span>
				</div>
				<div class="form-group row">
					<span class="col-md-6">
						<label>Last Name</label></span>
						<span class="col-md-6">
							<p><?php echo $info['lname'] ?></p>
						</span>
					</div>
					<div class="form-group row">
						<span class="col-md-6">
							<label>Course</label></span>
							<span class="col-md-6">
								<p class="text-uppercase"><?php echo $info['course'] ?> (<?php echo $info['branch'] ?>)</p>
							</span>
						</div>
						<div class="form-group row">
							<span class="col-md-6">
								<label>Year</label></span>
								<span class="col-md-6">
									<p><?php echo $info['current_course_year'] ?></p>
								</span>
							</div>
							<div class="form-group row">
								<span class="col-md-6">
									<label>Gender</label></span>
									<span class="col-md-6">
										<p><?php echo $info['gender'] ?></p>
									</span>
								</div>
								<div class="row">
									<span class="col-md-6">
										<label>Bio</label></span>
										<span class="col-md-6">
											<p><?php echo $info_social->bio ?></p>
										</span>
									</div>
								</div>
							</div>
						<?php endforeach; ?>

						<div class="con mr-3 ml-3 mb-3">
							<div class="form-group row">
								<span class="col">
									<i class="fab fa-github mr-2"></i>Github</span>
									<span class="col">
										<a href="https://www.facebook.com/NKTechPvtLtd" target="_blank"><?php echo $info_social->github ?></a>
									</span>
								</div>
								<div class="form-group row">
									<span class="col">
										<i class="fab fa-facebook mr-2"></i>Facebook</span>
										<span class="col">
											<a href="https://www.facebook.com/NKTechPvtLtd" target="_blank"><?php echo $info_social->fb ?></a>
										</span>
									</div>
									<div class="form-group row">
										<span class="col">
											<i class="fab fa-instagram mr-2"></i>Instagram</span>
											<span class="col">
												<a href="https://www.facebook.com/NKTechPvtLtd" target="_blank"><?php echo $info_social->instagram ?></a>
											</span>
										</div>
										<div class="form-group row">
											<span class="col">
												<i class="fab fa-twitter mr-2"></i>Twitter</span>
												<span class="col">
													<a href="https://www.facebook.com/NKTechPvtLtd" target="_blank"><?php echo $info_social->twitter ?></a>
												</span>
											</div>
											<div class="form-group row">
												<span class="col">
													<i class="fab fa-linkedin mr-2"></i>Linkedin</span>
													<span class="col">
														<a href="https://www.facebook.com/NKTechPvtLtd" target="_blank"><?php echo $info_social->linkedin ?></a>
													</span>
												</div>
												<div class="form-group row">
													<span class="col">
														<i class="fab fa-google-plus-g mr-2"></i>Google+</span>
														<span class="col">
															<a href="https://www.facebook.com/NKTechPvtLtd" target="_blank"><?php echo $info_social->google_plus ?></a>
														</span>
													</div>
												</div>


												<script type="text/javascript" src="<?php echo base_url('assets/js/student/my_profile.js'); ?>"></script>