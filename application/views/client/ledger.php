<script type="text/javascript" src="<?php echo base_url();?>assets/js/modaljs.js"></script>
<?php 
$ledger = $this->Loansmodel->Clientledger("124");
$loans = $this->Loansmodel->getLoansNew("124");
$client = 124;
$tmpl = array ('table_open'  => '<table class="table table-bordered table-hover" id="loanabletable">');
	$this->table->set_template($tmpl);
	if($loans->num_rows() > 0){
			$count =1;
			$this->table->set_heading('#', 'PN#', 'Status', 'Loan ', 'Loan Amount',  'Terms','Date Granted','Maturity Date','Action');
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
			$this->table->add_row($count, $loan->PN, $loan->status, $loan->productName." - ".$loan->LoanCode, $loanamount, $loan->Term."mos - ".$loan->extension."mos",date("d-M-y",strtotime($loan->dateApplied)),date("d-M-y",strtotime($loan->MaturityDate)), '<a href="'.base_url().'loans/info/summary/'.$loan->loanID.'" title="Update" data-target="#" data-toggle="modal">View Details</a>');
			$count++;
		}
	
		echo '<div class="table-responsive">';
		echo $this->table->generate();
		echo '</div>';
	}
$tmpl = array ('table_open'  => '<table class="table table-striped table-hover">');
$this->table->set_template($tmpl);

if($ledger->num_rows() > 0){
	$pn = "";
	$count = 1;
	foreach($ledger->result() as $ledge){
		if($pn != $ledge->PN){
			$pn = $ledge->PN;
			$count = 1;
			$this->table->add_row(array("colspan"=>4, "data"=>"PN No. ".$pn));
		}		
		$this->table->add_row($count, $ledge->AmountDue, $ledge->Paid, $ledge->AmountDue - $ledge->Paid);
		$count++;
	}
	$this->table->set_heading("#", "AmountDue", "Paid", "Balance");
	echo $this->table->generate();
}

 ?>
  <script>
    $(document).ready(function() {
        $('#loanabletable').dataTable({
			"processing": true,
			"oLanguage": {
                "sProcessing": "<img src='<?php echo base_url(); ?>assets/img/ajax-loader.gif'>"
            },
			"dom": 'T<"clear">lfrtip',
			"tableTools": {
				"sSwfPath": "/swf/copy_csv_xls_pdf.swf"
			}
		});
		
    });
   </script>
