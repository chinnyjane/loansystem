<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Interest extends CI_Controller {
	
	public $page = array ( "pagetitle" => "Product - Fruits Consulting Inc",
							"nav" => 'final/navheader',
							"template" => 'template/new/body',
							"menu" => 'final/sidemenu',
							"module" => "Header.Product",
							"submod"=>"Interest Rates Table",
							"active" => "Products.Interest Rates Table"
							);
							
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
		$page['header'] = $this->UserMgmt->getheader();
		$page['products'] = $this->Loansmodel->get_productcodes();
		$page['main'] = 'product/interest';
		$this->load->view($page['template'], $page);
	}
	
	public function getInterestTable(){
		//if ($this->is_ajax()) {
		 
			 $this->test_function(); 
		
		//}
	}
	
	
	function addInterest(){
		header("content-type:application/json");
		$this->form_validation->set_rules('term', "# of terms", "required|numeric|callback_existing_int|callback_minmaxterm");
		$this->form_validation->set_rules('interest', 'Interest Rate', 'required|numeric');
		$this->form_validation->set_rules('loancode', 'Loan Product', 'required');
		$this->form_validation->set_rules('method', 'Payment method', 'required');
		if($this->form_validation->run() == false){
			$re['errors'] = validation_errors();
			$re['status'] = false;
		}else{
			$interest = $this->Products->addInterest($_POST['loancode'], $_POST['method'], $_POST['term'], $_POST['interest']);
			
			if($interest == true)
				$re['status'] = true;
			else
				$re['status'] =  false;
		}
		
		echo json_encode($re);
		
	}
	
	
	function existing_int(){
		$int = $this->Products->checkInterestExist($_POST['loancode'], $_POST['method'], $_POST['term']);
		
		if($int == false){
			//echo $int;
			return true;
		}else{	
			
			$this->form_validation->set_message("existing_int", "There's an existing interest rate for this term of loan.");
			//echo $int;
			return false;
		}
	}
	
	function minmaxterm(){
		$data = array("minTerm >="=>$_POST['term'],
					"maxTerm <="=>$_POST['term'],
					"LoanCode"=>$_POST['loancode'],
					"PaymentTerm"=>$_POST['method']);
		if($this->Products->minmaxTerm($data) == true)
		{
			$this->form_validation->set_message("minmaxterm", "Term is invalid.");
			return false;	
		}else{
			return true;	
		}
	}
	
	function is_ajax() {
	  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}
	
	function updateInterest(){
		$this->load->view('product/editInterest');
	}
	
	function test_function(){
	  
	 $sql = "SELECT 
				  term,
				  interest,
				  minTerm,
				  maxTerm ,
				  interestID
				FROM
				  loantypes  
				  LEFT JOIN  interestrates
					ON interestrates.productCode = loantypes.LoanCode
					AND interestrates.payment = loantypes.PaymentTerm
				WHERE LoanCode = '".$_POST['loancode']."' 
				  AND PaymentTerm = '".$_POST['method']."' 
				  and interestrates.active =1
				ORDER BY term ASC ";
	  $return['sql']= $sql;
	  $res = $this->db->query($sql);
	  $return['loancode'] = $_POST['loancode'];
	  $return['method'] = $_POST['method'];
	  
	  if($res->num_rows() > 0){
			foreach($res->result() as $r){
				$return['int'][] = array("term"=>$r->term,
								"interest"=>$r->interest,
								"minTerm"=>$r->minTerm,
								"maxTerm"=>$r->maxTerm,
								"interestID"=>$r->interestID
								); 
				if($r->term == '')
				$return['lastterm'] ='';
				else
				$return['lastterm'] = $r->term;
			}
	  }else{
		$return['lastterm'] = '';
		$return["json"] = "false";
	  }
	   
	  //$return["json"] = json_encode($return);
	  echo json_encode($return);
	}
	
}
?>