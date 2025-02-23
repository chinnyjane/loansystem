<?php
	$clientid = $this->uri->segment(3);
	$loanid = $this->uri->segment(5);
	
	
	$tmpl = array ('table_open'          => '<table class="table  table-condensed table-hover " >',
			'thead_open' => '<thead class="header">'	); 
				$this->table->set_template($tmpl); 
	if($loans->num_rows() > 0){
		foreach($loans->result() as $loan){
			$loanname = $loan->LoanName;
			$loantype = $loan->LoanType;
			$pn = $loan->PNno;
			$bookpn = $loan->PN;
			$amount = $loan->AmountApplied;
			$approved = $loan->approvedAmount;
			$status = $loan->status;
			$pensionid = $loan->pensionID;
			$terms = $loan->Term ;
			$monthy = number_format($amount/$terms,2);
			$applied = $loan->dateApplied;
			//$agent = $this->UserMgmt->get_user_byid($loan->LoanProcessor);
			if($agent->num_rows() > 0 ){
			$a = $agent->row();
			$ag= $a->lastname.", ".$a->firstname;
			}else{
			$ag = '';
			}
		}
	}
	
	$amount = ($approved ? $approved : $amount);
	$req = $this->loansetup->requirements($loanid, $loantype); 
	
	if($status =='processing'){
		$complete = $req['complete'];
		if(in_array("0",$complete) == true){
			$reqcom = false;
			$approve = "disabled";	
		}else{
			//update status to approval
			$this->loansetup->updateLoanStatus('approval', $loanid);
			$reqcom = true;
			$approve = "";	
		}
	}elseif($status == 'approval'){
		$approve = "";	
	}
	
	//get loaninfo
	$product = $this->Loansmodel->getproductsbyID($loantype);
	if($product->num_rows() > 0){
		foreach ($product->result() as $pro){
			$pid = $pro->loanTypeID;
			$pcode = $pro->LoanCode;
			$pname = $pro->LoanName;
			$pdesc = $pro->LoanDescription;
			$minA = $pro->minAmount;
			$maxA = $pro->maxAmount;
			$minT = $pro->minTerm;
			$maxT = $pro->maxTerm;
			$penalty = $pro->penalty;
		}
	}
	//client info 
	$client = $this->Clientmgmt->getclientinfoByID($clientid);
	if($client->num_rows() > 0){

		foreach($client->result() as $c){
			$p['firstname'] = $c->firstName;
			$p['mname'] = $c->MiddleName;
			$p['lname'] = $c->LastName;
			$p['dob'] = $c->dateOfBirth;
			$p['city'] = $c->city;
			$p['address']=$c->address;
			$p['contact'] = $c->contact;
			$p['civilstatus'] = $c->civilStatus;
			$p['city'] = $c->cityname;
			$p['cityid'] = $c->city;
			$p['provid'] = $c->province;
			$p['barangay'] = $c->barangay;
			$p['address'] = $c->address;
			$p['gender'] = $c->gender;
			$p['age'] = $this->loansetup->get_age($p['dob']);
			if($c->dateOfBirth == '0000-00-00')
			$p['alert'] = "Please update client's birthday.";

			switch (strtolower($p['gender'])) {
			case 'f':
				$g = "Female";
				break;
			case "m": // never reached because "a" is already matched with 0
				$g = "Male";
				break;
			default:
				$g = "-";
			}
			
			$profileurl = base_url()."client/profile/".$clientid;
		}
	}
$datedisbursed = $loan->DateDisbursed ;
$dateapproved = $loan->dateApproved ;
if($datedisbursed == NULL)
$datedisbursed= "-";
else
$datedisbursed = date("F d, Y", strtotime($datedisbursed));
if($dateapproved == NULL)
$dateapproved= "-";
else
$dateapproved = date("F d, Y", strtotime($dateapproved));
$tmpl = array ('table_open'          => '<table class="table  table-condensed table-hover " >',
			'thead_open' => '<thead class="header">'	); 
				$this->table->set_template($tmpl); 
$comp = $this->loansetup->loancomputation($amount,$terms,$loantype, $loanid);

switch (strtolower($status)){
	case "processing";
		$color = "danger";
	break;
	case "approval";
		$color = "success";
	break;
	case "approved";
		$color = "green";
	break;
	case "release";
		$color = "yellow";
	break;
	case "cancel";
		$color = "default";
	break;
	case "canceled";
		$color = "default";
	break;
	case "ci";
		$color = "info";
	break;
	case "granted";
		$color = "primary";
	break;
	case "closed";
		$color = "danger";
	break;
	default:
		$color = "primary";
	break;
}

if(empty($bookpn)){ 
		$bookpn = '';
		$assignpn = '<button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#assignpn" href="#">Assign Promissory Note (PN)</button>';
}	else $assignpn = '';


?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/modaljs.js"></script>
<script src="<?php echo base_url();?>assets/js/loansprocess.js" type="text/javascript"></script>	

<label>Borrower's Name : </label>
<?php echo "<b><a href='".base_url()."client/profile/".$clientid."'>". strtoupper($p['lname'].", ". $p['firstname'])."</a></b>";?>
		
<h4>LOAN DETAILS</h4>
<hr/>

<table class="table-condensed" width="100%">
	<tr>
		<td><label>Loan Type : </label></td>
		<td><?php echo $pcode;?></td>
		<td><label>Payment method:</label></td>
		<td><?php switch( $loan->paymentmethod )
				{
					case 'M':
						echo 'Monthly';	
					break;
					
					case 'LS':
						echo 'Lumpsum';
					break;
					
					case 'SM':
						echo 'Semi-Monthly';
					break;
				}
				?></td>
	</tr>
</table>
<div class="panel panel-<?php echo $color;?>">
	<div class="panel-heading">Loan Details <span class="pull-right"><?php echo strtoupper($status);?> &nbsp; <a class="btn btn-default btn-xs" href="<?php echo base_url();?>forms/application/<?php echo $loanid;?>" target="_blank"><i class="fa fa-print"></i> Loan Application</a> </span></div>
	
	<div class="panel-body">
	
		
		
    	<div class="row form-group">
        	<div class="col-md-5 " >
            <input type="hidden" name="loanid" id="loanid" value="<?php echo $loanid;?>">
            	<label>Borrower's Name : </label>
                <?php echo "<b><a href='".base_url()."client/profile/".$clientid."'>". strtoupper($p['lname'].", ". $p['firstname'])."</a></b>";?>
            </div>
            <div class="col-md-3 pull-right" align="right">
            	<label>PN : </label>
                <?php
				if($status == "approved")
				echo $bookpn."&nbsp;".$assignpn;
				else echo "-";?>
            </div> 
        	
        </div>
		<div class="row form-group">
        	<div class="col-md-3">
            	<label>Loan Type : </label>
                <?php echo $pname;?>
            </div>
            <div class="col-md-3">
            	<label>Loan Terms : </label>
                <?php echo $terms." months";?>
            </div>
            <div class="col-md-3">
            	<label>Loan Extension : </label>
                <?php switch ( $loan->extension){
					case NULL:
						echo 'N/A';
					break;
					
					default:
						echo $loan->extension.' month(s)';
					break;
				} ?>
            </div>
            <div class="col-md-3">
            	<label>Payment Method : </label>
                <?php switch( $loan->paymentmethod )
				{
					case 'M':
						echo 'Monthly';	
					break;
					
					case 'LS':
						echo 'Lumpsum';
					break;
					
					case 'SM':
						echo 'Semi-Monthly';
					break;
				}
				?>
            </div>

                                  
        </div>
        
        <div class="row form-group">
        	<div class="col-md-12 " >
            	<label>Loan Amount : </label>
                <?php echo "<b>Php &nbsp;".number_format($amount,2)." ( <i>".strtoupper($this->loansetup->convert_number_to_words($amount)) ." PESOS ONLY ) </i></b>";?> <button data-toggle="modal" data-target="#computationmodal" data-backdrop="static" class="btn btn-primary btn-xs">Computation</button>
            </div>
        </div>
        <div class="row form-group">
        	 <div class="col-md-3">
            	<label>Applied Date: </label>
                <?php echo date("F d, Y", strtotime($applied));?>
            </div>
            <div class="col-md-3">
            	<label>Maturity Date : </label>
                <?php 
				if($loan->MaturityDate == NULL)
				echo '-';
				else
				echo  date("F d, Y", strtotime($loan->MaturityDate));?>
            </div>
            <div class="col-md-3">
            	<label>Disbursed Date : </label>
                <?php 
				if($loan->DateDisbursed == NULL)
				echo '-';
				else
				echo  date("F d, Y", strtotime($loan->DateDisbursed));?>
            </div>          
        </div>
		<ul class="nav nav-tabs" id="myTab">			
			<li><a href="#schedule" data-toggle="tab">Loan Ledger</a></li>
			<li ><a href="#pensioninfo" data-toggle="tab">Collateral</a></li>			
			<li><a href="#comaker" data-toggle="tab">Co-maker</a></li>
			<li><a href="#requirements" data-toggle="tab">Requirements</a></li>			
		</ul>	
        <div class="tab-content">	
			
				<div class="tab-pane well " id="schedule">		
				<?php 
				if($status == 'processing')
					echo $this->loansetup->loanschedule($terms, $amount, $loan->dateApplied);
				else
					echo $this->loansetup->approvedloansched($loanid, $amount);
				?>
				
			</div>
			<div class="tab-pane well" id="pensioninfo">
            	<div class="panel-body">				
				 <?php 	
				 echo $pensionid;
				 echo $this->Loansmodel->getcollaterals($pcode,$pid,$pensionid);?>
                 </div>
                 
			</div>
			
			<div class="tab-pane " id="comaker">
            	<div class="panel-body">	
				<?php echo $this->loansetup->comakerinfo($loanid); ?>
                </div>
			</div>
			<div class="tab-pane " id="requirements">	
				<?php
				
				if($status == "processing" or $status=='CI'){ ?>
				<form  method="post" id="requirementpost" action="">
					<?php echo $req['req'];?>
					<input type="hidden" name="loanid" value="<?php echo $loanid;?>">
					<input type="button" id="reqsubmit" name="submit" value="Submit Requirements" class="btn btn-success">
				</form>
				 <?php } else { 
					echo $req['req'];
				 }?>
				
			</div>			
		
	</div>
	</div>
	<div class="panel-footer">   
	<?php 
	switch ($status){
		case 'processing':
		?>
        <form action="" id="loanappform" method="post">
        	<button class="btn btn-sm btn-warning" id="cancelloan" type="button"><li class="fa fa-unlink"></li> Cancel Loan</button>
            &nbsp;&nbsp;
            <a href="<?php echo base_url();?>loans/application/process/<?php echo $loanid;?>" class="btn btn-sm btn-primary">Edit Loan Details</a>
        </form>		
		<?php
        break;
		case 'approval':
			if($this->auth->perms('Approve Loan',$this->auth->user_id(),3) == true)
			{
			?>
			<form action="" id="loanappform" method="post">		
				<label>Amount Approved : </label>
				<div class="row form-group">
                    <div class="col-md-4">					
                        <div class=" input-group">
                            <span class="input-group-addon">Php</span>
                            <input type="text" name="approvedamount" value="<?php echo $amount;?>" class="input-sm form-control">
                            <input type="hidden" name="loanid" value="<?php echo $loanid;?>" >
                        </div>
                    </div>
                    <div class="col-md-8">
                        <button class="btn btn-sm btn-success"  id="approveloan" <?php echo $approve;?>><li class="fa fa-check"></li> Approve Loan</button> &nbsp; 
                        <button class="btn btn-sm btn-danger"  id="declineloan" <?php echo $approve;?>><li class="fa fa-times"></li> Decline Loan</button>  &nbsp; 
                        <button class="btn btn-sm btn-warning" id="cancelloan" type="button"><li class="fa fa-unlink"></li> Cancel Loan</button> 
                    </div>
                </div>		
			</form>
            &nbsp;&nbsp;
            <a href="<?php echo base_url();?>loans/application/process/<?php echo $loanid;?>" class="btn btn-sm btn-primary">Edit Loan Details</a>
		<?php }
		break;
		case 'CI': ?> 
                       	
        		<?php 
				if($ci->num_rows() > 0){ 
					$cibutton = "Update CI Report";
					$disabled = '';
				}else{  $cibutton = "Create CI Report"; $disabled = 'disabled';}?>
                 <form action="<?php echo base_url();?>loans/application/submitforapproval" id="loanappform" method="post" class="formpost">
                 <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#createci" type="button"><li class="fa fa-eye"></li> <?php echo $cibutton;?></button>
                 
                 <button class="btn btn-sm btn-success" type="submit" <?php echo $disabled;?>><li class="fa fa-eye"></li> Submit for Approval </button>
                 <input type="hidden" name="loanid" value="<?php echo $loanid;?>">
                 &nbsp;&nbsp;
            <a href="<?php echo base_url();?>loans/application/process/<?php echo $loanid;?>" class="btn btn-sm btn-primary">Edit Loan Details</a>
           	 </form>
             
		<?php break;
		case 'approved':		
			echo '<button class="btn btn-success btn-sm" data-toggle="modal" data-target="#disburse" href="#">Create CV</button>';		
		break;
		
		case 'release':
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
	}
	?>
	
	</div>
		
	</div>
<div class="modal fade" id="disburse" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog ">
		<?php $this->load->view('forms/cv');?>
	  </div>
	</div>
	
	<div class="modal fade" id="assignpn" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-sm">
		<div class="modal-content">
		<form action="<?php echo base_url();?>loans/action/assignpn" class="formpost" method="post">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel">Assign PN</h4>
		  </div>
		  <div class="modal-body">
			<input type="text" name="bookpn" class="input form-control" placeholder="Enter PN here . . . ">
		  </div>
		  <div class="modal-footer">
			<input type="hidden" name="loanid" value="<?php echo $loanid;?>">
			<input type="submit" class="btn btn-sm btn-danger " name="button" value="Assign PN" >
		  </div>
		  </form>
	  </div>
	</div>
	</div>
    
    <div class="modal fade" id="computationmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
        	<div class="modal-header">
            	Computation Details
        	</div>
            <div class="modal-body">
				<?php echo $comp['table']; ?>
            </div>
            <div class="modal-footer">
           	 <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
            </div>
	  	</div>
		</div>
	</div>
    
    <div class="modal fade" id="createci" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-lg">
		<div class="modal-content">
        	<div class="modal-header">
            	<h4>Credit Investigation Report</h4>
        	</div>
            <?php $this->load->view('loans/forms/credit');?>
            
	  	</div>
		</div>
	</div>