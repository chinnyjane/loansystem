<?php

	$clientid = $this->session->userdata('clientid');
	$loantype = $this->session->userdata('loantype');
	
	//check if client has existing loans
	$status = $this->loansetup->clientstatusonLoan($loantype, $clientid);
	
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
	//get pension info
	$pension = $this->Loansmodel->get_pensionofclient($clientid);
echo validation_errors();
?>
<form action="<?php echo current_url();?>" method="post" id="loandetailsform">
<div class="panel panel-green">
	<div class="panel-heading">New Loan Application</div>
	<div class="panel-body">
		<div class="row form-group">
			<div class="col-md-4">
			<label>Client Name: </label> <a href="<?php echo $profileurl;?>"><?php echo $p['firstname']." ". $p['lname'];?></a>
			</div>
			<div class="col-md-4">
			<label>Loan Type: </label> <?php echo $pname;?>
			</div>
			<div class="col-md-4">
			<label>Date: </label> <?php echo date("F d, Y",strtotime($this->auth->localdate()));?>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-4">
			<label>Age: </label> <?php echo $p['age']; ?> years old
			</div>
			<div class="col-md-4">
			<label>Date of Birth: </label> <?php echo date("F d, Y",strtotime($p['dob']));?>
			</div>
			<div class="col-md-4">
			<label>Civil Status: </label> <?php echo strtoupper($p['civilstatus']);?>
			</div>
		</div>
		<hr/>
		<div class="row form-group">
		<div class="col-md-4">
				<label>Select Pension : </label>
				<div class="input-group">
				<?php if ( $pension->num_rows() > 0 ){ ?>
					<select name="pensionaccount"  class="form-control input-sm" required>
					 <?php foreach($pension->result() as $p){
						echo '<option value="'.$p->PensionID.'">'.strtoupper($p->PensionType)." - Php ".number_format($p->monthlyPension,2)."/monthly</option>";
					 } ?>
					</select>
				<?php }?>
				<span class="input-group-addon"><a href="<?php echo base_url();?>client/profile/<?php echo $this->session->userdata("clientid");?>#pensiontab"> <i class="fa fa-plus"></i> New Pension</a></span>
				
			</div>
			</div>
		
			<div class="col-md-3">
				<label>Amount Applied * : </label> 
				<div class="input-group">
					<span class="input-group-addon">Php</span>
					<input type="text" name="loanapplied" id="loanapplied" value="<?php echo set_value("loanapplied", $this->input->post("loanapplied"));?>" class="form-control input-sm" required>	  
					<input type="hidden" name="clientstatus" id="clientstatus" value="<?php echo $status;?>"  >	  
					<input type="hidden" name="civilstatus" value="<?php echo $c->civilStatus;?>">
				</div>
			</div>	

			<div class="col-md-3">
				<label>Terms * : </label>
					<select name="terms" id="terms" class="form-control input-sm" required>
					<?php $count=1; 
					if ( $age < 70)
					$maxterm = 24;
					else
					$maxterm = 18;				
					
					while ($count <= $maxterm) {?> 
						<option value='<?php echo $count;?>' <?php echo set_select('terms', $count ); ?>><?php echo $count;?></option>
					<?php $count++;}?>
					</select>
			</div>
			
		</div>
		<div class="alert alert-warning">
		NOTE: <label>Maximum Loan Amount</label>
		<?php echo $this->loansetup->maxloanOnPension($clientid);?> at <?php echo $maxterm;?> months term			
				
		</div>
		<ul class="nav nav-tabs" id="myTab">
			<li class="active"><a href="#details" data-toggle="tab">Loan Computation</a></li>
			<li><a href="#comaker" data-toggle="tab">Co-maker</a></li>
			<li><a href="#requirements" data-toggle="tab">Requirements</a></li>			
		</ul>
		<div class="tab-content">	
			<div class="tab-pane active" id="details">
				<?php $this->load->view('loans/loanfees');?>
			</div>		
			
			<div class="tab-pane " id="comaker">		
				<?php //if(isset( $c->civilStatus) and   $c->civilStatus != 'married') { ?>
				<?php $this->load->view('loans/comaker',$p);?>
				<?php //} else echo "<div class='alert alert-warning'>Spouse is the Comaker.</div>"; ?>
			</div>
			
			<div class="tab-pane " id="requirements">		
				<?php $this->load->view('loans/requirements',$p);?>
			</div>			
		</div>
	</div>
	<div class="panel-footer">
		<input type="hidden" name="loantype" value="<?php echo $loantype;?>">
		<input type="hidden" name="pid" value="<?php echo $loantype;?>">		
		<input type="hidden" name="clientid" value="<?php echo $clientid;?>">
		<input type="submit" name="submit" value="Submit Application" id="submitapplication" class="btn btn-success"> &nbsp; 
		<a class="btn btn-default" href="<?php echo current_url();?>/cancel">Cancel Application</a> 
	</div>

</div>
	</form>

<script type="text/javascript" src="<?php echo base_url();?>assets/js/modaljs.js"></script>
<script src="<?php echo base_url();?>assets/js/loansprocess.js" type="text/javascript"></script>				
						