<button class="btn  btn-primary btn-sm"  data-toggle="modal" data-target="#pension"><i class="fa fa-plus-circle"></i> New Pension</button>
<hr/>
<?php 
$id = $this->session->userdata('clientid');
$loantype = $this->session->userdata('loantype');

if($id == $clientid)
echo "&nbsp; <a href='".base_url()."loans/new'>Continue to Loan Application</a>";
$c = $client->row();
$cno = $c->CNO;
$tmpl = array ('table_open'          => '<table class="table  table-condensed table-hover " >',
			'thead_open' => '<thead class="header">'	); 
$this->table->set_template($tmpl); 

$pension = $this->Loansmodel->get_pensionofclient($clientid, $cno, $c->branchID);
//echo $this->db->last_query();
if($pension->num_rows() > 0){
 $disabled = 'disabled';
 $readonly = "readonly";
 
	foreach($pension->result() as $p){
		if(!isset($p->bankCode))
		$bankcode = '';
		else
		$bankcode = ($p->bankCode ? $p->bankCode : '');
	//echo $p->bankID;
		//$this->table->add_row($p->PensionType, $p->PensionNum, number_format($p->monthlyPension,2), $p->pensionDate,   $bankcode, $p->Bankaccount, $p->bankBranch,'<a href="'.base_url()."client/profile/".$clientid."/pension/".$p->PensionID.'">View</a> &nbsp; <a href="'.base_url()."client/profile/".$clientid."/pension/".$p->PensionID.'">Remove</a>');
		$monthly = ($p->monthlyPension ? $p->monthlyPension : 0);
		$this->table->add_row($p->PensionType, $p->PensionNum, number_format($monthly,2),   $bankcode, $this->numbers->ordinal($p->pensionDate), $p->bankBranch,'<a href="'.base_url()."client/profile/".$clientid."/pension/".$p->PensionID.'">View</a>');
	}
	//$this->table->set_heading("Pension", "Pension Account", "Monthly", "Date of Pension","Status", "Bank", "Bank Account", "Branch", "Action" );
	$this->table->set_heading("Pension", "Pension ID #", "Monthly",  "Bank", "Date of Withdrawal", "Branch", "Action" );
	echo $this->table->generate();
}
 ?>

<div class="modal fade" id="pension" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
	<?php 
	$pen['clientid'] = $clientid;
	$this->load->view('loans/forms/addpension', $pen); ?>
	</div>
</div>