<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template extends CI_Controller {
	public function index(){
		$this->load->view("template/template2.php");
	}
	
	public function finaltemp(){
		$this->load->view("template/finaltemplate");
	}
}