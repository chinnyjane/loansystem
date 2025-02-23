<?php if($this->auth->perms("Branches.Banks",$this->auth->user_id(),2) == true) { ?>
<script src="<?php echo base_url();?>assets/js/modaljs.js"></script>
<?php 
if($branch->num_rows() >0){
	foreach($branch->result() as $br){
		$branchname = $br->branchname;
	}
$tmpl = array ('table_open'          => '<table class="table table-bordered">' );
$this->table->set_template($tmpl);
echo validation_errors();
?>
<?php if(isset($success)) echo '<div class="alert alert-success">'.$success.'</div>';
elseif(isset($error)) echo '<div class="alert alert-danger">'.$error.'</div>';
  ?>
<form method="post" id="updateform" action="<?php echo base_url();?>cash/branches/details/<?php echo $branchid;?>">
<?php //echo current_url();?>
  <div class="panel panel-info"><div class="panel-heading"><?php echo $branchname;?>  Banks &nbsp;<?php if($this->auth->perms("Branches.Banks",$this->auth->user_id(),1) == true) { ?><button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#myModal">Add New Bank Account</button><?php } ?></div>
		<?php if($banks->num_rows() >0) {   ?>
		<div class="table-responsive">
			<?php 
			$this->table->set_heading('<input class="check-all" type="checkbox" />','#', 'Bank Code','Bank Account', 'Bank Branch', 'Bank Address', 'Beginning Balance', 'Beginning Date');
			$count = 1;
			$total = 0;
			foreach($banks->result() as $ba){
				if(!empty($ba->branchCode))
				$bcode = "-".$ba->branchCode;
				else $bcode = "";
				$this->table->add_row('<input type="checkbox" name="checked[]"  class="case" value="'.$ba->branchBankID.'" />',$count, $ba->bankCode.$bcode,  '<a href="'.base_url().'cash/branches/bankAccount/'.$branchid.'/'.$ba->branchBankID.'">'.$ba->bankAccount.'</a>',   $ba->bankBranch, $ba->bankAddress,  number_format($ba->BeginBalance,2),  $ba->BeginDate );
				//$total += $ba->bankBalance;
				//$total = $ba->TotalBal;
				$count++;
			}
			$total = $this->Cashmodel->getbanktotal($branchid);
			$t = $total->row()->TotalBal;
			$t = $t ? $t : 0;
			$this->table->add_row('', '<b>Total Cash in Bank</b>', '','','','',number_format($t,2), 0);
			echo $this->table->generate();
			?>
		</div>
		<?php }else { echo '<div class="alert alert-danger">'."No banks associated yet. ".'</div>'; }?>
	<div class="panel-footer">
	<?php if($this->auth->perms("Branches.Banks",$this->auth->user_id(),3) == true) { ?> <input type="submit" value="Activate" name="submit" class="btn btn-success btn-sm"> &nbsp; <input type="submit" value="Deactivate" name="submit" class="btn btn-warning btn-sm"> &nbsp; <?php } if($this->auth->perms("Branches.Banks",$this->auth->user_id(),4) == true) { ?> <input type="submit" value="Remove" name="submit" class="btn btn-danger btn-sm"> <?php } ?></div>
</div>
</form>
<?php } else  echo '<div class="alert alert-danger">'."Branch doesn't exists.".'</div>'; ?>




<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
  <form class="form-horizontal" method="post" action="">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Add Bank to Branch</h4>
      </div>
      <div class="modal-body">         
			<div class="row form-group">
				<div class="col-md-6"><label>Choose Bank</label>
					<?php if($bankslist->num_rows() > 0) { ?>
					<select name="bankID" class="form-control input-sm" required>
							<?php foreach ($bankslist->result() as $b){
								echo '<option value="'.$b->bankID.'">'.$b->bankCode.'</option>';
							}?>
					</select>
					<?php } else { ?><a href="<?php base_url();?>cash/banks" class="btn btn-sm btn-default">Add Bank</a> <?php } 
					// ADD New Bank redirect to Bank Management Module
					?>
				</div>
				<!--<div class="col-md-5"><label>Bank not on the list?</label>
					<a href="<?php echo base_url();?>cash/banks" class="btn btn-warning btn-sm">Add New Bank</a>
				</div>-->
				<div class="col-md-6"><label>Branch Code <i>(optional)</i></label>
					<input type="text" class="form-control input-sm" name="branchcode" placeholder="ex. ESC">
				</div>
				</div>
				
				<div class="row form-group">
				<div class="col-md-6">
				<label>Bank Account</label>
				<input type="text" class="form-control input-sm" name="bankAccount" placeholder="Bank Account" required>
				</div>
				<div class="col-md-6">
				<label>Bank Branch</label>
				<input type="text" class="form-control input-sm" name="bankBranch" placeholder="Bank Branch" required>
				</div>
				</div>
				
				<div class="row form-group">
				<div class="col-md-12">
				<label>Bank Address</label>
				<input type="text" class="form-control input-sm" name="bankAddress" placeholder="Bank Address" required>
				</div>
				</div>
				<div class="row form-group">
				<div class="col-md-6">
				<label>Beginning Date</label>
				<input type="text" class="form-control input-sm" name="BeginningDate" id="BeginDate" placeholder="yyyy-mm-dd" required>
				<script>
				$(function() {
					var datepick = $( "#BeginDate" ).datepicker({format: 'yyyy-mm-dd',
					changeMonth: true,
					changeYear: true
					}).on('changeDate', function(ev) {
						datepick.hide();
					}).data('datepicker');				
				  });
			  </script>
				</div>
				<div class="col-md-6">
				<label>Beginning Balance</label>
				<input type="text" class="form-control input-sm" name="BeginningBal" placeholder="00.00" required>
				</div>	
				</div>
			</div>     
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
		<input type="hidden" name="branchid" value="<?php echo $branchid;?>">
       <input type="submit" class="btn btn-sm btn-primary " name="submit" value="Add Bank">
      </div>
	  </div>  
	  </form>
    </div>  
</div>

<?php } ?>