<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Overview extends CI_Controller {

	
	public $page = array ( "pagetitle" => "Clients - Fruits Consulting Inc",
							"nav" => 'final/navheader',
							"template" => 'template/new/body',
							"menu" => 'final/sidemenu',
							"module" => "Header.Clients");
							
	public $debug = false; // turn to false if live
	
	function __construct()
	{
	  parent::__construct();
	  $this->load->helper('security');	
	   $this->auth->restrict();
	     $this->load->library('Datatables');
	}
	
	public function index()
	{			
		$page = $this->page;		
		$page['submod'] = "Masterlist";
		$page['header'] = $this->UserMgmt->getheader();
		if(isset($_POST['name'])){
			$name = $_POST['name'];	
			$this->session->set_userdata('searchname', $name);		
		}
		
		$page['name'] = $this->session->userdata('searchname');
		//$page['main'] = 'cash/overview';
		$page['main'] = 'client/list';
		$this->load->view($page['template'], $page);
	}	
	
	
	public function getclient(){
		
		if($this->auth->perms("CMC All Branches", $this->auth->user_id(),2) != true)
		{
			$where = array("clientinfo.active"=>1,
								"branchID"=>$this->auth->branch_id());
		}else{
			$where = array("clientinfo.active"=>1);
		}		
	
		 $aColumns = array('ClientID', 'branchname', 'LastName', 'firstName', 'dateOfBirth');
		 
		 $this->db->select($aColumns);
		 $this->db->from('clientinfo');
		 $this->db->where($where);
		$this->db->join("branches", "branches.id = clientinfo.branchID");
		 $res = $this->db->get();
		 //echo $this->db->last_query();
		 
		 foreach($res->result_array() as $aRow)
        {
            $row = array();
            
            foreach($aColumns as $col)
            {
                $row[] = $aRow[$col];
				
            }    
            $output['data'][] = $row;
        }
    
        echo json_encode($output, JSON_HEX_QUOT | JSON_HEX_TAG);
	}
	public function getClients()
    {
        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('ClientID', 'LastName', 'firstName', 'dateOfBirth', 'address','active');
        
        // DB table to use
        $sTable = 'clientinfo';
        //
    
        $iDisplayStart = $this->input->get_post('iDisplayStart', true);
        $iDisplayLength = $this->input->get_post('iDisplayLength', true);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', true);
        $iSortingCols = $this->input->get_post('iSortingCols', true);
        $sSearch = $this->input->get_post('sSearch', true);
        $sEcho = $this->input->get_post('sEcho', true);
    
        // Paging
        if(isset($iDisplayStart) && $iDisplayLength != '-1')
        {
            $this->db->limit($this->db->escape_str($iDisplayLength), $this->db->escape_str($iDisplayStart));
        }
        
        // Ordering
        if(isset($iSortCol_0))
        {
            for($i=0; $i<intval($iSortingCols); $i++)
            {
                $iSortCol = $this->input->get_post('iSortCol_'.$i, true);
                $bSortable = $this->input->get_post('bSortable_'.intval($iSortCol), true);
                $sSortDir = $this->input->get_post('sSortDir_'.$i, true);
    
                if($bSortable == 'true')
                {
                    $this->db->order_by($aColumns[intval($this->db->escape_str($iSortCol))], $this->db->escape_str($sSortDir));
                }
            }
        }
        
        /* 
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        if(isset($sSearch) && !empty($sSearch))
        {
            for($i=0; $i<count($aColumns); $i++)
            {
                $bSearchable = $this->input->get_post('bSearchable_'.$i, true);
                
                // Individual column filtering
                if(isset($bSearchable) && $bSearchable == 'true')
                {
                    $this->db->or_like($aColumns[$i], $this->db->escape_like_str($sSearch));
                }
            }
        }
        
        // Select Data
		//$sql = 'select SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $aColumns)).' from '.$sTable;
		$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $aColumns)), false);
		$this->db->from($sTable);
		$where = array("active"=>1);
		$this->db->where($where);
        $rResult = $this->db->get();
    
	
		
        // Data set length after filtering
		
        $this->db->select('FOUND_ROWS() AS found_rows');
        $iFilteredTotal = $this->db->get()->row()->found_rows;
    
        // Total data set length
        $iTotal = $this->db->count_all($sTable);
    
        // Output
        $output = array(
            'sEcho' => intval($sEcho),
            'iTotalRecords' => $iTotal,
            'iTotalDisplayRecords' => $iFilteredTotal,
            'aaData' => array()
        );
        
        foreach($rResult->result_array() as $aRow)
        {
            $row = array();
            
            foreach($aColumns as $col)
            {
                $row[] = $aRow[$col];
				//echo $aRow[$col];
            }
    
            $output['aaData'][] = $row;
        }
    
        echo json_encode($output);
    }
	
	function addnew(){
		$page = $this->page;
		$page['header'] = $this->UserMgmt->getheader();
		$page['submod'] = "Add New Client";
		if($_POST){
			
			$this->clientvalidation();
			
		}else{
			//$page['form'] = 'user/loans/clientinfoform';
			$page['subcontent']="client/addclient";	
			//$page['subcontent']="client/loanapplication";			
			$page['main'] = 'cash/overview';
			$this->load->view($page['template'], $page);
		}
	}
	
	function name_exist(){
		$fname = $_POST['firstname'];
		$lname = $_POST['lname'];
		$pars = array('firstName'=>$fname,
					'LastName'=>$lname,
					'active'=>1);
					
		if($this->Loansmodel->fieldIn('clientinfo', $pars) == true)
		{
			$this->form_validation->set_message('name_exist', "Client Name already exists.");
			return false;
		}else{
			return true;
		}
	}
	
	function comakervalidation(){
		if(isset($_POST['clientid']))
			$clientid = $_POST['clientid'];
		else
			$clientid = $this->session->userdata('applicant_id');		
		
		if($_POST['loanid'] != ''){
			$loanid = $_POST['loanid'];
		}elseif($this->session->userdata('loanid') != NULL){
			$loanid = $this->session->userdata('loanid');
		}else{
			$loanid = '';	
		}
		
		if(isset($_POST['comakerid'])){
			$comakerid = $_POST['comakerid'];
		}elseif($this->session->userdata('comakerid') != NULL){
			$comakerid = $this->session->userdata('comakerid');
		}else{
			$comakerid = '';	
		}
		
		if($clientid == '' && $loanid == ''){
			$content = "Please complete Applicant's Loan Information ";
		}else{
			
			if($comakerid ==''){
				$client = $this->loansetup->addpersonalinfo();	
				if($client != false){
					//save to comaker
					$data = array("clientID"=>$client,
								"loanID"=>$loanid,
								"dateAdded"=>$this->auth->localtime(),
								"addedBy"=>$this->auth->user_id(),
								"active"=>1);
					$this->Loansmodel->addtotable("co_maker", $data);
					$content = "Comaker was added.";
				}else{
					$content = validation_errors();	
				}
			}else{
				$content = "Comaker information was updated.";
				$this->loansetup->update_clientinfo($comakerid);
			}
		}
		
		$footer = '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>';
		echo $this->form->modal($content, $footer);
	}
	
	function clientvalidation(){
		header("content-type:application/json");
		if($_POST){	
		
			if( $this->session->userdata('applicant_id'))
				$clientid = $this->session->userdata('applicant_id'); 
			else
				$clientid = $_POST['clientid'];
			//echo $clientid;
			if($clientid == ''){					
					$clientid = $this->loansetup->addpersonalinfo();
					if($clientid != false){
						
						$content='New client was added. Client ID = '.$clientid;	
						$footer = '<a href="'.base_url().'client/profile/'.$clientid.'" >Go to Profile</a>';
					}else{
						$content = validation_errors();
						$footer = '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>';						
					}
					
					//echo $this->form->modal($content, $footer);
					echo json_encode($content);
					
				//}
			}else{
				
				$content='<div class="alert alert-success">Client information was updated.</div>';
				$this->loansetup->update_clientinfo($clientid);
				if($this->input->post('civilstatus') != 'single' ){
					$_POST['info'] = 'spouse';
					$content .= $this->Clientmgmt->updateinfo();
				}
				$footer = '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>';
				//echo $this->form->modal($content, $footer);
				echo json_encode($content);
			
			}
		}
	}
	
	
	
	/*
	if($_POST['civilstatus'] != 'single'){
					if($this->loansetup->validate_spouse() == false){
						$clientid = false;
						$footer = '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>';
						echo $this->form->modal(validation_errors(), $footer);				
					}else{
						$clientid = $this->loansetup->addclientinfo();
					}
				}else{
					$clientid = $this->loansetup->addclientinfo();
				}				
				
				
					
				if($clientid != false){
					
					//add dependents
					echo $this->loansetup->add_dependents($clientid);					
						
					//add creditor
					echo $this->loansetup->add_creditor($clientid);
						
					$clientprofile = base_url() . "client/profile/".$clientid;
					$session = array("applicant_id"=>$clientid);
					$this->session->set_userdata($session);
						
					//$footer = '<a href="'.$clientprofile.'" class="btn btn-default btn-sm">Proceed to Client\'s Profile</a>';
					$footer = '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>';
					$content = '<div class="alert alert-success">New client was added.</div>';
					echo $this->form->modal($content, $footer);
						
				}else{
					$footer = '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>';
					$content = '<div class="alert alert-danger">Failed to add client info. Please try again.</div>';
					echo $this->form->modal($content, $footer);
				}	
	*/
	
	function financialinfo(){
		if(isset($_POST['clientid']))
				$clientid = $_POST['clientid'];
			else
				$clientid = $this->session->userdata('applicant_id');		
		
		if(empty($clientid)){			
			$content = "Please select/enter your client information first.";	
		}else{
		
			if(empty($financeinfo)){
				$finance = $this->loansetup->financeinfo($clientid);
				if($finance == true){					
					$content = "Financial Info was added successfully.";
				}else{
					$content = validation_errors();
				}
			}else{
				//update existing data
				//$finance = $this->loansetup->financeinfo();
				$content = "Financial Information will be updated.";
			}
			
		}
		echo $content;
		//$footer = '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>';
		//echo $this->form->modal($content, $footer);
	}
	
	function ledger(){
		$page = $this->page;		
		$page['submod'] = "Client Ledger";
		$page['header'] = $this->UserMgmt->getheader();
		$page['main'] = "client/ledger";
		$this->load->view($page['template'], $page);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */