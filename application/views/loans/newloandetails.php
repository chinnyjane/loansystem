<?php
	$clientid = $this->uri->segment(3);
	$loanid = $this->uri->segment(5);
	
	$loans = $this->Loansmodel->getLoanbyID($loanid);
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
			$agent = $this->UserMgmt->get_user_byid($loan->LoanProcessor);
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
		$bookpn = 'No PN. ';
		$assignpn = '<button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#assignpn" href="#">Assign Promissory Note (PN)</button>';
}	else $assignpn = '';


?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/modaljs.js"></script>
<script src="<?php echo base_url();?>assets/js/loansprocess.js" type="text/javascript"></script>	
<div class="panel panel-<?php echo $color;?>">
	<div class="panel-heading">Loan Details <span class="pull-right"><b><?php echo strtoupper($status);?></span></div>
	<div class="panel-body">
		<div class="row form-group">
			<div class="col-md-4">
				<b>LOAN INFORMATION</b>
			</div>
			<div class="col-md-4 pull-right" style="text-align: right">
				</b>
			</div>
		</div>
			<hr/>
			<?php
				$this->table->set_heading("Loan Type"," : ", $pname);
				$this->table->add_row("<b>Name of Borrower </b>"," : ", "<b><a href='".base_url()."client/profile/".$clientid."'>". $p['lname'].", ". $p['firstname']."</a></b>");
				$this->table->add_row("<b>PN no. </b>"," : ", $bookpn."&nbsp;".$assignpn);
				$this->table->add_row("<b>Amount Applied</b>"," : ","<b>Php &nbsp;".number_format($amount,2)." ( ".strtoupper($this->loansetup->convert_number_to_words($amount)) ." PESOS ONLY ) </b>" );
				$this->table->add_row("<b>Net Proceeds  </b>", " : ", '<button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#computationmodal" href="#">Fee Details</button>');
				$this->table->add_row("<b>Terms : </b>", " : ",$terms." months" );
				$this->table->add_row("<b>Monthly Installment : </b>", " : ",number_format($amount/$terms,2) );
				
				echo $this->table->generate();
				
				$tmpl = array ('table_open'   => '<table class="table  table-condensed " >',
			'thead_open' => '<thead class="header">'	); 
				$this->table->set_template($tmpl); 
				$this->table->set_heading("Date Applied", "Date Approved", "Date Released", "Loan Processor");
				$this->table->add_row( date("F d, Y ", strtotime($loan->dateApplied)),$dateapproved, $datedisbursed  ,$ag);
				echo $this->table->generate();
			?>		
	</div>
	<div class="panel-footer">
	<?php 
	if($status == 'processing' or $status == 'approval' ) {
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
	<?php }
	} else {
		$cv = $this->Loansmodel->cvexist($bookpn);
		if(!empty($bookpn)){ 
			if($cv->num_rows() > 0 ){ ?>
				<a class="btn btn-default btn-sm" href="<?php echo base_url();?>forms/checkvoucher/<?php echo $loanid;?>" target="_blank">Check Voucher</a> 
					&nbsp; 
					<a class="btn btn-default btn-sm" href="<?php echo base_url();?>forms/disclosure/<?php echo $loanid;?>" target="_blank">Disclosure Statement</a>
			&nbsp;
			<a class="btn btn-default btn-sm" href="<?php echo base_url();?>forms/promissory/<?php echo $loanid;?>" target="_blank">Promissory Note</a>
			&nbsp;
			<a class="btn btn-default btn-sm" href="<?php echo base_url();?>forms/computation/<?php echo $loanid;?>" target="_blank">Computation Sheet</a>
			&nbsp;
			<a class="btn btn-default btn-sm" href="<?php echo base_url();?>forms/rfplmonitoring/<?php echo $loanid;?>" target="_blank">RFPL Monitoring</a>
			<?php }else if ($status == 'approved'){	?>	
			<button class="btn btn-success btn-sm" data-toggle="modal" data-target="#disburse" href="#">Create CV</button>
			
			<?php  
				}
			}
		}		?>
	
	
	</div>
		
	</div>

		<ul class="nav nav-tabs" id="myTab">
			
			<li><a href="#schedule" data-toggle="tab">Loan Schedule</a></li>
			<li ><a href="#pensioninfo" data-toggle="tab">Collateral</a></li>			
			<li><a href="#comaker" data-toggle="tab">Co-maker</a></li>
			<li><a href="#requirements" data-toggle="tab">Requirements</a></li>			
		</ul>
		<div class="tab-content">	
			
				<div class="tab-pane " id="schedule">		
				<?php 
				if($status == 'processing')
					echo $this->loansetup->loanschedule($terms, $amount, $loan->dateApplied);
				else
					echo $this->loansetup->approvedloansched($loanid, $amount);
				?>
				
			</div>
			<div class="tab-pane" id="pensioninfo">
				 <?php echo $this->loansetup->pensioninfo($pensionid);?>
			</div>
			
			<div class="tab-pane " id="comaker">		
				<?php echo $this->loansetup->comakerinfo($loanid); ?>
			</div>
			<div class="tab-pane " id="requirements">	
				<?php
				
				if($status == "processing" and $approve == 'disabled'){ ?>
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