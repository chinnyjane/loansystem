<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Settings {
	function __construct(){
		$this->ci =& get_instance();
		$this->ip_address = $this->ci->input->ip_address();		
	}

	function sidemenu($module){
		$menu = array("module_name like"=> $module.".%",
				"active"=>1);
		$modules = $this->ci->UserMgmt->getmenu($menu);
		if($modules->num_rows() > 0){
		$exsub='';
			foreach ($modules->result() as $mod){
				if($this->ci->auth->perms($mod->module_name,$this->ci->auth->user_id(),2) == TRUE){
					list($mainmod, $submod) = explode(".", $mod->module_name);
					if($submod != $exsub)
					echo '<li><a href="'.base_url().$mod->module_link.'">'.$submod.'</a></li>';
					$exsub = $submod;
				}
			}
		}
	}
	
	function submenu($module,$act){
		$menu = array("module_name like"=> $module."%",
				"module_name not like"=>$module.".Profile%",
				"active"=>1);
		$modules = $this->ci->UserMgmt->getmenu($menu);
		if($modules->num_rows() > 0){
		//$exp=array();
		//$link = strtolower(str_replace(".","/",$module));
		//echo '<li class="active"><a href="'.base_url().$link.'">'.$module.'</a></li>';
			foreach ($modules->result() as $mod){
			$modulename = str_replace($module.".","",$mod->module_name);
			$link = $mod->module_link;
			//echo $act."=".$mod->module_name;
				if($mod->module_name == $act)
					$active = "class='active'";
				else $active = '';
				echo '<li '.$active.'><a href="'.base_url().$link.'">'.$modulename.'</a></li>';
			}
		}
	}
	
	function subsubmenu($module,$id,$sub){
		$menu = array("module_name like"=> $module."%",
					"active"=>1);
		$modules = $this->ci->UserMgmt->getmenu($menu);
		if($modules->num_rows() > 0){
		//$exp=array();
		//$link = strtolower(str_replace(".","/",$module));
		//echo '<li class="active"><a href="'.base_url().$link.'">'.$module.'</a></li>';
			foreach ($modules->result() as $mod){
			$modulename = str_replace($module.".","",$mod->module_name);
			$link = $mod->module_link;
				if($mod->module_name == $sub)
					$active = "class='active'";
				else $active = '';
				echo '<li '.$active.'><a href="'.base_url().$link."/".$id.'">'.$modulename.'</a></li>';
			}
		}
	}
	
	function action_user(){
		$action = $_POST['submit'];
		if($action == "Delete"){
				//DELETE USER from USER TABLE
				$users = $this->ci->input->post('user');
				if(count($users) > 0){
					foreach ($users as $u){
						$data = array('deleted'=>1,
									'active'=>0);
						 $this->ci->UserMgmt->user_status($data,$u);						
					}
				}
		}elseif($action == "Activate"){
			//Activate USER from USER TABLE
			$users = $this->ci->input->post('user');
			if(isset($_POST['user'])){
					foreach ($users as $u){
						$data = array('deleted'=>0,
									'active'=>1);
						 $this->ci->UserMgmt->user_status($data,$u);						
					}
				}
			
		}elseif($action == "Deactivate"){
			//deactivate USER from USER TABLE
			$users = $this->ci->input->post('user');
				if(isset($_POST['user'])){
					foreach ($users as $u){
						$data = array('deleted'=>0,
									'active'=>0);
						 $this->ci->UserMgmt->user_status($data,$u);						
					}
				}
		
		}elseif($action == "Permanent Delete"){
			//Permanently Delete User from DB
			$users = $this->ci->input->post('user');
				if(isset($_POST['user'])){
					foreach ($users as $u){
						 $this->UserMgmt->delete_user($u);						
					}
				}
		}elseif($action == "Add User"){
		//echo "<pre>";
		//print_r($_POST);
		//echo "</pre>";
			//ADD USER
			if($_POST){
				$this->ci->input->post(NULL, TRUE);
				$this->ci->form_validation->set_rules("firstname", "First Name", "trim|required|xss_clean");
				$this->ci->form_validation->set_rules("lastname", "Last Name", "trim|required|xss_clean");
				$this->ci->form_validation->set_rules("email", "Email", "required|valid_email|is_unique[user.email]");
				$this->ci->form_validation->set_rules("username", "Username", "required|is_unique[user.username]|xss_clean");
				$this->ci->form_validation->set_rules("contact", "Contact Number", "required|trim|xss_clean");
				$this->ci->form_validation->set_rules("password", "Password", "required|matches[confirmpassword]");
				$this->ci->form_validation->set_rules("confirmpassword", "Confirm Password", "required");
				$this->ci->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
				if($this->ci->form_validation->run() == FALSE){
					$page = "Error was encountered.";
				}else{
					$userid = $this->ci->UserMgmt->add_user($_POST);
					if($userid)
					$page = "New User was added.";
					else $page = "User was not added.";
					
					//ADD Rights based on role
					$module = $this->ci->UserMgmt->get_module();
					if($module->num_rows() > 0){
						foreach ($module->result() as $mod){
							$rights = $this->ci->UserMgmt->get_group_rights($_POST['group'],$mod->id);
							if($rights->num_rows() > 0){
								foreach($rights->result() as $r){
									$data = array("user_id"=>$userid,
														"module_id"=>$r->module_id,
														"module_right"=>$r->module_right,
														"group_id"=>$r->group_id,
														"active"=>$r->active,
														"date_created"=> date("Y-m-d h:i:s"),
														"created_by"=> 1,
														"user_ip"=>$this->ci->input->ip_address());
									$this->ci->UserMgmt->add_rights($data,"user");
								}
							}
						}
					}				
				}
				return $page;
			}
		}
	}
	
	function addholiday(){
		if($_POST['submit'] == "Add Holiday"){
			//echo "process in";
			$this->ci->form_validation->set_rules("Date", "Date", "required|callback_holidayexist");
			$this->ci->form_validation->set_rules("holiday", "Holiday", "required|trim|xss_clean");
			$this->ci->form_validation->set_rules("branch", "Holiday", "required|trim|xss_clean");
			$this->ci->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
			if($this->ci->form_validation->run() != false){
				//echo "process";
				$data = array("holiday"=>$_POST['holiday'],
							"dateOfHoliday"=> $_POST['Date'],
							"branchID"=>$_POST['branch'],
							"addedBy"=>$this->ci->auth->user_id(),
							"dateAdded"=>$this->ci->auth->localtime(),
							"active"=>1);
				if($this->ci->UserMgmt->insert_data_to("holidays", $data) != false)
				return true;
				else return false;
				reset($this->ci->form_validation->_field_data); 
			}
		}else return false;
	}
	
	function holidayexist(){
		$data = array("dateOfHoliday"=> $_POST['Date'],
					"branchID"=>$_POST['branch']);
		if($this->ci->Loansmodel->get_data_from('holidays', $data)->num_rows() > 0)
		{
			$this->ci->form_validation->set_message("holidayexist", "Holiday Dates Already Exists.");
			return false;
		}else return true;
	}
	
	function CompanyName(){
		$sql = "select * from company";
		$q = $this->ci->db->query($sql);
		return $q;
	}
} 
?>