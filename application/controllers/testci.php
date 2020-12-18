<?php
class testci extends CI_Controller
{
	public function index()
	{
		return true;
	}

	public funtion testfun_name(){
		$this->db->get_where('admin', array('id'=>$this->session->userdata('ims_id')));
		$this->db->get('admin');
	}
}
