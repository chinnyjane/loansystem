<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<?php
$config['base_url'] = base_url()."cash/collections/";				
$config['per_page'] = 10;
$col = $this->Cashmodel->getTransof($branch,'adjustment',NULL,NULL);
$config['total_rows'] = $col->num_rows();
$config['uri_segment'] = 3;
$this->pagination->initialize($config);
$segment = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
$module = $this->Cashmodel->getTransof($branch,'adjustment',$segment,$config['per_page']);
$tmpl = array ('table_open' => '<table class="table table-bordered table-hover">' );
$this->table->set_template($tmpl);

?>
<div class="panel panel-warning"><div class="panel-heading"><b>ADJUSTMENTS</b>&nbsp; </div>
<div class="panel-body">
<button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#adjust">Add New Adjustments</button>
</div>
	<div class="table-responsive">
	<?php	
	if($module->num_rows() > 0){ 
	$count = $segment +1;
	foreach($module->result() as $m){
		if($m->Amount_IN > 0) $amount = $m->Amount_IN;
		else if($m->Amount_OUT > 0) $amount = -1 * $m->Amount_OUT; 
		$this->table->add_row($count, date('m-d-Y', strtotime($m->dateOfTransaction)),$m->bankCode,$m->referenceNo, $m->PN, $m->transType, number_format($amount,2) );
		$count++;
	}
	$this->table->set_heading("#", "Date","Bank","JV No", "PN No", "Adjustment Type", "Amount");
	echo $this->table->generate();
	}else{
		echo "<div class='panel-body'>No Adjustments yet.</div>";
	}
	?>
	</div>

<div class="panel-footer">
<?php echo $this->pagination->create_links(); ?>
</div>
</div>

<div class="modal fade" id="adjust" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
  <?php 
	if($this->cash->cmcstatus($branch, $this->auth->localdate()) == true){
	$this->load->view('cash/forms/adjustment');
	}else { 
	$this->load->view('cash/forms/closed');
	} ?>
 </div>
</div>