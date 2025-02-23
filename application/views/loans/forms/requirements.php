<!--<form action="<?php echo base_url();?>loans/application/submitrequirements" method="post" class="formpost">-->
<div id="requirementsform">        
<?php 
$tmpl = array ('table_open'  => '<table class="table table-bordered" id="loanstatustable">');
		$this->table->set_template($tmpl);
if(isset($_POST['submit'])){
	if($_POST['submit'] == "Save Loan Information"){
		if(isset($_POST['reqID'])){
			$reqname = $_POST['reqname'];
			$count = 1;
			$this->table->set_heading("#","Submit", "Description");
			foreach($_POST['reqID'] as $reqid=>$value){ 		
				if($value == "0"){
					$uncheck= "checked";
					$check = "";
				}else{
					$check = "checked";
					$uncheck= "";
				}
				$this->table->add_row($count,"<input type='hidden' name='reqID[".$reqid."]' value='0' ".$uncheck."><input type='checkbox' name='reqID[".$reqid."]' value='1' ".$check."><input type='hidden' name='reqname[".$reqid."]' value='".$reqname[$reqid]."' >" , $reqname[$reqid]);
				$count++;
			}
			echo $this->table->generate();
		}
		
	} 
}?>                        
	
</div>

