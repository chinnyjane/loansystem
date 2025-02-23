<div class="modal-dialog ">
<form>
 <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">CREATE NEW CMC TRANSACTION</h4>
      </div>
      <div class="modal-body">
	  <div class="row form-group">
		<div class="col-md-12">
		<label>Choose Date of Transaction:</label><input type="text" id="date" name="date" placeholder="yyyy-mm-dd" class="form-control input">		
		<script>
				  $(function() {
					var datepick = $( "#date" ).datepicker({format: 'yyyy-mm-dd',
					changeMonth: true,
					changeYear: true
					}).on('changeDate', function(ev) {
						datepick.hide();
					}).data('datepicker');	
				  });
			  </script>
		</div>
		</div>
		</div>
		<div class="modal-footer">
		 <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
		<input type="submit" name="submit" value="Create Transaction" class="btn btn-primary ">
		</div>		
	</div>
	</form>
 </div>
