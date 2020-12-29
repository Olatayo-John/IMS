<?php

class Adminmodel extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function login($uname, $pwd)
	{
		$query = $this->db->get_where('admin', array('uname' => $uname))->row();
		if (!$query) {
			return false;
			exit();
		} else {
			if ($query->active == 0) {
				return 'inactive';
				exit();
			}
			$pwdverify = password_verify($pwd, $query->password);
			if ($pwdverify == 0) {
				return false;
				exit();
			} elseif ($pwdverify == 1) {
				return $query;
			}
		}
	}

	public function log_activity($department, $action)
	{
		$data = array(
			'department' => $department,
			'action' => $action,
		);
		$this->db->insert('activity_log', $data);
		return true;
	}

	public function get_current_admin_info()
	{
		$id = $this->session->userdata('ims_id');
		$this->db->where('id', $id);
		$query = $this->db->get('admin');
		return $query->result_array();
	}

	public function get_current_admin_info_social()
	{
		$id = $this->session->userdata('ims_id');
		$this->db->where('adm_id', $id);
		$query = $this->db->get('adm_profile')->row();
		return $query;
	}

	public function save_personal_info($profile_img)
	{
		$data = array(
			'profile_img' => $profile_img,
			'uname' => htmlentities($this->input->post('uname')),
			'fname' => htmlentities($this->input->post('fname')),
			'lname' => htmlentities($this->input->post('lname')),
			'gender' => htmlentities($this->input->post('gender')),
		);
		// print_r($data);die;
		$this->db->where('id', $this->session->userdata('ims_id'));
		$this->db->update('admin', $data);

		$this->session->set_userdata('ims_uname', htmlentities($this->input->post('uname')));
		$this->session->set_userdata('ims_fname', htmlentities($this->input->post('fname')));
		$this->session->set_userdata('ims_lname', htmlentities($this->input->post('lname')));

		$bio = htmlentities($this->input->post('bio'));
		$this->updatebio($bio);
		return true;
	}

	public function updatebio($bio)
	{
		$this->db->set('bio', $bio);
		$this->db->where('adm_id', $this->session->userdata('ims_id'));
		$this->db->update('adm_profile');
		return true;
	}

	public function delete_profileimg($id, $gender)
	{
		if ($gender == "Male") {
			$img = "male_profile.png";
		} else if ($gender == "Female") {
			$img = "female_profile.png";
		}

		$this->db->set('profile_img', $img);
		$this->db->where('id', $this->session->userdata('ims_id'));
		$this->db->update('admin');

		$file_path = "assets/adm_uploads/" . $this->session->userdata('ims_profile_img');
		unlink($file_path);
		$this->session->set_userdata('ims_profile_img', $img);
		return true;
	}

	public function save_contact()
	{
		$data = array(
			'github' => htmlentities($this->input->post('github')),
			'fb' => htmlentities($this->input->post('fb')),
			'instagram' => htmlentities($this->input->post('instagram')),
			'twitter' => htmlentities($this->input->post('twitter')),
			'linkedin' => htmlentities($this->input->post('linkedin')),
			'google_plus' => htmlentities($this->input->post('google_plus')),
		);
		$this->db->where('adm_id', $this->session->userdata('ims_id'));
		$this->db->update('adm_profile', $data);

		$email = htmlentities($this->input->post('email'));
		$mobile = htmlentities($this->input->post('mobile'));
		$this->updateprofile($email, $mobile);
		return true;
	}

	public function check_pwd()
	{
		$c_pwd = $this->input->post('c_pwd');
		$query = $this->db->get_where('admin', array('id' => $this->session->userdata('ims_id')))->row();
		if (!$query) {
			return false;
			exit;
		} else {
			if (password_verify($c_pwd, $query->password)) {
				$this->db->set('password', password_hash($c_pwd, PASSWORD_DEFAULT));
				$this->db->where('id', $this->session->userdata('ims_id'));
				$this->db->update('admin');
				return true;
			} else {
				return false;
				exit;
			}
		}
	}

	public function updateprofile($email, $mobile)
	{
		$this->db->set('email', $email);
		$this->db->set('mobile', $mobile);
		$this->db->where('id', $this->session->userdata('ims_id'));
		$this->db->update('admin');
		$this->session->set_userdata('ims_email', htmlentities($this->input->post('email')));
		return true;
	}

	public function deact_account()
	{
		$this->db->set('active', '0');
		$this->db->where('id', $this->session->userdata('ims_id'));
		$this->db->update('admin');
		$this->session->set_userdata('ims_active', '0');
		return true;
	}

	public function reach_admin()
	{
		if ($this->session->userdata('ims_role') == "0") {
			$dept = "Admin";
			$admin_id = $this->session->userdata('ims_id');
		}
		if ($this->session->userdata('ims_role') == "Staff") {
			$dept = "Staff";
			$staff_id = $this->session->userdata('ims_id');
		}
		if ($this->session->userdata('ims_role') == "Student") {
			$dept = "Student";
			$student_id = $this->session->userdata('ims_id');
		}

		$data = array(
			'subj' => $this->input->post('subj'),
			'msg' => $this->input->post('msg'),
			'department' => $dept,
			'uname' => $this->session->userdata('ims_uname'),
			'email' => $this->session->userdata('ims_email'),
			'staff_id' => $staff_id,
			'student_id' => $student_id,
			'admin_id' => $admin_id,
			'active' => $this->session->userdata('ims_active'),
			'seen' => '0',
		);
		$this->db->insert('contact', $data);
		return true;
	}

	public function tadmin()
	{
		$t_ad = $this->db->get('admin');
		return $t_ad->num_rows();
	}
	public function active_admin()
	{
		$ac_ad = $this->db->get_where('admin', array('active' => '1'));
		return $ac_ad->num_rows();
	}
	public function inactive_admin()
	{
		$ac_ad = $this->db->get_where('admin', array('active' => '0'));
		return $ac_ad->num_rows();
	}

	public function tstaff()
	{
		$t_stf = $this->db->get('staff');
		return $t_stf->num_rows();
	}
	public function active_staff()
	{
		$ac_stf = $this->db->get_where('staff', array('active' => '1'));
		return $ac_stf->num_rows();
	}
	public function inactive_staff()
	{
		$in_ac_stt = $this->db->get_where('staff', array('active' => '0'));
		return $in_ac_stt->num_rows();
	}

	public function tstudent()
	{
		$t_std = $this->db->get('student');
		return $t_std->num_rows();
	}
	public function active_student()
	{
		$ac_std = $this->db->get_where('student', array('active' => '1'));
		return $ac_std->num_rows();
	}
	public function inactive_student()
	{
		$in_ac_std = $this->db->get_where('student', array('active' => '0'));
		return $in_ac_std->num_rows();
	}

	public function admin_activity_logs()
	{
		$this->db->order_by('id', 'DESC');
		//$this->db->limit(3);
		$query = $this->db->get_where('activity_log', array('department' => 'Admin'));
		return $query;
	}
	public function staff_activity_logs()
	{
		$this->db->order_by('id', 'DESC');
		//$this->db->limit(3);
		$query = $this->db->get_where('activity_log', array('department' => 'Staff'));
		return $query;
	}
	public function student_activity_logs()
	{
		$this->db->order_by('id', 'DESC');
		//$this->db->limit(3);
		$query = $this->db->get_where('activity_log', array('department' => 'Student'));
		return $query;
	}

	public function contacts()
	{
		$this->db->order_by('id', 'DESC');
		// $this->db->limit(2);
		$query = $this->db->get_where('contact', array('seen' => '0'));
		return $query;
	}
	public function admin_contacts()
	{
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get_where('contact', array('department' => 'Admin'));
		return $query;
	}
	public function stf_contacts()
	{
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get_where('contact', array('department' => 'Staff'));
		return $query;
	}
	public function std_contacts()
	{
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get_where('contact', array('department' => 'Student'));
		return $query;
	}

	public function save_reply()
	{
		$data = array(
			'request_id' => $this->input->post('request_id'),
			'subj' => $this->input->post('subj'),
			'department' => $this->input->post('dept'),
			'reply' => $this->input->post('reply'),
			'admin_id' => $this->input->post('admin_id'),
			'seen' => '0',
		);
		$this->db->insert('replies', $data);
		return true;
	}
	public function stf_save_reply()
	{
		$data = array(
			'request_id' => $this->input->post('request_id'),
			'subj' => $this->input->post('subj'),
			'department' => $this->input->post('dept'),
			'reply' => $this->input->post('reply'),
			'staff_id' => $this->input->post('staff_id'),
			'seen' => '0',
		);
		$this->db->insert('replies', $data);
		return true;
	}
	public function std_save_reply()
	{
		$data = array(
			'request_id' => $this->input->post('request_id'),
			'subj' => $this->input->post('subj'),
			'department' => $this->input->post('dept'),
			'reply' => $this->input->post('reply'),
			'student_id' => $this->input->post('student_id'),
			'seen' => '0',
		);
		$this->db->insert('replies', $data);
		return true;
	}

	public function user_adm_replies()
	{
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get_where('replies', array('admin_id' => $this->session->userdata('ims_id')));
		return $query;
	}
	public function adm_replies()
	{
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get_where('replies', array('admin_id' => $this->session->userdata('ims_id'), 'seen' => '0'));
		return $query;
	}

	public function admin_save_reply()
	{
		$data = array(
			'subj' => $this->input->post('subj'),
			'msg' => $this->input->post('reply'),
			'department' => $this->input->post('dept'),
			'uname' => $this->session->userdata('ims_uname'),
			'email' => $this->session->userdata('ims_email'),
			'admin_id' => $this->input->post('admin_id'),
			'active' => $this->session->userdata('ims_active'),
			'seen' => '0',
		);
		$this->db->insert('contact', $data);
		return true;
	}

	public function admin_show_response($id)
	{
		$this->db->set('seen', '1');
		$this->db->where('id', $id);
		$this->db->update('replies');
		$query = $this->db->get_where('replies', array('id' => $id));
		return $query;
	}

	public function admin_delete_response($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('replies');
		return true;
	}

	public function show_request($id)
	{
		$this->db->set('seen', '1');
		$this->db->where('id', $id);
		$this->db->update('contact');
		$query = $this->db->get_where('contact', array('id' => $id));
		return $query;
	}

	public function delete_request($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('contact');
		return true;
	}
}
