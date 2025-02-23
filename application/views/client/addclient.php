<script type="text/javascript" src="<?php echo base_url();?>assets/js/modaljs.js"></script>
<script src="<?php echo base_url();?>assets/js/loansprocess.js" type="text/javascript"></script>	
<?php 
 if($_POST){
	 }
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
<div class="panel panel-green">
	<div class="panel-heading"><b>Add New Client</b></div>
	<form class="formpost" method="post" action="<?php echo current_url();?>">
	<!-- PERSONAL INFO-->
	<?php $this->load->view('client/personalinfo');?>		

	</form>
</div>

