<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Module extends CI_Model {

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	//ADD Module
	public function add()
	{
		$data1 = array("module_name" => $_POST['module'],
						"description" => $_POST['description'],
						"module_link" => $_POST['link'],
						"date_created" => $this->auth->localtime(),
						"date_modified" => $this->auth->localtime(),
						"created_by" => '0',
						"last_modified_by" => '0',
						"user_ip" => $this->input->ip_address(),
						"hash" => md5($_POST['module']),
						"active" => '1'
						 );
		$this->db->insert('modules', $data1);
		if ($this->db->insert_id())
		return true;
	}
	
	public function get($data){
		$this->db->where($data);				
		return $this->db->get('modules');
	}
	
	function getheadermodulelink($mod){
		$this->db->select('module_link');
		$this->db->from('modules');
		$this->db->where('module_name', 'Header.'.$mod);
		return $this->db->get();
	}
}
?>