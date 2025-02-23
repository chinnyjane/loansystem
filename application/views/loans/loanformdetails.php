
<?php
	//$clientid = $this->uri->segment(3);
	$loanid = $this->uri->segment(3);
	
	$loans = $this->Loansmodel->getLoanbyID($loanid);

	if($loans->num_rows() > 0){
		foreach($loans->result() as $loan){
			$loanname = $loan->LoanName;
			$clientid = $loan->ClientID;
			$loantype = $loan->LoanType;
			$pn = $loan->PNno;
			$amount = $loan->AmountApplied;
			$status = $loan->status;
			$pensionid = $loan->pensionID;
			$terms = $loan->Term ;
			$monthy = number_format($amount/$terms,2);
			$applied = $loan->dateApplied;
			$agent = $this->UserMgmt->get_user_byid($loan->LoanProcessor);
			if($agent->num_rows() > 0 ){
			$a = $agent->row();
			$ag= $a->lastname.", ".$a->firstname;
			}
		}
	}
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
	$spouse =  $this->Clientmgmt->getspouse($clientid);
	$dependents =  $this->Clientmgmt->getdependents($clientid);
	$creditor =  $this->Clientmgmt->getcreditor($clientid);
	if($client->num_rows() > 0){

		foreach($client->result() as $c){
			$p['firstname'] = $c->firstName;
			$p['mname'] = $c->MiddleName;
			$p['lname'] = $c->LastName;
			$p['dob'] = $c->dateOfBirth;
			//$p['city'] = $c->city;
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
	$tmpl = array ('table_open'          => '<table class="table  table-condensed  " >',
			'thead_open' => '<thead class="header">'	); 
				$this->table->set_template($tmpl); 
?>
<div class="panel-body">
<?php 
	$this->table->set_heading("Loan Type"," : ", $loanname,"Date Applied"," : ", date("F d, Y", strtotime($applied))); 
	$this->table->add_row("<b>Amount Applied </b>", " : ", number_format($amount,2), "<b>Terms of Loan  </b>"," : ", $terms." months" ); 
	//$this->table->add_row("Amount Applied : ", number_format($amount,2),'',''); 
	
	echo $this->table->generate();
?>
	
		<div class="row form-group">
			<div class="col-md-12">
				<h4>Personal Information</h4>
				<hr/>
			</div>
		</div>
		
		<?php 
		$this->table->set_heading("Applicant:", $loan->clname.", ".$loan->cfname, "Date of Birth:", date("F d, Y", strtotime($loan->dateOfBirth)), "Age:", $this->loansetup->get_age($loan->dateOfBirth).' yrs. old');
		$this->table->add_row("Residence : ", array("colspan"=>3, "data"=>$loan->address.", ".$p['barangay'].", ".$p['city']) );
		$this->table->add_row("Contact # : ", ($p['contact'] ? $p['contact'] : "- none -"), "Civil Status : ", $p['civilstatus'], "Gender : ", $g);
		
		echo $this->table->generate();		
		if($p['civilstatus'] == ' ' or $p['civilstatus'] == 'single'){
		?>
<h4>Spouse Information </h4>
 <hr/>
  <?php 
   if($spouse->num_rows() > 0) { 
		foreach ($spouse->result() as $sp){
			$fname = $sp->firstname;
			$mname = $sp->middlename;
			$lname = $sp->lastname;
			$work = $sp->occupation;
			$company = $sp->companyname;
			$salary = $sp->salary;			
			$contact = $sp->contact;			
			$bday = $sp->dateOfBirth;			
		}
	}else{
		$fname = '';
		$mname ='';
		$lname = '';
		$work = '';
		$company = '';
		$salary = '';			
		$contact = '';
		$bday = '';
	}
  $name = ($fname ? $lname.", ".$fname." ".$mname : "-");
  $bday = ($bday ? date("F d, Y", strtotime($bday)) : "-");
  
	 $this->table->set_heading(array('data'=>'SPOUSE NAME', 'style'=>'width:30%'),$name); 
	 $this->table->add_row("Contact #",$contact); 
	 $this->table->add_row("Date of Birth",$bday); 
	 $this->table->add_row("Occupation",$work); 
	 $this->table->add_row("Company",$company); 
	 $this->table->add_row("Salary",$salary); 
	 
	 echo $this->table->generate(); 
	 }
 ?> 
 <h4>Pension Information </h4>
 <hr/>
  <?php echo $this->loansetup->pensioninfo($pensionid);?>
  
  <h4>Dependents Information </h4>
  <hr/>
			 <?php 
			 if($dependents->num_rows() >0){
				$this->table->set_heading("Name", "Date of Birth", "Age");
				foreach ($dependents->result() as $dep){
					$this->table->add_row($dep->firstname." ".$dep->middlename." ".$dep->lastname, date("F d, Y", strtotime($dep->dateOfBirth)), $this->loansetup->get_age($dep->dateOfBirth));
				}
				echo $this->table->generate();
			 }else{
				echo "No dependents.";
			}
			 ?>
	<h4>Outstanding Obligations </h4>
  <hr/>
			 <?php 
			 if($creditor->num_rows() >0){
				$this->table->set_heading("Name", "Address", "Amount", "Remarks");
				foreach($creditor->result() as $cre){
					$this->table->add_row($cre->name, $cre->address, $cre->amount, $cre->amount);
				}
				echo $this->table->generate();
			 }else{
				echo "No Outstanding Obligations";
			 } ?>
</div>
<hr/>
<div class="panel-footer">
	<div class="row form-group">
		<div class="col-md-4 pull-right">
			<label>Amount Approved : </label>
			<div class=" input-group">
				<span class="input-group-addon">Php</span>
				<input type="text" name="approvedamount" value="<?php echo $amount;?>" class="input form-control">
			</div>
		</div>
	</div>
</div>	
 