<?php
	$loaninfo = $this->Loansmodel->getLoanbyID($loanid);
	$loan = $loaninfo->row();
	$client = $this->Clientmgmt->getclientinfoByID($loan->ClientID);
	$c = $client->row();	
	echo "<pre>";
	print_r($c);
	echo "</pre>";
	$product = $this->Loansmodel->getproductsbyID($loan->LoanType);
	$p = $product->row();
	
	$dependents =  $this->Clientmgmt->getdependents($loan->ClientID);
	$creditor =  $this->Clientmgmt->getcreditor($loan->ClientID);
	$tmpl = array ('table_open'          => '<table class="table  table-border  " >'); 
	$this->table->set_template($tmpl);
	$data=array("PN"=>$loan->PNno,
					"isdeleted <>"=>1);
	$cvres = $this->Loansmodel->getTransByPN($loan->PN);
	
	$fees = $this->Loansmodel->getLoanFees($loanid);
	$fee = $fees->result();
	
	if($cvres->num_rows() > 0){
		$cv = $cvres->row();
		if($cv->addedBy != '')
		$agent = $this->UserMgmt->get_user_byid($cv->addedBy);
		if($agent->num_rows() > 0 ){
		$a = $agent->row();
		$ag= $a->lastname.", ".$a->firstname;
		
		$amount = strtoupper($this->loansetup->convert_number_to_words($cv->Amount_OUT));
	$explanation = $cv->explanation." PER PN No. : ".$cv->PN;
	$explanation .= "<br/><br/><p>NOTE:</p>";
	$explanation .= "<br/><br/><p>TOTAL PN AMOUNT : ".number_format($loan->approvedAmount,2)."</p>";
	}
	}
	
	switch($p->PaymentTerm){
		case 'M':
			$pterm = "Monthly";
		case 'L':
			$pterm = "Lumpsum";
	}
	
	//$c = 0;
	
	$rcve = "Received from FRUITS CONSULTING INC the said amount as full/partial payment for the above explanation.";
	$tmpl = array ('table_open'          => '<table class="table  table-border  " >'); 
	$this->table->set_template($tmpl);
	
	echo "<h4>Loan Information</h4>";
	$this->table->add_row("Amount Applied : <bold>".number_format($loan->AmountApplied,2).' - '.strtoupper($p->computation)." </bold>", "Type of Loan : <b>".strtoupper($p->LoanCode)."</b>", "Date Applied : ".date("F d, Y",strtotime($loan->dateApplied)));
	$this->table->add_row(array("colspan"=>'2',"data"=>"Amount in words: ".strtoupper($this->loansetup->convert_number_to_words($loan->AmountApplied))." PESOS ONLY"), "Terms : ".$loan->Term. " months"." - ".$pterm);	
	echo $this->table->generate();
	
	echo "<h4>Personal Information</h4>";
	$this->table->add_row("<small>Last Name:</small><br/><br/>".$loan->LastName, "<small>First Name: </small><br/><br/>".$loan->firstName,"<small>Middle Initial(s):</small><br/><br/>".$loan->MiddleName, "<small>Date of Birth: </small><br/><br/>". date("F d, Y", strtotime($loan->dateOfBirth)));
	$this->table->add_row("<small>Age:</small><br/><br/>". $this->loansetup->get_age($loan->dateOfBirth)." yrs. old" , "<small>Gender:</small><br/><br/>". strtoupper($loan->gender), "<small>Civil Status:</small><br/><br/>".$loan->civilStatus,"<small>Contact Number:</small><br/><br/>".$loan->contact);
	$this->table->add_row("Province:<br/><br/>".strtoupper($c->provname), "<small>City: </small><br/><br/>".strtoupper($c->cityname), array("colspan"=>"2", "data"=>"<small>Address:</small><br/><br/>".strtoupper($c->address.", Brgy. ".$c->barangay)));
	echo $this->table->generate();
	
	$spouse=  $this->Clientmgmt->getspouse($loan->ClientID);
	
	
	if($spouse->num_rows() > 0)	{
		$spouse = $spouse->row();
		if($loan->civilStatus != 'single'){
		if ($spouse->dateOfBirth == '0000-00-00'){
			$sbday = '';
			$sage = '';
		}else{
			$sbday = date("F d, Y", strtotime($spouse->dateOfBirth));
			$sage = $this->loansetup->get_age($spouse->dateOfBirth) ." yrs. old";
		}
		
		echo "<h4>Spouse Information</h4>";
		$this->table->add_row("<small>Last Name:</small><br/><br/>".$spouse->lastname, "<small>First Name: </small><br/><br/>".$spouse->firstname,"<small>Middle Initial(s):</small><br/><br/>".$spouse->middlename, "<small>Date of Birth: </small><br/><br/>".$sbday );
		$this->table->add_row("<small>Age:</small><br/><br/>". $sage , "<small>Occupation:</small><br/><br/>". strtoupper($spouse->occupation), "<small>Salary:</small><br/><br/>".$spouse->salary,"<small>Contact:</small><br/><br/>".$spouse->contact);
		echo $this->table->generate();
		}
	}
	echo "<h4>Collateral Information</h4>";
	//echo $this->loansetup->pensioninfo($loan->pensionID);
	echo $this->Loansmodel->getcollaterals($loan->LoanCode,$loan->LoanType,$loan->pensionID);
	
	echo "<h4>Dependents</h4>";
	 $this->table->add_row("Name", "Date of Birth", "Age");
			 if($dependents->num_rows() >0){
				
				foreach ($dependents->result() as $dep){
					$this->table->add_row($dep->firstname." ".$dep->middlename." ".$dep->lastname, date("F d, Y", strtotime($dep->dateOfBirth)), $this->loansetup->get_age($dep->dateOfBirth));
				}
				echo $this->table->generate();
			 }else{
				$this->table->add_row("&nbsp; ", " ", " ");
				$this->table->add_row("&nbsp; ", " ", " ");
				$this->table->add_row(" &nbsp;", " ", " ");
				echo $this->table->generate();
			}
			
	echo "<h4>Credit Obligations</h4>";		
	$this->table->add_row("Name", "Address", "Amount", "Remarks");
			 if($creditor->num_rows() >0){				
				foreach($creditor->result() as $cre){
					$this->table->add_row($cre->name, $cre->address, $cre->amount, $cre->remarks);
				}
				echo $this->table->generate();
			 }else{
				$this->table->add_row("&nbsp; ", " ", " ", " ");
				$this->table->add_row("&nbsp; ", " ", " ", " ");
				$this->table->add_row(" &nbsp;", " ", " ", " ");
				echo $this->table->generate();
			 }
	
	$content = "<small><p>I/We hereby certify the foregoing information provided are hereby true and correct. Furthermore, I/We authorize FRUITS CONSULTING INC to obtain such other information as may be required in connection with this application of loan.</p>";
	$content .= "<p>FRUITS CONSULTING INC reserve the right to REJECT and DISAPPROVE any credit application without offering any reason, waive any required formality which proposals are deemed disadvantageous. FRUITS CONSULTING INC assumes no obligation to compensate or indemnity prospective loan applicants for any expense or loss that may be incurred in the submission of loan requirements nor does not guarantee that said loan will be approved.</p></small>";
	echo $content;
	
	
	$tmpl = array ('table_open'          => '<table class="table  table-noborder  " >'); 
	$this->table->set_template($tmpl);
	$this->table->add_row(array("width"=>"33%", "height"=>"80px",  "data"=>"<small>Residence Certificate No:</small><br/><br/>___________________________"), array("width"=>"33%", "data"=>"<small>Place Issued:</small><br/><br/>___________________________"), "<small>Date Issued:</small><br/><br/>___________________________");
	$this->table->add_row(" "," ", "Signature: ____________________________");
	if($loan->approvedAmount != ''){
		$approve = number_format($loan->approvedAmount,2);
		$dateapproved = date("F d, Y", strtotime($loan->dateApproved));
	}else {
		$approve = '';
		$dateapproved = '';
	}
	$this->table->add_row(array("height"=>"70px", "valign"=>"bottom", "align"=>"center","data"=>"General Manager"),array("valign"=>"bottom","style"=>"font-weight: bold","data"=>"Amount approved:<br/> PHP ".$approve."<br/>"."Date : ".$dateapproved),array("valign"=>"bottom", 'align'=>"center","data"=>"Chief Operations Officer"));
	echo $this->table->generate();
	
	
	
	
?>
		