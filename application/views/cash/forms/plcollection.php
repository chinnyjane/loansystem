<script src="<?php echo base_url();?>assets/js/loansprocess.js" type="text/javascript"></script>
<?php 
$col = $this->Cashbalance->getTransactionType("collection"); 
$data = array("active"=>1);
$payment = $this->Loansmodel->get_data_from("paymenttype", $data);

$where = "transtype = '1' 
		  AND (
			product.productCode = 'PL' 
			OR fees.productID = '0'
		  ) 
		  AND fees.active = '1' ";
$fees = $this->Fees->getFee($where);


//$branch = $branch->row();
?>
<div class="collection">
<form class="form-horizontal " method="post" action="<?php echo base_url();?>cash/collections/post" id="collectionform" >
<div class="row form-group">
	<div class="col-lg-6 col-md-6">
	<div class="row form-group">
			<div class="col-md-8">
				<label>Name: </label><input type="text" name="particulars" value="<?php echo $_GET['particulars'];?>" class="form-control input-sm" readonly>
			</div>
			<div class="col-md-4">
			</div>
			<div class="col-md-4">
				<label>OR #:</label> <input type="text" name="reference" class="form-control input-sm" placeholder="Enter OR Number" >
			</div>
		  </div>
		  <div class="row form-group">
			<div class="col-md-4">
				<label>Collection Type</label>
				
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
			<div class="col-md-6">
				<label>ATM/Passbook Bal. </label>
				<input type="text" class="input-sm form-control" name="beginbal" id="beginbal" style="text-align: right; font-weight: bold" placeholder="00.00" required>
			</div>			
			<div class="col-md-6">
				<label>Amount Left</label>
				<input type="text" class="input-sm form-control" name="amountleft" id="amountleft" style="text-align: right; font-weight: bold" placeholder="00.00" readonly>
			</div>
			<div class="col-md-6">
				<label>Amount Withdrawn</label>
				<input type="text" class="input-sm form-control" name="amount" id="withdrawn" style="text-align: right; font-weight: bold" placeholder="00.00" required>
			</div>
			<div class="col-md-6">
				<label>Excess</label>
				<input type="text" class="input-sm form-control pull-right" style="text-align: right; font-weight: bold" name="excess" id="excess" value="" readonly>
			</div>
		</div>
		
		<div class="row form-group" id="cash">
			<div class="col-md-4 pull-right">
				<label>Amount Paid</label>
				<input type="text" class="input-sm form-control" name="amountcash" placeholder="00.00" >
			</div>			
		</div>
		<div class="row form-group" id="check">
			<div class="col-md-4">
				<label>Amount Received</label>
				<input type="text" class="input-sm form-control" name="amountreceived"  placeholder="00.00" >
			</div>	
			<div class="col-md-4">
				<label>Check No.</label>
				<input type="text" class="input-sm form-control" name="check"  placeholder="######" >
			</div>	
			<div class="col-md-4">
				<label>Bank of the Check</label>
				<input type="text" class="input-sm form-control" name="bankfcheck" placeholder="######" >
			</div>			
		</div>
		
	</div>
	<div class="col-lg-6 col-md-6">
		<h4>Due as of <?php echo date("F d, Y");?> </h4>
		<?php $date = $this->auth->localdate();
		$now = strtotime($this->auth->localtime());
		$enddate = date("Y-m-d", strtotime($date."+5 day"));
		//$due = $this->Loansmodel->clientpensiondue($pensionid, $enddate);
		//$due = $this->Loansmodel->getOutBalance($client->ClientID);
		$due = $this->Loansmodel->clientdue($_GET['pensionID'], $enddate);
		
		$tmpl = array ('table_open'  => '<table class="table table-striped table-bordered table-condensed table-hover">');
		$this->table->set_template($tmpl);	
		$count=1;
		$total = 0;
		if($due->num_rows() > 0){
			
			$totaldues =0;
			foreach($due->result() as $d){
				$dues = $this->Loans->getPDI($d->DDUE, $d->INSTAMT);
				$duedate = strtotime($d->DDUE);
				$datediff = $now - $duedate;
				$aging =  floor($datediff/(60*60*24));
				if($aging <= 0)
				$aging = 0;
				$totaldues += floatval(str_replace(",","",$dues));
				$this->table->add_row("<input type='checkbox' name='amountdue[".$d->PN."][".$d->schedID."]' class='' value='".$d->INSTAMT."' >", "<a href='".base_url()."client/profile/".$_GET['clientID']."/loan/".$d->loanID."'>".$d->PN, date("M d, Y", strtotime($d->DDUE)), number_format($d->INSTAMT,2), '<input type="text" name="pdi['.$d->PN.']['.$d->schedID.']" value="'.$dues.'" class="form-control input pdi">' );
				$count++;
				$total += $d->INSTAMT;
				
				//echo $totaldues;
			}
			
				$this->table->set_heading("Pay","PN", "Due Date", "Amount Due","PDI");
				$this->table->add_row(array("colspan"=>4, "data"=>"<label>TOTAL PDI</label>"), '<input type="text" class="input-sm form-control pull-right" style="text-align: right; font-weight: bold" name="totaldue" value="'.number_format($totaldues,2).'" readonly>');
				?>
		
			<div class="panel panel-default">
				<?php echo $this->table->generate();?>			
			</div>
			<?php		
		}?>
		<div class="row form-group">
					<div class="col-md-6">
						<h4> TOTAL DUE </h4>
					</div>
					<div class="col-md-6">
						<input type="text" class="input-lg form-control pull-right totaldue" style="text-align: right; font-weight: bold; " name="totaldue" id="totaldue" value="<?php echo $total;?>" readonly>
					</div>
		</div>
	</div>
	
</div>
	<div class="row form-group">
		<div class="col-lg-12">
		<input type="hidden" name="clientID" value="<?php echo $_GET['clientID'];?>">
		<input type="hidden" name="pensionID" value="<?php echo $_GET['pensionID'];?>">
		<input type="button" id="submit" class="btn btn-primary pull-right" value="Save Collection" >
		</div>
	</div>
	
</form>
</div>

<div align="center" id="loader"><img src="<?php echo base_url();?>assets/img/loader-old.gif"></div>

<div class="collectionvalidate">
	
</div>

<script>
	$(document).ready(function(){
		$("#loader").hide();
		$("#submit").on('click', function(){
			$(".collection").hide('slow');
			$("#loader").show();
			setTimeout($("#loader").hide(), 10000);
			$(".collectionvalidate").html('hello');
			//setTimeout($(".collectionvalidate").html("Post Here"), 10);
		});
	});
</script>