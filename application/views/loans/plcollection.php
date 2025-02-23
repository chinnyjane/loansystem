<br/><p><button class="btn btn-sm btn-success" data-toggle="modal" data-target="#collection">Add Collection</button></p>

<div class="panel panel-default">
<?php

$collection = $this->Loansmodel->getCollections($pensionid);

if($collection->num_rows() > 0){
	
	/*echo "<pre>";
	print_r($collection->result());
	echo "</pre>";*/

	$this->table->set_heading("Date Withdrawn", "Balance Inquiry", "Amount Withdrawn","End Bank Balance",  "Principal", "Excess", "OR #", "AR #");
	
	foreach($collection->result()  as $col){
		$principal = $col->amountwithdrawn - $col->excessamount;
		$this->table->add_row(date("Y-m-d", strtotime($col->dateAdded)), number_format($col->beginbal,2), number_format($col->amountwithdrawn,2),number_format($col->amountLeft,2),  $principal, $col->excessamount, $col->referenceNo, "AR #");
	}
	
	echo $this->table->generate();
}
?>
</div>