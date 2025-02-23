<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<?php if($_POST) $date = $_POST['date']; 
else $date = $this->auth->localdate(); ?>

<div class="panel panel-info"><div class="panel-heading"><b>Consolidated</b> - CMC</div>	
<div class="panel-body"><form action="" method="post">
	<div class="row form-group">
		<form class="form_horizontal" method="post">
		<div class="col-md-3"><label>CMC Date: <?php echo date("F d, Y", strtotime($date));?></label></div>
		<div class="col-md-2"><input type="text" name="date" id="date" placeholder="<?php echo $date;?>" class="form-control input-sm"></div>
		<script>
		$(function() {
			var datepick = $( "#date" ).datepicker({format: 'yyyy-mm-dd',
			changeMonth: true,
			changeYear: true
			}).on('changeDate', function(ev) {
			datepick.hide();
			}).data('datepicker');				
		});
		</script>
		<div class="col-md-2"><button type="submit" class="btn btn-primary btn-sm">Search CMC</button></div>
		</form>
	<?php if($this->auth->perms("export", $this->auth->user_id(), 2) == true) { ?> 
	<div class="col-md-2"><form action="<?php echo base_url();?>cash/consolidated/report" method="post" target="_blank">
			<input type="hidden" name="date" value="<?php echo $date;?>">
			<button type="submit" class="btn btn-primary btn-sm">Print CMC</button></form></div>
	</div>
	<?php 
	} ?>
	</div>


<?php 
$cmcrec = $this->Cashmodel->consolidatedCMC($date);
$tmpl = array ('table_open'  => '<table class="table table-bordered table-hover">');
	$this->table->set_template($tmpl);
if($cmcrec->num_rows() > 0){
	
	echo $this->table->set_heading('#', 'Branch', 'Beginning Balance', 'Total Collections', 'Total Disbursements', 'Total Adjustments', 'Ending Balance','Status');
	$count=1;
	$begin = 0;
	$tc =0;
	$td =0;
	$ta =0;
	$end = 0;
	foreach ($cmcrec->result() as $cmc){
		$sum = $this->Cashbalance->EndOfDateSummary($cmc->branchID, $cmc->dateTransaction);
		if($cmc->status == 'open')
		 $act = '<input type="submit" name="lock['.$cmc->transID.']" value="Lock" class="btn btn-xs btn-danger">';
		else
		 $act = '<input type="submit" name="lock['.$cmc->transID.']" value="Open" class="btn btn-xs btn-success">';
		
		$this->table->add_row($count, '<a href="'.base_url().'cash/daily/transaction/'.$cmc->transID.'">'.$cmc->branchname.'</a>', number_format($sum['begin'],2),number_format($sum['totalcol'],2), number_format($sum['totaldis'],2), number_format($sum['totaladj'],2), number_format($sum['end'],2), strtoupper($cmc->status));
		$count++;
		
		$begin += $sum['begin'];
		$tc += $sum['totalcol'];
		$td += $sum['totaldis'];
		$ta += $sum['totaladj'];
		$end +=  $sum['end'];
	}
	$this->table->add_row("-", "<b>Total</b>", "<b>".number_format($begin,2)."</b>",  "<b>".number_format($tc,2)."</b>",  "<b>".number_format($td,2)."</b>",  "<b>".number_format($ta,2)."</b>","<b>".number_format($end,2)."</b>", "");
	echo '<div class="table-responsive">';
	echo '<form action="" method="post">';
	echo $this->table->generate();
	echo '</form>';
	echo '</div>';
	
}else{ ?>
	<div class="panel-body">No transactions on this day.</div>
<?php } ?>
</div>
</form>
