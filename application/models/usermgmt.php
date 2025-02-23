<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usermgmt extends CI_Model {

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	//ADD Module
	public function add_module()
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
	
	public function get_module(){
		$ql = "select * from modules";
		$res = $this->db->query($ql);
		$newres = $res;
		
		return $res;
	}
	
	function getheadermodulelink($mod){
		$this->db->select('module_link');
		$this->db->from('modules');
		$this->db->where('module_name', 'Header.'.$mod);
		return $this->db->get();
	}
	
	public function getheader(){
		$ql = "select * from modules where active='1' and module_name like 'Header.%' order by id ASC";
		$res = $this->db->query($ql);
		$newres = $res;		
		return $res;
	}
	public function getmenu($menu){
		$this->db->select('*');
		$this->db->where($menu);
		return $this->db->get("modules");
	}
	
	public function delete_module($mod){
		//delete from module table and rights by module_id
		$data = array("active" => 0);
		$this->db->where("id", $mod);
		$this->db->update("modules", $data );
		
		$this->db->where("module_id",$mod);
		$this->db->update("rights", $data);
		
	}
	public function activate_module($mod){
		//delete from module table and rights by module_id
		$data = array("active" => 1);
		$this->db->where("id", $mod);
		$this->db->update("modules", $data );
		
		$this->db->where("module_id",$mod);
		$this->db->update("rights", $data);
		
	}
	
	public function module_action($action){
		
	}
	
	public function add_branch()
	{
		
	}
	
	public function get_branches()
	{
		$sql ="select * from branches where active='1' order by id ASC ";
		//$sql = "CALL getBranches()";
		$res = $this->db->query($sql);
		
		return $res;
	}
	
	public function get_branch_by_id($id)
	{
		//$sql = "CALL getBranchById('$id')";
		$sql = "select branchname from branches where id='$id'";
		$res = $this->db->query($sql);
		if($res->num_rows() > 0){
			foreach($res->result() as $r)
			{
				return $r->branchname;				
			}			
		
		}
	}
	
	function branch($brid){
		$this->db->where("id",$brid);
		$info['details'] = $this->db->get('branches');
		$info['emps'] = $this->br_employees($brid);
		$info['banks'] = $this->Cashmodel->getbanklistonbranch($brid);
		return $info;
		
	}
	
	
	function br_employees($br){
		$this->db->where("branch_id",$br);
		$this->db->where("user.active",1);
		$this->db->join("roles", "roles.group_id = user.group_id");
		return $this->db->get('user');
	}
	
	public function add_user($post)
	{
		list($password, $salt) = $this->hash_password($post['password']);
		$data = array("firstname" => $post['firstname'],
						"username" => $post['username'],
						"lastname" => $post['lastname'],
						"email" => $post['email'],
						"contact" => $post['contact'],
						"password" => $password,
						"branch_id" => $post['branch'],
						"group_id" => $post['group'],
						"salt" => $salt,
						"date_created" => date("Y-m-d h:i:s"),
						"date_modified" => date("Y-m-d h:i:s"),
						"created_by" => 1,
						"userip" => $this->input->ip_address(),
						"active" => 0);
		$this->db->insert("user", $data);
		$user_id = $this->db->insert_id();
		return $user_id;
	}
	

	public function update_user($post)
	{
		$data = array("firstname" => $post['firstname'],
						"lastname" => $post['lastname'],
						"email" => $post['email'],
						"contact" => $post['contact'],
						"username" => $post['username'],
						"date_modified" => date("Y-m-d h:i:s"));
		$this->db->where("id", $this->auth->user_id());
		$user_id = $this->db->update("user", $data);
		if($user_id) return true;
	}
	public function get_total_user(){
		//$this->db->where ('active', '1');
		$total = $this->db->get('user')->num_rows();
		return $total;
	}
	public function get_users($perpage, $urlpage){
		//$sql = "CALL getActiveUsers()";
		$sql = "select * from user order by lastname ASC";
		$this->db->select('*');
		$this->db->order_by("lastname", "asc"); 
		$res = $this->db->get('user', $perpage, $urlpage);
		return $res;
	}
	
	public function get_user_byid($id){
		$sql = "select * from user where id='$id'";
		$res = $this->db->query($sql);
		return $res;
	}
	
	public function add_role($post)
	{
		$data = array("name" => $post['role'],
					"description" => $post['description'],
					"date_created" => date("Y-m-d h:i:s"),
					"date_modified" => date("Y-m-d h:i:s"),
					"created_by" => 0,
					"last_modified_by" => 0,
					"user_ip" => $this->input->ip_address(),
					"active" => 1);
		//add_rights
		$role_id = $this->db->insert("roles", $data);
	
		
		if($role_id) return true;		
	}
	
	public function get_total_records($table,$active){
		if($active != 'all')
		$this->db->where ('active', $active);
		$total = $this->db->get($table)->num_rows();
		return $total;
	}
	
	public function get_roles($perpage, $urlpage)
	{
		$sql = "select * from roles order by name ASC";
		$this->db->select('*');
		$res = $this->db->get('roles', $perpage, $urlpage);
		//$res = $this->db->query($sql);
		return $res;
	}
	
	public function get_role()
	{
		$sql = "select * from roles order by name ASC";
		$this->db->select('*');
		$res = $this->db->get('roles');
		//$res = $this->db->query($sql);
		return $res;
	}
	
	public function get_records_from($table ,$perpage, $urlpage, $active)
	{		
		$this->db->select('*');
		if($active != 'all')
			$this->db->where("active", $active);
		$res = $this->db->get($table, $perpage, $urlpage);
		//$res = $this->db->query($sql);
		return $res;
	}
	
	public function role_byid($id)
	{
		$sql = "select * from roles where group_id='$id'";
		$res = $this->db->query($sql);
		
		return $res;
	}
	
	public function get_role_byid($id)
	{
		$sql = "select * from roles where group_id='$id'";
		$res = $this->db->query($sql);
		if($res->num_rows() > 0){
			foreach($res->result() as $r)
			{
				$role = $r->name;
			}
		}
		return $role;
	}
	
	public function edit_role($data,$id)
	{
		$this->db->where("group_id", $id);
		$this->db->update("roles", $data);
		return true ;
	}
	
	public function numofUser($id){
		$sql = "select count(*) from user where group_id='$id'";
		$res=$this->db->query($sql);
		foreach($res->result() as $r){
			
		}
		return $res;
	}
	
	public function validate_password($pass,$id){
		$sql = "select * from user where id='$id'";	
		$res=$this->db->query($sql);
		if (!function_exists('do_hash'))
		{
			$this->load->helper('security');
		}
		if($res->num_rows() >0)
		{
			foreach ($res->result() as $u){
				// Try password
				//echo "<br/>with salt old= ".do_hash($u->salt . $pass);
				//echo "<br/>".$u->password;
				if (do_hash($u->salt . $pass) == $u->password)
				{					
					return TRUE;
				}else
					return FALSE;
			}	
		}else return FALSE;
		
		if($res->num_rows() >0)return true;
	}
	
	public function update_password($post){
		list($password, $salt) = $this->hash_password($post['newpassword']);
		$data = array("password" => $password,
						"salt" => $salt,
						"date_modified" => $this->auth->localtime());
		$this->db->where('id', $post['userid']);
		$this->db->update("user", $data);
		return true;
	}
	
	public function reset_password($post){
		list($password, $salt) = $this->hash_password($post['newpassword']);
		$data = array("password" => $password,
						"salt" => $salt,
						"date_modified" => $this->auth->localtime());
		$this->db->where('email', $post['email']);
		$this->db->update("user", $data);
		return true;
	}
	
	public function check_rights($module,$userid){
		$sql = "select * from rights where user_id='$userid' and module_id='$module'";
		$res = $this->db->query($sql);
		if($res->num_rows() > 0)
		return true;
		else return false;
	}
	
	public function check_right_perm($module,$userid,$right){
		$where = array("module_id"=>$module,
								"user_id"=>$userid,
								"module_right"=>$right);
		$this->db->select("active");
		$r = $this->db->get("rights");
		//$this->db->last_query();
		//$this->output->enable_profiler(TRUE);
		return $r;
	}
	
	public function get_right_byname($right){
		$this->db->where("module_name", $right);
		$this->db->select("id");
		$r = $this->db->get("modules");
		//$this->db->last_query();
		//$this->output->enable_profiler(TRUE);
		return $r;
	}

	public function add_rights($data,$user){
		if($user == "user")
		$this->db->insert("rights",$data);
		if($user == "group")
		$this->db->insert("group_rights",$data);
		//$this->db->last_query();
		//$this->output->enable_profiler(TRUE);
		return true;
	}
	public function right_update($where, $active){
		$this->db->where($where);
		$this->db->update('rights', $active);
		//$this->db->last_query();
		//$this->output->enable_profiler(TRUE);
		return true;
	}
	
	public function get_rights($userid,$mod){
		$sql="select * from rights where user_id='$userid' and module_id='$mod'";
		//echo $sql;
		$res = $this->db->query($sql);
		return $res;		
	}
	

	public function groupright_update($where, $active){
		$this->db->where($where);
		$this->db->update('group_rights', $active);
		//$this->db->last_query();
		//$this->output->enable_profiler(TRUE);
		return true;
	}
	
	public function get_group_rights($group,$mod){
		$sql="select * from group_rights where group_id='$group' and module_id='$mod'";
		$res = $this->db->query($sql);
		return $res;		
	}
	
	public function user_status($status,$userid)
	{	
		$this->db->where("id",$userid);
		$this->db->update("user", $status);
	}
	
	public function delete_user($userid){
		$this->db->where("id",$userid);
		$this->db->delete("user");
		
		$this->db->where("user_id",$userid);
		$this->db->delete("rights");
	}
	
	
	public function login($username,$pass){
		$this->db->select("roles.name as role, id,firstname,lastname,email,user.group_id,branch_id,salt,password");
		$where = array ("username"=> $username,
								"user.active" => 1);
		$this->db->where($where);
		$this->db->join("roles","roles.group_id=user.group_id");
		$user = $this->db->get('user');
		//$this->db->last_query();
		//$this->output->enable_profiler(TRUE);
		if($user->num_rows() >0)
		{
		//return $user;
			foreach ($user->result() as $u){
				// Try password
				if (do_hash($u->salt . $pass) == $u->password)
				{					
					return $user;
				}else
					return FALSE;
			}
		}else return FALSE;
	
	}
	
	//--------------------------------------------------------------------
	// !AUTH HELPER METHODS
	//--------------------------------------------------------------------

	/**
	 * Generates a new salt and password hash for the given password.
	 *
	 * @access public
	 *
	 * @param string $old The password to hash.
	 *
	 * @return array An array with the hashed password and new salt.
	 */
	public function hash_password($old='')
	{
		if (!function_exists('do_hash'))
		{
			$this->load->helper('security');
		}

		$salt = $this->generate_salt();
		$pass = do_hash($salt . $old);

		return array($pass, $salt);

	}//end hash_password()

	//--------------------------------------------------------------------

	/**
	 * Create a salt to be used for the passwords
	 *
	 * @access private
	 *
	 * @return string A random string of 7 characters
	 */
	private function generate_salt()
	{
		if (!function_exists('random_string'))
		{
			$this->load->helper('string');
		}

		return random_string('alnum', 7);

	}//end generate_salt()
	//--------------------------------------------------------------------
	
	public function lastlogin($userid){
		$data = array("last_login"=> $this->auth->localtime(),
							"userip"=> $this->input->ip_address());
		//$this->db->select('*');
		$this->db->where("id", $userid);
		$this->db->update("user", $data);
		//$this->db->last_query();
		//$this->output->enable_profiler(TRUE);
	}
	
	public function new_activity($activity,$userid,$module){
		$data = array("user_id" => $userid,
							"activity" => $activity,
							"module" => $module,
							"created_on" => $this->auth->localtime()
							);
		$this->db->insert('activity_logs',$data);		
	}
	
	public function permission($data){
		$this->db->where($data);
		$this->db->select("active");
		$right = $this->db->get("rights");
		//$sql = "CALL checkRightPerm(?,?,?)";
		//$right = $this->db->query($sql, $data);
		//$this->db->last_query();
		//$this->output->enable_profiler(TRUE);
		return $right;
		if($right->num_rows() > 0)
		{
			foreach($right->result() as $r)
			{
				$res = $r->active;
			}			
			return $res;
		}else
		return false;
	}
	
	public function count_role($role){
		$this->db->select('count(*)');
		$this->db->where("group_id", $role);
		$res = $this->db->get("user");
		if($res->num_rows() > 0){
			foreach($res->result() as $re){
				foreach($re as $r){
					echo $r;
				}
			}
		}
	
	}
	
	public function email_exist($email){
		$this->db->where("email", $email);
		$re = $this->db->get("user");
		if($re->num_rows() > 0) return true;
		else return false;
	}
	
	public function check_branchrights($data){
		$this->db->where($data);
		$res = $this->db->get('branchrights');
		
		if($res->num_rows() > 0)
		return true;
		else return false;
	
	}
	
	public function insert_data_to($table,$data){
		$this->db->insert($table, $data);
		//$this->db->last_query();
		//$this->output->enable_profiler(TRUE);
		if($this->db->insert_id())
		return $this->db->insert_id();
		else
		return false;
	}
	
	public function updateBranch($where, $data){
		$this->db->where($where);
		$this->db->update('branches', $data);
	}
	
	function getHolidays($br, $start, $limit){
		$this->db->select('*');
		//$this->db->join("branches", "branches.id = holidays.branchID");
		if($br!="")
		$this->db->where("branchID",$br);
		//$this->db->where("holidays.active",1);
		$this->db->from('holidays');
		if(!is_null($start))
		$this->db->limit($limit, $start);
		$this->db->order_by("dateOfHoliday", "ASC");
		//$this->db->last_query();
		//$this->output->enable_profiler(TRUE);
		return $this->db->get();
	}
	
	
	function audit($module, $action){
		$localdate = $this->auth->localtime();
		$serverdate = date("Y-m-d H:i:s");
		$userid = $this->auth->user_id();
		$crud = $action; 
		// add:firstname=chinny;add:lastname=palmares;change:bday 0923=0922;
		// add:collection OR=3243243; add:amount=1231; 
		$ip = $this->input->ip_address();
		$hostname = $_SERVER['SERVER_NAME'];
		$sql = "CALL spInsertAuditLogs( ?, ?, ?, ?, ?, ?, ?)";
		//echo $sql;
		$data = array($localdate, $serverdate, $userid, $module, $crud, $ip, $hostname);
		$result = $this->db->query($query, $data);
        $data = "";
           
		   return true;
	}
	
	
	//escapes and adds single quotes
    //to each value of an array
    function safe_escape(&$data)
    {
        if(count($data) <= 0)
        {
            return $data;
        }
        
        foreach($data as $node)
        {
            $node = $this->db->escape($node);
        }
        
        return $data;
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */