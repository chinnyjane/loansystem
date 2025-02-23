 <?php
 /*
if($_POST){
	
	
	echo "<pre>";
	print_r($_POST);
	echo "</pre>";	
}	 
  */
$product = $this->Loansmodel->getproductsbyID($pid);
if($product->num_rows() > 0){
	foreach ($product->result() as $pro){
		$pcode = $pro->LoanCode;
		$pname = $pro->LoanName;
		$pdesc = $pro->LoanDescription;
		$minA = $pro->minAmount;
		$maxA = $pro->maxAmount;
		$minT = $pro->minTerm;
		$maxT = $pro->maxTerm;
		$penalty = $pro->penalty;
	}
}
  ?>
  
 <div class="panel panel-green">
 <form method="post">
	<div class="panel-heading">Product Definition - <?php echo $pname;?>
	</div>
	<div class="panel-body">
	
	<?php if(isset($errors)) echo "<font color='red'>".$errors.'</font>';
	echo validation_errors("<font color='red'>", '</font>');?>
		 <div class="form-group row">
			<div class="col-md-3"><label>Product Code</label>
				<input id="pcode" name="pcode" type="text" placeholder="Product Code" class="form-control input-sm" value="<?php echo $pcode;?>" required>
			</div>
			<div class="col-md-3"><label>Product Name</label>
				<input id="pname" name="pname" type="text" placeholder="Product Name" value="<?php echo $pname;?>" class="form-control input-sm" required>
			</div>
			<div class="col-md-3"><label>Product Description</label>
				<input id="pdesc" name="pdesc" placeholder="Product Description" class="form-control input-sm" value="<?php echo $pdesc;?>" required>	
			</div>
		</div>
		
		<!-- Product menu -->
		<ul class="nav nav-tabs" role="tablist" id="myTab">
		  <li class="active"><a href="#details" role="tab" data-toggle="tab">Parameters</a></li>
		  <li><a href="#fees" role="tab" data-toggle="tab">Fees</a></li>
		  <li><a href="#requirements" role="tab" data-toggle="tab">Requirements</a></li>
		
		</ul>
		<!-- Menu Details -->
		<div class="tab-content">
			<div class="tab-pane active" id="details">
			<div class=" well panel panel-default">
				<div class="form-group row">				
					<div class="col-md-2"><label>Min. Loan Amount</label></div>
					<div class="col-md-3"><div class="input-group">
						<span class="input-group-addon">Php</span>
					<input type="text" class="input-sm form-control" name="minAmount" value="<?php echo $minA;?>" >
					</div>
					</div>					
				</div>
				<div class="form-group row">
				<div class="col-md-2"><label>Max. Loan Amount</label></div>
					<div class="col-md-3"><div class="input-group">
						<span class="input-group-addon">Php</span>
					<input type="text" class="input-sm form-control"  name="maxAmount" value="<?php echo $maxA;?>">
					</div></div>
				</div>
				<div class="form-group row">		
					<div class="col-md-2"><label>Min. Loan Term</label></div>
					<div class="col-md-3"><div class="input-group">						
					<input type="text" class="input-sm form-control"  name="minTerm" value="<?php echo $minT;?>"><span class="input-group-addon">months</span>
					</div></div>					
				</div>				
				<div class="form-group row">
				<div class="col-md-2"><label>Max. Loan Term</label></div>
					<div class="col-md-3"><div class="input-group">						
					<input type="text" class="input-sm form-control"  name="maxTerm" value="<?php echo $maxT;?>"><span class="input-group-addon">months</span>
					</div></div>
				</div>
				<div class="form-group row">				
					<div class="col-md-2"><label>Penalty</label></div>
					<div class="col-md-3"><div class="input-group">
						<span class="input-group-addon">Php</span>
					<input type="text" class="input-sm form-control"  name="penalty" value="<?php echo $penalty;?>">
					</div></div>				
				</div>
				
			
			</div>
			</div>
			<div class="tab-pane" id="fees">
				<div class=" well panel panel-default">
				<div class=" panel-body" id="feefields">
				<button class="add" class="btn" ><i class=" fa fa-plus"></i> Add Fee</button>
					<div class="row form-group">
						<div class="col-md-1"><label>Remove</label></div>
						<div class="col-md-3"><label>Fee Name</label></div>
						<div class="col-md-2"><label>Fee type</label></div>
						<div class="col-md-3"><label>Fee Value</label></div>
						<div class="col-md-2"><label>Display on Disclosure</label></div>						
					</div>
					<?php $fees = $this->Loansmodel->getfees($pid); 
					if($fees->num_rows() > 0){
						foreach($fees->result() as $fee){ ?>
						<div class="row form-group">
							<div class="col-md-1">
								<input type="checkbox" name="remove[]"  class="case" value="<?php echo $fee->feeID;?>" />
							</div>
							<div class="col-md-3">
								<input type="text" class="input-sm form-control" placeholder="Fee name" name="fee[<?php echo $fee->feeID;?>][name]" value="<?php echo $fee->feeName;?>" required>
							</div>						
							<div class="col-md-2">
								<select name="fee[<?php echo $fee->feeID;?>][type]" class="input-sm form-control" required>
									<option value="fixed" <?php if($fee->comptype == "fixed") echo "selected";?>>Fixed</option>
									<option value="%" <?php if($fee->comptype == "%") echo "selected";?>>%</option>
									<option value="formula" <?php if($fee->comptype == "formula") echo "selected";?>>Formula</option>
								</select>
							</div>						
							<div class="col-md-3"><input type="text" class="input-sm form-control" placeholder="Fee value" name="fee[<?php echo $fee->feeID;?>][value]"  value="<?php echo $fee->value;?>" required></div>
							<div class="col-md-2">
							<label> <input type="radio" name="fee[<?php echo $fee->feeID;?>][display]" value="1" <?php if($fee->display == "1") echo "checked";?>> Yes </label> &nbsp;
							<label> <input type="radio" name="fee[<?php echo $fee->feeID;?>][display]" value="0" <?php if($fee->display != "1") echo "checked";?>> No </label></div>						
						</div>
						<?php }
					} else {?>
					<div class="row form-group">
						<div class="col-md-1"></div>
						<div class="col-md-3"><input type="text" class="input-sm form-control" placeholder="Fee name" name="feename[]" required></div>						
						<div class="col-md-3"><select name="feetype[]" class="input-sm form-control" required><option value="fixed">Fixed</option><option value="%">%</option><option value="formula">Formula</option></select></div>						
						<div class="col-md-3"><input type="text" class="input-sm form-control" placeholder="Fee value" name="feevalue[]" required></div>						
					</div>
					<?php } ?>
				</div>
				</div>
			</div>
		<div class="tab-pane" id="requirements">
			<div class=" well panel panel-default">
			<div class="panel-body">
				<div class="row form-group">
						<div class="col-md-3"><input type="button" id="reqsadd" class="btn btn-sm" value="Add Requirement"></div>						
				</div>
				<div class="row form-group">
						<div class="col-md-1"><label>Remove</label></div>
						<div class="col-md-1"><label>#</label></div>
						<div class="col-md-5"><label>Requirement</label></div>						
					</div>
				<div  id="reqlist">
						
						<?php $reqs = $this->Loansmodel->getreqs($pid); 
						if($reqs->num_rows() > 0){
						$count = 1;
							foreach($reqs->result() as $req){?>
								<div class="row form-group">
									<div class="col-md-1"><input type="checkbox" name="reqremove[]"  class="case" value="<?php echo $req->reqID;?>" /></div>
									<div class="col-md-1"><?php echo $count;?></div>
									<div class="col-md-5">
										<input type="text" class="input-sm form-control" name="req[<?php echo $req->reqID;?>]" value="<?php echo $req->requirement;?>" >
									</div>
								</div>
							<?php 
							$count++;
							}
						} ?>
				</div>
			</div>
			</div>
			</div>	
						
		</div>	
		
	
	</div>
	<div class="panel-footer">
		<input type="submit" class="btn btn-sm btn-success" value="Save Product">
	</div>
	</form>
</div>

<script type="text/javascript">
      $(document).ready(function () {
         	//when the Add Filed button is clicked
		$(".add").click(function (e) {
			//Append a new row of code to the "#items" div
			$("#feefields").append('<div class="row form-group"><div class="col-md-1"></div><a class="btn-sm btn-default btn delete"><i class="fa fa-times"></i> Remove</a><div class="col-md-3"><input type="text" class="input-sm form-control" placeholder="Fee name" name="feename[]" required></div><div class="col-md-3"><select name="feetype[]" class="input-sm form-control" required><option value="fixed">Fixed</option><option value="%">%</option><option value="formula">Formula</option></select></div><div class="col-md-3"><input name="feevalue[]" type="text" class="input-sm form-control" placeholder="Fee value" required></div>	</div>');
		});

		$("body").on("click", ".delete", function (e) {
			$(this).parent("div").remove();
		}); 

		$("#reqsadd").click(function (e) {
			//Append a new row of code to the "#items" div
			var req = $("#reqs").val();
			$("#reqlist").append('<div class="row form-group"><div class="col-md-1"></div><a class="btn-sm btn-default btn delete"><i class="fa fa-times"></i> Remove</a><div class="col-md-3"><input type="text" class="input-sm form-control" name="requirement[]" required></div></div>');
			$("#reqs").val('');
		});
	
	})
    </script>