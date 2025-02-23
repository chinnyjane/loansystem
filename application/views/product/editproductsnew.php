<script type="text/javascript" src="<?php echo base_url();?>assets/js/modaljs.js"></script>
<?php
 /*
if($_POST){
	
	
	echo "<pre>";
	print_r($_POST);
	echo "</pre>";	
}	 
  */
 
 $tmpl = array ('table_open' => '<table class="table table-hover table-condensed table-bordered table-striped">' );
		$this->table->set_template($tmpl);
$product = $this->Loansmodel->getproductsbyID($pid);
if($product->num_rows() > 0){
	foreach ($product->result() as $pro){
		$pcode = $pro->LoanCode;
		$pname = $pro->LoanName;
		$pdesc = $pro->LoanDescription;
		$sub = $pro->LoanSubCode;
		$minA = $pro->minAmount;
		$maxA = $pro->maxAmount;
		$minT = $pro->minTerm;
		$maxT = $pro->maxTerm;
		$penalty = $pro->penalty;
	}
}
  ?>
<form method="post">
 <div class="panel panel-primary"> 
	<div class="panel-heading"><b>Product Definition - <?php echo $pname;?></b></div>
	
	<div class="panel-body">	
	<?php if(isset($errors)) echo "<font color='red'>".$errors.'</font>';
	echo validation_errors("<font color='red'>", '</font>');?>
		 <div class="form-group row">
			<div class="col-md-3"><label>Product Code</label>
				<input id="pcode" name="pcode" type="text" placeholder="Product Code" class="form-control input-sm" value="<?php echo $pcode;?>" required>
			</div>
			<div class="col-md-3"><label>Payment Method</label>
				<select name="psubcode"  class="form-control input-sm">					
					<option value="M" <?php if($sub=="M") echo "selected"; ?>>Monthly</option>
					<option value="L" <?php if($sub=="L") echo "selected"; ?>>Lumpsum</option>
				</select>
			</div>
			<div class="col-md-3"><label>Product Name</label>
				<input id="pname" name="pname" type="text" placeholder="Product Name" value="<?php echo $pname;?>" class="form-control input-sm" required>
			</div>
			<div class="col-md-3"><label>Product Description</label>
				<input id="pdesc" name="pdesc" placeholder="Product Description" class="form-control input-sm" value="<?php echo $pdesc;?>" required>	
			</div>
		</div>
		
		<div class="form-group row">
			<div class="col-md-3">
				<label>Min. Loan Amount</label>
				<div class="input-group">
					<span class="input-group-addon">Php</span>
					<input type="text" class="input-sm form-control" name="minAmount" value="<?php echo $minA;?>" >
				</div>
			</div>
			<div class="col-md-3">
				<label>Max. Loan Amount</label>
				<div class="input-group">
						<span class="input-group-addon">Php</span>
					<input type="text" class="input-sm form-control"  name="maxAmount" value="<?php echo $maxA;?>">
					</div>
			</div>
			<div class="col-md-3">
				<label>Min. Loan Term</label>
				<div class="input-group">						
					<input type="text" class="input-sm form-control"  name="minTerm" value="<?php echo $minT;?>"><span class="input-group-addon">months</span>
				</div>
			</div>
			<div class="col-md-3">
				<label>Max. Loan Term</label>
				<div class="input-group">						
					<input type="text" class="input-sm form-control"  name="maxTerm" value="<?php echo $maxT;?>"><span class="input-group-addon">months</span>
					</div>
			</div>
		</div>
		</div>
		<div class="panel-footer">
		<input type="submit" class="btn btn-sm btn-success" value="Save Product">
	</div>
</div>
</form>
	<div class="row">
	<div class="col-md-12">
	<form action="<?php echo base_url();?>product/overview/editfee" method="post" class="formpost">
	<div class="panel panel-default">
		<div class="panel-heading">	<b>
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne"> <i class="fa fa-caret-down"></i>
          PRODUCT FEES
        </a>
      </b> </div>
	  <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
		<div class="panel-body"><a href="#" data-toggle="modal" data-target="#addfee"><i class="fa fa-plus"></i> New Fee</a></div>
			<?php $this->table->set_heading("#", "Fee Name", "Fee Type", "Fee Value", "Display on Disclosure","Action"); 
				$fees = $this->Loansmodel->getfees($pid); 
				
				if($fees->num_rows() > 0){
					
					$count = 1;
					foreach($fees->result() as $fee){ 
						$act = anchor(base_url()."product/overview/editfee/".$fee->feeID,"<i class='fa fa-pencil'></i>", "title='update' data-toggle='modal' data-target='#editfee'");
						$act .= "&nbsp;";
						$act .= anchor(current_url()."#","<i class='fa fa-times'></i>", "title='remove'");
						
						$feename = '<input type="text" name="fee['.$fee->feeID.'][name]" value="'.$fee->feeName.'" class="table_input form-control" required>';
						
						$comptype = '<select  name="fee['.$fee->feeID.'][type]" class="table_input form-control" required>';
							$comptype .= '<option value="fixed" '; 
								if($fee->comptype == "fixed") $comptype .= "selected"; 
								$comptype .= '>Fixed</option>';
							$comptype .= '<option value="%" ';
								if($fee->comptype == "%") $comptype .= "selected";
								$comptype .= '>%</option>';
							$comptype .= '<option value="formula" '; 
								if($fee->comptype == "formula") $comptype .= "selected";
								$comptype .= '>Formula</option>';					
						$comptype .= '</select>';
						
						$feevalue = '<input type="text" name="fee['.$fee->feeID.'][value]" value="'.$fee->value.'" class="table_input form-control" required>';
						
						
						$display = '<label> <input type="radio" name="fee['.$fee->feeID.'][display]" value="1" ';
							if($fee->display == "1") $display .= "checked";
						$display .= '> Yes </label> &nbsp';
						$display .= '<label> <input type="radio" name="fee['.$fee->feeID.'][display]" value="0" ';
							if($fee->display != "1") $display .= "checked";
						$display .= '> No </label></div>';
						
						$this->table->add_row($count, $feename, $comptype,$feevalue, $display,$act) ;
						$count++;
					}
					
					echo $this->table->generate();
				
				}
			?>
			<div class="panel-footer">
				<button class="btn btn-sm btn-primary">Update Fees</button>
			</div>
		</div>
		</div>
			</div>
	</div>
	<div class="col-md-12">
	<div class="panel panel-success">
		<div class="panel-heading">	<b>REQUIREMENTS</b> </div>
		<div class="panel-body"><a href="#"><i class="fa fa-plus"></i>  New Requirement</a></div>
		<?php 
		$reqs = $this->Loansmodel->getreqs($pid); 
		
		if($reqs->num_rows() > 0){
		
			$count = 1;
			$this->table->set_heading("#", "Requirement", "Required/Optional","Action");
			
			foreach($reqs->result() as $req){
			
				$this->table->add_row($count,$req->requirement,"","" );
				$count++;
				
			}
			echo $this->table->generate();
		}
		
		?>
	</div>
	</div>
</div>


<div class="modal fade" id="addfee" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<?php $this->load->view('product/addfee');?>
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