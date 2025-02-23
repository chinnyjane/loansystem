<!--
		<div class="panel panel-info"><div class="panel-heading">Dependents</div>
		<div class="panel-body">
		<div class="row form-group">	
			<div class="col-md-4"><label>Name of Dependents</label></div>
			<div class="col-md-4"><label>Date of Birth</label></div>
			
			</div>
		<div class="row form-group">	
			<div class="col-md-4"><input type="text" class="form-control input-sm" placeholder="Name" name="dpname[]" > </div>
			<div class="col-md-4"><input type="text" class="form-control input-sm" placeholder="mm-dd-yyyy" id="dpbday1"  name="dpbday[]" > <script>
				  $(function() {
					$( "#dpbday1" ).datepicker({format: 'mm-dd-yyyy',
					changeMonth: true,
					changeYear: true
					});
				  });
			  </script></div>
			</div>
				<div class="row form-group">	
			<div class="col-md-4"><input type="text" class="form-control input-sm" placeholder="Name" name="dpname[]" > </div>
			<div class="col-md-4"><input type="text" class="form-control input-sm" placeholder="mm-dd-yyyy" id="dpbday2"  name="dpbday[]" > <script>
				  $(function() {
					$( "#dpbday2" ).datepicker({format: 'mm-dd-yyyy',
					changeMonth: true,
					changeYear: true
					});
				  });
			  </script></div>
			</div>
			<div class="row form-group">	
			<div class="col-md-4"><input type="text" class="form-control input-sm" placeholder="Name" name="dpname[]" > </div>
			<div class="col-md-4"><input type="text" class="form-control input-sm" placeholder="mm-dd-yyyy" id="dpbday3"  name="dpbday[]" > <script>
				  $(function() {
					$( "#dpbday3" ).datepicker({format: 'mm-dd-yyyy',
					changeMonth: true,
					changeYear: true
					});
				  });
			  </script></div>
			</div>
			<div class="row form-group">	
			<div class="col-md-4"><input type="text" class="form-control input-sm" placeholder="Name" name="dpname[]" > </div>
			<div class="col-md-4"><input type="text" class="form-control input-sm" placeholder="mm-dd-yyyy" id="dpbday4"  name="dpbday[]" > <script>
				  $(function() {
					$( "#dpbday4" ).datepicker({format: 'mm-dd-yyyy',
					changeMonth: true,
					changeYear: true
					});
				  });
			  </script></div>
			</div>
			<div class="row form-group">	
			<div class="col-md-4"><input type="text" class="form-control input-sm" placeholder="Name" name="dpname[]" > </div>
			<div class="col-md-4"><input type="text" class="form-control input-sm" placeholder="mm-dd-yyyy" id="dpbday5"  name="dpbday[]" > <script>
				  $(function() {
					$( "#dpbday5" ).datepicker({format: 'mm-dd-yyyy',
					changeMonth: true,
					changeYear: true
					});
				  });
			  </script></div>
			</div>
			</div>
		</div> -->
		
		
		<div class="panel panel-info" >
						<div class="panel-heading">Pension Info</div>
						<div class="panel-body">
						<div class="form-group">
						<label>Monthly Pension</label><div class="input-group">
						<span class="input-group-addon">Php</span>
						<input type="text" name="pension" id="pension" value="<?php echo set_value("pension");?>" class="form-control input-sm" required>	  
						</div>
						</div>
						<div class="form-group">
							<label>SSS/GSIS No.</label>
							<input type="text" name="pension" id="pension" value="<?php echo set_value("pension");?>" class="form-control input-sm" required>						
						</div>
						<div class="form-group">
							<label>Depository Bank</label>
							<input type="text" name="pension" id="pension" value="<?php echo set_value("pension");?>" class="form-control input-sm" required>						
						</div>
						<div class="form-group">
							<label>Bank Account No.</label>
							<input type="text" name="pension" id="pension" value="<?php echo set_value("pension");?>" class="form-control input-sm" required>						
						</div>
						<div class="form-group">
							<label>Date of Pension</label>
							<input type="text" name="pension" id="pension" value="<?php echo set_value("pension");?>" class="form-control input-sm" required>						
						</div>
					</div>
				</div>
				