<?php
	$loaninfo = $this->Loansmodel->getLoanbyID($loanid);
	$loan = $loaninfo->row();
	$product = $this->Loansmodel->getproductsbyID($loan->LoanType);
	$p = $product->row();
	$tmpl = array ('table_open'          => '<table class="table  table-border  " >'); 
	$this->table->set_template($tmpl);
	$data=array("PN"=>$loan->PNno,
					"isdeleted <>"=>1);
	$cvres = $this->Loansmodel->getTransByPN($loan->PN);
	$cv = $cvres->row();
	$fees = $this->Loansmodel->getLoanFees($loanid);
	$fee = $fees->result();
	$agent = $this->UserMgmt->get_user_byid($cv->addedBy);
	if($agent->num_rows() > 0 ){
		$a = $agent->row();
		$ag= $a->lastname.", ".$a->firstname;
	}
	$c = 0;
	$amount = strtoupper($this->loansetup->convert_number_to_words($cv->Amount_OUT));
	$explanation = $cv->explanation." PER PN No. : ".$cv->PN;
	$explanation .= "<br/><br/><p>NOTE:</p>";
	$explanation .= "<br/><br/><p>TOTAL PN AMOUNT : ".number_format($loan->approvedAmount,2)."</p>";
	$rcve = "Received from FRUITS CONSULTING INC the said amount as full/partial payment for the above explanation.";
	$tmpl = array ('table_open'          => '<table class="table  table-noborder  " >'); 
	$this->table->set_template($tmpl);
	
	echo "<h4>Loan Information</h4>";
	$this->table->add_row("Type of Loan : <b>".$loan->LoanType."</b>", "Date Applied : ".date("F d, Y",strtotime($loan->dateApplied)));
	$this->table->add_row("Amount Applied : <b>".$loan->appliedAmount."</b>", "Terms : ".$loan->Term);
	
	
	$comp = $this->loansetup->loancomputation($loan->approvedAmount,$loan->Term,$loan->LoanType, $loanid);
	
	$tmpl = array ('table_open'          => '<table class="table  table-border  " >'); 
	$this->table->set_template($tmpl);
	$this->table->add_row("NAME"."<br/><br/>".$loan->LastName.", ".$loan->firstName, "TYPE OF LOAN"."<br/><br/>".$p->LoanName, array("rowspan"=>"3", "data"=>$comp['table']));
	//$this->table->add_row($loan->LastName.", ".$loan->firstName,$p->LoanName);
	$this->table->add_row("ADDRESS"."<br/><br/>".$loan->address,"TERM"."<br/><br/>".$loan->Term." Months");
	//$this->table->add_row($loan->address,$loan->Term." Months");
	$this->table->add_row("Computed & Posted By: "."<br/><br/>".$ag,"Nature of Payment"."<br/><br/>MONTHLY");
	//$this->table->add_row(array("height"=>"40px","data"=>$ag),"MONTHLY");	
	echo  $this->table->generate();
	
	$this->table->add_row(array("width"=>'33%',"data"=>"Verified By"),array("width"=>'33%',"data"=>"Approved By"), array("width"=>'33%',"data"=>"Paid By"));
	$this->table->add_row(array("height"=>'40px',"data"=>""),"", "");
	echo  $this->table->generate();
?>
		