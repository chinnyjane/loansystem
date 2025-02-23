<?php
$config['base_url'] = base_url()."cash/banks";				
$config['per_page'] = 10;
$config['total_rows'] = $this->UserMgmt->get_total_records('banks', 'all');
$config['uri_segment'] = 3;
$this->pagination->initialize($config);
$segment = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
$branch = $this->UserMgmt->get_records_from('banks' ,$config['per_page'], $segment, 'all');
$tmpl = array (
                    'table_open'          => '<table class="table table-bordered">',
                    'heading_row_start'   => '<tr>',
                    'heading_row_end'     => '</tr>',
                    'heading_cell_start'  => '<th class="column-check">',
                    'heading_cell_end'    => '</th>',                    
					'row_start'           => '<tr>',
                    'row_end'             => '</tr>',
                    'cell_start'          => '<td>',
                    'cell_end'            => '</td>',
                    'row_alt_start'       => '<tr >',
                    'row_alt_end'         => '</tr>',
                    'cell_alt_start'      => '<td>',
                    'cell_alt_end'        => '</td>',
                    'table_close'         => '</table>'
              );
$this->table->set_template($tmpl);
$start = $segment+1;
$end = $segment + $branch->num_rows();
 
 if(isset($success)) echo '<div class="alert alert-success">'.$success."</div>"; ?>
<div class="panel panel-info"><div class="panel-heading"><b>Banks</b> - CMC</div>
<form class="form_horizontal" method="post">
<div class="panel-body">
	<div class="row form-group">
		<div class="col-md-2">			
			<input type="text" class="form-control input-sm" name="bankcode" placeholder="Bank Code" required>
		</div>
		<div class="col-md-3">			
			<input type="text" class="form-control input-sm" name="bankName" placeholder="Bank Name" required>
		</div>
		<div class="col-md-3">			
			<input type="submit" class="btn btn-primary btn-sm" name="submit" value="Add Bank">
		</div>
	</div>	
	<div class="col-md-12">
 <p class="text-right"><small><b>Records <?php echo $start." - ".$end." of ".$config['total_rows'];?></b></small></p></div>
 </div>
</form>
 <form class="form_horizontal" method="post">
<div class="table-responsive">
<?php 
$this->table->set_heading('<input class="check-all" type="checkbox" />','#', 'Bank Code', 'Bank Name', 'Status');
$count = $segment +1;
foreach ($branch->result() as $br){
	if($br->active == 1) $stat = 'active';
	else $stat = "inactive";
	//$linktoBranch = anchor('cash/branches/details/'.$br->id, $br->branchname);
	$this->table->add_row('<input type="checkbox" name="checked[]"  class="case" value="'.$br->bankID.'" />',$count, $br->bankCode, $br->BankName, $stat);
	$count++;
}
echo $this->table->generate();
?>
</div>
<div class="panel-footer">
<div class="row form-group"><div class="col-md-8"><input type="submit" name="submit" value="Activate" class="btn btn-success btn-sm">&nbsp;<input type="submit" name="submit" value="Deactivate" class="btn btn-danger btn-sm"></div>
<div class="col-md-4"><?php echo $this->pagination->create_links(); ?></div>
</div>
</div>
</form>
</div>

