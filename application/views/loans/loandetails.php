
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
	case "declined";
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
	if($loaninfo->approvedAmount <= 0)
	$principal = floatval(str_replace(",","",$loaninfo->principalAmount));
	else $principal = floatval(str_replace(",","",$loaninfo->approvedAmount));
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
	$cv = $this->Transaction->byLoanID($loanid);
						
?>

<div class="row form-group">
<div class="col-lg-12">
	<div class="col-lg-3 col-md-3 alert alert-<?php echo $color;?>">
		<b>LOAN STATUS : </b><?php echo strtoupper($loanstatus);?>
	</div>
	<div class="col-lg-3 col-md-3  alert alert-danger">
		<b><?php echo "<label>Total Loan Balance : Php ".number_format($totalBal,2).'</label>';?></b>
	</div>
</div>
<div class="col-md-9">
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingOne">
      <h4 class="panel-title">       
         I. Loan Information <a class="pull-right" href="<?php echo base_url();?>forms/application/<?php echo $loanid;?>" target="_blank"><i class="fa fa-print"></i> PRINT FORM</a>
      </h4>
    </div>
    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
      <div class="">
	  
        <?php 		
		if($loans['loaninfo']->num_rows() > 0){			
			
			?>
			<table class=" table-condensed table-bordered" width="100%">
				<thead>
					<tr>
						<th colspan="2">
							<label>Client's Name: </label>
							<a href="<?php echo base_url();?>client/profile/<?php echo $clientid;?>"><?php echo $client->LastName.", ".$client->firstName." ".$client->MiddleName; ?></a>						
							
						</th>
						<th>
							<label> PN: <?php echo $loaninfo->PN;?></label> 
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td align="center"><img src="<?php echo $image;?>" width="250px">
						</td>
						<td colspan="2" style="vertical-align: top">
							<label>Age : </label> <?php echo $this->loansetup->get_age($client->dateOfBirth); ?> yrs. old <br/>
							<label>Date of Birth : </label> <?php echo date("F d, Y", strtotime($client->dateOfBirth)); ?> <br/>
							<label>Civil Status : </label> <?php echo $client->civilStatus; ?> <br/>
							<label>Gender : </label> <?php echo $client->gender; ?> <br/>
							<label>Address: </label> <?php echo $client->address.", ".$client->barangay.", <br/> ".$client->cityname.", ".$client->provname; ?> <br/>
						</td>
					</tr>
					<tr>
						<td><label>Type of Loan: </label> <?php echo strtoupper(substr($loaninfo->pensionType, 0,1)).$loaninfo->productCode."-".$loaninfo->LoanCode;?></td>
						<td><label>Status: </label> <?php $loancode = $loaninfo->LoanSubCode;
						switch($loancode){
							case 'N':
								echo "New";
							break;
							case 'E':
								echo "Extension";
							break;
							case 'A':
								echo "Additional";
							break;
							case 'R':
								echo "Renewal";
							break;
						}
						
						switch($loaninfo->paymentmethod){
							case 'M':
								$pterm = "Monthly";
							break;
							case 'L':
								$pterm = "Lumpsum";
							break;
							case 'SM':
								$pterm = "Semi-Monthly";
							break;
						}
						?></td>
						<td><label>Payment Term: </label> <?php echo $pterm;?></td>
					</tr>
					<tr>
						<td><label>Applied Amount: </label> <?php echo number_format($loaninfo->AmountApplied,2). " - ". $loaninfo->computation; ?></td>
						<td><label>Approved Amount : </label> <label><?php 
						if(strtolower($loanstatus) == "processing" or strtolower($loanstatus) == "approval") echo "NOT YET APPROVED.";
						//if($loaninfo->computation == 'net')
							//echo number_format($loaninfo->approvedAmount / ((100+$loaninfo->interest) / 100), 2 );
						//else 
						else	echo number_format( $loaninfo->approvedAmount ,2);
						?></label></td>
						<td><label>Net Proceeds: </label> <?php echo number_format($netproceeds,2) ;?></td>
					</tr>
					<tr>
						<td><label>Terms: </label> <?php echo $loaninfo->Term; ?> month(s) <?php echo ($loaninfo->extension ? " - ".$loaninfo->extension." mos ext." : '');?></td>
						<td><label>Interest : </label> <?php echo $loaninfo->interest;?> %</td>
						<td><label>Monthly Installment: </label> <?php echo round($loaninfo->MonthlyInstallment);?></td>
					</tr>
					<tr>
						<td><label>Date Applied: </label> <?php echo date("F d, Y", strtotime($loaninfo->dateApplied)); ?> </td>
						<td><label>Date Approved : </label> <?php if(strtolower($loanstatus) == "processing" or strtolower($loanstatus) == "approval") echo "NOT YET APPROVED.";
						else echo $loaninfo->dateApproved;?> </td>
						<td><label>Date of Maturity: </label> <?php echo $loaninfo->MaturityDate;?></td>
					</tr>
					<tr>
						<td><label>Loan Processor: </label> <?php echo $ag; ?> </td>
						<td><label>Approved By: </label> <?php if(strtolower($loanstatus) == "processing" or strtolower($loanstatus) == "approval") echo "NOT YET APPROVED."; else echo $ap;?> </td>
						<td><label>Released Date: </label> <?php echo $loaninfo->DateDisbursed;?></td>
					</tr>
					<tr>
						<td colspan="3">
						<label>Remarks : </label>
						<font color="red"><?php echo $loaninfo->remarks;?></font>
						<br/>
						<p>
						Promissory Note Remarks: <?php echo $loaninfo->PN_remarks;?><br/><br/>
						<button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#pnremarks" href="#">Change PN Remarks</button>
						<?php if($this->auth->perms('Loan Details', $this->auth->user_id(), 3) == true ){ 
						
						//if($this->auth->perms('debug', $this->auth->user_id(), 3)){?>
						&nbsp; &nbsp; 	<button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#updateMaturity" href="#">Change Date of Transactions</button><?php } ?>
						</p>
						</td>
					</tr>
				</tbody>
			</table>
		<?php	
			
		}else{ 
		
			echo "No Loans was made. Please enter details.";			
			$this->load->view('loans/addloan');
		}
		?>	
      </div>
	  <div class="panel-footer">
	<?php 
	switch ($loanstatus){
		case 'processing':
		?>
       
        	<button class="btn btn-sm btn-warning" id="cancelloan" type="button"><li class="fa fa-unlink"></li> Cancel Loan</button>
            &nbsp;&nbsp;
			<?php if($this->auth->perms('Submit for Approval',$this->auth->user_id(),2) == true) { ?>
           <form action="<?php echo base_url();?>loans/application/finalsubmission" method="post" id="forapproval">	
				<input type="hidden" name="loanid" id="loanid" value="<?php echo $loanid;?>">
				<input type="submit" name="submit" value="Submit for Approval" class="btn btn-primary ">
			</form>
			<?php } ?>
		<?php
        break;
		case 'approval':			
			
			//productID to be changed to loanTypeID
			
			if($this->auth->perms('Approve Loan',$this->auth->user_id(),3) == true and $this->auth->loanapproval($loaninfo->loanTypeID, $this->auth->user_id(), $loaninfo->branchID, $totalBal) == true)
			{ //echo "For Approval";
		
			?>
			
			<form action="<?php echo base_url();?>loans/application/action/approve" id="loanappform" method="post">		
				
				<div class="row form-group">
                    <div class="col-md-4">	
						<label>Amount Approved : </label>
                        <div class=" input-group">
                            <span class="input-group-addon">Php</span>
                            <input type="text" name="approvedamount" value="<?php echo $loaninfo->principalAmount;?>" class="input-sm form-control number">
							<input type="hidden" name="appliedamount" value="<?php echo $loaninfo->AmountApplied;?>" >
							<input type="hidden" name="principalAmount" value="<?php echo $loaninfo->principalAmount;?>" >							
							<input type="hidden" name="method" value="<?php echo $loaninfo->PaymentTerm;?>" >
							<input type="hidden" name="startpayment" value="<?php echo $loaninfo->dateStartPayment;?>" >
                            <input type="hidden" name="loanid" value="<?php echo $loanid;?>" >					
                            <input type="hidden" name="pid" value="<?php echo $loaninfo->LoanType;?>" >				
                            <input type="hidden" name="computation" value="<?php echo $loaninfo->computation;?>" >				
                        </div>
                    </div>
					<?php if ( $loancode != 'E'){ ?>
					<div class="col-md-3">
						<label>Term:</label>					
						<select name="term" id="term" class="form-control input-sm"  required>
						<?php $count = $loaninfo->minTerm; 
							  $maxterm = $loaninfo->maxTerm;							   
							while ($count <= $maxterm) {												
								?> 
								<option value='<?php echo $count;?>' <?php  if($count == $loaninfo->Term) echo "selected";?>><?php echo $count;?> &nbsp; month(s)</option>
							<?php $count++;}?>
						</select>					
					</div>  
					<?php }else{ ?>
					<div class="col-md-3">
						<label>Term:</label>
						<input type="hidden" id="basedTerm" value="<?php echo $loaninfo->Term-$loaninfo->extension;?>">
						<input type="text" value="<?php echo  $loaninfo->Term;?>" name="term" id="term" class="form-control input-sm" >
					</div>
					<div class="col-md-3">
						<label>Extension</label>
						<select name="extendedTerm" id="nextendedTerm" class="form-control input-sm"  >
						<?php $count = 1; 
							  $baseTerm = $loaninfo->Term - $loaninfo->extension;
							  $maxterm = $loaninfo->maxTerm - $baseTerm;	   
							while ($count <= $maxterm) {												
								?> 
								<option value='<?php echo $count;?>' <?php  if($count == $loaninfo->extension) echo "selected";?>><?php echo $count;?> &nbsp; month(s)</option>
							<?php $count++;}?>
						</select>
					</div>
					<?php } ?>
					
                </div>	
				<div class="row form-group">
					<div  class="col-md-12">
						<label>Remarks</label>
						<textarea class="input-sm form-control" name="remarks"></textarea>	
					</div>
				</div>
				<div class="row form-group">
					<div class="col-md-12">						
                        <button class="btn btn-sm btn-success"   id="approveloan"><li class="fa fa-check"></li> Approve Loan</button> &nbsp; 
                        <button class="btn btn-sm btn-danger" type="button" id="declineloan" ><li class="fa fa-times"></li> Decline Loan</button>  &nbsp; 
                        <button class="btn btn-sm btn-warning" id="cancelloan" type="button"><li class="fa fa-unlink"></li> Cancel Loan</button>
            &nbsp;&nbsp;
                    </div>
				</div>
			</form>
		<?php } else { ?>
		
			<button class="btn btn-sm btn-warning" id="cancelloan" type="button"><li class="fa fa-unlink"></li> Cancel Loan</button>
			
		<?php }
		
		 ?>
			
		<?php break;
		case 'approved':  ?>
		
		<?php
			if($this->auth->perms('Revoke Approval',$this->auth->user_id(),1) == true){ ?>
			<form action="<?php echo base_url();?>loans/application/changestatus" method="post" class="formpost">	
			
			<input type="hidden" name="loanid" value="<?php echo $loanid;?>">
			<input type="hidden" name="status" value="approval">
			<input type="submit" name="submit" value="Revoke Approval" class="btn btn-sm btn-warning">&nbsp;&nbsp;			
			</form>
			<button class="btn btn-sm btn-warning" id="cancelloan" type="button"><li class="fa fa-unlink"></li> Cancel Loan</button>
			<?php 
			}
			if(empty($loaninfo->PN)){ 
					$bookpn = '';
					echo '<button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#assignpn" href="#">Assign Promissory Note (PN)</button>';
					echo '&nbsp;';
			}else{
				echo '<label>PN : '.$loaninfo->PN.' &nbsp;</label>';
				echo '<button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#assignpn" href="#">Edit Promissory Note (PN)</button>';echo '&nbsp; <button class="btn btn-success btn-sm" data-toggle="modal" data-backdrop="static" data-target="#disburse" href="#">Create CV</button>';				
			}
	
			echo '&nbsp; '
				.'<a class="btn btn-default btn-sm" href="'.base_url().'forms/disclosure/'.$loanid.'" target="_blank">Disclosure Statement</a>
			&nbsp;'
				.'<a class="btn btn-default btn-sm" href="'.base_url().'forms/promissory/'.$loanid.'" target="_blank">Promissory Note</a>
			&nbsp;'
				.'<a class="btn btn-default btn-sm" href="'.base_url().'forms/computation/'.$loanid.'" target="_blank">Computation Sheet</a>
			&nbsp;';
			
			if(strpos($loaninfo->productCode, "PL") !== FALSE){
				echo '<a class="btn btn-default btn-sm" href="'.base_url().'forms/rfplmonitoring/'.$loanid.'" target="_blank">RFPL Monitoring</a>'
					.'&nbsp;'
					.'<a class="btn btn-default btn-sm" href="'.base_url().'forms/rfplagreement/'.$loanid.'" target="_blank">RFPL Agreement</a>'
					.'&nbsp;'
					.'<a class="btn btn-default btn-sm" href="'.base_url().'forms/deed/'.$loanid.'" target="_blank">Deed of Undertaking</a>';
					
			}
		break;
		
		case 'release':
			
			if(!empty($loaninfo->PN)){ 			
				//$cv = $this->Loansmodel->cvexist($loaninfo->PN);
				
				if($cv->num_rows() > 0 ){
					$cv = $cv->row();
					echo '<a class="btn btn-default btn-sm" href="'.base_url().'cash/disbursements/printcv/'.$cv->gl_id.'">Check Voucher</a>';
					//echo '<a class="btn btn-default btn-sm" href="'.base_url().'forms/checkvoucher/'. $loanid.'" target="_blank">Check Voucher</a>';
				}
			}
			echo '&nbsp; '
				.'<a class="btn btn-default btn-sm" href="'.base_url().'forms/disclosure/'.$loanid.'" target="_blank">Disclosure Statement</a>
			&nbsp;'
				.'<a class="btn btn-default btn-sm" href="'.base_url().'forms/promissory/'.$loanid.'" target="_blank">Promissory Note</a>
			&nbsp;'
				.'<a class="btn btn-default btn-sm" href="'.base_url().'forms/computation/'.$loanid.'" target="_blank">Computation Sheet</a>
			&nbsp;';
			
			if(strpos($loaninfo->productCode, "PL") !== FALSE){
				echo '<a class="btn btn-default btn-sm" href="'.base_url().'forms/rfplmonitoring/'.$loanid.'" target="_blank">RFPL Monitoring</a>'
					.'&nbsp;'
					.'<a class="btn btn-default btn-sm" href="'.base_url().'forms/rfplagreement/'.$loanid.'" target="_blank">RFPL Agreement</a>'
					.'&nbsp;'
					.'<a class="btn btn-default btn-sm" href="'.base_url().'forms/deed/'.$loanid.'" target="_blank">Deed of Undertaking</a>';
					
			}
		break;
		case 'current':
			if(!empty($bookpn)){ 			
				if($cv->num_rows() > 0 ){
					$cv = $cv->row();
					echo '<a class="btn btn-default btn-sm" href="'.base_url().'cash/disbursements/printcv/'.$cv->gl_id.'">Check Voucher</a>';
					//echo '<a class="btn btn-default btn-sm" href="'.base_url().'forms/checkvoucher/'. $loanid.'" target="_blank">Check Voucher</a>';
				}
			}
			if($this->auth->perms('Loan Details',$this->auth->user_id(),3) == true)  {
					echo '&nbsp; ';
					echo '<button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#assignpn" href="#">Edit PN Number</button>';
				}
			echo '&nbsp; '
				.'<a class="btn btn-default btn-sm" href="'.base_url().'forms/disclosure/'.$loanid.'" target="_blank">Disclosure Statement</a>
			&nbsp;'
				.'<a class="btn btn-default btn-sm" href="'.base_url().'forms/promissory/'.$loanid.'" target="_blank">Promissory Note</a>
			&nbsp;'
				.'<a class="btn btn-default btn-sm" href="'.base_url().'forms/computation/'.$loanid.'" target="_blank">Computation Sheet</a>
			&nbsp;'
				.'<a class="btn btn-default btn-sm" href="'.base_url().'forms/rfplmonitoring/'.$loanid.'" target="_blank">RFPL Monitoring</a>';
		break;
		default:
			
			if(!empty($loaninfo->PN)){
				//echo '<button class="btn btn-sm btn-warning" id="cancelloan" type="button"><li class="fa fa-unlink"></li> Cancel Loan</button>';
				if($this->auth->perms('Loan Details',$this->auth->user_id(),3) == true)  {
					echo '&nbsp; ';
					echo '<button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#assignpn" href="#">Edit PN Number</button>';
				}
				echo '&nbsp; ';
				if($cv->num_rows() > 0 ){
					$cv = $cv->row();
					echo '<a class="btn btn-default btn-sm" href="'.base_url().'cash/disbursements/printcv/'.$cv->gl_id.'">Check Voucher</a>';
					//echo '<a class="btn btn-default btn-sm" href="'.base_url().'forms/checkvoucher/'. $loanid.'" target="_blank">Check Voucher</a>';
				}
				
				echo '&nbsp; '
					.'<a class="btn btn-default btn-sm" href="'.base_url().'forms/disclosure/'.$loanid.'" target="_blank">Disclosure Statement</a>
				&nbsp;'
					.'<a class="btn btn-default btn-sm" href="'.base_url().'forms/promissory/'.$loanid.'" target="_blank">Promissory Note</a>
				&nbsp;'
					.'<a class="btn btn-default btn-sm" href="'.base_url().'forms/computation/'.$loanid.'" target="_blank">Computation Sheet</a>
				&nbsp;';
				
				if(strpos($loaninfo->productCode, "PL") !== FALSE){
					echo '<a class="btn btn-default btn-sm" href="'.base_url().'forms/rfplmonitoring/'.$loanid.'" target="_blank">RFPL Monitoring</a>'
						.'&nbsp;'
						.'<a class="btn btn-default btn-sm" href="'.base_url().'forms/rfplagreement/'.$loanid.'" target="_blank">RFPL Agreement</a>'
						.'&nbsp;'
						.'<a class="btn btn-default btn-sm" href="'.base_url().'forms/deed/'.$loanid.'" target="_blank">Deed of Undertaking</a>';
						
						
				}
			}
		break;
	}
	?>
</div>
</div>
</div>  
 </div>
 
 <!-- END OF LOAN INFORMATION -->
 
 <!-- CHECK FOR LOAN EXTENSION -->
 <?php
 
// if($this->auth->perms('debug',$this->auth->user_id(),3) == true) { 	
	$this->load->view('loans/extension', $loans);
 //}
 ?>
 <!--  ================ END HERE ===========================-->
 
 <ul class="nav nav-tabs" id="myTab">
	<?php if($loanstatus != 'processing' and $loanstatus !='approval') { ?>
		<li><a href="#transaction">Transactions</a></li>
	<?php }?>
	<li><a href="#schedule">Ledger</a></li>	
	<li ><a href="#pensioninfo" data-toggle="tab">Collateral</a></li>	
	<?php // if($loaninfo->LoanSubCode != 'E') { ?><li><a href="#requirements" data-toggle="tab">Requirements</a></li><?php // } ?>	
	<li><a href="#comakerform" data-toggle="tab">Co-maker</a></li>
	<?php //if($loaninfo->LoanSubCode != 'E') { ?><li><a href="#ciform" data-toggle="tab">CI Report</a></li><?php //} ?>
	<?php if(strpos($loaninfo->productCode,"PL") !== false){ ?>
	<li><a href="#planalysis" data-toggle="tab">PL Analysis</a></li>
	<?php } ?>
</ul>
<div class="tab-content">
	<div class="tab-pane  " id="transaction">
		<div class="panel pane-default">
			<div class="panel-body">
			<?php
			$trans = $this->Accounting->LoanTrans($loanid);
			
			if($trans){
					
					foreach($trans->result() as $tr){				
					
					if($tr->transCatName == 'disbursement')
					$this->table->add_row($tr->date_added, $tr->transType, $tr->reference_no, number_format($tr->dr_total,2), number_format($tr->cr_total, 2), '<a href="'.base_url().'cash/disbursements/printcv/'.$tr->gl_id.'">Check Voucher</a>');
					else
					$this->table->add_row($tr->date_added, $tr->transType, $tr->reference_no, number_format($tr->dr_total,2), number_format($tr->cr_total, 2), '<a href="'.base_url().'cash/disbursements/details/'.$tr->gl_id.'">View Details</a>');
				}
				$this->table->set_heading("Date", "Trans Type", "Reference No", "DR", "CR");
				echo $this->table->generate();
			}
			?>
			</div>
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
				
				if($this->auth->perms('Generate Schedule',$this->auth->user_id(),3) == true ) {  
				echo "<code>If the PN Schedule is incorrect, please re-generate the schedule by pressing the Generate Schedule button.</code>";
				?>
				<form action="<?php echo base_url();?>loans/overview/generate_loanschedule" id="genform" method="post" >
					<?php
				
					if($loancode == 'E' or $loaninfo->extension > 0){
						$lterm =  $loaninfo->extension;
						//if($loaninfo->dateStartPayment != '0000-00-00'){
							/*$year = date("Y", strtotime($loaninfo->dateStartPayment."-1 month"));
							$m = date("m", strtotime($loaninfo->dateStartPayment."-1 month"));
							//$d = date("d", strtotime($loaninfo->DateDisbursed));		
							$myDate = explode('-', date("Y-m-d", strtotime($loaninfo->DateDisbursed)));
							$d = date( "d", mktime(0,0,0 ,$myDate[1] - 1 ,$myDate[2],$myDate[0]) );	
							//echo $loaninfo->dateStartPayment;*/
							
						//}else{
							//echo strtotime($loaninfo->MaturityDate);
							$year = date("Y",strtotime(" -".$lterm." month", strtotime($loaninfo->MaturityDate)));							
							$m = date("m",strtotime(" -".$lterm." month", strtotime($loaninfo->MaturityDate)));					
							$myDate = explode('-', $loaninfo->MaturityDate );
							$d = date( "d", mktime(0,0,0 ,$myDate[1] - 1 ,$myDate[2],$myDate[0]) );		
							//echo $d;							
						//}
						$date = $year."-".$m."-".$d;
						
						
					
					
					} else {
						$lterm = $loaninfo->Term;
						//if($loaninfo->status != 'granted' and $loaninfo != 'approved' )
							//$date = date("Y-m-d", strtotime($loaninfo->dateApplied));
						//else 
							$date =  date("Y-m-d", strtotime($loaninfo->DateDisbursed));
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
					//echo $d;
					echo "<br/>";
					echo $date;
					echo "<br/>";
					echo $loaninfo->MaturityDate;
					echo "<br/>";
					echo $amount;
				}
				?>
					
					<input type="hidden" name="loanid" value="<?php echo $loanid;?>">
					<input type="hidden" name="method" value="<?php echo $loaninfo->paymentmethod;?>">					
					<input type="hidden" name="startpayment" value="<?php echo $loaninfo->dateStartPayment;?>">					
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
		<?php if($updateloan == true and $loaninfo->productCode != 'PL'):?>
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
<div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingThree">
      <h4 class="panel-title">
          IV. Comakers <i class="fa fa-caret-down"></i>
      </h4>
    </div>
    <div id="Comakers" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingThree">
      <div class="panel-body">
	  <div>
	  <?php
		$com['comaker'] = $loans['comaker'];
		$com['loanid'] = $loanid;
		$com['updateloan'] = $updateloan;
	  $this->load->view('loans/comakerlist', $com);?>        
		</div>
      </div>
	  <div class="panel-footer">
			<?php //if($updateloan ==true) { ?>
			<button class="btn btn-sm btn-primary" data-toggle="modal" data-backdrop="static"  data-target="#upcomaker" href="#"><i class="fa fa-pencil"></i> Add Comaker</button>
			<?php //} ?>
	  </div>
    </div>
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
	<div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingThree">
      <h4 class="panel-title">
          PL Analysis <i class="fa fa-caret-down"></i>
      </h4>
    </div>
	<div class="panel-body" style="overflow-x: scroll; border: thin solid black;" >
		<?php
		$p['pl']= $this->Clientmgmt->planalysis($loaninfo->pensionID);
		$p['pensionid'] = $loaninfo->pensionID;
		$p['thispn'] = $loaninfo->PN;
		$p['loanID'] = $loaninfo->loanID;
		$p['maxterm'] = $loaninfo->maxTerm;
		$this->load->view('loans/forms/planalysis', $p);
		?>
	</div>
  </div>
</div>
</div>

</div>
	
<div class="col-sm-3">
	
	<div class="panel panel-success">
		<div class="panel-heading">			
			  Loan Computation 
		</div>
		<div class=""  id="feedetails">
		
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
				
		$fees = $loans['fees'];
		
		if($fees->num_rows() > 0){ 
			foreach($fees->result() as $fee) { 
			$f=  floatval(str_replace(",","",$fee->value));
			$total += $f;		
			
			?>
			<tr>
				<td><label><?php echo $fee->feeName;?> </label></td>
				<td align="right"><?php echo number_format($f,2);?></td>
			</tr>
			<?php }
			}
			?>
			<tr>
				<td><label>Total Fees</label></td>
				<td align="right"><?php echo number_format($total,2);?></td>
			</tr>
			<tr style="color:red">
				<td><label>NET PROCEEDS</label></td>
				<td align="right"><h3><?php echo number_format($netproceeds,2);?></h3></td>
			</tr>
		</table>
			
		
		</div>
		<div class="panel-footer">
			<?php if($updateloan == true){ ?>
			<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#upfeesform" data-backdrop="static"  href="#">Update Fees</button>
			<?php } ?>
		</div>
	</div>	
</div>
</div>


<!-- MODALS HERE -->

<!-- PN REMARKS -->
<div class="modal fade" id="pnremarks" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog ">
		<div class="modal-content">
		<form action="<?php echo base_url();?>loans/application/remarks" id="remarksform" method="post">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Promissory Note Remarks</h4>				
			</div>
			
			<div class="modal-body">
				<label>
					PN Remarks:
				</label>
				<textarea name="pn_remarks" class="form-control input"><?php echo $loaninfo->PN_remarks;?></textarea>
			</div>
			<div class="modal-footer">
				<input type="hidden" name="loanid" value="<?php echo $loanid;?>">
				<button  class="btn btn-sm bt-default" data-dismiss="modal" data-toggle="close">Close</button>
				<input type="button"  class="btn btn-sm btn-primary" id="remarksbtn" name="submit" value="Change PN Remarks">
			</div>
			</form>
		</div>
	</div>
</div>

<!--PN REMARKS ENDS HERE-->

<!-- COMAKER -->
<div class="modal fade" id="upcomaker" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

	  <div class="modal-dialog modal-lg">
		<div class="modal-content">
		
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel">Update Comaker</h4>
		  </div>
		  <div class="modal-body">	
		  <p>
				<button class="btn btn-sm btn-primary" type="button" id="chooseclient">Select from Client list</button> &nbsp; &nbsp; <button id="addclient" type="button" class="btn btn-sm btn-primary">Add New Client Profile</button> 
			</p>
				<div id="clientlist">		
				<form method="post" action="<?php echo base_url();?>loans/comaker/add"  id="newcomakerexist">
				<?php 
				$newtmpl = array ('table_open'  => '<table class="table  table-striped table-bordered table-hover" id="clients">');
				$this->table->set_template($newtmpl);
				$this->table->set_heading("Select","Branch", "Last Name", "First Name", "Birthday");
				echo $this->table->generate();			
				//$this->load->view('client/addclientmodal'); ?>
				<input type="hidden" name="loanid" id="loanid" value="<?php echo $loanid;?>">
				<input type="submit" class="btn btn-sm btn-danger " value="Add Comaker" >
				</form>
			</div>
			<div id="addcomaker">
				<form  method="post" action="<?php echo base_url();?>loans/comaker/add" id="newcomaker">
				<?php $this->load->view('client/addclientmodal'); ?>
				<input type="hidden" name="loanid" value="<?php echo $loanid;?>">
				<input type="submit" class="btn btn-sm btn-danger" id="addnew" name="submit" value="Add Comaker Profile" >
				</form>
			</div>
		  </div>
		  <div class="modal-footer">			
			<button  class="btn btn-sm bt-default" data-dismiss="modal" data-toggle="close">Close</button>
		  </div>
		  
	  </div>
	</div>
	<input type="hidden" id="base_url" value="<?php echo base_url();?>">
</div>

<!-- ASSIGN PN -->
<div class="modal fade" id="assignpn" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-sm">
		<div class="modal-content">
		<form action="<?php echo base_url();?>loans/action/assignpn" id='assignpnform' method="post">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel">Assign PN</h4>
		  </div>
		  <div class="modal-body">
			<input type="text" name="bookpn" class="input form-control" placeholder="Enter PN here . . . " value="<?php echo $loaninfo->PN;?>">
		  </div>
		  <div class="modal-footer">
			<input type="hidden" name="loanid" value="<?php echo $loanid;?>">
			<input type="hidden" name="branchid" value="<?php echo $loaninfo->branchID;?>">
			<input type="hidden" name="transdate" value="<?php echo  $this->auth->localdate();?>">
			<input type="hidden" name="method" value="<?php echo $loaninfo->paymentmethod;?>">
			<input type="hidden" name="startpayment" value="<?php echo $loaninfo->dateStartPayment;?>">
			<input type="hidden" name="exterm" value="<?php echo $loaninfo->extension;?>">
			<input type="hidden" name="loancode" value="<?php echo $loaninfo->LoanSubCode;?>">
			<input type="hidden" name="term" value="<?php echo $loaninfo->Term;?>">
			<input type="hidden" name="approved" value="<?php echo $loaninfo->approvedAmount;?>">
			<input type="hidden" name="clientid" value="<?php echo $clientid;?>">
			<input type="submit" class="btn btn-sm btn-danger " name="button" value="Assign PN" id='assignpnsub'>
		  </div>
		  </form>
	  </div>
	</div>
	</div>
	
	<!-- ASSIGN PN -->
<div class="modal fade" id="updateMaturity" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog ">
		<div class="modal-content">
		<form action="<?php echo base_url();?>loans/action/update" id='updateMaturityDate' method="post" id="transdate" class="formpost">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel">Change Loan Transaction Info</h4>
		  </div>
		  <div class="modal-body">
			<div class="panel-body">
				<div class="col-lg-6 col-md-6 col-xs-12">
					<label>Loan Status</label>
					<?php if($this->auth->perms('debug',$this->auth->user_id(),3) == true) {  ?>
					<select name="status" class="input form-control">
						<option value="processing" <?php if(strtolower($loanstatus) == 'processing') echo "selected";?>>Processing</option>
						<option value="approval" <?php if(strtolower($loanstatus) == 'approval') echo "selected";?>>Approval</option>
						<option value="approved" <?php if(strtolower($loanstatus) == 'approved') echo "selected";?>>Approved</option>
						<option value="granted" <?php if(strtolower($loanstatus) == 'granted') echo "selected";?>>Granted</option>						
						<option value="CURRENT" <?php if(strtolower($loanstatus) == 'current') echo "selected";?>>Current</option>
						<option value="CLOSED" <?php if(strtolower($loanstatus) == 'closed') echo "selected";?>>Closed</option>
					</select>
					<?php }else{ ?>
					<input type="hidden" name="status" value="<?php echo $loanstatus;?>">
					<?php }?>
				</div>
				<div class="col-lg-6 col-md-6 col-xs-12">
					<label>Payment Method</label>
					<select name="paymentmethod" class="input form-control">
						<option value="M" <?php if($loaninfo->paymentmethod == 'M') echo "selected";?>>Monthly</option>
						<option value="L" <?php if($loaninfo->paymentmethod == 'L') echo "selected";?>>Lumpsum</option>
						<option value="SM" <?php if($loaninfo->paymentmethod == 'SM') echo "selected";?>>Semi-Monthly</option>
					</select>
				</div>
				<?php if(strtolower($loanstatus) == 'current' ) { ?>
				<div class="col-lg-6 col-md-6 col-xs-12">
					<label>Total Terms</label>
					<input type="number" name="term" class="input form-control"  value="<?php echo $loaninfo->Term?>">
				</div><div class="col-lg-6 col-md-6 col-xs-12">
					<label>Extension (if PL Extension)</label>
					<input type="number" name="extension" class="input form-control"  value="<?php echo $loaninfo->extension?>">
				</div>
				<?php }else{ ?>
				<input type="hidden" name="term" value="<?php echo $loaninfo->Term;?>">
				<input type="hidden" name="extension" value="<?php echo $loaninfo->extension;?>">
				<?php }?>
				<div class="col-lg-12 col-md-12 col-xs-12">
					<label>Release Date</label>
					<input type="date" name="DisburseDate" class="input form-control"  value="<?php echo date('Y-m-d', strtotime($loaninfo->DateDisbursed));?>">
				</div>
				<div class="col-lg-12 col-md-12 col-xs-12">
					<label>Start Of Payment Date</label>
					<input type="date" name="startpaymentdate" class="input form-control"  value="<?php echo $loaninfo->dateStartPayment;?>">
				</div>
				<div class="col-lg-12 col-md-12 col-xs-12">
					<label>Maturity Date</label>
					<input type="date" name="maturityDate" class="input form-control"  value="<?php echo $loaninfo->MaturityDate;?>">
				</div>
			</div>
		  </div>
		  <div class="modal-footer">
			<input type="hidden" name="loanid" value="<?php echo $loanid;?>">
			<input type="hidden" name="update" value="maturity">
			<input type="submit" class="btn btn-sm btn-danger " name="button" value="Update" id='updateDate'>
		  </div>
		  </form>
	  </div>
	</div>
	</div>
<!-- UPDATE FEES -->
<div class="modal fade" id="upfeesform" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4>UPDATE FEES</h4>
			</div>
			<form action="<?php echo base_url();?>loans/application/updatefees" method="post" id="updatefee">
			<div class="modal-body">
				<div class="row form-group">
					<div class="col-md-4">
						Principal/Gross
					</div>
					<div class="col-md-8">
						<input type="text" name="principal" id="principal" style="font-weight:bold" value="<?php echo ($loaninfo->approvedAmount ? $loaninfo->approvedAmount : $principal);?>"  class="input-sm form-control number" readonly>
					</div>
				</div>
				<?php 
					$totalfees = 0;
					$count = 1;				
					foreach($fees->result() as $fee) { 
						$f=  floatval(str_replace(",","",$fee->value)); 
						$totalfees += $f;
						?>
						<div class="row form-group">
							<div class="col-md-4">
								<?php echo $fee->feeName;?>
							</div>
							<div class="col-md-8">
								<?php 
								if($fee->comptype == 'fixed')
									$enable = "";
								else $enable = "";
								?>
								<input type="text" name="feeid[<?php echo $fee->loanfeeID;?>]"  value="<?php echo $fee->value;?>" <?php echo $enable;?> class="input-sm form-control number feeeeee">
							</div>
						</div>
				<?php
					$count ++;
				}?>
				<div class="row form-group">
					<div class="col-md-4">
						Total Fees
					</div>
					<div class="col-md-8">
						<input type="text" name="totalfees" id="finaltotal" value="<?php echo $totalfees;?>"   class="input-sm form-control number" readonly>
					</div>
				</div>
				<hr/>
				<div class="row form-group">
					<div class="col-md-4">
						Net Proceeds
					</div>
					<div class="col-md-8">
						<input type="text" name="netproceeds" id="netpro" value="<?php echo $netproceeds;?>"  class="input-sm form-control number" readonly>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<input type="hidden" name="loanid" value="<?php echo $loanid;?>">
				<input type="hidden" name="loanstatus" value="<?php echo $loaninfo->status;?>">
				<button  class="btn btn-sm btn-default" data-dismiss="modal" data-toggle="close">Close</button>
				<button class="btn btn-sm btn-primary" type="button" id="savefees">Save Fees</button>
			</div>
			</form>
		</div>
	</div>

</div>
<div class="modal fade" id="disburse" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog ">
		<?php $this->load->view('forms/cv', $loans);?>
	  </div>
	</div>
<script>
    $(document).ready(function() {  	
		var base_url = $('#base_url').val();
		var totalSum = 0;
		
		var term = $('#term').val();
		
		var basedTerm = parseInt($('#basedTerm').val());
		
		$('#nextendedTerm').on('change',function(e){
			var ext = parseInt( $('#nextendedTerm').val());
			var newterm =  basedTerm + ext;
			$('#term').val(newterm);
			//alert(newterm);
		});

	
		
		$('#remarksbtn').on('click',function(e){
			e.preventDefault();
			var btn = $(this);
			btn.button('loading');
			$.ajax({
				type: "POST",
				url: $('#remarksform').attr('action'), //process to mail
				data: $('#remarksform').serialize(),
				success: function(msg){
					$(".modal").hide();
					if(msg['stat']==1){
						bootbox.alert(msg['data'], function(){
							location.reload();
						});
					}
					else{
						bootbox.alert(msg['data']);
					}
					btn.button('reset');
				},
				error: function(){
					bootbox.alert("Please try again.");
					btn.button('reset');
				}
			});
		});
		
		$('#generate').click(function(e){
			e.preventDefault();
			var btn = $(this);
			btn.button('loading');
			$.ajax({
				type: "POST",
				url: $('#genform').attr('action'), //process to mail
				data: $('#genform').serialize(),
				success: function(msg){
					$(".modal").hide();
					bootbox.alert("Loan schedule was generated.",
					function(){
						location.reload();
					});
					btn.button('reset');
				},
				error: function(){
					bootbox.alert("Please try again.");
					btn.button('reset');
				}
			});
		});
		
		$('#dispost').on('click',function(e) {
				e.preventDefault();
				var btn = $(this);
				btn.button('loading');
				var currentForm = this;
				var clicked = $("#dispost").attr('value');
				var form_url = $("#disburseform").attr("action");
				var info = new Array( $('#disburseform').serialize(), "submit=Add Disbursement" );
				//bootbox.alert('<center><img src="'+base_url+'assets/img/loader.gif"></center>');
				 $.ajax({
					type: "POST",
					url: form_url, //process to mail
					data: $('#disburseform').serialize(),
					success: function(msg){
						$(".modal").modal('hide');
						//if(msg == true){
							bootbox.dialog({
							  message: msg,
							  title: "Verify Transaction",
							  buttons: {
								success: {
								  label: "Add Transaction",
								  className: "btn-danger",
								  callback: function() {
									 $.ajax({
										type: "POST",
										url: base_url+"cash/addtransaction", //process to mail
										data: $('#disburseform').serialize() ,
										success: function(msgs){
											if(msgs == "Transaction was posted."){
												bootbox.alert(msgs,
												function(){
													//$("#coltable").append("<tr><td>Refresh Page to see the changes</td></tr>");
													location.reload(true);													
												});
											}else{
												bootbox.alert(msgs,
												function(){
													$("#disburse").modal();
												});
											}
																			
										},
										error: function(){
											bootbox.alert("error");
										}
									});
									
								  }
								},
								danger: {
								  label: "Back",
								  className: "btn-default",
								  callback: function(){
									$('#disburse').modal();
								  }
								}
							  }
							});	
						//}else{
							//$('<div class="modal fade" id="adjustconfirm" tabindex="-1" role="dialog" //aria-labelledby="myModalLabel" aria-hidden="true">' + msg + '</div>').modal();
						//}
						btn.button('reset');
					},
					error: function(){
						bootbox.alert('Internet Connection Problem. Please try again.');
						btn.button('reset');
					}
				});
								
		});
		
		$('#assignpnsub').on('click', function(e){
			e.preventDefault();
			var btn = $(this);
			btn.button('loading');
			$.ajax({
				type: "POST",
				url: $('#assignpnform').attr('action'), //process to mail
				data: $('#assignpnform').serialize(),
				success: function(msg){
					$(".modal").modal('hide');
					
					if(msg['stat'] == 1){
						bootbox.alert("New PN was assigned.",
							function(){
							location.reload();
						});
					}else{
							
							bootbox.dialog({
							  message: msg['content'],
							  title: "Assign PN",
							  buttons: {								
								danger: {
								  label: "Back",
								  className: "btn-default",
								  callback: function(){
									$('#assignpn').modal();
								  }
								}
							  }
							});	
						
					}
					
					btn.button('reset');
				},
				error: function(){
					bootbox.alert("Please try again.");
					btn.button('reset');
				}
			});
		});
		
		$('#ciformsub').on('click', function(e){
			e.preventDefault();
			var btn = $(this);
			btn.button('loading');
			$.ajax({
				type: "POST",
				url: $('#ciformpost').attr('action'), //process to mail
				data: $('#ciformpost').serialize(),
				success: function(msg){
					$(".modal").hide();
					bootbox.alert("CI Report was posted.",
					function(){
						location.reload();
					});
					btn.button('reset');
				},
				error: function(){
					bootbox.alert("Please try again.");
					btn.button('reset');
				}
			});
		});
		
		$('.feeeeee').on('keyup',function(e){
			e.preventDefault(); 
			var totalSum = 0;
			$('.feeeeee').each(function (index,element) {
				totalSum += parseFloat($(this).val());
			});
			$('#finaltotal').val(totalSum);
			
			var net = $('#principal').val() - totalSum;
			$('#netpro').val(net);
		});
		
		$("#savefees").on("click", function(){
			var btn = $(this);
			var url = $('#updatefee').attr('action');
			btn.button('loading');
			
			$.ajax({
				type: "POST",
				url: url, //process to mail
				data: $('#updatefee').serialize(),
				success: function(msg){
					$(".modal").hide();
					bootbox.alert("Loan fees was updated",
					function(){
						location.reload();
					});
				},
				error: function(){
					bootbox.alert(msg + "Please try again.");
					btn.button('reset');
				}
			});
			
			
		});
		
		$("body").on("click", ".remcom", function (e) {
			var li =  $(this).parent("li")
			var coid = $('#mycom').val();
			bootbox.dialog({
			  message: "Are you sure you want to remove this comaker?"+ coid,
			  title: "Remove Comaker",
			  buttons: {
				success: {
				  label: "Yes",
				  className: "btn-danger",
				  callback: function() {
					 //ajax function here
					 
					 li.remove();
				  }
				},
				danger: {
				  label: "No",
				  className: "btn-default",
				}
			  }
			});
			
		}); 
		
		$('#addnew').on('click', function (e){
			e.preventDefault();
			var btn = $(this);
			btn.button('loading');
			$.ajax({
				type: "POST",
				url: $('#newcomaker').attr('action'), //process to mail
				data: $('#newcomaker').serialize(),
				datatype: 'json',
				success: function(msg){	
					$("#upcomaker").modal('hide');
					
					if(msg['status'] == false){
						bootbox.dialog({
						  message: msg['errors'],
						  title: "Add Comaker",
						  buttons: {
							success: {
							  label: "Back",
							  className: "btn-danger",
							  callback: function() {
								$("#upcomaker").modal('show');
							  }
							}							
						  }
						});
						
					}else{
						bootbox.alert("New Comaker was added",
							function(){
								location.reload();
							});
					}
					btn.button('reset');
				},
				error: function(msg){
					if(msg['status'] == true){
						bootbox.alert("New Comaker was added",
							function(){
								location.reload();
							});
					}else{
						bootbox.alert(msg['errors'] + "Please try again.");
					}
					btn.button('reset');
				}
			});
		});
		
		$('#newcomakerexist').on('submit', function (e){
			e.preventDefault();
			var btn = $(this);
			//btn.button('loading');
			$.ajax({
				type: "POST",
				url: $(this).attr('action'), //process to mail
				data: $(this).serialize(),
				datatype: 'json',
				success: function(msg){	
					$("#upcomaker").modal('hide');					
					if(msg['status'] == 0){
						bootbox.dialog({
						  message: msg['errors'],
						  title: "Add Comaker",
						  buttons: {
							success: {
							  label: "Back",
							  className: "btn-danger",
							  callback: function() {
								$("#upcomaker").modal('show');
							  }
							}							
						  }
						});
					}else{
						//$('#comakerlist').append('<li><a href="'+base_url+'client/profile/'+msg['comakerid']+'" target="_blank">'+msg['comakername']+'</a> &nbsp; </li>');
						bootbox.alert("New Comaker was added",
							function(){
								location.reload();
							});
					}
				},
				error: function(){
					bootbox.alert(msg + "Please try again.");
					btn.button('reset');
				}
			});
		});
	
		$('#addcomaker').hide();
		
		$('#addclient').on('click', function(){
			$('#addcomaker').show('slow');
			$('#clientlist').hide('slow');
		});
		
		$('#chooseclient').on('click', function(){
			$('#addcomaker').hide('slow');
			$('#clientlist').show('slow');
		});
		
		$('#clients').dataTable({				
	        "ajax": "<?php echo base_url();?>client/overview/getclient",
			"oLanguage": {
				"sProcessing": "<p align='center'><img src='<?php echo base_url();?>assets/img/ajax-loader.gif'></p>"
			},
	        "iDisplayStart": 1,
	        "iDisplayLength": 5,
	        "aLengthMenu": [[5,10, 25, 50, -1], [5, 10, 25, 50, "All"]],
	        "aaSorting": [[0, 'asc']],
			"aoColumnDefs": [ 
				{
				  "aTargets": [0],
				  "mData": "download_link",
				  "mRender": function ( data, type, full ) {
						return '<input type="radio" name="comakerid" id="comakerid" value="'+full[0]+'" ><input type="hidden" name="comakername" id="comakername" value="'+full[1]+', '+full[2]+'">';
					}
				},
				{
				  "aTargets": [1],
				  "mData": "download_link",
				  "mRender": function ( data, type, full ) {
						return '<a>'+full[1]+"</a>";
					}
				}
			],
	        "aoColumns": [				
				{ "bVisible": true, "bSearchable": false, "bSortable": true },
				{ "bVisible": true, "bSearchable": true, "bSortable": true },
				{ "bVisible": true, "bSearchable": true, "bSortable": true },
				{ "bVisible": true, "bSearchable": true, "bSortable": true },
				{ "bVisible": true, "bSearchable": true, "bSortable": true }				
	        ],			
		});
		
		$('.comakerbutton').on('click', function(){
			alert('babay');
		});		
		
		$('#addfield').on('click', function(){
			$('#cv').append('<tr><td><input type="text" name="accountname[]" class="input-sm form-control"></td><td><input type="text" name="dr[]" class="input-sm form-control"></td><td><input type="text" name="cr[]" class="input-sm form-control"></td></tr>');
		});
		
	compute_all();
	
	function compute_all(){
		
		var dr = $('.dr_cv'),
		sumdr = 0;		
		//alert(sumdr);
		 $(dr).each(function (index, element) {
			sumdr = sumdr + parseFloat($(element).val());
		});	
		
		//$('#totaldr_cv').val(sumdr);
		sumdr = Math.round(sumdr * 100)/100;
		$('#totaldr').val(sumdr );
		
		var cr = $(".cr_cv"),
		sum = 0;
		//var amt = $("#amount").val();
		//sum += parseFloat(amt);	 
		$(cr).each(function (index, element) {
			sum = sum + parseFloat($(element).val());
		});	
		//alert(sum);
		//$('#totalcr_cv').val(sum);
		sum = Math.round(sum * 100)/100;
		$('#totalcr').val(sum);
		
		if(sum == sumdr)
			$("#SubmitCV").prop("disabled", false);
		else $("#SubmitCV").prop("disabled", true);
		
	}
	
	$("#SubmitCV").on("click", function(e){
		e.preventDefault();
		$(this).button("loading");
				
		var form = $("#cvform").attr("action");
		
		bootbox.confirm({
			message: "This is a confirm your post! Do you want to continue?",
			buttons: {
				confirm: {
					label: 'Yes',
					className: 'btn-success'
				},
				cancel: {
					label: 'No',
					className: 'btn-danger'
				}
			},
			callback: function (result) {
				if(result == true){
					
					$.ajax({
						type: "POST",
						url: form, //process to mail
						data: $('#cvform').serialize(),
						success: function(msg){
							if(msg['stat'] == true){
								var ref = msg['ref'];
								bootbox.alert("Check Voucher was posted", function (){
									$('#disburse').modal('hide');
									$(this).modal('hide');
									$('#cvform').trigger('reset');
									$(location).attr('href',"<?php echo base_url();?>cash/disbursements/printcv/"+ref);
									//showDisburement();
								});
							}else{
								bootbox.alert(msg['msg']);
							}
							
							$("#SubmitCV").button("reset");
						},
						error: function(){
							bootbox.alert("Please try again."+ msg['stat']);
							$("#SubmitCV").button("reset");
						}
					});
		
				}else{
					$("#SubmitCV").button("reset");
				}
				
			}
		});			
		
	});

    });
 </script>