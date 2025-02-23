<?php
	$loans = $this->Loansmodel->getLoanDetails($loanid);
	$loaninfo = $loans['loaninfo']->row();
	$lclient = $loans['clientinfo']->row();
	$client = $this->Clientmgmt->getclientinfoByID($comaker)->row();
	$spouse =  $this->Clientmgmt->getspouse($comaker);
	$dependents =  $this->Clientmgmt->getdependents($comaker);
	$creditor =  $this->Clientmgmt->getcreditor($comaker);
	$emp = $this->Clientmgmt->getEmployer($comaker);
	$incomeexpense = $this->Clientmgmt->getIncomeExpense($comaker);	
	
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
	}
	 if($client->gender =='M') $gender =  "MALE"; else $gender = "FEMALE" ;
	
	//$c = 0;
	//echo $loaninfo->PaymentTerm;
	$rcve = "Received from FRUITS CONSULTING INC the said amount as full/partial payment for the above explanation.";
	$tmpl = array ('table_open'          => '<table class="table  table-border  " >'); 
	$this->table->set_template($tmpl);
	echo '<p align="center">'.strtoupper($loaninfo->branchname).' BRANCH <br/>'.strtoupper($loaninfo->branchaddress.", ".$loaninfo->city).'</p>';

	echo '<h4 align="center">'.$formtitle.'</h4>'; ?>

	<table>
		<tr>
		<td><h4>I. PERSONAL DATA</h4></td>
		<td align="right" style="border-bottom: 1px solid " width="25%">DATE :  <?php echo date(" l, F d, Y", strtotime($loaninfo->dateApplied));?></td>
		</tr>
	</table>
	
	<table class="table-noborder">
		<tr>
			<td>Co-Maker's Name</td>
			<td width="25%" style="border-bottom: 1px solid">: <?php echo $client->LastName.", ".$client->firstName." ".$client->MiddleName;?></td>
			<td>Date of Birth</td>
			<td  style="border-bottom: 1px solid">: <?php echo date("F d, Y",strtotime($client->dateOfBirth));?></td>
			<td>Age</td>
			<td  style="border-bottom: 1px solid">: <?php echo $this->loansetup->get_age($client->dateOfBirth).' yrs old';?></td>
			<td>Gender</td>
			<td  style="border-bottom: 1px solid">: <?php echo $gender;?></td>
		</tr>
		<?php if($client->civilStatus != 'single') { 
		
		if($spouse->num_rows() > 0){
			$spouse = $spouse->row();
			
		?>
		<tr>
			<td>Name of Spouse</td>
			<td width="25%"  style="border-bottom: 1px solid">: <?php echo $spouse->lastname.", ".$spouse->firstname." ".$spouse->middlename;?></td>
			<td>Date of Birth</td>
			<td  style="border-bottom: 1px solid">: <?php echo $spouse->dateOfBirth;?> </td>
			<td>Age</td>
			<td  style="border-bottom: 1px solid">: <?php echo $this->loansetup->get_age($spouse->dateOfBirth);?></td>
			<td></td>
			<td></td>
		</tr>
		<?php	}else{ ?>
		<tr>
			<td>Name of Spouse</td>
			<td width="25%"  style="border-bottom: 1px solid">: N/A</td>
			<td>Date of Birth</td>
			<td  style="border-bottom: 1px solid">: N/A</td>
			<td>Age</td>
			<td  style="border-bottom: 1px solid">: N/A</td>
			<td></td>
			<td></td>
		</tr>
			<?php }
		} else {?>
		<tr>
			<td>Name of Spouse</td>
			<td width="25%"  style="border-bottom: 1px solid">: N/A</td>
			<td>Date of Birth</td>
			<td  style="border-bottom: 1px solid">: N/A</td>
			<td>Age</td>
			<td  style="border-bottom: 1px solid">: N/A</td>
			<td></td>
			<td ></td>
		</tr>
		<?php } ?>
		<tr>
			<td>No. of Dependents</td>
			<td width="25%"  style="border-bottom: 1px solid">: <?php echo $dependents->num_rows();?>&nbsp;&nbsp;&nbsp; No. of Studying: &nbsp;&nbsp;&nbsp;</td>
			<td>Elementary</td>
			<td  style="border-bottom: 1px solid">: </td>
			<td>High School</td>
			<td  style="border-bottom: 1px solid">: </td>
			<td>College</td>
			<td  style="border-bottom: 1px solid">:</td>
		</tr>
		<tr>
			<td>Home Address</td>
			<td colspan='3'  style="border-bottom: 1px solid">: <?php echo strtoupper($client->address.", ".$client->barangay.", ".$client->cityname);?></td>			
			<td>Tel./Cell :</td>
			<td  colspan='3' style="border-bottom: 1px solid">: <?php echo $client->contact;?> </td>			
		</tr>
		<tr>
			<td></td>
			<td colspan='3' >: ( ) Own &nbsp;&nbsp;&nbsp; ( ) Rent &nbsp;&nbsp;&nbsp; ( ) Free Use </td>			
			<td>Length of Stay </td>
			<td  colspan='3' style="border-bottom: 1px solid">:  </td>			
		</tr>
		<tr>
			<td>Business Address</td>
			<td colspan='7'  style="border-bottom: 1px solid">: </td>						
		</tr>
		<tr>
			<td>Business Tel. No. </td>
			<td width="25%"  style="border-bottom: 1px solid">:</td>
			<td>Depository Bank </td>
			<td  style="border-bottom: 1px solid">: </td>
			<td>Account No.</td>
			<td colspan="3" style="border-bottom: 1px solid">: </td>			
		</tr>
	</table>
	
	<p>
	<h4>TO : YUSAY CREDIT & FINANCE CORPORATION</h4>
	&nbsp;&nbsp;&nbsp; I AGREE to become Co-maker of Loan Applicant, <span class="underline">&nbsp;&nbsp;&nbsp; <?php echo $lclient->LastName.", ".$lclient->firstName;?> &nbsp;&nbsp;&nbsp; </span> in the amount not exceeding <span class="underline"><?php echo strtoupper($this->loansetup->convert_number_to_words($loaninfo->approvedAmount));?> </span> PESOS (PHP <span class="underline"><?php echo number_format($loaninfo->approvedAmount,2) ?></span> ) and to co-sign the Promissory Note executed by the loan applicant.
	</p>
	<p>
	<h4>I AM AWARE OF THE RESPONSIBILITY WHICH I WILL ASSUME IN SIGNING SUCH NOTE AS CO-MAKER.</h4>
	I authorize you to obtain such information as you may require concerning this statement and agree that this document shall remain your property whether or not the loan is granted.
	</p>
	<?php if($loaninfo->productCode !='PL'){ ?>
	
	<h4> II. EMPLOYMENT / BUSINESS  </h4>
	<table class="table-noborder">
		<tr>
			<td>Employer</td>
			<td style="border-bottom: 1px solid" width="40%">:</td>
			<td>Position</td>
			<td style="border-bottom: 1px solid" width="20%">:</td>
		</tr>
		<tr>
			<td>Address</td>
			<td style="border-bottom: 1px solid" >:</td>
			<td>Length of Service</td>
			<td style="border-bottom: 1px solid">:</td>
		</tr>
		<tr>
			<td>Nature of Business</td>
			<td style="border-bottom: 1px solid" >:</td>
			<td>Employment Status</td>
			<td style="border-bottom: 1px solid">:</td>
		</tr>
		<tr>
			<td>Name of Immediate Supervisor</td>
			<td style="border-bottom: 1px solid" >:</td>
			<td></td>
			<td ></td>
		</tr>
	</table>
	<br/>
	<table class="table-noborder">
		<tr>
			<td width="50%">
				<h4>III. SOURCE OF INCOME</h4>
				<table class="table-noborder" width="400">
					<tr>					
					<th width="40%">NATURE</th>
					<th>VALUE </th>
					</tr>
					 <?php
					if(isset($income)){				
						foreach($income as $in) : 
						$inc  = ($in['value'] ? number_format($in['value'],2) : number_format(0,2));
						?>						
								<tr>									
									<td ><?php echo $in['nature'];?></td>
									<td  style="border-bottom: 1px solid" > : <?php echo $inc;?></td>
								</tr>	
						<?php endforeach;
					}				
					?>
				</table>
				<table class="table-no-border" width="400">
				<tr>					
					<td width="40%">TOTAL </td>
					<td style="border-bottom: 1px solid"><h4>: PHP <?php echo number_format($sumIncome,2);?></h4></td>
					
				</tr>
			</table>
			</td>
			<td valign="top">
				<h4>IV. EXPENSES</h4>
				<table class="table-noborder" width="400">
					<tr>					
					<th width="40%">NATURE</th>
					<th>VALUE </th>
					</tr>
					<?php
						if(isset($expense)){				
							foreach($expense as $in) : ?>						
									<tr>										
										<td><?php echo $in['nature'];?></td>
										<td style="border-bottom: 1px solid">: <?php echo $in['value'];?></td>
									</tr>	
							<?php endforeach;
						}
						?>
				</table>
				<table class="table-no-border" width="400">
				<tr>					
					<td width="40%">TOTAL </td>
					<td style="border-bottom: 1px solid"><h4>: PHP <?php echo number_format($sumExpense,2);?></h4></td>
					
				</tr>
			</table>
			</td>
		</tr>
	</table>
	
	<h4>V. OUTSTANDING BALANCE</h4>
	<?php
	if(isset($creditor)){
		if($creditor->num_rows() >0){
		  $this->table->set_heading("Creditor", "Address", "Amount", "Remarks");
		  foreach($creditor->result() as $cre){
			  $this->table->add_row($cre->name, $cre->address, $cre->amount, $cre->remarks);
		  }
		  echo $this->table->generate();
		}else{
		  $this->table->set_heading("Creditors", "Address",  "Amount", "Remarks");
		  $this->table->add_row("&nbsp;","","","");
		  
		  echo $this->table->generate();
		}
	}	
	
	echo "<h4>VI. CREDIT REFERENCE:</h4>";
		$this->table->set_heading("Name", "Address","Telephone ");
		  $this->table->add_row("&nbsp;","","");
		 
		  echo $this->table->generate();
	
	echo "<h4>VII. ASSETS:</h4>";
		$this->table->set_heading("Title No.", "Lot No.","Area ", "Classification", "Motor/Vehicle/Appliances");
		  $this->table->add_row("&nbsp;","","","","");
		
		  echo $this->table->generate();
	?>
	<?php } ?>
	<div class="noheader">
	<h4> OTHERS</h4>
	<p>How long have you known the applicant?_____________ How are you related with the borrower?__________________________________<br/>
	Have you ever been a borrower? ________________________________________
	<br/>
	Name of the Lender/Lending Company/Financing ________________________________________<br/>
	Have you been a Co-Maker? ______________________________________<br/>
	</p>
	<p>
	I/We hereby certify that the foregoing information are true and correct. Furthermore, I/We authorize YUSAY CREDIT & FINANCE CORPORATION to obtain such other information as maybe required in connection with the loan applciation of <?php echo $lclient->LastName.", ".$lclient->firstName;?>.
	</p>
	
	<p>TIN No. _________________________ Community Tax Certificate No. ____________________ Place & Date Issued _________________________</p>
	
	<table>
		<tr>
			<td align="center" valign="bottom" height="100px">
			___________________________<br/>
			COMAKER<br/>
			Signature over Printed name</td>
			<td align="center" valign="bottom">___________________________<br/>SPOUSE<br/>Signature over Printed name</td>
		</tr>
	</table>
	

	<div>