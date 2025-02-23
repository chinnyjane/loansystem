<?php
$c = $client->row();
$products = $this->Loansmodel->get_productcodes();
?>

<table class="table-condensed table-no-bordered" >
<?php if(isset($c)){ ?>
			<tr>
				<td colspan='3'><label>Client's Name: </label> &nbsp; <br/><a href="<?php echo base_url();?>client/profile/<?php echo $c->ClientID;?>" class="btn btn-success"><?php echo $c->LastName.", ".$c->firstName;?></a>			</td>
			</tr>
<?php }?>
			<tr>
					<td width="25%"><label>Select Type of Loan</label>
					<select name="loancode" class="form-control input" id="loancode" >
					<option>Select Type of Loan</option>
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
					<select name="loanstatus" id='loanstatus'  class="form-control input">
						<option value="N" <?php if($this->input->post('loanstatus') == 'N') echo "selected";?>>NEW</option>						          
						<option value="A" <?php if($this->input->post('loanstatus') == 'A') echo "selected";?>>ADDITIONAL</option>
						<option value="R" <?php if($this->input->post('loanstatus') == 'R') echo "selected";?>>RENEWAL</option>                    
					</select>
				</td>
			
				<td  width="25%"><label>Payment Method</label>
				<select name="method" id="method" class="form-control input" > 
					<option value="M" <?php if($this->input->post('method') == 'M') echo "selected";?>>Monthly</option>					
					<option value="L" <?php if($this->input->post('method') == 'L') echo "selected";?>>Lumpsum</option>
				</select></td>
				<td>					
					<label> Compute based on:</label>
					<select name="computation" id='computation'  class="form-control input">							
						<option value="principal" <?php // if($this->input->post('computation') == 'principal') echo "selected";?>>Gross Amount</option>
						<option value="net" <?php // if($this->input->post('computation') == 'net') echo "selected";?> >Net Amount</option>					
					</select>
				</td>
			</tr>
			<tr>
				
				<td id="pensioninput">
						<label>Monthly Pension/Amort.</label>
						 <div class="input-group">
						 <span class="input-group-addon">Php</span>
						<input type="text" id="pensionamount" name="pensionamount" class="input form-control number" value="<?php echo $this->input->post('pensionamount');?>">
						</div>
				</td>
				
				<td>
					<label>Terms * <code> [ $term ] </code>: </label>
						<select name="terms" id="terms" class="form-control input"  >
						
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
				<input type="text" id="loanapplied" name="loanapplied" class="input form-control number" value="<?php echo $this->input->post('loanapplied');?>" required>
				</td>
				<td>
					
				</td>
			</tr>
		</table>
		
    	