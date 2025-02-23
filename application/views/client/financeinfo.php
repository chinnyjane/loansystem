<?php
if(isset($emp)){
	if($emp->num_rows() > 0){
		$emp = $emp->row();
		$_POST['emp[employer]'] = $emp->employer;
		$_POST['emp[address]'] = $emp->address;
		$_POST['emp[position]'] = $emp->position;
		$_POST['emp[nature]'] = $emp->natureOfBusiness;
		$_POST['emp[contact]'] = $emp->contact;
		$_POST['emp[length]'] = $emp->lengthOfService;
		$_POST['emp[status]'] = $emp->status;
		$_POST['emp[salary]'] = $emp->monthlySalary;
	}
}
$sumIncome = 0;
	$sumExpense = 0;
if(isset($incomeexpense)){
	if($incomeexpense->num_rows() > 0){
		foreach($incomeexpense->result() as $ie){
			if($ie->type == 'income'){
				$income[] = array("nature"=>$ie->nature,
							"value"=>$ie->value,
							"id"=>$ie->id);
				$sumIncome += $ie->value;
			}elseif($ie->type == 'expense'){
				$expense[] = array("nature"=>$ie->nature,
							"value"=>$ie->value,
							"id"=>$ie->id);
				$sumExpense += $ie->value;
			}
		}
	}
}
?>
<form id="personal" method="post" action="<?php echo base_url();?>client/overview/financialinfo" class="formpost" >
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
				<th><input type="checkbox"></th>
				<th>Nature</th>
				<th>Value</th>
			</tr>
			 <?php
			if(isset($income)){				
				foreach($income as $in) : ?>						
						<tr>
							<td><input type="checkbox" name="remove[<?php echo $in['id'];?>]" value="1">
							<input type="hidden" name="income[id][]" value="<?php echo $in['id'];?>">
							</td>
							<td><input type="text" name="income[nature][<?php echo $in['id'];?>]" class="form-control input-sm" value="<?php echo $in['nature'];?>" placeholder="Enter income nature"></td>
							<td><input type="number" name="income[value][<?php echo $in['id'];?>]" class="form-control input-sm" value="<?php echo $in['value'];?>" placeholder="Enter Value "></td>
						</tr>	
				<?php endforeach;
			}				
			?>
				
					
		</table>
		<table class="well table-condensed table-no-bordered" width="100%">
			<tr>
				<td></td>
				<td>TOTAL </td>
				<td><?php echo number_format($sumIncome,2);?></td>
				
			</tr>
		</table>
		<input type="button" id="addincome" class="btn btn-sm" value="Add Income">
	</div>
	<div class="col-md-6">
		<h4>MONTHLY EXPENSES</h4>
		<hr/>
		<table id='expenses' class="table-condensed table-no-bordered well" width="100%">
			<tr>
				<th></th>
				<th>Nature</th>
				<th>Value</th>
			</tr> <?php
			if(isset($expense)){				
				foreach($expense as $in) : ?>						
						<tr>
							<td><input type="hidden" name="remove[<?php echo $in['id'];?>]" value="0"><input type="checkbox" name="remove[<?php echo $in['id'];?>]" value="1">
							<input type="hidden" name="expense[id][]" value="<?php echo $in['id'];?>"></td>
							<td><input type="text" name="expense[nature][<?php echo $in['id'];?>]" class="form-control input-sm" value="<?php echo $in['nature'];?>" placeholder="Enter income nature"></td>
							<td><input type="number" name="expense[value][<?php echo $in['id'];?>]" class="form-control input-sm" value="<?php echo $in['value'];?>" placeholder="Enter Value "></td>
						</tr>	
				<?php endforeach;
			}
			?>
						
		</table>
		<table class="well table-condensed table-no-bordered" width="100%">
			<tr>
				<td></td>
				<td>TOTAL </td>
				<td><?php echo number_format($sumExpense,2);?></td>
				
			</tr>
		</table>
	<input type="button" id="addexpenses" class="btn btn-sm" value="Add Expenses">
	</div>
</div>
   
   <div class="panel-footer">
    	<?php if(isset($clientid)){ ?>
        <input type="hidden" name="clientid" value="<?php echo $clientid;?>">
        <?php } ?>
	<input type="submit" class="btn btn-primary btn-lg btn-block " value="Save Financial Info">
	</div>
</form>


