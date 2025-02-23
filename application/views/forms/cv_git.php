<?php 
	
	
	//$loans = $this->Loansmodel->getLoanbyID($loanid);
	$tmpl = array ('table_open'          => '<table class="table  table-condensed table-hover " >',
			'thead_open' => '<thead class="header">'	); 
				$this->table->set_template($tmpl); 
	if(isset($loaninfo)){
		$loans = $loaninfo;
		
		if($loans->num_rows() > 0){
			$loan = $loans->row();
				$loanname = $loan->LoanName;
				$loantype = $loan->LoanType;
				$pn = $loan->PNno;
				$bookpn = $loan->PN;
				$amount = $loan->AmountApplied;
				$approved = $loan->approvedAmount;
				$status = $loan->status;
				$pensionid = $loan->pensionID;
				$terms = $loan->Term ;
				$branchID = $loan->branchID ;
				$clientid = $loan->ClientID ;
				$monthy = number_format($amount/$terms,2);
				$applied = $loan->dateApplied;
				$agent = $this->UserMgmt->get_user_byid($loan->LoanProcessor);
				if($agent->num_rows() > 0 ){
				$a = $agent->row();
				$ag= $a->lastname.", ".$a->firstname;
				}		
		}
		$datedisbursed = $loan->DateDisbursed ;
			$dateapproved = $loan->dateApproved ;
			if($datedisbursed == '0000-00-00 00:00:00')
			$datedisbursed= "-";
			else
			$datedisbursed = date("F d, Y", strtotime($datedisbursed));
			if($dateapproved == '0000-00-00 00:00:00')
			$dateapproved= "-";
			else
			$dateapproved = date("F d, Y", strtotime($dateapproved));
		
		$comp = $this->loansetup->loancomputation($amount,$terms,$loantype, $loanid);
	}else{
		$loanname = "";
		$loantype = "";
		$pn = "";
		$bookpn = "";
		$amount = "";
		$approved = "";
		$status = "";
		$pensionid = "";
		$terms = "" ;
		$branchID = $this->auth->branch_id();
		$clientid = "" ;
		$monthy = "";
		$applied = "";
		$a = "";
		$loanid = "";
		$datedisbursed = "" ;
		$dateapproved = "" ;
		if($datedisbursed == '0000-00-00 00:00:00')
		$datedisbursed= "-";
		else
		$datedisbursed = date("F d, Y", strtotime($datedisbursed));
		if($dateapproved == '0000-00-00 00:00:00')
		$dateapproved= "-";
		else
		$dateapproved = date("F d, Y", strtotime($dateapproved));
	}
	$amount = ($approved ? $approved : $amount);
	//$req = $this->loansetup->requirements($loanid, $loantype); 
	
	if($status =='processing'){
		$complete = $req['complete'];
		if(in_array("0",$complete) == true){
			$reqcom = false;
			$approve = "disabled";	
		}else{
			//update status to approval
			//$this->loansetup->updateLoanStatus('approval', $loanid);
			$reqcom = true;
			$approve = "";	
		}
	}elseif($status == 'approval'){
		$approve = "";	
	}
	
		//client info 
	if(isset( $clientinfo)){
	$client = $clientinfo;
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
	}




$dis = $this->Cashbalance->getTransactionType("disbursement");
$pn  = ($pn ? $pn : "-");
$payee = $p['lname'].", ".$p['firstname'];
$tmpl = array ('table_open'   => '<table class="table  table-condensed table-bordered" >',
			'thead_open' => '<thead class="header">'	); 
$this->table->set_template($tmpl); 
?>
<form class="form-horizontal" method="post" action="<?php echo base_url();?>cash/disbursements/post" id="disburseform">
<?php 
	if($dis->num_rows() >0){
		foreach($dis->result() as $d){
			if($d->transType == "Releases")
				echo "<input type='hidden' name='transtype'  value='".$d->transTypeID."' >";
		}
	}
?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Check Voucher</h4>
      </div>
      <div class="modal-body">
	  <div class="row form-group">
	  <div class="col-xs-4 has-error"><label>CV No.</label>
		<input type="text" name="reference" class="form-control input-sm" required>
		</div>
		
		<div class="col-xs-4 pull-right"><label>PN No.</label>
		<input type="text" name="PN" class="form-control input-sm" value = "<?php echo $bookpn;?>" required  >
		</div>
	  </div>
	  <div class="row form-group">
		<div class="col-xs-4"><label>Payee</label>
		<input type="text" name="particular" class="form-control input-sm"  value="<?php echo $payee;?>" required readonly>
		</div>
		<div class="col-xs-8"><label>Explanation</label>
			<textarea name="explanation" class="form-control input-sm" required>Net Proceeds of <?php echo $pname;?></textarea>
		</div>	
		</div>
		<div class="row form-group">
			<div class="col-xs-4 has-error" ><label>Bank</label>
			<select name="bankID" class="form-control input-sm" required>
				<?php foreach($banks->result() as $ba){
				if(!empty($ba->branchCode))
				$bcode = "-".$ba->branchCode;
				else $bcode = "";
				?>
					<option value="<?php echo $ba->branchBankID;?>"><?php echo $ba->bankCode.$bcode;?></option>
				<?php } ?>
			</select>
			</div>	
			<div class="col-xs-4 has-error"><label>Check No.</label>
				<input type="text" name="checkno" class="form-control input-sm" required>
			</div>
			<div class="col-xs-4 "><label>Amount</label>
				<input type="text" name="amount" placeholder="00.00" value="<?php echo $comp['net'];?>" class="form-control input-sm number" required readonly>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-12">
			<h4>Double Check the Computation before you proceed.</h4>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-12">
			<?php
			$tmpl = array ('table_open'   => '<table class="table  table-condensed table-bordered" id="cv" >',
			'thead_open' => '<thead class="header">'	); 
			$this->table->set_template($tmpl); 
			$this->table->set_heading("Account/Description", "DR", "CR");
			$this->table->add_row("PL", array("align"=>"right","data"=>number_format($amount,2)), array("align"=>"right","data"=>number_format(0,2)));
			if(count($comp['fee']) > 0){
			foreach($comp['fee'] as $feename=>$val){
				$this->table->add_row($feename, array("align"=>"right","data"=>number_format(0,2)), array("align"=>"right","data"=>number_format($val,2)));
			}
			}
			$this->table->add_row("Cash in Bank",array("align"=>"right","data"=>number_format(0,2)) , array("align"=>"right","data"=>number_format($comp['net'],2)));
			echo $this->table->generate();
			?>
			<button type="button" class="btn btn-sm" id="addfield">Add field</button>
			</div>
	  </div>
	  </div>
	   <div class="modal-footer">
		<input type="hidden" name="transdate" value="<?php echo  $this->auth->localdate();?>">
		<input type="hidden" name="branchID" value="<?php echo  $branchID;?>">
		<input type="hidden" name="submit" value="Add Disbursement">
		<input type="hidden" name="loanid" value="<?php echo $loanid;?>">
		<input type="hidden" name="method" value="<?php echo $loan->paymentmethod;?>">
		<input type="hidden" name="startpayment" value="<?php echo $loan->dateStartPayment;?>">
		<input type="hidden" name="exterm" value="<?php echo $loan->extension;?>">
		<input type="hidden" name="loancode" value="<?php echo $loan->LoanSubCode;?>">
		<input type="hidden" name="term" value="<?php echo $loan->Term;?>">
		<input type="hidden" name="approved" value="<?php echo $loan->approvedAmount;?>">
		<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
       <input type="button" class="btn btn-sm btn-danger " name="button" id="dispost" value="Create Voucher">
      </div>
	</div>
</form>
