 <?php
 
if($product->num_rows() > 0){
	$p = $product->row();
	foreach ($product->result() as $pro){
		$pcode = $pro->LoanCode;
		$pname = $pro->LoanName;
		$pdesc = $pro->LoanDescription;
		$minA = $pro->minAmount;
		$maxA = $pro->maxAmount;
		$minT = $pro->minTerm;
		$maxT = $pro->maxTerm;
		$penalty = $pro->penalty;
		$sub = $pro->LoanSubCode;
		$pterm = $pro->PaymentTerm;
		$pcom = $pro->computation;
		$proID = $pro->productID;
	}
}
$where = "fees.productID = ".$proID." and transCatName='disbursement' ";
$fee_account = $this->Fees->getFee($where);
$fees = $this->Loansmodel->getfees($pid); 
$branches = $this->UserMgmt->get_branches();
$users =  $this->UserMgmt->get_users(NULL, 0);
  ?>
  
 <div class="panel panel-green">
 <form method="post" id="updateproductform" action="<?php echo base_url();?>product/overview/updateproduct"> 
	<div class="panel-heading">Product Definition - <?php echo $pname; ?>
	</div>
	<div class="panel-body">
	<table class="table-condensed table-no-bordered" width="100%">
		<tr>
			<td>Product Name: <br/>
			<!--<input id="pname" name="pname" type="text" placeholder="Product Name" value="<?php echo $pname;?>" class="form-control input-sm" required>-->
			<select name="productID"  class="form-control input-sm">
			<?php
			if($products->num_rows() > 0){
				foreach($products->result() as $prod){
					if($proID == $prod->productID)
						$select = 'selected';
					else $select = '';
					echo '<option value="'.$prod->productID.'" '.$select.'>'.$prod->productName.'</option>';
				}
			}
			?>
			</select>
			</td>
			<td>Product Sub Code:<br/>
			<input id="pcode" name="pcode" type="text" placeholder="Product Code" class="form-control input-sm" value="<?php echo $pcode;?>" required>
			</td>
			<td> Product Description<br/>
				<input id="pdesc" name="pdesc" placeholder="Product Description" class="form-control input-sm" value="<?php echo $pdesc;?>" required>	
			</td>
			
		</tr>
		<tr>
			<td>Loan Status<br/>
				<select name="psubcode"  class="form-control input-sm">
                	<option value="N" <?php if($sub=="N") echo "selected"; ?>>NEW</option>
                    <option value="E" <?php if($sub=="E") echo "selected"; ?>>EXTENSION</option>                    
                    <option value="A" <?php if($sub=="A") echo "selected"; ?>>ADDITIONAL</option>
                    <option value="R" <?php if($sub=="R") echo "selected"; ?>>RENEWAL</option>                    
                </select>
			</td>
			<td>Payment Method<br/>				
			<select name="paymentterm"  class="form-control input-sm">					
					<option value="M" <?php if($pterm=="M") echo "selected"; ?>>Monthly</option>
					<option value="SM" <?php if($pterm=="SM") echo "selected"; ?>>Semi-Monthly</option>
					<option value="L" <?php if($pterm=="L") echo "selected"; ?>>Lumpsum</option>
				</select></td>
			<td>Computation based on<br/>				
			<select name="computation"  class="form-control input-sm">					
					<option value="principal" <?php if($pcom=="principal") echo "selected"; ?>>Principal Amount</option>
					<option value="net" <?php if($pcom=="net") echo "selected"; ?>>Net Amount</option>					
				</select></td>
			
		</tr>
	</table>
	
	
	<?php if(isset($errors)) echo "<font color='red'>".$errors.'</font>';
	echo validation_errors("<font color='red'>", '</font>');?>
		
		
		<!-- Product menu -->
		<ul class="nav nav-tabs" role="tablist" id="myTab">
		  <li class="active"><a href="#details" role="tab" data-toggle="tab"><b>Parameters</b></a></li>
		  <li><a href="#fees" role="tab" data-toggle="tab"><b>Fees</b></a></li>		  
          <li><a href="#interest" role="tab" data-toggle="tab"><b>Interest Rates</b></a></li>
		  <li><a href="#role" role="tab" data-toggle="tab"><b>Roles Assignment</b></a></li>
		</ul>
		<!-- Menu Details -->
		<div class="tab-content">
			<div class="tab-pane active well" id="details">
				<table class="table-condensed table-no-bordered" width="100%">
					<tr>
						<td>Min. Loan Amount: <br/>
							<div class="input-group">
								<span class="input-group-addon">Php</span>
								<input type="text" class="input-sm form-control" name="minAmount" value="<?php echo $minA;?>" >
							</div>
						</td>
						<td>Max. Loan Amount: <br/>
							<div class="input-group">
								<span class="input-group-addon">Php</span>
								<input type="text" class="input-sm form-control"  name="maxAmount" value="<?php echo $maxA;?>">
							</div>
						</td>
						<td>Min. Loan Term <br/>
							<div class="input-group">						
								<input type="text" class="input-sm form-control"  name="minTerm" value="<?php echo $minT;?>"><span class="input-group-addon">months</span>
							</div>
						</td>
						<td>Max. Loan Term <br/>
							<div class="input-group">						
								<input type="text" class="input-sm form-control"  name="maxTerm" value="<?php echo $maxT;?>"><span class="input-group-addon">months</span>
							</div>
						</td>
					</tr>
				</table>				
			</div>
			
			
			<div class="tab-pane well " id="fees">
				<table class="table-condensed table-no-bordered "  id="feefields" width="100%">
					<thead>
						<tr>
							<th>Remove</th>
							<th>Fee Name</th>
							<th>Fee type</th>
							<th>Fee Value</th>
							<th>Charge Type</th>
							<th>Display on Disclosure</th>
							<th>Upfront</th>
						</tr>
					</thead>
					<tbody>
					<?php 
					if($fees->num_rows() > 0){
						$charges = $this->Products->getCharges();
						foreach($fees->result() as $fee){ ?>
						<tr>
							<td><input type="checkbox" name="remove[]"  class="case" value="<?php echo $fee->feeID;?>" /></td>
							<td>
							<?php if($fee->fee_account_id == null) { ?>
							<select name="fee[<?php echo $fee->feeID;?>][fee_account_id]" class="input-sm form-control">
								<?php foreach($fee_account->result()  as $fa) {
									echo "<option value='".$fa->id."'>".$fa->fee_name."</option>";									
								}?>
							</select>
							<input type="text" class="input-sm form-control" placeholder="Fee name" name="fee[<?php echo $fee->feeID;?>][name]" value="<?php echo $fee->feeName;?>" > 
							<?php }else{ 
								echo $fee->fee_name;
							}?>
							</td>
							<td><select name="fee[<?php echo $fee->feeID;?>][type]" class="input-sm form-control" required>
									<option value="fixed" <?php if($fee->comptype == "fixed") echo "selected";?>>Fixed</option>
									<option value="%" <?php if($fee->comptype == "%") echo "selected";?>>%</option>
									<option value="formula" <?php if($fee->comptype == "formula") echo "selected";?>>Formula</option>
								</select></td>
							<td><input type="text" class="input-sm form-control" placeholder="Fee value" name="fee[<?php echo $fee->feeID;?>][value]"  value="<?php echo $fee->value;?>" ></td>
							<td><select name="fee[<?php echo $fee->feeID;?>][charge]" class="input-sm form-control"><?php 
									if($charges->num_rows() > 0){
										$count = 1;
										foreach($charges->result() as $ch){
											if($fee->charge_type_ID == $ch->id) $select = "selected";
											else $select = '';
											echo "<option value='".$ch->id."' ".$select.">".$ch->charge_name."</option>";
										}
									}?></select></td>
							<td><label> <input type="radio" name="fee[<?php echo $fee->feeID;?>][display]" value="1" <?php if($fee->display == "1") echo "checked";?>> Yes </label> &nbsp;
							<label> <input type="radio" name="fee[<?php echo $fee->feeID;?>][display]" value="0" <?php if($fee->display != "1") echo "checked";?>> No </label></td>
							<td><label> <input type="radio" name="fee[<?php echo $fee->feeID;?>][upfront]" value="add" <?php if($fee->upfront == "add") echo "checked";?>> Add On </label> &nbsp;
							<label> <input type="radio" name="fee[<?php echo $fee->feeID;?>][upfront]" value="deduct" <?php if($fee->upfront == "deduct") echo "checked";?>> Deductable </label></td>
						</tr>
						<?php }
					} ?>							
					</tbody>
					
				</table>
				
				</div>
		
        
		<div class="tab-pane well" id="interest">
            	<h4>Interest Rates</h4>
				<hr/>
				<table class="table-condensed table-no-bordered" id="interesttable">
					<thead>
						<tr>
							<th>Terms</th>
							<th>Interest</th>
							<th>Remove</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							$interest = $this->Products->getInterestByPID($pid);	
							$lastterm = 0;
							if($interest->num_rows() >0){	
							
								foreach($interest->result() as $ir):?>									
									<tr>
										<td>
										<input type="hidden" name="interestID[]" value="<?php echo $ir->interestID;?>">
										<input type='number' name='term[<?php echo $ir->interestID;?>]'  value="<?php echo $ir->term;?>" class='input-sm form-control'  ></td>
										<td><input type='number' name='interest[<?php echo$ir->interestID;?>]'  value="<?php echo $ir->interest;?>" class='input-sm form-control'  ></td>
										<td><input type="checkbox" name="intremove[<?php echo $ir->interestID;?>]"  class="case" value="1" /></td>
									</tr>									
								<?php 
									$lasterm = $ir->term+1;	
								endforeach;	
							}	else{
								$lasterm = $minT;
							}
						?>
						<tr>
							<td><input type='number' name='newterm[]'  value="<?php echo $lasterm;?>" class='input-sm form-control'  ></td>
							<td><input type='number' name='newinterest[]'  class='input-sm form-control'  ></td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<td><button type="button" id="addint" class="btn btn-sm btn-warning">Add Interest</button></td>
						</tr>
					</tfoot>
				</table>
                
            </div>
			<div class="tab-pane" id="role">
            <?php 
				 $roles = $this->Products->allLoanApproval($pid);
				 $branches = $this->UserMgmt->get_branches();
				 $users =  $this->UserMgmt->get_users(NULL, 0);
				 
				?>
				<div class="panel">
					<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-backdrop="static"  data-target="#roleuser" href="#">Add Authorized User</button><br/><br/>
					<?php
					$tmpl = array ('table_open'   => '<table class="table  table-bordered " >'); 
					$this->table->set_template($tmpl);
						if($roles->num_rows() > 0){
							$count =1;							
							foreach($roles->result() as $role){
								if($role->branchID == 0)
								$branch = "All branches";
								else
									$branch = $role->branchname;
								$this->table->add_row($count, $role->lastname.", ".$role->firstname,$branch, number_format($role->fromAmount,2), number_format($role->toAmount,2));
								$count++;
							}
							$this->table->set_heading("#", "Employee Name","Branch", "Approve Amount from", "Approve Amount to ");
							echo $this->table->generate();
						}
					?>
				</div>
        	</div>

            </div>	
		
		
	</div>
	<div class="panel-footer">
		<input type="hidden" name="pid" value="<?php echo $pid;?>">
		<select name="active" class="input-sm">
			<option value="1" <?php if($p->active == '1') echo 'selected';?>>Active</option>
			<option value="0" <?php if($p->active == '0') echo 'selected';?>>Inactive</option>			
		</select>
		<?php //echo $p->dateModified;?>
		<input type="submit" class="btn btn-sm btn-success" id="savepro" value="Save Product">
	</div>
	</form>
</div>
<div class="modal fade" id="roleuser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">		
		<div class="modal-content">
			<div class="modal-header">
				Add User for Approval
			</div>
			<form action="<?php echo base_url();?>product/overview/addrole" method="post" id="productroleform">
			<div class="modal-body">
				<table class="table table-condensed table-bordered ">
					<tr>
						<td width="30%">Choose Branch:</td>
						<td><select name="branchID" class="input-sm form-control">
						<option value="0">All branches</option>
						<?php foreach ($branches->result() as $b): ?>
							<option value="<?php echo $b->id;?>"><?php echo $b->branchname;?></option>
						<?php endforeach;?></select>
						</td>
					</tr>
					<tr>
						<td>Assign User to Approve:</td>
						<td><select name="userID" class="input-sm form-control">
						<?php foreach ($users->result() as $b): ?>
							<option value="<?php echo $b->id;?>"><?php echo $b->lastname.", ".$b->firstname;?></option>
						<?php endforeach;?></select></td>
					</tr>
					<tr>
						<td>Approve Amount From: </td>
						<td><div class="input-group"><span class="input-group-addon">Php</span><input type="text" name="fromAmount" class="input-sm form-control" required/></div></td>
					</tr>
					<tr>
						<td>Approve Amount To: </td>
						<td><div class="input-group"><span class="input-group-addon">Php</span><input type="text" name="toAmount" class="input-sm form-control" required/></div></td>
					</tr>
				</table>
			</div>
			<div class="modal-footer">
				<input type="hidden" name="pid" value="<?php echo $pid;?>">
				<button class="btn btn-sm btn-primary" type="button" id="addrole">Add User</button> &nbsp; <button  class="btn btn-sm bt-default" data-dismiss="modal" data-toggle="close">Close</button>
			</div>
			</form>
		</div>
	</div>
</div>


<script type="text/javascript" src="<?php echo base_url();?>assets/js/modaljs.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/product.js"></script>
<script type="text/javascript">
      $(document).ready(function () {
		  
		 $("#savepro").on("click", function(e){
			 e.preventDefault();
			 var btn = $(this)
			 btn.button('loading');
			 $.ajax({
				type: "POST",
				url: $("#updateproductform").attr('action'), //process to mail
				data: $("#updateproductform").serialize(),				
				success: function(msg){
					bootbox.alert(msg);
					btn.button("reset");
				},
				error: function(){
					bootbox.alert("Please try again.");
					btn.button("reset");
				}
			 });
		 })
         	//when the Add Filed button is clicked
		$(".add").click(function (e) {
			//Append a new row of code to the "#items" div
			$("#feefields").append('<tr>'
				+'<td></td>'
				+'<td><input type="text" class="input-sm form-control" placeholder="Fee name" name="feename[]" ></td>'
				+'<td><select name="feetype[]" class="input-sm form-control" required><option value="fixed">Fixed</option><option value="%">%</option><option value="formula">Formula</option></select></td>'
				+'<td><input name="feevalue[]" type="text" class="input-sm form-control" placeholder="Fee value" ></td>'
				+'<td></td>'
				+'<td></td>'
				+'<tr>');
		});

		$("body").on("click", ".delete", function (e) {
			$(this).parent("div").remove();
		}); 
		
		
		$("#addint").click(function (e) {
			//Append a new row of code to the "#items" div
			$("#interesttable").append('<tr>'
				+"<td><input type='number' name='newterm[]'  class='input-sm form-control'  ></td>"
				+"<td><input type='number' name='newinterest[]'  class='input-sm form-control'  ></td>"
				+'</tr>');
			
		});
		
		
	
	})
    </script>