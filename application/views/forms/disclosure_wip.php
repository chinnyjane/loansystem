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

	$tmpl = array ('table_open'          => '<table class="table  table-noborder " style="font-weight: bold" >'); 
	$this->table->set_template($tmpl);
		
	$data=array("PN"=>$loan->PNno,
					"isdeleted <>"=>1);
	$cvres = $this->Loansmodel->getTransByPN($loan->PN, $loan->branchID);
	$cv = $cvres->row();
	$fees = $loans['fees'];
	$fee = $fees->result();
	
	$c = 0;
	
	$rcve = "Received from FRUITS CONSULTING INC the said amount as full/partial payment for the above explanation.";
	if($fees->num_rows > 0){
		$ch = array();
		foreach($fee as $f){
			//$this->table->add_row(array("colspan"=>'2', "data"=>$f->feeName),'',  array( "align"=>"right","data"=>number_format($f->value,2)));
			if($f->display == 1){
			$dis = number_format($f->value,2);
			//$int = round($f->value/$loan->approvedAmount * 100,2);
			$int= $loan->interest;
			$interest = $f->feeName;		
			$c += $f->value;
			
			}	
			$dis = number_format($c,2);		 	
			//$ch[$f->charge_type_ID][] = $f->value;
		}
	}else{
		$int = $loan->interest;
		$c = $loan->approvedAmount * ($int /100);
		$interest = 'Interest';
		$dis = number_format($c,2);
	}
	$int= $loan->interest;
	//echo "<pre>";
		//print_r($ch);
		//print_r($fee);
		//echo "</pre>";
	$newamount = $loan->approvedAmount - $c;
	$this->table->add_row(array("data"=>"Name of Borrower ", "width"=>"20%"),": ".$client->LastName.", ".$client->firstName);
	$this->table->add_row("Address "," : ".$client->address.", ".$client->barangay.", ".$client->cityname.", ".$client->provname);
	echo "<p></p>";
	echo  $this->table->generate();
	$tmpl = array ('table_open'          => '<table class="table  table-noborder  " >'); 
	$this->table->set_template($tmpl);
	$this->table->add_row("1. Cash/Purchase price or Net Proceeds of loan Item Purchased)",array("width"=>"20%", "data"=>'', "align"=>"right"),"",array("width"=>"20%", "style"=>"border-bottom: 1px solid #000","align"=>"right","data"=>number_format($newamount,2)));
	$this->table->add_row("2. Less: Down payment and/or Trade-in Value (Not applicable  for loan transaction)","","","");
	$this->table->add_row("3. Unpaid Balance of Cash/Purchased Price of net proceeds of loan","","","");
	$this->table->add_row("4. Non-Finance Charges ( Advanced by seller/creditor)","","","");
	$this->table->add_row("&nbsp; &nbsp; a. Insurance Premium","","","");
	$this->table->add_row("&nbsp; &nbsp; b. Taxes","","","");
	$this->table->add_row("&nbsp; &nbsp; c. Registration Fees","","");
	$this->table->add_row("&nbsp; &nbsp; d. Documentary/Science Stamps","","","");
	$this->table->add_row("&nbsp; &nbsp; Total Non-Finance Charges","","","");
	$this->table->add_row("5. Amount to be Financed (items 3/4)","","",array("align"=>"right","style"=>"border-bottom: 1px solid #000","data"=>number_format($loan->approvedAmount,2)));
	$this->table->add_row("6. Finance Charges","","","");
		
	$this->table->add_row("&nbsp; &nbsp; a. ".$interest." &nbsp;".$int."%",array("align"=>"right","style"=>"border-bottom: 1px solid #000","data"=>$dis),"","");
	$this->table->add_row("&nbsp; &nbsp; b. Discounts",array("align"=>"right","style"=>"border-bottom: 1px solid #000","data"=>''),"","");
	$this->table->add_row("&nbsp; &nbsp; c. Service/Handling Charges",array("align"=>"right","style"=>"border-bottom: 1px solid #000","data"=>''),"","");
	$this->table->add_row("&nbsp; &nbsp; d. Collection Charges",array("align"=>"right","style"=>"border-bottom: 1px solid #000","data"=>''),"","");
	$this->table->add_row("&nbsp; &nbsp; e. Credit Investigation Fees",array("align"=>"right","style"=>"border-bottom: 1px solid #000","data"=>''),"","");
	$this->table->add_row("&nbsp; &nbsp; f. Appraisal Fees",array("align"=>"right","style"=>"border-bottom: 1px solid #000","data"=>''),"","");
	$this->table->add_row("&nbsp; &nbsp; g. Attorney's/Legal Fees",array("align"=>"right","style"=>"border-bottom: 1px solid #000","data"=>''),"","");
	$this->table->add_row("&nbsp; &nbsp; h. Other charges incidental to the extension of credit","","","");
	$this->table->add_row("&nbsp; &nbsp; Total Finance Charges","","",array("align"=>"right","style"=>"border-bottom: 1px solid #000","data"=>number_format($c,2)));
	$this->table->add_row("7. Percentage of Finance Charges to Total Amount Financed",array("align"=>"right","style"=>"border-bottom: 1px solid #000","data"=>$int." %"),"",'');
	$this->table->add_row("8. Effective interest Rate ( mentioned of computation attached)",array("align"=>"right","style"=>"border-bottom: 1px solid #000","data"=>number_format($c/$newamount*100,2)." %"),"",'');
	$this->table->add_row("9. Payment","","",'');
	$this->table->add_row("&nbsp; &nbsp; a. Single Payment due","","","");
	$this->table->add_row("&nbsp; &nbsp; b. Total Installment Payments","","","");
	$this->table->add_row("&nbsp; &nbsp;(payable in <u> ".$loan->Term." </u> Months ) @",array("align"=>"right","style"=>"border-bottom: 1px solid #000", "data"=>number_format($loan->MonthlyInstallment)),"",array("align"=>"right","style"=>"border-bottom: 1px solid #000","data"=>number_format($loan->approvedAmount,2)));
	$this->table->add_row("10. Additional Charges in case certain stipulations in the contract are not met by the debtor.","","",'');
	echo  $this->table->generate();
	$tmpl = array ('table_open'          => '<table class="table  table-noborde  signed" >'); 
	$this->table->set_template($tmpl);
	$this->table->add_row("Nature", "Rate", "Amount");
	$this->table->add_row("______________________________________", "______________________________________", "______________________________________");
	$this->table->add_row("______________________________________ ", "______________________________________", "______________________________________");
	$this->table->add_row(" ", "", " ");
	$this->table->add_row(" ", "", "Certified Correct:");
	//Branch Manager's Name Here
	$this->table->add_row(array("height"=>"60px","data"=>''), "", array("style"=>"border-bottom: 1px solid #000; font-weight: bold;","data"=>strtoupper($mgr)));
	$this->table->add_row("", "", array("data"=>'(signature of creditor/authorized <br/>representative over Printed Name)'));
	echo $this->table->generate();
	
	$content = "&nbsp; &nbsp; &nbsp;";
	$content .= "I acknowledge receipt of A copy of this statement prior to the consummation of the credit transaction  and that I understand and fully agree to the terms and conditions thereof.</p>";
	echo $content;
	
	$this->table->add_row(array("height"=>"60px","data"=>''), "", array("style"=>"border-bottom: 1px solid #000; font-weight: bold","data"=>$client->LastName.", ".$client->firstName));
	$this->table->add_row("Date : ".date("l, F d, Y", strtotime($this->auth->localdate())),"", array("width"=>"35%","data"=>'(Signature of Buyer/Borrower)'));
	echo $this->table->generate();
?>
		