<?php 
$coll = $collection->row();
$col = $this->Cashbalance->getTransactionType("collection"); 
$data = array("active"=>1);
$payment = $this->Loansmodel->get_data_from("paymenttype", $data);
echo validation_errors();
if(isset($error)) echo '<div class="alert alert-danger">'.$error.'</div>';
if(isset($success)) echo '<div class="alert alert-success">'.$success.'</div>';
?>
<div class="modal-dialog ">
<form class="form-horizontal" method="post" action="<?php echo base_url();?>cash/daily/update/collection/<?php echo $id;?>">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Add New Collection</h4>
      </div>
      <div class="modal-body">
	  <div class="row form-group">
	   <div class="col-xs-6"><label>OR Num</label>
		<input type="text" name="reference" class="form-control input-sm" value="<?php echo $coll->referenceNo;?>" required>
		</div>
		<div class="col-xs-6"><label>PN No.</label>
			<input type="text" name="PN" class="form-control input-sm" value="<?php echo $coll->PN;?>" required>
		</div>	
	  </div>
	   <div class="row form-group">
	   <div class="col-xs-12"><label>Collection Name</label>
		<input type="text" name="particular" class="form-control input-sm" value="<?php echo $coll->Particulars;?>" required>
		</div>
			
	  </div>
	  <div class="row form-group">
	  <div class="col-xs-6"><label>Collection Type</label>
		<select name="transtype" class="form-control input-sm" required >
			<option disabled selected>Collection Type</option>
			<?php foreach ($col->result() as $c){
				if( $coll->transtype == $c->transTypeID) $select = "selected";
				else $select = '';
				echo "<option value='".$c->transTypeID."' ".$select." >".$c->transType."</option>";
			} ?>
		</select>
		</div>
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
			</div>
		<div class="row form-group">
		<div class="col-xs-6"><label>Amount</label>
		<input type="text" name="amount" placeholder="00.00"  value="<?php echo $coll->Amount_IN;?>" class="form-control input-sm" required>
		</div>
		<div class="col-xs-6"><label>Payment Type</label>
		<select name="paymentType" class="form-control input-sm" required>
			<option disabled selected>Payment</option>
			<?php if($payment->num_rows() > 0){
				foreach ($payment->result() as $trans ){
					if($coll->paymentType == $trans->paymentTypeID )
					$select = 'selected';
					else
					$select = '';
					echo '<option value="'.$trans->paymentTypeID.'" '.$select.'>'.$trans->typeOfPayment.'</option>';
				}
			} ?>			
		</select>
		</div>
	  </div>	 
	  </div>
	   <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
		<input type="hidden" name="transdate" value="<?php if (isset($transdate)) echo $transdate; else echo $this->auth->localdate();?>">
       <input type="submit" class="btn btn-sm btn-success " name="submit" value="Update Collection">
      </div>
	</div>
</form>
</div>
