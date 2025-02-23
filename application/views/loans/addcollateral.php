<?php
$c = $client->row();
$product = $this->Loansmodel->getproductsbyID($this->session->userdata('pid'))->row();

$loancode =  $product->LoanCode;
$cno = $c->CNO;
$clientid = $c->ClientID;
?>

<form action="" method="post">
<div class="panel panel-primary">
	<div class="panel-heading">
	Loan Collateral Details
	</div>
	<div class="panel-body">	
	<?php
		if(strpos($loancode, 'PL') !== false){
			$collateral = $this->Loansmodel-> get_pensionofclient($clientid, $cno);			
		}else {
			$collateral = $this->Products-> getCollateralsOfClient($clientid);			
		}
	if($collateral->num_rows() > 0)
	{
		echo "<pre>";
		print_r($collateral->result());
		echo "</pre>";
		$disabled = "";
		$checked = "";
		$form = $this->form->collateralForm($this->session->userdata('pid'), $this->session->userdata('loanid'), $product->LoanCode);	
	}else{
		$form = $this->form->collateralForm($this->session->userdata('pid'), $this->session->userdata('loanid'), $product->LoanCode);	
		$disabled= "disabled";
		$checked = "checked";
	}
	?>
	<form>
	<label><input type="radio" name="coll" value="existing" <?php echo $disabled;?>> Existing Collateral</label>
	<br>
		<div id="existingcol">
			<?php echo "<pre>";
		print_r($collateral->result());
		echo "</pre>";?>
		</div>
	<label><input type="radio" name="coll" value="new" <?php echo $checked;?>> New Collateral</label>
		<div id="newcol">
			<?php echo $form;?>
		</div>
	</form>
	
	
	</div>
	<div class="panel-footer">
		<input type="submit" name="submit" value="Save Loan Collateral" class="btn btn-sm btn-primary">
	</div>
</div>
</form>