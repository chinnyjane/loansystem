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
    	<ul class="nav nav-tabs nav-justified " style="display:none" >
            <li class="active"><a href="#profile" data-toggle="tab" ><span class="fa fa-pencil"></span>
                <p>Client Profile</p></a></li>
            <li><a href="#loandetails" data-toggle="tab"><span class="fa fa-dollar"></span>
                <p> Loan Details</p></a></li>
            <li><a href="#collateral" data-toggle="tab"><span class="fa fa-users"></span>
                <p>Collateral </p></a></li>
            <li><a href="#comaker" data-toggle="tab"><span class="fa fa-users"></span>
                <p>Co-maker </p></a></li>
            <li><a href="#require" data-toggle="tab"><span class="fa fa-cloud-upload"></span>
                <p>Requirements</p></a></li>
            
            <li><a href="#finish" data-toggle="tab"><span class="fa fa-star"></span>
                <p>Finish Loan Application</p></a></li>						
        </ul>
        
        <div class="tab-content" >
            <div class="tab-pane fade in active" id="profile" >
              <div class="col-xs-12">
                <div class="col-md-12  ">
                    <div class="text-center">
                    <h1>STEP 1</h1>
                    <h3 class="underline">Client Information Form</h3>			
                    </div>
                    <?php $this->load->view('client/personalinfo');?>	
                </div>
            </div>
            </div>
            <div class="tab-pane fade" id="loandetails">
                <div class="col-xs-12">								
                        <div class="text-center">
                        <h1>STEP 2</h1>
                        <h3 class="underline">Loan Information</h3>			
                        </div>
                    <?php  $this->load->view('loans/forms/loandetails');?>		
                    
                </div>
            </div>
            <div class="tab-pane fade" id="collateral">
                <div class="col-xs-12">
                <div class="col-md-12  ">
                    <div class="text-center">
                    <h1>STEP 3</h1>
                    <h3 class="underline">Collateral Information</h3>	                               	
                    </div>
                     <?php $this->load->view('loans/forms/collaterals');?>	
                </div>
            </div>
            </div>
            <div class="tab-pane fade" id="comaker">
                <div class="col-xs-12">
                <div class="col-md-12  ">
                    <div class="text-center">
                    <h1>STEP 4</h1>
                    <h3 class="underline">Who is the Co-maker?</h3>	                               	
                    </div>
                     <?php //$this->load->view('loans/forms/comaker');?>	
                     <?php //$this->load->view('client/personalinfo');?>
                </div>
            </div>
            </div>
            <div class="tab-pane fade" id="require">
                <div class="col-xs-12">
                <div class="col-md-12  ">
                    <div class="text-center">
                    <h1>STEP 5</h1>
                    <h3 class="underline">Check all submitted requirements</h3>			
                    </div>                             
                    <?php $this->load->view('loans/forms/requirements');?>	
                </div>
            </div>
            </div>
            
            <div class="tab-pane fade" id="finish">
                <div class="col-xs-12">
                <div class="col-md-12  ">
                    <div class="text-center">
                    <h1>STEP 6</h1>
                    <h3 class="underline">Double check Client's Information and submit for Credit Investigation.</h3>			
                    </div>
                    <?php $this->load->view('loans/forms/final');?>
                </div>
            </div>
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
 
