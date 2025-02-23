<?php
$disabled = '';
if(isset($client)){
	if($client->num_rows() > 0){
		$c = $client->row();
		$_POST['firstname'] = $c->firstName;
		$_POST['mname'] = $c->MiddleName;
		$_POST['lname']=$c->LastName;
		$_POST['bdate'] =$c->dateOfBirth;
		$_POST['civilstatus'] = $c->civilStatus;
		$_POST['province'] = $c->province;
		$_POST['city'] = $c->city;
		$_POST['brgy'] = $c->barangay;
		$_POST['address'] =$c->address;
		$_POST['gender'] = $c->gender ;
		$_POST['contact']=$c->contact;
		
		if($c->civilStatus != 'single'){
			if($spouse->num_rows() > 0){
				$spouseinfo = $spouse->row();	
				$_POST['spfirstname'] = $spouseinfo->firstname;
				$_POST['spmname'] = $spouseinfo->middlename;
				$_POST['splname'] = $spouseinfo->lastname;
			}
			
		}else{
			$disabled = 'disabled';	
		}
	}
	
}
?>

<form id="personal" method="post" action="<?php echo base_url();?>client/overview/clientvalidation" class="formpost">	
    <table class="table-condensed table-no-bordered" width="100%">
    	<tr>
        	<td>Client Name:</td>
        </tr>
        <tr>
        	<td><input type="text" class="form-control input-sm" placeholder="Last Name"  name="lname" value="<?php echo set_value('lname',$this->input->post('lname'));?>"  required></td>
            <td><input type="text" class="form-control input-sm" placeholder="First name" name="firstname" id="firstname" value="<?php echo set_value('firstname',$this->input->post('firstname'));?>" required></td>
            <td><input type="text" class="form-control input-sm" placeholder="Middle Name"  name="mname" value="<?php echo set_value('mname',$this->input->post('mname'));?>" required></td>
        </tr>
        <tr>
        	<td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>
    <div class="row form-group">
        <div class="col-sm-4"><label>Name: </label>  <?php //echo strtoupper($c->LastName.", ".$c->firstName." ".$c->MiddleName); ?>
            <input type="text" class="form-control input-sm" placeholder="First name" name="firstname" id="firstname" value="<?php echo set_value('firstname',$this->input->post('firstname'));?>" required>
        </div>
        <div class="col-sm-4"><label>Middle Name</label> 
            <input type="text" class="form-control input-sm" placeholder="Middle Name"  name="mname" value="<?php echo set_value('mname',$this->input->post('mname'));?>" required>
        </div>
        <div class="col-sm-4"><label>Last Name</label> 
            <input type="text" class="form-control input-sm" placeholder="Last Name"  name="lname" value="<?php echo set_value('lname',$this->input->post('lname'));?>"  required>
        </div>
    </div>
    
    <div class="row form-group">
        <div class="col-sm-4"><label>Date of Birth</label> 
        <input type="date" name="bdate" value="<?php echo set_value('bdate', $this->input->post('bdate') );?>" class="input-sm form-control">
        <?php //echo $this->form->datefield("bdate", $this->input->post('bdate') );?>
        </div>
        <div class="col-sm-4"><label>Contact Number</label>
        <input type="text" class="form-control input-sm" id="contact" placeholder="Contact Number" name="contact" value="<?php echo set_value('contact',  $this->input->post('contact'));?>"  required>
        </div>
        <div class="col-sm-4"><label>Civil Status</label>
        <?php echo $this->form->civilstatus("civilstatus",  $this->input->post('civilstatus'));?>
        </div>
    </div>
    
    <div class="row form-group">
        <div class="col-sm-4"><label>Province</label> 
        <?php echo $this->form-> provincefield("province",  $this->input->post('province'));?>
        </div>
        <div class="col-sm-4"><label>City</label>
        <?php echo $this->form->cityfield("city",  $this->input->post('city'),  $this->input->post('province'));?>
        </div>
        <div class="col-sm-4"><label>Barangay</label>
        <input type="text" class="form-control input-sm" placeholder="Barangay" name="brgy"   value="<?php echo set_value('brgy',  $this->input->post('brgy'));?>" required>
        </div>
    </div>	
    
    <div class="row form-group"> 
        <div class="col-sm-8">
            <label>House #, Street</label>
            <input type="text" class="form-control input-sm" placeholder="House No., Street, Barangay" name="address" value="<?php echo set_value('address', $this->input->post('address'));?>"  required>
        </div>
        <div class="col-sm-4">
        	<label>Gender</label>
        	<?php echo $this->form->gender("gender", $this->input->post('gender')); ?>
        </div>
    </div>
    
 		 
    
    <div class="panel-body">
        <h4>Spouse Information</h4>
        <hr/>
        <label>Spouse Name</label>
        <div class="row form-group">
            <div class="col-sm-4"><input type="text" class="form-control input-sm" placeholder="First name" name="spfirstname" id="spfirstname" value="<?php echo set_value('spfirstname',  $this->input->post('spfirstname'));?>" <?php echo $disabled;?> > </div>
            <div class="col-sm-4"><input type="text" class="form-control input-sm" placeholder="Middle Name"  name="spmname" id="spmname"   value="<?php echo set_value('spmname',  $this->input->post('spmname'));?>" <?php echo $disabled;?>></div>
            <div class="col-sm-4"><input type="text" class="form-control input-sm" placeholder="Last Name" name="splname"  id="splname"  value="<?php echo set_value('splname',  $this->input->post('splname'));?>" <?php echo $disabled;?>></div>
        </div>
        
        <label>Occupation</label>
        <div class="row form-group">
            <div class="col-sm-4"><input type="text" class="form-control input-sm" placeholder="Occupation" name="spwork" id="spwork"  value="<?php echo set_value('spwork',  $this->input->post('spwork'));?>" <?php echo $disabled;?>> </div>
            <div class="col-sm-4"><input type="text" class="form-control input-sm" placeholder="Company"  name="spcompany"  id="spcompany"  value="<?php echo set_value('spcompany',  $this->input->post('spcompany'));?>" <?php echo $disabled;?>></div>
            <div class="col-sm-4"><input type="text" class="form-control input-sm" placeholder="Salary" name="spsalary" id="spsalary"  value="<?php echo set_value('spsalary',  $this->input->post('spsalary'));?>" <?php echo $disabled;?> ></div>
        </div>
        
        
        <div class="row form-group">
            <div class="col-sm-4"><label>Contact Number</label><input type="text" class="form-control input-sm" placeholder="contact number" name="spcontact" id="spcontact"  value="<?php echo set_value('spcontact',  $this->input->post('spcontact'));?>" <?php echo $disabled;?>></div>	
            <div class="col-sm-4"><label>Date of Birth</label> 
        	<?php //echo $this->form->datefield("spbdate", $this->input->post('spbdate') );?>
            <input type="date" name="spbdate" id="spbdate" placeholder="mm-dd-yyyy" class="form-control input-sm "  value="<?php echo set_value('spbdate',  $this->input->post('spbdate'));?>" <?php echo $disabled;?> >
        	</div>        
        </div>		   
    </div>
    

   
	<div class="panel-footer">
    	<?php if(isset($clientid)){ ?>
        <input type="hidden" name="clientid" value="<?php echo $clientid;?>">
        <?php } ?>
	<input type="submit" class="btn btn-primary btn-lg btn-block " value="Save Client Profile">
	</div>		   
</form>


