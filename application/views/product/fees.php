<div class="panel panel-default">	
	<div class="panel-heading">
	<button class="btn btn-warning" data-toggle="modal" data-target="#addfee">Add Fee</button>
	</div>
	<div class="panel-body">
<?php
$where = "fees.productID = ".$productID;
$fees = $this->Fees->getFee($where);
$account =$this->Accounting->ChartOfAccounts();
$where = array("active"=>1);
$transtype = $this->Loansmodel->get_data_from("transcategory", $where);
$data = array("*");
$where = array("active"=>1);
$loantype = $this->Products->get($data, $where);
/*
echo "<pre>";
print_r($fees->result());
echo "</pre>";
*/
$tmpl = array ('table_open' => '<table class="table table-hover table-condensed table-bordered">' );
$this->table->set_template($tmpl);

if($fees->num_rows() > 0 ) {
	$c = 1;
	foreach ($fees->result() as $f) {
		$this->table->add_row($c, $f->transCatName, $f->coa_code, $f->fee_name, $f->coa_name, strtoupper($f->dc));
		$c++;
	}
	
	$this->table->set_heading("#", "transaction","Account Code","Fee Name", "Account Name", "DR/CR");
	echo $this->table->generate();
}
?>
	</div>
</div>

<div class="modal fade" id="addfee" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
	<div class="modal-content">
	<form action="<?php echo base_url();?>product/fee/add" method="post" id="feeform">
		<div class="modal-header">
			<h4>Add Fee</h4>
		</div>
		<div class="modal-body">		
			<div class="row form-group">
				<div class="col-lg-4 col-md-6">
					<label>Fee Name</label>
					<input type="text" name="feename" class="form-control input-sm">			
				</div>
				<div class="col-lg-4 col-md-6">
					<label>Gl Account</label>
					<select name="accountid" class="form-control input-sm">
						<?php foreach($account->result() as $ac){ ?>
						<option value="<?php echo $ac->coa_id;?>"><?php echo $ac->coa_name." - ".$ac->coa_code;?></option>
						<?php } ?>
					</select>
				</div>
				<div class="col-lg-4 col-md-6">
					<label>Variable Name:</label>
					<input type="text" name="var_name" class="form-control input-sm">
				</div>
				<div class="col-lg-4 col-md-6">
					<label>Transaction Type</label>
					<select name="transtype" class="form-control input-sm">
						<?php foreach($transtype->result() as $tr){ ?>
							<option value="<?php echo $tr->transCatID;?>"><?php echo $tr->transCatName;?></option>
						<?php }?>
					</select>
				</div>
				<div class="col-lg-4 col-md-6">
					<label>Product Type</label>
					<input name="productID" type="hidden" name="productID" value="<?php echo $productID;?>" >
					<input type="text" value="<?php echo $proname;?>" class="form-control" readonly>
					
				</div>
				<div class="col-lg-4 col-md-6">
					<label>DR/CR</label>
					<select name="dc" class="form-control input-sm">
						<option value="dr">DR</option>
						<option value="cr">CR</option>
					</select>
				</div>
			</div>
		
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-primary" id="addbutton">Add Fee</button>
		</div>
	</form>
	</div>
 </div>
</div>
<script>
	$(document).ready(function(){
		$('#addbutton').on('click', function(){
			var btn = $(this);
			btn.button('loading');
			var form_url = $('#feeform').attr('action');
			$.ajax({
				type: "POST",
					url: form_url, //process to mail
					data: $('#feeform').serialize(),
					success: function(msg){
						$(".modal").modal('hide');
						if(msg['stat'] == '1')
							bootbox.alert('ok',  function(){
								location.reload(true);
							});
						else bootbox.alert(msg['msg'], function(){
							$(this).hide();
						});
						btn.button('reset');
					},
					error: function(msg){
						bootbox.alert(msg);
						btn.button('reset');
					}
			});
			
		});
	});
</script>