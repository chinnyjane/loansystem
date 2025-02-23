<?php
$config['base_url'] = base_url()."client";				
$config['per_page'] = 10;
$col = $this->Clientmgmt->get_clients($name,'','');
$config['total_rows'] = $col->num_rows();
$config['uri_segment'] = 2;
$this->pagination->initialize($config);
$segment = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
$module = $this->Clientmgmt->get_clients($name, $config['per_page'] , $segment);
$tmpl = array ('table_open' => '<table class="table table-bordered table-hover">' );
$this->table->set_template($tmpl);
$count = $segment + 1;
$num = $segment + $config['per_page'];
?>
<script type="text/javascript">
	$(document).ready(function() {
	
		// Support for AJAX loaded modal window.
		// Focuses on first input textbox after it loads the window.
	$('[data-toggle="modal"]').click(function(e) {
		e.preventDefault();
		var url = $(this).attr('href');
		if (url.indexOf('#') == 0) {
			$(url).modal('open');
		} else {
			$.get(url, function(data) {
				$('<div class="modal fade" id="collection" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">' + data + '</div>').modal();
			}).success(function() { $('input:text:visible:first').focus(); });
		}
	});
	});
</script>
<div class="panel panel-green">
<div class="panel-heading"><b>Clients Management</b></div>
<div class="panel-body">
	<form action="<?php echo $config['base_url'];?>" method="post">
	<div class="row form-group">
		<div class="col-md-3">
			<input type="text" class="input-sm form-control" placeholder="Seaching <?php echo $name;?>" name="name">
		</div>
		<div class="col-md-3">
			<input type="submit" class="btn btn-success btn-sm" name="submit" value="Search Client">
		</div>
	</div>
	</form>
	<?php echo "<b>Showing result: ".$count." - ".$num." of ".$config['total_rows']." records </b>";?>
</div>
<div class="table-responsive">
<?php
$tmpl = array ('table_open'  => '<table class="table table-bordered table-hover table-condensed" id="dataTables-example">');
$this->table->set_template($tmpl);
$this->table->set_heading("#","Action","Client ID", "Last Name", "First Name",  "Date of Birth","Gender","Civil Status", "Address");
if ($module->num_rows() > 0){
	foreach($module->result() as $cl){
		//$act = "<a href='".base_url()."client/page/profile/".$cl->ClientID."' title='View' data-target='#' data-toggle='modal'><span class='glyphicon glyphicon-list-alt'></span></a> &nbsp;";
		$act = "<a href='".base_url()."client/profile/".$cl->ClientID."' title='Update' ><i class='fa fa-list-alt'></i> View</a> &nbsp;";
		$this->table->add_row($count,$act,$cl->ClientID, $cl->LastName ,$cl->firstName, $cl->dateOfBirth, $cl->gender, $cl->civilStatus, substr($cl->address,0,50));
		$count++;
	}
}else{
	$this->table->add_row( "","No results found.","","","","");
}
echo $this->table->generate();
?>
</div>
<div class="panel-footer"><?php echo $this->pagination->create_links(); ?></div>
</div>
 
	 <!-- Metis Menu Plugin JavaScript -->
    <script src="assets/js/plugins/metisMenu/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="assets/js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/plugins/dataTables/dataTables.bootstrap.js"></script>
 <script>
    $(document).ready(function() {
        $('#dataTables-example').dataTable();
    });
   </script>

