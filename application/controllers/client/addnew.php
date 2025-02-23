<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Addnew extends CI_Controller {
	function __construct()
	{
	  parent::__construct();
	  $this->load->helper('security');
	  $this->auth->restrict();
	}
	public $page = array ( "pagetitle" => "Dashboard - Fruits Consulting Inc",
							"nav" => 'final/navheader',
							"template" => 'template/new/body',
							"module" => "Header.Clients",
							"submod"=> "Add New Client");

	public function index_old(){
		$page = $this->page;
		$page['header'] = $this->UserMgmt->getheader();
		if($_POST){
			echo $this->validate_client();
		}else{
		//$page['form'] = 'user/loans/clientinfoform';
		$page['main']="client/addclient";
		//$page['subcontent']="client/loanapplication";
		//$page['main'] = 'cash/overview';
		$this->load->view($page['template'], $page);
		}
	}

	public function index(){
		$page = $this->page;
		$page['header'] = $this->UserMgmt->getheader();
		if($_POST){
			if($this->validation_client() ==true){
				$clientid = $this->loansetup->addpersonalinfo();
				redirect(base_url().'client/profile/'.$clientid);
			}
		}
		$page['main']="client/applicationflow";

		$clientid = $this->session->userdata("applicant_id") ;
		$page['loantype'] = $this->session->userdata("loantype") ;
		$page['loanid'] = $this->session->userdata('loanid');
		$page['clientID'] = $this->session->userdata('applicant_id');
		$page['pid'] = $this->session->userdata('pid');

		$page['client'] = $this->Clientmgmt->getclientinfoByID($clientid);
		$page['spouse'] = $this->Clientmgmt->getspouse($clientid);
		$page['dependents'] = $this->Clientmgmt->getdependents($clientid);
		$page['creditor'] = $this->Clientmgmt->getcreditor($clientid);
		$page['IncomeExpense'] = $this->Clientmgmt->getIncomeExpense($clientid);
		$page['clientid'] = $clientid;


		$this->load->view($page['template'], $page);

	}

	function validate_client(){
		if($this->loansetup->validation_client() == false){
			echo validation_errors();
		}
	}
	
	function get_cities(){
		//echo $_POST['province'];
		$cities = $this->Loansmodel->get_cities_by_prov($_POST['province']);
		foreach($cities->result() as $c){
			echo "<option value='".$c->id."'>".$c->name."</option>";
		}
	}


	function get_age($bday){
		$age = $this->loansetup->get_age($bday);
		if($age > 70){
			$this->form_validation->set_message('get_age', 'The applicant is not qualified. '.$age." yrs old");
			return false;
		}elseif($age<18){
			$this->form_validation->set_message('get_age', 'The applicant is not qualified. '.$age." yrs old");
			return false;
		}else{
			return true;
		}
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
		
			
		if($this->form_validation->run() == False){		
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
		$mname = $_POST['mname'];
		$pars = array('firstName'=>$fname,
					'LastName'=>$lname,
					'MiddleName'=>$mname,
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