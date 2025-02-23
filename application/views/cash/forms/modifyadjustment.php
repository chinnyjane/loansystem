<?php 
$coll = $collection->row();
$adj = $this->Cashbalance->getTransactionType("adjustment");
echo validation_errors();
if(isset($error)) echo '<div class="alert alert-danger">'.$error.'</div>';
if(isset($success)) echo '<div class="alert alert-success">'.$success.'</div>';
?>
<div class="modal-dialog ">
<form class="form-horizontal" method="post" action="<?php echo base_url();?>cash/daily/update/adjustment/<?php echo $id;?>">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Add New Adjustment</h4>
      </div>
      <div class="modal-body">
	  <div class="row form-group">
	  <div class="col-xs-4"><label>JV No.</label>
			<input type="text" name="reference" placeholder="JV No" class="form-control input-sm" value="<?php echo $coll->referenceNo;?>"required>
		</div>
	  <div class="col-xs-4"><label>Particulars / Name</label>
		<input type="text" name="particular" class="form-control input-sm" value="<?php echo $coll->Particulars;?>" required>
		</div>
		<div class="col-xs-4"><label>Adjustment Type</label>
			<select name="transtype" class="form-control input-sm" required>
				<option disabled selected>Select</option>
				<?php 
				if($adj->num_rows() >0){
					foreach($adj->result() as $c){
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
		<div class="col-xs-4"><label>Add to or Less from</label>
		<select name="addorless" class="form-control input-sm" required>
			<option disabled selected>Add to/ Less from</option>
			<option value="add">Add to</option>
			<option value="less">Less from</option>
		</select>
		</div>
		<div class="col-xs-4"><label>Bank</label>
			<select name="bankID" class="form-control input-sm" required>
				<?php foreach($banks->result() as $ba){ 
					if( $coll->branchBankID = $ba->branchBankID) $select = "selected";
					else $select = '';
					if(!empty($ba->branchCode))
					$bcode = "-".$ba->branchCode;
					else $bcode = "";
				?>
					<option value="<?php echo $ba->branchBankID;?>" <?php echo $select;?>><?php echo $ba->bankCode.$bcode;?></option>
				<?php } ?>
			</select>
		</div>		
		<div class="col-xs-4"><label>Amount</label>
		<?php if(!empty($coll->Amount_IN)) $amount = $coll->Amount_IN;
			else $amount = $coll->Amount_OUT; ?>
		<input type="text" name="amount" placeholder="00.00"  class="form-control input-sm" value="<?php echo $amount;?>" required>
		</div>
	  </div>
		<div class="row form-group">
		<div class="col-xs-12"><label>Explanation</label>
		<textarea class="form-control input" name="explanation" value="<?php echo $coll->explanation;?>"></textarea>
		</div>
		</div>
	  </div>
	   <div class="modal-footer">
	   <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-sm btn-warning " name="submit" value="Update Adjustment">
      </div>
	</div>
</form>
</div>