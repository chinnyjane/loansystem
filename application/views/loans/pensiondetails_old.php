
<div class="panel panel-green">
	<div class="panel-heading">PENSION INFORMATION</div>
	<div class="panel-body">
<h4>Borrower: <a href="<?php echo base_url();?>client/profile/<?php echo $clientid;?>"><?php echo $client->LastName.", ".$client->firstName;?></a> &nbsp;&nbsp;</h4>
<hr/>

<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li class="active"><a href="#pensioninfo" role="tab" data-toggle="tab">Pension Details </a></li>
			<li ><a href="#loanslist" role="tab" data-toggle="tab">Pension Loans </a></li>  
			<li><a href="#collections" role="tab" data-toggle="tab">PL Collections</a></li>		  
		</ul>
		
		
		<div class="tab-content">
			<div class="tab-pane active" id="pensioninfo">
				<?php	$this->load->view('loans/pensioninformation');	?>
			</div>
			<div class="tab-pane" id="loanslist">
				<?php	$this->load->view('client/pensiondue');	?>
				<h4><?php echo strtoupper("Loans Under this Pension");?></h4>
				<hr/>
				<div class="panel panel-default">
				<?php
					$loans = $this->Loansmodel->pensionloan($pensionid);
					if($loans->num_rows() > 0){					
					
						foreach($loans->result() as $loan){
							$this->table->add_row( "<a href='".base_url()."client/profile/".$clientid."/loan/".$loan->loanID."'>".$loan->loanID."</a>", $loan->PN, $loan->status, number_format($loan->AmountApplied,2));
						}
						$this->table->set_heading("LoanID", "PN", "Status", "Amount Applied");
						echo $this->table->generate();
					}else{
						echo "No Loans yet";
					}
				?>
				</div>  
			</div>  
			<div class="tab-pane" id="collections">
				<?php $this->load->view('loans/plcollection'); ?>
			</div>			 
		</div>
</div>
</div>