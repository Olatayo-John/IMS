<link rel="stylesheet" href="<?php echo base_url('assets/css/admin/edit_prof.css'); ?>">
<div class="tab_div ml-3 mr-3 mt-3 con">
    <a href="" class="tab_link prof_a">Profile</a>
    <a href="" class="tab_link cnt_a">Contact</a>
    <a href="" class="tab_link ac_a">Account</a>
</div>
<?php foreach ($infos as $info) : ?>
    <div class="con mt-3 mr-3 ml-3 mb-3 prof_div">
        <h4 class="text-dark mb-3 p_i mb-3">Personal Information</h4>
        <form action="<?php echo base_url('admin/save_personal_info'); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" class="csrf_token" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
            <?php if ($info['profile_img'] === "female_profile.png" || $info['profile_img'] == "male_profile.png" || $info['profile_img'] == "") : ?>
                <div class="d-flex flex-direction-row prof_img_outer_div">
                    <div class="profileimg_div">
                        <?php if ($info['gender'] === "Male") : ?>
                            <img src="<?php echo base_url('assets/adm_uploads/male_profile.png'); ?>">
                        <?php endif; ?>
                        <?php if ($info['gender'] === "Female") : ?>
                            <img src="<?php echo base_url('assets/adm_uploads/female_profile.png'); ?>">
                        <?php endif; ?>
                    </div>
                    <div class="update_profileimg_div ml-3">
                        <input type="file" name="update_profileimg" class="update_profileimg">
                    </div>
                </div>
            <?php elseif ($info['profile_img'] !== "female_profile.png" || $info['profile_img'] !== "male_profile.png") : ?>
                <div class="profileimg_div col-md-4 form-group">
                    <img src="<?php echo base_url('assets/adm_uploads/' . $info['profile_img']); ?>">
                    <i class="fas fa-times-circle text-danger" id="<?php echo $this->session->userdata('ims_id') ?>" gender="<?php echo $info['gender'] ?>"></i>
                </div>
            <?php endif; ?>
            <div class="form-group">
                <label><span class="text-danger">* </span>Username</label>
                <input type="text" name="uname" class="form-control uname" value="<?php echo $info['uname'] ?>">
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label><span class="text-danger">* </span>First Name</label>
                    <input type="text" name="fname" class="form-control fname" value="<?php echo $info['fname'] ?>" placeholder="Your First Name">
                </div>
                <div class="form-group col-md-6">
                    <label>Last Name</label>
                    <input type="text" name="lname" class="form-control lname" value="<?php echo $info['lname'] ?>" placeholder="Your Last Name">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-6">
                    <label><span class="text-danger">* </span>Gender</label>
                    <select class="form-control gender" name="gender">
                        <option value="<?php echo $info['gender'] ?>"><?php echo $info['gender'] ?></option>
                        <?php if ($info['gender'] == "Female") : ?>
                            <option value="Male">Male</option>
                            <option value="Other">Other</option>
                        <?php endif; ?>
                        <?php if ($info['gender'] == "Male") : ?>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        <?php endif; ?>
                        <?php if ($info['gender'] == "Other") : ?>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        <?php endif; ?>
                        <?php if (!$info['gender']) : ?>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>Bio</label>
                <textarea rows="4" cols="4" class="form-control" name="bio" placeholder="Tell us about yourself" class="bio"><?php echo $info_social->bio ?></textarea>
            </div>
            <div class="form-group text-left">
                <button class="btn text-light" type="submit savepi_btn" style="background-color:#00695C">Save</button>
            </div>
        </form>
    </div>

    <div class="con mt-3 mr-3 ml-3 mb-3 cnt_div">
        <h4 class="text-dark contact mb-3">Contact Information</h4>
        <form action="<?php echo base_url('admin/save_contact'); ?>" method="post">
            <input type="hidden" class="csrf_token" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
            <div class="form-group">
                <label><i class="fas fa-envelope mr-2"></i>E-mail<span class="text-danger">* </span></label>
                <input type="email" name="email" class="form-control email" value="<?php echo $info['email'] ?>" placeholder="example@domain.com">
            </div>
            <div class="form-group">
                <label><i class="fas fa-phone mr-2"></i>Mobile</label>
                <input type="number" name="mobile" class="form-control mobile" value="<?php echo $info['mobile'] ?>" placeholder="0123456789">
                <span class="text-danger font-weight-bolder mobileerr">Enter a valid mobile length</span>
            </div>
            <div class="form-group">
                <label><i class="fab fa-github mr-2"></i>Github</label>
                <input type="text" name="github" class="form-control github" value="<?php echo $info_social->github ?>">
            </div>
            <div class="form-group">
                <label><i class="fab fa-facebook mr-2"></i>Facebook</label>
                <input type="text" name="fb" class="form-control fb" value="<?php echo $info_social->fb ?>">
            </div>
            <div class="form-group">
                <label><i class="fab fa-instagram mr-2"></i>Instagram</label>
                <input type="text" name="ig" class="form-control ig" value="<?php echo $info_social->instagram ?>">
            </div>
            <div class="form-group">
                <label><i class="fab fa-twitter mr-2"></i>Twitter</label>
                <input type="text" name="twitter" class="form-control twitter" value="<?php echo $info_social->twitter ?>">
            </div>
            <div class="form-group">
                <label><i class="fab fa-linkedin mr-2"></i>Linkedin</label>
                <input type="text" name="linkedin" class="form-control linkedin" value="<?php echo $info_social->linkedin ?>">
            </div>
            <div class="form-group">
                <label><i class="fab fa-google-plus-g mr-2"></i>Google+</label>
                <input type="text" name="google_plus" class="form-control google_plus" value="<?php echo $info_social->google_plus ?>">
            </div>
            <div class="form-group text-left">
                <button class="btn text-light savecnt_btn" type="submit savecnt_btn" style="background-color:#00695C">Save</button>
            </div>
        </form>
    </div>

    <div class="con mt-3 mr-3 ml-3 mb-3 ac_div">
        <h4 class="text-dark account mb-3">Account Settings</h4>
        <form action="<?php echo base_url('admin/save_account'); ?>" method="post">
            <input type="hidden" class="csrf_token" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
            <div class="form-group">
                <label><i class="fas fa-key mr-2"></i>Current Password<span class="text-danger">* </span></label>
                <input type="text" name="c_pwd" class="form-control c_pwd" placeholder="Your current password">
            </div>
            <div class="form-group">
                <label><i class="fas fa-lock mr-2"></i>New Password</label>
                <input type="number" name="n_pwd" class="form-control n_pwd" placeholder="Password must be at least 7 characters long">
                <span class="text-danger font-weight-bolder n_pwd_err">Password is too short</span>
            </div>
            <div class="form-group">
                <label><i class="fas fa-lock mr-2"></i>Re-type Password</label>
                <input type="text" name="rtn_pwd" class="form-control rtn_pwd">
                <span class="text-danger font-weight-bolder rtn_pwd_err">Passwords do not match</span>
            </div>
            <div class="form-group text-right">
                <button class="btn btn-danger deact_btn" type="button">De-activate account</button>
            </div>
            <div class="form-group text-left">
                <button class="btn text-light saveact_btn" type="submit saveact_btn" style="background-color:#00695C">Save</button>
            </div>
        </form>
    </div>

    <div class="deact_div" id="deact_div">
        <div class="modal-body">
            <p>Are you sure you want to perform this operation?</p>
            <p>Your account will be de-activated and you'll be logged out completely</p>
            <div class="d-flex justify-content-between">
                <button class="btn btn-dark deact_close_btn">No</button>
                <button class="btn btn-danger yes_btn" user_id="<?php echo $info['id'] ?>">Yes</button>
            </div>
        </div>
    </div>

    </div>
    </div>

<?php endforeach; ?>
<script type="text/javascript" src="<?php echo base_url('assets/js/admin/edit_prof.js'); ?>"></script>

<script>
    $(document).ready(function() {
        $(document).on('click', 'i.fa-times-circle', function() {
            var csrfName = $('.csrf_token').attr('name');
            var csrfHash = $('.csrf_token').val();
            var id = $(this).attr('id');
            var gender = $(this).attr('gender');

            $.ajax({
                url: "<?php echo base_url('admin/delete_profileimg'); ?>",
                method: "post",
                data: {
                    [csrfName]: csrfHash,
                    id: id,
                    gender: gender
                },
                success: function(data) {
                    window.location.reload();
                },
                error: function(data) {
                    window.location.reload();
                }
            });
        });

        $(document).on('click', 'button.yes_btn', function() {
            var userid = $(this).attr("user_id");
            var csrfHash = $('.csrf_token').val();
            var csrfName = $('.csrf_token').attr("name");

            $.ajax({
                url: "<?php echo base_url('admin/deact_account'); ?>",
                method: "post",
                data: {
                    userid: userid,
                    [csrfName]: csrfHash
                },
                beforeSend: function(data) {
                    $('button.yes_btn').html("Deactivating...");
                },
                success: function(data) {
                    var url = "<?php echo base_url('admin/logout') ?>";
                    window.location.assign(url);
                }
            })
        });
    });
</script>