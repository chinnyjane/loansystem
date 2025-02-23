<?php
	$loans = $this->Loansmodel->getLoanDetails($loanid);
	
	$loaninfo = $loans['loaninfo']->row();
	$client = $loans['clientinfo']->row();
	$spouse = $loans['spouseinfo'];
	
	$branch = $this->UserMgmt->branch($loaninfo->branchID);

	$emps = $branch['emps'];

	if($emps->num_rows() > 0){
		foreach($emps->result() as $emp){
			if($emp->name == "Branch Manager")
			{
				$position = "Branch Manager";
				$mgr = $emp->firstname." ".$emp->lastname;
			}else if($emp->name == "General Manager" and strpos(strtolower($emp->firstname), "dummy") === false){
				$position = "General Manager";
				$mgr = $emp->firstname." ".substr($emp->middlename, 0, 1).". ".$emp->lastname;
				//$position = "Chief Operations Officer";
				//$mgr = "John Felix A. Yusay";
			}
		}
	}
	if(empty($position)){
		$position = "General Manager";
		$mgr = "Rebecca A. Rodelas";
	}
	
	switch($loaninfo->PaymentTerm){
		case 'M':
			$pterm = "Monthly";
		break;
		case 'L':
			$pterm = "Lumpsum";
		break;
	}
	
	$loancode = $loaninfo->LoanSubCode;
	switch($loancode){
		case 'N':
			$n = ' ✔ ';
			$e = '';
			$a = '';
			$r = '';
		break;
		case 'E':
			$e = ' ✔ ';
			$n = '';
			$a = '';
			$r = '';
		break;
		case 'A':
			$a = ' ✔ ';
			$n = '';
			$e = '';
			$r = '';
		break;
		case 'R':
			$r = ' ✔ ';
			$n = '';
			$e = '';
			$a = '';
		break;
	}
	
	 if($client->gender =='M') $gender =  "MALE"; else $gender = "FEMALE" ;
	
	//$c = 0;
	//echo $loaninfo->PaymentTerm;
	$rcve = "Received from FRUITS CONSULTING INC the said amount as full/partial payment for the above explanation.";
	$tmpl = array ('table_open'          => '<table class="table  table-border  " >'); 
	$this->table->set_template($tmpl);
	echo '<p align="center">'.strtoupper($loaninfo->branchname).' BRANCH <br/>'.strtoupper($loaninfo->branchaddress.", ".$loaninfo->city).'</p>';

	echo '<h4 align="center">'.$formtitle.'</h4>';
	
	$this->table->add_row(array("colspan"=>4, "data"=>"<h4>LOAN INFORMATION</h4>"));
	$this->table->add_row(array("colspan"=>2, "data"=>"Type of Loan : <b>".strtoupper($loaninfo->productName)."</b>"), array("colspan"=>2, "data"=>"(".$n.") NEW &nbsp; (".$r.") RENEWAL &nbsp; (".$e.") EXTENSION &nbsp; (".$a.") ADDITIONAL "));
	$this->table->add_row(array("colspan"=>2, "data"=>"Amount Applied : <bold>".number_format($loaninfo->AmountApplied,2).' - '.strtoupper($loaninfo->computation)." </bold>"), "Terms : ".$loaninfo->Term. " months"." - ".$pterm, "Date Applied : ".date("F d, Y",strtotime($loaninfo->dateApplied)));
	//$this->table->add_row(array("colspan"=>'3',"data"=>"Amount in words: ".strtoupper($this->loansetup->convert_number_to_words($loaninfo->AmountApplied))." PESOS ONLY"));		
	$this->table->add_row(array("colspan"=>4, "data"=>"<h4>PERSONAL INFORMATION</h4>"));
	$this->table->add_row(array("colspan"=>2, "data"=>"<small>Name: </small>".$client->LastName.", ".$client->firstName." ".$client->MiddleName),  "<small>Date of Birth: </small> ". date("F d, Y", strtotime($client->dateOfBirth)), "Age: ".$this->loansetup->get_age($client->dateOfBirth)." yrs. old");
	$this->table->add_row(array("colspan"=>2, "data"=> "<small>Gender:</small> ".$gender), "<small>Civil Status:</small> ".$client->civilStatus,"<small>Contact Number:</small> ".$client->contact);
	$this->table->add_row("Province: ".strtoupper($client->provname), "<small>City: </small> ".strtoupper($client->cityname), array("colspan"=>"2", "data"=>"<small>Address:</small> ".strtoupper($client->address.", Brgy. ".$client->barangay)));
	
	
	if($client->civilStatus != 'single'){
		if($spouse->num_rows() > 0){
			$spouse = $spouse->row();
			if ($spouse->dateOfBirth == '0000-00-00'){
				$sbday = '';
				$sage = '';
			}else{
				$sbday = date("F d, Y", strtotime($spouse->dateOfBirth));
				$sage = $this->loansetup->get_age($spouse->dateOfBirth) ." yrs. old";
			}
			
			$this->table->add_row(array("colspan"=>3, "data"=>"<h4>SPOUSE INFORMATION</h4>"));
			$this->table->add_row(array("colspan"=>2, "data"=>"<small> Name:</small> ".$spouse->lastname.", ".$spouse->firstname." ".$spouse->middlename),  "<small>Date of Birth: </small>".$sbday, "<small>Age:</small>". $sage );
			$this->table->add_row( array("colspan"=>2, "data"=>"<small>Occupation:</small> ". strtoupper($spouse->occupation)), "<small>Salary:</small> ".$spouse->salary,"<small>Contact:</small> ".$spouse->contact);
			
		}
	}
	echo $this->table->generate();
	
	if(strpos($loaninfo->LoanCode,"PL") === false){
	$this->table->add_row(array("colspan"=>4, "data"=>"<h4>EMPLOYMENT/BUSINESS</h4>"));
	$emp = $loans['employment'];
	$incomeexpense = $loans['incomeexpense'];
	
	if(isset($emp)){
		if($emp->num_rows() > 0){
			$emp = $emp->row();
			$this->table->add_row('Employer/Business Name:<br/>'.$emp->employer, 'Address: <br/>'.$emp->address, 'Nature of Business: <br/>'.$emp->natureOfBusiness, 'Contact #: <br/>'.$emp->contact);
			$this->table->add_row('Position/Department:<br/>'.$emp->position, 'Length of Service: <br/>'.$emp->lengthOfService, 'Employment Status: <br/>'.$emp->status, 'Monthly Salary: <br/>'.$emp->monthlySalary);
			
		}else{
			$this->table->add_row('Employer/Business Name:<br/>&nbsp;', 'Address: <br/>&nbsp;', 'Nature of Business: <br/>&nbsp;', 'Contact #: <br/>&nbsp;');
			$this->table->add_row('Position/Department:<br/>&nbsp;', 'Length of Service: <br/>&nbsp;', 'Employment Status: <br/>&nbsp;', 'Monthly Salary: <br/>&nbsp;');
			
		}
	}
	echo $this->table->generate();
	$sumIncome = 0;
	$sumExpense = 0;
	if(isset($incomeexpense)){
		if($incomeexpense->num_rows() > 0){
			foreach($incomeexpense->result() as $ie){
				if($ie->type == 'income'){
					$income[] = array("nature"=>$ie->nature,
								"value"=>$ie->value,
								"id"=>$ie->id);
					$sumIncome += $ie->value;
				}elseif($ie->type == 'expense'){
					$expense[] = array("nature"=>$ie->nature,
								"value"=>$ie->value,
								"id"=>$ie->id);
					$sumExpense += $ie->value;
				}
				
				
			}
		}
		
		$intable = $this->table;
		$intmpl = array ('table_open'  => '<table class="table  table-border  " width="100%" >'); 
		//$intable->set_template($intmpl);
		$intable='';
		
		if(isset($income)){	
			//$intable->set_heading("Nature","Income");
			foreach($income as $in){
				//$intable->add_row($in['nature'], $in['value']);
				$intable .= "<b>".$in['nature']."&nbsp; : &nbsp;</b>";
				$intable .= number_format($in['value'],2);
				$intable .= "<br/>";
			}
			$intable .= "<br/>";
			$intable .= "<b>Total Income &nbsp; : &nbsp;</b> ". number_format($sumIncome,2);
			//$inc = $intable->generate();
		}
		
		//$extable = $this->table;		
		$extable = '';
		if(isset($expense)){
			//$extable->set_heading("Nature","Expense");
			foreach($expense as $in){
				//$extable->add_row($in['nature'], $in['value']);
				$extable .= "<b>".$in['nature']."&nbsp; : &nbsp;</b>";
				$extable .= number_format($in['value'],2);
				$extable .= "<br/>";
			}
			//$extable->add_row("Total Expenses", $sumExpense);
			$extable .= "<br/>";
			$extable .= "<b>"."Total Expenses &nbsp; : &nbsp;</b> ".number_format($sumExpense,2);
			//$exp = $extable->generate();			
		}
		
		$this->table->clear();
		
		if(isset($intable))
			$inex = $this->table->add_row(array("data"=>$intable,"colspan"=>2), array("data"=>$extable,"colspan"=>2, "style"=>"vertical-align: bottom"));
		else
			$inex = $this->table->add_row(array("data"=>"N/A","colspan"=>2), array("data"=>"N/A","colspan"=>2));
		
	}else{
		$this->table->add_row("&nbsp;", "&nbsp;");
	}
	$tmpl = array ('table_open'  => '<table class="table  table-border  " width="100%" >'); 
	$this->table->set_heading(array("data"=>"SOURCE OF INCOME", "width"=>"50%","colspan"=>2), array("data"=>"EXPENSES", "width"=>"50%", "colspan"=>2));	
	echo $this->table->generate();
	}
	echo "<h4>COLLATERAL INFORMATION</h4>";
	//echo $this->loansetup->pensioninfo($loan->pensionID);
	$collaterals = $loans['collaterals'];
	if(!empty($collaterals)){
	if($collaterals->num_rows() > 0){	
			echo '<table class="table  table-border  " >';	
				$count=1;
			foreach($collaterals->result() as $col){ 
			
				if(strpos( $loaninfo->productCode,"PL") !== false){
					echo "<tr>";
						echo "<td> <label>Pension from : </label> ".strtoupper($col->PensionType)."</td>";
						echo "<td> <label>Status : </label> ".strtoupper($col->PensionStatus)."</td>";
						echo "<td> <label>Pension number : </label> ".strtoupper($col->PensionNum)."</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<td colspan='2'> <label>Monthly Pension : </label> ".number_format($col->monthlyPension,2)."</td>";
						echo "<td> <label>Date of Withdrawal : </label> ". $this->numbers->ordinal($col->pensionDate). "</td>";					echo "</tr>";
					echo "<tr>";						
						echo "<td colspan='2'> <label>Bank & Branch : </label> ".strtoupper($col->BankName)." - ".strtoupper($col->bankBranch). "</td>";
						echo "<td> <label>Account number : </label> ".$col->Bankaccount."</td>";
					echo "</tr>";
				}else{
					if($count == 1)
					echo '<tr>';
					echo '<td width="30%" style="vertical-align: middle" ><label>'.$col->collateralname.': </label>';
					echo $col->value.'</td>';
					if($count == 4)
					echo '</tr>';				
				}
				if($count == 4) $count = 0;
				$count ++;
			}
			echo "</table>";
		}else{
			echo $this->Loansmodel->getcollaterals($loaninfo->productCode,$loaninfo->LoanType,$loaninfo->pensionID);
			
		}
	}
	
	
	 $dependents = $loans['dependents'];
			 if($dependents->num_rows() >0){
				echo "<h4>Dependents</h4>";
				$this->table->add_row("Name", "Date of Birth", "Age");
				foreach ($dependents->result() as $dep){
					$this->table->add_row($dep->firstname." ".$dep->middlename." ".$dep->lastname, date("F d, Y", strtotime($dep->dateOfBirth)), $this->loansetup->get_age($dep->dateOfBirth));
				}
				echo $this->table->generate();
			 }
			
	
	$creditor = $loans['creditor'];
	 if($creditor->num_rows() >0){
		echo "<h4>Credit Obligations</h4>";		
		$this->table->add_row("Name", "Address", "Amount", "Remarks");				 
			foreach($creditor->result() as $cre){
				$this->table->add_row($cre->name, $cre->address, $cre->amount, $cre->remarks);
			}
				echo $this->table->generate();
	}
	
	$content = "<small><p>I/We hereby certify the foregoing information provided are hereby true and correct. Furthermore, I/We authorize FRUITS CONSULTING INC to obtain such other information as may be required in connection with this application of loan.</p>";
	$content .= "<p>FRUITS CONSULTING INC reserve the right to REJECT and DISAPPROVE any credit application without offering any reason, waive any required formality which proposals are deemed disadvantageous. FRUITS CONSULTING INC assumes no obligation to compensate or indemnity prospective loan applicants for any expense or loss that may be incurred in the submission of loan requirements nor does not guarantee that said loan will be approved.</p></small>";
	echo $content;
	
	
	$tmpl = array ('table_open'          => '<table class="table  table-noborder  " >'); 
	$this->table->set_template($tmpl);
	$this->table->add_row(array("width"=>"33%", "height"=>"80px",  "data"=>"<small>Residence Certificate No:</small><br/><br/>___________________________"), array("width"=>"33%", "data"=>"<small>Place Issued:</small><br/><br/>___________________________"), "<small>Date Issued:</small><br/><br/>___________________________");
	$this->table->add_row(" "," ", "Signature: ____________________________");
	$apr = $this->UserMgmt->get_user_byid($loaninfo->ApprovedBy);
	if($apr->num_rows() > 0 ){
		$a = $apr->row();
		$ap= $a->lastname.", ".$a->firstname;
	}else $ap = '';
	if($loaninfo->approvedAmount != ''){
		$approve = number_format($loaninfo->approvedAmount,2);
		$dateapproved = date("F d, Y", strtotime($loaninfo->dateApproved));
	}else {
		$approve = '';
		$dateapproved = '';
	}
	$this->table->add_row(array("height"=>"70px", "valign"=>"bottom", "align"=>"center","data"=>strtoupper($mgr)."<br/>".$position),array("valign"=>"bottom","style"=>"font-weight: bold","data"=>"Amount approved:<br/> PHP ".$approve."<br/>"."Date : ".$dateapproved."<br/>Approved by: ".$ap),array("valign"=>"bottom", 'align'=>"center","data"=>"Chief Operations Officer"));
	echo $this->table->generate();
	
	
	
	
?>
		