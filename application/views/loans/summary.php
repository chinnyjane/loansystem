<div class="modal-dialog modal-lg ">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel">Summary of Loan</h4>
		</div>
		<div class="modal-body">
			<?php $p = $this->Loansmodel->getLoanDetails($loanid);
			$loaninfo = $p['loaninfo']->row();
			if($p['schedule']->num_rows() > 0){
					$loanbal = $loaninfo->approvedAmount;
						foreach($p['schedule']->result() as $sch){
							if($sch->LoanBalance == NULL)
								$loanbal -= $sch->Paid;
							else
								$loanbal = $sch->LoanBalance;
							//echo $loanbal;
							$paid = ($sch->Paid ? $sch->Paid : 0);
							$this->table->add_row($sch->order, $sch->DueDate,number_format($sch->AmountDue,2), number_format($paid,2), $sch->DatePaid);
						}
						$this->table->set_heading("#", "Due Date","Amount Due", "Amount Paid","Date Paid");
						echo $this->table->generate();
					}
			?>
		</div>
		<div class="modal-footer">
			<button type="button" id="button">Button Click</button>
			<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>	
		</div>
	</div>
</div>
<script>
	 $(document).ready(function() {
		 $("#button").on("click", function(){
			alert("button clicked"); 
		 });
	 });
</script>