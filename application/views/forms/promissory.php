<?php
	$loans= $this->Loansmodel->getLoanDetails($loanid);
	$loan = $loans['loaninfo']->row();
	$comaker = $loans['comaker'];
	$tmpl = array ('table_open'          => '<table class="table table-noborder " >'); 
	$this->table->set_template($tmpl);
	
	$clientid = $loan->ClientID;
	$client = $this->Clientmgmt->getclientinfoByID($clientid);
	$client = $client->row();
	
	$cvres = $this->Loansmodel->getTransByPN($loan->PN, $loan->branchID);
	$cv = $cvres->row();
	$fees = $this->Loansmodel->getLoanFees($loanid);
	$fee = $fees->result();
	
	$c = 0;
	
	$agent = $this->UserMgmt->get_user_byid($loan->LoanProcessor);
	if($agent->num_rows() > 0 ){
		$a = $agent->row();
		$ag= $a->firstname." ".$a->lastname;
	}else $ag = '';
	
	if($loan->DateDisbursed != NULL){
		$dateGranted =  date("d-M-y", strtotime($loan->DateDisbursed));
		$dateMatured =  date("d-M-y", strtotime($loan->MaturityDate));
	}else{
		$dateGranted = '-';
		$dateMatured = '-';
	}
	
	$rcve = "Received from FRUITS CONSULTING INC the said amount as full/partial payment for the above explanation.";
	//echo "Branch: ".strtoupper($loan->branchname);
	$this->table->set_heading(array("width"=>"70%","data"=>"<u> PHP ".number_format($loan->approvedAmount,2)."</u>"), "PN NO . : ", $loan->PN);
	$this->table->add_row('', "Date Granted . : ",$dateGranted);
	$this->table->add_row('', "Maturity Date : ", $dateMatured);
	echo  $this->table->generate();
	
	$content = "&nbsp; &nbsp; &nbsp;";
	$content .= "For value received, I/We jointly and severally promise to pay to the order of YUSAY CREDIT & FINANCE CORPORATION the sum of ";
	$content .= "<u><i>".strtoupper($this->loansetup->convert_number_to_words(round($loan->approvedAmount,2)))." PESOS ONLY </i> &nbsp;( PHP ".number_format($loan->approvedAmount,2)." )</u>, Philippines Currency as follows: ";
	
	echo $content;
	echo "<p>&nbsp;</p>";
	
	$tmpl = array ('table_open'          => '<table class=" table-noborder " style="align:center; font-size:9px" >'); 
	$this->table->set_template($tmpl);
	echo $this->loansetup->loanschedule($loanid);

	echo "<p style='margin: 10px'>".$loan->PN_remarks." </p> ";
	if(strpos($loan->productCode, "PL") !== FALSE){
		$content = "<p>&nbsp; &nbsp; &nbsp;";
		$content .= "It is hereby agreed that this loan wether renewal, new or additional shall be secured by assignment of ";
		$content .= "<u> Php ".number_format($loan->MonthlyInstallment,2)." </u> Monthly Pension and related documents.</p>";
		echo $content;
		
		$content = "<p>&nbsp; &nbsp; &nbsp;";
		$content .= "Violation of any terms and conditions in relation to the above assignment of <u> ".strtoupper($loan->productName)."</u> ";
		$content .= "proceeds in the amount of <u> Php ".number_format($loan->MonthlyInstallment,1)." </u> ";
		$content .= "and its related documents shall render the entire unpaid installment due and payable without need of any formal demand.</p>";
		echo $content;
		
		$content = "<p>&nbsp; &nbsp; &nbsp;";
		$content .= "Interest Rate of Two Percent (2%) per month plus Penalty at the rate of Three Percent (3%) per month will be charged on all amounts due if not paid on maturity date, and if this note is placed in the hands of an Attorney for collection, the maker and endorser shall in addition pay twenty five percent (25%) of the total amount due as Attorney's fee.</p>";
		echo $content;
		
		$content = "<p>&nbsp; &nbsp; &nbsp;";
		$content .= "That in the event of any deficiency/non-payment in any of the monthly amortizations as stipulated above, YCFC may deduct the amount due from pensioner's 13th month proceeds. </p>";
		$content .= "<p>&nbsp; &nbsp; &nbsp;";
		$content .= "All legal actions shall be instituted in any court of competent jurisdiction in the City of ".$loan->city;
		echo $content;
	}elseif(strpos($loan->productCode, "SL") !== FALSE){
		
		?>
		<p>&nbsp; &nbsp; &nbsp; Time is declared of the essence hereof and upon default of payment of  any one installment due, all other installment shall become due and payable together with all interest that may have accrued. Interest Rate of two percent (2%) per month plus penalty at the rate of three percent (3%) per month will also be charged on all amounts over due and if this note is placed in the hands of an attorney for collection, the makers and endorser shall in addition, pay twenty five percent (25 %) of the total amount due as attorney's fees which sum shall in all cases not be less than P 1,000.00 beside the legal costs and expenses of litigation and any legal action arising out of this note will be instituted in the proper court of the City of <?php echo $loan->city;?> and in case of judicial execution the right by Rule 39, Sec. 12 of the Rules of Court are hereby waived.</p>
		
		<p>&nbsp; &nbsp; &nbsp;Acceptance by holder hereof of payment of any installment or any part thereof after due date shall not be considered as extending the time for the payment of any of the installment aforesaid nor as modification of any of the condition hereof and shall in no case release the maker/s or party/ies to this note from liability for the payment of the obligation arising from this note. </p>
		
		<p>&nbsp; &nbsp; &nbsp;In case any extra-ordinary inflation or deflation of currency stipulated should occur before this obligation is paid in full, the value of the currency at the time of the establishment of the obligation will be the bases of payment.</p>
		
		<p>&nbsp; &nbsp; &nbsp; Formal demand, presentment and notices of dishonor and protest are hereby waived.</p>
		<p style="height:30px;">&nbsp;</p>
	<?php }else{ ?>
		<p>&nbsp; &nbsp; &nbsp; It is hereby agreed that this loan whether renewal, new or additional shall be secured by Chattel and/or Real Estate Mortgage in accordance with the provisions of the mortgage contract.</p>
		
		<p>&nbsp; &nbsp; &nbsp; Time is declared of the essence hereof and upon default of payment of  any one installment due, all other installment shall become due and payable together with all interest that may have accrued. Interest Rate of two percent (2%) per month plus penalty at the rate of three percent (3%) per month will also be charged on all amounts over due and if this note is placed in the hands of an attorney for collection, the makers and endorser shall in addition, pay twenty five percent (25 %) of the total amount due as attorney's fees which sum shall in all cases not be less than P 1,000.00 beside the legal costs and expenses of litigation and any legal action arising out of this note will be instituted in the proper court of the City of <?php echo $loan->city;?> and in case of judicial execution the right by Rule 39, Sec. 12 of the Rules of Court are hereby waived.</p>
		
		<p>&nbsp; &nbsp; &nbsp;Acceptance by holder hereof of payment of any installment or any part thereof after due date shall not be considered as extending the time for the payment of any of the installment aforesaid nor as modification of any of the condition hereof and shall in no case release the maker/s or party/ies to this note from liability for the payment of the obligation arising from this note. </p>
		
		<p>&nbsp; &nbsp; &nbsp;In case any extra-ordinary inflation or deflation of currency stipulated should occur before this obligation is paid in full, the value of the currency at the time of the establishment of the obligation will be the bases of payment.</p>
		
		<p>&nbsp; &nbsp; &nbsp; Formal demand, presentment and notices of dishonor and protest are hereby waived.</p>
		<p style="height:30px;">&nbsp;</p>
	<?php }
	$tmpl = array ('table_open'          => '<table class="table table-noborder signed " style="align:center">'); 
	$this->table->set_template($tmpl);
	
	$c = array();
	if(!empty($comaker)){
		if($comaker->num_rows() > 0){
			$count =1;
			foreach($comaker->result() as $com){
				if($com->clientID != ''){
					$clientinfo = $this->Clientmgmt->getclientinfoByID($com->clientID)->row();
					$c[] = $clientinfo->firstName." ".$clientinfo->MiddleName.". ".$clientinfo->LastName;
					if($this->auth->perms('debug',$this->auth->user_id(),3) == true) { 	
					//echo "<pre>";
					//print_r($clientinfo);
					//echo "</pre>";
					}
					$add[] = $clientinfo->address.", ".$clientinfo->barangay.", ".$clientinfo->cityname.", ".$clientinfo->provname;
					$id[] = $clientinfo->id_presented;
					$count++;
				}
			}
		}
	}
	
	$comcount = count($c);
	if(count($c) > 0){		
		$co=0;
		$count=1;
		while($count <=  $comcount){
			$com2 = $c[$co]."<br/>"; 
			$cadd2 = $add[$co];
			if($co == 0){
				$this->table->add_row(array("width"=>"40%", "height"=>"30px", "valign"=>"top",  "data"=>"<u> ".$c[$co]." </u><br/>Co-Maker<br/>".$add[$co]."<br/>ID NO: <u> ".$id[$co]." </u>"),'', array("width"=>"50%","valign"=>"top",  "data"=>"<u> ".strtoupper($client->firstName." ".$client->MiddleName." ".$client->LastName)." </u><br/>PRINCIPAL<br/>".$client->address.", ".$client->barangay.", ".$client->cityname.", ".$client->provname."<br/>ID NO : <u> ".$client->id_presented." </u>"));
				echo  $this->table->generate();
			}else{
				$this->table->add_row(array("height"=>"50px","width"=>"40%",  "data"=>"<u> ".$c[$co]." </u><br/>Co-Maker<br/>".$add[$co]."<br/>ID NO : <u> ".$id[$co]." </u>"),array("height"=>"50px","width"=>"60%"));
				echo  $this->table->generate();
			}
			$co++;
			$count++;
		}
		
	}else{
		$this->table->add_row(array("width"=>"40%", "height"=>"30px", "valign"=>"top"),'', array("width"=>"50%","valign"=>"top",  "data"=>"<u> &nbsp;".strtoupper($client->firstName." ".$client->MiddleName." ".$client->LastName)." &nbsp;</u><br/>PRINCIPAL<br/>".$client->address.", ".$client->barangay.", ".$client->cityname.", ".$client->provname."<br/>ID NO : &nbsp;<u> ".$client->id_presented." &nbsp;</u>"));
				echo  $this->table->generate();
	}
	
	//$this->table->add_row(array("width"=>"30%", "height"=>"80px",  "data"=>"Co-Maker"),'', array("width"=>"30%", "data"=>"<large>".$client->firstName." ".$client->LastName."</large><br/>Principal"));
	
	$this->table->add_row(array("height"=>"10px"),'signed in the presence of', array("data"=>""));
		echo  $this->table->generate();
	$this->table->add_row(array("height"=>"10px", "data"=>"___________________"),'', array("data"=>"_____________________"));
	echo  $this->table->generate();
	
		
?>
		