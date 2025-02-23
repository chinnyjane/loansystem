<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth {

	private $logged_in = NULL;
	
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
	
	public function holiday($date){
		$data = array("dateOfHoliday"=> $date,
					"branchID"=>0,
					"active"=>1);
		if($this->ci->Loansmodel->get_data_from('holidays', $data)->num_rows() >0)
		return true;
		else return false;
	}
	
	public function login($username, $password){
	
		if(empty($username) || empty($password)){
			return FALSE;
		}			
		
		$user = $this->ci->UserMgmt->login($username,$password);
		
		if($user  === FALSE or $user->num_rows() <= 0)
		return FALSE;
		else{
			foreach($user->result() as $u){
				
				$user_id = $u->id;
				$email = $u->email;
				$pass = $u->password;
				$lastname = $u->lastname;
				$firstname = $u->firstname;
				$role_id = $u->group_id;
				$branch_id = $u->branch_id;
				
			}
			$com = $this->ci->settings->CompanyName();
			$comp = $com->row();
			//setup session						
			$data = array(
			'user_id'		=> $user_id,
			'auth_custom'	=> $email,
			'user_token'	=> do_hash($user_id . $pass),
			'lastname'		=> $lastname,
			'firstname' => $firstname,
			'role_id'		=> $role_id,
			'branch_id' => $branch_id,
			'company'=>$comp->company_name,
			'company_ab'=>$comp->abbr,
			'logged_in'		=> TRUE,						'allbranch' => $this->perms("CMC ALL Branches",$user_id,3)
			);
		
		if($this->ci->session->set_userdata($data))
		echo "ok session";
			
			//update login date and IP
			$this->ci->UserMgmt->lastlogin($user_id);
				
			//update activity
			$this->ci->UserMgmt->new_activity("logged in",$user_id,"1");
			return TRUE;
		}
	}
	
	public function set_session($user_id, $email,$pass,$lastname,$firstname,$role_id,$branch_id){
	
		$data = array(
			'user_id'		=> $user_id,
			'auth_custom'	=> $email,
			'user_token'	=> do_hash($user_id . $pass),
			'lastname'		=> $lastname,
			'firstname' => $firstname,
			'role_id'		=> $role_id,
			'branch_id' => $branch_id,
			'logged_in'		=> TRUE,
		);
		
		$this->ci->session->set_userdata($data);
	}
	
	public function change_password($password, $email){
	$post = array("newpassword" => $password,
							"email" => $email);
	return $this->ci->UserMgmt->reset_password($post);
	}
	
	public function check_perm($mod,$userid,$right){
		$data = array("user_id"=>$userid,
							"module_right"=>$right,
							"module_id"=>$mod);
		return $this->ci->UserMgmt->permission($data);
	}
	
	public function restrict(){
		//$this->is_loggedin();
		if($this->is_loggedin() === FALSE)
		redirect(base_url());
	}
	public function localtime(){
		date_default_timezone_set("Asia/Manila");
		return date("Y-m-d H:i:s");
	}
	
	public function mtime(){
		date_default_timezone_set("Asia/Manila");
		return date("h:i A");
	}

	public function localdate(){
		date_default_timezone_set("Asia/Manila");
		return date("Y-m-d");
	}	
	public function is_loggedin()
	{
		//echo "check ta";
		// If we've already checked this session,
		// return that.
		if (!is_null($this->logged_in))
		{
			//echo "logged in";
			return $this->logged_in;
		}//else echo "wala";

		if (!class_exists('CI_Session'))
		{
			$this->ci->load->library('session');
		}
			
		if($this->ci->session->userdata("user_id")&&$this->ci->session->userdata('user_token')){
			//check password if matches
			$user = $this->ci->UserMgmt->get_user_byid($this->ci->session->userdata("user_id"));
			if($user->num_rows() >0){
			//echo "may ara";
				foreach($user->result() as $u)
				{
					if (!function_exists('do_hash'))
					{
						$this->ci->load->helper('security');
					}
					if (do_hash($this->ci->session->userdata('user_id') . $u->password) === $this->ci->session->userdata('user_token'))
					{
						$this->logged_in = TRUE;
						return TRUE;
					}else{
					//echo "wala gd";
						return FALSE;
						}
				}
			}else{
				//echo "wala pa";
				return FALSE;
				}
		}else return FALSE;
	}
	
	public function logout()
	{
		$data = array(
			'user_id'	=> $this->user_id(),
			'role_id'	=> $this->role_id()
		);
		
		// Destroy the session
		$this->ci->session->sess_destroy();

	}//end logout()
	
	public function user_id(){
		return (int) $this->ci->session->userdata("user_id");
	}
	
	public function company_name(){
		return  $this->ci->session->userdata("company");
	}
	
	public function role_id(){
		return (int) $this->ci->session->userdata("role_id");
	}
	
	public function branch_id(){
		return (int) $this->ci->session->userdata("branch_id");
	}
	public function allbranch(){		return (int) $this->ci->session->userdata("allbranch");	}
	public function branchname(){
		$where = array("id"=>$this->branch_id());
		$branch = $this->ci->Loansmodel->get_data_from('branches', $where); 
		$br = $branch->row(); 
		return $br->branchname;
	}	
	
	public function firstname(){
		return $this->ci->session->userdata("firstname");
	}
	
	public function fullname(){
		$fname= $this->ci->session->userdata("firstname");
		$lname= $this->ci->session->userdata("lastname");
		
		return strtoupper($lname.", ".$fname);
	}
	
	public function perms($module,$userid,$right=NULL){
		$this->ci->load->helper('array');
		$mod = $this->ci->UserMgmt->get_right_byname($module);
		if($mod->num_rows() > 0){
			$res = $mod->result();
			
			//$perm= $this->check_perm($res[0]->id,$userid,$right);
			$data = array("user_id"=>$userid,
								"module_right"=>$right,
								"module_id"=>$res[0]->id);
			$per = $this->ci->UserMgmt->permission($data);
			if($per->num_rows() > 0){
				 $perm = $per->row();
				 if($perm->active == 1){				
					 return true;
				 }else{				
				 	return false;
				 }
			}else{		
				return false;
			}
		}else return false;
		
	}
	
	public function updatebranchrights(){	
			$userid = $_POST['userid'];
			foreach ($_POST['branch'] as $bid => $stat){
				//echo "Branch = ".$bid."-".$stat."<br/>";
				$data = array("branch_id"=>$bid,
										"user_id" => $userid);
				$table = "branchrights";
				if($this->ci->UserMgmt->check_branchrights($data) == true){
					//echo "update db";			
					$newdata = array("active" => $stat,
												"approved" => '',
												"approvedBy" => '',
												"date_modified" => $this->localtime(),
												"last_modified_by" => $this->user_id());
					if($this->ci->Loansmodel->update_data($table, $data, $newdata) == true)
					$error[] = false;
					else
					$error[] = true;
				}else{ 
					//echo "add to db";
					$newdata = array("branch_id"=>$bid,
												"user_id" => $userid,
												"active" => $stat,
												"approved" => '',
												"approvedBy" => '',										
												"date_created" => $this->localtime(),
												"created_by" => $this->user_id());
					if($this->ci->UserMgmt->insert_data_to($table,$newdata)== false)
					$error[] = true;
					else
					$error[] = false;
				}
			if(in_array(true, $error)) return false;
			else return true;
		}
	}
	
	public function notify($userid, $moduleid, $notification){
		$data = array("notification" =>$notification,
						"userID" => $userid,
						"moduleID" => $moduleID,
						"dateadded" => $this->localtime(),
						"active" => 1);
		if($this->ci->UserMgmt->insert_data_to('notifications',$data)== false)
		return true;			
			
	}
	
	public function updatemodule($modid){
		$newdata = array();
		$table = "modules";
		$where = array("id"=>$modid);
		$this->ci->form_validation->set_rules('module', 'Module Name', 'trim|required|xss_clean|callback_checkmodule');
		$this->ci->form_validation->set_rules('description', 'Description', 'trim|required|xss_clean');
		$this->ci->form_validation->set_rules('link', 'Module Link', 'trim|required|xss_clean');
		if($this->ci->form_validation->run() != FALSE){
			$newdata = array("module_name"=> $_POST['module'],
								"module_link" => $_POST['link'],
								"description" => $_POST['description']);
			if($this->ci->Loansmodel->update_data($table, $where, $newdata) == true)
				return true;
			else
				return false;
		}else return false;
		
	}
	
	function checkmodule(){
		$where = array("id !=" => $_POST['moduleid'],
								"module_name" => $_POST['module']);
		$table = "modules";
		if($this->ci->Loansmodel->get_data_from($table, $where)->num_rows() > 0)
		{
			$this->ci->form_validation->set_message("checkmodule", "Module name already exists.");
			return false;
		}else
			return true;
	}
	
	function updateBranchStatus(){
		foreach($_POST['checked'] as $c=>$val){
			$data[] = array("id"=>$c,
								"active"=>$val);
		}
		if($this->ci->db->update_batch('branches', $data, 'id'))
		return true;
	}
	
	function update_password(){
		$this->ci->input->post(NULL, TRUE);
		//$this->ci->form_validation->set_rules("oldpassword", "Old Password", "required|callback_checkpassword");
		$this->ci->form_validation->set_rules("newpassword", "New Password", "required|matches[confirmpassword]");
		$this->ci->form_validation->set_rules("confirmpassword", "Confirm Password", "required");
		$this->ci->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
		if($this->ci->form_validation->run() != FALSE){
			$this->ci->UserMgmt->update_password($_POST);
			return true;
		}else return false;
	}
	
	
	function updateProfile(){
		$this->ci->input->post(NULL, TRUE);
		$this->ci->form_validation->set_rules("firstname", "First Name", "trim|required|xss_clean");
		$this->ci->form_validation->set_rules("lastname", "Last Name", "trim|required|xss_clean");
		$this->ci->form_validation->set_rules("username", "Username", "trim|required|xss_clean|callback_duplicateuser");
		$this->ci->form_validation->set_rules("email", "Email", "required|valid_email");
		$this->ci->form_validation->set_rules("contact", "Contact Number", "required|trim|xss_clean");
		$this->ci->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
		if($this->ci->form_validation->run() != FALSE){
			$data = array("firstname" => $_POST['firstname'],
								"lastname" => $_POST['lastname'],
								"email" => $_POST['email'],
								"username" => $_POST['username'],
								"branch_id" => $_POST['branch'],
								"group_id" => $_POST['group'],
								"date_modified" => $this->localtime(),
								"last_modified_by" => $this->user_id());
			$table = "user";
			$where = array("id" => $_POST['userid']);
			if($this->ci->Loansmodel->update_data($table, $where, $data) == true)
			return true;
			}else return false;
		}
	
	
	function audit(){
	
		
	}
	
	function loanapproval($productid, $userid, $branch, $amount){
		//check for all branches
		$data = array("productID"=>$productid,
							"userid"=>$userid,
							"branchID"=>0,
							"fromAmount <="=>$amount,
							"toAmount >="=>$amount,
							"active"=>1);
		$res = $this->ci->Loansmodel->get_data_from('loanapproval', $data);
		
		if($res->num_rows() > 0){
			return true;
		}else{
			$data = array("productID"=>$productid,
							"userid"=>$userid,
							"branchID"=>$branch,
							"fromAmount <="=>$amount,
							"toAmount >="=>$amount,
							"active"=>1);
			$res = $this->ci->Loansmodel->get_data_from('loanapproval', $data);

			if($res->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}
	}
}

?>