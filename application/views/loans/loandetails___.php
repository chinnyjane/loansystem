
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
	case "review";
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

$loans['color'] = $color;
$loans['updateloan'] = $updateloan;

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

<div class="alert alert-<?php echo $color;?>">
<?php echo "STATUS : <b>".strtoupper($loanstatus)."</b>";?>
</div>
<div class="row form-group">	<div class=" ">	
		<div class="col-lg-7 col-md-8 col-md-12 col-xs-12">
		<h2><?php echo $client->LastName.", ".$client->firstName." ".$client->MiddleName; ?> <small><?php echo $this->UserMgmt->get_branch_by_id($loaninfo->branchID); ?> Branch</small></h2>
		<div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
			<img src="<?php echo $image;?>" width="100%"/>
		</div>
		<div class="col-lg-7 col-md-6 col-sm-6 col-xs-12">
			<h4 style="margin-top: 5px;">CLIENT  INFORMATION</h4>
			<hr style="margin-top: 0px;"/>
			<label>Age : </label> <?php echo $this->loansetup->get_age($client->dateOfBirth); ?> yrs. old <br/>
			<label>Date of Birth : </label> <?php echo date("F d, Y", strtotime($client->dateOfBirth)); ?> <br/>
			<label>Civil Status : </label> <?php echo $client->civilStatus; ?> <br/>
			<label>Gender : </label> <?php echo $client->gender; ?> <br/>
			<label>Address: </label> <?php echo $client->address.", ".$client->barangay.",  ".$client->cityname.", ".$client->provname; ?> <br/>
			<a href="<?php echo base_url();?>client/profile/<?php echo $clientid;?>" class="pull-right"> <i class="fa fa-user"></i> View Profile</a>
		</div>
		</div>
		<div class="col-lg-5 col-md-4 col-sm-12 col-xs-12 " >
			<h2 align="right"><font style="color: red" ><?php echo strtoupper($loaninfo->productCode)." - ".strtoupper($loaninfo->LoanCode);?></font> </h2>
			<div class="col-lg-12">
			<h4 style="margin-top: 5px;">LOAN INFORMATION</h4>
			<hr style="margin-top: 0px;"/>
			<?php 
							if(empty($loaninfo->PN) and $loanstatus == 'approved'){ 
									$bookpn = '';
									echo '<button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#assignpn" href="#">Assign Promissory Note (PN)</button>';
									echo '&nbsp;';
							}elseif(!empty($loaninfo->PN)) {
								echo '<label>PN : '.$loaninfo->PN.' &nbsp;</label>';
								echo '<button class="btn btn-danger btn-sm pull-right" data-toggle="modal" data-target="#assignpn" href="#">Change PN Number</button>';
							} ?><br/>
			<label> <?php $loancode = $loaninfo->LoanSubCode;
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
											
						?> -  <?php if($loaninfo->paymentmethod == "M") echo "Monthly"; else echo "Lumpsum";?></label><br/>
			<label>Amount Applied: </label> <div class="pull-right">Php <?php echo number_format($loaninfo->AmountApplied,2). " - ". $loaninfo->computation; ?></div><br/>
			<label>Amount Approved: </label> <div class="pull-right"><label> <?php 
						if(strtolower($loanstatus) == "processing" or strtolower($loanstatus) == "approval") echo "NOT YET APPROVED.";
						else	echo "Php ". number_format( $loaninfo->approvedAmount ,2);
						?></label></div><br/>
			<label>Net Proceeds: </label>  <div class="pull-right"> <a type="button"  data-toggle="modal" data-target="#upfeesform" data-backdrop="static"  href="#"><small> Fee Details</small></a> Php <?php echo number_format($netproceeds,2) ;?></div><br/>
			<label>Terms: </label> <div class="pull-right"><?php echo $loaninfo->Term; ?> month(s) <?php echo ($loaninfo->extension ? " - ".$loaninfo->extension." mos ext." : '');?></div><br/>
			<label>Interest : </label> <div class="pull-right"><?php echo $loaninfo->interest;?> %</div><br/>		
						
		</div>
		</div>
		</div>
		

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
 <!--  ================ END HERE ===========================-->
 <br/>
 <div class="panel panel-success with-nav-tabs">
	<div class="panel-heading " style="margin-bottom: 0px; padding-bottom:0px;">
		 <ul class="nav nav-tabs" id="myTab"  style="margin-bottom: 0px;">
			<?php if(strtolower($loanstatus) != "processing" and strtolower($loanstatus) != "approval") { ?>
			<li><a href="#loaninfo">Generated Loan Forms</a></li>	
			<li><a href="#schedule">Ledger</a></li>	
			<?php } ?>
			<li ><a href="#pensioninfo" data-toggle="tab">Collateral</a></li>	
			<?php if($loaninfo->LoanSubCode != 'E') { ?><li><a href="#requirements" data-toggle="tab">Requirements</a></li><?php } ?>	
			<li><a href="#comakerform" data-toggle="tab">Co-maker</a></li>
			<?php if($loaninfo->LoanSubCode != 'E') { ?><li><a href="#ciform" data-toggle="tab">CI Report</a></li><?php } ?>
			<?php if(strpos($loaninfo->productCode,"PL") !== false){ ?>
			<li><a href="#planalysis" data-toggle="tab">PL Analysis</a></li>
			<?php } ?>
		</ul>
	</div>
<div class="tab-content">
	<div class="tab-pane  " id="loaninfo">
		<div class="panel-body">
		<?php if ($loanstatus !="processing" && $loanstatus !="approval" && $loanstatus !="bm_approval") { ?>
		<a class="btn btn-success btn-sm col-md-3" href="<?php echo base_url().'forms/loanapplication/'.$loanid;?>" style="margin: 5px" target="_blank"> <i class="fa fa-print"></i> Loan Application</a> 
		<a class="btn btn-success btn-sm col-md-3" href="" style="margin: 5px" target="_blank"> <i class="fa fa-print"></i> Promissory Note</a> 
		<a class="btn btn-success btn-sm col-md-3" href="<?php echo base_url().'forms/disclosure/'.$loanid;?>" style="margin: 5px" target="_blank"> <i class="fa fa-print"></i> Disclosure Statement</a> 
		<a class="btn btn-success btn-sm col-md-3" href="" style="margin: 5px" target="_blank"> <i class="fa fa-print"></i> Computation Sheet</a> 
		<a class="btn btn-success btn-sm col-md-3" href="" style="margin: 5px" target="_blank"> <i class="fa fa-print"></i> RFPL Agreement</a> 
		<?php } else { echo "Forms will be generated after approval."; } ?>
		</div>
			<?php	$this->load->view('loans/loancomputation', $loans); ?>
 <!-- END OF LOAN INFORMATION -->
 
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
				
				if($this->auth->perms('Generate Schedule',$this->auth->user_id(),3) == true and $loanstatus != 'processing') {  
				echo "<code>If the PN Schedule is incorrect, please re-generate the schedule by pressing the Generate Schedule button.</code>";
				?>
				<form action="<?php echo base_url();?>loans/overview/generate_loanschedule" id="genform" method="post">
					<?php
				
					if($loancode == 'E' or $loaninfo->extension > 0){
						$lterm =  $loaninfo->extension;					
						$year = date("Y",strtotime(" -".$lterm." month", strtotime($loaninfo->MaturityDate)));							
						$m = date("m",strtotime(" -".$lterm." month", strtotime($loaninfo->MaturityDate)));					
						$myDate = explode('-', $loaninfo->MaturityDate );
						$d = date( "d", mktime(0,0,0 ,$myDate[1] - 1 ,$myDate[2],$myDate[0]) );								
						$date = $year."-".$m."-".$d;						
					} else {
						$lterm = $loaninfo->Term;
						$date =  date("Y-m-d", strtotime($loaninfo->DateDisbursed));
					} 
							if( $loaninfo->approvedAmount != '' )
									$amount =  $loaninfo->approvedAmount;
							else
								$amount = $loaninfo->AmountApplied;						
						?>
					<input type="hidden" name="term" value="<?php echo $lterm; ?>">
					<input type="hidden" name="dateDisbursed" value="<?php echo date("Y-m-d",strtotime($date));?>">	
					<input type="hidden" name="approveamount" value="<?php echo $amount;?>">			
						
					<input type="hidden" name="loanid" value="<?php echo $loanid;?>">
					<input type="hidden" name="method" value="<?php echo $loaninfo->paymentmethod;?>">					
					<input type="submit" name="submit" value="Generate Loan Schedule" id="generate" class="btn btn-sm btn-primary">				
				</form>
				<?php }	?>
		<div class="panel-footer">
			<a href="<?php echo base_url();?>forms/ledger/<?php echo $loanid;?>" class="btn btn-sm btn-primary" target="_blank">Print Ledger Card</a>
		</div>
		</div>
	</div>
	<div class="tab-pane  " id="pensioninfo">	
	
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
<div class="tab-pane" id="requirements">

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
<div class="tab-pane" id="comakerform">

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
 <div class="tab-pane" id="ciform">
	<form action="<?php echo base_url();?>loans/application/cireport" method="post" id="ciformpost">
    
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
								
			}
		
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
<div class="tab-pane" id="planalysis">
	<div class="panel-body" style="overflow-x: scroll; margin:0px;" >
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


		<?php $this->load->view('loans/loanbuttons'); ?>
		</div>

<!--<div class="col-sm-8">
		<div class="alert alert-<?php echo $color;?>">
			<b>LOAN STATUS : </b><?php echo strtoupper($loanstatus);?>
		</div>
		</div>
		<div class="col-sm-4">
		<div class="alert alert-danger">
			<b><?php echo "Total Loan Balance : Php ".number_format($totalBal,2).'';?></b>
		</div>
		</div>-->

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
				$tmpl = array ('table_open'  => '<table class="table  table-striped table-bordered table-hover" id="clients">');
				$this->table->set_template($tmpl);
				$this->table->set_heading("Select","Branch", "Last Name", "First Name");
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
				{ "bVisible": true, "bSearchable": true, "bSortable": true }				
	        ],			
		});
		
		$('.comakerbutton').on('click', function(){
			alert('babay');
		});		
		
		$('#addfield').on('click', function(){
			$('#cv').append('<tr><td><input type="text" name="accountname[]" class="input-sm form-control"></td><td><input type="text" name="dr[]" class="input-sm form-control"></td><td><input type="text" name="cr[]" class="input-sm form-control"></td></tr>');
		})

    });
 </script>