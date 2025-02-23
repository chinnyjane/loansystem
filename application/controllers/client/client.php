<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client extends CI_Controller 

	public $page = array ( "pagetitle" => "Clients - Fruits Consulting Inc",
							"nav" => 'final/navheader',
							"template" => 'template/new/body',
							"menu" => 'final/sidemenu',
							"module" => "Header.Clients");
							
	public $debug = false; // turn to false if live
	
	function __construct()
	{
	  parent::__construct();
	  $this->load->helper('security');	
	   $this->auth->restrict();
	     $this->load->library('Datatables');
	}
	
	public function index()
	{			
		$page = $this->page;		
		$page['submod'] = "Masterlist";
		$page['header'] = $this->UserMgmt->getheader();
		if(isset($_POST['name'])){
			$name = $_POST['name'];	
			$this->session->set_userdata('searchname', $name);		
		}
		
		$page['name'] = $this->session->userdata('searchname');
		//$page['main'] = 'cash/overview';
		$page['main'] = 'client/list';
		$this->load->view($page['template'], $page);
	}

	//===NEW CLIENT
	function add(){
		
		//Personal INFO validation
		$client = $this->validation_client();
		
		if($_POST['civilstatus'] != 'single' ) 
			$spouse = $this->validate_spouse();
			
 		if( $client == false or $spouse ==  false){
			echo validation_errors();
		}else{
			
			//add personal info and SPOUSE INFO
			$data = array("firstName" => $_POST['firstname'],
					"MiddleName" => $_POST['mname'],
					"LastName" => $_POST['lname'],
					"dateOfBirth" => date("Y-m-d", strtotime($_POST['bdate'])),
					"address" => $_POST['address'],
					"barangay" => $_POST['brgy'],
					"contact" => $_POST['contact'],
					"city" => $_POST['city'],
					"civilStatus" => $_POST['civilstatus'],
					"gender" => $_POST['gender'],
					"dateAdded" => $this->auth->localtime(),
					"branchID" => $this->auth->branch_id(),
					"addedBy" => $this->auth->user_id());
			
			$clientid = $this->Clientmgmt->save();
			
			$hash = md5(substr($this->auth->localtime(), 0, 10).$this->ci->auth->user_id().$clientid);
			$id = array("clientID"=>$clientid);
			$hasharray = array("inlineHash"=>$hash);
			$this->Loansmodel->update_inlinehash('clientinfo', $id,$hasharray);
			
			//EMPLOYMENT INFO
			//INCOME
			//EXPENSE
			//DEPENDENTS
			//Credit
		}
			
	}
	
	//===UPDATE CLIENT INFO
	function update(){
	
		//Personal INFO
		//SPOUSE INFO
		//EMPLOYMENT INFO
		//INCOME
		//EXPENSE
		//DEPENDENTS
		//Credit
		
	}
	
	//===VIEW CLIENT PROFILE
	function profile(){
	
		//Personal INFO
		//SPOUSE INFO
		//EMPLOYMENT INFO
		//INCOME
		//EXPENSE
		//DEPENDENTS
		//Credit
		//Active Loans 
		
	}
	
	
	
	//============= VALIDATION FUNCTIONS HERE==================//
	
	function validation_client(){
	
		//PERSONAL INFO
		$this->form_validation->set_rules("firstname", "First Name", "required");
		$this->form_validation->set_rules("mname", "Middle Name", "required");
		$this->form_validation->set_rules("lname", "Last Name", "required|callback_name_exist");
		$this->form_validation->set_rules("contact", "Client's Contact #", "required|is_numeric");
		$this->form_validation->set_rules("city", "City", "required");
		$this->form_validation->set_rules("brgy", "Barangay", "required");
		$this->form_validation->set_rules("address", "Address", "required");
		$this->form_validation->set_rules("address", "Address", "required");
		$this->form_validation->set_rules("bdate", "Date of Birth", "required");
		
			
		if($this->ci->form_validation->run() == False){		
			return false;
		}else{
			return true;
		}
	}
	
	function validate_spouse(){
		$this->form_validation->set_rules('spfirstname', "Spouse first name", "required|xss_clean");
		$this->form_validation->set_rules('spmname', "Spouse middle name", "required|xss_clean");
		$this->form_validation->set_rules('splname', "Spouse last name", "required|xss_clean");
		$this->form_validation->set_rules('spwork', "Spouse Occupation", "xss_clean");
		$this->form_validation->set_rules('spsalary', "Spouse Salary", "xss_clean");
		$this->form_validation->set_rules('spcompany', "Spouse Company", "xss_clean");
		$this->form_validation->set_rules('spcontact', "Spouse Contact", "is_numeric|xss_clean");
		$this->form_validation->set_rules('spbdate', "Spouse Date of Birth", "xss_clean");
		if($this->form_validation->run() == False){		
			return false;
		}else{
			return true;
		}
	}
	
	function name_exist(){
		$fname = $_POST['firstname'];
		$lname = $_POST['lname'];
		$pars = array('firstName'=>$fname,
					'LastName'=>$lname,
					'active'=>1);
					
		if($this->Loansmodel->fieldIn('clientinfo', $pars) == true)
		{
			$this->form_validation->set_message('name_exist', "Client Name already exists.");
			return false;
		}else{
			return true;
		}
	}

} 

?>