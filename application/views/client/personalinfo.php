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
		$_POST['id_presented']=$c->id_presented;
		
		if($c->lock == 1) $lock = 'readonly';
		else $lock = '';
		
		if($c->civilStatus != 'single'){
			if($spouse->num_rows() > 0){
				$spouseinfo = $spouse->row();
				$_POST['spfirstname'] = $spouseinfo->firstname;
				$_POST['spmname'] = $spouseinfo->middlename;
				$_POST['splname'] = $spouseinfo->lastname;
				$_POST['spbdate'] = $spouseinfo->dateOfBirth;
				$_POST['spcompany'] = $spouseinfo->companyname;
				$_POST['spcontact'] = $spouseinfo->contact;
				$_POST['spwork'] = $spouseinfo->occupation;
				$_POST['spsalary'] = $spouseinfo->salary;				
			}

		}else{
			$disabled = 'disabled';
		}

		$clientid = $this->uri->segment(3);
		if ($c->image!='')
		$image = base_url().$c->image;
		else
		$image = 'holder.js/100%x180';

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
	}else{
		$image = NULL;
	}



}else{
		$image = NULL;
}

$tmpl = array ('table_open' => '<table class="table table-condensed tablesort" >',
		'thead_open' => '<thead class="header">'	);
$this->table->set_template($tmpl);
?>
<h4>PERSONAL INFORMATION</h4>
<hr/>
<form id="personal" method="post" action="<?php echo base_url();?>client/overview/clientvalidation" class="formpost">
    <table class="table-condensed table-no-bordered" width="100%">
	<?php if($image != NULL) { ?>
    	<tr>
        	<td align="center" ><img src="<?php echo $image;?>" width="250px"><br/><a href="#imageupload"  data-toggle="modal" class="thumbnail">Change Photo</a></td>
        </tr>
        <tr>
       <?php } ?>
        	<td valign="top">Client Name:<br/><input type="text" class="form-control input-sm" placeholder="Last Name"  name="lname" value="<?php echo set_value('lname',$this->input->post('lname'));?>"  required <?php echo $lock;?>></td>
            <td valign="top"><br/><input type="text" class="form-control input-sm" placeholder="First name" name="firstname" id="firstname" value="<?php echo set_value('firstname',$this->input->post('firstname'));?>" required <?php echo $lock;?>></td>
            <td valign="top"><br/><input type="text" class="form-control input-sm" placeholder="Middle Name"  name="mname" value="<?php echo set_value('mname',$this->input->post('mname'));?>" required <?php echo $lock;?>></td>
			<td>Age:<br/><input type="text" name="age" class="input-sm form-control" value="<?php echo $this->loansetup->get_age($this->input->post('bdate')); ?> yrs. old." readonly></td>
        </tr>
        <tr>
            <td>Date of Birth:<br/>
            <input type="date" name="bdate" value="<?php echo set_value('bdate', $this->input->post('bdate') );?>" class="input-sm form-control" <?php echo $lock;?>>
            </td>
            <td>Contact number:<br/>
            <input type="text" class="form-control input-sm" id="contact" placeholder="Contact Number" name="contact" value="<?php echo set_value('contact',  $this->input->post('contact'));?>"  required></td>
            <td>Civil Status: <br/>
            <?php echo $this->form->civilstatus("civilstatus",  $this->input->post('civilstatus'));?></td>
            <td>Gender:<br/>
            <?php echo $this->form->gender("gender", $this->input->post('gender')); ?></td>
        </tr>
        <tr>
        	<td>Province:<br/>
            <?php echo $this->form-> provincefield("province",  $this->input->post('province'));?></td>
            <td>City: <br/>
            <?php echo $this->form->cityfield("city",  $this->input->post('city'),  $this->input->post('province'));?></td>
            <td>Barangay:<br/>
             <input type="text" class="form-control input-sm" placeholder="Barangay" name="brgy"   value="<?php echo set_value('brgy',  $this->input->post('brgy'));?>" required></td>
            <td>House #, Street<br/>
            <input type="text" class="form-control input-sm" placeholder="House No., Street, Barangay" name="address" value="<?php echo set_value('address', $this->input->post('address'));?>"  required></td>
        </tr>
		<tr>
			<td><label>ID Presented</label>
			<input type="text" name="id_presented" class="form-control input-sm" value="<?php echo set_value('id_presented',  $this->input->post('id_presented'));?>">
			</td>
		</tr>
    </table>
<h4>SPOUSE INFORMATION</h4>
<hr/>
 <table class="table-condensed table-no-bordered" width="100%">
 	<tr>
    	<td>Spouse Name<br/>
        	<input type="text" class="form-control input-sm" placeholder="Last Name" name="splname"  id="splname"  value="<?php echo set_value('splname',  $this->input->post('splname'));?>" <?php echo $disabled;?>>
        </td>
        <td><br/>
        	<input type="text" class="form-control input-sm" placeholder="First name" name="spfirstname" id="spfirstname" value="<?php echo set_value('spfirstname',  $this->input->post('spfirstname'));?>" <?php echo $disabled;?> >
        </td>
        <td><br/>
        	<input type="text" class="form-control input-sm" placeholder="Middle Name"  name="spmname" id="spmname"   value="<?php echo set_value('spmname',  $this->input->post('spmname'));?>" <?php echo $disabled;?>></td>
        <td>Date of Birth: <br/><input type="date" name="spbdate" id="spbdate" placeholder="mm-dd-yyyy" class="form-control input-sm "  value="<?php echo set_value('spbdate',  $this->input->post('spbdate'));?>" <?php echo $disabled;?> ></td>
    </tr>
    <tr>
    	<td>Occupation:<br/>
        	<input type="text" class="form-control input-sm" placeholder="Occupation" name="spwork" id="spwork"  value="<?php echo set_value('spwork',  $this->input->post('spwork'));?>" <?php echo $disabled;?>>
        </td>
        <td><br/><input type="text" class="form-control input-sm" placeholder="Company"  name="spcompany"  id="spcompany"  value="<?php echo set_value('spcompany',  $this->input->post('spcompany'));?>" <?php echo $disabled;?>></td>
        <td>Monthly Salary:<br/><input type="text" class="form-control input-sm" placeholder="Salary" name="spsalary" id="spsalary"  value="<?php echo set_value('spsalary',  $this->input->post('spsalary'));?>" <?php echo $disabled;?>> </td>
        <td>Contact No.<br/><input type="text" class="form-control input-sm" placeholder="contact number" name="spcontact" id="spcontact"  value="<?php echo set_value('spcontact',  $this->input->post('spcontact'));?>" <?php echo $disabled;?>></td>

    </tr>
 </table>

<h4>Dependents Information <a data-toggle="modal" data-target="#depinfo" href="#"><i class="fa fa-pencil-square-o"></i> </a></h4>
<hr/>
<div class="panel-collapse ">
	<?php
	if(isset($dependents)){
		if($dependents->num_rows() >0){
		  $this->table->set_heading("Name", "Date of Birth", "Age");
		  foreach ($dependents->result() as $dep){
			  $this->table->add_row($dep->firstname." ".$dep->middlename." ".$dep->lastname, date("F d, Y", strtotime($dep->dateOfBirth)), $this->loansetup->get_age($dep->dateOfBirth));
		  }
		  echo $this->table->generate();
		}else{
		  echo "No dependents.";
		}
	}
    ?>
</div>
  <h4>Outstanding Obligations <a data-toggle="modal" data-target="#creditor" href="#" ><i class="fa fa-pencil-square-o"></i> </a></h4>
  <hr/>
  <div id="obligations" class="panel-collapse ">
	<?php
	if(isset($creditor)){
		if($creditor->num_rows() >0){
		  $this->table->set_heading("Name", "Address", "Amount", "Remarks");
		  foreach($creditor->result() as $cre){
			  $this->table->add_row($cre->name, $cre->address, $cre->amount, $cre->remarks);
		  }
		  echo $this->table->generate();
		}else{
		  echo "No Outstanding Obligations";
		}
	}	?>
  </div>
<hr/>
<?php if(isset($clientid)){ ?>
	<input type="hidden" name="clientid" value="<?php echo $clientid;?>">
<?php }
if($this->auth->perms('Profile',$this->auth->user_id(),4) == true) { 
 ?>
<select name="active" class="input-sm"><option value="1">Active</option><option value="0">Inactive</option></select>
&nbsp; <input type="checkbox" name="lock" id="lock" value="<?php echo $c->lock;?>" class="btn-sm" <?php if($c->lock == 1) echo checked; ?> data-label-text="Lock"> 
<?php	
	
 } ?>


	<input type="submit" class="btn btn-primary" value="Save Client Profile">
</form>


<!-- MODAL Dependents -->
<div class="modal fade" id="depinfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<?php
$mid = 'depinfo';
$form = 'Update Dependents';
$url = 'client/profile/updateinfo';
echo $this->form->modalformopen($mid, $url, $form);  ?>
<div >
	<div class="row form-group">
		<div class="col-md-3"><input type="button" id="adddep" class="btn btn-sm" value="Add Dependent"></div>
	</div>
<?php
echo '<table class="table table-condensed"  id="dependents">';
		echo '<tr>';
		echo '<th>Remove</th>';
		echo '<th>Firstname</th>';
		echo '<th>Middle name</th>';
		echo '<th>Lastname</th>';
		echo '<th>Date of Birth</th>';
		echo '</tr>';
if(isset($dependents)){
	if($dependents->num_rows() >0){
		
		foreach ($dependents->result() as $dep) : ?>
			<tr>
			<td><input type="checkbox" class="input-sm" name="depid[]" value="<?php echo $dep->depID;?>" ></td>
			<td><input type="text" class="form-control input-sm" placeholder="First Name" name="dfname[<?php echo $dep->depID;?>]" value="<?php echo $dep->firstname;?>" ></td>
			<td><input type="text" class="form-control input-sm" placeholder="Middle Name" name="dmname[<?php echo $dep->depID;?>]" value="<?php echo $dep->middlename;?>"></td>
			<td><input type="text" class="form-control input-sm" placeholder="Last Name" name="dlname[<?php echo $dep->depID;?>]" value="<?php echo $dep->lastname;?>"></td>
			<td><?php echo $this->form-> datefield("dbday[".$dep->depID."]", $dep->dateOfBirth);?></td>
			</tr>
		<?php endforeach;
		
	}
}
echo '</table>';
 ?>

</div>
<?php $footer = '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button> &nbsp;'
			.'<input type="hidden" name="client" value="'.$clientid.'">'
			.'<input type="hidden" name="info" value="dependents">'
			.'<input class="btn btn-primary btn-sm" value="Save Dependents" type="submit" >';
echo $this->form->modalformclose($footer);
?>
<!-- MODAL Credit -->
<div class="modal fade" id="creditor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<?php
$mid = 'creditinfo';
$form = 'Update Outstanding Obligations';
$url = 'client/profile/updateinfo';
echo $this->form->modalformopen($mid, $url, $form);  ?>
<div  id="credit">
	<div class="row form-group">
		<div class="col-md-3"><input type="button" id="addcreditor" class="btn btn-sm" value="Add Creditor"></div>
	</div>
<table class="table-condensed table-no-bordered " id="credit" width="100%">
	<thead>
	<tr>
		<th>Remove</th>
		<th>Name of Creditor</th>
		<th>Address</th>
		<th>Amount</th>
		<th>Remarks</th>
	</tr>
	</thead>
<?php
if(isset($creditor)){
	if($creditor->num_rows() >0){
		foreach ($creditor->result() as $credit): ?>
			<tr>
				<td><input type="checkbox" class="input-sm" name="crid[]" value="<?php echo $credit->creditID;?>" ></td>
				<td><input type="text" class="form-control input-sm" placeholder="Name of Creditor" name="credtor[<?php echo $credit->creditID;?>]" id="spcontact" value="<?php echo $credit->name;?>" ></td>
				<td><input type="text" class="form-control input-sm" placeholder="Address" name="credadd[<?php echo $credit->creditID;?>]" id="spcontact" value="<?php echo $credit->address;?>"></td>
				<td><input type="text" class="form-control input-sm" placeholder="Amount" name="credamount[<?php echo $credit->creditID;?>]" id="spcontact" value="<?php echo $credit->amount;?>"></td>
				<td><input type="text" class="form-control input-sm" placeholder="Remarks" name="credremarks[<?php echo $credit->creditID;?>]" id="spcontact" value="<?php echo $credit->remarks;?>"></td>
			</tr>
			
		<?php endforeach;
	}
}
?>
</table>
</div>
<?php $footer = '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button> &nbsp;'
			.'<input type="hidden" name="client" value="'.$clientid.'">'
			.'<input type="hidden" name="info" value="credit">'
			.'<input class="btn btn-primary btn-sm" value="Save Creditor" type="submit" >';
echo $this->form->modalformclose($footer);
?>

