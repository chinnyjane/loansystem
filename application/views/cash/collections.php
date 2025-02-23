
<div class="panel panel-default">
	<div class="panel-heading">
		<!-- when clicked, it will load the create product form -->
			<div id='create-collection' class='btn btn-sm btn-primary'>
				<span class='glyphicon glyphicon-plus'></span> Add Collection
			</div>
		<!-- when clicked, it will load the create product form -->
			<div id='view-collection' class='btn btn-sm btn-primary display-none'>
				<span class='glyphicon glyphicon-plus'></span> View Collections
			</div>
	</div>
	<div class="panel-body">
		<!-- this is the loader image, hidden at first -->
		<div id='loader-image'><img src='<?php echo base_url();?>assets/img/ajax-loader.gif' /></div>
		<!-- this is where the contents will be shown. -->
		<div id='page-content'></div>

	</div>
	<div class="panel-footer">
	</div>
</div>



<script type='text/javascript'>
// jquery / javascript codes will be here

$(document).ready(function(){
	// hide read products button
    $('#view-collection').hide();
	
// view products on load of the page
	$('#loader-image').show();
	showCollections();
	
	// show create product button
    $('#create-collection').show();
       
    
	function showCollections(){
		 // fade out effect first
		$('#page-content').fadeOut('slow', function(){
			$('#page-content').load('<?php echo base_url();?>cash/collections/read', function(){
				// hide loader image
				$('#loader-image').hide(); 
				 
				// fade in effect
				$('#page-content').fadeIn('slow');
			});
		});				
	}
	
	// will show the create collection form
    $('#create-collection').click(function(){
        // change page title
        //changePageTitle('Create Product');
         
        // show create product form
        // show a loader image
        $('#loader-image').show();
         
        // hide create product button
        $('#create-collection').hide();
         
        // show read products button
       $('#view-collection').show();
         
        // fade out effect first
        $('#page-content').fadeOut('slow', function(){
            $('#page-content').load('<?php echo base_url();?>cash/collections/add', function(){ 
             
                // hide loader image
                $('#loader-image').hide(); 
                 
                // fade in effect
                $('#page-content').fadeIn('slow');
            });
        });
    });
	
	
	$(document).on('click', '.view-btn', function(){ 
		// show a loader image
        $('#loader-image').show();
         
        // hide create product button
        $('#create-collection').hide();
         
        // show read products button
       $('#view-collection').show();
	   
	   var clientID = $(this).closest('td').find('.clientID').text(); 
	    // fade out effect first
        $('#page-content').fadeOut('slow', function(){
            $('#page-content').load('<?php echo base_url();?>client/loan/'+clientID , function(){ 
             
                // hide loader image
                $('#loader-image').hide(); 
                 
                // fade in effect
                $('#page-content').fadeIn('slow');
            });
        });
	});
	
	$('#view-collection').click(function(){
        
        // show create product form
        // show a loader image
        $('#loader-image').show();
         
        // hide create product button
        $('#create-collection').show();
         
        // show read products button
       $('#view-collection').hide();
	   
	   showCollections();
	});
	
	$(document).on('click', '.edit-btn', function(){ 
		
		var particulars = $(this).closest('td').find('.particulars').text();
		var clientID = $(this).closest('td').find('.clientID').text();
		var pensionID = $(this).closest('td').find('.pensionID').text();
		
		// fade out effect first
		$('#plcollection').hide();
		
		$('#page-content').fadeOut('slow', function(){
			$('#page-content').load('<?php echo base_url();?>cash/collections/addpl?particulars=' + encodeURIComponent(particulars) + '&clientID=' + clientID + '&pensionID='+pensionID, function(){

				// hide loader image
				$('#loader-image').hide(); 
				 
				// fade in effect
				$('#page-content').fadeIn('slow');
			});
		});
	});
});
</script>