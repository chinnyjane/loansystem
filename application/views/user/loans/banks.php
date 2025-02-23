<?php
$banks = $this->Loansmodel->get_data_from('banks', '');
?>
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<h2 class="content-subhead"><a href="<?php echo base_url();?>loans/setup">Loan Setup</a></h2>
<a href="<?php echo base_url();?>loans/setup/banks/create" class="btn btn-warning">Add New Bank</a><br/><br/>
<form class="form-horizontal" method="post" action="">
	<div class="panel panel-success"><div class="panel-heading">Banks</div>	
	<?php if($banks->num_rows() > 0){ 
	$count = 1;
	?>
	<table class="table table-bordered">
		<thead>
		<tr>
			<th>No.</th>
			<th>Bank Code</th>
			<th>Bank Name</th>
		</tr>
		<thead>
		<tbody>
	<?php foreach ($banks->result() as $b){ ?>
		<tr><td><?php echo $count;?></td>
		<td><?php echo $b->bankCode;?></td>	
		<td><?php echo $b->BankName;?></td>		
		</tr>
	<?php $count++; } ?>
		</tbody>
	</table>
	<?php } ?>
</div>
</div>
 