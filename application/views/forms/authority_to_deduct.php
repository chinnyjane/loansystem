<?php
	$loaninfo = $this->Loansmodel->getLoanbyID($loanid);
	$loan = $loaninfo->row();
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

?>

<p>Date: <u><?php echo $this->auth->localdate();?></u></p>
<p>TO WHOM IT MAY CONCERN:</p>
		