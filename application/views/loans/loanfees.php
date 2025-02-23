<?php 
$clientid = $this->session->userdata('clientid');
$pid = $this->session->userdata('loantype');
$pension = $this->Loansmodel-> get_pensioninfo($clientid);
if($pension->num_rows() > 0){
 $disabled = 'disabled';
 $readonly = "readonly";
	foreach($pension->result() as $p){
		$pensiontype = $p->PensionType;
		$pensionNum = $p->PensionNum;
		$monthly = $p->monthlyPension;
		$pstatus = $p->PensionStatus;
		$bank = $p->BankID;
		$bankacct = $p->Bankaccount;
		$bankBranch = $p->bankBranch;
	}
}else{
$disabled = '';
 $readonly = "";
 $pensiontype = '';
		$pensionNum = '';
		$monthly = 0;
		$pstatus = '';
		$bank = '';
		$bankacct = '';
		$bankBranch = '';
}
 ?>
 <div class="panel-body">
 <div class="row form-group">	 
	 <div class="col-md-6" id="feedetails">
		<div class='alert alert-warning'>Input loan amount and terms.</div>
	 </div>
 </div>
 </div>
 