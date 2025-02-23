<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Keywords extends CI_Controller {
	function __construct()
	{
	  parent::__construct();
	  $this->auth->restrict();	
	}
	public $page = array ( "pagetitle" => "Control Panel - Keywords - Fruits Consulting Inc",
							"nav" => 'final/navheader',
							"template" => 'template/new/body',
							"menu" => 'final/sidemenu',
							"module" => "Settings",
							"submod" => "Keywords",
							"active" => "Keywords");
	function index(){
		$page = $this->page;		
		$page['main'] = 'settings/keywords/list';
		//$page['main'] = 'settings/holidays/list';
		$this->load->view($page['template'], $page);
	}
	
	function addkeyword(){	
		$this->form_validation->set_rules("keyword", "Keyword", "required|xss_clean|callback_keyword_exist");
		$this->form_validation->set_rules("description", "Keyword description", "required|xss_clean");
		
		if($this->form_validation->run() == true){
			$data = array("keyword"=>$_POST['keyword'],
						"description"=>$_POST['description'],
						"active"=>1,
						"addedBy"=>$this->auth->user_id(),
						"dateAdded"=>$this->auth->localtime());
			if($this->loansmodel->addtotable('keywords', $data) == true){
				$content = "Keyword was added successfully.";
				$footer = '<a href="'.base_url().'settings/keywords"  class="btn btn-default btn-sm" >Close</a>';
				echo $this->form->modal($content, $footer);	
			}else{
				$content = "Error encoutered on inserts. Please try again.";
				$footer = '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>';
				echo $this->form->modal($content, $footer);
			}
		}else{
			$content = validation_errors();
			$footer = '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>';
			echo $this->form->modal($content, $footer);
		}
	}
	
	function keyword_exist(){
		$pars = array("keyword"=>$_POST['keyword'],
					"active"=>1);
		
		if($this->Loansmodel->fieldIn("keywords",$pars) == true){
			$this->form_validation->set_message("keyword_exist", "Keyword already exists.");
			return false;
		}else return true;
	}
}
?>