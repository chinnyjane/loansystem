<?php
$tmpl = array ('table_open'  => '<table class="table table-bordered" id="loanstatustable">');
$this->table->set_template($tmpl);
?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/modaljs.js"></script>
<script src="<?php echo base_url();?>assets/js/loansprocess.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/jasny-bootstrap.min.js" type="text/javascript"></script>	


<?php 
if(validation_errors() != NULL){
	echo '<div class="alert alert-danger" role="alert">
  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>'.validation_errors().'</div>';
}
  
  
?>
<form id="personal" method="post" action="<?php echo base_url();?>client/addnew" >
 <div class="panel panel-primary with-nav-tabs"  >
	<div class="panel-heading" style="margin-bottom: 0px; padding-bottom:0px;">	
		<ul class="nav nav-tabs" style="margin-bottom: 0px;">
            <li class="active"><a data-toggle="tab" href="#profile" >         Basic Information</a></li>
            <li><a href="#finance" data-toggle="tab">
                Financial Info</a></li>
             <li><a href="#depends" data-toggle="tab">
                Dependents</a></li> 						
        </ul>
    </div>
    
    	
        
		 <div class="tab-content" >
            <div class="tab-pane in active" id="profile" > 
				<div class="panel-body">
					<?php $this->load->view('client/addclientform');?>	             
				</div>
            </div>
			<div class="tab-pane" id="finance" >
				<div class="panel-body">
					<?php  $this->load->view('client/addfinanceinfo');?>	
				</div> 
            </div>		
			<div class="tab-pane" id="depends" >
				<div class="panel-body">
					<?php  $this->load->view('client/dependents');?>	
				</div> 
            </div> 
        </div>	
                   
 
    <div class="panel-footer">
		<input type="submit" name="submit" value="Save New Client" class="btn btn-success btn-sm"> &nbsp; <a href="<?php echo base_url();?>client" class="btn btn-sm">Cancel</a>
    </div>	
</div>
</form>
	<script>
	$(document).ready(function() {
	
		 $('#firstname').tooltip('hide');
		 $('#lname').tooltip('hide');
		 $('#mname').tooltip('hide');
		 $('.datepicker').tooltip('hide');
		
	
	  	$('#rootwizard').bootstrapWizard({onNext: function(tab, navigation, index) {		
							
				// Set the name for the next tab
				$('#tab2').html('This is the first loan of  ' + $('#firstname').val());
				
			}, onTabShow: function(tab, navigation, index) {
				var $total = navigation.find('li').length;
				var $current = index+1;
				var $percent = ($current/$total) * 100;
				$('#rootwizard').find('.bar').css({width:$percent+'%'});
			}});	
		window.prettyPrint && prettyPrint()
		
		
		
		
	});
	</script>
 
