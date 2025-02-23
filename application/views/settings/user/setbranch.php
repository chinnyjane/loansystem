<div class="panel panel-green"><div class="panel-heading">Branches</div>
<?php if($this->auth->perms($active,$this->auth->user_id(),1) == TRUE){ ?>
<form class="form-horizontal" method="post" action="">
	<div class="panel-body">
		<div class="row form-group">
		<div class="col-md-4"><label>Branch Name</label>
        <input id="branch" name="branch" type="text" class="form-control input-sm" placeholder="Branch Name" required></div>
		<div class="col-md-4"><label>Branch Address</label>
		 <input id="address" name="address" type="text" class="form-control input-sm" placeholder="Address" required></div>
		 <div class="col-md-1"><label></label>
        <input type="submit" class="btn btn-success btn-sm" name="action" value="Add Branch"></div>
		</div>
		</div>	
</form>
<?php 
//ADD branch street, brgy, city, province, phone number
 } ?>
<div>
<form action="" method="post">
<?php
$config['base_url'] = base_url().$link;				
$config['per_page'] = 10;
$config['total_rows'] = $this->UserMgmt->get_total_records('branches', '1');
$config['uri_segment'] = 4;
$this->pagination->initialize($config);
$segment = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
$branch = $this->UserMgmt->get_records_from('branches' ,$config['per_page'], $segment, '1');
$tmpl = array ('table_open'  => '<table class="table table-bordered table-hover">');
$this->table->set_template($tmpl);
if(isset($success)) echo '<div class="alert alert-success">'.$success."</div>";
if($branch->num_rows() >0){ 

$count = $segment +1;
foreach ($branch->result() as $br){
	//$total = $this->cash->getTotalBal($br->id);
	$banks = $this->Cashmodel->getbanklistonbranch($br->id);
	$totalbanks = count($banks->result());
	$totalbanks = $totalbanks ? $totalbanks :0;
	$linktoBranch = anchor('settings/branch/details/'.$br->id, $br->branchname);
	$this->table->add_row('<input type="checkbox" name="checked[]"  class="case" value="'.$br->id.'" />',$count, $linktoBranch, $totalbanks);
	$count++;
}
$this->table->set_heading('<input class="check-all" type="checkbox" />','#', 'Branch Name', '# of Bank Accounts');
echo $this->table->generate();?>
</div>
<div class="panel-body">
<div class="row">
	<div class="col-lg-8"><?php if($this->auth->perms($active,$this->auth->user_id(),3) == TRUE){ ?>
		<input type="submit" class="btn btn-success btn-sm" name="action" value="Activate">&nbsp;<input type="submit" class="btn btn-warning btn-sm" name="action" value="Deactivate">&nbsp;<input type="submit" class="btn btn-danger btn-sm" name="action" value="Remove Branch(es)"><?php } ?>
	</div>
	<div class="col-lg-4 pull-right"><?php echo $this->pagination->create_links(); ?></div>
</div>

</div></form>
</div>
<!--<form class="form_horizontal" method="post">
<div class="panel panel-info"><div class="panel-heading">List of Branches</div>
	   <table class="table table-bordered">
        <thead>
            <tr>
                <th class="column-check"><input class="check-all" type="checkbox" /></th>
                <th>Branch ID</th>
                <th>Branch Name</th>               
               
            </tr>
        </thead>
        <tbody>
	<?php foreach ($branch->result() as $br): ?>
    	<tr>
        	<td><input type="hidden" name="checked[<?php echo $br->id; ?>]"  class="case" value="0" /><input type="checkbox" name="checked[<?php echo $br->id; ?>]"  class="case" value="1" /></td>
					<td><?php echo $br->id; ?></td>
					<td><?php echo $br->branchname; ?></td>
					
				</tr>
    <?php endforeach;?>	
    </tbody>
    </table>
	<div class="panel-footer"><?php if($this->auth->perms($active,$this->auth->user_id(),4) == TRUE){ ?><input type="submit" class="btn btn-danger btn-sm" name="action" value="Delete Branch(es)"><?php } ?></div>
</div>
	<?php echo $this->pagination->create_links(); ?>
<?php }else{
	echo "No branches yet";
}
?>
</form>-->

