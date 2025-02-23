<div class="col-md-7" style>
		<div class="panel panel-default"><div class="panel-heading">Loan Summary</div>
		<div class="panel-body">
			<div class="row form-group">
				<div class="col-md-5"><label>Loanable Amount</label></div>
				<div class="col-md-7">
				<div class="input-group">
				<span class="input-group-addon">Php</span>
				<input type="text" id="maxamount" class="form-control input-sm" readonly>	  
				</div></div>
				</div>
			<div class="row form-group">
				<div class="col-md-5"><label>Amount Applied *</label></div>
				<div class="col-md-7">
				<div class="input-group">
				<span class="input-group-addon">Php</span>
				<input type="text" name="loanapplied" id="loanapplied" value="<?php echo set_value("loanapplied");?>" class="form-control input-sm" required>	  
				</div></div>
				</div>
				<div class="row form-group">
				<div class="col-md-5"><label>Terms *</label></div>
				<div class="col-md-7"><select name="terms" id="terms" class="form-control input-sm"><?php $count=1; while ($count <= 24) {?> <option value='<?php echo $count;?>' <?php echo set_select('terms', $count ); ?>><?php echo $count;?></option><?php $count++;}?></select></div>
			</div>
			<div class="row form-group">
				<div class="col-md-5"><label for="interest">Monthly Installment</label></div>
				<div class="col-md-7"><input type="text" id="monthly" name="monthly" placeholder="0.00" value="<?php if(isset($monthly)) echo $monthly; ?>"  class="form-control input-sm" readonly></div>
			</div>
			<div class="row form-group">
				<div class="col-md-5"><label for="interest">Excess</label></div>
				<div class="col-md-7"><input type="text" name="excess" id="excess" placeholder="0.00" value="<?php if(isset($excess)) echo $excess; ?>"  class="form-control input-sm" readonly></div>
			</div>
			<div class="row form-group">
				<div class="col-md-5"><label class="htitle" style="color: red" for="net"> Net Proceeds </label></div>
				<div class="col-md-7"><input type="text" name="net" id="net" placeholder="00.00"  style="color: red;" value="<?php if(isset($net)) echo $net;?>" class="form-control input-sm" readonly></div>
				</div>
			</div>
		</div>
		</div>
						