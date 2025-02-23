<div class=" form-group">
	<button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addproduct" data-backdrop="static"> <i class="fa fa-plus"></i> Add Product Type </button>
</div>

<?php
$tmpl = array ('table_open' => '<table class="table table-hover table-condensed table-bordered">' );
		$this->table->set_template($tmpl);
if($products->num_rows() > 0){
	//$count = 1;
	foreach($products->result() as $pro){
		$this->table->add_row($pro->productID, $pro->productCode, $pro->productName, $pro->productDescription, '<a href="'.base_url().'product/overview/info/'.$pro->productID.'">View Product</a>');
	}
	$this->table->set_heading("#", "Code", "Product Name", "Product Description","Action");
	echo $this->table->generate();
}else{
	echo '<div class=" form-group">'."No Products on record."."</div>";
}
?>

<div class="modal fade" id="addproduct" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="<?php  echo base_url();?>product/overview/add" method="post" id="addproform">
				<div class="modal-header">
					<b>ADD PRODUCT</b>
				</div>
				<div class="modal-body">
					<div class="form-group row">
						<div class="col-md-6"><label>Product Code</label>
							<input id="pcode" name="pcode" type="text" placeholder="Product Code" class="form-control input-sm" value="<?php set_value('pcode', $this->input->post('pcode'));?>" required>
						</div>
						<div class="col-md-6"><label>Product Name</label>
							<input id="pname" name="pname" type="text" placeholder="Product Name" value="<?php set_value('pname', $this->input->post('pname'));?>" class="form-control input-sm" required>
						</div>
					</div>
					<div class="row form-group">
						<div class="col-md-12"><label>Product Description</label>
							<textarea class="form-control input-sm" name="pdesc" id="pdesc"></textarea>						
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
					<input type="submit" name="submit" value="Add Product" id="addbut" class="btn btn-sm btn-primary">				
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
		$('#addproform').on('submit', function(e){
			e.preventDefault();
			$("#addbut").button('loading');
			$.ajax({
				type: "POST",
				url: $(this).attr('action'), //process to mail
				data: $(this).serialize(),				
				success: function(msg){
					$('#addproform').hide();
					if(msg['status'] == 1){
						bootbox.alert(msg['msg'], function(){
							location.reload();
						});
					}else{
						bootbox.alert(msg['msg'], function(){
							$('#addproform').modal('show');
						});
					}
					$("#addbut").button("reset");
				},
				error: function(){
					bootbox.alert("Please try again.");
					$("#addbut").button("reset");
				}
			});
		});
	});
</script>