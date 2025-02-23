<?php

if(isset( $_POST['branch']))
$branch = $_POST['branch'];
else
$branch =  $this->auth->branch_id();

$config['base_url'] = base_url()."cash/collections/";				
$config['per_page'] = 10;
$module = $this->Cashmodel->getTransof($branch,'disbursement',NULL,NULL);
//$config['total_rows'] = $col->num_rows();
$config['uri_segment'] = 3;
$this->pagination->initialize($config);
$segment = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
//$module = $this->Cashmodel->getTransof($branch,'disbursement',$segment,$config['per_page']);
$tmpl = array ('table_open' => '<table class="table table-bordered table-hover" id="tableuser">' );
$this->table->set_template($tmpl);
?>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<div class="panel panel-danger"><div class="panel-heading">Disbursements&nbsp; </div>
    <div class="panel-body">
    <form action="" method="post">
    	<div class="col-md-3">        
        <select name="branch" class="input-sm form-control">
        <option>Select Branch</option>
        	<?php 
			$br = $this->UserMgmt->get_branches();
			foreach($br->result() as $b){
				echo '<option value="'.$b->id.'">'.$b->branchname.'</option>';
			}
			?>
        </select>        
        </div>
        <div class="col-md-2">
        <button class="btn btn-sm btn-success">Search</button>
        </div>
       </form>
    </div>
	<div class="table-responsive">
	<?php	
	if($module->num_rows() > 0){ 
	$count = $segment +1;
	foreach($module->result() as $m){
		if($m->Amount_OUT > 0)
			$amt = number_format($m->Amount_OUT,2);
		else
			$amt = '-';
		$this->table->add_row($count, $m->branchname, date('m-d-Y', strtotime($m->dateOfTransaction)), $m->transType, $m->bankCode,$m->referenceNo, $m->Checkno, $m->PN,$m->Particulars, $amt, $m->explanation );
		$count++;
	}
	$this->table->set_heading("#", "Branch", "Date", "Type", "Bank","CV No","Check No", "PN No","Payee","Amount","Explanation");
	echo $this->table->generate();
	}else{
		echo "<div class='panel-body'>No Disbursement yet.</div>";
	}
	?>
	</div>
<div class="panel-footer">
<?php //echo $this->pagination->create_links(); ?>
</div>
</div>
<!-- DISBURSEMENTS -->
<div class="modal fade" id="disburse" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
	<?php 
	if($this->cash->cmcstatus($branch, $this->auth->localdate()) == true){
	//$this->load->view('cash/forms/disbursements');
	}else { 
	//$this->load->view('cash/forms/closed');
	}
	?>
  </div>
</div>
<script>
    $(document).ready(function() {
        $('#tableuser').dataTable({
			"dom": 'T<"clear">lfrtip',
			"tableTools": {
				"sSwfPath": "/swf/copy_csv_xls_pdf.swf"
			}
		});
    });
   </script>