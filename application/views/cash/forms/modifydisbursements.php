<?php 
$coll = $collection->row();
$dis = $this->Cashbalance->getTransactionType("disbursement");
echo validation_errors();
if(isset($error)) echo '<div class="alert alert-danger">'.$error.'</div>';
if(isset($success)) echo '<div class="alert alert-success">'.$success.'</div>';
?>
<div class="modal-dialog ">
<form class="form-horizontal" method="post" action="<?php echo base_url();?>cash/daily/update/disbursement/<?php echo $id;?>">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Add New Disbursement</h4>
      </div>
      <div class="modal-body">
	  <div class="row form-group">
	  <div class="col-xs-4"><label>CV No.</label>
		<input type="text" name="reference" class="form-control input-sm" value="<?php echo $coll->referenceNo;?>"  required>
		</div>
		<div class="col-xs-4"><label>Check No.</label>
		<input type="text" name="checkno" class="form-control input-sm" value="<?php echo $coll->Checkno;?>" required>
		</div>
		<div class="col-xs-4"><label>PN No.</label>
		<input type="text" name="PN" class="form-control input-sm" value="<?php echo $coll->PN;?>" required>
		</div>
	  </div>
	  <div class="row form-group">
		<div class="col-xs-6"><label>Payee</label>
		<input type="text" name="particular" class="form-control input-sm" value="<?php echo $coll->Particulars;?>"  required>
		</div>
		<div class="col-xs-6"><label>Disbursement Type</label>
			<select name="transtype" class="form-control input-sm" required>
				<option disabled selected>Select</option>
				<?php 
				if($dis->num_rows() >0){
					foreach($dis->result() as $c){
						if( $coll->transType = $c->transTypeID) $select = "selected";
						else $select = '';
						echo "<option value='".$c->transTypeID."' ".$select." >".$c->transType."</option>";
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
					if( $coll->branchBankID == $ba->branchBankID) $select = "selected";
					else $select = '';
					if(!empty($ba->branchCode))
				$bcode = "-".$ba->branchCode;
				else $bcode = "";
				?>
					<option value="<?php echo $ba->branchBankID;?>" <?php echo $select;?>><?php echo $ba->bankCode.$bcode;?></option>
				<?php } ?>
			</select>
		</div>		
		<div class="col-xs-6"><label>Amount</label>
		<input type="text" name="amount" placeholder="00.00" value="<?php echo $coll->Amount_OUT;?>" class="form-control input-sm" required>
		</div>
	  </div>
		<div class="row form-group">
		<div class="col-xs-12"><label>Explanation</label>
			<textarea name="explanation" class="form-control input-sm" ><?php echo $coll->explanation;?></textarea>
		</div>				
	  </div>
	  </div>
	   <div class="modal-footer">
		<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
          <input type="submit" class="btn btn-sm btn-danger " name="submit" value="Update Disbursement">
      </div>
	</div>
</form>
</div>
