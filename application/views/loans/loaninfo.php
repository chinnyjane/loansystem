	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingOne">
      <h4 class="panel-title">       
         Loan Information <a class="pull-right" href="<?php echo base_url();?>forms/application/<?php echo $loanid;?>" target="_blank"><i class="fa fa-print"></i> PRINT FORM</a>
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
							<?php 
							if(empty($loaninfo->PN) and $loanstatus == 'approved'){ 
									$bookpn = '';
									echo '<button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#assignpn" href="#">Assign Promissory Note (PN)</button>';
									echo '&nbsp;';
							}elseif(!empty($loaninfo->PN)) {
								echo '<label>PN : '.$loaninfo->PN.' &nbsp;</label>';
								echo '<button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#assignpn" href="#">Change PN Number</button>';
							} ?>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td align="center">
						</td>
						<td colspan="2" style="vertical-align: top">
							<br/>
						</td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td><label>Payment Term: </label></td>
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
						<td><label>Date Approved : </label> <?php echo $loaninfo->dateApproved;?> </td>
						<td><label>Date of Maturity: </label> <?php echo $loaninfo->MaturityDate;?></td>
					</tr>
					<tr>
						<td><label>Loan Processor: </label> <?php echo $ag; ?> </td>
						<td><label>Approved By: </label> <?php echo $ap;?> </td>
						<td><label>Released Date: </label> <?php echo $loaninfo->DateDisbursed;?></td>
					</tr>
					<tr>
						<td colspan="3">
						<label>Remarks : </label>
						<font color="red"><?php echo $loaninfo->remarks;?></font>
						<br/>
						<p>
						Promissory Note Remarks: <?php echo $loaninfo->PN_remarks;?><br/><br/>
						<?php if($this->auth->perms('Loan Details', $this->auth->user_id(), 3) == true and ($loanstatus == 'granted' or $loanstatus == 'CURRENT' or $loanstatus == 'current' or strtolower($loanstatus) == 'canceled' or strtolower($loanstatus) == 'closed')){ ?>
						<button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#pnremarks" href="#">Change PN Remarks</button>
						
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
			if($this->auth->perms('Approve Loan',$this->auth->user_id(),3) == true and $this->auth->loanapproval($loaninfo->productID, $this->auth->user_id(), $loaninfo->branchID, $totalBal) == true)
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
						<input type="text" value="<?php echo  $loaninfo->Term;?>" name="term" name="term" class="form-control input-sm" readonly>
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
                        <button class="btn btn-sm btn-danger"  id="declineloan" ><li class="fa fa-times"></li> Decline Loan</button>  &nbsp; 
                        <button class="btn btn-sm btn-warning" id="cancelloan" type="button"><li class="fa fa-unlink"></li> Cancel Loan</button>
            &nbsp;&nbsp;
                    </div>
				</div>
			</form>
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
			if(!empty($loaninfo->PN) ){
			echo '<button class="btn btn-success btn-sm" data-toggle="modal" data-backdrop="static" data-target="#disburse" href="#">Create CV</button>';	
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
					.'<a class="btn btn-default btn-sm" href="'.base_url().'forms/rfplagreement/'.$loanid.'" target="_blank">RFPL Agreement</a>';
					
			}
		break;
		
		case 'release':
			
			if(!empty($loaninfo->PN)){ 			
				$cv = $this->Loansmodel->cvexist($loaninfo->PN);
				if($cv->num_rows() > 0 ){
					echo '<a class="btn btn-default btn-sm" href="'.base_url().'forms/checkvoucher/'. $loanid.'" target="_blank">Check Voucher</a>';
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
					.'<a class="btn btn-default btn-sm" href="'.base_url().'forms/rfplagreement/'.$loanid.'" target="_blank">RFPL Agreement</a>';
					
			}
		break;
		case 'current':
			if(!empty($bookpn)){ 			
				$cv = $this->Loansmodel->cvexist($bookpn);
				if($cv->num_rows() > 0 ){
					echo '<a class="btn btn-default btn-sm" href="'.base_url().'forms/checkvoucher/'. $loanid.'" target="_blank">Check Voucher</a>';
				}
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
				
				echo '&nbsp; ';
				echo '<a class="btn btn-default btn-sm" href="'.base_url().'forms/checkvoucher/'. $loanid.'" target="_blank">Check Voucher</a>';
				 
				
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
						.'<a class="btn btn-default btn-sm" href="'.base_url().'forms/rfplagreement/'.$loanid.'" target="_blank">RFPL Agreement</a>';
						
				}
			}
		break;
	}
	?>
</div>
</div>
</div>  
 </div>
 