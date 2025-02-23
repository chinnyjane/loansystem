
	<div class="modal-dialog ">
    <?php echo form_open_multipart(base_url().'settings/charges/action/update',' method="post" id="fileupload" class="jquerypost"');?>
    <div class="modal-content">
        	<div class="modal-header">
            	Edit Charges
            </div>
            <div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<label>Charge Type</label>
						<select class="input-sm form-control" name="charge_type">
							<option value="F" <?php if ($ctype == 'F') echo 'selected';?>>Finance</option>
							<option value="NF" <?php if ($ctype == 'NF') echo 'selected';?>>Non-Finance</option>							
						</select>
					</div>
					<div class="col-md-6">
						<label>Charge Name</label>
						<input type="text" class="input-sm form-control" name="charge_name" value="<?php echo $cname;?>">
						<input type="hidden" class="input-sm form-control" name="id" value="<?php echo $id;?>">
					</div>
				</div>
            	
            </div>
            <div class="modal-footer">            	
            	<input type="submit" name="submit" value="Update Charges" class="btn btn-primary"></div>
            </div>
      </form>
        </div>
