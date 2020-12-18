<link rel="stylesheet" href="<?php echo base_url('assets/css/student/new_std.css'); ?>">
<input type="hidden" name="<?php echo $this->security->get_csrf_token_name() ?>" value="<?php echo $this->security->get_csrf_hash() ?>" class="csrf_token">

<div class="mt-3 mr-3 ml-3 mb-5 indexpagebody">
    <form method="post" action="<?php echo base_url('student/new'); ?>" enctype="multipart/form-data">
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
        <div class="form-group">
            <label>Profile Image</label>
            <input type="file" name="std_profile" class="form-control std_profile">
        </div>
        <div class="row">
            <div class="form-group col">
                <label><span class="red_star">* </span>First Name</label>
                <input type="text" name="fname" class="form-control fname">
            </div>
            <div class="form-group col">
                <label><span class="red_star">* </span>Last Name</label>
                <input type="text" name="lname" class="form-control lname">
            </div>
        </div>
        <div class="row">
            <div class="form-group col">
                <label>Father's Name</label>
                <input type="text" name="f_name" class="form-control f_name">
            </div>
            <div class="form-group col">
                <label>Mother's Name</label>
                <input type="text" name="m_name" class="form-control m_name">
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
            <label for="email"><span class="red_star">* </span>Email</label>
            <input type="email" name="email" class="form-control email">
        </div>
        <div class="form-group">
            <label for="mobile">Mobile</label>
            <input type="number" name="mobile" class="form-control mobile">
        </div>
        <div class="form-group row">
            <div class="col">
                <label for="course"><span class="red_star">* </span>Course</label>
                <select class="form-control text-uppercase course" name="course">
                    <?php foreach ($courses->result_array() as $course) : ?>
                        <option value="<?php echo $course['name'] ?>" class="text-uppercase course_list"><?php echo $course['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col branch_div">
                <label for="branch"><span class="red_star">* </span>Branch</label>
                <select class="form-control text-uppercase branch" name="branch">
                    <?php foreach ($branches->result_array() as $branch) : ?>
                        <option value="<?php echo $branch['name'] ?>" class="text-uppercase"><?php echo $branch['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="pwd"><span class="red_star">* </span>Password</label>
            <input type="password" name="pwd" class="form-control pwd" placeholder="Password must be at least 7 characters long">
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
            <input type="checkbox" name="active_std" class="active_std">
            <span class="text-danger font-weight-bolder">Activate account</span>
        </div>
        <div class="sub_div form-group">
            <button type="submit" class="btn text-light new_std_btn" style="background-color:#00695C"><i class="fas fa-plus-circle mr-2"></i>Add student</button>
        </div>
    </form>
    <hr>
</div>

<script type="text/javascript">

</script>
<script type="text/javascript" src="<?php echo base_url('assets/js/student/new_std.js'); ?>"></script>