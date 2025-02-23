<?php 
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
	$loan = $loaninfo;
	$loanid = array('loanid'=>$loan->loanID,
					'clientid'=>$clientid);
}else{
	$loanid = array('loanid'=>'',
					'clientid'=>'');	
}

?>
<form action="<?php echo base_url();?>loans/application/comaker" method="post" class="formpost">
<div class="panel-body">
			<div class="row form-group">
				<div class="col-md-3">
					<label>First name</label>
					<input type="text" class="input-sm form-control" name="comaker[firstname]" value="<?php echo set_value("comaker[firstname]",$this->input->post("comaker[firstname]"));?>">
				</div>
				<div class="col-md-3">
					<label>Middle  name</label>
					<input type="text" class="input-sm form-control" name="comaker[mname]" value="<?php echo set_value("comaker[mname]",$this->input->post("comaker[mname]"));?>">
				</div>
				<div class="col-md-3">
					<label>Last name</label>
					<input type="text" class="input-sm form-control" name="comaker[lname]" value="<?php echo set_value("comaker[lname]",$this->input->post("comaker[lname]"));?>">
				</div>
                <div class="col-md-3">
					<label>Relationship to Borrower</label>
					<input type="text" class="input-sm form-control" name="comaker[relationship]" value="<?php echo set_value("comaker[relationship]",$this->input->post("comaker[relationship]"));?>">
				</div>
			</div>
			<div class="row form-group">
				<div class="col-md-3">
					<label>Date of Birth</label>
					<?php //echo $this->form->datefield("comaker[dob]", ''); ?>
                     <input type="text" name="comaker[dob]" id="comaker[dob]" placeholder="mm-dd-yyyy" class="form-control input-sm datepicker" >
				</div>
				<div class="col-md-3">
					<label>Contact Num</label>
					<input type="text" class="input-sm form-control" name="comaker[contact]" value="<?php echo set_value("comaker[contact]",$this->input->post("comaker[contact]"));?>">
				</div>
				<div class="col-md-3">
					<label>Civil Status</label>
					<?php echo $this->form->civilstatus('comaker[civilstatus]', ''); ?>
				</div>
				<div class="col-md-3">
					<label>Gender</label>
					<?php echo $this->form->gender("comaker[gender]", ''); ?>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-md-3">
					<label>Province</label>					
					<?php echo $this->form->provincefield("comaker[province]", $this->input->post('comaker[province]')); ?>
				</div>
				<div class="col-md-3">
					<label>City</label>
					<?php echo $this->form->cityfield("comaker[city]", $this->input->post('comaker[city]'), $this->input->post('comaker[province]'));?>
				</div>
				<div class="col-md-3">
					<label>Barangay</label>
					<input type="text" class="input-sm form-control" name="comaker[barangay]" value="<?php echo set_value("comaker[barangay]",$this->input->post("comaker[barangay]"));?>">
				</div>
				<div class="col-md-3">
					<label>House #, Street</label>
					<input type="text" class="input-sm form-control" name="comaker[address]" value="<?php echo set_value("comaker[address]",$this->input->post("comaker[address]"));?>">
				</div>
			</div>
		</div>

<div class="panel-footer">
	<?php echo form_hidden($loanid); ?>
	<input type="submit" class="btn btn-primary btn-lg btn-block"   name="submit" value="Save Co-maker's Information">
</div>
</form>