<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Student extends CI_Controller
{

	public function index()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$department = 'Student';
			$action = "Not logged in. Access:Student";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('student/login');
		}
		if ($this->session->userdata('ims_role') == "Student" && $this->session->userdata('ims_logged_in')) {
			redirect('student/dashboard');
		}
		if ($this->session->userdata('ims_role') == "1"  && $this->session->userdata('ims_logged_in')) {
			redirect('student/students');
		}
		if ($this->session->userdata('ims_role') == "0" && $this->session->userdata('ims_logged_in')) {
			redirect('student/students');
		}
		if ($this->session->userdata('ims_role') == "Staff" && $this->session->userdata('ims_logged_in')) {
			redirect('student/students');
		}
	}

	public function students($offset = 0)
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$department = 'Student';
			$action = "Not logged in. Access:Student";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('student/login');
		}
		if ($this->session->userdata('ims_role') == "Student") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Student';
			$action = "Unauthorized Access by " . $uname . ". Access:Student";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect('student/dashboard');
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
		$this->load->view('student/index', [$data, 'links' => $links]);
		$this->load->view('templates/footer');
	}

	public function login()
	{
		if ($this->session->userdata('ims_logged_in')) {
			redirect('student/index');
		}
		$this->form_validation->set_rules('uname', 'Username', 'required|trim|html_escape');
		$this->form_validation->set_rules('pwd', 'Password', 'required|trim|html_escape');

		if ($this->form_validation->run() == false) {
			$data = $this->Settingsmodel->get_options();
			$data['contacts'] = $this->Adminmodel->contacts();
			$this->load->view('templates/header', $data);
			$this->load->view('student/login');
			$this->load->view('templates/footer');
		} else {
			$uname = htmlentities($this->input->post('uname'));
			$pwd = htmlentities($this->input->post('pwd'));

			$validate = $this->Studentmodel->login($uname, $pwd);
			if ($validate == 'inactive') {
				$uname = $this->input->post('uname');
				$department = 'Student';
				$action = "Failed login attempt by " . $uname . ". Account is Inactive";
				$this->Adminmodel->log_activity($department, $action);
				$this->session->set_flashdata('inactive_acct', 'Your account is inactive. Contact Super Admin');
				redirect('student/login');
				exit();
			}
			if (!$validate) {
				$uname = $this->input->post('uname');
				$department = 'Student';
				$action = "Failed login attempt by " . $uname . ". Invalid Username/Password";
				$this->Studentmodel->log_activity($department, $action);
				$this->session->set_flashdata('invalid_login', 'Incorrect Username/Password');
				redirect('student/login');
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

				$std_ses = array(
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
				$department = 'Student';
				$action = "Successfull login by " . $uname;
				$this->Adminmodel->log_activity($department, $action);
				$this->session->set_userdata($std_ses);
				$this->session->set_flashdata('valid_login', 'Welcome ' . $fname . " " . $lname);
				redirect('student/dashboard');
			}
		}
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
		$this->session->unset_userdata('ims_active');
		$this->session->unset_userdata('ims_role');
		$this->session->unset_userdata('ims_profile_img');
		$this->session->unset_userdata('ims_logged_in');
		$this->session->sess_destroy();

		$this->session->set_flashdata('log_out', 'Logged out');
		redirect('pages/index');
	}

	public function search_student($offset = 0)
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$department = 'Student';
			$action = "Not logged in. Access:Search Students";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
		}
		if ($this->session->userdata('ims_role') == "Student") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Student';
			$action = "Unauthorized Access by " . $uname . ". Access:Search Students";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
		}
		$output = '';
		$query = '';
		if ($this->input->post('query')) {
			$query = $this->input->post('query');
		}
		$res = $this->Studentmodel->search_student($query);
		$output .= '<table class="table table-hover table-light table-bordered std_table table-sm">
		<thead class="text-light" style="background-color: #00695C">
		<tr>
		<th>
		<input type="checkbox" name="chk_allbox" id="chk_allbox" class="chk_allbox">
		</th>
		<th>
		<div class="fn">First Name</div>
		</th>
		<th>
		<div class="fn">Last Name</div>
		</th>
		<th>
		<div class="fn">Course</div>
		</th>
		<th>
		<div class="fn">Email</div>
		</th>
		<th>
		<div class="fn">Mobile</div>
		</th>
		<th>
		<div class="fn">Status</div>
		</th>
		</tr>
		</thead>';
		if ($res->num_rows() == '0') {
			$output .= '<tr class="text-dark">
			<td colspan="10" class="font-weight-bolder text-center text-uppercase mt-5 mb-5">
			<i class="fas fa-folder-minus"></i>No data found</td>
			</tr>';
		} else {
			foreach ($res->result_array() as $std) {
				$output .= '<tr id="' . $std['id'] . '">
				<td>
				<input type="checkbox" name="chk_onebox" id="chk_onebox" class="chk_onebox">
				</td>
				<td>' . $std['fname'] . '
				<div class="' . $std['id'] . ' action_div">
				<small>
				<a href="" class="text-info action_div_a view_std" id="' . $std['id'] . '">View</a>
				<a href="" class="text-info action_div_a edit edit_std" id="' . $std['id'] . '">Edit</a>
				<a href="" class="text-danger action_div_a delete_std" id="' . $std['id'] . '">Delete</a>
				</small>
				</div>
				</td>
				<td>' . $std['lname'] . '</td>
				<td class="text-uppercase">' . $std['course'] . '</td>
				<td>' . $std['email'] . '</td>
				<td>' . $std['mobile'] . '</td>';
				if ($std["active"] == '0') {
					$output .= '<td class="text-center">
					<i class="fas fa-toggle-off text-danger status_icon" id="' . $std['id'] . '" status="1"></i>
					</td>';
				} elseif ($std["active"] == '1') {
					$output .= '<td class="text-center">
					<i class="fas fa-toggle-on text-success status_icon" id="' . $std['id'] . '" status="0"></i>
					</td>';
				}
			}
			$output .= '</tr>
			</table>';
		}
		echo $output;
	}

	public function filter_by()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$department = 'Student';
			$action = "Not logged in. Access:Filter By Students";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
		}
		if ($this->session->userdata('ims_role') == "Student") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Student';
			$action = "Unauthorized Access by " . $uname . ". Access:Filter By Students";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
		}
		$res = $this->Studentmodel->filter_by($_POST['flt_key'], $_POST['flt_val']);
		if ($res == false) {
			return false;
		} else {
			$output = "";
			$output .= '<table class="table table-hover table-light table-bordered std_table table-sm">
			<thead class="text-light" style="background-color: #00695C">
			<tr>
			<th>
			<input type="checkbox" name="chk_allbox" id="chk_allbox" class="chk_allbox">
			</th>
			<th>
			<div class="fn">First Name</div>
			</th>
			<th>
			<div class="fn">Last Name</div>
			</th>
			<th>
			<div class="fn">Course</div>
			</th>
			<th>
			<div class="fn">Branch</div>
			</th>
			<th>
			<div class="ccy">Current Year</div>
			</th>
			<th>
			<div class="fn">End Year</div>
			</th>
			<th>
			<div class="fn">Email</div>
			</th>
			<th>
			<div class="fn">Mobile</div>
			</th>
			<th>
			<div class="fn">Status</div>
			</th>
			</tr>
			</thead>';
			if ($res->num_rows() == '0') {
				$output .= '<tr class="text-dark">
				<td colspan="10" class="font-weight-bolder text-center text-uppercase mt-5 mb-5">
				<i class="fas fa-folder-minus"></i>No data found</td>
				</tr>';
			} else {
				foreach ($res->result_array() as $std) {
					$output .= '<tr id="' . $std['id'] . '">
					<td>
					<input type="checkbox" name="chk_onebox" id="chk_onebox" class="chk_onebox">
					</td>
					<td>' . $std['fname'] . '
					<div class="' . $std['id'] . ' action_div">
					<small>
					<a href="" class="text-info action_div_a view_std" id="' . $std['id'] . '">View</a>
					<a href="" class="text-info action_div_a edit edit_std" id="' . $std['id'] . '">Edit</a>
					<a href="" class="text-danger action_div_a delete_std" id="' . $std['id'] . '">Delete</a>
					</small>
					</div>
					</td>
					<td>' . $std['lname'] . '</td>
					<td class="text-uppercase">' . $std['course'] . '</td>';
					if (!$std["branch"]) {
						$output .= '<td class="text-uppercase">null</td>';
					} else {
						$output .= '<td class="text-uppercase">' . $std['branch'] . '</td>';
					}
					if ($std["current_course_year"] == "1") {
						$output .= '<td>' . $std['current_course_year'] . '<sup>st</sup> year</td>';
					} elseif ($std["current_course_year"] == '2') {
						$output .= '<td>' . $std['current_course_year'] . '<sup>nd</sup> year</td>';
					} elseif ($std["current_course_year"] == '3') {
						$output .= '<td>' . $std['current_course_year'] . '<sup>rd</sup> year</td>';
					} elseif ($std["current_course_year"] == '4') {
						$output .= '<td>' . $std['current_course_year'] . '<sup>th</sup> year</td>';
					}
					$output .= '<td>' . $std['end_course_year'] . '</td>
					<td>' . $std['email'] . '</td>
					<td>' . $std['mobile'] . '</td>';
					if ($std["active"] == '0') {
						$output .= '<td class="text-center">
						<i class="fas fa-toggle-off text-danger status_icon" id="' . $std['id'] . '" status="1"></i>
						</td>';
					} elseif ($std["active"] == '1') {
						$output .= '<td class="text-center">
						<i class="fas fa-toggle-on text-success status_icon" id="' . $std['id'] . '" status="0"></i>
						</td>';
					}
				}
				$output .= '</tr>
				</table>';
			}
			echo $output;
		}
	}

	public function refresh_std_table($offset = 0)
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$department = 'Student';
			$action = "Not logged in. Access:Refresh Students Table";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
		}
		if ($this->session->userdata('ims_role') == "Student") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Student';
			$action = "Unauthorized Access by " . $uname . ". Access:Refresh Students Table";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
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
		$res = $this->Studentmodel->get_all_students($config["per_page"], $offset);
		if (!$res) {
			$this->session->set_flashdata('acc_denied', 'Access Denied');
		} else {
			$output = "";
			$output .= '<table class="table table-hover table-light table-bordered std_table table-sm">
			<thead class="text-light" style="background-color: #00695C">
			<tr>
			<th>
			<input type="checkbox" name="chk_allbox" id="chk_allbox" class="chk_allbox">
			</th>
			<th>
			<div class="fn">First Name<i class="fas fa-sort-amount-down" param="asc" name="fname"></i></div>
			</th>
			<th>
			<div class="fn">Last Name<i class="fas fa-sort-amount-down" param="asc" name="lname"></i></div>
			</th>
			<th>
			<div class="fn">Course<i class="fas fa-sort-amount-down" param="asc" name="course"></i></div>
			</th>
			<th>
			<div class="fn">Email<i class="fas fa-sort-amount-down" param="asc" name="email"></i></div>
			</th>
			<th>
			<div class="fn">Mobile<i class="fas fa-sort-amount-down" param="asc" name="mobile"></i></div>
			</th>
			<th>
			<div class="fn">Status<i class="fas fa-sort-amount-down" param="asc" name="active"></i></div>
			</th>
			</tr>
			</thead>';
			if ($res->num_rows() == '0') {
				$output .= '<tr class="text-dark">
				<td colspan="6" class="font-weight-bolder text-center text-uppercase">No data found</td>
				</tr>';
			} else {
				foreach ($res->result_array() as $std) {
					$output .= '<tr id="' . $std['id'] . '">
					<td>
					<input type="checkbox" name="chk_onebox" id="chk_onebox" class="chk_onebox">
					</td>
					<td>' . $std['fname'] . '
					<div class="' . $std['id'] . ' action_div">
					<small>
					<a href="" class="text-info action_div_a view_std" id="' . $std['id'] . '">View</a>
					<a href="" class="text-info action_div_a edit edit_std" id="' . $std['id'] . '">Edit</a>
					<a href="" class="text-danger action_div_a delete_std" id="' . $std['id'] . '">Delete</a>
					</small>
					</div>
					</td>
					<td>' . $std['lname'] . '</td>
					<td class="text-uppercase">' . $std['course'] . '</td>
					<td>' . $std['email'] . '</td>
					<td>' . $std['mobile'] . '</td>';
					if ($std["active"] == '0') {
						$output .= '<td class="text-center">
						<i class="fas fa-toggle-off text-danger status_icon" id="' . $std['id'] . '" status="1"></i>
						</td>';
					} elseif ($std["active"] == '1') {
						$output .= '<td class="text-center">
						<i class="fas fa-toggle-on text-success status_icon" id="' . $std['id'] . '" status="0"></i>
						</td>';
					}
				}
				$output .= '</tr>
				</table>' . $links . '';
			}
			echo $output;
		}
	}

	public function export_std_csv()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$department = 'Student';
			$action = "Not logged in. Access:Export Students CSV";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
		}
		if ($this->session->userdata('ims_role') == "Student") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Student';
			$action = "Unauthorized Access by " . $uname . ". Access:Export Students CSV";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
		}
		header("Content-Type: text/csv; charset=utf-8");
		header("Content-Disposition: attachment; filename=students.csv");
		$output = fopen("php://output", "w");
		fputcsv($output, array('ID', 'Username', 'First Name', 'Last Name', 'Fathers Name', 'Mothers Name', 'E-mail', 'Gender', 'Mobile', 'Course', 'Current Course Year', 'Started Course Year ', 'End Course Year', 'Active', 'Role'));
		$data = $this->Studentmodel->export_std_csv();
		foreach ($data as $row) {
			fputcsv($output, $row);
		}
		fclose($output);
		$fname = $this->session->userdata('ims_fname');
		$lname = $this->session->userdata('ims_lname');
		$department = 'Student';
		$action = $fname . " " . $lname . " " . "exported 'StudentsCSV.csv'";
		$this->Studentmodel->log_activity($department, $action);
	}

	public function filter_students()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$department = 'Student';
			$action = "Not logged in. Access:Filter Students";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
		}
		if ($this->session->userdata('ims_role') == "Student") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Student';
			$action = "Unauthorized Access by " . $uname . ". Access:Filter Students";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
		}
		$res = $this->Studentmodel->filter_students($_POST['param'], $_POST['col_name']);
		if (!$res) {
			$this->session->set_flashdata('acc_denied', 'Access Denied');
		} else {
			$output = "";
			$output .= '<table class="table table-hover table-light table-bordered std_table table-sm">
			<thead class="text-light" style="background-color: #00695C"s>
			<tr>
			<th>
			<input type="checkbox" name="chk_allbox" id="chk_allbox" class="chk_allbox">
			</th>
			<th>
			<div class="fn">First Name<i class="fas fa-sort-amount-down" param="asc" name="fname"></i></div>
			</th>
			<th>
			<div class="fn">Last Name<i class="fas fa-sort-amount-down" param="asc" name="lname"></i></div>
			</th>
			<th>
			<div class="fn">Course<i class="fas fa-sort-amount-down" param="asc" name="course"></i></div>
			</th>
			<th>
			<div class="fn">Email<i class="fas fa-sort-amount-down" param="asc" name="email"></i></div>
			</th>
			<th>
			<div class="fn">Mobile<i class="fas fa-sort-amount-down" param="asc" name="mobile"></i></div>
			</th>
			<th>
			<div class="fn">Status<i class="fas fa-sort-amount-down" param="asc" name="active"></i></div>
			</th>
			</tr>
			</thead>';
			if ($res->num_rows() == '0') {
				$output .= '<tr class="text-dark">
				<td colspan="6" class="font-weight-bolder text-center text-uppercase">No data found</td>
				</tr>';
			} else {
				foreach ($res->result_array() as $std) {
					$output .= '<tr id="' . $std['id'] . '">
					<td>
					<input type="checkbox" name="chk_onebox" id="chk_onebox" class="chk_onebox">
					</td>
					<td>' . $std['fname'] . '
					<div class="' . $std['id'] . ' action_div">
					<small>
					<a href="" class="text-info action_div_a view_std" id="' . $std['id'] . '">View</a>
					<a href="" class="text-info action_div_a edit edit_std" id="' . $std['id'] . '">Edit</a>
					<a href="" class="text-danger action_div_a delete_std" id="' . $std['id'] . '">Delete</a>
					</small>
					</div>
					</td>
					<td>' . $std['lname'] . '</td>
					<td class="text-uppercase">' . $std['course'] . '</td>
					<td>' . $std['email'] . '</td>
					<td>' . $std['mobile'] . '</td>';
					if ($std["active"] == '0') {
						$output .= '<td class="text-center">
						<i class="fas fa-toggle-off text-danger status_icon" id="' . $std['id'] . '" status="1"></i>
						</td>';
					} elseif ($std["active"] == '1') {
						$output .= '<td class="text-center">
						<i class="fas fa-toggle-on text-success status_icon" id="' . $std['id'] . '" status="0"></i>
						</td>';
					}
				}
				$output .= '</tr>
				</table>';
			}
			echo $output;
		}
	}

	public function change_std_status()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$department = 'Student';
			$action = "Not logged in. Access:Change Student Status";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
		}
		if ($this->session->userdata('ims_role') == "Student") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Student';
			$action = "Unauthorized Access by " . $uname . ". Access:Change Student Status";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
		}
		$res = $this->Studentmodel->change_std_status($_POST['id'], $_POST['status']);
		if ($res == true) {
			// $this->session->set_flashdata('std_stat_succ', 'Student status updated');
			$data = $this->security->get_csrf_hash();
			echo json_encode($data);
		} else {
			$this->session->set_flashdata('std_stat_err', 'Failed to update student status');
		}
	}

	public function view_student()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$department = 'Student';
			$action = "Not logged in. Access:View Student";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
		}
		if ($this->session->userdata('ims_role') == "Student") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Student';
			$action = "Unauthorized Access by " . $uname . ". Access:View Student";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
		}
		$res = $this->Studentmodel->delete_student($_POST['id']);
		if ($res == true) {
			$this->session->set_flashdata('std_del_succ', 'Student data deleted');
		} else {
			$this->session->set_flashdata('std_del_err', 'Failed to deleted data');
		}
	}

	public function edit_student()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$department = 'Student';
			$action = "Not logged in. Access:Edit Student";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
		}
		if ($this->session->userdata('ims_role') == "Student") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Student';
			$action = "Unauthorized Access by " . $uname . ". Access:Edit Student";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
		}
		// $res = $this->Studentmodel->delete_student($_POST['id']);
		$res = false;
		if ($res == true) {
			$this->session->set_flashdata('std_del_succ', 'Student data deleted');
		} else {
			$this->session->set_flashdata('std_del_err', 'Failed to deleted data');
		}
	}

	public function delete_student()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$department = 'Student';
			$action = "Not logged in. Access:Delete Student";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
		}
		if ($this->session->userdata('ims_role') == "Student") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Student';
			$action = "Unauthorized Access by " . $uname . ". Access:Delete Student";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
		}
		$res = $this->Studentmodel->delete_student($_POST['id']);
		if ($res == true) {
			$this->session->set_flashdata('std_del_succ', 'Student data deleted');
		} else {
			$this->session->set_flashdata('std_del_err', 'Failed to deleted data');
		}
	}

	public function new()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$department = 'Student';
			$action = "Not logged in. Access:Add Student";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('student/login');
		}
		if ($this->session->userdata('ims_role') == "Student") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Student';
			$action = "Unauthorized Access by " . $uname . ". Access:Add Student";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect('student/dashboard');
			exit();
		}

		$this->form_validation->set_rules('fname', 'First Name', 'required|html_escape|trim');
		$this->form_validation->set_rules('lname', 'Last Name', 'required|html_escape|trim');
		$this->form_validation->set_rules('f_name', 'Fathers Name', 'html_escape|trim');
		$this->form_validation->set_rules('m_name', 'Mothers Name', 'html_escape|trim');
		$this->form_validation->set_rules('gender', 'Gender', 'html_escape|trim');
		$this->form_validation->set_rules('email', 'Email', 'required|html_escape|trim|valid_email');
		$this->form_validation->set_rules('mobile', 'Mobile', 'html_escape|trim');
		$this->form_validation->set_rules('course', 'Course', 'required|html_escape|trim');
		$this->form_validation->set_rules('branch', 'Branch', 'html_escape|trim');
		$this->form_validation->set_rules('pwd', 'Password', 'required|trim');

		if ($this->form_validation->run() == false) {
			$data = $this->Settingsmodel->get_options();
			$data['contacts'] = $this->Adminmodel->contacts();
			$data['adm_contacts'] = $this->Adminmodel->adm_replies();
			$data['stf_contacts'] = $this->Staffmodel->stf_replies();
			$data['std_contacts'] = $this->Studentmodel->std_replies();
			$data['courses'] = $this->Studentmodel->get_all_course();
			$data['branches'] = $this->Studentmodel->get_all_branch();
			$this->load->view('templates/header', $data);
			$this->load->view('student/new_std');
			$this->load->view('templates/footer');
		} else {
			$email = htmlentities($this->input->post('email'));
			$fname = htmlentities($this->input->post('fname'));
			$lname = htmlentities($this->input->post('lname'));
			$pwd = htmlentities($this->input->post('pwd'));

			$mail_res = $this->new_std_email($email, $fname, $lname, $pwd);
			// $mail_res = true;
			if ($mail_res !== true) {
				$department = 'Student';
				$action = "Error sending students credentials. Access:Student Credentials";
				$this->Studentmodel->log_activity($department, $action);
				$this->session->set_flashdata('mail_err', $mail_res);
				redirect($_SERVER['HTTP_REFERER']);
			} else {
				if (isset($_POST['active_std'])) {
					$active = "1";
				} else {
					$active = "0";
				}

				if ($_FILES['std_profile']['name']) {
					$rand = mt_rand(0, 10000000);
					$fname = strtolower(htmlentities($this->input->post("fname")));

					$config['upload_path'] = './assets/std_uploads';
					$config['allowed_types'] = 'jpg|jpeg|png|';
					$config['max_size'] = '2048';
					$config['max_height'] = '3000';
					$config['max_width'] = '3000';
					$config['file_name'] = $rand . $fname;
					$this->load->library('upload', $config);

					if (!$this->upload->do_upload('std_profile')) {
						$upload_error = array('error' => $this->upload->display_errors());
						$this->session->set_flashdata('logo_upload_err', print_r($upload_error));
						redirect($_SERVER['HTTP_REFERER']);
					} else {
						$uploaded = $_FILES['std_profile']['name'];
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

				$res = $this->Studentmodel->add_new_std($active, $profile_img);
				if ($res == false) {
					$department = 'Student';
					$action = $this->session->userdata('ims_uname') . "failed creating a student. Access:Student Credentials";
					$this->Studentmodel->log_activity($department, $action);
					$this->session->set_flashdata('new_std_err', 'Error adding student');
					redirect($_SERVER['HTTP_REFERER']);
				} else {
					$department = 'Student';
					$action = $this->session->userdata('ims_uname') . " added student. Access:Add Student";
					$this->Studentmodel->log_activity($department, $action);
					$this->session->set_flashdata('new_std_succ', 'Student added successfully');
					redirect($_SERVER['HTTP_REFERER']);
				}
			}
		}
	}

	public function new_std_email($email, $fname, $lname, $pwd)
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

		$subj = "You are added as a student";
		$bdy = "Hi " . $fname . " " . $lname . "\n\n You are added as student on our website\n\n Please use the following logic credentials:\n\n Username: " . $fname . "\n Password: " . $pwd . "\n\n Click the link below to login in the dashboard.\n" . base_url("student") . "\n\n Best Regards,\n IMS Team\n" . base_url();

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
			$department = 'Student';
			$action = "Not logged in. Access:Student Dashboard";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('student/login');
		}
		if ($this->session->userdata('ims_role') !== "Student") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Student';
			$action = "Unauthorized Access by " . $uname . ". Access:Student Dashboard";
			$this->Studentmodel->log_activity($department, $action);
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
			$department = 'Student';
			$action = "Unauthorized Access by " . $uname . ". Access:My Profile";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('staff/login');
			exit();
		}
		if ($this->session->userdata('ims_role') !== "Student") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Student';
			$action = "Unauthorized Access by " . $uname . ". Access:My Profile";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect('student/dashboard');
			exit();
		}
		$data = $this->Settingsmodel->get_options();
		$data['contacts'] = $this->Adminmodel->contacts();
		$data['stf_contacts'] = $this->Staffmodel->stf_replies();
		$data['std_contacts'] = $this->Studentmodel->std_replies();
		$data['infos'] = $this->Studentmodel->get_current_student_info();
		$data['info_social'] = $this->Studentmodel->get_current_student_info_social();

		$this->load->view('templates/header', $data);
		$this->load->view('student/my_prof', $data);
		$this->load->view('templates/footer');
	}

	public function edit()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Student';
			$action = "Unauthorized Access by " . $uname . ". Access:Edit Profile";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('student/login');
			exit();
		}
		if ($this->session->userdata('ims_role') !== "Student") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Student';
			$action = "Unauthorized Access by " . $uname . ". Access:Edit Profile";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect($_SERVER['HTTP_REFERER']);
			exit();
		}

		$data = $this->Settingsmodel->get_options();
		$data['contacts'] = $this->Adminmodel->contacts();
		$data['stf_contacts'] = $this->Staffmodel->stf_replies();
		$data['std_contacts'] = $this->Studentmodel->std_replies();
		$data['infos'] = $this->Studentmodel->get_current_student_info();
		$data['info_social'] = $this->Studentmodel->get_current_student_info_social();

		$this->load->view('templates/header', $data);
		$this->load->view('student/edit_prof', $data);
		$this->load->view('templates/footer');
	}

	public function save_personal_info()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Student';
			$action = "Unauthorized Access by " . $uname . ". Access:Update my Profile";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('student/login');
			exit();
		}
		if ($this->session->userdata('ims_role') !== "Student") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Student';
			$action = "Unauthorized Access by " . $uname . ". Access:My Profile";
			$this->Studentmodel->log_activity($department, $action);
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

				$config['upload_path'] = './assets/std_uploads';
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
			$res = $this->Studentmodel->save_personal_info($profile_img);
			if ($res !== true) {
				$uname = $this->session->userdata('ims_uname');
				$department = 'Student';
				$action = "Profile Update Failed by " . $uname . ". Access:Update Profile";
				$this->Studentmodel->log_activity($department, $action);
				$this->session->set_flashdata('p_update_err', 'Error updating your Profile');
				redirect('staff/profile');
				exit;
			} else {
				$uname = $this->input->post('uname');
				$department = 'Student';
				$action = "Profile Update Successfull by " . $uname . ". Access:Update Profile";
				$this->Studentmodel->log_activity($department, $action);
				$this->session->set_flashdata('p_update_succ', 'Profile Updated');
				redirect($_SERVER['HTTP_REFERER']);
				exit();
			}
		}
	}

	public function save_contact()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Student';
			$action = "Unauthorized Access by " . $uname . ". Access:Update my Profile";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('student/login');
			exit();
		}
		if ($this->session->userdata('ims_role') !== "Student") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Student';
			$action = "Unauthorized Access by " . $uname . ". Access:My Profile";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect('student/dashboard');
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
			$res = $this->Studentmodel->save_contact();
			if ($res !== true) {
				$uname = $this->session->userdata('ims_uname');
				$department = 'Student';
				$action = "Profile Update Failed by " . $uname . ". Access:Update Profile";
				$this->Studentmodel->log_activity($department, $action);
				$this->session->set_flashdata('p_update_err', 'Error updating your Profile');
				redirect('staff/profile');
				exit;
			} else {
				$uname = $this->input->post('uname');
				$department = 'Student';
				$action = "Profile Update Successfull by " . $uname . ". Access:Update Profile";
				$this->Studentmodel->log_activity($department, $action);
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
			$department = 'Student';
			$action = "Unauthorized Access by " . $uname . ". Access:Update my Password";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect($_SERVER['HTTP_REFERER']);
			exit();
		}
		if ($this->session->userdata('ims_role') !== "Student") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Student';
			$action = "Unauthorized Access by " . $uname . ". Access:Update my Password";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect($_SERVER['HTTP_REFERER']);
		}

		$this->form_validation->set_rules('c_pwd', 'Current Password', 'required|trim');
		$this->form_validation->set_rules('n_pwd', 'New Password', 'required|trim');
		$this->form_validation->set_rules('rtn_pwd', 'Re-type Password', 'required|trim');

		if ($this->form_validation->run() == false) {
			$this->edit();
		} else {
			$pwd_res= $this->Studentmodel->check_pwd();
			if ($pwd_res == false) {
				$uname = $this->session->userdata('ims_uname');
				$department = 'Student';
				$action = "Password Update Failed by " . $uname . ". Access:Update Password";
				$this->Studentmodel->log_activity($department, $action);
				$this->session->set_flashdata('pwd_update_err', 'Incorrect password provided');
				redirect($_SERVER['HTTP_REFERER']);
			} else {
				$uname = $this->input->post('uname');
				$department = 'Student';
				$action = "Password Update Successfull by " . $uname . ". Access:Update Password";
				$this->Studentmodel->log_activity($department, $action);
				$this->session->set_flashdata('pwd_update_succ', 'Password changed');
				redirect($_SERVER['HTTP_REFERER']);
			}
		}
	}

	public function deact_account()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Student';
			$action = "Unauthorized Access by " . $uname . ". Access:De-activate my Profile";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect($_SERVER['HTTP_REFERER']);
			exit();
		}
		if ($this->session->userdata('ims_role') !== "Student") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Student';
			$action = "Unauthorized Access by " . $uname . ". Access:De-activate my Profile";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect($_SERVER['HTTP_REFERER']);
		}
		$act_res= $this->Studentmodel->deact_account();
		if ($act_res == false) {
			$this->session->set_flashdata('deact_account_err', 'Error performing this operation');
		}
	}

	public function save_course()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Student';
			$action = "Unauthorized Access by " . $uname . ". Access:Update my Profile";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('student/login');
			exit();
		}
		if ($this->session->userdata('ims_role') !== "Student") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Student';
			$action = "Unauthorized Access by " . $uname . ". Access:My Profile";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect('student/dashboard');
			exit();
		}

		$this->form_validation->set_rules('course', 'Course', 'trim|html_escape');
		$this->form_validation->set_rules('branch', 'Branch', 'trim|html_escape');
		$this->form_validation->set_rules('started_course_year', 'Year started', 'trim|html_escape');
		$this->form_validation->set_rules('end_course_year', 'End year', 'trim|html_escape');
		$this->form_validation->set_rules('current_course_year', 'Year', 'trim|html_escape');

		if ($this->form_validation->run() == false) {
			$this->edit();
		} else {
			$res = $this->Studentmodel->save_course();
			if ($res !== true) {
				$uname = $this->session->userdata('ims_uname');
				$department = 'Student';
				$action = "Profile Update Failed by " . $uname . ". Access:Update Profile";
				$this->Studentmodel->log_activity($department, $action);
				$this->session->set_flashdata('p_update_err', 'Error updating your Profile');
				redirect('staff/profile');
				exit;
			} else {
				$uname = $this->input->post('uname');
				$department = 'Student';
				$action = "Profile Update Successfull by " . $uname . ". Access:Update Profile";
				$this->Studentmodel->log_activity($department, $action);
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
			$department = 'Student';
			$action = "Unauthorized Access by " . $uname . ". Access:Delete my profile image";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('student/login');
			exit();
		}
		if ($this->session->userdata('ims_role') !== "Student") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Student';
			$action = "Unauthorized Access by " . $uname . ". Access:Delete my profile image";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect('student/dashboard');
			exit();
		}
		$res = $this->Studentmodel->delete_profileimg($_POST['id'], $_POST['gender']);
		if ($res !== true) {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Student';
			$action = "Profile Update Failed by " . $uname . ". Access:Profile image delete";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('p_update_err', 'Failed to delete Profile image');
		} else {
			$uname = $this->input->post('uname');
			$department = 'Student';
			$action = "Profile Update Successfull by " . $uname . ". Access:Profile image deleted";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('p_update_succ', 'Profile image deleted');
		}
	}

	public function response()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Student';
			$action = "Unauthorized Access by " . $uname . ". Access:Admin Response";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('student/login');
			exit();
		}
		if ($this->session->userdata('ims_role') !== "Student") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Student';
			$action = "Unauthorized Access by " . $uname . ". Access:Admin Response";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect($_SERVER['HTTP_REFERER']);
			exit();
		}
		$this->form_validation->set_rules('reply', 'Reply', 'required|trim|html_escape');
		if ($this->form_validation->run() == FALSE) {
			$data = $this->Settingsmodel->get_options();
			$data['contacts'] = $this->Adminmodel->contacts();
			$data['stf_contacts'] = $this->Staffmodel->stf_replies();
			$data['user_std_contacts'] = $this->Studentmodel->user_std_replies();
			$data['std_contacts'] = $this->Studentmodel->std_replies();
			$this->load->view('templates/header', $data);
			$this->load->view('student/response', $data);
			$this->load->view('templates/footer');
		} else {
			$res = $this->Studentmodel->save_reply();
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
			$department = 'Student';
			$action = "Unauthorized Access by " . $uname . ". Access:Show Requests";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
		}
		if ($this->session->userdata('ims_role') == "1" || $this->session->userdata('ims_role') == "Staff" || $this->session->userdata('ims_role') == "0") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Student';
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
			$department = 'Student';
			$action = "Not logged in. Access:Delete Response";
			$this->Studentmodel->log_activity($department, $action);
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
		}
		if ($this->session->userdata('ims_role') !== "Student") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Student';
			$action = "Unauthorized Access by " . $uname . ". Access:Delete Response";
			$this->Studentmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
		}
		$del_res = $this->Studentmodel->delete_response($_POST['id']);
		if ($del_res !== true) {
			$this->session->set_flashdata('request_del_err', 'Error deleting message');
		} else {
			$this->session->set_flashdata('request_del_succ', 'Message deleted!');
		}
	}
}
