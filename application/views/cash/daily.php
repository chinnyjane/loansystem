<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<div class="panel panel-primary"><div class="panel-heading"><b><?php echo $branchname;?> - Daily</b> CMC</div>
<div class="panel-body">
	<div class="row form-group">	
		<form action="" method="post">
		<div class="col-md-3">
		<div class="input-group">
		  <span class="input-group-addon input-sm">From</span>
		   <input type="text" class="form-control input-sm" placeholder="From Date" name="fromdate" id="fromdate" required>
		  <script>
			$(function() {
				var datepick = $( "#fromdate" ).datepicker({format: 'yyyy-mm-dd',
				changeMonth: true,
				changeYear: true
				}).on('changeDate', function(ev) {
				datepick.hide();
				}).data('datepicker');				
			});
			  </script>
		</div>
		</div>
		<div class="col-md-3">
		<div class="input-group">
		 <span class="input-group-addon input-sm" >To</span>
		  <input type="text" class="form-control input-sm" placeholder="To Date" name="todate" id="todate" required> 
		  <script>
			$(function() {
				var datepick = $( "#todate" ).datepicker({format: 'yyyy-mm-dd',
				changeMonth: true,
				changeYear: true
				}).on('changeDate', function(ev) {
				datepick.hide();
				}).data('datepicker');				
			});
			  </script>
		</div></div>
		<div class="col-md-3">
		<input name='submit' class="btn btn-primary btn-sm" type="submit" value="Search">
		</div>
		</form>
		</div>
	</div>

<?php
$config['base_url'] = base_url()."cash/daily";				
$config['per_page'] = 10;
$config['uri_segment'] = 3;	
$total = $this->Cashmodel->getTransaction($date, $branch,'','');
$segment = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
$transaction = $this->Cashmodel->getTransaction($date, $branch,$config['per_page'],$segment);
$trans = $transaction['rec'];
$config['total_rows']  = $total['rec']->num_rows();
//echo $total['rec']->num_rows();
$this->pagination->initialize($config);

if($trans->num_rows() <= 0){?><div class="alert alert-danger"> No Transactions yet.</div><?php }else{
$tmpl = array ('table_open'  => '<table class="table table-bordered table-hover table-condensed">');
$this->table->set_template($tmpl);
$this->table->set_heading('#', 'Trans Date','Beginning Balance', 'Total Collections', 'Total Disbursement', 'Total Adjustment', 'Ending Balance','Status');
$count = $segment +1;
foreach($trans->result() as $r){
	$sum = $this->Cashbalance->EndOfDateSummary($r->branchID, $r->dateTransaction);
	if($r->status == 'open')
	 $act = '<input type="submit" name="lock['.$r->transID.']" value="Lock" class="btn btn-xs btn-danger">';
	else
	 $act = '<input type="submit" name="lock['.$r->transID.']" value="Open" class="btn btn-xs btn-success">';	
	$this->table->add_row($count, '<a href="'.base_url().'cash/daily/transaction/'.$r->transID.'">'.$r->dateTransaction.'</a>',  number_format($sum['begin'],2),number_format($sum['totalcol'],2), number_format($sum['totaldis'],2), number_format($sum['totaladj'],2), number_format($sum['end'],2), strtoupper($r->status) );
	$count++;
}
echo '<div class="table-responsive">';
echo '<form action="" method="post">';
echo $this->table->generate();
echo '</form>';
echo '</div>';
} ?>
<div class="panel-footer">
<?php echo $this->pagination->create_links(); ?>
</div>
</div>
