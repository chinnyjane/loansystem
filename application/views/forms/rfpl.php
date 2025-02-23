<?php
	$loans = $this->Loansmodel->getLoanDetails($loanid);
	$loan = $loans['loaninfo']->row();
	$client = $loans['clientinfo']->row();
		$cvres = $this->Loansmodel->getTransByPN($loan->PN, $loan->branchID);
		if($cvres->num_rows() >0){
			$cv = $cvres->row();
			$cvnum =$cv->Checkno;
		}else{
			$cvnum = '';
		}
			
		$fees = $loans['fees'];
		$fee = $fees->result();
		
		$feename = array();
		foreach($fee as $f){
			$feename[$f->feeName] = $f->value;
		}
		
		echo '<p align="center">'.strtoupper($loan->branchaddress.", ".$loan->city).'</p>';
	echo '<hr style="margin:0"/>';
	echo '<h4 align="center"><b>'.$formtitle.'</b></h4>';
		
		$tmpl = array ('table_open'          => '<table class="table  table-border  " >'); 
		$this->table->set_template($tmpl);
		$this->table->add_row("Name of Borrower", $client->LastName.", ".$client->firstName, "Loan Granted",number_format($loan->approvedAmount,2));
		$this->table->add_row("Address", $client->address, "Term of Loan",$loan->Term);
		$this->table->add_row("Date of Birth", date("F d, Y", strtotime($client->dateOfBirth)), "Date of Release",date("F d, Y", strtotime($loan->DateDisbursed)));
		$this->table->add_row("Age", $this->loansetup->get_age($client->dateOfBirth), "Maturity Date", date("F d, Y", strtotime($loan->MaturityDate)));
		$this->table->add_row("Gender", $client->gender, "RFPL Due",number_format($feename['RFPL'],2));
		$this->table->add_row("Civil Status", $client->civilStatus, "Check no.",$cvnum);
		$this->table->add_row("Promissory Note No.", $loan->PN, " "," ");		
		
		echo  $this->table->generate();
		?>
		