<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Settings extends CI_Controller
{
	public function index()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$department = 'Admin';
			$action = "Not logged in. Access:Settings";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('admin/login');
		}
		if ($this->session->userdata('ims_role') !== "1") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Settings';
			$action = "Unauthorized Access by " . $uname . ". Access:Settings";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect($_SERVER['HTTP_REFERER']);
		}
		$data = $this->Settingsmodel->get_options();
		$data['logs'] = $this->Settingsmodel->activity_logs();
		$data['contacts'] = $this->Adminmodel->contacts();
		$this->load->view('templates/header', $data);
		// die(print_r($data));
		$this->load->view('admin/settings', $data);
		$this->load->view('templates/footer');
	}

	public function save_options()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$department = 'Admin';
			$action = "Not logged in. Access:Settings";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('admin/login');
		}
		if ($this->session->userdata('ims_role') === "Staff" || $this->session->userdata('ims_role') === "Student" || $this->session->userdata('ims_role') === "0") {
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect($_SERVER['HTTP_REFERER']);
			exit();
		}

		$this->form_validation->set_rules('webname', 'Webname', 'required|trim|html_escape');
		$this->form_validation->set_rules('weblogo', 'Weblogo', 'trim|html_escape');
		$this->form_validation->set_rules('webfavicon', 'Webfavicon', 'trim|html_escape');
		$this->form_validation->set_rules('webemail', 'Webemail', 'required|trim|valid_email|html_escape');
		$this->form_validation->set_rules('webdomain', 'Webdomain', 'required|trim|html_escape');

		if ($this->form_validation->run() == FALSE) {
			$data = $this->Settingsmodel->get_options();
			$data['logs'] = $this->Settingsmodel->activity_logs();
			$data['contacts'] = $this->Adminmodel->contacts();
			$this->load->view('templates/header', $data);
			$this->load->view('admin/settings', $data);
			$this->load->view('templates/footer');
		} else {
			if ($_FILES['weblogo']['name']) {
				$config['upload_path'] = './assets/options';
				$config['allowed_types'] = 'jpg|jpeg|png|';
				$config['max_size'] = '2048';
				$config['max_height'] = '3000';
				$config['max_width'] = '3000';
				$config['file_name'] = 'logo';
				$this->load->library('upload', $config);

				if (!$this->upload->do_upload('weblogo')) {
					$upload_error = array('error' => $this->upload->display_errors());
					// print_r($upload_error);
					$this->session->set_flashdata('logo_upload_err', print_r($upload_error));
					redirect($_SERVER['HTTP_REFERER']);
				} else {
					$logo_uploaded = $_FILES['weblogo']['name'];
					$logo_ext = htmlentities(strtolower(pathinfo($logo_uploaded, PATHINFO_EXTENSION)));
					$data = array('upload_data' => $this->upload->data());
					$logo = "logo." . $logo_ext;
				}
			}

			if ($_FILES['webfavicon']['name']) {
				$config['upload_path'] = './assets/options';
				$config['allowed_types'] = 'jpg|jpeg|png|';
				$config['max_size'] = '2048';
				$config['max_height'] = '3000';
				$config['max_width'] = '3000';
				$config['file_name'] = 'logo1';
				$this->load->library('upload', $config);

				if (!$this->upload->do_upload('webfavicon')) {
					$upload_error = array('error' => $this->upload->display_errors());
					// print_r($upload_error);
					$this->session->set_flashdata('logo_upload_err', print_r($upload_error));
					redirect($_SERVER['HTTP_REFERER']);
				} else {
					$favicon_uploaded = $_FILES['webfavicon']['name'];
					$favicon_ext = htmlentities(strtolower(pathinfo($favicon_uploaded, PATHINFO_EXTENSION)));
					$data = array('upload_data' => $this->upload->data());
					$favicon = "logo1." . $favicon_ext;
				}
			}

			$res = $this->Settingsmodel->save_options($logo, $favicon);
			if ($res == false) {
				$this->session->set_flashdata('formerr', 'Error updating');
				redirect($_SERVER['HTTP_REFERER']);
			} else {
				$this->session->set_flashdata('formsucc', 'Settings Updated!');
				redirect($_SERVER['HTTP_REFERER']);
			}
		}
	}

	public function delete_logo_option()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$department = 'Admin';
			$action = "Not logged in. Access:Settings";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('admin/login');
		}
		if ($this->session->userdata('ims_role') !== "1") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Admin/Settings';
			$action = "Unauthorized Access by " . $uname . ". Access:Delete Logo";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect($_SERVER['HTTP_REFERER']);
			exit();
		}
		$this->Settingsmodel->delete_logo_option($_POST['id']);
		$uname = $this->session->userdata('ims_uname');
		$department = 'Admin';
		$action = "WebLogo Deleted by " . $uname;
		$this->Adminmodel->log_activity($department, $action);
		$this->session->set_flashdata('logodel', 'Logo Deleted!');
		return true;
	}

	public function delete_favicon_option()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$department = 'Admin';
			$action = "Not logged in. Access:Settings";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('admin/login');
		}
		if ($this->session->userdata('ims_role') !== "1") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Admin/Settings';
			$action = "Unauthorized Access by " . $uname . ". Access:Delete Favicon";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect($_SERVER['HTTP_REFERER']);
			exit();
		}
		$this->Settingsmodel->delete_favicon_option($_POST['id']);
		$uname = $this->session->userdata('ims_uname');
		$department = 'Admin';
		$action = "WebFavicon Deleted by " . $uname;
		$this->Adminmodel->log_activity($department, $action);
		$this->session->set_flashdata('icondel', 'Favicon Deleted!');
		return true;
	}

	public function change_pwd()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$department = 'Admin';
			$action = "Not logged in. Access:Settings";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('admin/login');
		}
		if ($this->session->userdata('ims_role') !== "1") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Admin/Settings';
			$action = "Unauthorized Access by " . $uname . ". Access:Change Password";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect($_SERVER['HTTP_REFERER']);
			exit();
		}
		$this->form_validation->set_rules('opwd', 'Old Password', 'required|trim');
		$this->form_validation->set_rules('npwd', 'New Password', 'required|trim');
		$this->form_validation->set_rules('rpwd', 'Re-type Password', 'required|trim|matches[npwd]');
		if ($this->form_validation->run() == FALSE) {
			$data = $this->Settingsmodel->get_options();
			$data['contacts'] = $this->Adminmodel->contacts();
			$this->load->view('templates/header', $data);
			$this->load->view('admin/settings', $data);
			$this->load->view('templates/footer');
		} else {
			$res = $this->Settingsmodel->change_pwd();
			if ($res == false) {
				$uname = $this->session->userdata('ims_uname');
				$department = 'Admin';
				$action = "Invalid Credentials by " . $uname . ". Access:Change Password";
				$this->Adminmodel->log_activity($department, $action);
				$this->session->set_flashdata('invpwdchange', 'Invalid Password');
				redirect($_SERVER['HTTP_REFERER']);
				exit();
			} elseif ($res == true) {
				$uname = $this->session->userdata('ims_uname');
				$department = 'Admin';
				$action = "Password changed by " . $uname;
				$this->Adminmodel->log_activity($department, $action);
				$this->session->set_flashdata('succpwdchange', 'Password Updated');
				redirect($_SERVER['HTTP_REFERER']);
				exit();
			}
		}
	}

	public function filter_param()
	{
		$output = "";
		$data = $this->Settingsmodel->filter_param($_POST['param'], $_POST['type']);
		$output .= '<table class="table table-bordered table-light table-hover">
		<tr class="font-weight-bolder text-light" style="background-color: #00695C">
			<th>
				<div class="no">
					<i class="fas fa-arrows-alt-v" name="id" type="asc"></i>
					<span>No.</span>
				</div>
			</th>
			<th>
				<div class="dept">
					<i class="fas fa-arrows-alt-v" name="department" type="asc"></i>
					<span>Department</span>
				</div>
			</th>
			<th>
				<div class="ac">
					<i class="fas fa-arrows-alt-v" name="action" type="asc"></i>
					<span>Action</span>
				</div>
			</th>
			<th>
				<div class="time">
					<i class="fas fa-arrows-alt-v" name="created_at" type="asc"></i>
					<span>Time</span>
				</div>
			</th>
		</tr>';
		foreach ($data->result() as $log) {
			$output .= '<tr class="text-dark">
								<td class="font-weight-bolder">' . $log->id . '</td>
								<td class="font-weight-bolder">' . $log->department . '</td>
								<td class="font-weight-bolder">' . $log->action . '</td>
								<td class="font-weight-bolder text-danger">' . $log->created_at . '</td>
							</tr>
							</table>';
		}
		echo $output;
	}

	public function activity_logs_export_csv()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$department = 'Admin';
			$action = "Not logged in. Access:Settings";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('admin/login');
		}
		if ($this->session->userdata('ims_role') !== "1") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Admin/Settings';
			$action = "Unauthorized Access by " . $uname . ". Access:Export Activity Log";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect($_SERVER['HTTP_REFERER']);
			exit();
		}
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=activity_log.csv');
		$output = fopen("php://output", "w");
		fputcsv($output, array('ID', 'Department', 'Action', 'Time'));
		$logs = $this->Settingsmodel->activity_logs();
		foreach ($logs as $log) {
			fputcsv($output, $log);
		}
		fclose($output);
		$fname = $this->session->userdata('ims_fname');
		$lname = $this->session->userdata('ims_lname');
		$department = 'Admin';
		$action = $fname . " " . $lname . " " . "exported 'Activity Log.csv'";
		$this->Adminmodel->log_activity($department, $action);
		return TRUE;
		exit();
	}

	public function delete_activity_logs()
	{
		if (!$this->session->userdata('ims_logged_in')) {
			$department = 'Admin';
			$action = "Not logged in. Access:Settings";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('admin/login');
		}
		if ($this->session->userdata('ims_role') !== "1") {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Admin/Settings';
			$action = "Unauthorized Access by " . $uname . ". Access:Delete Activity Log";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('acc_denied', 'Access Denied');
			redirect($_SERVER['HTTP_REFERER']);
		}
		$del_res = $this->Settingsmodel->delete_activity_logs();
		if ($del_res == false) {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Admin';
			$action = "Invalid Credentials by " . $uname . ". Access:Delete Activity Log";
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('activity_log_del_err', 'Failed to Delete');
		} elseif ($del_res == true) {
			$uname = $this->session->userdata('ims_uname');
			$department = 'Admin';
			$action = "Activity Log Deleted by " . $uname;
			$this->Adminmodel->log_activity($department, $action);
			$this->session->set_flashdata('activity_log_del_succ', 'Logs Deleted');
		}
	}
}
