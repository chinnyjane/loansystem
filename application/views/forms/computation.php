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
	$loancode = $loan->LoanSubCode;
	switch($loancode){
		case 'N':
			$status= "New";
		break;
		case 'E':
			$status= "Extension";
		break;
		case 'A':
			$status= "Additional";
		break;
		case 'R':
			$status= "Renewal";
		break;
	}
	$method = $loan->paymentmethod;
	if($method == 'M') $m='MONTHLY'; else $m='LUMPSUM';
	
	$product = $this->Loansmodel->getproductsbyID($loan->LoanType);
	$p = $product->row();
	$tmpl = array ('table_open'          => '<table class="table  table-border  " >'); 
	$this->table->set_template($tmpl);
	$data=array("PN"=>$loan->PNno,
					"isdeleted <>"=>1);
	$cvres = $this->Loansmodel->getTransByPN($loan->PN, $loan->branchID);
	$cv = $cvres->row();
	$fees = $this->Loansmodel->getLoanFees($loanid);
	$fee = $fees->result();
	$agent = $this->UserMgmt->get_user_byid($loan->LoanProcessor);
	$apr = $this->UserMgmt->get_user_byid($loan->ApprovedBy);
	if($agent->num_rows() > 0 ){
		$a = $agent->row();
		$ag= $a->lastname.", ".$a->firstname;
	}else $ag = '';
	if($apr->num_rows() > 0 ){
		$a = $apr->row();
		$ap= $a->lastname.", ".$a->firstname;
	}else $ap = '';
	$c = 0;
	
	if(!empty($loaninfo->PN)){ 			
		$cv = $this->Loansmodel->cvexist($loaninfo->PN);
		if($cv->num_rows() > 0 ){
			$cv = $cv->row();
			
			$cvdate = $cv->dateOfTransaction;
		}
	}
	//SUMMARY OF COMPUTATION at the UPPER RIGHT HAND
	
	$rcve = "Received from FRUITS CONSULTING INC the said amount as full/partial payment for the above explanation.";
	$tmpl = array ('table_open'          => '<table class="table  table-noborder  " >'); 
	$this->table->set_template($tmpl);
	$comp = $this->loansetup->ComputationOfLoan($loans);
	
	$this->table->add_row(array("width"=>'33%',"data"=>""),array("width"=>'35%',"align"=>'center',"data"=>'<p align="center">'.strtoupper($loan->branchname).' BRANCH <br/>'.strtoupper($loan->branchaddress.", ".$loan->city).'</p>'.'<h4 align="center">'.$formtitle.'</h4>'),array('align'=>"right",'data'=>'<p > <label> PN # : '.$loan->PN.'<br/> CV # : '.$cv->referenceNo.'<br/> Check # : '.$cv->Checkno.'</label></p>'));
	echo $this->table->generate();
	
	$tmpl = array ('table_open'          => '<table class="table  table-border  " >'); 
	$this->table->set_template($tmpl);
	$this->table->add_row(array("data"=>"NAME"."<br/><br/>".$client->LastName.", ".$client->firstName, "width"=>"33%"), "TYPE OF LOAN"."<br/><br/>".$p->LoanName, array("width"=>"33%","rowspan"=>"3", "data"=>$comp['table']));
	//$this->table->add_row($loan->LastName.", ".$loan->firstName,$p->LoanName);
	$this->table->add_row("ADDRESS"."<br/><br/>".$client->address.", ".$client->barangay.", ".$client->cityname.", ".$client->provname,"TERM"."<br/><br/>".$loan->Term." Months - ".$loan->extension." ".$status);
	//$this->table->add_row($loan->address,$loan->Term." Months");
	$this->table->add_row("Computed & Posted By: "."<br/><br/>".$ag,"Nature of Payment"."<br/><br/>".$m);
	//$this->table->add_row(array("height"=>"40px","data"=>$ag),"MONTHLY");	
	echo  $this->table->generate();
	
	
	$this->table->add_row(array("width"=>'33%',"data"=>"Checked By"),array("width"=>'33%',"data"=>"Approved By"), array("width"=>'33%',"data"=>"Paid By"));
	$this->table->add_row(array("height"=>'40px','valign'=>"bottom","data"=>'Auditor/Bookkeeper'),array("data"=>$ap, "valign"=>"bottom"), array("data"=>"Name & Signature", "valign"=>"bottom"));
	echo  $this->table->generate();
	echo '<p align="center" style="margin: 50px 0px;">- - - - - - - - - - - - - - - - - - - - - - - - -  - -<br/>';
	$tmpl = array ('table_open'          => '<table class="table  table-noborder  " >'); 
	$this->table->set_template($tmpl);
	$this->table->add_row(array("width"=>'33%',"data"=>""),array("width"=>'35%',"align"=>'center',"data"=>'<p align="center">'.strtoupper($loan->branchname).' BRANCH <br/>'.strtoupper($loan->branchaddress.", ".$loan->city).'</p>'.'<h4 align="center">'.$formtitle.'</h4>'),array('align'=>"right",'data'=>'<p > <label> PN # : '.$loan->PN.'<br/> CV # : '.$cv->referenceNo.'<br/> Check # : '.$cv->Checkno.'</label></p>'));
	echo $this->table->generate();
	
	$tmpl = array ('table_open'          => '<table class="table  table-border  " >'); 
	$this->table->set_template($tmpl);
	
	$this->table->add_row(array("data"=>"NAME"."<br/><br/>".$client->LastName.", ".$client->firstName, "width"=>"33%"), "TYPE OF LOAN"."<br/><br/>".$p->LoanName, array("width"=>"33%","rowspan"=>"3", "data"=>$comp['table']));
	//$this->table->add_row($loan->LastName.", ".$loan->firstName,$p->LoanName);
	
	
	$this->table->add_row("ADDRESS"."<br/><br/>".$client->address.", ".$client->barangay.", ".$client->cityname.", ".$client->provname,"TERM"."<br/><br/>".$loan->Term." Months - ".$loan->extension." ".$status);
	//$this->table->add_row($loan->address,$loan->Term." Months");
	
	$this->table->add_row("Computed & Posted By: "."<br/><br/>".$ag,"Nature of Payment"."<br/><br/>".$m);
	//$this->table->add_row(array("height"=>"40px","data"=>$ag),"MONTHLY");	
	echo  $this->table->generate();
	
	$this->table->add_row(array("width"=>'33%',"data"=>"Checked By"),array("width"=>'33%',"data"=>"Approved By"), array("width"=>'33%',"data"=>"Paid By"));
	$this->table->add_row(array("height"=>'40px','valign'=>"bottom","data"=>'Auditor/Bookkeeper'),array("data"=>$ap, "valign"=>"bottom"), array("data"=>"Name & Signature", "valign"=>"bottom"));
	echo  $this->table->generate();
?>
		