<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Roles extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public $page = array ( "pagetitle" => "User Management - Fruits Consulting Inc",
							"nav" => 'template/usermgmtnav',
							"template" => 'template/green',
							"menu" => 'template/setupmenu'); 							
	function __construct()
	{
		 parent::__construct();
		 $this->auth->restrict();
		 $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
		$config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
		$config['next_link'] = 'Next &rarr;';
        $config['next_tag_open'] = '<li class="next">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '&larr; Previous';
        $config['prev_tag_open'] = '<li class="previous">';
        $config['prev_tag_close'] = '</li>';
		$this->pagination->initialize($config);
	}
	public function index()
	{
		$page = $this->page;
		if($this->auth->perms("User.Roles",$this->auth->user_id(),2) == TRUE){
			if($_POST){
				$this->input->post(NULL, TRUE);
				$this->form_validation->set_rules("role", "Roles", "trim|required|xss_clean");
				$this->form_validation->set_rules("description", "Description", "trim|required|xss_clean");
				if($this->form_validation->run() == FALSE){
					echo "error";
				}else{
					echo "add role na";
					$role = $this->UserMgmt->add_role($_POST);
					if($role == true)
					echo  "New Role was added";
					else
					echo "wala nagsulod";
				}
			}			
			$page['main']="user/roles";			
		}else{
			$page['main']="user/noperm";
		}		
		$this->load->view($page['template'], $page);
	}
	
	public function edit(){
		$page = $this->page;
		if ($this->auth->perms("User.Roles",$this->auth->user_id(),3) == true){
		$page['groupid'] = $this->uri->segment(4);
		if($_POST){
			if($_POST['Submit']=="Edit Role"){
				$this->input->post(NULL, TRUE);
				$this->form_validation->set_rules("role", "Roles", "trim|required|xss_clean");
				$this->form_validation->set_rules("description", "Description", "trim|required|xss_clean");
				if($this->form_validation->run() == FALSE){
					echo "error";
				}else{
					//echo "add role na";
					$data = array("name" => $_POST['role'],
								"description" => $_POST['description'],							
								"date_modified" => date("Y-m-d h:i:s"),
								"last_modified_by" => 0,
								"user_ip" => $this->input->ip_address(),
								"active" => 1);
					$role = $this->UserMgmt->edit_role($data, $page['groupid']);
					if($role == true)
					echo  "Role was updated";
					else
					echo "wala nagsulod";
				}
			}elseif($_POST['Submit'] == "Update Rights"){
				$this->update_rights();
			}
		}		
		$page['groupid'] = $this->uri->segment(4);
		$page['main']="user/roles_details";		
		}else{
			$page['main']="user/noperm";
		}
		$this->load->view($page['template'], $page);
	}
	
	function update_rights(){
		$groupid = $this->input->post('groupid');
		foreach($_POST['rights'] as $module => $value)
		{
			if($this->UserMgmt->get_group_rights($groupid,$module)->num_rows() > 0){
				//update rights
				//echo "update";
				foreach ($value as $ri => $perm){
					$active = array("active"=>$perm,
								"date_modified"=>date("Y-m-d h:i:s"),
								"last_modified_by"=>1);
					$where = array("group_id"=>$groupid,
								"module_id"=>$module,
								"module_right"=>$ri);
					$this->UserMgmt->groupright_update($where, $active);
				}
				
			}else{
				//add_rights
				foreach ($value as $ri => $perm){
					$data = array("group_id"=>$groupid,
								"module_id"=>$module,
								"module_right"=>$ri,
								"active"=>$perm,
								"date_created"=> date("Y-m-d h:i:s"),
								"created_by"=> 1,
								"user_ip"=>$this->input->ip_address());
					$this->UserMgmt->add_rights($data,"group");
					
				}
			}
		}			
			
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */