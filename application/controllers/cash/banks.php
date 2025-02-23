<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Banks extends CI_Controller {
	function __construct()
	{
	  parent::__construct();
	  $this->auth->restrict();	
	}
	public $page = array ( "pagetitle" => "Banks - Fruits Consulting Inc",
							"nav" => 'final/navheader',
							"template" => 'template/new/body',
							"menu" => 'final/sidemenu',
							"module" => "Cash",
							"submod" => "Banks");
	function index(){
		$page = $this->page;
		$mod = $page['module'].".".$page['submod'];
		if($_POST){
			if($_POST['submit'] == "Add Bank"){
				if($this->auth->perms($mod, $this->auth->user_id(), 1) == true){
					if($this->cash->addBank() == true)
					$page['success'] = "New Bank was added.";
					else
					$page['error'] = "Please try again";
				}else{
					$page['error'] = "You have no permission to add a bank.";
				}
			}elseif($_POST['submit'] == 'Activate'){
				if($this->auth->perms($mod, $this->auth->user_id(), 3) == true){
					if(isset($_POST['checked'])){
						$this->cash->updateBankStatus(1);
					}
				}else{
					$page['error'] = "You have no permission.";
				}
			}elseif($_POST['submit'] == 'Deactivate'){
				if($this->auth->perms($mod, $this->auth->user_id(), 3) == true){	
					if(isset($_POST['checked'])){
						$this->cash->updateBankStatus(0);
					}
				}else{
					$page['error'] = "You have no permission.";
				}
			}
		}
		$page['header'] = $this->UserMgmt->getheader();
		$page['subcontent'] = 'cash/banks';
		$page['main'] = "cash/overview";
		$this->load->view($page['template'], $page);
	}
	
	function details(){
		$page = $this->page;
		$page['header'] = $this->UserMgmt->getheader();
		$page['main'] = 'cash/branches';
		$this->load->view($page['template'], $page);
	}
	
	
	function transactions(){
		$page = $this->page;
		$page['header'] = $this->UserMgmt->getheader();
		
		if(!isset($_POST['bank']))
		$bankID= NULL;
		else
		$bankID = $_POST['bank'];
		
		//echo $bankID;
		$page['banks'] = $this->Cashmodel->getbanklistonbranch($this->auth->branch_id());
		$tmpl = array ('table_open' => '<table class="table table-bordered table-hover" id="tableuser">' );
		$this->table->set_template($tmpl);
		$banks = $this->Cashmodel->getBankTransactions($bankID, $dateStart = NULL, $dateEnd = NULL);
		$page['content'] = '';
		$page['content'] .= $this->load->view('cash/forms/bankselection',$page, true);
		//$page['content'] .= '<p>'.$bankID.'</p>';
		//$str = $this->db->last_query();
		//$page['content'] .= $str;
		if($banks->num_rows() > 0){
			
			$this->table->set_heading("Type","Trans","PN","JV/OR/CV", "Checkno","Particulars","Bank", "Amount", "Date-of-CMC", "Date Posted");
			foreach($banks->result() as $bank){
				if($bank->Amount_IN != '')
				$amount = '<font color="green">'.number_format($bank->Amount_IN,2).'</font>';
				else
				$amount = '<font color="red">( '.number_format($bank->Amount_OUT,2).' )</font>';
				$this->table->add_row($bank->type, $bank->trans, $bank->PN,$bank->referenceNo, $bank->Checkno,$bank->particulars, $bank->bankCode, array("align"=>'right', "data"=>$amount), $bank->dateOfTransaction,$bank->dateAdd);
			}
			$page['content'] .= $this->table->generate();
		}else{
			$page['content'] .= "No transactions.";	
		}
		$page['main'] = 'template/new/content';
		$this->load->view($page['template'], $page);
	}
}
?>