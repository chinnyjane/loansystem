<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Branches extends CI_Controller {
	function __construct()
	{
	  parent::__construct();
	  $this->auth->restrict();	
	}
	public $page = array ( "pagetitle" => "Branches - Fruits Consulting Inc",
							"nav" => 'final/navheader',
							"template" => 'template/new/body',
							"menu" => 'final/sidemenu',
							"module" => "Cash",
							"submod" => "Branches and Banks");
	function index(){
		$page = $this->page;
		$page['active']= "Cash.Branches";
		$page['link'] = "cash/branches/";
		
			if($_POST){
				if($_POST['action'] == "Add Branch"){
					$this->input->post(NULL, true);
					$this->form_validation->set_rules('branch', "Branch","trim|required|xss_clean");
					$this->form_validation->set_rules('address', "Address", "trim|required|xss_clean");
				}
				elseif($_POST['action'] == "Remove Branch(es)"){
					if($this->auth->updateBranchStatus() == true)
					$page['success'] = "Branch Status was updated.";
				}
			}else{
			}
			
			$page['main'] = 'settings/user/branch';
		
		
		$page['header'] = $this->UserMgmt->getheader();
		///$page['main'] = 'cash/overview';
		
		$this->load->view($page['template'], $page);
	}
	
	function details(){
		$page = $this->page;
		$page['branchid'] = $this->uri->segment(4);
		if($page['branchid'] =='')
		$page['branchid'] = $this->auth->branch_id();
		//echo $page['branchid'];
		if($_POST){
			if(isset($_POST['submit']))
				$post = $this->cash->updateBranchBankStatus();
				if(isset($post['success']))
				$page['success'] = $post['success'];
				if(isset($post['error']))
				$page['error'] = $post['error'];
		}
		
		//branches
		$table = "branches";
		$where = array("id"=>$page['branchid']);
		$page['branch'] = $this->Loansmodel->get_data_from($table, $where);
		
		//list of Banks
		$wherelist = array("active" => 1);
		$page['bankslist'] = $this->Loansmodel->get_data_from('banks', $wherelist);
		
		//list of bank on Branch
		$wherebank = array("branchID"=>$page['branchid']);
		$page['banks'] = $this->Cashmodel->getbanklistonbranch($page['branchid']);
		
		$page['header'] = $this->UserMgmt->getheader();
		$page['main'] = 'cash/overview';
		$page['subcontent'] = 'cash/branchdetails';
		$this->load->view($page['template'], $page);
	}
	
	function update(){
	echo '<pre>';
					print_r($_POST);
					echo '</pre>';
		if($_POST){
			switch ($_POST['submit']) {
				case "Remove": 
					//remove bank
					echo '<pre>';
					print_r($_POST);
					echo '</pre>';
					
				break;
				case "Activate":
					//activate bank
				break;
				case "Deactivate";
					//deactivate bank
				break;
			}
		}
	}
	
	function accountExist(){
		return $this->cash->accountExist();
	}
	function accountExistexept(){
		return $this->cash->accountExistexept();
	}
	
	function checktrans(){
		
	}
	
	function bankAccount(){
		$page = $this->page;
		$page['branchid'] = $this->uri->segment(4);
		$page['bankAccount'] = $this->uri->segment(5);	

		if($_POST){
			if($_POST['submit'] == "Update Bank"){
				if($this->cash->updateBranchbank() == true)
				$page['success']="Bank Account was updated successfully";
			}
		}
		//list of Banks
		$wherelist = array("active" => 1);
		$page['bankslist'] = $this->Loansmodel->get_data_from('banks', $wherelist);
		
		//branches
		$table = "branches";
		$where = array("id"=>$page['branchid']);
		$page['branch'] = $this->Loansmodel->get_data_from($table, $where);
		
		$page['header'] = $this->UserMgmt->getheader();
		$page['main'] = 'cash/overview';
		$page['subcontent'] = 'cash/bankupdate';
		$this->load->view($page['template'], $page);
	}
	
}
?>