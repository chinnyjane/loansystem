<?php
$clientid = $this->uri->segment(3);
?>
<form action="<?php echo base_url();?>client/profile/updateinfo" method="post" id="clientinfoform">
<?php if (isset($alert)){ ?>
<div class="alert alert-danger alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
  <?php echo $alert;?>
</div>
<?php } ?>
<div class="panel-group" id="accordion">
  <div class="panel panel-success">
    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#personal">      
        <span data-toggle="collapse" data-parent="#accordion" href="#personal">
			<b>Personal Information</b>
        </span>
    </div>
    <div id="personal" class="panel-collapse collapse in">
      <div class="panel-body">
			<div class="row form-group">
				<div class="col-sm-4"><label>First Name</label> <input type="text" class="form-control input-xs" placeholder="First name" name="firstname" value="<?php echo set_value('firstname',$firstname);?>" required> </div>
				<div class="col-sm-4"><label>Middle Name</label> <input type="text" class="form-control input-xs" placeholder="Middle Name"  name="mname" value="<?php echo set_value('mname',$mname);?>" required></div>
				<div class="col-sm-4"><label>Last Name</label> <input type="text" class="form-control input-xs" placeholder="Last Name"  name="lname" value="<?php echo set_value('lname',$lname);?>"  required></div>
			</div>
			<div class="row form-group">
			  <div class="col-sm-4"><label>Date of Birth</label> <input type="text" id="bdate" class="form-control input-xs"  placeholder="yyyy-mm-dd" name="bdate" value="<?php echo set_value('bdate',$dob);?>" required>
                <script>
				$(function() {
					var datepick = $( "#bdate" ).datepicker({format: 'yyyy-mm-dd',
					changeMonth: true,
					changeYear: true,
					weekStart: 1,
					viewMode: 2,
					minViewMode: 0
					}).on('changeDate', function(ev) {
						//datepick.hide();
					}).data('datepicker');				
				  });
			  </script> </div>
				  <div class="col-sm-4"><label>Contact Number</label><input type="text" class="form-control input-sm" id="contact" placeholder="Contact Number" name="contact" value="<?php echo set_value('contact',$contact);?>"  required> </div>
				  <div class="col-sm-4"><label>Civil Status</label>
            <div class="pure-control-group">
                 <select name="civilstatus" id="civilstatus" class="form-control input-sm" value="<?php echo set_value('firstname',$civilstatus);?>">
					<option disabled >Civil Status</option>
					<option value="single" <?php if($civilstatus == 'single') echo 'selected';?>>Single</option>
					<option value="married" <?php if($civilstatus == 'married') echo 'selected';?>>Married</option>
					<option value="widow" <?php if($civilstatus == 'widow') echo 'selected';?>>Window/Widower</option>
					<option value="separated" <?php if($civilstatus == 'separated') echo 'selected';?>>Separated</option>
				</select>
               </div></div>
			</div>
						
			<div class="row form-group">
			 <div class="col-sm-4"><label>Province</label> 
			<select name="province" id="province" class="form-control input-sm">
			<option disabled >Select Province</option>
			 <?php $province = $this->Loansmodel->get_province();
			 	foreach($province->result() as $pro){
					if ($pro->id == $provid)
						$select = true;
					else
						$select = false;
					echo "<option value='".$pro->id."'".set_select('province', $provid, $select).">".$pro->name."</option>";
				}	
				?>
				</select>
			 </div>
			<div class="col-sm-4"><label>City</label>                <select name="city"  class="form-control input-sm" id="city">
			<?php $cities = $this->Loansmodel->get_cities_by_prov($provid);
				foreach($cities->result() as $c){
					if ($c->id == $cityid)
						$select = true;
					else
						$select = false;
					echo "<option value='".$c->id."'".set_select('city', $cityid, $select).">".$c->name."</option>";
				} ?>
			</select></div>
			 <div class="col-sm-4"><label>Barangay</label><input type="text" class="form-control input-sm" placeholder="Barangay" name="brgy"   value="<?php echo set_value('brgy',$barangay);?>" required></div>
			</div>	
			<div class="row form-group"> 
			<div class="col-sm-8">
			<label>House #, Street</label><input type="text" class="form-control input-sm" placeholder="House No., Street, Barangay" name="address" value="<?php echo set_value('address',$address);?>"  required>
			</div>
			<div class="col-sm-4">
			<label>Gender</label><select name="gender" class="form-control input-sm"><option value="F" <?php 
			if($gender == "F") $s = true; else $s = false;
			echo set_select('gender', $gender, $s); ?>>Female</option><option value="M" <?php 
			if($gender == "M") $s = true; else $s = false;
			echo set_select('gender', $gender, $s); ?>>Male</option></select>
			  </div>
			  </div>
			 
			  
			</div>	
    </div>
   </div>
   <div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#spouse">      
      	<b>Spouse Information</b>
    </div>
    <div id="spouse" class="panel-collapse collapse">
		<div class="panel-body">
			
			<label>Spouse Name</label>
			<div class="row form-group">
				  <div class="col-sm-4"><input type="text" class="form-control input-sm" placeholder="First name" name="spfirstname" id="spfirstname" > </div>
				  <div class="col-sm-4"><input type="text" class="form-control input-sm" placeholder="Middle Name"  name="spmname" id="spmname"></div>
				  <div class="col-sm-4"><input type="text" class="form-control input-sm" placeholder="Last Name" name="splname"  id="splname"></div>
				</div>
			
			<label>Occupation</label>
			<div class="row form-group">
				  <div class="col-sm-4"><input type="text" class="form-control input-sm" placeholder="Occupation" name="spwork" id="spwork" > </div>
				  <div class="col-sm-4"><input type="text" class="form-control input-sm" placeholder="Company"  name="spcompany"  id="spcompany" ></div>
				  <div class="col-sm-4"><input type="text" class="form-control input-sm" placeholder="Salary" name="spsalary" id="spsalary"  ></div>
				</div>
			
			 
			 <div class="row form-group">
				<div class="col-sm-4"><label>Contact Number</label><input type="text" class="form-control input-sm" placeholder="contact number" name="spcontact" id="spcontact" ></div>	
				<div class="col-sm-4"><label>Date of Birth</label> <input type="text" id="spbdate"  class="form-control input-sm"  placeholder="mm-dd-yyyy" name="spbdate" id="spbdate" >
                <script>
				 $(function() {
					var datepick = $( "#spbdate" ).datepicker({format: 'yyyy-mm-dd',
					changeMonth: true,
					changeYear: true
					}).on('changeDate', function(ev) {
						datepick.hide();
					}).data('datepicker');				
				  });
			  </script> </div>
			 
           </div>
		   </div>	
  </div>
 </div>
 
 <div class="panel panel-default">
    <div class="panel-heading"  data-toggle="collapse" data-parent="#accordion" href="#dependents">      
     	<b>Dependents</b>
    </div>
    <div id="dependents" class="panel-collapse collapse">
		<div class="panel-body">
			 <?php $dep = 1;
			 while ($dep <= 5){ ?>
				<div class="row form-group">
				<div class="col-sm-1"><input type="text" class="form-control input-sm" placeholder="<?php echo $dep;?>" readonly></div>
				<div class="col-sm-3"><input type="text" class="form-control input-sm" placeholder="First Name" name="dep[<?php echo $dep;?>][fname]" id="spcontact" ></div>
				<div class="col-sm-3"><input type="text" class="form-control input-sm" placeholder="Middle Name" name="dep[<?php echo $dep;?>][mname]" id="spcontact" ></div>
				<div class="col-sm-3"><input type="text" class="form-control input-sm" placeholder="Last Name" name="dep[<?php echo $dep;?>][lname]" id="spcontact" ></div>
				<div class="col-sm-2"><input type="text" class="form-control input-sm" placeholder="contact number" name="dep[<?php echo $dep;?>][bday]" id="spcontact" ></div>
				</div>
			 <?php 
			 $dep++;
			 } ?>
		   </div>	
  </div>
 </div>
 
 <div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#obligations">      
   		<b>Outstanding Obligations</b>
    </div>
    <div id="obligations" class="panel-collapse collapse">
		<div class="panel-body">
			
		<div class="row form-group">
				<div class="col-sm-3"><input type="text" class="form-control input-sm" placeholder="Name of Creditor" name="c[0][name]" id="spcontact" ></div>
				<div class="col-sm-3"><input type="text" class="form-control input-sm" placeholder="Address" name="c[0][address]" id="spcontact" ></div>
				<div class="col-sm-3"><input type="text" class="form-control input-sm" placeholder="Amount" name="c[0][amount]" id="spcontact" ></div>
				<div class="col-sm-3"><input type="text" class="form-control input-sm" placeholder="Remarks" name="c[0][remarks]" id="spcontact" ></div>
				</div>
			<div class="row form-group">
				<div class="col-sm-3"><input type="text" class="form-control input-sm" placeholder="Name of Creditor" name="c[1][name]" id="spcontact" ></div>
				<div class="col-sm-3"><input type="text" class="form-control input-sm" placeholder="Address" name="c[1][address]" id="spcontact" ></div>
				<div class="col-sm-3"><input type="text" class="form-control input-sm" placeholder="Amount" name="c[1][amount]" id="spcontact" ></div>
				<div class="col-sm-3"><input type="text" class="form-control input-sm" placeholder="Remarks" name="c[1][remarks]" id="spcontact" ></div>
				</div>
		   </div>	
  </div>
 </div>

 <div class="panel-body">
 <input type="hidden" name="client" value="<?php echo $clientid;?>">
 <input class="btn btn-primary btn-sm" id="saveinfo" value="Save Client Info" type="submit" disabled> &nbsp; &nbsp; <a class="btn btn-success btn-sm">Print Client Information</a>
 </div>
 </div>
 </form>
 	
