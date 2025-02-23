<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Update extends CI_Controller {

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
	public $page = array ( "pagetitle" => "Cash Movement - Fruits Consulting Inc",
							"nav" => 'final/navheader',
							"template" => 'final/template',
							"menu" => 'final/sidemenu',
							"module" => "Cash",
							"submod"=> "Cash.Transactions");
	function __construct()
	{
	  parent::__construct();
	  $this->load->helper('security');
	
	}
	
	public function index()
	{
		redirect(base_url());
	}
	
	function balanceonbank(){
		if($_POST){
			$this->form_validation->set_rules("amountbalance", "Actual Balance on Bank", "required|trim|is_numeric|xss_clean");
			if($this->form_validation->run() != false){
				$data = array("actualBalance"=>$_POST['amountbalance']);
				$where = array("branchBankID"=>$_POST['branchBankID'],
										"transID"=>$_POST['transid'],
										"dateOfTransaction"=>$_POST['transdate']);
					if($this->Loansmodel->get_data_from('banksummary', $where)->num_rows > 0){
						if($this->Loansmodel->update_data('banksummary', $where, $data) == true){
						//$this->db->last_query();
						//$this->output->enable_profiler(TRUE);
							//echo "true";
						$stat = true;
						}else{ $stat = false;
							//echo "false";
						}
					}else{
						if($this->UserMgmt->insert_data_to("banksummary",$where) != false)
						return true;
						else return false;
					}
			}
		}
		redirect(base_url()."cash/daily/transaction/".$_POST['transid']."/update/".$stat);
	}
	
	function verifystatus(){
		$banktrans = $this->uri->segment(4);
		$data = $this->cash->verifyEachTrans($banktrans);
		if($data == true)
		echo 'ok';
		else
		echo "no ok";
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */