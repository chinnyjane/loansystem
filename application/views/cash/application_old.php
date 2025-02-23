<!-- Steps Progress and Details - START -->
<div class="container">
<section id="wizard">
			
	
				<div id="rootwizard">
					
					  <div class="navbar-inner">
					    <div class="container">						
							<ul class="nav nav-tabs nav-justified">
								<li class="active"><a href="#profile" data-toggle="tab" ><span class="fa fa-pencil"></span>
									<p>Client Profile</p></a></li>
								<li><a href="#loandetails" data-toggle="tab"><span class="fa fa-dollar"></span>
									<p> Loan Details</p></a></li>
								<li><a href="#collateral" data-toggle="tab"><span class="fa fa-suitcase"></span>
									<p>Collateral </p></a></li>
								<li><a href="#comaker" data-toggle="tab"><span class="fa fa-users"></span>
									<p>Co-maker </p></a></li>
								<li><a href="#require" data-toggle="tab"><span class="fa fa-cloud-upload"></span>
									<p>Requirements</p></a></li>
                                 <li><a href="#ci" data-toggle="tab"><span class="fa fa-credit-card"></span>
									<p>Credit Investigation</p></a></li>
								<li><a href="#finish" data-toggle="tab"><span class="fa fa-star"></span>
									<p>Finish for Approval</p></a></li>						
							</ul>
							
							
					</div>
					<div class="tab-content">
					    <div class="tab-pane active" id="profile" >
					      <div class="col-xs-12">
							<div class="col-md-12 well ">
								<div class="text-center">
								<h1>STEP 1</h1>
								<h3 class="underline">Client Information Form</h3>			
								</div>
								<?php $this->load->view('client/personalinfo');?>	
							</div>
						</div>
					    </div>
					    <div class="tab-pane" id="loandetails">
							<div class="col-xs-12">
								<div class="col-md-12 well ">
									<div class="text-center">
									<h1>STEP 2</h1>
									<h3 class="underline">Loan Information</h3>			
									</div>
								<?php  $this->load->view('loans/forms/loandetails');?>		
								</div>
							</div>
					    </div>
						<div class="tab-pane" id="collateral">
							<div class="col-xs-12">
							<div class="col-md-12 well ">
								<div class="text-center">
								<h1>STEP 3</h1>
								<h3 class="underline">Collateral Information</h3>			
								</div>
								<?php  $this->load->view('loans/forms/collaterals');?>
							</div>
						</div>
					    </div>
						<div class="tab-pane" id="comaker">
							<div class="col-xs-12">
							<div class="col-md-12 well ">
								<div class="text-center">
								<h1>STEP 4</h1>
								<h3 class="underline">Who is the Co-maker?</h3>	                               	
								</div>
								 <?php $this->load->view('loans/forms/comaker');?>	
							</div>
						</div>
					    </div>
						<div class="tab-pane" id="require">
							<div class="col-xs-12">
							<div class="col-md-12 well ">
								<div class="text-center">
								<h1>STEP 5</h1>
								<h3 class="underline">Check all submitted requirements</h3>			
								</div>                             
								<?php $this->load->view('loans/forms/requirements');?>	
							</div>
						</div>
					    </div>
						<div class="tab-pane" id="ci">
							<div class="col-xs-12">
							<div class="col-md-12 well ">
								<div class="text-center">
								<h1>STEP 6</h1>
								<h3 class="underline">Credit Investigation and Appraisal.</h3>			
								</div>
								<?php $this->load->view('loans/forms/credit');?>
							</div>
						</div>
					    </div>
                        <div class="tab-pane" id="finish">
							<div class="col-xs-12">
							<div class="col-md-12 well ">
								<div class="text-center">
								<h1>STEP 7</h1>
								<h3 class="underline">Double check Client's Information and submit for approval.</h3>			
								</div>
								
							</div>
						</div>
					    </div>
												
					</div>	
					
					
					<ul class="pager wizard">
							<li class="previous first" style="display:none;"><a href="#">First</a></li>
							<li class="previous"><a href="#">Previous</a></li>
							<li class="next last" style="display:none;"><a href="#">Last</a></li>
						  	<li class="next"><a href="#">Next</a></li>
						</ul>
				</div>
				

				
			</div>
            </section>
 </div>
 
