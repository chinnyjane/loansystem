<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class NewApplication extends CI_Controller {

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
							"template" => 'template/green',
							"menu" => 'template/loanmenu');
	function __construct()
	{
	  parent::__construct();
	  $this->load->helper('security');	
	  $this->auth->restrict();
	}
	
	public function index(){
		echo "hello";
	}
	/*
	public function index()
	{	
		$page = $this->page;
		if($_POST){			
			if($_POST['firstname'])
			$page['main']="user/loans/pensioninfo";
		}else{
			$page['main']="user/loans/application";
		}
		$this->load->view($page['template'], $page);		
	}*/
	
	public function newloan(){
		$page = $this->page;
		$url = $this->uri->segment(4);
		if($_POST){
			
			if($_POST['submit'] == 'PersonalInfo')
			{
				if( $this->loansetup->validate_clientinfo()  != false){
					redirect(base_url().'loans/application/newloan/pensioninfo');
				}
			}else if($_POST['submit'] == 'pensioninfo'){
				if( $this->loansetup->addpensioninfo() != false)
				redirect(base_url().'loans/application/newloan/loaninfo');
			}elseif($_POST['submit'] == 'Compute Net Proceeds'){
				$this->form_validation->set_rules('pension', "Monthly Pension", "callback_money_multi|required");
				$this->form_validation->set_rules('loanapplied', "Loan Applied", "callback_money_multi|required");
				$this->form_validation->set_rules("terms", "Terms of Payment", "less_than[25]|required");
				if($this->form_validation->run() != false){
					$terms = $this->input->post("terms");
					$loanamount = $this->input->post("loanapplied");
					$pension = $this->loansetup->monthlypension();
					$monthly = $loanamount/$terms;
					$excess = 0;
					if($monthly > $pension)
					$page['errors'] = "Loan amount cannot be paid by your monthly Pension. ";
									
					$excess = $pension-$monthly;
					//interest
					IF($terms<=12)
						$int = (0.02*$terms)*$loanamount;
					else 
						$int = ((0.02*12)+(($terms-12)*0.01)) *$loanamount;
						
					//$servicefee
					$servicefee = number_format("400",2);
					
					//RFPL
					$rfpl = $loanamount/1000*1.5*$terms;
					
					//ATM
					$atm = 15*$terms;
					
					//notarial
					$notarial = 100;
					
					$totalcharges = $int+$servicefee+$rfpl+$atm+$notarial;
					$net = $loanamount - $totalcharges;
					
					$page['notarial'] = number_format($notarial,2);
					$page['totalcharges'] = number_format($totalcharges,2);
					$page['net'] = number_format($net,2);
					$page['rfpl'] = number_format($rfpl,2);
					$page['atm'] = number_format($atm,2);
					$page['monthly'] = number_format($monthly,2);
					$page['servicefee'] = $servicefee;
					$page['int'] = $int;
					$page['excess'] = number_format($excess,2);
				}
			}elseif($_POST['submit'] == 'Submit Loan Info'){
				//add to DB
			}
		}
		//vars
		$page['gen']="disabled";
		$page['peninfo']="disabled";
		$page['loaninfo']="disabled";
		$page['loanreq']="disabled";
		$page['ci']="disabled";
		$page['app']="disabled";
		$page['client'] = $this->loansetup->clientid();
		
		if(empty($url)){
			$page['gen']="active";
			$page['form'] = 'user/loans/clientinfoform';
		}elseif($url == "loaninfo"){
			$page['form'] = 'user/loans/loaninfo';		
			$page['loaninfo']="active";
			$page['gen']="";
			$page['peninfo']="";
		}elseif($url == "pensioninfo"){
			if(!isset($page['client']))
				redirect($this->newloan);
			$page['form'] = 'user/loans/pensioninfo';		
			$page['peninfo']="active";
			$page['gen']="";			
		}
		$page['main']="user/loans/loaninfoform";
		$this->load->view($page['template'], $page);		
	}
	
	public function form(){
		$page = $this->page;
		$page['main']="user/loans/clientinfoform";
		$this->load->view($page['template'], $page);		
	}
	
	public function money_multi($input, $params) {   
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
			$CI->form_validation->set_message('money_multi', $message);
			return FALSE;
		}
		return TRUE;
	}
	
	function compute(){
			$this->form_validation->set_rules('pension', "Monthly Pension", "callback_money_multi|required");
			$this->form_validation->set_rules('loanapplied', "Loan Applied", "callback_money_multi|required");
			$this->form_validation->set_rules("terms", "Terms of Payment", "less_than[25]|required");
			if($this->form_validation->run() != false){
				$terms = $this->input->post("terms");
				$loanamount = $this->input->post("loanapplied");
				$pension = $this->input->post("pension");
				$monthly = $loanamount/$terms;
				if($monthly > $pension)
				$page['errors'] = "Loan amount cannot be paid by your monthly Pension. ";
								
				$excess = $pension-$monthly;
				//interest
				IF($terms<=12)
					$int = (0.02*$terms)*$loanamount;
				else 
					$int = ((0.02*12)+(($terms-12)*0.01)) *$loanamount;
					
				//$servicefee
				$servicefee = number_format("400",2);
				
				//RFPL
				$rfpl = $loanamount/1000*1.5*$terms;
				
				//ATM
				$atm = 15*$terms;
				
				//notarial
				$notarial = 100;
				
				$totalcharges = $int+$servicefee+$rfpl+$atm+$notarial;
				$net = $loanamount - $totalcharges;
				
				$page['notarial'] = number_format($notarial,2);
				$page['totalcharges'] = number_format($totalcharges,2);
				$page['net'] = number_format($net,2);
				$page['rfpl'] = number_format($rfpl,2);
				$page['atm'] = number_format($atm,2);
				$page['monthly'] = number_format($monthly,2);
				$page['servicefee'] = $servicefee;
				$page['int'] = $int;
				$page['excess'] = number_format($excess,2);
			}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */