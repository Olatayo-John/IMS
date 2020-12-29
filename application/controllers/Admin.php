<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
	public function index()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$department = 'Admin';
			$action = "Not logged in. Access:Response";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('admin/login');
		}
		if ($this->session->userdata('ims_role') == "Staff" || $this->session->userdata('ims_role') == "Student") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Admin';
			$action = "Unauthorized Access by " . $uname . ". Access:Staff Requests";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect($_SERVER['HTTP_REFERER']);
			exit();
		}
		$data = $this->Settingsmodel->get_options();
		$data['ad_logs'] = $this->Adminmodel->admin_activity_logs();
		$data['stf_logs'] = $this->Adminmodel->staff_activity_logs();
		$data['std_logs'] = $this->Adminmodel->student_activity_logs();
		$data['contacts'] = $this->Adminmodel->contacts();
		$data['adm_contacts'] = $this->Adminmodel->adm_replies();
		// print_r($data['contacts']);die;
		$this->load->view('templates/header', $data);
		$this->load->view('admin/index', $data);
		$this->load->view('templates/footer');
	}

	public function login()
	{
		if ($this->session->userdata('ims_logged_in')) {
			redirect('admin/index');
		}
		$this->form_validation->set_rules('uname', 'Username', 'required|trim|html_escape');
		$this->form_validation->set_rules('pwd', 'Password', 'required|trim|html_escape');

		if ($this->form_validation->run() == false) {
			$data = $this->Settingsmodel->get_options();
			$data['contacts'] = $this->Adminmodel->contacts();
			$this->load->view('templates/header', $data);
			$this->load->view('admin/login');
			$this->load->view('templates/footer');
		} else {
			$uname = $this->input->post('uname');
			$pwd = $this->input->post('pwd');

			$validate = $this->Adminmodel->login($uname, $pwd);
			if ($validate == 'inactive') {
				$uname = $this->input->post('uname');
				$department = 'Admin';
				$action = "Failed login attempt by " . $uname . ". Account is Inactive";
				$this->Adminmodel->log_activity($department, $action);
				$this->session->set_flashdata('inactive_acct', 'Your account is inactive. Contact Super Admin');
				redirect('admin/login');
				exit();
			}
			if (!$validate) {
				$uname = $this->input->post('uname');
				$department = 'Admin';
				$action = "Failed login attempt by " . $uname . ". Invalid Username/Password";
				$this->Adminmodel->log_activity($department, $action);
				$this->session->set_flashdata('invalid_login', 'Incorrect Username/Password');
				redirect('admin/login');
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
					'ims_profile_img' => $profile_img,
					'ims_active' => $active,
					'ims_role' => $role,
					'ims_logged_in' => TRUE,
				);

				$uname = $this->input->post('uname');
				$department = 'Admin';
				$action = "Successfull login by " . $uname;
				$this->Adminmodel->log_activity($department, $action);
				$this->session->set_userdata($admin_ses);
				$this->session->set_flashdata('valid_login', 'Welcome ' . $fname . " " . $lname);
				redirect('admin');
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
		$data['db_req'] = $this->Adminmodel->login($_POST['uname'], $_POST['pwd']);
		if ($data['db_req'] == false) {
			$uname = $_POST['uname'];
			$department = 'Admin';
			$action = "Failed login attempt by " . $uname . ". Invalid Username/Password";
			$this->Adminmodel->log_activity($department, $action);
		} else if ($data['db_req'] == 'inactive') {
			$uname = $_POST['uname'];
			$department = 'Admin';
			$action = "Failed login attempt by " . $uname . ". Account is Inactive";
			$this->Adminmodel->log_activity($department, $action);
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
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_userdata($admin_ses);
			$this->session->set_flashdata('valid_login', 'Welcome ' . $fname . " " . $lname);
		}
		$data['redirect_url'] = base_url('admin');
		$data['token'] = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function logout()
	{
		$uname = $this->session->userdata('ims_uname');
		$department = 'Admin';
		$action = $uname . " Logged Out";
		$this->Adminmodel->log_activity($department, $action);

		$this->session->unset_userdata('ims_id');
		$this->session->unset_userdata('ims_uname');
		$this->session->unset_userdata('ims_fname');
		$this->session->unset_userdata('ims_lname');
		$this->session->unset_userdata('ims_email');
		$this->session->unset_userdata('ims_profile_img');
		$this->session->unset_userdata('ims_active');
		$this->session->unset_userdata('ims_role');
		$this->session->unset_userdata('ims_logged_in');

		//session destroy removes all running sessions...even the ones not associated on this webiste
		// $this->session->sess_destroy();

		$this->session->set_flashdata('log_out', 'Logged out');
		redirect('pages/index');
	}

	public function profile()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$department = 'Admin';
			$action = "Not logged in. Access:Response";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('admin/login');
		}

		$data = $this->Settingsmodel->get_options();
		$data['contacts'] = $this->Adminmodel->contacts();
		$data['adm_contacts'] = $this->Adminmodel->admin_contacts();
		$data['stf_contacts'] = $this->Staffmodel->stf_replies();
		$data['infos'] = $this->Adminmodel->get_current_admin_info();
		$data['info_social'] = $this->Adminmodel->get_current_admin_info_social();
		$this->load->view('templates/header', $data);
		$this->load->view('admin/my_prof', $data);
		$this->load->view('templates/footer');
	}

	public function edit()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Admin';
			$action = "Unauthorized Access by " . $uname . ". Access:Edit Profile";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect($_SERVER['HTTP_REFERER']);
		}
		if ($this->session->userdata('ims_role') == "Staff" || $this->session->userdata('ims_role') == "Student") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Admin';
			$action = "Unauthorized Access by " . $uname . ". Access:Edit Profile";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect($_SERVER['HTTP_REFERER']);
			exit();
		}

		$data = $this->Settingsmodel->get_options();
		$data['contacts'] = $this->Adminmodel->contacts();
		$data['adm_contacts'] = $this->Adminmodel->admin_contacts();
		$data['stf_contacts'] = $this->Staffmodel->stf_replies();
		$data['infos'] = $this->Adminmodel->get_current_admin_info();
		$data['info_social'] = $this->Adminmodel->get_current_admin_info_social();
		$this->load->view('templates/header', $data);
		$this->load->view('admin/edit_prof', $data);
		$this->load->view('templates/footer');
	}

	public function save_personal_info()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Admin';
			$action = "Unauthorized Access by " . $uname . ". Access:Update my Profile";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('admin/login');
			exit();
		}
		if ($this->session->userdata('ims_role') == "Staff" || $this->session->userdata('ims_role') == "Student") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Admin';
			$action = "Unauthorized Access by " . $uname . ". Access:My Profile";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect($_SERVER['HTTP_REFERER']);
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

				$config['upload_path'] = './assets/adm_uploads';
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
			$res = $this->Adminmodel->save_personal_info($profile_img);
			if ($res !== true) {
				$uname = $this->session->userdata('ims_uname');
				$department = 'Admin';
				$action = "Profile Update Failed by " . $uname . ". Access:Update Profile";
				$this->Adminmodel->log_activity($department, $action);
				$this->session->set_flashdata('p_update_err', 'Error updating your Profile');
				redirect(redirect($_SERVER['HTTP_REFERER']));
				exit;
			} else {
				$uname = $this->input->post('uname');
				$department = 'Admin';
				$action = "Profile Update Successfull by " . $uname . ". Access:Update Profile";
				$this->Adminmodel->log_activity($department, $action);
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
			$department = 'Admin';
			$action = "Unauthorized Access by " . $uname . ". Access:Delete my profile image";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect($_SERVER['HTTP_REFERER']);
		}
		if ($this->session->userdata('ims_role') == "Staff" || $this->session->userdata('ims_role') == "Student") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Admin';
			$action = "Unauthorized Access by " . $uname . ". Access:Delete my profile image";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect($_SERVER['HTTP_REFERER']);
		}
		$res = $this->Adminmodel->delete_profileimg($_POST['id'], $_POST['gender']);
		if ($res !== true) {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Admin';
			$action = "Profile Update Failed by " . $uname . ". Access:Profile image delete";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('p_update_err', 'Failed to delete Profile image');
		} else {
			$uname = $this->input->post('uname');
			$department = 'Admin';
			$action = "Profile Update Successfull by " . $uname . ". Access:Profile image deleted";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('p_update_succ', 'Profile image deleted');
		}
	}

	public function save_contact()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Admin';
			$action = "Unauthorized Access by " . $uname . ". Access:Update my Profile";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect($_SERVER['HTTP_REFERER']);
		}
		if ($this->session->userdata('ims_role') == "Staff" || $this->session->userdata('ims_role') == "Student") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Admin';
			$action = "Unauthorized Access by " . $uname . ". Access:My Profile";
			$this->Adminmodel->log_activity($department, $action);
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
			$res = $this->Adminmodel->save_contact();
			if ($res !== true) {
				$uname = $this->session->userdata('ims_uname');
				$department = 'Admin';
				$action = "Profile Update Failed by " . $uname . ". Access:Update Profile";
				$this->Adminmodel->log_activity($department, $action);
				$this->session->set_flashdata('p_update_err', 'Error updating your Profile');
				redirect($_SERVER['HTTP_REFERER']);
			} else {
				$uname = $this->input->post('uname');
				$department = 'Admin';
				$action = "Profile Update Successfull by " . $uname . ". Access:Update Profile";
				$this->Adminmodel->log_activity($department, $action);
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
			$department = 'Admin';
			$action = "Unauthorized Access by " . $uname . ". Access:Update my Profile";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect($_SERVER['HTTP_REFERER']);
			exit();
		}
		if ($this->session->userdata('ims_role') === "Staff" || $this->session->userdata('ims_role') === "Student") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Admin';
			$action = "Unauthorized Access by " . $uname . ". Access:My Profile";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect($_SERVER['HTTP_REFERER']);
		}

		$this->form_validation->set_rules('c_pwd', 'Current Password', 'required|trim');
		$this->form_validation->set_rules('n_pwd', 'New Password', 'required|trim');
		$this->form_validation->set_rules('rtn_pwd', 'Re-type Password', 'required|trim');

		if ($this->form_validation->run() == false) {
			$this->edit();
		} else {
			$pwd_res = $this->Adminmodel->check_pwd();
			if ($pwd_res == false) {
				$uname = $this->session->userdata('ims_uname');
				$department = 'Admin';
				$action = "Password Update Failed by " . $uname . ". Access:Update Password";
				$this->Adminmodel->log_activity($department, $action);
				$this->session->set_flashdata('pwd_update_err', 'Incorrect password provided');
				redirect($_SERVER['HTTP_REFERER']);
			} else {
				$uname = $this->input->post('uname');
				$department = 'Admin';
				$action = "Password Update Successfull by " . $uname . ". Access:Update Password";
				$this->Adminmodel->log_activity($department, $action);
				$this->session->set_flashdata('pwd_update_succ', 'Password changed');
				redirect($_SERVER['HTTP_REFERER']);
			}
		}
	}

	public function deact_account()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Admin';
			$action = "Unauthorized Access by " . $uname . ". Access:De-activate my Profile";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect($_SERVER['HTTP_REFERER']);
			exit();
		}
		if ($this->session->userdata('ims_role') === "Staff" || $this->session->userdata('ims_role') === "Student") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Admin';
			$action = "Unauthorized Access by " . $uname . ". Access:De-activate my Profile";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect($_SERVER['HTTP_REFERER']);
		}
		$act_res = $this->Adminmodel->deact_account();
		// $act_res= false;
		if ($act_res == false) {
			$this->session->set_flashdata('deact_account_err', 'Error performing this operation');
		}
	}

	public function reach_admin()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$department = 'Admin';
			$action = "Not logged in. Access:Response";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('admin/login');
		}
		$this->form_validation->set_rules('subj', 'Subject', 'required|trim|html_escape');
		$this->form_validation->set_rules('msg', 'Message', 'required|trim|html_escape');

		if ($this->form_validation->run() == false) {
			$this->profile();
		} else {
			$res = $this->Adminmodel->reach_admin();
			if ($res !== true) {
				$uname = $this->session->userdata('ims_uname');
				$department = 'Admin';
				$action = "Failed to send message to Super Admin by " . $uname . ". Access:Reach Admin";
				$this->Adminmodel->log_activity($department, $action);
				$this->session->set_flashdata('msg_send_fail', 'Error sending message');
				redirect('admin/profile');
				exit;
			} else {
				$uname = $this->session->userdata('ims_uname');
				$department = 'Admin';
				$action = "Successfull sent message to Super Admin by " . $uname . ". Access:Reach Admin";
				$this->Adminmodel->log_activity($department, $action);
				//load controller to send mail to super_Admin
				//$this->reach_admin_mail($msg,$user and other stuff);
				$this->session->set_flashdata('msg_send_succ', 'Message sent! Super Admin will contact you shortly');
				redirect($_SERVER['HTTP_REFERER']);
			}
		}
	}

	public function bar_data()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$department = 'Admin';
			$action = "Not logged in. Access:Response";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('admin/login');
		}
		if ($this->session->userdata('ims_role') == "Staff" || $this->session->userdata('ims_role') == "Student") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Admin';
			$action = "Unauthorized Access by " . $uname . ". Access:Bar Data";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect($_SERVER['HTTP_REFERER']);
			exit();
		}
		$data['t_ad'] = $this->Adminmodel->tadmin();
		$data['ac_ad'] = $this->Adminmodel->active_admin();
		$data['in_ac_ad'] = $this->Adminmodel->inactive_admin();
		$data['t_stf'] = $this->Adminmodel->tstaff();
		$data['ac_stf'] = $this->Adminmodel->active_staff();
		$data['in_ac_stt'] = $this->Adminmodel->inactive_staff();
		$data['t_std'] = $this->Adminmodel->tstudent();
		$data['ac_std'] = $this->Adminmodel->active_student();
		$data['in_ac_std'] = $this->Adminmodel->inactive_student();
		$data['token'] = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function adminrequest()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$department = 'Admin';
			$action = "Not logged in. Access:Admin Requests";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('admin/login');
		}
		if ($this->session->userdata('ims_role') !== "1") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Admin';
			$action = "Unauthorized Access by " . $uname . ". Access:Admin Requests";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect($_SERVER['HTTP_REFERER']);
			exit();
		}
		$this->form_validation->set_rules('reply', 'Reply', 'required|trim|html_escape');
		if ($this->form_validation->run() == FALSE) {
			$data = $this->Settingsmodel->get_options();
			$data['contacts'] = $this->Adminmodel->contacts();
			$data['ad_contacts'] = $this->Adminmodel->admin_contacts();
			$this->load->view('templates/header', $data);
			$this->load->view('admin/admin_request', $data);
			$this->load->view('templates/footer');
		} else {
			$res = $this->Adminmodel->save_reply();
			if ($res !== TRUE) {
				$uname = $this->session->userdata('ims_uname');
				$department = 'Admin';
				$action = "Reply failed to send " . $uname . ". Access:Admin Request Reply";
				$this->Adminmodel->log_activity($department, $action);
				$this->session->set_flashdata('reply_failed', 'Failed sending reply');
				redirect($_SERVER['HTTP_REFERER']);
				exit();
			} else {
				$uname = $this->session->userdata('ims_uname');
				$department = 'Admin';
				$action = "Reply sent by " . $uname . ". Access:Admin Request Reply";
				$this->Adminmodel->log_activity($department, $action);
				$this->session->set_flashdata('reply_sent', 'Reply Sent');
				redirect($_SERVER['HTTP_REFERER']);
				exit();
			}
		}
	}

	public function staffrequest()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$department = 'Admin';
			$action = "Not logged in. Access:Staff Requests";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('admin/login');
		}
		if ($this->session->userdata('ims_role') !== "1") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Admin';
			$action = "Unauthorized Access by " . $uname . ". Access:Staff Requests";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect($_SERVER['HTTP_REFERER']);
			exit();
		}
		$this->form_validation->set_rules('reply', 'Reply', 'required|trim|html_escape');
		if ($this->form_validation->run() == FALSE) {
			$data = $this->Settingsmodel->get_options();
			$data['contacts'] = $this->Adminmodel->contacts();
			$data['stf_contacts'] = $this->Adminmodel->stf_contacts();
			$this->load->view('templates/header', $data);
			$this->load->view('admin/stf_request');
			$this->load->view('templates/footer');
		} else {
			$res = $this->Adminmodel->stf_save_reply();
			if ($res !== TRUE) {
				$uname = $this->session->userdata('ims_uname');
				$department = 'Admin';
				$action = "Reply failed to send " . $uname . ". Access:Staff Request Reply";
				$this->Adminmodel->log_activity($department, $action);
				$this->session->set_flashdata('reply_failed', 'Failed sending reply');
				redirect($_SERVER['HTTP_REFERER']);
				exit();
			} else {
				$uname = $this->session->userdata('ims_uname');
				$department = 'Admin';
				$action = "Reply sent by " . $uname . ". Access:Staff RequestReply";
				$this->Adminmodel->log_activity($department, $action);
				$this->session->set_flashdata('reply_sent', 'Reply Sent');
				redirect($_SERVER['HTTP_REFERER']);
				exit();
			}
		}
	}

	public function studentrequest()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$department = 'Admin';
			$action = "Not logged in. Access:Student Requests";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('admin/login');
		}
		if ($this->session->userdata('ims_role') !== "1") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Admin';
			$action = "Unauthorized Access by " . $uname . ". Access:Student Requests";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect($_SERVER['HTTP_REFERER']);
			exit();
		}
		$this->form_validation->set_rules('reply', 'Reply', 'required|trim|html_escape');
		if ($this->form_validation->run() == FALSE) {
			$data = $this->Settingsmodel->get_options();
			$data['contacts'] = $this->Adminmodel->contacts();
			$data['std_contacts'] = $this->Adminmodel->std_contacts();
			$this->load->view('templates/header', $data);
			$this->load->view('admin/std_request');
			$this->load->view('templates/footer');
		} else {
			$res = $this->Adminmodel->std_save_reply();
			if ($res !== TRUE) {
				$uname = $this->session->userdata('ims_uname');
				$department = 'Admin';
				$action = "Reply failed to send " . $uname . ". Access:Student Request Reply";
				$this->Adminmodel->log_activity($department, $action);
				$this->session->set_flashdata('reply_failed', 'Failed sending reply');
				redirect($_SERVER['HTTP_REFERER']);
				exit();
			} else {
				$uname = $this->session->userdata('ims_uname');
				$department = 'Admin';
				$action = "Reply sent by " . $uname . ". Access:Student Request Reply";
				$this->Adminmodel->log_activity($department, $action);
				$this->session->set_flashdata('reply_sent', 'Reply Sent');
				redirect($_SERVER['HTTP_REFERER']);
				exit();
			}
		}
	}

	public function show_request()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$department = 'Admin';
			$action = "Not logged in. Access:Show Requests";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
		}
		if ($this->session->userdata('ims_role') !== "1") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Admin';
			$action = "Unauthorized Access by " . $uname . ". Access:Show Requests";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
		} else {
			$res = $this->Adminmodel->show_request($_POST['id']);
			$output = array();
			foreach ($res->result_array() as $row) {
				$output['id'] = $row['id'];
				$output['subj'] = $row['subj'];
				$output['msg'] = $row['msg'];
				$output['department'] = $row['department'];
				$output['email'] = $row['email'];
				$output['uname'] = $row['uname'];
				$output['staff_id'] = $row['staff_id'];
				$output['admin_id'] = $row['admin_id'];
				$output['student_id'] = $row['student_id'];
				$output['active'] = $row['active'];
				$output['seen'] = $row['seen'];
				$output['time'] = $row['time'];

				$output['token'] = $this->security->get_csrf_hash();
			}
			echo json_encode($output);
		}
	}

	public function delete_request()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$department = 'Admin';
			$action = "Not logged in. Access:Delete Request";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('admin/login');
		}
		if ($this->session->userdata('ims_role') !== "1") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Admin';
			$action = "Unauthorized Access by " . $uname . ". Access:Delete Request";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect($_SERVER['HTTP_REFERER']);
			exit();
		} else {
			$del_res = $this->Adminmodel->delete_request($_POST['id']);
			if ($del_res !== true) {
				$this->session->set_flashdata('request_del_err', 'Error deleting message');
			} else {
				$this->session->set_flashdata('request_del_succ', 'Message deleted!');
			}
		}
	}

	public function response()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$department = 'Admin';
			$action = "Not logged in. Access:Admin Response";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('admin/login');
		}
		if ($this->session->userdata('ims_role') !== "0") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Admin';
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
			$data['user_adm_contacts'] = $this->Adminmodel->user_adm_replies();
			$data['adm_contacts'] = $this->Adminmodel->adm_replies();
			$this->load->view('templates/header', $data);
			$this->load->view('admin/response', $data);
			$this->load->view('templates/footer');
		} else {
			$res = $this->Adminmodel->admin_save_reply();
			if ($res !== TRUE) {
				$uname = $this->session->userdata('ims_uname');
				$department = 'Admin';
				$action = "Failed to send reply by " . $uname . ". Access:Admin esponse Reply";
				$this->Staffmodel->log_activity($department, $action);
				$this->session->set_flashdata('reply_failed', 'Failed sending reply');
				redirect($_SERVER['HTTP_REFERER']);
				exit();
			} else {
				$uname = $this->session->userdata('ims_uname');
				$department = 'Admin';
				$action = "Reply sent by " . $uname . ". Access:Admin Response Reply";
				$this->Staffmodel->log_activity($department, $action);
				$this->session->set_flashdata('reply_sent', 'Reply Sent');
				redirect($_SERVER['HTTP_REFERER']);
				exit();
			}
		}
	}

	public function admin_show_response()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$department = 'Admin';
			$action = "Not logged in. Access:Show Response";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
		}
		if ($this->session->userdata('ims_role') !== "0") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Admin';
			$action = "Unauthorized Access by " . $uname . ". Access:Show Response";
			$this->Staffmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
		} else {
			$res = $this->Adminmodel->admin_show_response($_POST['id']);
			$output = array();
			foreach ($res->result_array() as $row) {
				$output['sbj'] = $row['subj'];
				$output['department'] = $row['department'];
				$output['reply'] = $row['reply'];
				$output['time'] = $row['time'];
				$output['id'] = $row['id'];
				$output['admin_id'] = $row['admin_id'];
				$output['token'] = $this->security->get_csrf_hash();
			}
			echo json_encode($output);
		}
	}

	public function admin_delete_response()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$department = 'Admin';
			$action = "Not logged in. Access:Delete Admin Response";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
		}
		if ($this->session->userdata('ims_role') !== "0") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Admin';
			$action = "Unauthorized Access by " . $uname . ". Access:Delete Admin Response";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
		} else {
			$del_res = $this->Adminmodel->admin_delete_response($_POST['id']);
			if ($del_res !== true) {
				$this->session->set_flashdata('request_del_err', 'Error deleting message');
			} else {
				$this->session->set_flashdata('request_del_succ', 'Message deleted!');
			}
		}
	}

	public function chart()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('admin/login');
			exit();
		}
		if ($this->session->userdata('ims_role') == "Staff" || $this->session->userdata('ims_role') == "Student") {
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect($_SERVER['HTTP_REFERER']);
			exit();
		}
		$data = $this->Settingsmodel->get_options();
		$data['contacts'] = $this->Adminmodel->contacts();
		$data['adm_contacts'] = $this->Adminmodel->adm_replies();
		$this->load->view('templates/header', $data);
		$this->load->view('pages/chart');
		$this->load->view('templates/footer');
	}
}
