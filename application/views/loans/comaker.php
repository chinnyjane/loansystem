<div class="panel panel-default">	
	<div id="co-maker" class="panel-collapse collapse in">
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
			</div>
			<div class="row form-group">
				<div class="col-md-3">
					<label>Date of Birth</label>
					<?php echo $this->form->datefield("comaker[dob]", ''); ?>
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
	</div>		
</div>