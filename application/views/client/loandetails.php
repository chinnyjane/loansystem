<?php 
$clientid = $this->uri->segment(3);
$loanid = $this->uri->segment(5);
$client = $this->Clientmgmt->getclientinfoByID($clientid);
$p['client']= $client;
if($client->num_rows() > 0){

foreach($client->result() as $c){
	$p['firstname'] = $c->firstName;
	$p['mname'] = $c->MiddleName;
	$p['lname'] = $c->LastName;
	$p['dob'] = $c->dateOfBirth;
	$p['city'] = $c->city;
	$p['address']=$c->address;
	$p['contact'] = $c->contact;
	$p['civilstatus'] = $c->civilStatus;
	$p['city'] = $c->cityname;
	$p['cityid'] = $c->city;
	$p['provid'] = $c->province;
	$p['barangay'] = $c->barangay;
	$p['address'] = $c->address;
	$p['gender'] = $c->gender;
	$p['age'] = $this->loansetup->get_age($p['dob']);
	if($c->dateOfBirth == '0000-00-00')
	$p['alert'] = "Please update client's birthday.";
}
$tmpl = array ('table_open'  => '<table class="table  table-condensed table-bordered ">');
	$this->table->set_template($tmpl);
	
	
$loans = $this->Loansmodel->getLoanbyID($loanid);

if($loans->num_rows() > 0){
	foreach($loans->result() as $loan){
		$loantype = $loan->LoanName;
		$pn = $loan->PNno;
		$amount = $loan->AmountApplied;
		$terms = $loan->Term." mos" ;
		$monthy = number_format($amount/$terms,2);
		$applied = $loan->dateApplied;
		$agent = $this->UserMgmt->get_user_byid($loan->LoanProcessor);
		if($agent->num_rows() > 0 ){
		$a = $agent->row();
		$ag= $a->lastname.", ".$a->firstname;
		}
	}

?>
<div class="panel panel-success">
<div class="panel-heading"><b>Client Name : <a href="<?php echo base_url();?>client/profile/<?php echo $clientid;?>"><?php echo $p['firstname']." ".$p['lname'];?></a></b><span class="navbar-right">Status: <?php echo $loan->status;?></span></div>
<div class="panel-body">
	
	<ul class="nav nav-tabs" role="tablist" id="myTab">
	  <li class="active"><a href="#details" role="tab" data-toggle="tab">Loan Details</a></li>
	  <li><a href="#requirements" role="tab" data-toggle="tab">Requirements</a></li>
	  <li><a href="#CI" role="tab" data-toggle="tab">Credit Investigation</a></li>
	</ul>

	<div class="tab-content">
	  <div class="tab-pane active" id="details"><div class=" well panel panel-default">
	  <div class="panel-body ">
			<div class="col-md-3">
				<div class="form-group">
					<label>Type of Loan</label>
					<input type="text" class="input form-control" value="<?php echo $loan->LoanName;?>" readonly>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Amount Applied</label>
					<div class="input-group">
						<span class="input-group-addon">Php</span>
					<input type="text" class="input form-control" value="<?php echo number_format($loan->AmountApplied,2);?>" readonly>
				</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Terms</label>
					<div class="input-group">						
					<input type="text" class="input form-control" value="<?php echo $loan->Term;?>" readonly>
					<span class="input-group-addon">Months</span>
				</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Date Applied</label>
					<input type="text" class="input form-control" value="<?php echo date("F d, Y h:i A", strtotime($loan->dateApplied));?>" readonly>
				</div>
			</div>
			
		</div>
		<?php $this->load->view('loans/pensioninfo', $p);?>
	  </div>
	  </div>
	  <div class="tab-pane" id="requirements"><div class="well"><h4>Requirements</h4></div></div>
	  <div class="tab-pane" id="CI"><div class="well"><h4>Credit Investigation Report</h4></div></div>	 
	</div>

<script>
  $(function () {
    $('#myTab a:last').tab('show')
  })
</script>
</div>
<div class="panel-body">
	<button class="btn btn-success"><li class="fa fa-check"></li> Approve Loan</button> &nbsp; <button class="btn btn-danger"><li class="fa fa-times"></li> Decline Loan</button>  &nbsp; <button class="btn btn-warning"><li class="fa fa-unlink"></li> Cancel Loan</button> &nbsp; <button class="btn btn-primary"><li class="fa fa-money"></li> Release Loan</button>
</div>
</div>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/modaljs.js"></script>
<script src="<?php echo base_url();?>assets/js/loansprocess.js" type="text/javascript"></script>	
<?php
	}
 }else {
	echo "<h3>Client Info not found.</h3>";
} ?>