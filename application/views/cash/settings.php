<?php echo validation_errors(); 
$data = array("active"=>1);
$transcat = $this->Loansmodel->get_data_from("transcategory", $data);
$tmpl = array ('table_open' => '<table class="table table-bordered table-condensed table-hover">' );
$this->table->set_template($tmpl);
?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/modaljs.js"></script>
<div class="panel panel-default">
	<div class="panel-heading">
		<b>CMC Settings</b>
	</div>
	<!-- LINKS -->
		<ul class="nav nav-tabs" id="myTab">
			<li class="active"><a href="#transcat" data-toggle="tab">Transaction Category</a></li>
			<li><a href="#transtype" data-toggle="tab">Transaction Type</a></li>
			<li><a href="#paymenttype" data-toggle="tab">Payment Type</a></li>			
		</ul>
		<div class="tab-content">	
	  <div class="tab-pane active" id="transcat">
		  <div class="panel panel-info">
				<div>
				<form action="" method="post">
				<div class="form-group">
					<div class="col-md-3">
						<input type="text" name="transcat" class="input-sm form-control">
					</div>
					<div class="col-md-3">
						<input type="submit" name="submit" value="Add Transaction Category" class="btn btn-sm btn-default">
					</div>
				</div>
				</form>
				</div>
				<div class="table-response">	
				<?php
				if($transcat->num_rows() > 0){
					echo $this->table->set_heading("#", "Transaction Category","Action");
					$count = 1;
					foreach ($transcat->result() as $trans ){
						echo $this->table->add_row($count, $trans->transCatName,"");
						$count++;
					}
					echo $this->table->generate();
				}else echo "no transaction category yet";
				?>
			</div>
		  </div>
	  </div>
	  <div class="tab-pane" id="transtype">
		  <div class="panel panel-info">
			<div class="panel-body">
				<form action="" method="post">
				<input type="text" name="transaction" required>
				<select name="transcat" required>
					<option disabled selected>Choose Category</option>
					<?php if($transcat->num_rows() > 0){	
					foreach ($transcat->result() as $trans ){
						echo "<option value='".$trans->transCatID."'>".$trans->transCatName."</option>";
					}	
					} ?>
				</select>
				<input type="submit" name="submit" value="Add Transaction Type">
				</form>
				<h1>Transaction Type</h1>
				<?php 
				$data = array("active"=>1);
				$transaction = $this->Loansmodel->get_data_from("transactiontype", $data);
				if($transaction->num_rows() > 0){
					echo $this->table->set_heading("#", "Transaction Type","Action");
					$count = 1;
					foreach ($transaction->result() as $trans ){
						echo $this->table->add_row($count, $trans->transType,"");
						$count++;
					}
					echo $this->table->generate();
				}else echo "no transaction type yet";
				?>
			</div>
		  </div>
	  </div>
	  <div class="tab-pane" id="paymenttype">
		  <div class="panel panel-info">
			<div class="panel-body">
				<form action="" method="post">
				<input type="text" name="payment">
				<input type="submit" name="submit" value="Add Payment Type">
				</form>
				<h1>Payment Type</h1>
				<?php 
				$data = array("active"=>1);
				$payment = $this->Loansmodel->get_data_from("paymenttype", $data);
				if($payment->num_rows() > 0){
					echo $this->table->set_heading("#", "Payment Type","Action");
					$count = 1;
					foreach ($payment->result() as $trans ){
						echo $this->table->add_row($count, $trans->typeOfPayment,"");
						$count++;
					}
					echo $this->table->generate();
				}else echo "no transaction type yet";
				?>
				
			</div>
		  </div>
	  </div>
	</div>
</div>





