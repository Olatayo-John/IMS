<?php

class Staffmodel extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function login($uname, $pwd)
	{
		$query = $this->db->get_where('staff', array('uname' => $uname))->row();
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

	public function get_current_staff_info()
	{
		$id = $this->session->userdata('ims_id');
		$this->db->where('id', $id);
		$query = $this->db->get('staff');
		return $query->result_array();
	}

	public function get_current_staff_info_social()
	{
		$id = $this->session->userdata('ims_id');
		$this->db->where('stf_id', $id);
		$query = $this->db->get('stf_profile')->row();
		return $query;
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
		$this->db->update('staff');

		$file_path = "assets/stf_uploads/" . $this->session->userdata('ims_profile_img');
		unlink($file_path);
		$this->session->set_userdata('ims_profile_img', $img);
		return true;
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
		$this->db->update('staff', $data);

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
		$this->db->where('stf_id', $this->session->userdata('ims_id'));
		$this->db->update('stf_profile');
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
		$this->db->where('stf_id', $this->session->userdata('ims_id'));
		$this->db->update('stf_profile', $data);

		$email = htmlentities($this->input->post('email'));
		$mobile = htmlentities($this->input->post('mobile'));
		$this->updateprofile($email, $mobile);
		return true;
	}

	public function updateprofile($email, $mobile)
	{
		$this->db->set('email', $email);
		$this->db->set('mobile', $mobile);
		$this->db->where('id', $this->session->userdata('ims_id'));
		$this->db->update('staff');
		$this->session->set_userdata('ims_email', htmlentities($this->input->post('email')));
		return true;
	}

	public function check_pwd()
	{
		$c_pwd = $this->input->post('c_pwd');
		$query = $this->db->get_where('staff', array('id' => $this->session->userdata('ims_id')))->row();
		if (!$query) {
			return false;
			exit;
		} else {
			if (password_verify($c_pwd, $query->password)) {
				$this->db->set('password', password_hash($c_pwd, PASSWORD_DEFAULT));
				$this->db->where('id', $this->session->userdata('ims_id'));
				$this->db->update('staff');
				return true;
			} else {
				return false;
				exit;
			}
		}
	}

	public function deact_account()
	{
		$this->db->set('active', '0');
		$this->db->where('id', $this->session->userdata('ims_id'));
		$this->db->update('staff');
		$this->session->set_userdata('ims_active', '0');
		return true;
	}

	public function user_stf_replies()
	{
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get_where('replies', array('staff_id' => $this->session->userdata('ims_id')));
		return $query;
	}
	public function stf_replies()
	{
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get_where('replies', array('staff_id' => $this->session->userdata('ims_id'), 'seen' => '0'));
		return $query;
	}

	public function show_request($id)
	{
		$this->db->set('seen', '1');
		$this->db->where('id', $id);
		$this->db->update('replies');
		$query = $this->db->get_where('replies', array('id' => $id));
		return $query;
	}

	public function delete_response($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('replies');
		return true;
	}

	public function save_reply()
	{
		$data = array(
			'subj' => $this->input->post('subj'),
			'msg' => $this->input->post('reply'),
			'department' => 'Staff',
			'uname' => $this->session->userdata('ims_uname'),
			'email' => $this->session->userdata('ims_email'),
			'staff_id' => $this->session->userdata('ims_id'),
			'active' => $this->session->userdata('ims_active'),
			'seen' => '0',
		);
		$this->db->insert('contact', $data);
		return true;
	}

	public function add_new_stf($active, $profile_img)
	{
		$hash_pwd = password_hash($this->input->post('pwd'), PASSWORD_DEFAULT);
		$data = array(
			'profile_img' => $profile_img,
			'uname' => htmlentities($this->input->post('fname')),
			'fname' => htmlentities($this->input->post('fname')),
			'lname' => htmlentities($this->input->post('lname')),
			'gender' => htmlentities($this->input->post('gender')),
			'email' => htmlentities($this->input->post('email')),
			'mobile' => htmlentities($this->input->post('mobile')),
			'active' => $active,
			'role' => "Staff",
			'password' => $hash_pwd,
		);
		$this->db->insert('staff', $data);
		return true;
	}
}
