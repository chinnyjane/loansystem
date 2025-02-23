<?php $col = $this->Cashbalance->getTransactionType("collection"); 
$data = array("active"=>1);
$payment = $this->Loansmodel->get_data_from("paymenttype", $data);
$branch = $branch->row();
?>
<form class="form-horizontal formpost" method="post" action="<?php echo base_url();?>cash/collections/post" id="collectionform" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Add New Collection</h4>
      </div>
      <div class="modal-body">
	  <div class="row form-group">
	   <div class="col-xs-6"><label>OR Num</label>
		<input type="text" name="reference" class="form-control input-sm" required>
		</div>
		<div class="col-xs-6"><label>PN No.</label>
			<input type="text" name="PN" class="form-control input-sm" value="<?php echo set_value('PN');?>" required>
		</div>	
	  </div>
	   <div class="row form-group">
	   <div class="col-xs-12"><label>Collection Name</label>
		<input type="text" name="particular" class="form-control input-sm"  value="<?php echo set_value('particular');?>"  required>
		</div>
			
	  </div>
	  <div class="row form-group">
	  <div class="col-xs-6"><label>Collection Type</label>
		<select name="transtype" class="form-control input-sm" required >
			<option disabled selected>Collection Type</option>
			<?php foreach ($col->result() as $c){
				echo "<option value='".$c->transTypeID."'>".$c->transType."</option>";
			} ?>
		</select>
		</div>
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
			</div>
		<div class="row form-group">
		<div class="col-xs-6"><label>Amount</label>
		<input type="text" name="amount" placeholder="00.00"  class="form-control input-sm" required>
		</div>
		<div class="col-xs-6"><label>Payment Type</label>
		<select name="paymentType" class="form-control input-sm" required>
			<option disabled selected>Payment</option>
			<?php if($payment->num_rows() > 0){
				foreach ($payment->result() as $trans ){
					echo '<option value="'.$trans->paymentTypeID.'">'.$trans->typeOfPayment.'</option>';
				}
			} ?>			
		</select>
		</div>
	  </div>	 
	  </div>
	   <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
		<input type="hidden" name="submit" value="Add Collection">
		<input type="hidden" name="status" id="status" value="<?php echo $cmcstatus;?>">
		<input type="hidden" name="branchID" id="branchID" value="<?php echo $branch->id;?>">
		<input type="hidden" name="transdate" value="<?php if (isset($transdate)) echo $transdate; else echo $this->auth->localdate();?>">
		<?php if (isset($transid)) { ?>
		<input type="hidden" name="transid" id="transid" value="<?php  echo $transid; ?>">
		<?php } ?>
       <input type="submit" class="btn btn-sm btn-success submit " name="submit" id="collectionpost" value="Add Collection">
      </div>
	</div>
</form>
