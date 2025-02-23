<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Clients {

	function __construct(){
		$this->ci =& get_instance();
		$this->ip_address = $this->ci->input->ip_address();
		if (!class_exists('CI_Session'))
		{
			$this->ci->load->library('session');
		}
		if (!class_exists('UserMgmt'))
		{
			$this->ci->load->model('usermgmt', 'usermgmt', TRUE);
		}
	}

	function get_clients($search,$start, $limit){
		if(!empty($search))
		{
			$list = str_replace(" ", '%',$search);
			$this->db->where( "CONCAT( firstname,  ' ', LastName )",'%'.$list.'%' );
		}
		if(!empty($start))
		{
			$this->db->limit($limit, $start);
		}
		
		$this->db->from('clientinfo');
		$this->db->get();
	}
	
	function getclientByID($id){
		$data = array("ClientID"=>$id);
		$client = $this->ci->Loansmodel->get_data_from("clientinfo",$data);
		$tmpl = array ('table_open'          => '<table class="table table-bordered table-condensed table-hover">' );
		$this->ci->table->set_template($tmpl);
		$content = '<div class="modal-dialog modal-lg">';
		$content .= '<form class="form-horizontal" method="post" id="actualbal" action="" id="actualbalform">';
		$content .= '<div class="modal-content">';
		$content .= '<div class="modal-header">';
		$content .= '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Client Information</h4>';
		$content .= '</div>';		
		if($client->num_rows() > 0){
			
			$cli = $client->row();			
			$firstname = $cli->firstName;
			$lastname = $cli->LastName;
			$bday = $cli->dateOfBirth;
			if($bday == "0000-00-00") 
			$bday = "none - (<i>update record</i>)";
			else
			$bday = date("F d, y", strtotime($bday));
			$add = $cli->address;
			$gender = $cli->gender;
			$civil = $cli->civilStatus;
			$cno = $cli->ClientID;
			
			$content .= '<div class="modal-body">';
				$content .= '<div class="form-group">';
						$content .= '<div class="col-md-4">';
						$content .= "Name: ".$lastname.", ".$firstname." <br/>";
						$content .= "Birthday: ".$bday."<br/>";
						$content .= "Address: ".$add."<br/>";
						$content .= "Gender: ".$gender."<br/>";
						$content .= "Civil Status: ".$civil."<br/>";
					$content .= '</div>';
					$content .= '<div class="col-md-8">';
						
							
							$this->ci->table->set_heading('PN', 'Status', 'Type', 'Term/MOP','Rel Date', 'Maturity Date', 'Amount', 'Cur Balance');
							$data = array("CNO" => $cno);
							$loans = $this->ci->Loansmodel->getLoans($data);
							if($loans->num_rows() > 0){
								$closed = 0;
								$current = 0;
								$pastdue =0;
									foreach ($loans->result() as $loan){
										if($loan->CURBAL == 0){
											$closed++;
											$stat = "closed";
										}elseif($loan->CURBAL > 0 and ($loan->MATDATE < $this->ci->auth->localdate())){
											$pastdue++;
										$stat = "pastdue";
										}else{
											$current++;
										$stat = "current";
										}
											$this->ci->table->add_row($loan->PN,$stat, $loan->LoanCode, $loan->NMONS,$loan->DOPEN, $loan->MATDATE, number_format($loan->AMTGRANT,2), $loan->CURBAL);
									}									
									$content .= '<div class="list-group">'; 
									$content .= '<li class="list-group-item"><b>Loans</b></li>';
										if($current >0)
										$content .= '<a href="#" class="list-group-item"><span class="badge">'.$current.'</span>Current Loans</a>';
										if($pastdue >0)
										$content .= '<a href="#" class="list-group-item"><span class="badge">'.$pastdue.'</span>Past Due Loans</a>';
										if($closed >0)
										$content .= '<a href="#" class="list-group-item"><span class="badge">'.$closed.'</span>Closed Loans</a>';
									$content .= '</div>'; 								
									
							}			
							//$content .= $this->ci->table->generate();
					$content .= '</div>';
				$content .= '</div>';
			$content .= '</div>';
		}else{
		}
		$content .= '<div class="modal-footer">';
		$content .= '<a class="btn btn-sm btn-success" data-dismiss="modal">Add New Loan</a>';
		$content .= '<a class="btn btn-sm btn-default" data-dismiss="modal">Close</a>';
		$content .= "</div>";
		return $content .= '</div></form></div>';
	}
	
	function clientLoansfromOld($clientID){
		$this->ci->table->set_heading('PN', 'Status', 'Type', 'Term/MOP','Rel Date', 'Maturity Date', 'Amount', 'Cur Balance', 'Action');
		$data = array("CNO" => $clientID);
		$loans = $this->ci->Loansmodel->getLoans($data);
		
		if($loans->num_rows() > 0){
			$closed = 0;
			$current = 0;
			$pastdue =0;
			
			foreach ($loans->result() as $loan){
				
				if($loan->CURBAL == 0){
					$closed++;
					$stat = "closed";
				}elseif($loan->CURBAL > 0 and ($loan->MATDATE < $this->ci->auth->localdate())){
					$pastdue++;
					$stat = "pastdue";
				}else{
					$current++;
					$stat = "current";
				}
				if($stat != 'closed' )
				$this->ci->table->add_row($loan->PN,$stat, $loan->LoanCode, $loan->NMONS,$loan->DOPEN, $loan->MATDATE, number_format($loan->AMTGRANT,2), number_format($loan->CURBAL,2), '<a href="'.base_url().'client/profile/'.$clientID.'/">View</a>' );
			
			}
				$content = '<div class="list-group">';
				$content .= '<li class="list-group-item"><b>Loans</b></li>';
				if($current >0)
					$content .= '<a href="#" class="list-group-item"><span class="badge">'.$current.'</span>Current Loans</a>';
				if($pastdue >0)
					$content .= '<a href="#" class="list-group-item"><span class="badge">'.$pastdue.'</span>Past Due Loans</a>';
				if($closed >0)
					$content .= '<a href="#" class="list-group-item"><span class="badge">'.$closed.'</span>Closed Loans</a>';
				$content .= '</div>'; 								
			
			return $this->ci->table->generate();
		}else{
			
			return "No active loans.";
		}
	}
} 
?>