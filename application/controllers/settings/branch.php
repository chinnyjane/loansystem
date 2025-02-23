<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Branch extends CI_Controller {

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
	public $page = array ( "pagetitle" => "Branches - Fruits Consulting Inc",
							"nav" => 'final/navheader',
							"template" => 'template/new/body',
							"menu" => 'final/sidemenu',
							"module" => "Settings",
							"submod" => "Branches",
							"active" => "Settings.User.Branch");
	
	public function index()
	{
		$page = $this->page;
		$page['active']=  "Settings.User.Branch";
		$page['link'] = "settings/user/branch/";
		//if($this->auth->perms("Settings.User.Branch",$this->auth->user_id(),2) == TRUE){
			if($_POST){
				if($_POST['action'] == "Add Branch"){
					$this->input->post(NULL, true);
					$this->form_validation->set_rules('branch', "Branch","trim|required|xss_clean");
					$this->form_validation->set_rules('address', "Address", "trim|required|xss_clean");
				}
				elseif($_POST['action'] == "Delete Branch(es)"){
					if($this->auth->updateBranchStatus() == true)
					$page['success'] = "Branch Status was updated.";
				}
			}else{
			}
			
			$page['main'] = 'settings/user/setbranch';
		//}else{
			//$page['main'] = 'notallowed';
		//}		
		$page['header'] = $this->UserMgmt->getheader();
		//$page['main'] = 'settings/overview';
		
		$this->load->view($page['template'], $page);
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