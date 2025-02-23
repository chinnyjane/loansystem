<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup extends CI_Controller {

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
	
	public $page = array("pagetitle" => "Fruits Consulting Inc",
							"template" => 'template/new/body',
							"menu" => 'template/setupmenu',
							"module" => 'Product',
							"submod" => 'Setup');
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
		$page['main']="user/loans/setup";		
		$this->load->view($page['template'], $page);		
	}
	
	public function products()
	{	
		$page = $this->page;
		//CREATE PRODUCT
		if($this->uri->segment(4) == 'create'){
			if($_POST)
			{
				$p['postdetails'] = $_POST;
					if($this->debug == true)
					$this->load->view('template/postdetails',$p);				
				
				$this->form_validation->set_rules('pcode', "Product Code", "required");
				$this->form_validation->set_rules('pname', "Product Name", "required");
				$this->form_validation->set_rules('pdesc', "Product Description", "required");
				if($this->form_validation->run() ==false)
				{
					
				}else{
					$page['errors']= $this->Loansmodel->add_products($_POST['pcode'],$_POST['pname'],$_POST['pdesc']);
				}	
			}
			$page['main']="user/loans/createproducts";
		}
		//PRODUCT DETAILS MODIFICATION PAGE
		elseif($this->uri->segment(4) == 'details'){
			$page['pid'] = $this->uri->segment(5);
			if($_POST){
				$p['postdetails'] = $_POST;
					//if($this->debug == true)
					//$this->load->view('template/postdetails',$p);
				$this->form_validation->set_rules("pcode", "Product Code", "required|callback_is_pcode_exist");
				$this->form_validation->set_rules('pname', "Product Name", "required");
				$this->form_validation->set_rules('pdesc', "Product Description", "required");
				if($this->form_validation->run() != false){
					$hash = md5($_POST['pid'].substr($this->auth->localtime(), 0,10).$this->auth->user_id());
					$p['post'] = array("loancode"=>$_POST['pcode'],
								"loanname"=>$_POST['pname'],
								"loandescription"=>$_POST['pdesc'],
								"dateModified"=>$this->auth->localtime(),
								"modifiedBy" => $this->auth->user_id(),
								"active" => 1,
								"inlineHash" => $hash);
				if($this->Loansmodel->update_products($p['post'],$_POST['pid']) == true)
				$page['errors'] = "Loan Product was updated";
				else
				$page['errors']="Please try again";
				}
			}	
			$page['main']="user/loans/editproducts";
		}
		//LIST OF PRODUCTS activate/deactivate
		else{			
			if($_POST){
				$p['postdetails'] = $_POST;
				if($this->debug == true)
				$this->load->view('template/postdetails',$p);
				if( count($_POST['checked']) > 0){
					if($_POST['submit'] == "Activate") $active=1;
						else $active = 0;
					$hash="";
					foreach($_POST['checked'] as $pid){
						//echo $pid."=".$active."<br/>";
						$hash = md5($pid.$active.substr($this->auth->localtime(), 0,10).$this->auth->user_id());
							//echo $hash;
						$p['post'] = array("active"=> $active,
									"dateModified"=>$this->auth->localtime(),
									"addedBy" => $this->auth->user_id(),
									"inlineHash" => $hash);
						if($this->Loansmodel->update_products($p['post'],$pid)==true)
						$page['errors'] = "Loan Product was deactivated. ".$pid;
						else
						$page['errors'] = "<br/>Loan Product is still active. ".$pid;
					}
				}else
					$page['errors'] = "No selected product";
			}
			$page['main']="user/loans/products";
		}
				
		$this->load->view($page['template'], $page);		
	}
	
	
	function is_pcode_exist(){
		$pars = array("loancode"=>$_POST['pcode'],
							"loantypeID !=" => $_POST['pid']);
		if($this->Loansmodel->fieldIn("loantypes",$pars) == TRUE){
			$this->form_validation->set_message("is_pcode_exist","Product Code is being used already");
			return false;
		}else
		return true;
	}
	
	public function fees()
	{	
		$page = $this->page;	
			$page = $this->page;
		if($this->uri->segment(4) == 'create'){
			if($_POST)
			{
				$p['postdetails'] = $_POST;
					if($this->debug == true)
					$this->load->view('template/postdetails',$p);					
				$this->form_validation->set_rules("feename", "Fee Name", "required");
				$this->form_validation->set_rules('value', "Value", "callback_is_money_multi|required");
				if($this->form_validation->run() !=false)
				{				
					//add fee to DB
					$page['errors'] = $this->Loansmodel->add_loanfee($_POST);
				}
				
			}
			$page['main']="user/loans/createfee";
		}else
		$page['main']="user/loans/fees";		
		$this->load->view($page['template'], $page);		
	}
	
	public function computation()
	{	
		$page = $this->page;	
			$page = $this->page;
		if($this->uri->segment(4) == 'create'){
			if($_POST){
				if($_POST['submit'] == "Add Method"){
					$this->form_validation->set_rules("compname","Computation Name", "required|callback_is_method_exist");
					$this->form_validation->set_rules("method", "Computation Method", "required");
					if($this->form_validation->run() != false){
							$page['errors']="Add computation to DB";
					}
				}
			}
			$page['main']="user/loans/createcomputation";
		}else		
		$page['main']="user/loans/computation";		
		$this->load->view($page['template'], $page);		
	}
	
	function is_method_exist(){
		$pars = array("compname"=>$this->input->post("compname"));
		if($this->Loansmodel->fieldIn("computationmethod", $pars ) == true)
		{
			$this->form_validation->set_message("is_method_exist", "Computation Name already exist. Please enter other Computation Name");
			return false;
		}else return true;
	}
	
	public function banks()
	{	
		$page = $this->page;
		if($this->uri->segment(4) == 'create'){
			if($_POST){
				if ($this->loansetup->addbank()== true)
				$page['success'] = "New Bank was added.";
			}
			$page['main']="user/loans/createbank";
		}
		else				
		$page['main']="user/loans/banks";		
		$this->load->view($page['template'], $page);		
	}
	
	
	public function requirements()
	{	
		$page = $this->page;		
		$page['main']="user/loans/requirements";		
		$this->load->view($page['template'], $page);		
	}
	
	function is_money_multi($input, $params) {   
		@list($thousand, $decimal, $message) = explode(',', $params);
		$thousand = (empty($thousand) || $thousand === 'COMMA') ? ',' : '.';
		$decimal = (empty($decimal) || $decimal === 'DOT') ? '.' : ',';
		$message = (empty($message)) ? 'The money field is invalid' : $message;

		$regExp = "/^\s*[$]?\s*((\d+)|(\d{1,3}(\{thousand}\d{3})+)|(\d{1,3}(\{thousand}\d{3})(\{decimal}\d{3})+))(\{decimal}\d{2})?\s*$/";
		$regExp = str_replace("{thousand}", $thousand, $regExp);
		$regExp = str_replace("{decimal}", $decimal, $regExp);

		$ok = preg_match($regExp, $input);
		if(!$ok) {
			$CI =& get_instance();
			$CI->form_validation->set_message('is_money_multi', $message);
			return FALSE;
		}
		return TRUE;
	}
	
	public function updateloanstatus($stat){
		$table = "loanapplication";
		$data = array("");
		
		$this->Loansmodel->update_data($table, $id, $data);
	}
	
	
	public function updaterequirements(){
		if($_POST){
			$loanid = $_POST['loanid'];
			$this->loansetup->updateSubmittedReqs($loanid);			
			echo "Loan Requirements was submitted.";
		}
	}
	
	
	public function computeloan(){
		
		if ($_POST){
			
			${loan} = $_POST['loan'];
			${terms} = $_POST['terms'];
			
			//check if loan is new, renewal, addition, extenstion
			$loanstatus = $_POST['loanstatus'];
			
			//check if loan is monthly or lumpsum
			
			//check loan type
			$loanCode = $_POST['loantype'];
			
			
		}
		
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */