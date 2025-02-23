<?php 
$client =$this->uri->segment(3);
$loans = $this->Loansmodel->getLoansNew($client);
?>
<h4>LOAN INFORMATION</h4>
<hr>
<div id="loandetails">
	<?php if($this->session->userdata('clientid') ==null or $client != $this->session->userdata('clientid')) { ?>
	<p>
    <form action="<?php echo base_url();?>loans/application" method="post">
    <input type="hidden" name="clientid" value="<?php echo $client;?>">
	<button type="submit" class="btn btn-sm btn-danger" ><i class="fa fa-plus-circle"></i> New Loan</button>
    </form>
	</p>
<?php } else { ?><p><a class="btn btn-sm btn-default" href="<?php echo base_url();?>loans/new">New Loan</a></p> <?php } 
	
	$tmpl = array ('table_open'  => '<table class="table table-bordered table-hover" id="loanabletable">');
	$this->table->set_template($tmpl);
	if($loans->num_rows() > 0){
			$count =1;
			$this->table->set_heading('#', 'PN#', 'Status', 'Loan ', 'Loan Amount',  'Terms','Ext','Applied Date','Maturity Date','Action');
		foreach($loans->result() as $loan){
			$agent = $this->UserMgmt->get_user_byid($loan->addedBy);
			if($agent->num_rows() > 0){
			$a = $agent->row();
			$agent= $a->lastname.", ".$a->firstname;
			}else $agent = '';
			if($loan->approvedAmount != '')
				$loanamount = number_format($loan->approvedAmount,2);
			else
				$loanamount = number_format($loan->AmountApplied,2);
			if( $loan->status != 'canceled')
			$this->table->add_row($count, $loan->PN, $loan->status, $loan->productName." - ".$loan->LoanCode, $loanamount, $loan->Term, $loan->extension,date("d-M-y",strtotime($loan->dateApplied)),date("d-M-y",strtotime($loan->MaturityDate)), '<a href="'.base_url().'client/profile/'.$client.'/loan/'.$loan->loanID.'">View</a>');
			$count++;
		}
	
		echo '<div class="table-responsive">';
		echo $this->table->generate();
		echo '</div>';
		
	} else{
		echo "<h4> No Active Loans.</h4>";
	}
	
	
	?>
</div>

