
<?php
$br = $this->UserMgmt->get_branches();
$branch = $this->auth->branch_id();
?>

<div class="" id="search" >
	<form method="post" id="searchform" action="<?php echo base_url();?>search-result" >
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<input type="text" id="name" placeholder="Enter name..." name="client" class="form-control input">			</div>
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">			 
				  <select name="branch" id="branch" type="text"  class="form-control">
					<option value='all'>ALL</option>		
					<?php
						foreach($br->result() as $b){
							if($branch == $b->id) $select = 'selected';
							else $select='';
							echo "<option value='".$b->id."' ".$select.">".$b->branchname."</option>";				
						}
					?>		
				  </select>
	
			</div>
			<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
				<button type="submit" class="btn btn-primary btn-flat" id="show">Search Client</button>
			</div>
		</div>
</div>

<br/>
<div align="center">
	<image id="loader" src="<?php echo base_url();?>assets/img/loading.gif">
</div>
<div>
	<button class="btn btn-flat btn-success" id="backtosearch"> Back to Search Result</button>
	<br/>
</div>

<div class="" id="searchresult">
	
</div>

<div id="profilesection">

</div>
	
<script>
$(document).ready(function(){
	$('#backtosearch').hide();
	$('#loader').hide();
	
	$('#name').on('keyup', function(){
		search();
	}).keydown(function( event ) {
	  if ( event.which == 13 ) {
		event.preventDefault();
	  }
	});
	
	$('#branch').on('change', function(){
		search();
	});
	
	$('#searchform').on('submit', function (e){
		e.preventDefault();	
		search();
	});
	
	$('#backtosearch').on('click', function(){
		$('#searchresult').show();	
	});
	
	function search(){
		
		$('#show').button('loading');
		$('#loader').show();
		$('#searchresult').hide();		
		var form = $("#searchform").attr("action");
		 $.ajax({
			type: "POST",
			url: form, //process to mail
			data: $('#searchform').serialize(),
			success: function(msg){
				$('#searchresult').html(msg);	
				$('#searchresult').show();		
				$('#show').button('reset');
				$('#loader').hide();
			},
			error: function(){
				bootbox.alert("Please try again.", function(){
					$('#show').button('reset');
					$('#loader').hide();
				});
				
			}
		});
	}
	
});
</script>