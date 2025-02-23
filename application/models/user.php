<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UserMgmt extends CI_Model {

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	//ADD Module
	public function add_module()
	{
		$data = array(array("module_name" => $_POST['module'].".Create",
						"description" => "Allow users to Create ".$_POST['description'],
						"date_created" => date("Y-m-d h:i:s"),
						"date_modified" => date("Y-m-d h:i:s"),
						"created_by" => '0',
						"last_modified_by" => '0',
						"user_ip" => $this->input->ip_address(),
						"hash" => md5($_POST['module']),
						"active" => '1'
						 ),
					array("module_name" => $_POST['module'].".View",
						"description" => "Allow users to View ".$_POST['description'],
						"date_created" => date("Y-m-d h:i:s"),
						"date_modified" => date("Y-m-d h:i:s"),
						"created_by" => '0',
						"last_modified_by" => '0',
						"user_ip" => $this->input->ip_address(),
						"hash" => md5($_POST['module']),
						"active" => '1'
						),
					array("module_name" => $_POST['module'].".Manage",
						"description" => "Allow users to Manage ".$_POST['description'],
						"date_created" => date("Y-m-d h:i:s"),
						"date_modified" => date("Y-m-d h:i:s"),
						"created_by" => '0',
						"last_modified_by" => '0',
						"user_ip" => $this->input->ip_address(),
						"hash" => md5($_POST['module']),
						"active" => '1'
						),
					array("module_name" => $_POST['module'].".Delete",
						"description" => "Allow users to Delete ".$_POST['description'],
						"date_created" => date("Y-m-d h:i:s"),
						"date_modified" => date("Y-m-d h:i:s"),
						"created_by" => '0',
						"last_modified_by" => '0',
						"user_ip" => $this->input->ip_address(),
						"hash" => md5($_POST['module']),
						"active" => '1'
						)
					 );
		$str = $this->db->insert_batch('modules', $data);
		if ($this->db->insert_id())
		return true;
	}
	
	public function get_module(){
		$ql = "select * from modules";
		$res = $this->db->query($ql);
		return $res;
	}
	
	public function module_action($action){
		
	}
	
	public function add_branch()
	{
		
	}
	
	public function get_branches()
	{
		$sql ="select * from branches where active = 1 order by id ASC ";
		$res = $this->db->query($sql);
		return $res;
	}
	
	public function add_user($post)
	{
		$data = array("firstname" => $post['firstname'],
						"lastname" => $post['lastname'],
						"email" => $post['email'],
						"password" => md5($post['password']),
						"branch_id" => $post['branch'],
						"date_created" => date("Y-m-d h:i:s"),
						"date_modified" => date("Y-m-d h:i:s"));
		$user_id = $this->db->insert("user", $data);
		if($user_id) return true;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */