<?php 
$pro = $product->row();
?>
<div id="msg" style="z-index: 1000">
</div>
<div class="panel panel-default">
<form action="<?php  echo base_url();?>product/overview/update" methodp="post" id="updatepro">	
	<div class="modal-body">
		<div class="form-group row">
			<div class="col-md-6"><label>Product Code</label>
				<input id="pcode" name="pcode" type="text" placeholder="Product Code" class="form-control input-sm" value="<?php echo $pro->productCode;?>" required>
			</div>
			<div class="col-md-6"><label>Product Name</label>
				<input id="pname" name="pname" type="text" placeholder="Product Name" value="<?php echo $pro->productName;?>" class="form-control input-sm" required>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-12"><label>Product Description</label>
				<textarea class="form-control input-sm" name="pdesc" id="pdesc"><?php echo $pro->productDescription;?></textarea>						
			</div>
		</div>
	</div>
	<div class="panel-footer">
		<input type="hidden" name="productID" value="<?php echo $pro->productID;?>">
		<select name="active" class="input-sm">
			<option value="1" <?php if($pro->active == '1') echo 'selected';?>>Active</option>
			<option value="0" <?php if($pro->active == '0') echo 'selected';?>>Inactive</option>			
		</select>
		<input type="submit" name="submit" value="Update Product"  id="upbutton" class="btn btn-sm btn-primary">				
	</div>
</form>
</div>

<ul class="nav nav-tabs" role="tablist" id="myTab">	
	<li class="active"><a href="#products" role="tab" data-toggle="tab"><b>Product Config</b></a></li>
	<li class=""><a href="#requirements" role="tab" data-toggle="tab"><b>Requirements</b></a></li>
    <li><a href="#collaterals" role="tab" data-toggle="tab"><b>Collaterals</b></a></li>
    <li><a href="#ci" role="tab" data-toggle="tab"><b>Credit Investigation and Appraisal</b></a></li>      
    <li><a href="#fees" role="tab" data-toggle="tab"><b>Fees</b></a></li>      
</ul>
<form method="post" id="updateproduct" action="<?php echo base_url();?>product/overview/update_details"> 
<div class="tab-content">
		<div class="tab-pane well active" id="products">
			<?php
			$p['productID']= $pro->productID;
			$p['proname']= $pro->productName;
			$this->load->view("product/overview", $p);
			?>
		</div>
		<div class="tab-pane well " id="requirements">
			<table class="table-condensed table-no-bordered" id="reqlist" width='100%'>
				<thead>
					<tr>
						<th width="10%">Remove</th>
						<th>Requirement</th>
						<th>Optional</th>
					</tr>
				</thead>
				<tbody>
					<?php $reqs = $this->Loansmodel->getreqs($pid); 
					//echo $this->db->last_query();
						if($reqs->num_rows() > 0){
						$count = 1;
							foreach($reqs->result() as $req){?>
								<tr>
									<td><input type="checkbox" name="reqremove[]"  class="case" value="<?php echo $req->reqID;?>" /></td>
									<td><input type="text" class="input-sm form-control" name="req[<?php echo $req->reqID;?>]" value="<?php echo $req->requirement;?>" ></td>
									<td></td>										
								</tr>
							<?php 
							$count++;
							}
						} ?>
				</tbody>
				<tfoot>
					<tr>
						<td><input type="button" id="reqsadd" class="btn btn-sm btn-warning" value="Add Requirement"></td>
					</tr>
				</tfoot>
			</table>			
		</div>
	<div class="tab-pane" id="collaterals">
			<div class=" well panel panel-default">
			<div class="panel-body" id="colbody" >
            	<div class="row form-group">
                	<b>Collaterals Information</b>
                </div>               
                 <div class="row form-group">                	
                    <div class="col-md-3">
                    	<input type="button" id="addcoldetails" class="btn btn-sm" value="Add Details">
                    </div>
                </div>
                <div class="row form-group">                	
                    <div class="col-md-1"><label></label></div>
                    <div class="col-md-1"><label>Primary</label></div>
                    <div class="col-md-3"><label>Collateral detail</label></div>	
                    <div class="col-md-3"><label>Data Type</label></div>  
                     <div class="col-md-1"><label>Remove</label></div>                 
                </div>
			<?php 
             $cols = $this->Products->getProCollaterals($pid);
             if($cols->num_rows() > 0){
                foreach($cols->result() as $col):
					if($col->primary == 1)
					$check = 'checked="checked"';
					else $check ='';
				?>
                <div class="row form-group">                	
                    <div class="col-md-1"><label></label></div>
                    <div class="col-md-1"><input type="radio" name="pri"  class="case" value="<?php echo $col->procolID;?>" <?php echo $check; ?> /></div>
                    <div class="col-md-3"><input type="text" class="input-sm form-control" name="coldetail[]" value="<?php echo $col->collateralname;?>" ></div>	
                    <div class="col-md-3"><input type="text" class="input-sm form-control" name="coltype[]" value="<?php echo $col->datatype;?>" ></div>  
                     <div class="col-md-1"><input type="checkbox" name="colremove[]"  class="case" value="<?php echo $col->procolID;?>" />
                     <input type="hidden" name="colID[]" value="<?php echo $col->procolID;?>" />
                     </div>                 
                </div>
                <?php endforeach;
             }
            ?>
            </div>
            </div>
        </div>
		<div class="tab-pane" id="ci">
			<div class=" well panel panel-default">
				<div class="panel-body" id="cibody">
					<div class="row form-group">
						<input type="button" class="btn btn-sm btn-primary" id="addci" value="Add CI detail">
					</div>
					<div class="row form-group">
						<div class="col-md-1"><label>Remove</label></div>
						<div class="col-md-3"><label>CI Requirement</label></div>	
						<div class="col-md-3"><label>Data Type</label></div>						
					</div>
					
					<?php $cid = $this->Loansmodel->getcidetails($pid);
						if($cid->num_rows() > 0){
							
							
							foreach($cid->result() as $ci){ ?>
								<div class="row form-group">
									<div class="col-md-1"><input type="checkbox" name="ciremove[]"  class="case" value="<?php echo $ci->ci_id;?>" /></div>
									<div class="col-md-3"><input type="text" class="input-sm form-control" name="ci[<?php echo $ci->ci_id;?>][name]" value="<?php echo $ci->ci_name;?>" ></div>	
									<div class="col-md-3"><input type="text" class="input-sm form-control" name="ci[<?php echo $ci->ci_id;?>][type]" value="<?php echo $ci->datatype;?>" ></div>	
								 </div>
							<?php }
						}
					?>
				</div>
				</div>
        </div>
		<div class="tab-pane" id="fees">
			<?php $this->load->view("product/fees", $p); ?>
		</div>
		</div>
<div class="panel-footer">
		<input type="hidden" name="pid" value="<?php echo $pid;?>">		
		<input type="button" class="btn btn-sm btn-success" id="saveproduct" value="Save Product">
	</div>
</form>
<div class="modal fade" id="addpro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
	<div class="modal-dialog">
	<?php $this->load->view('product/addproduct', $p); ?>
	</div>
</div>


<script type="text/javascript" src="<?php echo base_url();?>assets/js/modaljs.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/product.js"></script>

<script>
$(document).ready(function(){
	$('#updatepro').on('submit', function(e){
		e.preventDefault();
		var btn = $('#upbutton');
		btn.button('loading');
		$.ajax({
			type: "POST",
			url: $(this).attr('action'), //process to mail
				data: $(this).serialize(),				
				success: function(msg){					
					if(msg['status'] == 1){
						$("#msg").html('<div class="alert alert-success">'+msg['msg']+'</div>');
					}else{
						$("#msg").html('<div class="alert alert-danger">'+msg['msg']+'</div>');
					}
					$('#msg').fadeIn('fast');
					setTimeout(hide_alert, 1500);
					btn.button("reset");
				},
				error: function(){
					$("#msg").html('<div class="alert alert-danger">Please try again.</div>');
					setTimeout(hide_alert, 1500);
					btn.button("reset");
				}
		});
	});
	
	function hide_alert(){
		$('#msg').fadeOut('slow');
		
	}
	
	$("#reqsadd").click(function (e) {
			//Append a new row of code to the "#items" div
			var req = $("#reqs").val();
			$("#reqlist").append('<tr>'
				+'<td></td>'
				+'<td><input type="text" class="input-sm form-control" name="requirement[]" ></td>'
				+'<td></td>'
				+'</tr>');
			$("#reqs").val('');
		});
	
	$("#addci").click(function (e) {
			//Append a new row of code to the "#items" div
			var req = $("#cibody").val();
			$("#cibody").append('<div class="row form-group"><div class="col-md-1"></div><a class="btn-sm btn-default btn delete"><i class="fa fa-times"></i> Remove</a><div class="col-md-3"><input type="text" class="input-sm form-control" name="ciname[]"  placeholder="CI name" ></div><div class="col-md-3"><input type="text" class="input-sm form-control" name="citype[]" placeholder="Data Type" ></div></div>');
			$("#reqs").val('');
		});
		
		$("#addcoldetails").click(function (e) {
			//Append a new row of code to the "#items" div
			var req = $("#colbody").val();
			$("#colbody").append('<div class="row form-group"><a class="btn-sm btn-default btn delete"><i class="fa fa-times"></i> </a><div class="col-md-1"></div><div class="col-md-1"><input type="checkbox"></div><div class="col-md-3"><input type="text" class="input-sm form-control" name="coldetail[]"  placeholder="Collateral detail" required></div><div class="col-md-3"><select name="coltype[]" class="input-sm form-control"><option value="number">number</option><option value="alphanumeric">Alpha numeric</option></select></div></div>');
			$("#reqs").val('');
		});
	
});
</script>