<?php
	$loans = $this->Loansmodel->getLoanDetails($loanid);
$loan = $loans['loaninfo']->row();
$client = $loans['clientinfo']->row();
$branch = $this->UserMgmt->branch($loan->branchID);

$emps = $branch['emps'];

if($emps->num_rows() > 0){
	foreach($emps->result() as $emp){
		if($emp->name == "Branch Manager")
		{
			$position = "Branch Manager";
			$mgr = $emp->firstname." ".$emp->lastname;
		}else if($emp->name == "General Manager" and strpos(strtolower($emp->firstname), "dummy") === false){
			$position = "General Manager";
			$mgr = $emp->firstname." ".$emp->lastname;
		}
	}
}

if(empty($position)){
	$position = "General Manager";
	$mgr = "Rebecca A. Rodelas";
}
	$tmpl = array ('table_open'          => '<table class="table  table-border  " >'); 
	$this->table->set_template($tmpl);		


	$data=array("PN"=>$loan->PN,
					"isdeleted <>"=>1);
	$cvres = $this->Loansmodel->getTransByPN($loan->PN, $loan->branchID);
	if($this->auth->perms('debug',$this->auth->user_id(),3) == true) { 
		//echo $loan->branchID;
		$cvres = $this->Loansmodel->getTransByPN($loan->PN, $loan->branchID);
		echo $this->db->last_query();
	 }
	$cv = $cvres->row();
	 if($this->auth->perms('debug',$this->auth->user_id(),3) == true) { 
		//echo $loan->branchID;
		/*echo "<pre>";
		print_r($cvres->result());
		echo "</pre>";*/
	 }
	$fees = $this->Loansmodel->getLoanFees($loanid);
	$fee = $fees->result();
	$agent = $this->UserMgmt->get_user_byid($loan->LoanProcessor);
	if($agent->num_rows() > 0 ){
		$a = $agent->row();
		$ag= $a->lastname.", ".$a->firstname;
	}
	$c = 0;
	$amount = strtoupper($this->loansetup->convert_number_to_words($cv->Amount_OUT));
	$explanation = $cv->explanation." PER PN No. : ".$cv->PN;
	$explanation .= "<br/><br/><p>NOTE:</p>";
	$explanation .= "<br/><br/><p>TOTAL PN AMOUNT : ".number_format($loan->approvedAmount,2)."</p>";
	$rcve = "Received from YUSAY CREDIT AND FINANCE CORPORATION the said amount as full/partial payment for the above explanation.";
	echo '<p align="center">'.strtoupper($loan->branchname).' BRANCH <br/>'.strtoupper($loan->branchaddress.", ".$loan->city).'</p>';
	echo '<h4 align="center">'.$formtitle.'</h4>';
	//$this->table->add_row("PAYEE", $client->LastName.", ".$client->firstName." ".$client->MiddleName, "DATE", date("d-M-y", strtotime($loan->DateDisbursed)));
	$this->table->add_row("PAYEE", $cv->Particulars, "DATE", date("d-M-y", strtotime($loan->DateDisbursed)));
	$this->table->add_row("Address", $client->address.", ".$client->barangay.", ".$client->cityname.", ".$client->provname, "CV No.", $cv->referenceNo);
	//$this->table->add_row(array("colspan"=>'4', "data"=>"" ));
	//echo  $this->table->generate();

	$this->table->add_row(array("colspan"=>'2', "align"=>"center", "data"=>"EXPLANATION" ), array("colspan"=>'2', "align"=>"center", "data"=>"AMOUNT" ));
	$this->table->add_row(array("colspan"=>'2', "data"=>$explanation ), array("colspan"=>'2', "align"=>"center", "data"=>number_format($cv->Amount_OUT,2) ));
	$this->table->add_row("<i><b>AMOUNT IN WORDS :</b></i>", "<i>".$amount." PESOS ONLY</i>", array("colspan"=>'2', "align"=>"center", "style"=>'font-weight: bold', "data"=>number_format($cv->Amount_OUT,2) ));
	$this->table->add_row("Bank/Branch : ".$cv->bankCode, "Check No. : ".$cv->Checkno, "PN No.", $cv->PN);
	echo  $this->table->generate();


	$this->table->add_row(array("width"=>"25%", "data"=>"Prepared By/Encoded By: "), array("width"=>"25%", "data"=>"Pre-Audited By:"),array("colspan"=>'2', "data"=>"Approved By:" ));
	$this->table->add_row(array("height"=>"50px", "valign"=>"bottom","data"=>$ag),array("valign"=>"bottom","data"=>"Auditor/Bookkeeper"),array("valign"=>"bottom","data"=>"Manager"),array("valign"=>"bottom","data"=>"COO"));
	$this->table->add_row(array("height"=>"70px","colspan"=>"2", "data"=>$rcve),array("colspan"=>"2", "valign"=>"bottom","align"=>"center", "data"=>"<ul>".$cv->Particulars."</ul>Signature Over Printed Name"));
	
	$this->table->add_row(array("colspan"=>'2', "data"=>"Account Title", "align"=>"center"), "DEBIT", "CREDIT");
	$this->table->add_row(array("colspan"=>'2', "data"=>$loan->productCode), array( "align"=>"right","data"=>number_format($loan->approvedAmount,2)), "");
	//FEES
	foreach($fee as $f){
		$this->table->add_row(array("colspan"=>'2', "data"=>$f->feeName),'',  array( "align"=>"right","data"=>number_format($f->value,2)));
		$c += $f->value;
	}
	$c += $cv->Amount_OUT;
	$this->table->add_row(array("colspan"=>'2', "data"=>"CIB"),'', array( "align"=>"right","data"=>number_format($cv->Amount_OUT,2)));
	$this->table->add_row(array("colspan"=>'2', "data"=>"TOTAL"),array( "align"=>"right", "style"=>'font-weight: bold',"data"=>'<b>'.number_format($loan->approvedAmount,2).'</b>'), array( "align"=>"right", "style"=>'font-weight: bold', "data"=>number_format($c,2)));
	echo  $this->table->generate();
		
?>
		