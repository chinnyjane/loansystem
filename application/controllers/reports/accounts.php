<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Accounts extends CI_Controller {

	public $page = array ( "pagetitle" => "Chart of Accounts - Yusay Credit & Finance Corporation",
							"nav" => 'final/navheader',
							"template" => 'template/new/body',
							"menu" => 'final/sidemenu',
							"module" => "Reports");

	public $debug = false; // turn to false if live

	function __construct()
	{
	  parent::__construct();
	  $this->load->helper('security');
	   $this->auth->restrict();
	}

	public function index()
	{
		$page = $this->page;
		$page['module'] = "Chart of Accounts";
		$page['main'] = "reports/accounts";
		//$page['main'] = 'settings/overview';		
		$page['coagroup'] = $this->Accounting->getAcctGroups();
		$page['account'] = $this->Accounting->ChartOfAccounts();
		//echo $this->db->last_query();
		$this->load->view($page['template'], $page);
	}
	
	function details(){
		$page = $this->page;
		$page['module'] = "Chart of Accounts";
		$page['main'] = "reports/accountdetails";
		$coacode = $this->uri->segment(4);
		$page['coagroup'] = $this->Accounting->getAcctGroups();
		$page['account'] = $this->Accounting->ChartOfAccounts();
		$page['coa_details'] = $this->Accounting->coa_details($coacode);
		//$page['main'] = 'settings/overview';		
		
		$this->load->view($page['template'], $page);
	}
	
	function gl(){
		$page = $this->page;
		$page['branchid'] = $this->uri->segment(4);
		$page['module'] = "GL Accounts";
		$page['coagroup'] = $this->Accounting->getAcctGroups();
		$page['account'] = $this->Accounting->ChartOfAccounts();
		$page['main'] = "reports/gl";
		//echo $this->db->last_query();
		$this->load->view($page['template'], $page);
	}
	
	public function category()
	{
		$page = $this->page;
		$page['module'] = "Account Groups";
		$page['pagetitle'] = "Accounts Group - Yusay Credit & Finance Corporation";
		$page['main'] = "reports/group";
		$page['coagroup'] = $this->Accounting->getAcctGroups();
		//echo $this->db->last_query();
		$this->load->view($page['template'], $page);
	}
	
	
	function cat_details(){
		$page = $this->page;
		$page['module'] = "Account Groups";
		$page['main'] = "reports/category_details";
		$coacode = $this->uri->segment(4);
		$page['coagroup'] = $this->Accounting->getAcctGroups();
		$page['account'] = $this->Accounting->ChartOfAccounts();
		$page['coa_details'] = $this->Accounting->acct_group_details($coacode);
		//$page['main'] = 'settings/overview';		
		
		$this->load->view($page['template'], $page);
	}
	
	
	function addgroup(){
		header("content-type:application/json");
		$this->form_validation->set_rules("coa_codeprefix", "Acct Code Prefix", "required|xss_clean");
		$this->form_validation->set_rules("coa_code", "Account", "required|xss_clean|callback_accountCode");
		$this->form_validation->set_rules("coa_name", "Account Title", "required|xss_clean");
		$this->form_validation->set_rules("coa_desc", "Account Description", "required|xss_clean");
		$this->form_validation->set_rules("coa_order", "Order of Account", "required|xss_clean|is_numeric");
		
		if($this->form_validation->run() === false){
			$msg['stat'] = false;
			$msg['msg']= validation_errors();
		}else{
			$data = array("coa_codeprefix"=>$_POST['coa_codeprefix'],
									"coa_code"=> $_POST['coa_code'],
									"coa_name"=> $_POST['coa_name'],
									"coa_parent"=> $_POST['coa_parent'],
									"coa_desc"=>$_POST['coa_desc'],
									"coa_order"=>$_POST['coa_order'],
									"date_added"=>$this->auth->localtime(),
									"added_by"=>$this->auth->user_id(),
									"active"=>1	);
			$table = "gl_coacategory";
			if($this->Loansmodel->addtotable($table, $data) == true){
				$msg['stat'] = true;
				$msg['msg']= "New Account Group was added";
			}else{
				$msg['stat'] = false;
				$msg['msg']= "Account group was not added";
			}
		}
		
		echo json_encode($msg);
	}
	
	function updategroup(){
		header("content-type:application/json");
		if($_POST['code_prefix'] == $_POST['coa_codeprefix'])
			$this->form_validation->set_rules("coa_codeprefix", "Acct Code Prefix", "required|xss_clean");
		else 
			$this->form_validation->set_rules("coa_codeprefix", "Acct Code Prefix", "required|xss_clean|callback_codeprefix");
		
		if($_POST['code'] == $_POST['coa_code'])
			$this->form_validation->set_rules("coa_code", "Account", "required|xss_clean");
		else
			$this->form_validation->set_rules("coa_code", "Account", "required|xss_clean|callback_accountCode");

		$this->form_validation->set_rules("coa_order", "Order of Account", "required|xss_clean|is_numeric");		
		$this->form_validation->set_rules("coa_name", "Account Title", "required|xss_clean");
		$this->form_validation->set_rules("coa_desc", "Account Description", "required|xss_clean");
		
		if($this->form_validation->run() === false){
			$msg['stat'] = false;
			$msg['msg']= validation_errors();
		}else{
			$data = array("coa_codeprefix"=>$_POST['coa_codeprefix'],
									"coa_code"=> $_POST['coa_code'],
									"coa_name"=> $_POST['coa_name'],
									"coa_parent"=> $_POST['coa_parent'],
									"coa_desc"=>$_POST['coa_desc'],
									"coa_order"=>$_POST['coa_order'],
									"date_modified"=>$this->auth->localtime(),
									"modified_by"=>$this->auth->user_id(),
									"active"=>1	);
			$where = array("coa_id"=>$_POST['coa_id']);
			if($this->Loansmodel->update_data("gl_coacategory", $where, $data)){
				$msg['stat'] = true;
				$msg['msg']= "Account Group was updated";
			}else{
				$msg['stat'] = false;
				$msg['msg']= "Account group was not updated";
			}
		}
		
		echo json_encode($msg);
	}
	
	function codeprefix(){
		$codeprefix = $_POST['coa_codeprefix'];
		$data = array("coa_codeprefix"=>$codeprefix,
								"active"=>1);
								
		if($this->Loansmodel->fieldIn("gl_coacategory",$data) ==true){
			$this->form_validation->set_message("codeprefix","Code Prefix is on record already. Please choose another prefix.");
			return false;
		}else{
			return true;
		}
		
	}
	
	function accountCode(){
		$codeprefix = $_POST['coa_code'];
		$data = array("coa_code"=>$codeprefix,
								"active"=>1);
								
		if($this->Loansmodel->fieldIn("gl_coacategory",$data) ==true){
			$this->form_validation-set_message("accountCode","Account Code  is on record already. Please try another code.");
			return false;
		}else{
			return true;
		}
		
	}
	
	function add_account(){
		header("content-type:application/json");
		$this->form_validation->set_rules("coa_code", "Account", "required|xss_clean|callback_accountCoaCode");
		$this->form_validation->set_rules("coa_name", "Account Title", "required|xss_clean");
		$this->form_validation->set_rules("coa_desc", "Account Description", "required|xss_clean");
		$this->form_validation->set_rules("coa_category", "Account Category", "required|xss_clean");
		
		if($this->form_validation->run() === false){
			$msg['stat'] = false;
			$msg['msg']= validation_errors();
		}else{
			$data = array("coa_category"=>$_POST['coa_category'],
									"coa_code"=> $_POST['coa_code'],
									"coa_name"=> $_POST['coa_name'],
									"coa_desc"=>$_POST['coa_desc'],
									"coa_parent"=>$_POST['coa_parent'],
									"normal_balance"=>$_POST['norm_bal'],
									"with_sub"=>$_POST['with_sub'],
									"date_added"=>$this->auth->localtime(),
									"added_by"=>$this->auth->user_id(),
									"active"=>1	);
			$table = "gl_coa";
			if($this->Loansmodel->addtotable($table, $data) == true){
				$msg['stat'] = true;
				$msg['msg']= "New Account  was added";
			}else{
				$msg['stat'] = false;
				$msg['msg']= "New Account  was not added";
			}
		}		
		echo json_encode($msg);
	}
	
	function accountCoaCode(){
		$codeprefix = $_POST['coa_code'];
		$data = array("coa_code"=>$codeprefix,
								"active"=>1);
								
		if($this->Loansmodel->fieldIn("gl_coa",$data) ==true){
			$this->form_validation-set_message("accountCode","Account Code  is on record already. Please try another code.");
			return false;
		}else{
			return true;
		}
		
	}
	
	function updatedetails(){
		header("content-type:application/json");
		if($_POST['code']==$_POST['coa_code']){
			$this->form_validation->set_rules("coa_code", "Account Code", "required|xss_clean");
		}else{
			$this->form_validation->set_rules("coa_code", "Account Code", "required|xss_clean|callback_accountCoaCode");
		}
		
		$this->form_validation->set_rules("coa_name", "Account Title", "required|xss_clean");
		$this->form_validation->set_rules("coa_desc", "Account Description", "required|xss_clean");
		$this->form_validation->set_rules("coa_category", "Account Category", "required|xss_clean");
		
		if($this->form_validation->run() === FALSE){
			$msg['stat'] = false;
			$msg['msg'] = validation_errors();
		}else{
			$data = array("coa_code"=>$_POST['coa_code'],
									"coa_name"=>$_POST['coa_name'],
									"coa_desc"=>$_POST['coa_desc'],
									"coa_category"=>$_POST['coa_category'],
									"coa_parent"=>$_POST['coa_parent'],
									"normal_balance"=>$_POST['norm_bal'],
									"with_sub"=>$_POST['with_sub'],
									"date_modified"=>$this->auth->localtime(),
									"modified_by"=>$this->auth->user_id());
			$where = array("coa_id"=>$_POST['coa_id']);
			$this->Loansmodel->update_data("gl_coa", $where, $data);
			$msg['stat'] = true;
			$msg['msg'] = "Account details was updated";
		}
		
		echo json_encode($msg);
	}
	
	function addbranchGL(){
		header("content-type:application/json");
		$this->db->trans_begin();		
		$code = $this->Accounting->getLastSubCode($_POST['coa_code']);
		list($coa_id, $coa_code) = explode("-",$_POST['coa_code']);
			$data = array("coa_category"=>$_POST['coa_category'],
							"coa_code"=> $coa_code,
							"coa_name"=> $_POST['coa_name'],
							"coa_desc"=>$_POST['coa_desc'],
							"coa_parent"=>$coa_id,
							"normal_balance"=>$_POST['norm_bal'],
							"with_sub"=>$_POST['with_sub'],
							"op_balance"=>$_POST['balance'],
							"coa_sub_code"=>$code,
							"branch_id"=>$_POST['branch_id'],
							"date_added"=>$this->auth->localtime(),
							"added_by"=>$this->auth->user_id(),
							"active"=>1	);
			$table = "gl_coa";
			if($this->Loansmodel->addtotable($table, $data) == true){
				
				$acct_id = $this->db->insert_id();
				$msg['stat'] = true;
				$msg['msg']= "New Account Group was added";
				
				$data2 = array("gl_id"=>$acct_id,
								"branch_id"=>$_POST['branch_id'],
								"beginning_balance"=>$_POST['balance'],
								"current_balance"=>$_POST['balance'],
								"date"=>$this->auth->localdate(),
								"date_added"=>$this->auth->localtime(),
								"added_by"=>$this->auth->user_id(),
								"active"=>1);
				$table = "gl_balances";
				if($this->Loansmodel->addtotable($table, $data2) == true){
					
				}
				
			}
		
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$msg['stat'] = false;
			$msg['msg']= "Account group was not added";
		}
		else
		{
			$this->db->trans_commit();
			$msg['stat'] = true;
			$msg['msg'] .= "Balance was updated";
		}
		echo json_encode($msg);
	}
} ?>
