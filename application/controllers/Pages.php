<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends CI_Controller{
	public function index(){
		$data= $this->Settingsmodel->get_options();
		$data['contacts']= $this->Adminmodel->contacts();
		$data['adm_contacts']= $this->Adminmodel->adm_replies();
		$data['stf_contacts']= $this->Staffmodel->stf_replies();
		$this->load->view('templates/header',$data);
		$this->load->view('pages/index');
		$this->load->view('templates/footer');
	}

	public function support(){
		$data= $this->Settingsmodel->get_options();
		$data['contacts']= $this->Adminmodel->contacts();
		$data['adm_contacts']= $this->Adminmodel->adm_replies();
		$data['stf_contacts']= $this->Staffmodel->stf_replies();
		$this->load->view('templates/header',$data);
		$this->load->view('pages/support');
		$this->load->view('templates/footer');
	}
}