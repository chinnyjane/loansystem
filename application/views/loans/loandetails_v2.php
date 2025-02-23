<script type="text/javascript" src="<?php echo base_url();?>assets/js/modaljs.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/loansprocess.js"></script>
<?php

$client = $loans['clientinfo']->row();
$donestatus = array("closed", "canceled", "litigated");
$loaninfo = $loans['loaninfo']->row();
$loanstatus = $loaninfo->status;

if ($client->image!='')
		$image = base_url().$client->image;
		else
		$image = 'holder.js/100%x180';

switch (strtolower($loanstatus)){
	case "processing";
		$color = "danger";
		$updateloan = true;
	break;
	case "approval";
		$color = "warning";
		$updateloan = true;
	break;
	case "approved";
		$color = "success";
		$updateloan = true;
	break;
	case "release";
		$color = "yellow";
		$updateloan = false;
	break;
	case "cancel";
		$color = "default";
		$updateloan = false;
	break;
	case "canceled";
		$color = "default";
		$updateloan = false;
	break;
	case "ci";
		$color = "info";
		$updateloan = true;
	break;
	case "granted";
		$color = "info";
		$updateloan = false;
	break;
	case "closed";
		$color = "warning";
		$updateloan = false;
	break;
	case "current";
		$color = "info";
		$updateloan = false;
	break;
	case "past due";
		$color = "info";
		$updateloan = false;
	break;
	default:
		$color = "primary";
		$updateloan = true;
	break;
}
 $loancode = $loaninfo->LoanSubCode;

$agent = $this->UserMgmt->get_user_byid($loaninfo->LoanProcessor);
$appr = $this->UserMgmt->get_user_byid($loaninfo->ApprovedBy);
	if($agent->num_rows() > 0 ){
		$a = $agent->row();
		$ag= $a->firstname." ".$a->lastname;
	}else $ag = '';
	
	if($appr->num_rows() > 0 ){
		$a = $appr->row();
		$ap= $a->firstname." ".$a->lastname;
	}else $ap = '';

	if(!empty($loaninfo->PN)){ 			
		$cv = $this->Loansmodel->cvexist($loaninfo->PN);
		if($cv->num_rows() > 0 ){
			$cv = $cv->row();
			
			$cvdate = $cv->dateOfTransaction;
		}
	}
	
	//DEBUG CODES HERE
	 if($this->auth->perms('debug',$this->auth->user_id(),3) == true) { 
		//$this->output->enable_profiler(TRUE);
		//echo $this->db->last_query();
		//echo "<pre>";
		//print_r($cv);
		//echo "</pre>";
	 }
	 $principal = floatval(str_replace(",","",$loaninfo->principalAmount));
	$netproceeds = floatval(str_replace(",","", $loaninfo->netproceeds));
	
	$monthly = ($loaninfo->MonthlyInstallment ? $loaninfo->MonthlyInstallment : ($loaninfo->extension ? ($loaninfo->approvedAmount/$loaninfo->extension) : ($loaninfo->approvedAmount/$loaninfo->Term)));
			if($loaninfo->productCode == 'PL'){
				$PLBal = $this->Loans->PLBalance($loaninfo->pensionID);
				if($PLBal->num_rows() >0){
					$plb = $PLBal->row();
					$totalBal = $plb->totalPL-$plb->totalPaid;
					$loans['LastDate'] = $plb->lastDate;
				}
			}else{
				$totalBal = $principal;
				$loans['LastDate'] = $loaninfo->MaturityDate;
			}		
?>
<div class="panel panel-primary with-nav-tabs">
	<div class="panel-heading">
		<ul class="nav nav-tabs" style="margin-bottom:0px">
			<li class="active"><a href="#computation" data-toggle="tab" >Computation</a></li>
			<li><a href="#schedule" data-toggle="tab">Ledger</a></li>	
			<li ><a href="#pensioninfo" data-toggle="tab">Collateral</a></li>	
			<?php if($loaninfo->LoanSubCode != 'E') { ?><li><a href="#requirements" data-toggle="tab">Requirements</a></li><?php } ?>	
			<li><a href="#comakerform" data-toggle="tab">Co-maker</a></li>
			<?php if($loaninfo->LoanSubCode != 'E') { ?><li><a href="#ciform" data-toggle="tab">CI Report</a></li><?php } ?>
			<?php if(strpos($loaninfo->productCode,"PL") !== false){ ?>
			<li><a href="#planalysis" data-toggle="tab">PL Analysis</a></li>
			<?php } ?>
		</ul>
	</div>
	<div class="panel-body">
		<div class="tab-content">
			<div class="tab-pane active" id="computation">
				<div class=""  id="feedetails">
		<?php 
		$fees = $loans['fees'];
		
		if($fees->num_rows() > 0){ ?>
		<table class="table-condensed table-nobordered" width="100%">
			
			<tr>
				<td><label>Principal: </label></td>
				<td align="right"><?php
				//if($loaninfo->computation == 'net')
					//echo ($loaninfo->approvedAmount ? number_format($loaninfo->approvedAmount / ((100+$loaninfo->interest) / 100),2) :  number_format($principal ,2));
					//echo number_format($principal,2);
				//else 
					echo ($loaninfo->approvedAmount ? number_format($loaninfo->approvedAmount,2) :  number_format($principal,2));?></td>
			</tr>
			<tr>
				<td><label>Interest: </label></td>
				<td align="right"><?php echo $loaninfo->interest;?> %</td>
			</tr>
			<tr>
				<td colspan="2"><label>FEES: </label></td>
			</tr>
			<?php 
			$total = 0;			
			foreach($fees->result() as $fee) { 
			$f=  floatval(str_replace(",","",$fee->value));
			$total += $f;		
			
			?>
			<tr>
				<td><label><?php echo $fee->feeName;?> </label></td>
				<td align="right"><?php echo number_format($f,2);?></td>
			</tr>
			<?php } ?>
			<tr>
				<td><label>Total Fees</label></td>
				<td align="right"><?php echo number_format($total,2);?></td>
			</tr>
			<tr style="color:red">
				<td><label>NET PROCEEDS</label></td>
				<td align="right"><h3><?php echo number_format($netproceeds,2);?></h3></td>
			</tr>
		</table>
			
		<?php }else{
			if(!in_array($loaninfo->status, $donestatus))
				echo "NOTE: Fee details was recorded on Old System (Linux).";
		}
		?>
		</div>
			</div>
			<div class="tab-pane  " id="schedule">
			<div class="panel pane-default">
				<?php 
						if($updateloan == true){
							echo $this->loansetup->loanschedule($loanid);
						}else{
							if($loans['schedule']->num_rows() > 0){
								$loanbal = $loaninfo->approvedAmount;
								foreach($loans['schedule']->result() as $sch){
									if($sch->LoanBalance == NULL)
										$loanbal -= $sch->Paid;
									else
										$loanbal = $sch->LoanBalance;
									//echo $loanbal;
									$paid = ($sch->Paid ? $sch->Paid : 0);
									$this->table->add_row($sch->order, $sch->DueDate,number_format($sch->AmountDue,2), number_format($paid,2), $sch->DatePaid);
								}
								$this->table->set_heading("#", "Due Date","Amount Due", "Amount Paid","Date Paid");
								echo $this->table->generate();
							}
						}
						
						if($this->auth->perms('Generate Schedule',$this->auth->user_id(),3) == true) {  
						echo "<code>If the PN Schedule is incorrect, please re-generate the schedule by pressing the Generate Schedule button.</code>";
						?>
						<form action="<?php echo base_url();?>loans/overview/generate_loanschedule" id="genform" method="post">
							<?php
						
							if($loancode == 'E' or $loaninfo->extension > 0){
								$year = date("Y", strtotime($loaninfo->dateStartPayment."-1 month"));
								$m = date("m", strtotime($loaninfo->dateStartPayment."-1 month"));
								$d = date("d", strtotime($loaninfo->DateDisbursed));
								$date = $year."-".$m."-".$d;
								//echo $date;
								$lterm =  $loaninfo->extension;
							
							
							} else {
								$lterm = $loaninfo->Term;
								if($loaninfo->status != 'granted' and $loaninfo != 'approved' )
									$date = date("Y-m-d", strtotime($loaninfo->dateApplied));
								else $date =  date("Y-m-d", strtotime($loaninfo->DateDisbursed));
											//echo $date;
							} 
									if( $loaninfo->approvedAmount != '' )
											$amount =  $loaninfo->approvedAmount;
									else
										$amount = $loaninfo->AmountApplied;						
								?>
								<input type="hidden" name="term" value="<?php echo $lterm; ?>">
							<input type="hidden" name="dateDisbursed" value="<?php echo date("Y-m-d",strtotime($date));?>">	
							<input type="hidden" name="approveamount" value="<?php echo $amount;?>">
								
						<?php 
						
						if($this->auth->perms('debug',$this->auth->user_id(),3) == true) {  
							echo $date;
							echo $amount;
						}
						?>
							
							<input type="hidden" name="loanid" value="<?php echo $loanid;?>">
							<input type="hidden" name="method" value="<?php echo $loaninfo->paymentmethod;?>">					
							<input type="submit" name="submit" value="Generate Loan Schedule" id="generate" class="btn btn-sm btn-primary">				
						</form>
						<?php }
						?>
				<div class="panel-footer">
					<a href="<?php echo base_url();?>forms/ledger/<?php echo $loanid;?>" class="btn btn-sm btn-primary" target="_blank">Print Ledger Card</a>
				</div>
				</div>
			</div>
			<div class="tab-pane  " id="pensioninfo">	
			<div class="panel panel-default">
			<div class="panel-heading" role="tab" id="headingTwo">
			  <h4 class="panel-title">II. Collateral Information </h4>
			</div>
			<div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">
				<form action="<?php echo base_url();?>loans/application/updatecollateral" id="loancollateral" method="post">
				
			  
			   <div class="panel-body">
			  <?php
				$collaterals = $loans['collaterals'];	
				if(!empty($collaterals)){
					//echo $loaninfo->pensionID;
				if($collaterals->num_rows() > 0){	
					echo '<table class="table table-bordered table-condensed" width="100%">';					
					foreach($collaterals->result() as $col){ 
					
						if(strpos($loaninfo->productCode,"PL") !== false){
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
						?>
						<tr>
							<td width="30%" style="vertical-align: middle" align="right"><label><?php echo $col->collateralname;?> : </label></td>
							<td><input type="text" class="input-sm form-control" name="colvalue[<?php echo $col->colID;?>]" value="<?php echo $col->value;?>" <?php if($updateloan == false) echo "readonly";?> ></td>
						</tr>				
						<?php 
						}
					}
					echo "</table>";
				}else{
					
					if(!in_array($loaninfo->status, $donestatus)){
						echo $this->Loansmodel->getcollaterals($loaninfo->productCode,$loaninfo->LoanType,$loaninfo->pensionID);
						
					}else
						echo "NOTE: Collateral Information was filed on Old System (linux)";
				}
				}
				
				if(strpos($loaninfo->productCode,"PL") !== false){
					//echo '<a class="btn btn-sm btn-primary" href="'.base_url().'client/profile/'.$clientid.'/pension/'.$loaninfo->pensionID.'">Update Pension Info</a><br/><br/>';
				}
				 
			   if($this->auth->perms('Loan Details',$this->auth->user_id(),3) == true) { 
				
				   if($loaninfo->productCode == 'PL'){
					   echo "<code>If Collateral is incorrect, please choose the correct Pension below. </code> <br/><br/>";
					   $pension = $this->Loansmodel->get_pensionofclient($clientid, $client->CNO, $client->branchID);
					   if($pension->num_rows() > 0){
							?>
						   <select name="pensionID" class="input-sm">
						   <?php 
							$count = 1;
						   foreach($pension->result() as $pe){				
								 
						   ?>
						   <option value="<?php echo $pe->PensionID;?>" <?php  if($loaninfo->pensionID ==$pe->PensionID ) echo "selected";?>><?php echo $count." - ".$pe->bankCode." - ".$pe->PensionNum." - Monthly Pension: ".number_format($pe->monthlyPension,2);?></option>					   
						   <?php
							$count ++;
						   } ?>
						   </select>
						   <?php
					   }
					   echo '<input type="submit" name="submit" value="Submit Pension" class="btn btn-sm btn-success" >';
					   echo '<input type="hidden" name="loanid" value="'.$loanid.'" class="btn btn-sm btn-primary" >';
				   }
			   } 
				
				
				?>
				
			  </div>
			  <div class="panel-footer">
				<?php if($updateloan == true):?>
					<input type="submit" name="submit" value="Update Collateral" class="btn btn-sm btn-primary" >
				<?php endif;?>
			  </div>
			  </form>
			</div>
		  </div>  
		</div>
		<div class="tab-pane" id="requirements">
		<div class="panel panel-default">
			<div class="panel-heading" role="tab" id="headingThree">
			  <h4 class="panel-title">
				III. Requirements <i class="fa fa-caret-down"></i>
			  </h4>
			</div>
			<div id="collapseThree" class="panel-collapse collapse in " role="tabpanel" aria-labelledby="headingThree">
			  <form action="" method="post" id="requirementpost">
				<?php 
				$reqs = $loans['req'];			
						
					if(in_array($loaninfo->status, $donestatus))
						echo "NOTE: Requirements Information was filed on hardcopy.";
					else{
						echo $reqs['req'];
					}				
				?>
				<div class="panel-footer">
					<input type="hidden" name="loanid" value="<?php echo $loanid;?>">
					<?php if($updateloan == true) { ?>
					<input type="button" id="reqsubmit"  class="btn btn-sm btn-primary" name="submit" value="Submit Requirements">
					<?php } ?>
				</div>
				</form>
			</div>
		  </div> 
		  
		</div>
		<div class="tab-pane" id="comakerform">
			
			<div id="Comakers" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingThree">
			  
			  
				<?php	
				echo "<ol id='comakerlist'>";		
					if(!empty($loans['comaker'])){				
						if($loans['comaker']->num_rows() > 0){
							
							foreach($loans['comaker']->result() as $com){
								if($com->clientID != ''){
								$clientinfo = $this->Clientmgmt->getclientinfoByID($com->clientID)->row();
								echo '<li><a href="'.base_url().'client/profile/'.$com->clientID.'" target="_blank">'.$clientinfo->LastName.", ".$clientinfo->firstName.'</a> &nbsp; 
								<input type="hidden" name="comakerid" id="mycom" value="'.$com->clientID.'">'; ?>
								<a href="<?php echo base_url();?>forms/comaker/<?php echo $loanid;?>/<?php echo $com->clientID;?>" target="_blank">Co-maker's Statement</a>
								<?php if($updateloan == true)
									echo '<button class="remcom btn btn-sm btn-default">Rem</button></li>';	
								}
									
							}
							
						}
					}else echo "No Comakers yet.";
					echo "</ol>";
				?>		
				
			 
					<?php if($updateloan ==true and $loaninfo->productCode != 'PL') { ?>
					<button class="btn btn-sm btn-primary" data-toggle="modal" data-backdrop="static"  data-target="#upcomaker" href="#"><i class="fa fa-pencil"></i> Add Comaker</button>
					<?php } ?>
			 
			</div>
		  

		</div>
		 <div class="tab-pane" id="ciform">
		<div class="panel panel-default">
			<div class="panel-heading" role="tab" id="headingThree">
			  <h4 class="panel-title">
				  Credit Investigation Report <i class="fa fa-caret-down"></i>
			  </h4>
			</div>
			<form action="<?php echo base_url();?>loans/application/cireport" method="post" id="ciformpost">
			<div id="ci" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingThree">
			 <div class="panel-body">
			  <?php	
			  
				if($loans['ci']->num_rows() > 0){
					foreach($loans['ci']->result() as $ci){
						if($this->auth->perms('debug',$this->auth->user_id(),3) == true) { 
							/*echo "<pre>";
							print_r($ci);
							echo "</pre>";*/
						}
						if(isset($ci->value))
						$val = $ci->value ? $ci->value : '';
						else $val = '';
						echo "<label>".strtoupper($ci->ci_name).":</label>";
						if($updateloan == true){
							if($ci->datatype != 'longtext')
							echo "<input type='".$ci->datatype."' name='ci[".$ci->ci_id."]' value='".$val."' class='input-sm form-control'>";
							else
								echo '<textarea name="ci['.$ci->ci_id.']" class="input-sm form-control" rows="15">'.$val.'</textarea>';
						}else{
							echo "<p>".nl2br($val)."</p>";
						}
						//if($ci->modifiedBy == null)
							//$ciu = $ci->addedBy;
						//else $ciu = $ci->modifiedBy;
						//$ciuser = $this->UserMgmt->get_user_byid($ciu)->row();		
						
					}
					//echo "<br/><small><b>CI By: </b>".$ciuser->lastname.", ".$ciuser->firstname.'</small>';
				}
			  ?>
			 </div>
			  <div class="panel-footer">
					<input type="hidden" name="loanid" value="<?php echo $loanid;?>">
					<?php if($updateloan ==true) { ?>
					<?php if($this->auth->perms('CI',$this->auth->user_id(),1) == true) { ?>
					<input type="submit" name="sub" value="Submit CI Report"  id="ciformsub" class="btn btn-sm btn-primary" >
					<?php } 
					}?>
			  </div>
			  </form>
			</div>
			
		  </div>

		</div>
		<div class="tab-pane" id="planalysis">
			
			<div style="overflow-x: scroll; border: thin solid #ccc;" >
				<?php
				$p['pl']= $this->Clientmgmt->planalysis($loaninfo->pensionID);
				$p['pensionid'] = $loaninfo->pensionID;
				$p['thispn'] = $loaninfo->PN;
				$p['maxterm'] = $loaninfo->maxTerm;
				$this->load->view('loans/forms/planalysis', $p);
				?>
			
		</div>
		</div>

	</div>
</div>