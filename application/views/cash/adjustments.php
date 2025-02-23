<?php 
	$col = $this->Cashbalance->getTransactionType("adjustment"); 
?>
<div class="row">
<?php 
	if($col->num_rows() > 0){
		foreach($col->result() as $c){ ?>
			<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
				<button href="" class="btn btn-default .btn-flat form-control" style="margin-bottom: 10px"><?php echo $c->transType;?></button>
			</div>
		<?php }
	}
?>
</div>