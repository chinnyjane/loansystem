<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users {

	function __construct(){
		$this->ci =& get_instance();
		$this->ip_address = $this->ci->input->ip_address();
		if (!class_exists('CI_Session'))
		{
			$this->ci->load->library('session');
		}
		if (!class_exists('UserMgmt'))
		{
			$this->ci->load->model('usermgmt', 'usermgmt', TRUE);
		}
	}

	function userBranchrights(){
		
	}
	
	function
} 
?>