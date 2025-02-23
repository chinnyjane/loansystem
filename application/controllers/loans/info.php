<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Info extends CI_Controller {

	public $page = array("pagetitle" => "Fruits Consulting Inc",
							"nav" => 'final/navheader',
							"template" => 'final/formtemplate',
							"menu" => 'template/loanmenu',
							"module" => "Loans");
							
	public $debug = false; // turn to false if live
	
	function __construct()
	{
	  parent::__construct();
	  $this->load->helper('security');	
	   $this->auth->restrict();
	}
	
	public function index()
	{			
		$this->load->view('client/loaninfo');		
	}	
	
	function ci(){
		$loanid = $this->uri->segment(4);
		
		if(!empty($loanid)){
			echo "hello";	
		}else{
			redirect(base_url()."loans/status/ci");
		}
	}
	
	function summary(){
		$page['loanid'] = $this->uri->segment(4);
		$this->load->view("loans/summary", $page);
	}
	
	function pension(){
		if($_POST){
			if($this->loansetup->addpensioninfo() == true)
			echo "Pension Information was saved.";
			else  echo validation_errors();
		}else echo "Please try again.";
	}
	
	function form(){
		$page = $this->page;
		$form = $this->uri->segment(2);
		$loanid = $this->uri->segment(3);
		$page['comaker'] = $this->uri->segment(4);
		$print =null;
		//if(!isset($print))
		$page['template'] = "template/new/report";
		$page['loanid'] = $loanid;
		$mgl=15;
		$mgr=15;
		$mgt = 10;
		
		switch ($form){
		
			case 'rfplmonitoring':				
				$page['formtitle'] = "RFPL MONITORING SHEET - PENSION LOAN";
				$page['main'] = 'forms/rfpl';
				$param = 'utf-8';
				$format = "Letter";
				$orientation = "P";
				$footer = "<footer><hr/>Generated: ".$this->auth->localtime();
			 $footer .=  "&nbsp; | &nbsp;";
			 $footer .= "Printed by : ".$this->auth->fullname().'</footer>'; 
						
			break;
			
			case 'rfplagreement':
				$page['template'] = "template/new/legalform";
				$page['formtitle'] = "RESERVE FUND FOR PENSION LOAN (RFPL) AGREEMENT";
				$page['main'] = 'forms/rfplagreement';
				$param = 'utf-8';
				$format = "Folio";
				$orientation = "P";
				$footer = '';
			break;
			
			case 'promissory':
				$page['template'] = "template/new/legalform";
				$page['formtitle'] = "PROMISSORY NOTE";
				$page['main'] = 'forms/promissory';
				$param = '"utf-8", "Folio-L"';
				$format = "Folio";
				$orientation = "P";
				$footer = '';
			break;
			
			case 'checkvoucher':
				$page['formtitle'] = "CHECK VOUCHER";
				$page['main'] = 'forms/checkvoucher';
				$param = 'utf-8';
				$format = "Letter";
				$orientation = "P";
				$footer = "<footer><hr/>Generated: ".$this->auth->localtime();
			 $footer .=  "&nbsp; | &nbsp;";
			 $footer .= "Printed by : ".$this->auth->fullname().'</footer>'; 
			
			
			break;
			
			case 'disclosure':
				//$page['template'] = "template/new/legalform";
				$page['formtitle'] = "DISCLOSURE STATEMENT OF LOAN/CREDIT TRANSACTION<br/>(SINGLE PAYMENT OR INSTALLMENT PLAN)";
				$page['main'] = 'forms/disclosure';
				$param = '"utf-8", "Folio-L"';
				$format = "Folio";
				$orientation = "P";
				$footer = '';
			break;
			
			case 'application':
				$page['formtitle'] = "LOAN APPLICATION";
				$page['main'] = 'forms/loanapplication';
				$param = '"utf-8", "Folio-L"';
				$format = "Folio";
				$orientation = "P";
				$footer = "<footer><hr/>Generated: ".$this->auth->localtime();
				$footer .=  "&nbsp; | &nbsp;";
				$footer .= "Printed by : ".$this->auth->fullname().'</footer>'; 			
				//$footer = '';
			break;
			
			case 'ledger':
				$page['formtitle'] = "LEDGER CARD";
				$page['main'] = 'forms/ledgercard';
				$param = '"utf-8", "Folio-L"';
				$format = "Letter";
				$orientation = "P";
				$footer = "";
				$mgl=10;
				$mgr=10;
				$mgt = 5;
			break;
			
			case 'comaker':
				$page['formtitle'] = "CO-MAKER'S STATEMENT";
				$page['main'] = 'forms/comaker';
				$param = '"utf-8", "Folio-L"';
				$format = "Folio";
				$orientation = "P";
				$footer = "<footer><hr/>Generated: ".$this->auth->localtime();
				$footer .=  "&nbsp; | &nbsp;";
				$footer .= "Printed by : ".$this->auth->fullname().'</footer>'; 			
			break;
			
			case 'computation':
			
				$page['formtitle'] = "COMPUTATION SHEET";
				$page['main'] = 'forms/computation';
				$param = 'utf-8';
				$format = "Folio";
				$orientation = "P";
				//$footer = "<footer><hr/>Generated: ".$this->auth->localtime();
				// $footer .=  "&nbsp; | &nbsp;";
				 $footer = '';
				
				
			break;
			
			case 'planalysis':
				$clientid = $this->uri->segment(4);
				$client = $this->Clientmgmt->getclientinfoByID($clientid);
				$page['client']=$client;
				$page['formtitle'] = "PL Analysis";
				$page['main'] = 'loans/planalysis';
				$param = '"utf-8", "Folio-L"';
				$format = "Folio-L";
				$orientation = "L";
				$footer = "<footer><hr/>Generated: ".$this->auth->localtime();
				 $footer .=  "&nbsp; | &nbsp;";
				 $footer .= "Printed by : ".$this->auth->fullname().'</footer>'; 
				
				
			break;
		}
	
		if($print==null){
			$this->load->helper(array('dompdf', 'file'));
			 // page info here, db calls, etc.     
			$html = $this->load->view($page['template'], $page, true);
			$size = "letter";
			//$orientation = "portrait";
			
			
			$this->load->library('pdf');
			$pdf = $this->pdf->load($format, $orientation, $mgl, $mgr, $mgt);
			
			$string = "YCFC";
			$write = true;
			
			$pdf ->SetHTMLFooter($footer);
			 
			$pdf->WriteHTML($html); // write the HTML into the PDF
			$pdf->Output();
		}else{	
			
		}
	
	}
	
	function popup(){
		$page = $this->page;
		$form = $this->uri->segment(3);
		$loanid = $this->uri->segment(4);
		$print =$this->uri->segment(5);
		$page['loanid'] = $loanid;
		$page['template'] = "template/new/body";
		
		switch ($form){
		
			case 'rfplmonitoring':
				$page['formtitle'] = "RFPL MONITORING SHEET - PENSION LOAN";
				$page['main'] = 'forms/rfpl';
				$param = 'utf-8';
				$format = "Letter";
				$orientation = "P";
				
			break;
			
			case 'promissory':
				$page['formtitle'] = "PROMISSORY NOTE";
				$page['main'] = 'forms/promissory';
				$param = '"utf-8", "Folio-L"';
				$format = "Folio";
				$orientation = "P";
				
			break;
			
			case 'checkrelease':
				$page['main'] = 'forms/checkrelease';
				$footer = '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>';
				$footer .= '<button type="submit" class="btn btn-success btn-sm"  data-toggle="release">Release</button>';
				$param = 'utf-8';
				$format = "Letter";
				$orientation = "P";
				$this->load->view($page['main'], $page);
			break;
			
			case 'disclosure':
				$page['formtitle'] = "DISCLOSURE STATEMENT OF LOAN/CREDIT TRANSACTION<br/>(SINGLE PAYMENT OR INSTALLMENT PLAN)";
				$page['main'] = 'forms/disclosure';
				$param = '"utf-8", "Folio-L"';
				$format = "Folio";
				$orientation = "P";
			
			break;
			
			case 'application':
				$page['formtitle'] = "LOAN APPLICATION";
				$page['main'] = 'forms/plapplication';
				$param = '"utf-8", "Folio-L"';
			break;
			
			case 'computation':
				$page['formtitle'] = "COMPUTATION SHEET";
				$page['main'] = 'forms/computation';
				$param = 'utf-8';
				$format = "Letter";
				$orientation = "P";
			break;
		}
		
			
			//$page['template'] = "template/new/reporthtml";
			//$html = $this->load->view($page['template'], $page, true);			
			//echo $this->form->modallg($html, $footer);
			$modalid = 'finalform';
			$formtitle = "Check Release";
			$posturl= 'loans/action/release';
			//echo $this->form->modalform_open($modalid, $posturl, $formtitle);
			//echo $html;
			//secho $this->form->modalformclose($footer);
	}
	
	
	function update(){
		
		if($_POST){
			
			switch($_POST['updateinfo']){
				
				case 'comaker':
					header("content-type:application/json");
					$com = $_POST['comakerID'];
					
					if(count($com) > 0){
							foreach($com as $c){
								$data = array('active'=>'0');
								$where = array("comakerID"=>$c);
								$this->Loansmodel->update_data("co_maker", $where, $data);								
							}
							$r['stat'] = true;
							$r['msg'] = 'Comaker list was updated.';
					}else{
						$r['stat'] = false;
						$r['msg'] = 'Nothing to update.';
					}						
					echo json_encode($r);
				break;
			}
		}
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */