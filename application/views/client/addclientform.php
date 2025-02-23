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

    <table class="table-condensed table-no-bordered" width="100%">
	<?php if($image != NULL) { ?>
    	<tr>
        	<td align="center" ><img src="<?php echo $image;?>" ><br/><a href="#imageupload"  data-toggle="modal" class="thumbnail">Change Photo</a></td>
        </tr>
        <tr>
       <?php } ?>
        	<td valign="top">Client Name:<br/><input type="text" class="form-control input-sm" placeholder="Last Name"  name="lname" value="<?php echo set_value('lname',$this->input->post('lname'));?>"  ></td>
            <td valign="top"><br/><input type="text" class="form-control input-sm" placeholder="First name" name="firstname" id="firstname" value="<?php echo set_value('firstname',$this->input->post('firstname'));?>" ></td>
            <td valign="top"><br/><input type="text" class="form-control input-sm" placeholder="Middle Name"  name="mname" value="<?php echo set_value('mname',$this->input->post('mname'));?>" ></td>
        </tr>
        <tr>
            <td>Date of Birth:<br/>
            <input type="date" name="bdate" value="<?php echo set_value('bdate', $this->input->post('bdate') );?>" class="input-sm form-control">
            </td>
            <td>Contact number:<br/>
            <input type="text" class="form-control input-sm" id="contact" placeholder="Contact Number" name="contact" value="<?php echo set_value('contact',  $this->input->post('contact'));?>"  ></td>
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
             <input type="text" class="form-control input-sm" placeholder="Barangay" name="brgy"   value="<?php echo set_value('brgy',  $this->input->post('brgy'));?>" ></td>
            <td>House #, Street<br/>
            <input type="text" class="form-control input-sm" placeholder="House No., Street, Barangay" name="address" value="<?php echo set_value('address', $this->input->post('address'));?>"  ></td>
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

<?php if(isset($clientid)){ ?>
	<input type="hidden" name="clientid" value="<?php echo $clientid;?>">
<?php } ?>

