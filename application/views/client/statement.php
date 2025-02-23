<?php
$client = $this->uri->segment(3);
$date = $this->auth->localdate();
//echo $client;
$statement = $this->Loansmodel->getOutBalance($client);
$monthstatement = $this->Loansmodel->getBalancePerDate($client, $date);
$tmpl = array ('table_open'  => '<table class="table table-bordered table-hover">');
	$this->table->set_template($tmpl);
echo "<h4>Due as of ".date("F d, Y", strtotime($date))."</h4>";
$this->table->set_heading("PN", "Due Date","Amount Due","Aging");
if($monthstatement->num_rows() > 0){
	$total = 0; 
	
	foreach( $monthstatement->result() as $m){
		$this->table->add_row($m->PN, $m->DueDate, number_format($m->AmountDue,2), $m->aging." days");
		$total += $m->AmountDue; 
	}
	$this->table->add_row("<h4>Total</h4>", "", '', "<h4>".number_format($total,2).'</h4>');
	echo $this->table->generate(); ?>
	<!--<p><button class="btn btn-sm btn-danger"  data-toggle="modal" data-target="#collection">Pay Client Due</button></p>-->
<?php	
}else{
	echo "No Due this month";	
}

echo "<h4>Total Outstanding Balance</h4>";
if($statement->num_rows() > 0){
		$this->table->set_heading("PN", "Loan Type", "Date applied", "Maturity Date", "# of Outstanding Months", "MonthlyDue", "Outstanding Balance");
		$total = 0;
		$totalmonths = 0;
	foreach($statement->result() as $st){
		$this->table->add_row($st->PN, $st->productCode, date("F d, Y", strtotime($st->DateApplied)), $st->maturityDate, $st->months, number_format($st->AmountDue), number_format($st->OutstandingBal,2));
		$totalmonths += $st->months;
		$total +=  $st->OutstandingBal;
	}
	$this->table->add_row(array("colspan"=>'4',"data"=>"<h4>Total Number of months Outstanding</h4>"), '<h4>'.$totalmonths.'</h4>', '','<h4>'.number_format($total,2).'</h4>');
	echo $this->table->generate();
}
?>
