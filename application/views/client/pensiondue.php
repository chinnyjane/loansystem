<?php $date = $this->auth->localdate();
	$now = strtotime($this->auth->localtime());
	$enddate = date("Y-m", strtotime($date));
	$due = $this->Loansmodel->clientdue($pensionid, $enddate);
	echo $this->db->last_query();		
	$this->output->enable_profiler(TRUE);
	$olddue = $this->Loansmodel->clientpensiondue_old($pensionid, $enddate);	
	?>
	<form action="" method="post">
		<div class="panel panel-danger">
			<div class="panel-heading">Due this month</div>
<?php
	$tmpl = array ('table_open'  => '<table class="table table-bordered table-condensed table-hover">');
	$this->table->set_template($tmpl);	
	
	if($due != ''){
		if($this->auth->perms('debug',$this->auth->user_id(),3) == true) { 
		//echo "<pre>";
		//print_r($due);
		//echo "</pre>";		
		}
	if($due->num_rows() > 0){
		
		$count=1;
		$total = 0;
		foreach($due->result() as $d){
			
			$duedate = strtotime($d->DDUE);
			$datediff = $now - $duedate;
			$aging =  floor($datediff/(60*60*24));
			if($aging <= 0)
			$aging = 0;
			$this->table->add_row($count, "<a href='".base_url()."client/profile/".$clientid."/loan/".$d->loanID."'>".$d->PN, $d->DDUE, number_format($d->INSTAMT,2),$aging." day(s)");
			$count++;
			$total += $d->INSTAMT;
		}
		
			$this->table->set_heading("#", "PN", "Due Date", "Amount Due", "Aging");
			$this->table->add_row(array("colspan"=>3, "data"=>"<b>TOTAL DUE</b>"), "<b>".number_format($total,2)."</b>", "");
			?>
	
			<?php echo $this->table->generate();?>
			<div class="panel-footer">
				<button class="btn btn-sm btn-danger"  data-toggle="modal" data-target="#collection">Pay Client Due</button>
			</div>
		
	<?php		
	}else{
		echo "<div class='panel-body'>No due for this month.</div>";
	}
	}else{
		echo "<div class='panel-body'>No due for this month.</div>";
	}
?>
</div>
	</form>
