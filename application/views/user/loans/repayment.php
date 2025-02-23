<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
 <div id="scroll" class="content" >
	<h2 class="content-subhead"><?php echo "Welcome ".$this->auth->firstname()."!";?></h2>
	<div>
		<ul>
			<li><a href="<?php echo base_url();?>loan/application" class="link">Loan Application</a></li>
			<li><a href="<?php echo base_url();?>loan/approval" class="link">Loan Approval</a></li>
			<li><a href="<?php echo base_url();?>loan/repayment" class="link">Loan Repayment</a></li>
		</ul>
	</div>
</div>
</div>
