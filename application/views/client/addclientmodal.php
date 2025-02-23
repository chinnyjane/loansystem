
 <div class="panel panel-success"  id="rootwizard">
	<div class="panel-heading">	
    	Client Information
    </div>   
    	<ul class="nav nav-tabs" id="myTab">
            <li class="active"><a href="#profile" data-toggle="tab" >
                Basic Information</a></li>
            <li><a href="#finance" data-toggle="tab">
                Financial Info</a></li>
             <li><a href="#depends" data-toggle="tab">
                Dependents</a></li> 						
        </ul>       
		 <div class="tab-content" >
            <div class="tab-pane in active" id="profile" >                              
            <?php $this->load->view('client/addclientform');?>	             
            </div>
			<div class="tab-pane" id="finance" >
			<?php  $this->load->view('client/addfinanceinfo');?>	
            </div>                                  
			<div class="tab-pane" id="depends" >
			<?php  $this->load->view('client/dependents');?>	
            </div> 
        </div>   
</div>
