<div class="row">
	<div class="col-md-4">
		<label>Search Borrower: </label>
		<input type="text" name=""class="input-sm form-control">
	</div>
</div>
<b>Successful Response (should be blank):</b>
<div id="success"></div>
<b>Error Response:</b>
<div id="error"></div>
 
<script>
/*$( "#success" ).load( "<?php echo base_url();?>", function( response, status, xhr ) {
  if ( status == "error" ) {
    var msg = "Sorry but there was an error: ";
    $( "#error" ).html( msg + xhr.status + " " + xhr.statusText );
  }
});*/
</script>