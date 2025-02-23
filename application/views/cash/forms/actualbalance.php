<?php 
if($actualbal->num_rows()> 0){
	$act = $actualbal->row();	
	$balance = $act->actualBalance;
}else{
	$balance = '';
}
foreach($banks->result() as $ba){ 
	if( $id == $ba->branchBankID) 
		$bank =  $ba->bankCode;
}
?>
<div class="modal-dialog ">
<form class="form-horizontal" method="post" id="actualbal" action="<?php echo base_url();?>cash/update/balanceonbank" id="actualbalform">
 <div class="modal-content">
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Add Actual Bank Balance</h4>
</div>
<div class="modal-body">
	<div class="form-group">
		<div class="col-xs-6">
			<label>Bank</label>
			<input type="text"  value="<?php echo $bank;?>" class="input-sm form-control" readonly>
			<input type="hidden" name="branchBankID" id="bankSumID"  value="<?php echo $id;?>" class="input-sm form-control" readonly>
			<input type="hidden" name="transid" id="bankSumID"  value="<?php echo $transid;?>" class="input-sm form-control" readonly>
			<input type="hidden" name="transdate" id="bankSumID"  value="<?php echo $transdate;?>" class="input-sm form-control" readonly>
		</div>
		<div class="col-xs-6">
			<label>Actual Bank Balance</label>
			<input type="text" name='amountbalance'  id='amountbalance'  value="<?php echo $balance;?>" placeholder="00.00" class="input-sm form-control">
		</div>
	</div>
	
</div>
<div class="modal-footer">
	<input type="submit" id="UpdateBal"  class="btn btn-primary btn-sm" value="Update Balance" />
	<a class="btn btn-sm btn-default" data-dismiss="modal">Close</a>
</div>
</div>
</form>
</div>