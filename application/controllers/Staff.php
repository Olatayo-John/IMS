<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Staff extends CI_Controller
{
	public function index()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$department = 'Staff';
			$action = "Not logged in. Access:Student";
			$this->Staffmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('staff/login');
		}
		if ($this->session->userdata('ims_role') == "Student" && $this->session->userdata('ims_logged_in')) {
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect($_SERVER['HTTP_REFERER']);
		}
		if ($this->session->userdata('ims_role') == "1"  && $this->session->userdata('ims_logged_in')) {
			redirect('staff/staffs');
		}
		if ($this->session->userdata('ims_role') == "0" && $this->session->userdata('ims_logged_in')) {
			redirect('staff/staffs');
		}
		if ($this->session->userdata('ims_role') == "Staff" && $this->session->userdata('ims_logged_in')) {
			redirect('staff/dashboard');
		}
	}

	public function staffs($offset = 0)
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$department = 'Staff';
			$action = "Not logged in. Access:Staffs";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('staff/login');
		}
		if ($this->session->userdata('ims_role') == "Staff" || $this->session->userdata('ims_role') == "Student") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Staff';
			$action = "Unauthorized Access by " . $uname . ". Access:Staffs";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect($_SERVER['HTTP_REFERER']);
			exit();
		}
		$config['base_url'] = base_url() . "student/students/";
		$config['total_rows'] = $this->db->count_all('student');
		$config['per_page'] = 10;
		$config['uri_segment'] = 3;
		$config['attributes'] = array('class' => 'page-link');
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['next_link'] = '&gt;';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['prev_link'] = '&lt;';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#" class="page-link">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		$this->pagination->initialize($config);

		$links = $this->pagination->create_links();
		$data = $this->Settingsmodel->get_options();
		$data['contacts'] = $this->Adminmodel->contacts();
		$data['adm_contacts'] = $this->Adminmodel->adm_replies();
		$data['stf_contacts'] = $this->Staffmodel->stf_replies();
		$data['std_contacts'] = $this->Studentmodel->std_replies();
		$data['students'] = $this->Studentmodel->get_all_students($config["per_page"], $offset);
		$data['course'] = $this->Studentmodel->get_all_course();
		$data['years'] = $this->Studentmodel->get_all_years();
		$data['branches'] = $this->Studentmodel->get_all_branch();

		$this->load->view('templates/header', $data);
		$this->load->view('staff/index', [$data, 'links' => $links]);
		$this->load->view('templates/footer');
	}

	public function login()
	{
		if ($this->session->userdata('ims_logged_in')) {
			redirect('staff/index');
		}
		$this->form_validation->set_rules('uname', 'Username', 'required|trim|html_escape');
		$this->form_validation->set_rules('pwd', 'Password', 'required|trim|html_escape');

		if ($this->form_validation->run() == false) {
			$data = $this->Settingsmodel->get_options();
			$data['contacts'] = $this->Adminmodel->contacts();
			$data['adm_contacts'] = $this->Adminmodel->adm_replies();
			// $data['stf_contacts']= $this->Staffmodel->stf_replies();
			$this->load->view('templates/header', $data);
			$this->load->view('staff/login');
			$this->load->view('templates/footer');
		} else {
			$uname = $this->input->post('uname');
			$pwd = $this->input->post('pwd');

			$validate = $this->Staffmodel->login($uname, $pwd);
			if ($validate == 'inactive') {
				$uname = $this->input->post('uname');
				$department = 'Staff';
				$action = "Failed login attempt by " . $uname . ". Account is Inactive";
				$this->Staffmodel->log_activity($department, $action);
				$this->session->set_flashdata('inactive_acct', 'Your account is inactive. Contact Super Admin');
				redirect('staff/login');
				exit();
			}
			if (!$validate) {
				$uname = $this->input->post('uname');
				$department = 'Staff';
				$action = "Failed login attempt by " . $uname . ". Invalid Username/Password";
				$this->Staffmodel->log_activity($department, $action);
				$this->session->set_flashdata('invalid_login', 'Incorrect Username/Password');
				redirect('staff/login');
				exit();
			} else {
				$id = $validate->id;
				$uname = $validate->uname;
				$fname = $validate->fname;
				$lname = $validate->lname;
				$email = $validate->email;
				$active = $validate->active;
				$role = $validate->role;
				$profile_img = $validate->profile_img;

				$admin_ses = array(
					'ims_id' => $id,
					'ims_uname' => $uname,
					'ims_fname' => $fname,
					'ims_lname' => $lname,
					'ims_email' => $email,
					'ims_active' => $active,
					'ims_role' => $role,
					'ims_profile_img' => $profile_img,
					'ims_logged_in' => TRUE,
				);

				$uname = $this->input->post('uname');
				$department = 'Staff';
				$action = "Successfull login by " . $uname;
				$this->Staffmodel->log_activity($department, $action);
				$this->session->set_userdata($admin_ses);
				$this->session->set_flashdata('valid_login', 'Welcome ' . $fname . " " . $lname);
				redirect('staff/dashboard');
			}
		}
	}

	public function modal_login()
	{
		if ($this->session->userdata('ims_logged_in')) {
			$data['token'] = $this->security->get_csrf_hash();
			echo json_encode($data);
			exit;
		}
		$data['db_req'] = $this->Staffmodel->login($_POST['uname'], $_POST['pwd']);
		if ($data['db_req'] == false) {
			$uname = $_POST['uname'];
			$department = 'Staff';
			$action = "Failed login attempt by " . $uname . ". Invalid Username/Password";
			$this->Staffmodel->log_activity($department, $action);
		} else if ($data['db_req'] == 'inactive') {
			$uname = $_POST['uname'];
			$department = 'Staff';
			$action = "Failed login attempt by " . $uname . ". Account is Inactive";
			$this->Staffmodel->log_activity($department, $action);
		} else if ($data['db_req'] !== false || $data['db_req'] !== 'inactive') {
			$id = $data['db_req']->id;
			$uname = $data['db_req']->uname;
			$fname = $data['db_req']->fname;
			$lname = $data['db_req']->lname;
			$email = $data['db_req']->email;
			$active = $data['db_req']->active;
			$role = $data['db_req']->role;
			$profile_img = $data['db_req']->profile_img;

			$admin_ses = array(
				'ims_id' => $id,
				'ims_uname' => $uname,
				'ims_fname' => $fname,
				'ims_lname' => $lname,
				'ims_email' => $email,
				'ims_profile_img' => $profile_img,
				'ims_active' => $active,
				'ims_role' => $role,
				'ims_logged_in' => TRUE,
			);

			$uname = $_POST['uname'];
			$department = 'Admin';
			$action = "Successfull login by " . $uname;
			$this->Staffmodel->log_activity($department, $action);
			$this->session->set_userdata($admin_ses);
			$this->session->set_flashdata('valid_login', 'Welcome ' . $fname . " " . $lname);
		}
		$data['redirect_url'] = base_url('staff');
		$data['token'] = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function logout()
	{
		$uname = $this->session->userdata('ims_uname');
		$department = 'Staff';
		$action = $uname . " Logged Out";
		$this->Staffmodel->log_activity($department, $action);

		$this->session->unset_userdata('ims_id');
		$this->session->unset_userdata('ims_uname');
		$this->session->unset_userdata('ims_fname');
		$this->session->unset_userdata('ims_lname');
		$this->session->unset_userdata('ims_email');
		$this->session->unset_userdata('ims_active');
		$this->session->unset_userdata('ims_role');
		$this->session->unset_userdata('ims_profile_img');
		$this->session->unset_userdata('ims_logged_in');
		$this->session->sess_destroy();

		$this->session->set_flashdata('log_out', 'Logged out');
		redirect('pages/index');
	}

	public function new()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$department = 'Staff';
			$action = "Not logged in. Access:Add Staff";
			$this->Staffmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('staff/login');
		}
		if ($this->session->userdata('ims_role') == "Staff") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Staff';
			$action = "Unauthorized Access by " . $uname . ". Access:Add Staff";
			$this->Staffmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect('student/dashboard');
			exit();
		}

		$this->form_validation->set_rules('fname', 'First Name', 'required|html_escape|trim');
		$this->form_validation->set_rules('lname', 'Last Name', 'required|html_escape|trim');
		$this->form_validation->set_rules('gender', 'Gender', 'required|html_escape|trim');
		$this->form_validation->set_rules('email', 'Email', 'required|html_escape|trim|valid_email');
		$this->form_validation->set_rules('mobile', 'Mobile', 'html_escape|trim');
		$this->form_validation->set_rules('pwd', 'Password', 'required|trim');

		if ($this->form_validation->run() == false) {
			$data = $this->Settingsmodel->get_options();
			$data['contacts'] = $this->Adminmodel->contacts();
			$data['adm_contacts'] = $this->Adminmodel->adm_replies();
			$data['stf_contacts'] = $this->Staffmodel->stf_replies();
			$data['std_contacts'] = $this->Studentmodel->std_replies();

			$this->load->view('templates/header', $data);
			$this->load->view('staff/new_stf');
			$this->load->view('templates/footer');
		} else {
			$email = htmlentities($this->input->post('email'));
			$fname = htmlentities($this->input->post('fname'));
			$lname = htmlentities($this->input->post('lname'));
			$pwd = htmlentities($this->input->post('pwd'));

			$mail_res = $this->new_stf_email($email, $fname, $lname, $pwd);
			if ($mail_res !== true) {
				$department = 'Staff';
				$action = "Error sending staff credentials. Access:Staff Credentials";
				$this->Staffmodel->log_activity($department, $action);
				$this->session->set_flashdata('mail_err', $mail_res);
				redirect($_SERVER['HTTP_REFERER']);
			} else {
				if (isset($_POST['active_stf'])) {
					$active = "1";
				} else {
					$active = "0";
				}

				if ($_FILES['stf_profile']['name']) {
					$rand = mt_rand(0, 10000000);
					$fname = strtolower(htmlentities($this->input->post("fname")));

					$config['upload_path'] = './assets/stf_uploads';
					$config['allowed_types'] = 'jpg|jpeg|png|';
					$config['max_size'] = '2048';
					$config['max_height'] = '3000';
					$config['max_width'] = '3000';
					$config['file_name'] = $rand . $fname;
					$this->load->library('upload', $config);

					if (!$this->upload->do_upload('stf_profile')) {
						$upload_error = array('error' => $this->upload->display_errors());
						$this->session->set_flashdata('logo_upload_err', print_r($upload_error));
						redirect($_SERVER['HTTP_REFERER']);
					} else {
						$uploaded = $_FILES['stf_profile']['name'];
						$uploaded_ext = htmlentities(strtolower(pathinfo($uploaded, PATHINFO_EXTENSION)));
						$data = array('upload_data' => $this->upload->data());
						$profile_img = $rand . $fname . "." . $uploaded_ext;
					}
				} else {
					$gen = htmlentities($this->input->post("gender"));
					if ($gen == "Male") {
						$profile_img = "male_profile.png";
					} else if ($gen == "Female") {
						$profile_img = "female_profile.png";
					}
				}

				$res = $this->Staffmodel->add_new_stf($active, $profile_img);
				if ($res == false) {
					$department = 'Staff';
					$action = $this->session->userdata('ims_uname') . "failed creating a staff. Access:Staff Credentials";
					$this->Studentmodel->log_activity($department, $action);
					$this->session->set_flashdata('new_std_err', 'Error adding staff');
					redirect($_SERVER['HTTP_REFERER']);
				} else {
					$department = 'Staff';
					$action = $this->session->userdata('ims_uname') . " added staff. Access:Add Staff";
					$this->Studentmodel->log_activity($department, $action);
					$this->session->set_flashdata('new_std_succ', 'Staff added successfully');
					redirect($_SERVER['HTTP_REFERER']);
				}
			}
		}
	}

	public function new_stf_email($email, $fname, $lname, $pwd)
	{
		$config['protocol']    = 'smtp';
		$config['smtp_host']    = 'ssl://smtp.gmail.com';
		$config['smtp_port']    = '465';
		$config['smtp_timeout'] = '7';
		$config['smtp_user']    = 'jvweedtest@gmail.com';
		$config['smtp_pass']    = 'Jvweedtest9!';
		$config['charset']    = 'iso-8859-1';
		$config['mailtype'] = 'text';
		$config['validation'] = TRUE;

		$this->load->library('email', $config);
		$this->email->set_newline("\r\n");

		$subj = "You are added as a staff member";
		$bdy = "Hi " . $fname . " " . $lname . "\n\n You are added as staff member on our website\n\n Please use the following login credentials:\n\n Username: " . $fname . "\n Password: " . $pwd . "\n\n Click the link below to login in the dashboard.\n" . base_url("staff") . "\n\n Best Regards,\n IMS Team\n" . base_url();

		$this->email->from('jvweedtest@gmail.com', 'IMS');
		$this->email->to($email);
		$this->email->subject($subj);
		$this->email->message($bdy);

		if ($this->email->send()) {
			return true;
		} else {
			return $this->email->print_debugger();
		}
	}

	public function dashboard()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$department = 'Staff';
			$action = "Not logged in. Access:Staff Dashboard";
			$this->Staffmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('student/login');
		}
		if ($this->session->userdata('ims_role') !== "Staff") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Staff';
			$action = "Unauthorized Access by " . $uname . ". Access:Staff Dashboard";
			$this->Staffmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect($_SERVER['HTTP_REFERER']);
			exit();
		}
		$data = $this->Settingsmodel->get_options();
		$data['contacts'] = $this->Adminmodel->contacts();
		$data['adm_contacts'] = $this->Adminmodel->adm_replies();
		$data['stf_contacts'] = $this->Staffmodel->stf_replies();
		$data['std_contacts'] = $this->Studentmodel->std_replies();
		$this->load->view('templates/header', $data);
		$this->load->view('student/dashboard', $data);
		$this->load->view('templates/footer');
	}

	public function profile()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Staff';
			$action = "Unauthorized Access by " . $uname . ". Access:My Profile";
			$this->Staffmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('staff/login');
			exit();
		}
		if ($this->session->userdata('ims_role') !== "Staff") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Staff';
			$action = "Unauthorized Access by " . $uname . ". Access:My Profile";
			$this->Staffmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect($_SERVER['HTTP_REFERER']);
			exit();
		}
		$data = $this->Settingsmodel->get_options();
		$data['contacts'] = $this->Adminmodel->contacts();
		$data['stf_contacts'] = $this->Staffmodel->stf_replies();
		$data['infos'] = $this->Staffmodel->get_current_staff_info();
		$data['info_social'] = $this->Staffmodel->get_current_staff_info_social();
		$this->load->view('templates/header', $data);
		$this->load->view('staff/my_prof', $data);
		$this->load->view('templates/footer');
	}

	public function edit()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Staff';
			$action = "Unauthorized Access by " . $uname . ". Access:Edit Profile";
			$this->Staffmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('staff/login');
			exit();
		}
		if ($this->session->userdata('ims_role') !== "Staff") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Staff';
			$action = "Unauthorized Access by " . $uname . ". Access:Edit Profile";
			$this->Staffmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect($_SERVER['HTTP_REFERER']);
			exit();
		}

		$data = $this->Settingsmodel->get_options();
		$data['contacts'] = $this->Adminmodel->contacts();
		$data['stf_contacts'] = $this->Staffmodel->stf_replies();
		$data['infos'] = $this->Staffmodel->get_current_staff_info();
		$data['info_social'] = $this->Staffmodel->get_current_staff_info_social();
		$this->load->view('templates/header', $data);
		$this->load->view('staff/edit_prof', $data);
		$this->load->view('templates/footer');
	}

	public function save_personal_info()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Staff';
			$action = "Unauthorized Access by " . $uname . ". Access:Update my Profile";
			$this->Staffmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('student/login');
			exit();
		}
		if ($this->session->userdata('ims_role') !== "Staff") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Staff';
			$action = "Unauthorized Access by " . $uname . ". Access:My Profile";
			$this->Staffmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect('student/dashboard');
			exit();
		}
		$this->form_validation->set_rules('uname', 'Username Name', 'required|trim|html_escape');
		$this->form_validation->set_rules('fname', 'First Name', 'required|trim|html_escape');
		$this->form_validation->set_rules('gender', 'Gender', 'required|trim|html_escape');

		if ($this->form_validation->run() == false) {
			$this->edit();
		} else {
			if ($_FILES['update_profileimg']['name']) {
				$rand = mt_rand(0, 10000000);
				$fname = strtolower(htmlentities($this->input->post("uname")));

				$config['upload_path'] = './assets/stf_uploads';
				$config['allowed_types'] = 'jpg|jpeg|png|';
				$config['max_size'] = '2048';
				$config['max_height'] = '3000';
				$config['max_width'] = '3000';
				$config['file_name'] = $rand . $fname;
				$this->load->library('upload', $config);

				if (!$this->upload->do_upload('update_profileimg')) {
					$upload_error = array('error' => $this->upload->display_errors());
					foreach ($upload_error as $error) {
						$this->session->set_flashdata('logo_upload_err', $error);
					}
					redirect($_SERVER['HTTP_REFERER']);
				} else {
					$uploaded = $_FILES['update_profileimg']['name'];
					$uploaded_ext = htmlentities(strtolower(pathinfo($uploaded, PATHINFO_EXTENSION)));
					$data = array('upload_data' => $this->upload->data());
					$profile_img = $rand . $fname . "." . $uploaded_ext;
					$this->session->set_userdata('ims_profile_img', $profile_img);
				}
			} else {
				$profile_img = $this->session->userdata('ims_profile_img');
			}
			$res = $this->Staffmodel->save_personal_info($profile_img);
			if ($res !== true) {
				$uname = $this->session->userdata('ims_uname');
				$department = 'Staff';
				$action = "Profile Update Failed by " . $uname . ". Access:Update Profile";
				$this->Staffmodel->log_activity($department, $action);
				$this->session->set_flashdata('p_update_err', 'Error updating your Profile');
				redirect('staff/profile');
				exit;
			} else {
				$uname = $this->input->post('uname');
				$department = 'Staff';
				$action = "Profile Update Successfull by " . $uname . ". Access:Update Profile";
				$this->Staffmodel->log_activity($department, $action);
				$this->session->set_flashdata('p_update_succ', 'Profile Updated');
				redirect($_SERVER['HTTP_REFERER']);
				exit();
			}
		}
	}

	public function delete_profileimg()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Staff';
			$action = "Unauthorized Access by " . $uname . ". Access:Delete my profile image";
			$this->Staffmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('staff/login');
			exit();
		}
		if ($this->session->userdata('ims_role') !== "Staff") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Staff';
			$action = "Unauthorized Access by " . $uname . ". Access:Delete my profile image";
			$this->Staffmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect($_SERVER['HTTP_REFERER']);
		}
		$res = $this->Staffmodel->delete_profileimg($_POST['id'], $_POST['gender']);
		if ($res !== true) {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Staff';
			$action = "Profile Update Failed by " . $uname . ". Access:Profile image delete";
			$this->Staffmodel->log_activity($department, $action);
			$this->session->set_flashdata('p_update_err', 'Failed to delete Profile image');
		} else {
			$uname = $this->input->post('uname');
			$department = 'Staff';
			$action = "Profile Update Successfull by " . $uname . ". Access:Profile image deleted";
			$this->Staffmodel->log_activity($department, $action);
			$this->session->set_flashdata('p_update_succ', 'Profile image deleted');
		}
	}

	public function save_contact()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Staff';
			$action = "Unauthorized Access by " . $uname . ". Access:Update my Profile";
			$this->Staffmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('staff/login');
			exit();
		}
		if ($this->session->userdata('ims_role') !== "Staff") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Staff';
			$action = "Unauthorized Access by " . $uname . ". Access:My Profile";
			$this->Staffmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect($_SERVER['HTTP_REFERER']);
			exit();
		}
		$this->form_validation->set_rules('fb', 'Facebook', 'trim|html_escape');
		$this->form_validation->set_rules('twitter', 'Twitter', 'trim|html_escape');
		$this->form_validation->set_rules('instagram', 'Instagram', 'trim|html_escape');
		$this->form_validation->set_rules('linkedin', 'Linkedin', 'trim|html_escape');
		$this->form_validation->set_rules('google_plus', 'Google Plus', 'trim|html_escape');
		$this->form_validation->set_rules('github', 'Github', 'trim|html_escape');

		if ($this->form_validation->run() == false) {
			$this->edit();
		} else {
			$res = $this->Staffmodel->save_contact();
			if ($res !== true) {
				$uname = $this->session->userdata('ims_uname');
				$department = 'Staff';
				$action = "Profile Update Failed by " . $uname . ". Access:Update Profile";
				$this->Staffmodel->log_activity($department, $action);
				$this->session->set_flashdata('p_update_err', 'Error updating your Profile');
				redirect('staff/profile');
				exit;
			} else {
				$uname = $this->input->post('uname');
				$department = 'Staff';
				$action = "Profile Update Successfull by " . $uname . ". Access:Update Profile";
				$this->Staffmodel->log_activity($department, $action);
				$this->session->set_flashdata('p_update_succ', 'Profile Updated');
				redirect($_SERVER['HTTP_REFERER']);
				exit();
			}
		}
	}

	public function save_account()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Staff';
			$action = "Unauthorized Access by " . $uname . ". Access:Update my Password";
			$this->Staffmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect($_SERVER['HTTP_REFERER']);
			exit();
		}
		if ($this->session->userdata('ims_role') !== "Staff") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Staff';
			$action = "Unauthorized Access by " . $uname . ". Access:Update my Password";
			$this->Staffmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect($_SERVER['HTTP_REFERER']);
		}

		$this->form_validation->set_rules('c_pwd', 'Current Password', 'required|trim');
		$this->form_validation->set_rules('n_pwd', 'New Password', 'required|trim');
		$this->form_validation->set_rules('rtn_pwd', 'Re-type Password', 'required|trim');

		if ($this->form_validation->run() == false) {
			$this->edit();
		} else {
			$pwd_res = $this->Staffmodel->check_pwd();
			if ($pwd_res == false) {
				$uname = $this->session->userdata('ims_uname');
				$department = 'Staff';
				$action = "Password Update Failed by " . $uname . ". Access:Update Password";
				$this->Staffmodel->log_activity($department, $action);
				$this->session->set_flashdata('pwd_update_err', 'Incorrect password provided');
				redirect($_SERVER['HTTP_REFERER']);
			} else {
				$uname = $this->input->post('uname');
				$department = 'Staff';
				$action = "Password Update Successfull by " . $uname . ". Access:Update Password";
				$this->Staffmodel->log_activity($department, $action);
				$this->session->set_flashdata('pwd_update_succ', 'Password changed');
				redirect($_SERVER['HTTP_REFERER']);
			}
		}
	}

	public function deact_account()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Staff';
			$action = "Unauthorized Access by " . $uname . ". Access:De-activate my Profile";
			$this->Staffmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect($_SERVER['HTTP_REFERER']);
			exit();
		}
		if ($this->session->userdata('ims_role') !== "Staff") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Staff';
			$action = "Unauthorized Access by " . $uname . ". Access:De-activate my Profile";
			$this->Staffmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect($_SERVER['HTTP_REFERER']);
		}
		$act_res = $this->Staffmodel->deact_account();
		if ($act_res == false) {
			$this->session->set_flashdata('deact_account_err', 'Error performing this operation');
		}
	}

	public function response()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Staff';
			$action = "Unauthorized Access by " . $uname . ". Access:Admin Response";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('staff/login');
			exit();
		}
		if ($this->session->userdata('ims_role') !== "Staff") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Staff';
			$action = "Unauthorized Access by " . $uname . ". Access:Admin Response";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect($_SERVER['HTTP_REFERER']);
			exit();
		}
		$this->form_validation->set_rules('reply', 'Reply', 'required|trim|html_escape');
		if ($this->form_validation->run() == FALSE) {
			$data = $this->Settingsmodel->get_options();
			$data['contacts'] = $this->Adminmodel->contacts();
			$data['user_stf_contacts'] = $this->Staffmodel->user_stf_replies();
			$data['stf_contacts'] = $this->Staffmodel->stf_replies();
			$this->load->view('templates/header', $data);
			$this->load->view('staff/response', $data);
			$this->load->view('templates/footer');
		} else {
			$res = $this->Staffmodel->save_reply();
			if ($res !== TRUE) {
				$uname = $this->session->userdata('ims_uname');
				$department = 'Staff';
				$action = "Failed to send reply by " . $uname . ". Access:Staff Response";
				$this->Staffmodel->log_activity($department, $action);
				$this->session->set_flashdata('reply_failed', 'Failed sending reply');
				redirect($_SERVER['HTTP_REFERER']);
				exit();
			} else {
				$uname = $this->session->userdata('ims_uname');
				$department = 'Staff';
				$action = "Reply sent by " . $uname . ". Access:Staff Response";
				$this->Staffmodel->log_activity($department, $action);
				$this->session->set_flashdata('reply_sent', 'Reply Sent');
				redirect($_SERVER['HTTP_REFERER']);
				exit();
			}
		}
	}

	public function show_request()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Staff';
			$action = "Unauthorized Access by " . $uname . ". Access:Show Requests";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
		}
		if ($this->session->userdata('ims_role') == "1" || $this->session->userdata('ims_role') == "Student" || $this->session->userdata('ims_role') == "0") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Staff';
			$action = "Unauthorized Access by " . $uname . ". Access:Show Requests";
			$this->Staffmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
		} else {
			$res = $this->Staffmodel->show_request($_POST['id']);
			$output = array();
			foreach ($res->result_array() as $row) {
				$output['sbj'] = $row['subj'];
				$output['department'] = $row['department'];
				$output['reply'] = $row['reply'];
				$output['time'] = $row['time'];
				$output['id'] = $row['id'];
				$output['staff_id'] = $row['staff_id'];
				$output['token'] = $this->security->get_csrf_hash();
			}
			echo json_encode($output);
		}
	}

	public function delete_response()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Staff';
			$action = "Unauthorized Access by " . $uname . ". Access:Delete Response";
			$this->Staffmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
		}
		if ($this->session->userdata('ims_role') == "1" || $this->session->userdata('ims_role') == "Student" || $this->session->userdata('ims_role') == "0") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Staff';
			$action = "Unauthorized Access by " . $uname . ". Access:Delete Response";
			$this->Staffmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
		}
		$del_res = $this->Staffmodel->delete_response($_POST['id']);
		if ($del_res !== true) {
			$this->session->set_flashdata('request_del_err', 'Error deleting message');
		} else {
			$this->session->set_flashdata('request_del_succ', 'Message deleted!');
		}
	}
}
