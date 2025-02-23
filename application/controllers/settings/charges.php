<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Charges extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	 function __construct()
	{
	  parent::__construct();
	  $this->auth->restrict();	
	}
	public $page = array ( "pagetitle" => "Product Charges - Fruits Consulting Inc",
							"nav" => 'final/navheader',
							"template" => 'template/new/body',
							"menu" => 'final/sidemenu',
							"module" => "Settings",
							"submod" => "Charges",
							"active" => "Settings.Charges");
	
	public function index()
	{
		$page = $this->page;
		$page['main'] = 'settings/overview';
		
		$page['content'] = $this->load->view('settings/charges', '',true);
		
		$page['header'] = $this->UserMgmt->getheader();
				
		$this->load->view($page['template'], $page);
	}
	
	function action(){
		header("content-type:application/json");
		$action = $this->uri->segment(4);
		switch($action){
			case 'add':
				$this->form_validation->set_rules('charge_name', "Charge Name", "required|xss_clean|callback_charge_exist");
				if($this->form_validation->run() === FALSE){
					$msg['msg'] = validation_errors();
					$msg['stat'] = false;
					
				}else{
					$data = array("charge_name"=>$_POST['charge_name'],
										"charge_type"=>$_POST['charge_type'],
										"date_added"=>$this->auth->localtime(),
										"added_by"=>$this->auth->user_id(),
										"active"=>1);
					if($this->Products->addCharges($data) == true)
					{
						$msg['msg'] = "New Charges was added.";
						$msg['stat'] = true;
					}else{
						$msg['msg'] = "error.";
						$msg['stat'] = false;
					}
					
				}
				echo json_encode($msg);
			break;
			case 'update':
				
				$data = array("charge_name"=>$_POST['charge_name'],
										"charge_type"=>$_POST['charge_type'],
										"date_modified"=>$this->auth->localtime(),
										"modified_by"=>$this->auth->user_id()
										);
				$where = array("id"=>$_POST['id']);
				if($this->Products->UpdateCharges($data,$where) == true)
				{
					$msg['msg'] = "Charges was updated.";
					$msg['stat'] = true;
				}else{
					$msg['msg'] = "error.";
					$msg['stat'] = false;
				}
				echo json_encode($msg);
			break;
			
			case 'delete':
				$msg['msg'] = "error.";
				$msg['stat'] = false;
				echo json_encode($msg);
			break;
			
		}
	}
	
	function update(){
		$ch['id'] = $action = $this->uri->segment(4);
		$cha = $this->Products->getChargeByID($ch['id']);
		if($cha->num_rows() > 0){
			foreach($cha->result() as $c){
				$ch['cname'] = $c->charge_name;
				$ch['ctype'] = $c->charge_type;
			}
			
		}else{
				$ch['cname'] = '';
				$ch['ctype'] = '';
		}
		$this->load->view('settings/editcharges', $ch);
	}
	
	function charge_exist(){
			$data = array("charge_name"=>$_POST['charge_name'],
									"active"=>1);
			if($this->Loansmodel->fieldIn("loan_charges",$data) ==true){
				$this->form_validation->set_message("charge_exist","Charges already exists.");
				return false;
			}else return true;
	}
	
	function details(){
		$page = $this->page;
		$page['branchid'] = $this->uri->segment(4);
		$page['submod'] = "Branch Details";
		$page['header'] = $this->UserMgmt->getheader();
		$page['main'] = 'settings/branchview';
		
		if($_POST){
			$brid = $page['branchid'];
			$pars = array("id <>"=>$brid,
							"branchname"=>$this->input->post('branchname'));
			if($this->Loansmodel->fieldIn("branches",$pars) == true){
				$page['msg']="Branch name already exists.";
				$page['status']=false;
			}else{
				$data = array("branchname"=>$this->input->post('branchname'),
									"province"=>$this->input->post('province'),
									"city"=>$this->input->post('city'),
									"address"=>$this->input->post('address'),
									"dateModified"=>$this->auth->localtime(),
									"modifiedBy"=>$this->auth->user_id());
				$where = array("id"=>$brid);
				$this->UserMgmt->updateBranch($where, $data);
				$page['msg'] = "Branch details was updated.";
				$page['status']=true;
			}
		}
		
		$data = $page['branchid'];
		$page['branch'] = $this->UserMgmt->branch($data);	
		
		$wherelist = array("active" => 1);
		$page['bankslist'] = $this->Loansmodel->get_data_from('banks', $wherelist);		
		
		$this->load->view($page['template'], $page);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */