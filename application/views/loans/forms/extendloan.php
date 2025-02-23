<script type="text/javascript" src="<?php echo base_url();?>assets/js/modaljs.js"></script>
<script src="<?php echo base_url();?>assets/js/loansprocess.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/jasny-bootstrap.min.js" type="text/javascript"></script>	
<?php
$products = $this->Loansmodel->get_productcodes();
/*echo "<pre>";
print_r($_POST);
echo "</pre>";*/
?>

<form action="<?php echo base_url();?>loans/application/submit" method="post"  id="loanamount">
<div class="col-md-12 form-group row">
<div class="panel panel-success">
	<div class="panel-body">
<table class="table-condensed table-no-bordered" >
	<tr>
		<td width="25%"><label>Select Type of Loan</label>
			<select name="loancode" class="form-control input-sm" id="loancode" >
				 <?php 
					 $count =1;
						foreach ($products->result() as $pro) {            
							
							if($pro->productID.'.'.$pro->LoanCode == $this->input->post('product'))
							$select = 'selected';
							else
							$select = '';
							if($pro->productCode == 'PL')
							echo '<option value="'.$pro->productID.'.'.$pro->LoanCode.'" '.$select.'>'.$pro->productCode.'-'.$pro->LoanCode.'</option>';
						}
					 ?>					
			</select>	
		</td>
		<td  width="25%"><label>Loan Status</label>
			<select name="loanstatus" id='loanstatus'  class="form-control input-sm">					
				<option value="E" <?php if($this->input->post('loanstatus') == 'E') echo "selected";?>>EXTENSION</option>                  
			</select>
		</td>
		<td  width="25%"><label>Monthly Amort. (max. P <?php echo number_format($this->input->post('monthlyPension'),2);?>)</label>
			
			<input type="text" value="<?php echo $this->input->post('monthly');?>" name="monthly" id='monthly'  class="form-control input-sm number">
		</td>
	</tr>
	<tr>											
			
		<td id="pensioninput">
		<input type="hidden" value="principal" name="computation" id='computation'  class="form-control input-sm">	
		<input type="hidden" name="method" id="method" class="form-control input-sm"  value="M"> 
		<input type="hidden" value="<?php echo $this->input->post('monthly');?>" name="monthly" id='monthly'  class="form-control input-sm">	
		<input type="hidden" value="<?php echo $this->input->post('loantype');?>" name="loantype" id='loantype'  class="form-control input-sm">	
		<input type="hidden" name="pid" id="pid" value="">
		<label>Current Term Balance: </label>
		<div class="input-group form">
			<input type="number" value="<?php echo $this->input->post('term');?>" name="currentTerm" id='currentTerm'  class="form-control input-sm">	
			<span class="input-group-addon">Months</span>
		</div>
		</td>
		<td>
			<label>Extend Terms * <code> [ $extendedTerm ] </code>: </label>
			<input type="hidden" name="maxterm" id="maxterm" value="<?php echo $this->input->post('maxTermAv')+ $this->input->post('term');?>" readonly>
			<input type="hidden" name="usedterm" id="usedterm" value="<?php echo $this->input->post('term');?>" readonly>
			<select name="extendedTerm" id="extendedTerm" class="form-control input-sm"  required>
				<option >Select Extension</option>
			<?php $count=1; 
					  $maxterm = 23;							   
					while ($count <= $maxterm) {												
						?> 
				<option value='<?php echo $count;?>' <?php  if($count == $this->input->post('terms')) echo "selected";?>><?php echo $count;?> &nbsp; mo(s) ext.</option>
				<?php $count++;}?>
			</select>
		</td>
		<td>
			<label>Applied Accumulated Terms <code>[ $terms ]</code> : </label>
			 <div class="input-group">				 
					<input type="text" id="terms" name="terms" class="input-sm form-control" value="<?php echo $this->input->post('term');?>" readonly>
					<span class="input-group-addon">Months</span>
			</div>
		</td>
		
		
		
		</tr>
		<tr>
			<td>
				<label> Loan Amount</label>
				<input type="text" id="loanapplied" name="loanapplied" class="input-sm form-control number" value="<?php echo $this->input->post('loanapplied');?>" readonly>
			</td>
			<td colspan='2'>
				<label>Maturity Date of the LAST PN <code>Make sure this is correct to avoid discrepancy.</code></label>
				<input type="date" value="<?php echo $this->input->post('lastDate');?>" name="lastDate" id='lastDate'  class="form-control input-sm">
			</td>
		</tr>
	</table>
	
		<div class="panel panel-success">
			<div class="panel-heading">
			Fee Details
			</div>
			<div class="panel-body"  id="feedetails">
			<?php 
			$this->load->view('loans/forms/feedetails');
			?>
			</div>
		</div>
	</div>
	<div class="panel-footer">
	<input type="hidden" value="<?php echo $this->input->post('clientid');?>" name="clientid" id='clientid'  class="form-control input-sm">	
	<input type="hidden" value="<?php echo $this->input->post('pensionID');?>" name="collateralID" id='collateralID'  class="form-control input-sm">	
	<input type="hidden" value="<?php echo $this->input->post('loanid');?>" name="parentLoan" id='parentLoan'  class="form-control input-sm">
	<input type="hidden" value="<?php echo $this->input->post('branchID');?>" name="branchID" id='branchID'  class="form-control input-sm">
	<input type="submit" name="submit" id="saveloan" value="Save Loan Information" class="btn btn-sm btn-primary">
	</div>
	</div>
	
</div>
 </form>
 <div style="clear:both"></div>
 
 <script>
$(document).ready(function(){
	$("#saveloan").on("click", function(e){
		e.preventDefault();
		var form = $("#loanamount");
		var btn = $(this);
		btn.button('loading');
		bootbox.confirm("Are you sure you want to submit this application?", function(result){
			if(result)
			{
				bootbox.alert("Loan Application will be submitted", function(){
					$.ajax({
						type: "POST",
						url: $('#loanamount').attr('action'), //process to mail
						data: $('#loanamount').serialize(),
						success: function(msg){
							if(msg['stat'] == 1)
								bootbox.alert(msg['data'], function(){
									location.href = msg['url'];									
								});
							else
								bootbox.alert(msg['data']);
							btn.button('reset');
						},
						error: function(msg){
							bootbox.alert(msg['data']);
							btn.button('reset');
						}
					});
				});
				
			}
			else
				btn.button('reset');
		});
	});	
	
});
</script>