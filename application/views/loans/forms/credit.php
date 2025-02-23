<form action="<?php echo base_url();?>loans/application/cireport" method="post" class="formpost">
	<div class="modal-body">
	<?php 		 
		 if($ci->num_rows() > 0){
			 echo '<input type="hidden" name="action" value="update">';
			foreach($ci->result() as $c){ 
				$cir[$c->ci_id] = $c->value;
				$ciid[$c->ci_id] = $c->cidetailID;
			}			
		 }
		 
		 $cidetails = $this->Products-> getcidata($loantype);
			  echo '<input type="hidden" name="action" value="insert">';
			  if($cidetails->num_rows() > 0){
				foreach($cidetails->result() as $cid){ ?>
					<div class="row form-group">
						<div class="col-md-12"><label><?php echo $cid->ci_name;?></label>
						<textarea name="ci[<?php echo $cid->ci_id;?>]" class="input form-control"><?php if(isset($cir)) echo $cir[$cid->ci_id];?></textarea>
						</div>
					</div>
				<?php }
			  }	
	?> 
    </div>
    <div class="modal-footer">
        <input type="hidden" name="step" value="CI Report">
        <input type="hidden" name="loanid" value="<?php echo $loanid;?>">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
        <input type="submit" name="submit" value="Submit CI/Appraisal Report" class="btn btn-primary">
    </div>
</form>
