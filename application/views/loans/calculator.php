<!-- Loan Calculator -->
<script type="text/javascript" src="<?php echo base_url();?>assets/js/modaljs.js"></script>
<script src="<?php echo base_url();?>assets/js/loansprocess.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/jasny-bootstrap.min.js" type="text/javascript"></script>	

<div class="panel panel-default">  
    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
      <div class="panel-body">
        <?php
		$products = $this->Loansmodel->get_productcodes();
		?>
<table class="table-condensed table-no-bordered" >
			<tr>
					<td width="25%"><label>Select Type of Loan</label>
					<select name="loancode" class="form-control input-sm" id="loancode" >
					 <?php 
					 $count =1;
						foreach ($products->result() as $pro) {            
							
							if($pro->LoanCode == $this->input->post('loancode'))
							$select = 'selected';
							else
							$select = '';
							echo '<option value="'.$pro->productID.'.'.$pro->LoanCode.'" '.$select.'>'.$pro->productCode.'-'.$pro->LoanCode.'</option>';
						}
					 ?>	
					 </select>	
				</td>
				<td  width="25%"><label>Loan Status</label>
					<select name="loanstatus" id='loanstatus'  class="form-control input-sm">
						<option value="N" <?php if($this->input->post('loanstatus') == 'N') echo "selected";?>>NEW</option>
						<option value="E" <?php if($this->input->post('loanstatus') == 'E') echo "selected";?>>EXTENSION</option>                    
						<option value="A" <?php if($this->input->post('loanstatus') == 'A') echo "selected";?>>ADDITIONAL</option>
						<option value="R" <?php if($this->input->post('loanstatus') == 'R') echo "selected";?>>RENEWAL</option>                    
					</select>
				</td>
			
				<td  width="25%"><label>Payment Method</label>
				<select name="method" id="method" class="form-control input-sm" > 
					<option value="M" <?php if($this->input->post('method') == 'M') echo "selected";?>>Monthly</option>					
					<option value="L" <?php if($this->input->post('method') == 'L') echo "selected";?>>Lumpsum</option>
				</select></td>
				
			</tr>
			<tr>
				<td>
					<label>Computation</label>
					<select name="computation" id='computation'  class="form-control input-sm">							
						<option value="principal" <?php if($this->input->post('computation') == 'principal') echo "selected";?>>Gross Amount</option>
						<option value="net" <?php if($this->input->post('computation') == 'net') echo "selected";?> >Net Amount</option>					
					</select>
				</td>
				<td id="pensioninput">
						<label>Monthly Pension</label>
						 <div class="input-group">
						 <span class="input-group-addon">Php</span>
						<input type="text" id="pensionamount" name="pensionamount" class="input-sm form-control" value="<?php echo $this->input->post('pensionamount');?>">
						</div>
					</td>
				
				<td>
					<label>Terms * <code> [ $term ] </code>: </label>
						<select name="terms" id="terms" class="form-control input-sm"  required>
						
						<?php $count=1; 
						  $maxterm = 24;							   
						while ($count <= $maxterm) {												
							?> 
							<option value='<?php echo $count;?>' <?php  if($count == $this->input->post('terms')) echo "selected";?>><?php echo $count;?> &nbsp; month(s)</option>
						<?php $count++;}?>
						</select>
				</td>
				<td>
				<label>Enter Loan Amount</label>
				<input type="text" id="loanapplied" name="loanapplied" class="input-sm form-control" value="<?php echo $this->input->post('loanapplied');?>">
				</td>
				<td>
					
				</td>
			</tr>
		</table>
		
      </div>
    </div>
  </div>

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
