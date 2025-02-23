<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Calculator extends CI_Controller {

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
	
	public $page = array ( "pagetitle" => "Loan Calculator - Fruits Consulting Inc",
							"nav" => 'final/navheader',
							"template" => 'template/new/body',
							"menu" => 'final/sidemenu',
							"module" => "Header.Loans",
							);
	function __construct()
	{
	  parent::__construct();
	  $this->load->helper('security');	
	}
	
	public function index()
	{	
		$page = $this->page;	
		$page['header'] = $this->UserMgmt->getheader();
		$page["submod"] = "Loan Calculator";
		
		$page['content'] = $this->load->view('loans/calculator', $page, true);	
		$page['main'] = 'template/new/content';
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
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */