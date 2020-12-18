<?php

class Settingsmodel extends CI_Model
{
	public function log_activity($department, $action)
	{
		$data = array(
			'department' => $department,
			'action' => $action,
		);
		$this->db->insert('activity_log', $data);
		return true;
	}

	public function get_options()
	{
		$data['webname'] = $this->db->get_where('options', array('id' => 1))->row();
		$data['weblogo'] = $this->db->get_where('options', array('id' => 2))->row();
		$data['webemail'] = $this->db->get_where('options', array('id' => 3))->row();
		$data['webdomain'] = $this->db->get_where('options', array('id' => 4))->row();
		$data['webfavicon'] = $this->db->get_where('options', array('id' => 5))->row();
		$data['webaa'] = $this->db->get_where('options', array('id' => 6))->row();
		return $data;
	}

	public function save_options($logo, $favicon)
	{
		$this->db->set('value', htmlentities($this->input->post('webname')));
		$this->db->where('id', '1', FALSE);
		$this->db->update('options');
		$uname = $this->session->userdata('ims_uname');
		$department = 'Admin';
		$action = "New webname uploaded by " . $uname . ". Changed to " . $this->input->post('webname');
		$this->log_activity($department, $action);

		$this->db->set('value', htmlentities($this->input->post('webemail')));
		$this->db->where('id', '3', FALSE);
		$this->db->update('options');
		$uname = $this->session->userdata('ims_uname');
		$department = 'Admin';
		$action = "New webemail uploaded by " . $uname . ". Changed to " . $this->input->post('webemail');
		$this->log_activity($department, $action);

		$this->db->set('value', htmlentities($this->input->post('webdomain')));
		$this->db->where('id', '4', FALSE);
		$this->db->update('options');
		$uname = $this->session->userdata('ims_uname');
		$department = 'Admin';
		$action = "New webdomain uploaded by " . $uname . ". Changed to " . $this->input->post('webdomain');
		$this->log_activity($department, $action);

		$this->db->set('value', $logo);
		$this->db->where('id', '2', FALSE);
		$this->db->update('options');
		$uname = $this->session->userdata('ims_uname');
		$department = 'Admin';
		$action = "New weblogo uploaded by " . $uname . ". Changed to " . $this->input->post('weblogo');
		$this->log_activity($department, $action);

		$this->db->set('value', $favicon);
		$this->db->where('id', '5', FALSE);
		$this->db->update('options');
		$uname = $this->session->userdata('ims_uname');
		$department = 'Admin';
		$action = "New webfavicon uploaded by " . $uname . ". Changed to " . $this->input->post('webfavicon');
		$this->log_activity($department, $action);

		return true;
	}

	public function delete_logo_option($id)
	{
		$this->db->set('value', '');
		$this->db->where('id', $id, FALSE);
		$this->db->update('options');
		return true;
	}

	public function delete_favicon_option($id)
	{
		$this->db->set('value', '');
		$this->db->where('id', $id, FALSE);
		$this->db->update('options');
		return true;
	}

	public function get_sadmin_hashpwd()
	{
		$id = $this->session->userdata('ims_id');
		$active = $this->session->userdata('ims_active');
		$role = $this->session->userdata('ims_role');
		$query = $this->db->get_where('admin', array('id' => $id, 'active' => $active, 'role' => $role))->row();
		return $query;
	}

	public function change_pwd()
	{
		$dbhaspwd = $this->get_sadmin_hashpwd();
		if (!$dbhaspwd) {
			return false;
			exit();
		} else {
			$opwverify = password_verify($this->input->post('opwd'), $dbhaspwd->password);
			if ($opwverify == 0) {
				return false;
				exit();
			} elseif ($opwverify == 1) {
				$npwdhash = password_hash($this->input->post('npwd'), PASSWORD_DEFAULT);
				$this->db->set('password', $npwdhash);
				$this->db->where('id', $dbhaspwd->id, FALSE);
				$this->db->update('admin');
				return true;
			}
		}
	}

	public function activity_logs()
	{
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get('activity_log');
		return $query;
	}

	public function filter_param($param, $type)
	{
		$this->db->order_by($param, $type);
		$query = $this->db->get('activity_log');
		return $query;
	}

	public function delete_activity_logs()
	{
		$this->db->truncate('activity_log');
		return true;
	}
}
