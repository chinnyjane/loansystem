<?php
if(isset($emp)){
	if($emp->num_rows() > 0){
		$emp = $emp->row();
		$_POST['emp[employer]'] = $emp->employer;
		$_POST['emp[addess]'] = $emp->address;
		$_POST['emp[position]'] = $emp->position;
		$_POST['emp[nature]'] = $emp->natureOfBusiness;
		$_POST['emp[contact]'] = $emp->contact;
		$_POST['emp[length]'] = $emp->lengthOfService;
		$_POST['emp[status]'] = $emp->status;
		$_POST['emp[salary]'] = $emp->monthlySalary;
	}
}

if(isset($incomeexpense)){
	if($incomeexpense->num_rows() > 0){
		foreach($incomeexpense->result() as $ie){
			if($ie->type == 'income'){
				$income[] = array("nature"=>$ie->nature,
							"value"=>$ie->value,
							"id"=>$ie->id);
			}elseif($ie->type == 'expense'){
				$expense[] = array("nature"=>$ie->nature,
							"value"=>$ie->value,
							"id"=>$ie->id);
			}
		}
	}
}
?>

<!-- EMPLOYMENT -->
<h4>FINANCIAL INFORMATION</h4>
<hr>
<table class="table-condensed table-no-bordered" width="100%">
	<tr>
		<td>Employer/Business Name: <br/>
			<input type="text" name="emp[employer]" value="<?php echo set_value('emp[employer]',$this->input->post('emp[employer]'));?>" class="form-control input-sm" >
		</td>
			
		<td>Address:<br/>
                <input type="text" name="emp[address]" value="<?php echo set_value('emp[address]',$this->input->post('emp[address]'));?>" class="form-control input-sm" >
		</td>
		<td>
			Nature of Business<br/>
                <input type="text" name="emp[nature]" value="<?php echo set_value('emp[nature]',$this->input->post('emp[nature]'));?>" class="form-control input-sm" >
		</td>
		<td>
			Contact No:<br/>
			<input type="text" name="emp[contact]" value="<?php echo set_value('emp[contact]',$this->input->post('emp[contact]'));?>" class="form-control input-sm" >
		</td>
	</tr>
	<tr>
		<td>Designation<br/>
                <input type="text" name="emp[position]" value="<?php echo set_value('emp[position]',$this->input->post('emp[position]'));?>" class="form-control input-sm" ></td>		
		<td>Length of Service<br/>
                <input type="text" name="emp[length]" value="<?php echo set_value('emp[length]',$this->input->post('emp[length]'));?>" class="form-control input-sm" ></td>
		<td>Employment Status<br/>
                <input type="text" name="emp[status]" value="<?php echo set_value('emp[status]',$this->input->post('emp[status]'));?>" class="form-control input-sm" ></td>
		<td>Monthly Salary<br/>
			<input type="text" name="emp[salary]" value="<?php echo set_value('emp[salary]',$this->input->post('emp[salary]'));?>" class="form-control input-sm" >
		</td>
	</tr>
</table>
    <!-- SOURCE of INCOME and EXPENSES -->
<div class="row form-group">
	<div class="col-md-6">
	<h4>SOURCE OF INCOME</h4>
	<hr/>
		<table id='source' class="well table-condensed table-no-bordered" width="100%">
			<tr>
				<th>Nature</th>
				<th>Value</th>
			</tr>
			<tbody>
			<tr>
				<td><input type="text" name="income[nature][]" class="form-control input-sm" placeholder="Enter income nature"></td>
				<td><input type="number" name="income[value][]" class="form-control input-sm" placeholder="Enter Value "></td>
			</tr>
			</tbody>
			<tfoot>
				<tr><td><button id="addincome" type="button" class="btn btn-xs btn-warning" ><i class="fa fa-plus"></i> Add Income</button></td></tr>
			</tfoot>
		</table>
		
	</div>
	<div class="col-md-6">
		<h4>MONTHLY EXPENSES</h4>
		<hr/>
		<table id='expenses' class="table-condensed table-no-bordered well" width="100%">
			<tr>
				<th>Nature</th>
				<th>Value</th>
			</tr>
			<tr>
				<td><input type="text" name="expense[nature][]" class="form-control input-sm" placeholder="Enter expense"></td>
				<td><input type="number" name="expense[value][]" class="form-control input-sm" placeholder="Enter Value"></td>
			</tr>			
			<tfoot>
				<tr><td><button id="addexpenses" type="button" class="btn btn-xs btn-warning" ><i class="fa fa-plus"></i> Add Expense</button></td></tr>
			</tfoot>
		</table>
	
	</div>
</div>
