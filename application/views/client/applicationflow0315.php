<?php
$tmpl = array ('table_open'  => '<table class="table table-bordered" id="loanstatustable">');
$this->table->set_template($tmpl);
?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/modaljs.js"></script>
<script src="<?php echo base_url();?>assets/js/loansprocess.js" type="text/javascript"></script>
 <script src="<?php echo base_url();?>assets/js/jasny-bootstrap.min.js" type="text/javascript"></script>	
<div class="panel panel-primary"  id="rootwizard">
	<div class="panel-heading">	
    	Loan Application
    </div>
    <div class="panel-body">
    	<ul class="nav nav-tabs" id="myTab">
            <li class="active"><a href="#profile" data-toggle="tab" ><span class="fa fa-pencil"></span>
                Client Profile</a></li>
            <li><a href="#finance" data-toggle="tab"><span class="fa fa-money"></span>
                Financial Info</a></li>
                <li><a href="#loandetails" data-toggle="tab"><span class="fa fa-dollar"></span>
                 Loan Details</a></li>
            <li><a href="#collateral" data-toggle="tab"><span class="fa fa-suitcase"></span>
                Collateral</a></li>
            <li><a href="#comaker" data-toggle="tab"><span class="fa fa-users"></span>
                Co-maker</a></li>
            <li><a href="#require" data-toggle="tab"><span class="fa fa-cloud-upload"></span>
                Requirements</a></li>            
            <li><a href="#finish" data-toggle="tab"><span class="fa fa-star"></span>
                Submit Loan</a></li>						
        </ul>
        
		 <div class="tab-content" >
            <div class="tab-pane in active" id="profile" >                              
            <?php $this->load->view('client/personalinfo');?>	             
            </div>
			<div class="tab-pane" id="finance" >
			<?php  $this->load->view('client/financeinfo');?>	
            </div>
            <div class="tab-pane " id="loandetails">
            <?php  $this->load->view('loans/forms/loandetails');?>		
            </div>
            <div class="tab-pane " id="collateral">
                 <?php  $this->load->view('loans/forms/collaterals');?>	
            </div>
            <div class="tab-pane" id="comaker">
                <?php $this->load->view('loans/forms/comakerpersonal');?>	
            </div>
            <div class="tab-pane" id="require">
                 <?php $this->load->view('loans/forms/requirements');?>	
            </div>
            
            <div class="tab-pane" id="finish">
                  <?php $this->load->view('loans/forms/final');?>
            
            </div>
                                    
        </div>	
                   

    </div>
    <div class="panel-footer">
    <ul class="pager wizard">
        <li class="previous first btn" style="display:none;"><a href="#">First</a></li>
        <li class="previous"><a href="#">Previous</a></li>
        <li class="next last" style="display:none;"><a href="#">Last</a></li>
        <li class="next "><a href="#">Next</a></li>
    </ul>
    </div>
</div>

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
 
