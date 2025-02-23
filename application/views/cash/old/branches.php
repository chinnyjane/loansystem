<?php
$config['base_url'] = base_url()."cash/branches";				
$config['per_page'] = 14;
$config['total_rows'] = $this->UserMgmt->get_total_records('branches', '');
$config['uri_segment'] = 3;
$this->pagination->initialize($config);
$segment = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
$branch = $this->UserMgmt->get_records_from('branches' ,$config['per_page'], $segment, '');
$tmpl = array (
                    'table_open'          => '<table class="table table-bordered">',
                    'heading_row_start'   => '<tr>',
                    'heading_row_end'     => '</tr>',
                    'heading_cell_start'  => '<th>',
                    'heading_cell_end'    => '</th>',                    
					'row_start'           => '<tr>',
                    'row_end'             => '</tr>',
                    'cell_start'          => '<td>',
                    'cell_end'            => '</td>',
                    'row_alt_start'       => '<tr>',
                    'row_alt_end'         => '</tr>',
                    'cell_alt_start'      => '<td>',
                    'cell_alt_end'        => '</td>',
                    'table_close'         => '</table>'
              );
$this->table->set_template($tmpl);
 ?>
 <form class="form_horizontal" method="post">
<div class="panel panel-info"><div class="panel-heading"><b>Branches</b> - CMC</div>	
<?php 
$this->table->set_heading('#', 'Branch Name', 'Beginning Balance', 'as Of Date');
$count = $segment +1;
foreach ($branch->result() as $br){
	$total = $this->cash->getTotalBal($br->id);
	$linktoBranch = anchor('cash/branches/details/'.$br->id, $br->branchname);
	$this->table->add_row($count, $linktoBranch, number_format($total, 2) , 0);
	$count++;
}
echo $this->table->generate();
?>
</div>
</form>
