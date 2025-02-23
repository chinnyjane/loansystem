<?php 
if(!isset($br)) $br = "";
$config['base_url'] = base_url()."settings/holidays/";				
$config['per_page'] = 10;
$col = $this->UserMgmt->getHolidays($br, NULL, NULL);
$config['total_rows'] = $col->num_rows();
$config['uri_segment'] = 3;
$this->pagination->initialize($config);
$segment = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
$holidays = $this->UserMgmt->getHolidays($br, $segment, $config['per_page']);
$tmpl = array ('table_open' => '<table class="table table-bordered table-hover">' );
$this->table->set_template($tmpl); 
?>
<div class="panel panel-green">
<div class="panel-heading"><b>Holidays</b></div>
<?php if($this->auth->perms($module.".".$submod, $this->auth->user_id(), 1) == true){ ?><div class="panel-body"><button class="btn btn-success btn-sm" data-toggle="modal" data-target="#holiday">Add New Holiday</button></div> <?php } 
echo validation_errors();
?>
<div class="table-responsive">
<?php 
if($holidays->num_rows() > 0){
	$this->table->set_heading("#", "Date", "Holiday", "Branch", "Status");
	$count = $segment + 1;
	foreach($holidays->result() as $hol){
		if($hol->branchID == 0)
		$branchname = "National";
		else $branchname = $hol->branchname;
		$this->table->add_row($count, date("F d, Y, l", strtotime($hol->dateOfHoliday)), $hol->holiday, $branchname, $hol->active);
		$count++;
	}
	echo $this->table->generate();
}else{ ?>
<div class="panel-body">No holidays yet.</div>
<?php } ?>
</div>
<div class="panel-footer"><?php echo $this->pagination->create_links(); ?></div>
</div>
<div class="modal fade" id="holiday" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
  <form action="" method="post">
	<div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Add New Holiday</h4>
      </div>
	  <div class="modal-body">
		<div class="row form-group">
		<div class="col-md-3"><label>Date</label>
		<input type="text" class="form-control input-sm" name="Date" id="BeginDate" placeholder="yyyy-mm-dd" required>
				<script>
				  $(function() {
					var datepick = $( "#BeginDate" ).datepicker({format: 'yyyy-mm-dd',
					changeMonth: true,
					changeYear: true
					}).on('changeDate', function(ev) {
						datepick.hide();
					}).data('datepicker');				
				  });
			  </script>
		</div>
		<div class="col-md-5">
			<label>Holiday</label>
			<input type="text" name="holiday" placeholder="Description" class="input-sm form-control" required>
		</div>
		<div class="col-md-4">
			<label>Branch</label>
			<select name="branch" class="input-sm form-control" required>
				<option value="0" selected>National</option>
				<?php $branch = $this->UserMgmt->get_branches();
				if($branch->num_rows() > 0){
					foreach($branch->result() as $br){
						echo "<option value='".$br->id."'>".$br->branchname."</option>";
					}
				}
				?>
			</select>
			</div>
		</div>
		</div>
	<div class="modal-footer">
	    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
       <input type="submit" class="btn btn-sm btn-warning " name="submit" value="Add Holiday">
      </div>
	  </div>
	</div>
  </form>
 </div>
</div>