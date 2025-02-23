<?php $dis = $this->Cashbalance->getTransactionType("disbursement");

?>
<form class="form-horizontal" method="post" id="checkvoucher">

<div class="col-md-12">
	<div class=" form-group ">
		<div class="col-lg-6 col-md-6">
		<label>Payee Name</label>
		<input type="text" name="particular" class="form-control input" required>
		</div>
		<div class="col-lg-3 col-md-3 ">
			<label>CV No.</label>
			<input type="text" name="reference" id="reference" class="form-control input" required>
		</div>
		<div class="col-lg-3 col-md-3 ">
			<label>Date</label>
			<input type="date" name="dateOfTransaction" value="<?php echo $this->auth->localdate();?>" class="form-control input" required>
		</div>		
	</div>
	<div class=" form-group ">		
		<div class="col-lg-3 col-md-3"><label>Disbursement Type</label>
			<select name="transtype" class="form-control input" required>
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
		<div class="col-lg-3 col-md-3">
			<label>Bank</label>
			<select name="bankID" class="form-control input" required>
				<?php foreach($banks->result() as $ba){
				if(!empty($ba->branchCode))
				$bcode = "-".$ba->branchCode;
				else $bcode = "";
				?>
					<option value="<?php echo $ba->branchBankID;?>"><?php echo $ba->bankCode.$bcode;?></option>
				<?php } ?>
			</select>
		</div>
		<div class="col-lg-3 col-md-3">
			<label>Check No.</label>
			<input type="text" name="checkno" class="form-control input" required>
		</div>
		<div class="col-lg-3 col-md-3">
			<label>PN No.</label>
			<input type="text" name="PN" class="form-control input" required>
		</div>
	</div>
	<div class="form-group">
		<div class="col-lg-12 col-md-12">
			<label>Particulars</label>
			<textarea name="explanation" class="form-control"></textarea>
		</div>
	</div>
</div>
<div class="form-group col-lg-12">
	<table class="table table-striped" id="voucher">
		<thead>
		<tr>
			<th>Account Code</th>
			<th>DR</th>
			<th>CR</th>
			<th>Action</th>
		</tr>
		</thead>
		<tfoot>
			<tr>
				<td><label>TOTAL</label></td>
				<td><input type="text" name="totaldr" id="totaldr" class="form-control input " value="0" readonly></td>
				<td><input type="text" name="totalcr" id="totalcr" class="form-control input "  value="0" readonly></td>
				<td></td>
			
			</tr>
		</tfoot>
		<tr>
			<td><select class="input-sm form-control" name="coa_parent[]">
				<?php 
					if($account){
						foreach($account->result() as $coa): ?>
							<option value="<?php echo $coa->coa_id;?>"><?php echo $coa->coa_codeprefix.$coa->coa_code;?> - <?php echo $coa->coa_name;?></option>
						<?php endforeach;  }?>
				</select></td>
			<td><input type="number" name="dr[]" class="form-control input dr" value="0"></td>
			<td><input type="number" name="cr[]" class="form-control input cr" value="0"></td>
			<td><button class="btn btn-sm btn-warning" type="button" id="add_account">Add</button></td>			
		</tr>
		
	</table>
	
</div>
<div class="form-group col-lg-12">
  <div class="col-md-12">
	<button id="submit" name="submit" class="btn btn-success" >Create CV</button>
    <button id="cancer" name="cancer" class="btn btn-danger">Cancel</button>
  </div>	
</div>

</form>

<script type="text/javascript">
$(document).ready(function() {
	$("#submit").prop('disabled', true);
	
	var totaldr = $("#totaldr").val();
	var totalcr = $("#totalcr").val();
	
	
	$("#checkvoucher").on("change", '.dr',function(){
		
		 var total = 0;
			$('.dr').each(function (index, element) {
				total = total + parseFloat($(element).val());
			});			
		$('#totaldr').val(total.toLocaleString(2));
		
		var totaldr = $("#totaldr").val();
		var totalcr = $("#totalcr").val();
		if(totaldr == totalcr) {
			$("#submit").prop('disabled', false);
		}else{
			$("#submit").prop('disabled', true);
		}
	});
	
	$("#checkvoucher").on("change", '.cr',function(){
		
		 var total = 0;
			$('.cr').each(function (index, element) {
				total = total + parseFloat($(element).val());
			});			
		$('#totalcr').val(total.toLocaleString(2));		
		
		var totaldr = $("#totaldr").val();
		var totalcr = $("#totalcr").val();
		if(totaldr == totalcr) {
			$("#submit").prop('disabled', false);
		}else{
			$("#submit").prop('disabled', true);
		}
	});
	
	$("#add_account").click(function (e) {
			//Append a new row of code to the "#items" div
			$('#voucher').append('<tr>'
				+'<td><select class="input-sm form-control" name="coa_parent[]">'
				<?php 
					if($account){
						foreach($account->result() as $coa): ?>
							+'<option value="<?php echo $coa->coa_id;?>"><?php echo $coa->coa_codeprefix.$coa->coa_code;?> - <?php echo $coa->coa_name;?></option>'
						<?php endforeach;  }?>
				+'</select></td>'
				+'<td><input type="number" name="dr[]" class="form-control input dr" value="0"></td>'
				+'<td><input type="number" name="cr[]" class="form-control input cr" value="0"></td>'
				+'<td></td>'
			+'</tr>');
		});
});
</script>