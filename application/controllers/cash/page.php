<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page extends CI_Controller {

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

	}
	
	function forms(){
		$page = $this->page;
		$page['subcontent'] = $this->uri->segment(4);
		$transid = $this->uri->segment(5);
		$page['transid'] = $transid;
		$id = $this->uri->segment(6);
		$page['id'] = $this->uri->segment(6);
		echo $id;
		echo $page['subcontent'] ;
		if($page['subcontent']  == "actualbalance"){
			$page['actualbal']=$this->Cashmodel->getbranchBank($id, $transid);
		}elseif($page['subcontent']  ==  "modifycollections"){
			$page['collection'] = $this->cash->getCollectionbyID($id);
		}elseif($page['subcontent'] == "modifydisbursements"){
			$page['collection'] = $this->cash->getCollectionbyID($id);
		}elseif($page['subcontent']  == "modifyadjustment"){
			$page['collection'] = $this->cash->getCollectionbyID($id);
		}elseif($page['subcontent']  == "modifyrecap"){
			echo $id;
			$page['collection'] = $this->cash->getRecapbyID($id);
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
		
		$this->load->view('cash/forms/'.$page['subcontent'], $page);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */