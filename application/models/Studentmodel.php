<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Studentmodel extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function login($uname, $pwd)
	{
		$query = $this->db->get_where('student', array('uname' => $uname))->row();
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

	public function get_all_students($limit = false, $offset = false)
	{
		$this->db->limit($limit, $offset);
		$query = $this->db->get('student');
		return $query;
	}

	public function get_all_course()
	{
		$this->db->order_by('name', 'desc');
		$query = $this->db->get('course');
		return $query;
	}

	public function get_all_years()
	{
		$this->db->order_by('current_course_year', 'desc');
		$query = $this->db->group_by('current_course_year')->get('student');
		return $query;
	}

	public function get_all_branch()
	{
		$this->db->order_by('name', 'asc');
		$query = $this->db->get('branch');
		return $query;
	}

	public function search_student($query)
	{
		$this->db->select('*');
		$this->db->from('student');
		if ($query != '') {
			$this->db->like('uname', $query);
			$this->db->or_like('fname', $query);
			$this->db->or_like('lname', $query);
			$this->db->or_like('fathersn', $query);
			$this->db->or_like('mothersn', $query);
			$this->db->or_like('email', $query);
			$this->db->or_like('gender', $query);
			$this->db->or_like('course', $query);
			$this->db->or_like('mobile', $query);
		}
		$this->db->order_by('id', 'desc');
		return $this->db->get();
	}

	public function filter_by($flt_key, $flt_val)
	{
		$this->db->order_by('id', 'desc');
		$table_num = count($flt_key);
		$value_num = count($flt_val);

		for ($i = 0; $i < $table_num; $i++) {
			$this->db->where(array($flt_key[$i] => $flt_val[$i]));
		}
		$query = $this->db->get('student');
		if (!$query) {
			return false;
		} else {
			return $query;
		}
	}

	public function export_std_csv()
	{
		$this->db->order_by('id', 'desc');
		$this->db->select('id,uname,fname,lname,fathersn,mothersn,email,gender,mobile,course,started_course_year,end_course_year,current_course_year,active,role');
		$query = $this->db->get('student');
		return $query->result_array();
	}

	public function filter_students($param, $col_name)
	{
		$this->db->order_by($col_name, $param);
		$query = $this->db->get('student');
		return $query;
	}

	public function change_std_status($id, $status)
	{
		$this->db->set('active', $status, FALSE);
		$this->db->where('id', $id);
		$this->db->update('student');
		return true;
	}

	public function delete_student($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('student');
		$this->delete_student_profile($id);
		return true;
	}

	public function delete_student_profile($id)
	{
		$this->db->where('std_id', $id);
		$this->db->delete('std_profile');
		return true;
	}

	public function chkbox_delete_student($filter)
	{
		//$this->db->where('id', array($filter));
		$this->db->where('id', $filter);
		$this->db->delete('student');
		return $this->db->last_query();
		//$this->chkbox_delete_student_profile($id);
		//return true;
	}

	public function chkbox_delete_student_profile($id)
	{
		$this->db->where('std_id', $id);
		$this->db->delete('std_profile');
		return true;
	}

	public function user_std_replies()
	{
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get_where('replies', array('student_id' => $this->session->userdata('ims_id')));
		return $query;
	}
	public function std_replies()
	{
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get_where('replies', array('student_id' => $this->session->userdata('ims_id'), 'seen' => '0'));
		return $query;
	}

	public function get_current_student_info()
	{
		$id = $this->session->userdata('ims_id');
		$this->db->where('id', $id);
		$query = $this->db->get('student');
		return $query->result_array();
	}

	public function get_current_student_info_social()
	{
		$id = $this->session->userdata('ims_id');
		$this->db->where('std_id', $id);
		$query = $this->db->get('std_profile')->row();
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
		$this->db->where('id', $this->session->userdata('ims_id'));
		$this->db->update('student', $data);

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
		$this->db->where('std_id', $this->session->userdata('ims_id'));
		$this->db->update('std_profile');
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
		$this->db->where('std_id', $this->session->userdata('ims_id'));
		$this->db->update('std_profile', $data);

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
		$this->db->update('student');
		$this->session->set_userdata('ims_email', htmlentities($this->input->post('email')));
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
		$this->db->update('student');

		$file_path = "assets/std_uploads/" . $this->session->userdata('ims_profile_img');
		unlink($file_path);
		$this->session->set_userdata('ims_profile_img', $img);
		return true;
	}

	public function save_course()
	{
		$course = htmlentities(strtolower($this->input->post('course')));
		if ($course == "b.tech" || $course == "btech") {
			$branch = htmlentities(strtolower($this->input->post('branch')));
		} else {
			$branch = "";
		}
		$data = array(
			'course' => $course,
			'branch' => $branch,
			'started_course_year' => htmlentities($this->input->post('started_course_year')),
			'end_course_year' => htmlentities($this->input->post('end_course_year')),
			'current_course_year' => htmlentities($this->input->post('current_course_year')),
		);
		$this->db->where('id', $this->session->userdata('ims_id'));
		$this->db->update('student', $data);
		return true;
	}

	public function check_pwd()
	{
		$c_pwd = $this->input->post('c_pwd');
		$query = $this->db->get_where('student', array('id' => $this->session->userdata('ims_id')))->row();
		if (!$query) {
			return false;
			exit;
		} else {
			if (password_verify($c_pwd, $query->password)) {
				$this->db->set('password', password_hash($c_pwd, PASSWORD_DEFAULT));
				$this->db->where('id', $this->session->userdata('ims_id'));
				$this->db->update('student');
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
		$this->db->update('student');
		$this->session->set_userdata('ims_active', '0');
		return true;
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
			'department' => 'Student',
			'uname' => $this->session->userdata('ims_uname'),
			'email' => $this->session->userdata('ims_email'),
			'student_id' => $this->session->userdata('ims_id'),
			'active' => $this->session->userdata('ims_active'),
			'seen' => '0',
		);
		$this->db->insert('contact', $data);
		return true;
	}

	public function add_new_std($active, $profile_img)
	{
		$hash_pwd = password_hash($this->input->post('pwd'), PASSWORD_DEFAULT);
		$data = array(
			'profile_img' => $profile_img,
			'uname' => htmlentities($this->input->post('fname')),
			'fname' => htmlentities($this->input->post('fname')),
			'lname' => htmlentities($this->input->post('lname')),
			'fathersn' => htmlentities($this->input->post('f_name')),
			'mothersn' => htmlentities($this->input->post('m_name')),
			'gender' => htmlentities($this->input->post('gender')),
			'email' => htmlentities($this->input->post('email')),
			'mobile' => htmlentities($this->input->post('mobile')),
			'course' => htmlentities($this->input->post('course')),
			'branch' => htmlentities($this->input->post('branch')),
			'active' => $active,
			'role' => "Student",
			'password' => $hash_pwd,
		);
		// print_r($data);
		// die;
		$this->db->insert('student', $data);
		return true;
	}
}
