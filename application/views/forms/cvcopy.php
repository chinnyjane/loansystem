<?php $dis = $this->Cashbalance->getTransactionType("disbursement");
$pn  = ($pn ? $pn : "-");
$payee = $p['lname'].", ".$p['firstname'];
$tmpl = array ('table_open'   => '<table class="table  table-condensed table-bordered" >',
			'thead_open' => '<thead class="header">'	); 
$this->table->set_template($tmpl); 
?>
<form class="form-horizontal" method="post" action="<?php echo base_url();?>cash/disbursements/post" id="disburseform">
<?php 
	if($dis->num_rows() >0){
		foreach($dis->result() as $d){
			if($d->transType == "Releases")
				echo "<input type='hidden' name='transtype'  value='".$d->transTypeID."' >";
		}
	}
?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Check Voucher</h4>
      </div>
      <div class="modal-body">
	  <div class="row form-group">
	  <div class="col-xs-4"><label>CV No.</label>
		<input type="text" name="reference" class="form-control input-sm" required>
		</div>
		
		<div class="col-xs-4 pull-right"><label>PN No.</label>
		<input type="text" name="PN" class="form-control input-sm" value = "<?php echo $pn;?>" readonly>
		</div>
	  </div>
	  <div class="row form-group">
		<div class="col-xs-4"><label>Payee</label>
		<input type="text" name="particular" class="form-control input-sm"  value="<?php echo $payee;?>" required readonly>
		</div>
		<div class="col-xs-8"><label>Explanation</label>
			<textarea name="explanation" class="form-control input-sm" required>Net Proceeds of <?php echo $pname;?></textarea>
		</div>	
		</div>
		<div class="row form-group">
			<div class="col-xs-4"><label>Bank</label>
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
			<div class="col-xs-4"><label>Check No.</label>
				<input type="text" name="checkno" class="form-control input-sm" required>
			</div>
			<div class="col-xs-4"><label>Amount</label>
				<input type="text" name="amount" placeholder="00.00" value="<?php echo $comp['net'];?>" class="form-control input-sm" required readonly>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-12">
			<?php
			$this->table->set_heading("Account/Description", "DR", "CR");
			$this->table->add_row("PL", array("align"=>"right","data"=>number_format($amount,2)), array("align"=>"right","data"=>number_format(0,2)));
			foreach($comp['fee'] as $feename=>$val){
				$this->table->add_row($feename, array("align"=>"right","data"=>number_format(0,2)), array("align"=>"right","data"=>number_format($val,2)));
			}
			$this->table->add_row("CIB",array("align"=>"right","data"=>number_format(0,2)) , array("align"=>"right","data"=>number_format($comp['net'],2)));
			echo $this->table->generate();
			?>
			</div>
	  </div>
	  </div>
	   <div class="modal-footer">
		<input type="hidden" name="transdate" value="<?php echo  $this->auth->localdate();?>">
		<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
       <input type="submit" class="btn btn-sm btn-danger " name="submit" id="disbursepost" value="Create Voucher">
      </div>
	</div>
</form>
