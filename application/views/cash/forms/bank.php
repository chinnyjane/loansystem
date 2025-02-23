<!-- ADD BANK MODAl -->
<div class="modal fade" id="addbank" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
  <form class="form-horizontal" method="post" action="">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Add Bank Balance</h4>
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