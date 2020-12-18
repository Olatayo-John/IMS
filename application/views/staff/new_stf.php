<link rel="stylesheet" href="<?php echo base_url('assets/css/staff/new_stf.css'); ?>">
<div class="mt-3 mr-3 ml-3 mb-5 indexpagebody">
    <form method="post" action="<?php echo base_url('staff/new'); ?>" enctype="multipart/form-data">
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
        <div class="form-group">
            <label>Profile Image</label>
            <input type="file" name="stf_profile" class="form-control stf_profile">
        </div>
        <div class="row">
            <div class="form-group col">
                <label><span class="red_star">* </span>First Name</label>
                <input type="text" name="fname" class="form-control fname" required>
            </div>
            <div class="form-group col">
                <label><span class="red_star">* </span>Last Name</label>
                <input type="text" name="lname" class="form-control lname" required>
            </div>
        </div>
        <div class="form-group">
            <label for="gender"><span class="red_star">* </span>Gender</label>
            <select class="form-control gender" name="gender">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Others">Others</option>
            </select>
        </div>
        <div class="form-group">
            <label><span class="red_star">* </span>Email</label>
            <input type="email" name="email" class="form-control email" required>
        </div>
        <div class="form-group">
            <label for="mobile">Mobile</label>
            <input type="number" name="mobile" class="form-control mobile">
        </div>
        <div class="form-group">
            <label for="pwd"><span class="red_star">* </span>Password</label>
            <input type="password" name="pwd" class="form-control pwd" placeholder="Password must be at least 7 characters long" required>
            <span class="pwd_err text-danger" style="display: none">Password too short</span>
        </div>
        <div class="d-flex justify-content-row form-group">
            <button type="button" class="gen_pwd btn">Generate password</button>
            <div class="col mt-2">
                <i class="fas fa-eye"></i>
                <i class="fas fa-eye-slash"></i>
            </div>
        </div>
        <div class="form-group">
            <input type="checkbox" name="active_stf" class="active_stf">
            <span class="text-danger font-weight-bolder">Activate account</span>
        </div>
        <div class="sub_div form-group">
            <button type="submit" class="btn text-light new_stf_btn" style="background-color:#00695C"><i class="fas fa-plus-circle mr-2"></i>Add Staff</button>
        </div>
    </form>
</div>

<script type="text/javascript">

</script>
<script type="text/javascript" src="<?php echo base_url('assets/js/staff/new_stf.js'); ?>"></script>