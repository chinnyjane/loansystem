<div id="PLForm"  >
	<h4>Pension Information</h4>
		 <div class="modal-content">
		<div class="modal-header">
			
			<h4 class="modal-title" id="myModalLabel">Add New Pension</h4>
		</div>
		<div class="modal-body">
			<div class="row form-group">
				<div class="col-md-4">
					<label>Pension from</label>
					<select name="pension[pensiontype]" class="input-sm form-control">
						<option value="sss">SSS</option>
						<option value="gsis">GSIS</option>
						<option value="afp">AFP</option>
						<option value="bfp">BFP</option>
						<option value="pnp">PNP</option>
					</select>
				</div>
				<div class="col-md-4">
					<label>Type of Pension</label>
					<select name="pension[pensionstatus]" class="input-sm form-control">
						<option value="survivorship">Survivorship</option>
						<option value="retirement">Retirement</option>
						<option value="itf">ITF</option>
						<option value="partialdisability">Partial Disability</option>
						<option value="permanentdisability">Permanent Disability</option>
					</select>
				</div>
				<div class="col-md-4">					
				</div>
			</div>
			<div class="row form-group">
				<div class="col-md-4">
					<label>SSS/GSIS/AFP Account:</label>
					<input type="text" class="input-sm form-control" name="pension[sss]" placeholder="ex.123456789">
				</div>
				<div class="col-md-4">
					<label>Monthly Pension</label>
					<input type="text" class="input-sm form-control" name="pension[pension]" placeholder="00.00">
				</div>
				<div class="col-md-4">
					<label>Pension Receive Day</label>
					<input type="text" class="input-sm form-control" name="pension[date]" placeholder="ex. 10">
				</div>
			</div>
			<div class="row form-group">
				<div class="col-md-4">
					<label>Bank:</label>
					<?php echo $this->form->bank("pension[bank]", ''); ?>
				</div>
				<div class="col-md-4">
					<label>Branch</label>
					<input type="text" class="input-sm form-control" name="pension[branch]" placeholder="bank branch">
				</div>
				<div class="col-md-4">
					<label>Account #</label>
					<input type="text" class="input-sm form-control" name="pension[accountnum]" placeholder="ex. 0123456789">
				</div>
			</div>
		</div>
	</div>