  
 <div class="modal-dialog modal-lg ">
 <form class="form-horizontal" id="checkrelease" method="post" action="<?php echo base_url();?>loans/action/release">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Check Release</h4>
      </div>
      <div class="modal-body">
		<?php
			$loaninfo = $this->Loansmodel->getLoanbyID($loanid);
			$loan = $loaninfo->row();
			$tmpl = array ('table_open'          => '<table class="table  table-border  " >'); 
			$this->table->set_template($tmpl);		


			$data=array("PN"=>$loan->PNno,
							"isdeleted <>"=>1);
			$cvres = $this->Loansmodel->getTransByPN($loan->PN);
			$cv = $cvres->row();
			$fees = $this->Loansmodel->getLoanFees($loanid);
			$fee = $fees->result();
			$agent = $this->UserMgmt->get_user_byid($cv->addedBy);
			if($agent->num_rows() > 0 ){
				$a = $agent->row();
				$ag= $a->lastname.", ".$a->firstname;
			}
			$c = 0;
			$amount = strtoupper($this->loansetup->convert_number_to_words($cv->Amount_OUT));
			$explanation = $cv->explanation." PER PN No. : ".$cv->PN;
			$explanation .= "<br/><br/><p>NOTE:</p>";
			$explanation .= "<br/><br/><p>TOTAL PN AMOUNT : ".number_format($loan->approvedAmount,2)."</p>";
			$rcve = "Received from FRUITS CONSULTING INC the said amount as full/partial payment for the above explanation.";


			$this->table->set_heading("PAYEE", $loan->LastName.", ".$loan->firstName, "DATE", date("d-M-y", strtotime($cv->dateOfTransaction)));
			$this->table->add_row("Address", $loan->address, "CV No.", $cv->referenceNo);
			//$this->table->add_row(array("colspan"=>'4', "data"=>"" ));
			//echo  $this->table->generate();

			//$this->table->add_row(array("colspan"=>'2', "align"=>"center", "data"=>"EXPLANATION" ), array("colspan"=>'2', "align"=>"center", "data"=>"AMOUNT" ));
			//$this->table->add_row(array("colspan"=>'2', "data"=>$explanation ), array("colspan"=>'2', "align"=>"center", "data"=>number_format($cv->Amount_OUT,2) ));
			$this->table->add_row("Bank/Branch : ".$cv->bankCode, "Check No. : ".$cv->Checkno, "PN No.", $cv->PN);
			$this->table->add_row("<i><b>Net Proceeds</b></i>", "<i><b>".$amount." PESOS ONLY</b></i>", array("colspan"=>'2', "align"=>"center", "data"=>"<b>PHP ".number_format($cv->Amount_OUT,2)."</b>" ));
			
			echo  $this->table->generate();


			/*$this->table->add_row(array("width"=>"25%", "data"=>"Prepared By/Encoded By: "), array("width"=>"25%", "data"=>"Pre-Audited By:"),array("colspan"=>'2', "data"=>"Approved By:" ));
			$this->table->add_row(array("height"=>"50px", "valign"=>"bottom","data"=>$ag),array("valign"=>"bottom","data"=>"Auditor"),array("valign"=>"bottom","data"=>"Manager"),array("valign"=>"bottom","data"=>"COO"));
			$this->table->add_row(array("height"=>"70px","colspan"=>"2", "data"=>$rcve),array("colspan"=>"2", "valign"=>"bottom","align"=>"center", "data"=>"<ul>".$loan->LastName.", ".$loan->firstName."</ul>Signature Over Printed Name"));
			
			$this->table->add_row(array("colspan"=>'2', "data"=>"Account Title", "align"=>"center"), "DEBIT", "CREDIT");
			$this->table->add_row(array("colspan"=>'2', "data"=>"PL"), array( "align"=>"right","data"=>number_format($loan->approvedAmount,2)), "");
			//FEES
			foreach($fee as $f){
				$this->table->add_row(array("colspan"=>'2', "data"=>$f->feeName),'',  array( "align"=>"right","data"=>number_format($f->value,2)));
				$c += $f->value;
			}
			$c += $cv->Amount_OUT;
			$this->table->add_row(array("colspan"=>'2', "data"=>"CIB"),'', array( "align"=>"right","data"=>number_format($cv->Amount_OUT,2)));
			$this->table->add_row(array("colspan"=>'2', "data"=>"TOTAL"),array( "align"=>"right","data"=>number_format($loan->approvedAmount,2)), array( "align"=>"right","data"=>number_format($c,2)));
			echo  $this->table->generate();*/		
		?>
		<input type="hidden" name="loanid" value="<?php echo $loanid;?>">
		<input type="hidden" name="amount" value="<?php echo $cv->Amount_OUT;?>">
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-success btn-sm"  id="release">Release</button>
	</div>
</div>
</form>
</div>

		