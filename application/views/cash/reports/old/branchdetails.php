<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<?php 
if($branch->num_rows() >0){
	foreach($branch->result() as $br){
		$branchname = $br->branchname;
	}
$tmpl = array ('table_open'          => '<table class="table table-bordered">' );
$this->table->set_template($tmpl);
?>
<div class="panel panel-primary"><div class="panel-heading"><b><?php echo $branchname;?></b> Details</div>	
	
</div>
<?php if(isset($success)) echo '<div class="alert alert-success">'.$success.'</div>';
elseif(isset($error)) echo '<div class="alert alert-danger">'.$error.'</div>';
  ?>
  <div class="panel panel-info"><div class="panel-heading"><?php echo $branchname;?>  Banks &nbsp;<button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#myModal">Add Bank</button></div>
			
		<?php if($banks->num_rows() >0) {   ?>
		<div class="table-responsive">
			<?php 
			$this->table->set_heading('#', 'Bank Code', 'Beginning Balance', 'Beginning Date');
			$count = 1;
			$total = 0;
			foreach($banks->result() as $ba){
				$this->table->add_row($count, $ba->bankCode, number_format($ba->bankBalance,2), $ba->asOfDate);
				$total += $ba->bankBalance;
				$count++;
			}
			$this->table->add_row('', '<b>Total Cash in Bank</b>', number_format($total,2), $ba->asOfDate);
			echo $this->table->generate();
			?>
		</div>
		<?php }else { echo '<div class="alert alert-danger">'."No banks associated yet. ".'</div>'; }?>
</div>
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
				<div class="col-md-5"><label>Choose Bank</label>
					<?php if($bankslist->num_rows() > 0) { ?>
					<select name="bankID" class="form-control input-sm">
							<?php foreach ($bankslist->result() as $b){
								echo '<option value="'.$b->bankID.'">'.$b->bankCode.'</option>';
							}?>
					</select>
					<?php } else { ?><a href="<?php base_url();?>cash/banks" class="btn btn-sm btn-default">Create Bank</a> <?php } ?>
				</div>
				</div>
				<div class="row form-group">
				<div class="col-md-12">
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
				<div class="col-md-12">
				<label>Beginning Date</label>
				<input type="text" class="form-control input-sm" name="BeginningDate" id="BeginDate" placeholder="yyyy-mm-dd" required>
				<script>
				  $(function() {
					$( "#BeginDate" ).datepicker({format: 'yyyy-mm-dd',
					changeMonth: true,
					changeYear: true
					});
				  });
			  </script>
				</div>
				</div>
				<div class="row form-group">
				<div class="col-md-12">
				<label>Beginning Balance</label>
				<input type="text" class="form-control input-sm" name="BeginningBal" placeholder="00.00" required>
				</div>				
			</div>
			</div>     
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
       <input type="submit" class="btn btn-sm btn-primary " name="submit" value="Add Bank">
      </div>
	  </div>  
	  </form>
    </div>  
</div>