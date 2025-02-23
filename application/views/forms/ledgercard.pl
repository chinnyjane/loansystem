<?php
	$loans = $this->Loansmodel->getLoanDetails($loanid);
	
	$loaninfo = $loans['loaninfo']->row();
	$client = $loans['clientinfo']->row();
	$spouse = $loans['spouseinfo'];
	
	switch($loaninfo->PaymentTerm){
		case 'M':
			$pterm = "Monthly";
		break;
		case 'L':
			$pterm = "Lumpsum";
		break;
	}
	
?>