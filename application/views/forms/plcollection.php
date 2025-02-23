<?php 
	//$pen = $pension->row();
	$client = $client->row();
	if(!isset($pen->bankCode))
	$bank = "<i>UPDATE BANK</i>";
	else {
		$bank = $pen->bankCode. " # ".$pen->Bankaccount;
	}

?>
<form class="form-horizontal" id="collectionform"  method="post" action="<?php echo base_url();?>cash/collections/addplcollection">
    <div class="modal-content" >
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Add New Collection</h4>
      </div>
      <div class="modal-body" id="plcollectionform">
	  <div class="alert alert-danger " id="warning" >
	  </div>
      	<!-- Start CONTENT -->
      	<div id="plcontent">
		  <div class="row form-group">
			<div class="col-md-4">
				<label>Name: <input type="text" name="particular" value="<?php echo $client->LastName.", ".$client->firstName;?>" class="form-control input-sm" readonly></label>
			</div>
			<div class="col-md-4">
			</div>
			<div class="col-md-4">
				<label>OR #: <input type="text" name="reference" class="form-control input-sm" placeholder="Enter OR Number" ></label>
			</div>
		  </div>
		  <div class="row form-group">
			<div class="col-md-4">
				<label>Collection Type</label>
				<?php $col = $this->Cashbalance->getTransactionType("collection"); ?>
				<select name="transtype" class="form-control input-sm" required >
					<?php foreach ($col->result() as $c){
						if($c->transType == "PL Collection")
						echo "<option value='".$c->transTypeID."'>".$c->transType."</option>";
					} ?>
				</select>
			</div>

			<div class="col-md-4">
				<label>Payment Type</label>
				<?php $data = array("active"=>1);
					$payment = $this->Loansmodel->get_data_from("paymenttype", $data);?>
				<select name="paymentType" class="form-control input-sm" id="paymenttype" required>
					<?php if($payment->num_rows() > 0){
						foreach ($payment->result() as $trans ){
							if($trans->typeOfPayment=='POS')
							$select = "selected";
							else
							$select = '';
							echo '<option value="'.$trans->typeOfPayment.'" '.$select.'>'.$trans->typeOfPayment.'</option>';
						}
					} ?>			
				</select>
			</div>
			<div class="col-md-4">
				<label>To Bank</label>
				<select name="bankID" class="input-sm form-control">
				<?php 
					foreach($banks->result() as $bank){
						echo "<option value='".$bank->branchBankID."'>".$bank->bankCode."</optio>";
					}
				?>
				</select>
			</div>
		</div>
		<div class="row form-group " id="POS">
			<div class="col-md-4">
				<label>Beginning Balance </label>
				<input type="text" class="input-sm form-control" name="beginbal" id="beginbal" placeholder="00.00" required>
			</div>
			<div class="col-md-4">
				<label>Amount Withdrawn</label>
				<input type="text" class="input-sm form-control" name="amount" id="withdrawn" placeholder="00.00" required>
			</div>
			<div class="col-md-4">
				<label>Amount Left</label>
				<input type="text" class="input-sm form-control" name="amountleft" id="amountleft" placeholder="00.00" readonly>
			</div>
		</div>
		<div class="row form-group" id="cash">
			<div class="col-md-4 pull-right">
				<label>Amount Paid</label>
				<input type="text" class="input-sm form-control" name="amountcash" placeholder="00.00" required>
			</div>			
		</div>
		<div class="row form-group" id="check">
			<div class="col-md-4">
				<label>Amount Received</label>
				<input type="text" class="input-sm form-control" name="amountreceived"  placeholder="00.00" required>
			</div>	
			<div class="col-md-4">
				<label>Check No.</label>
				<input type="text" class="input-sm form-control" name="check"  placeholder="######" required>
			</div>	
			<div class="col-md-4">
				<label>Bank of the Check</label>
				<input type="text" class="input-sm form-control" name="bankfcheck" placeholder="######" required>
			</div>			
		</div>
		
		<?php $date = $this->auth->localdate();
	$now = strtotime($this->auth->localtime());
	$enddate = date("Y-m-d", strtotime($date."+5 day"));
	//$due = $this->Loansmodel->clientpensiondue($pensionid, $enddate);
	//$due = $this->Loansmodel->getOutBalance($client->ClientID);
	$due = $this->Loansmodel->clientdue($pensionid, $enddate);
	?>
<h4>Due as of <?php echo date("F d, Y");?> </h4>

<?php
	$tmpl = array ('table_open'  => '<table class="table table-bordered table-condensed table-hover">');
	$this->table->set_template($tmpl);	
	$count=1;
	$total = 0;
	if($due->num_rows() > 0){
		
		
		foreach($due->result() as $d){
			
			$duedate = strtotime($d->DDUE);
			$datediff = $now - $duedate;
			$aging =  floor($datediff/(60*60*24));
			if($aging <= 0)
			$aging = 0;
			$this->table->add_row("<input type='checkbox' name='amountdue[".$d->PN."][".$d->schedID."]' class='' value='".$d->INSTAMT."' checked>",$count, "<a href='".base_url()."client/profile/".$clientid."/loan/".$d->loanID."'>".$d->PN, date("M d, Y", strtotime($d->DDUE)), number_format($d->INSTAMT,2));
			$count++;
			$total += $d->INSTAMT;
		}
		
			$this->table->set_heading("Pay","#", "PN", "Due Date", "Amount Due");
			//$this->table->add_row(array("colspan"=>4, "data"=>"<label>TOTAL DUE</label>"), '<input type="text" class="input-sm form-control pull-right" style="text-align: right; font-weight: bold" name="totaldue" value="'.number_format($total,2).'" readonly>');
			?>
	
		<div class="panel panel-default">
			<?php echo $this->table->generate();?>			
		</div>
		<?php		
	}
	
	
		?>
		<div class="row form-group">
					<div class="col-md-6">
						<label> TOTAL DUE </label>
					</div>
					<div class="col-md-6">
						<input type="text" class="input-lg form-control pull-right totaldue" style="text-align: right; font-weight: bold; " name="totaldue" id="totaldue" value="<?php echo $total;?>" readonly>
					</div>
		</div>
		
		<hr/>
		<div class="row form-group">
			<div class="col-md-6">
				<label>EXCESS </label>
			</div>
			<div class="col-md-6">
				<input type="text" class="input-sm form-control pull-right" style="text-align: right; font-weight: bold" name="excess" id="excess" value="" readonly>
			</div>
		</div>
		
		</div>		
		<!-- END CONTENT -->
	  </div>
	   <div class="modal-footer" id="plfooter">
	   <input type="hidden" name="transdate" value="<?php if (isset($transdate)) echo $transdate; else echo $this->auth->localdate();?>">
	   <input type="hidden" name="clientID" value="<?php echo $client->ClientID;?>">
	   <input type="hidden" name="pensionid" value="<?php echo $pensionid;?>">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
       <input type="submit" class="btn btn-sm btn-success submit"  name="submit" id="addcollection" value="Add Collection">
      </div>
	</div>
</form>

