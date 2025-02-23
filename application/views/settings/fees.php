<?php
$account =$this->Accounting->ChartOfAccounts();
$where = array("active"=>1);
$transtype = $this->Loansmodel->get_data_from("transcategory", $where);
$data = array("*");
$where = array("active"=>1);
$loantype = $this->Products->get($data, $where);
$tmpl = array ('table_open'  => '<table class="table table-striped table-bordered table-condensed table-hover">');
$this->table->set_template($tmpl);	
?>
<div class="panel panel-default with-nav-tabs	">
	<div class="panel-heading" style="margin-bottom: 0px; padding-bottom:0px;">	
		<ul class="nav nav-tabs" style="margin-bottom: 0px;">
			<li class="active"><a data-toggle="tab" href="#all" ><i class="fa fa-user"></i> All</a></li>
			<li ><a data-toggle="tab" href="#collections" > Collections</a></li>  
			<li ><a data-toggle="tab" href="#disbursements" > Disbursements</a></li>  
			<li ><a data-toggle="tab" href="#adjustments" > Adjustments</a></li>  
			<li><button class="btn btn-warning" data-toggle="modal" data-target="#addfee">Add Fee</button></li>
		</ul>
		
	</div>
	<div class="tab-content">
		<div class="tab-pane active" id="all">
			<div class="panel-body">
			<?php $allfees = $this->Fees->getall();
			/*echo $this->db->last_query();
			echo "<pre>";
			print_r($allfees);
			echo "</pre>";*/
			if($allfees->num_rows() > 0){
				$count = 1;
				foreach($allfees->result() as $af){
					
					$this->table->add_row($count, $af->fee_name, $af->coa_code);
					$count++;
				}
				echo $this->table->generate();
			}
			?>
			</div>
		</div>
		<div class="tab-pane " id="collections">
			<div class="panel-body">
			<?php $allfees = $this->Fees->getFeeByTrans(1);
			//echo $this->db->last_query();
			if($allfees->num_rows() > 0){
				$count = 1;
				foreach($allfees->result() as $af){
					$this->table->add_row($count, $af->fee_name, $af->coa_code);
					$count++;
				}
				echo $this->table->generate();
			}
			?>
			</div>
		</div>
		<div class="tab-pane " id="disbursements">
			<div class="panel-body">
			<?php $allfees = $this->Fees->getFeeByTrans(2);
			if($allfees->num_rows() > 0){
				$count = 1;
				foreach($allfees->result() as $af){
					$this->table->add_row($count, $af->fee_name, $af->coa_code);
					$count++;
				}
			}
			?>
			</div>
		</div>
		<div class="tab-pane " id="adjustments">
			<div class="panel-body">
			<?php $allfees = $this->Fees->getFeeByTrans(4);
			if($allfees->num_rows() > 0){
				$count = 1;
				foreach($allfees->result() as $af){
					$this->table->add_row($count, $af->fee_name, $af->coa_code);
					$count++;
				}
				echo $this->table->generate();
			}
			?>
			</div>
		</div>
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
				<div class="col-lg-4 col-md-4">
					<label>Transaction Type</label>
					<select name="transtype" class="form-control input-sm">
						<?php foreach($transtype->result() as $tr){ ?>
							<option value="<?php echo $tr->transCatID;?>"><?php echo $tr->transCatName;?></option>
						<?php }?>
					</select>
				</div>
				<div class="col-lg-4 col-md-4">
					<label>Product Type</label>
					<select name="productID" class="form-control input-sm">
						<option value="">None</option>
						<?php foreach($loantype->result() as $loan){ ?>
						<option value="<?php echo $loan->productID;?>"><?php echo $loan->productCode;?></option>
						<?php }?>
					</select>
				</div>
				<div class="col-lg-4 col-md-4">
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