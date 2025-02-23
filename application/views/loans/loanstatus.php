<div class="row">
	<div class="col-lg-3 col-md-6">
		<div class="panel panel-red">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-spinner fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge"><?php echo $this->loansetup->loancount('processing');?></div>
						<div>Processing</div>
                    </div>
				</div>
			</div>
			<a href="<?php echo base_url();?>loans/status/processing">
			<div class="panel-footer">
				<span class="pull-left">View Details</span>
				<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
				<div class="clearfix"></div>
            </div>
			</a>
		</div>
	</div>
    
	<div class="col-lg-3 col-md-6">
        <div class="panel panel-green">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-legal fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge"><?php echo $this->loansetup->loancount('approval');?></div>
						<div>Loans for Approval</div>
					</div>
				</div>
			</div>
			<a href="<?php echo base_url();?>loans/status/approval">
				<div class="panel-footer">
					<span class="pull-left">View Details</span>
					<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
	<div class="col-lg-3 col-md-6">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-check-square-o fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge"><?php echo $this->loansetup->loancount('approved');?></div>
						<div>Approved</div>
					</div>
				</div>
			</div>
			<a href="<?php echo base_url();?>loans/status/approved">
				<div class="panel-footer">
					<span class="pull-left">View Details</span>
					<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
	<div class="col-lg-3 col-md-6">
		<div class="panel panel-yellow">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-money fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge"><?php echo $this->loansetup->loancount('release');?></div>
						<div>Loans for Release</div>
					</div>
				</div>
			</div>
			<a href="<?php echo base_url();?>loans/status/release">
				<div class="panel-footer">
					<span class="pull-left">View Details</span>
					<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
	
</div>