<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daily extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->auth->restrict();
	}
	public $page = array ( "pagetitle" => "Cash Movement - Fruits Consulting Inc",
							"nav" => 'final/navheader',
							"template" => 'final/template',
							"menu" => 'final/sidemenu',
							"module" => "Cash",
							"submod"=> "Cash.Transactions");
							
	function index(){
		$page = $this->page;
		if(isset($_POST['date']))
			$date=$_POST['date'];
		else $date = $this->auth->localdate();
		
		$page['date'] = $date;
		//if($this->auth->holiday($date) == false)
		//$this->cash->transactions($date);
		$branch = $this->auth->branch_id();
		$page['branch'] = $branch;
		if($_POST){
			if(isset($_POST['lock'])){
				if(count($_POST['lock']) > 0)
					if($this->lock() == true)
					$page['success'] = "Transaction was locked.";
			}			
			if(isset($_POST['fromdate'])){
				$page['date'] = array("dateTransaction >=" => $_POST['fromdate'],
							"dateTransaction <="=> $_POST['todate'],
							"branchID"=>$branch);
			}else $page['date']  ='';	
		}else{
			$page['date']  ='';		
		}
			
		
		$page['header'] = $this->UserMgmt->getheader();
		$page['subcontent'] = 'cash/daily';
		$page['main'] = "cash/overview";
		$page['branchname'] = $this->auth->branchname();
		
		$branch = $this->auth->branch_id();
		//$page['trans'] = $this->cash->getTransaction($date, $branch);
		
		$this->load->view($page['template'], $page);
	}
	
	
	function createtransaction(){
		$page = $this->page;
		//echo "sdasda";
		//check opened transaction. if exists, user cannot open new transaction
		
			
			if($this->cash->checkOpenTrans($this->auth->branch_id()) == true){
				$page['error'] = "You still have opened transactions. ";
				$page['header'] = $this->UserMgmt->getheader();
				$page['subcontent'] = 'cash/daily';
				$page['main'] = "cash/overview";
				$page['branchname'] = $this->auth->branchname();
				$date = '';
				$branch = $this->auth->branch_id();
				$page['trans'] = $this->cash->getTransaction($date, $branch);
				$this->load->view($page['template'], $page);
			}else{
			//create Transaction
			$trans = $this->cash->startTransaction($this->auth->branch_id());
			if($trans != false)
			redirect(base_url()."cash/daily/transaction/".$trans);
			} 
		
	}
	
	function transaction(){
		$page = $this->page;
		$transid = $this->uri->segment(4);
		$page['action'] = $this->uri->segment(5);
		$page['actionid'] = $this->uri->segment(6);
		$page['transid'] = $transid;
		
		if($_POST){
				if(isset($_POST['update'])){
					if($this->cash->updateCMCStatus() == true){
					if( $this->auth->perms("debug", $this->auth->user_id(), 3) == true){ 
						echo "<pre>";
						print_r($_POST);
						echo "</pre>";
					echo $this->cash->updateCMCStatus();
					}
					echo "Ok";
					}else{ 
					
						echo "Please try again";
					
					}
					exit();
				}
				
			if(isset($_POST['submit'])){
				switch ($_POST['submit']) {
					case 'Add Collection':
						if($this->cash->addCollection($transid) == true)
						$status = "New Collection was added";
						break;
					case 'Add Disbursement':
						if($this->cash->addDisbursement($transid)==true)
						$status = "New Disbursement was added";
						break;
					case 'Add Adjustment':
						if($this->cash->addAdjustment($transid)==true)
						$status = "New Adjustment was added";
						break;
					case "Add Bank" :
						$status = $this->cash->addBanktoBranch($transid);
						break;
					case "Add Deposit" :
						$status = $this->cash->addDeposit($transid);
						break;
				}
			}
			
			
			
			
			if(isset($status)){
				if($status != false) $page['success'] = $status;
			}
		}		
		$page['branchname'] = $this->auth->branchname();
		$branch = $this->auth->branch_id();
		
		//list of Banks
		$wherelist = array("active" => 1);
		$page['bankslist'] = $this->Loansmodel->get_data_from('banks', $wherelist);
		
		$data = array("transID"=>$transid);
		$page['cmctrans'] = $this->Loansmodel->get_data_from('cmctransaction', $data);
		if($page['cmctrans']->num_rows() > 0){
		//$page['banktrans'] = $this->Loansmodel->get_data_from('bankstransactions', $data);		
			
		foreach($page['cmctrans']->result() as $tr){
			$page['transdate'] = $tr->dateTransaction;
			$page['opendate'] = $tr->dateOpen;
			$page['closedate'] = $tr->dateClose;
			$page['closedid'] = $tr->closedBy;
			$page['verifydate'] = $tr->dateVerified;
			$page['approvedate'] = $tr->dateApproved;
			$page['cmcstatus'] = $tr->status;
			$page['branchid'] = $tr->branchID;
			$page['verifyid'] = $tr->verifiedBy;
			$page['approvedby'] = $tr->approvedBy;
			if(!empty($page['closedid'] )){
				$verifiedby=$this->UserMgmt->get_user_byid($page['closedid']);
				$verify = $verifiedby->row();
				$page['closedBy'] = $verify->lastname.", ".$verify->firstname;
			}
			if(!empty($page['verifyid'] )){
				$verifiedby=$this->UserMgmt->get_user_byid($page['verifyid']);
				$verify = $verifiedby->row();
				$page['verifiedby'] = $verify->lastname.", ".$verify->firstname;
			}
			if(!empty($page['approvedby'] )){
				$verifiedby=$this->UserMgmt->get_user_byid($page['approvedby']);
				$verify = $verifiedby->row();
				$page['approvedby'] = $verify->lastname.", ".$verify->firstname;
			}
		}
		
		$page['recap'] = $this->Cashmodel->getrecap($transid);
		$page['banks'] = $this->Cashmodel->getbanklistonbranch($page['branchid']);
		//branches
		$table = "branches";
		$where = array("id"=>$page['branchid']);
		$page['branch'] = $this->Loansmodel->get_data_from($table, $where);
			
			$page['header'] = $this->UserMgmt->getheader();
			$page['subcontent'] = 'cash/transactions';
			$page['main'] = "cash/overview";
			$this->load->view($page['template'], $page);
		}else
			$this->index();
	}
	
	function report(){
		$page = $this->page;
		$transid = $this->uri->segment(4);
		$page['transid'] = $transid;
		//$page['template']= "final/reporttemplate";
		$page['template'] = "template/new/cmcreport";
		$page['formtitle'] = "Daily Cash Movement";
		$page['branchname'] = $this->auth->branchname();
		$branch = $this->auth->branch_id();
		
		//list of Banks
		$wherelist = array("active" => 1);
		$page['bankslist'] = $this->Loansmodel->get_data_from('banks', $wherelist);
		
		$data = array("transID"=>$transid);
		$page['cmctrans'] = $this->Loansmodel->get_data_from('cmctransaction', $data);
		if($page['cmctrans']->num_rows() > 0){
		//$page['banktrans'] = $this->Loansmodel->get_data_from('bankstransactions', $data);		
			
		foreach($page['cmctrans']->result() as $tr){
			$page['transdate'] = $tr->dateTransaction;
			$page['opendate'] = $tr->dateOpen;
			$page['closedate'] = $tr->dateClose;
			$page['verifydate'] = $tr->dateVerified;
			$page['cmcstatus'] = $tr->status;
			$page['closedid'] = $tr->closedBy;
			$page['branchid'] = $tr->branchID;
			$page['verifyid'] = $tr->verifiedBy;
			$page['approvedby'] = $tr->approvedBy;
			$page['approvedate'] = $tr->dateApproved;
			if(!empty($page['closedid'] )){
				$verifiedby=$this->UserMgmt->get_user_byid($page['closedid']);
				$verify = $verifiedby->row();
				$page['closedBy'] = $verify->lastname.", ".$verify->firstname;
			}
			if(!empty($page['verifyid'] )){
			$verifiedby=$this->UserMgmt->get_user_byid($page['verifyid']);
			$verify = $verifiedby->row();
			$page['verifiedby'] = $verify->lastname.", ".$verify->firstname;
			}
			if(!empty($page['approvedby'] )){
				$verifiedby=$this->UserMgmt->get_user_byid($page['approvedby']);
				$verify = $verifiedby->row();
				$page['approvedby'] = $verify->lastname.", ".$verify->firstname;
			}
		}
		$page['recap'] = $this->Cashmodel->getrecap($transid);
		$page['banks'] = $this->Cashmodel->getbanklistonbranch($page['branchid']);
		//branches
		$table = "branches";
		$where = array("id"=>$page['branchid']);
		$page['branch'] = $this->Loansmodel->get_data_from($table, $where);
			
			$page['header'] = $this->UserMgmt->getheader();
			$page['main'] = 'cash/reports';
			$param = 'utf-8';
				$format = "Folio";
				$orientation = "P";
				$mgl=10;
				$mgr=10;
			//$page['main'] = "cash/overview";
			//$this->load->view($page['template'], $page
			
			
			//$this->load->helper(array('dompdf', 'file'));
			 // page info here, db calls, etc.     
			$html = $this->load->view($page['template'], $page, true);
			$size = "letter";
			$orientation = "portrait";
			
			
			$this->load->library('pdf');
			$pdf = $this->pdf->load($format, $orientation, $mgl, $mgr);
			
			$string = "YCFC";
			$write = true;
			
			
			 $footer = "<footer><hr/>Generated: ".$this->auth->localtime();
			 $footer .=  "&nbsp; | &nbsp;";
			 $footer .= "Printed by : ".$this->auth->fullname(); 
			 $footer .= "&nbsp; | &nbsp; page : {PAGENO}/{nbpg}</footer>"; 
			
			$pdf ->SetHTMLFooter($footer);
			$pdf->WriteHTML($html); // write the HTML into the PDF
			$pdf->Output();
		}else
			$this->index();
	}
	
	function lock(){
		foreach ($_POST['lock'] as $transid=>$openlock){
			$openlock = strtolower($openlock);
			if($this->cash->openlocktransaction($transid, $openlock))
			return true;
			else return false;
		}		
	}
	
	function truncate(){
		$page = $this->page;
		if($this->auth->perms($page['submod'],$this->auth->user_id(),4) == true)
		{
			//truncate transaction table
			$this->Cashmodel->truncate_table('bankstransactions');
			$this->Cashmodel->truncate_table('banksummary');
			$this->Cashmodel->truncate_table('cmctransaction');
			redirect($this->index());
		}
	}
	
	function update(){
		$page = $this->page;
		$trans = $this->uri->segment(4);
		$page['id'] = $this->uri->segment(5);
		if($_POST){
			if($_POST['submit']== "Update Collection")
				$status = $this->cash->UpdateCollection($this->uri->segment(5));		
			elseif($_POST['submit']== "Update Disbursement"){
				$status = $this->cash->updateDisbursement($this->uri->segment(5));
			}elseif($_POST['submit']== "Update Adjustment")
				$status = $this->cash->updateAdjustment($this->uri->segment(5));
			elseif($_POST['submit']== "Update Deposit")
				$status = $this->cash->updateRecap($this->uri->segment(5));
		}		
		
		if($trans == 'recap')
		$page['collection'] = $this->cash->getRecapbyID($this->uri->segment(5));
		else
		$page['collection'] = $this->cash->getCollectionbyID($this->uri->segment(5));
		
		$coll = $page['collection']->row();
		if(!isset($coll->TransID))
		$transid = $coll->transID;
		else
		$transid = $coll->TransID;
		
		if(isset($status) and $status == true){
			//$page['success'] = "Transaction was updated."."<a href='".base_url()."cash/daily/transaction/".$transid."'>Back to Transaction</a>";
			redirect(base_url()."cash/daily/transaction/".$transid."/update/".$status);
		}
		
		
		$data = array("transID"=>$transid);
		$page['cmctrans'] = $this->Loansmodel->get_data_from('cmctransaction', $data);
		if($page['cmctrans']->num_rows() > 0){
		//$page['banktrans'] = $this->Loansmodel->get_data_from('bankstransactions', $data);		
			
		foreach($page['cmctrans']->result() as $tr){
			$page['transdate'] = $tr->dateTransaction;
			$page['opendate'] = $tr->dateOpen;
			$page['cmcstatus'] = $tr->status;
			$page['branchid'] = $tr->branchID;
		}
		$page['banks'] = $this->Cashmodel->getbanklistonbranch($page['branchid']);
		}
		
		$page['header'] = $this->UserMgmt->getheader();
		if($trans == "collection")
		$page['subcontent'] = 'cash/forms/modifycollections';
		elseif($trans == "disbursement")
		$page['subcontent'] = 'cash/forms/modifydisbursements';
		elseif($trans == "adjustment")
		$page['subcontent'] = 'cash/forms/modifyadjustment';
		elseif($trans == "recap")
		$page['subcontent'] = 'cash/forms/modifyrecap';
				
		$page['main'] = "cash/overview";
		$page['branchname'] = $this->auth->branchname();
		
		$branch = $this->auth->branch_id();
		//$page['trans'] = $this->cash->getTransaction($date, $branch);
		
		$this->load->view($page['template'], $page);
	}
	
	function remove(){
		if($this->cash->removeTrans($this->uri->segment(5)) == true)
		$remove = true;
		else $remove = false;
		
		redirect(base_url()."cash/daily/transaction/".$this->uri->segment(4)."/remove/".$remove);
		
	}
	
	function reverse(){
		if($this->cash->reverseTrans($this->uri->segment(5)) == true)
		$remove = true;
		else $remove = false;
		
		redirect(base_url()."cash/daily/transaction/".$this->uri->segment(4));
		
	}
	
	function recap(){
		if($this->uri->segment(5) == "remove"){
				if($this->cash->removeRecap($this->uri->segment(6)) == true)
			$remove = true;
			else $remove = false;
		}		
		redirect(base_url()."cash/daily/transaction/".$this->uri->segment(4)."/remove/".$remove);
		
	}
	
}
?>