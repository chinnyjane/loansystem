<h3>Add New Client</h3>
<div class="well">
</div>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/modaljs.js"></script>

 <?php 
 if($_POST){
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
<form class="form-horizontal" id="clientinfo" method="post" action="<?php echo current_url();?>">
<div class="panel panel-default">
	<div class="panel-heading">Client's Name Here</div>
	<div class="row">
		<div class="col-sm-3">
		<!-- CLient Details-->
		<ul class="nav nav-pills nav-stacked " id="myTab">
			<li class="active"><a href="#cliente" data-toggle="tab">Client Information</a></li>
		</ul>
		</div>
		<div class="col-sm-9">
		<div class="tab-content">
			<div class="tab-pane active" id="cliente">
			<!-- PERSONAL INFO-->
			 <?php $this->load->view('client/personalinfo');?>
			</div>		
			<div class="tab-pane" id="spouse">
			<!-- PERSONAL INFO-->
			<?php $this->load->view('client/dependents');?>
			</div>
		</div>		
		</div>		
	 </div>
</div>

</form>	

 