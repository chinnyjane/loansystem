<h4>Due this month</h4>
<hr/>
<?php
	$tmpl = array ('table_open'  => '<table class="table table-bordered table-condensed table-hover">');
	$this->table->set_template($tmpl);
	$date = date("Y-m-d");
	$enddate = date("Y-m-d", strtotime($date."+1 months -1 day"));
	$due = $this->Loansmodel->clientdue($clientid, $enddate);
	
	if($due->num_rows() > 0){
		
		$count=1;
		$total = 0;
		foreach($due->result() as $d){
			$this->table->add_row("",$count, $d->PN, $d->DDUE, number_format($d->INSTAMT,2));
			$count++;
			$total += $d->INSTAMT;
		}
		
			$this->table->set_heading("Pay","#", "PN", "Due Date", "Amount Due");
			$this->table->add_row("",array("colspan"=>3, "data"=>"<b>TOTAL DUE</b>"), number_format($total,2));
			?>
	<form action="" method="post">
		<?php echo $this->table->generate();?>
		<input name="submit" type="submit" value="Pay Client Due" class="btn btn-sm btn-success">
	</form>
	<?php		
	}else{
		echo "No due for this month.";
	}
?>
