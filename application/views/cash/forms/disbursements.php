<?php $dis = $this->Cashbalance->getTransactionType("disbursement");?>
<form class="form-horizontal" method="post" action="<?php echo base_url();?>cash/disbursements/post" id="disburseform">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Add New Disbursement</h4>
      </div>
      <div class="modal-body">
	  <div class="row form-group">
	  <div class="col-xs-4"><label>CV No.</label>
		<input type="text" name="reference" class="form-control input-sm" required>
		</div>
		<div class="col-xs-4"><label>Check No.</label>
		<input type="text" name="checkno" class="form-control input-sm" required>
		</div>
		<div class="col-xs-4"><label>PN No.</label>
		<input type="text" name="PN" class="form-control input-sm" required>
		</div>
	  </div>
	  <div class="row form-group">
		<div class="col-xs-6"><label>Payee</label>
		<input type="text" name="particular" class="form-control input-sm" required>
		</div>
		<div class="col-xs-6"><label>Disbursement Type</label>
			<select name="transtype" class="form-control input-sm" required>
				<option disabled selected>Select</option>
				<?php 
				if($dis->num_rows() >0){
					foreach($dis->result() as $d){
						echo "<option value='".$d->transTypeID."'>".$d->transType."</option>";
					}
				}
				?>
			</select>
		</div>
		</div>
		<div class="row form-group">
		<div class="col-xs-6"><label>Bank</label>
			<select name="bankID" class="form-control input-sm" required>
				<?php foreach($banks->result() as $ba){
				if(!empty($ba->branchCode))
				$bcode = "-".$ba->branchCode;
				else $bcode = "";
				?>
					<option value="<?php echo $ba->branchBankID;?>"><?php echo $ba->bankCode.$bcode;?></option>
				<?php } ?>
			</select>
		</div>		
		<div class="col-xs-6"><label>Amount</label>
		<input type="text" name="amount" placeholder="00.00"  class="form-control input-sm" required>
		</div>
	  </div>
		<div class="row form-group">
		<div class="col-xs-12"><label>Explanation</label>
			<textarea name="explanation" class="form-control input-sm" required></textarea>
		</div>				
	  </div>
	  </div>
	   <div class="modal-footer">
		<input type="hidden" name="branchID" id="branchID" value="<?php echo $branchid;?>">
		<input type="hidden" name="transdate" value="<?php if (isset($transdate)) echo $transdate; else echo $this->auth->localdate();?>">
		<input type="hidden" name="transid" id="transid" value="<?php echo $transid;?>">
		<input type="hidden" name="submit" value="Add Disbursement">
		<input type="hidden" name="status" id="status" value="<?php echo $cmcstatus;?>">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
       <input type="submit" class="btn btn-sm btn-danger " name="button" id="disbursepost" value="Add Disbursement">
      </div>
	</div>
</form>
