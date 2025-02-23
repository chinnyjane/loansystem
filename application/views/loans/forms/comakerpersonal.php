<?php
$disabled = '';

if(isset($loaninfo)){
	$loaninfo = $loaninfo->row();
	$status = strtolower($loaninfo->status);
	if($status =='processing' or $status =='ci' or $status == 'for approval'){
		$readonly=false;
		$disabled =false;
	}else{
		$readonly=true;
		$disabled = true;	
	}
	$loanstatus = $loaninfo->LoanSubCode;
	$loan = $loaninfo;
	$amountfield = array("name"=>'loanapplied',
					"id"=>'loanapplied',
					"value"=>$loan->AmountApplied,
					"class"=>'input-sm form-control');
	
	
	if($comaker != false){
		$personal = $comaker['clientinfo']->row();
		$loanid = array('loanid'=>$loan->loanID,
					'clientid'=>$clientid,
					'comakerid'=>$personal->ClientID);
		$firstname = $personal->firstName;
		$mname = $personal->MiddleName;
		$lname = $personal->LastName;
		$bdate = $personal->dateOfBirth;
		$contact = $personal->contact;
		$civilstatus = $personal->contact;
		$province = $personal->province;
		$city = $personal->city;
		$brgy = $personal->barangay;
		$address = $personal->address;
		$gender= $personal->gender;
		
		if($personal->civilStatus != 'single'){
			if($comaker['spouseinfo']->num_rows() > 0){
				$spouse = $comaker['spouseinfo']->row();
				$spfirstname = $spouse->firstname;
				$spmname = $spouse->middlename;
				$splname = $spouse->lastname;
				$spbdate = $spouse->dateOfBirth;
				$spcontact = $spouse->contact;
				$spwork = $spouse->occupation;
				$spcompany = $spouse->company;
				$spsalary = $spouse->salary;
			}else{			
				$spfirstname = '';
				$spmname = '';
				$splname = '';
				$spbdate = '';
				$spcontact = '';
				$spwork = '';
				$spcompany = '';
				$spsalary = '';
			}
		}else{
			$disabled = "disabled";
			$spfirstname = '';
				$spmname = '';
				$splname = '';
				$spbdate = '';
				$spcontact = '';
				$spwork = '';
				$spcompany = '';
				$spsalary = '';
		}
	}else{
		$firstname = '';
		$mname = '';
		$lname = '';
		$bdate = '';
		$contact = '';
		$civilstatus = '';
		$province = '';
		$city = '';
		$brgy = '';
		$address = '';
		$gender= '';
		
		$spfirstname = '';
		$spmname = '';
		$splname = '';
		$spbdate = '';
		$spcontact = '';
		$spwork = '';
		$spcompany = '';
		$spsalary = '';
	}
	
}else{
	$readonly='';
	$disabled = '';
	$loanstatus = 'N';
	
	$amountfield = array("name"=>'loanapplied',
					"id"=>'loanapplied',
					"value"=>'',
					"class"=>'input-sm form-control');
	$loanid = array('loanid'=>'',
					'clientid'=>'',
					'comakerid'=>'');
	
	$firstname = '';
		$mname = '';
		$lname = '';
		$bdate = '';
		$contact = '';
		$civilstatus = '';
		$province = '';
		$city = '';
		$brgy = '';
		$address = '';
		$gender= '';
		
		$spfirstname = '';
		$spmname = '';
		$splname = '';
		$spbdate = '';
		$spcontact = '';
		$spwork = '';
		$spcompany = '';
		$spsalary = '';
}
?>

<form id="personal" method="post" action="<?php echo base_url();?>client/overview/comakervalidation" class="formpost">
	<div class="panel-body">
    <h4>Personal Information</h4>
    <hr/>   
    <div class="row form-group">
        <div class="col-sm-4"><label>Name: </label>  <?php //echo strtoupper($c->LastName.", ".$c->firstName." ".$c->MiddleName); ?>
            <input type="text" class="form-control input-sm" placeholder="First name" name="firstname" id="firstname" value="<?php echo set_value('firstname',$firstname);?>" required>
        </div>
        <div class="col-sm-4"><label>Middle Name</label> 
            <input type="text" class="form-control input-sm" placeholder="Middle Name"  name="mname" value="<?php echo set_value('mname',$mname);?>" required>
        </div>
        <div class="col-sm-4"><label>Last Name</label> 
            <input type="text" class="form-control input-sm" placeholder="Last Name"  name="lname" value="<?php echo set_value('lname',$lname);?>"  required>
        </div>
    </div>
    
    <div class="row form-group">
        <div class="col-sm-4"><label>Date of Birth</label> 
        <input type="date" name="bdate" value="<?php echo set_value('bdate', $bdate );?>" class="input-sm form-control">
        <?php //echo $this->form->datefield("bdate", $this->input->post('bdate') );?>
        </div>
        <div class="col-sm-4"><label>Contact Number</label>
        <input type="text" class="form-control input-sm" id="contact" placeholder="Contact Number" name="contact" value="<?php echo set_value('contact',  $contact);?>"  required>
        </div>
        <div class="col-sm-4"><label>Civil Status</label>
        <?php echo $this->form->civilstatus("civilstatus", $civilstatus);?>
        </div>
    </div>
    
    <div class="row form-group">
        <div class="col-sm-4"><label>Province</label> 
        <?php echo $this->form-> provincefield("province",  $province);?>
        </div>
        <div class="col-sm-4"><label>City</label>
        <?php echo $this->form->cityfield("city",  $city,  $province);?>
        </div>
        <div class="col-sm-4"><label>Barangay</label>
        <input type="text" class="form-control input-sm" placeholder="Barangay" name="brgy"   value="<?php echo set_value('brgy',  $brgy);?>" required>
        </div>
    </div>	
    
    <div class="row form-group"> 
        <div class="col-sm-8">
            <label>House #, Street</label>
            <input type="text" class="form-control input-sm" placeholder="House No., Street, Barangay" name="address" value="<?php echo set_value('address', $address);?>"  required>
        </div>
        <div class="col-sm-4">
        	<label>Gender</label>
        	<?php echo $this->form->gender("gender",$gender); ?>
        </div>
    </div>
    
    </div>			 
    
    <div class="panel-body">
        <h4>Spouse Information</h4>
        <hr/>
        <label>Spouse Name</label>
        <div class="row form-group">
            <div class="col-sm-4"><input type="text" class="form-control input-sm" placeholder="First name" name="spfirstname" id="spfirstname" value="<?php echo set_value('spfirstname', $spfirstname);?>" <?php echo $disabled;?> > </div>
            <div class="col-sm-4"><input type="text" class="form-control input-sm" placeholder="Middle Name"  name="spmname" id="spmname" <?php echo $disabled;?> value="<?php echo set_value('spmname', $spmname);?>"></div>
            <div class="col-sm-4"><input type="text" class="form-control input-sm" placeholder="Last Name" name="splname"  id="splname" <?php echo $disabled;?> value="<?php echo set_value('splname', $splname);?>"></div>
        </div>
        
        <label>Occupation</label>
        <div class="row form-group">
            <div class="col-sm-4"><input type="text" class="form-control input-sm" placeholder="Occupation" name="spwork" id="spwork" value="<?php echo set_value('spwork', $spwork);?>" <?php echo $disabled;?>> </div>
            <div class="col-sm-4"><input type="text" class="form-control input-sm" placeholder="Company"  name="spcompany"  id="spcompany" <?php echo $disabled;?> value="<?php echo set_value('spcompany', $spcompany);?>"></div>
            <div class="col-sm-4"><input type="text" class="form-control input-sm" placeholder="Salary" name="spsalary" id="spsalary" <?php echo $disabled;?> value="<?php echo set_value('spsalary', $spsalary);?>" ></div>
        </div>
        
        
        <div class="row form-group">
            <div class="col-sm-4"><label>Contact Number</label><input type="text" class="form-control input-sm" placeholder="contact number" name="spcontact" id="spcontact" <?php echo $disabled;?>  value="<?php echo set_value('spcontact', $spcontact);?>" ></div>	
            <div class="col-sm-4"><label>Date of Birth</label> 
        	<?php //echo $this->form->datefield("spbdate", $this->input->post('spbdate') );?>
            <input type="date" name="spbdate" id="spbdate" placeholder="mm-dd-yyyy" class="form-control input-sm " <?php echo $disabled;?>  value="<?php echo set_value('spbdate', $spbdate);?>" >
        	</div>        
        </div>		   
    </div>
       
	<div class="panel-footer">
    	 <?php echo form_hidden($loanid); ?>
	<input type="submit" class="btn btn-primary btn-lg btn-block" name="submit" value="Save Comaker Profile">
	</div>		   
</form>


