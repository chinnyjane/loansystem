<?php
$config['base_url'] = base_url()."cash/branches";				
$config['per_page'] = 14;
$config['total_rows'] = $this->UserMgmt->get_total_records('branches', '1');
$config['uri_segment'] = 3;
$this->pagination->initialize($config);
$segment = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
$branch = $this->UserMgmt->get_records_from('branches' ,$config['per_page'], $segment, '1');
$tmpl = array ('table_open'  => '<table class="table table-bordered">');
$this->table->set_template($tmpl);
 ?>
 
<div class="panel panel-info"><div class="panel-heading"><b>Branches</b></div>	
<?php if($this->auth->perms('Cash.Branches and Banks',$this->auth->user_id(),1) == TRUE){ ?>
<form class="form_horizontal" method="post">
<div class="panel-body">
	<div class="row form-group">
	<div class="col-md-4"><label>Branch Name</label>
        <input id="branch" name="branch" type="text" class="form-control input-sm" placeholder="Branch Name" required></div>
	<div class="col-md-4"><label>Branch Address</label>
		 <input id="address" name="address" type="text" class="form-control input-sm" placeholder="Address" required></div>
	<div class="col-md-1"><label>&nbsp;</label>
		<input type="submit" class="btn btn-success btn-sm" name="action" value="Add Branch"></div>
	</div>
	</div>	
</form>
<?php 
}
$this->table->set_heading('<input class="check-all" type="checkbox" />','#', 'Branch Name', '# of Bank Accounts');
$count = $segment +1;
foreach ($branch->result() as $br){
	//$total = $this->cash->getTotalBal($br->id);
	$banks = $this->Cashmodel->getbanklistonbranch($br->id);
	$totalbanks = count($banks->result());
	$totalbanks = $totalbanks ? $totalbanks :0;
	$linktoBranch = anchor('cash/branches/details/'.$br->id, $br->branchname);
	$this->table->add_row('<input type="checkbox" name="checked[]"  class="case" value="'.$br->id.'" />',$count, $linktoBranch, $totalbanks);
	$count++;
}
echo $this->table->generate();
?>
</div>

