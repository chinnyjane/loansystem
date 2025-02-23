<!-- MODAL PERSONAL INFO -->
<div class="modal fade" id="personalinfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<form action="<?php echo base_url();?>client/profile/updateinfo" method="post" class="formpost">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Update Personal Information</h4>
				</div>
				<div class="modal-body">
					<div class="row form-group">
						<div class="col-sm-4"><label>Name: </label>  <?php //echo strtoupper($c->LastName.", ".$c->firstName." ".$c->MiddleName); ?>
							<input type="text" class="form-control input-sm" placeholder="First name" name="firstname" value="<?php echo set_value('firstname',$c->firstName);?>" required>
						</div>
						<div class="col-sm-4"><label>Middle Name</label> 
							<input type="text" class="form-control input-sm" placeholder="Middle Name"  name="mname" value="<?php echo set_value('mname',$c->MiddleName);?>" required>
						</div>
						<div class="col-sm-4"><label>Last Name</label> 
							<input type="text" class="form-control input-sm" placeholder="Last Name"  name="lname" value="<?php echo set_value('lname',$c->LastName);?>"  required>
						</div>
					</div>
					<div class="row form-group">
						<div class="col-sm-4"><label>Date of Birth</label> 
							<?php echo $this->form->datefield("bdate", $c->dateOfBirth);?>
						</div>
						<div class="col-sm-4"><label>Contact Number</label>
							<input type="text" class="form-control input-sm" id="contact" placeholder="Contact Number" name="contact" value="<?php echo set_value('contact',$c->contact);?>"  required>
						</div>
						<div class="col-sm-4"><label>Civil Status</label>
							<?php echo $this->form->civilstatus("civilstatus", $c->civilStatus);?>
						</div>
					</div>
					<div class="row form-group">
						<div class="col-sm-4"><label>Province</label> 
							<?php echo $this->form-> provincefield("province",$c->province);?>
						</div>
						<div class="col-sm-4"><label>City</label>
							<?php echo $this->form->cityfield("city", $c->city, $c->province);?>
						</div>
						<div class="col-sm-4"><label>Barangay</label>
							<input type="text" class="form-control input-sm" placeholder="Barangay" name="brgy"   value="<?php echo set_value('brgy',$c->barangay);?>" required>
						</div>
					</div>	
					<div class="row form-group"> 
						<div class="col-sm-8">
							<label>House #, Street</label>
							<input type="text" class="form-control input-sm" placeholder="House No., Street, Barangay" name="address" value="<?php echo set_value('address',$c->address);?>"  required>
						</div>
						<div class="col-sm-4">
							<label>Gender</label>
							<?php echo $this->form->gender("gender", $c->gender); ?>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
					<input type="hidden" name="client" value="<?php echo $clientid;?>">
					<input class="btn btn-primary btn-sm" value="Save Client Info" type="submit" >
				</div>
			</div>
		</form>
	</div>	
</div>	

<!-- MODAL SPOUSE -->
<div class="modal fade" id="spouseinfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<form action="<?php echo base_url();?>client/profile/updateinfo" method="post" class="formpost">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Update Spouse Information</h4>
				</div>
				<div class="modal-body">	  			
					<label>Spouse Name</label>
					<div class="row form-group">
						<div class="col-sm-4"><input type="text" class="form-control input-sm" placeholder="First name" name="spfirstname" value="<?php echo set_value('spfirstname', $fname);?>" id="spfirstname" > </div>
						<div class="col-sm-4"><input type="text" class="form-control input-sm" placeholder="Middle Name"  name="spmname" value="<?php echo set_value('spmname', $mname);?>" id="spmname"></div>
						<div class="col-sm-4"><input type="text" class="form-control input-sm" placeholder="Last Name" name="splname" value="<?php echo set_value('splname', $lname);?>"  id="splname"></div>
					</div>
					<div class="row form-group">
						<div class="col-sm-4"><label>Occupation</label><input type="text" class="form-control input-sm" placeholder="Occupation" name="spwork" id="spwork" value="<?php echo set_value('spwork', $work);?>"> </div>
						<div class="col-sm-4"><label>Company</label><input type="text" class="form-control input-sm" placeholder="Company"  name="spcompany"  id="spcompany" value="<?php echo set_value('spcompany', $company);?>"></div>
						<div class="col-sm-4"><label>Salary</label><input type="text" class="form-control input-sm" placeholder="Salary" name="spsalary" id="spsalary"  value="<?php echo set_value('spsalary', $salary);?>"></div>
					</div>		 
					<div class="row form-group">
						<div class="col-sm-4"><label>Contact Number</label>
							<input type="text" class="form-control input-sm" placeholder="contact number" name="spcontact" id="spcontact" value="<?php echo set_value('spcontact', $contact);?>" ></div>	
						<div class="col-sm-4"><label>Date of Birth</label> 
							<?php echo $this->form->datefield('spbdate', $bday); ?>
						</div> 
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
					<input type="hidden" name="client" value="<?php echo $clientid;?>">
					<input type="hidden" name="info" value="spouse">
					<input class="btn btn-primary btn-sm" value="Save Spouse Info" type="submit" >
				</div>
		   </div>
		</form>
	</div>
</div>
<!-- MODAL SPOUSE ENDS HERE -->

<!-- MODAL Dependents -->

<?php 
$mid = 'depinfo';
$form = 'Update Dependents';
$url = 'client/profile/updateinfo';
echo $this->form->modalformopen($mid, $url, $form);  ?>
<div  id="dependents">	
	<div class="row form-group">
		<div class="col-md-3"><input type="button" id="adddep" class="btn btn-sm" value="Add Dependent"></div>						
	</div>
	<?php 
			 if($dependents->num_rows() >0){
				//$this->table->set_heading("Name", "Date of Birth", "Age");
				foreach ($dependents->result() as $dep){
					//$this->table->add_row($dep->firstname." ".$dep->middlename." ".$dep->lastname, $dep->dateOfBirth, $this->loansetup->get_age($dep->dateOfBirth));
					?>
					<div class="row form-group">
					<div class="col-sm-3"><input type="text" class="form-control input-sm" placeholder="First Name" name="dfname[<?php echo $dep->depID;?>]" value="<?php echo $dep->firstname;?>" ></div>
					<div class="col-sm-3"><input type="text" class="form-control input-sm" placeholder="Middle Name" name="dmname[<?php echo $dep->depID;?>]" value="<?php echo $dep->middlename;?>"></div>
					<div class="col-sm-3"><input type="text" class="form-control input-sm" placeholder="Last Name" name="dlname[<?php echo $dep->depID;?>]" value="<?php echo $dep->lastname;?>"></div>
					<div class="col-sm-2"><?php echo $this->form-> datefield("dbday[".$dep->depID."]", $dep->dateOfBirth);?></div>
					</div>
					<?php
				}
				//echo $this->table->generate();
			 }else{
				echo "No dependents.";
			}
			 ?>
			
</div>
<?php $footer = '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button> &nbsp;'
			.'<input type="hidden" name="client" value="'.$clientid.'">'
			.'<input type="hidden" name="info" value="dependents">'
			.'<input class="btn btn-primary btn-sm" value="Save Dependents" type="submit" >';
echo $this->form->modalformclose($footer); 
?>
