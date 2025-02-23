<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
 <?php if($_POST){
	echo "<pre>";
		print_r($_POST);
		echo "</pre>"; }
$client = $this->loansetup->clientid();
if($client){
	$where = array("clientID"=>$this->loansetup->clientid());
	$applicant = $this->Loansmodel->get_data_from("clientinfo", $where);
	foreach($applicant->result() as $app){
		$fname = $app->firstName;
		$mname = $app->MiddleName;
		$lname = $app->LastName;
		$bdate = $app->dateOfBirth;
	}
}else{
$fname = "";
$mname = "";
$lname = "";
$bdate = "";
}
		?>
<form class="form-horizontal" method="post" action="">
	<div class="pure-div">
	<div class="panel panel-success"><div class="panel-heading">Personal Information</div>
		<div class="panel-body">
			<label>Applicant Name *</label>
			<div class="row form-group">
				<div class="col-md-4"><input type="text" class="form-control input-sm" placeholder="First name" name="firstname" value="<?php echo set_value("firstname", $fname);?>" required> </div>
				<div class="col-md-4"><input type="text" class="form-control input-sm" placeholder="Middle Name"  value="<?php echo set_value("mname", $mname);?>" name="mname" required></div>
				<div class="col-md-4"><input type="text" class="form-control input-sm" placeholder="Last Name" value="<?php echo set_value("lname", $lname);?>" name="lname"  required></div>
			</div>
			<div class="row form-group">
			  <div class="col-md-4"><label>Date of Birth</label> <input type="text" id="bdate" class="form-control input-sm"  placeholder="mm-dd-yyyy" name="bdate" value="<?php echo set_value("bdate", $bdate);?>" required>
                <script>
				  $(function() {
					$( "#bdate" ).datepicker({format: 'mm-dd-yyyy',
					changeMonth: true,
					changeYear: true
					});
				  });
			  </script> </div>
				  <div class="col-md-4"><label>Contact Number</label><input type="text" class="form-control input-sm" id="contact" placeholder="Contact Number" name="contact"  required> </div>
				  <div class="col-md-4"><label>Civil Status</label>
            <div class="pure-control-group">
                 <select name="civilstatus" id="civilstatus" class="form-control input-sm">
					<option value="single">Single</option>
					<option value="married">Married</option>
					<option value="widow">Window/Widower</option>
					<option value="separated">Separated</option>
				</select>
                <script>
					$(function() {
						$('#civilstatus').change();
					});
				</script>
            </div></div>
			</div>
						
			<div class="row form-group">
			 <div class="col-md-4"><label>Residence Address</label><input type="text" class="form-control input-sm" placeholder="House No., Street, Barangay" name="address"  required></div>
			<div class="col-md-4"><label>City</label><?php $cities = $this->Loansmodel->get_cities(); ?>
                <select name="City"  class="form-control input-sm">
				<?php	foreach ($cities->result() as $c){
					//echo $c->name.", ".$c->name; ?><option value="<?php echo $c->id?>"><?php echo $c->name.", ".$c->prov."<br/>";?></option>
					<?php };?>
				</select></div>
			 <div class="col-md-4"><label>Gender</label><select name="gender" class="form-control input-sm"><option value="F">Female</option><option value="Male">Male</option></select></div>
			</div>	
			</div>			
			</div>
			
			<div class="panel panel-success"><div class="panel-heading">Spouse Information</div>
		     <div class="panel-body">
			
			<label>Spouse Name</label>
			<div class="row form-group">
				  <div class="col-md-4"><input type="text" class="form-control input-sm" placeholder="First name" name="spfirstname" > </div>
				  <div class="col-md-4"><input type="text" class="form-control input-sm" placeholder="Middle Name"  name="spmname" ></div>
				  <div class="col-md-4"><input type="text" class="form-control input-sm" placeholder="Last Name" name="splname"  ></div>
				</div>
			
			<label>Occupation</label>
			<div class="row form-group">
				  <div class="col-md-4"><input type="text" class="form-control input-sm" placeholder="Occupation" name="spwork" > </div>
				  <div class="col-md-4"><input type="text" class="form-control input-sm" placeholder="Company"  name="spcompany" ></div>
				  <div class="col-md-4"><input type="text" class="form-control input-sm" placeholder="Salary" name="spsalary"  ></div>
				</div>
			
			 
			 <div class="row form-group">
				<div class="col-md-4"><label>Contact Number</label><input type="text" class="form-control input-sm" placeholder="contact number" name="spcontact" ></div>	
				<div class="col-md-4"><label>Date of Birth</label> <input type="text" id="spbdate"  class="form-control input-sm"  placeholder="mm-dd-yyyy" name="spbdate" >
                <script>
				  $(function() {
					$( "#spbdate" ).datepicker({Format: 'mm-dd-yy',
					changeMonth: true,
					changeYear: true
					});
				  });
			  </script> </div>
           </div>
		   </div>	
		</div>	
		
		<div style="clear:both"></div>
		<div style="margin: 20px; " >
		<input type="hidden" name="submit" value="PersonalInfo">
		<button type="submit" class="btn btn-primary">Submit Client Info</button>
		</div>
		
	</form>
</div>
</div>
 