<div class="responsive">
<?php if($recap->num_rows() > 0){
		$this->table->set_heading("#","Date Deposited","Bank","Amount","Type of Deposit","Time Deposited","Notes", "Action");
		$count=1;
		$total = 0;
	foreach($recap->result() as $rec){
		if($this->auth->perms("Cash.Recap of Deposits", $this->auth->user_id(), 3) == true and $cmcstatus == 'open'){
			$act = "<a href='".base_url()."cash/page/forms/modifyrecap/".$transid."/".$rec->recapdepositID."' title='Update' data-target='#' data-toggle='modal'><span class='glyphicon glyphicon-pencil'></span></a> &nbsp;";
		if($this->auth->perms("Cash.Recap of Deposits", $this->auth->user_id(), 4) == true)
			$act .= "<a href='".base_url()."cash/daily/recap/".$transid."/remove/".$rec->recapdepositID."' title='Remove' data-target='#' data-toggle='remove'><span class='glyphicon glyphicon-remove'></span></a>";
		}else $act='n/a';
		
		if(!empty($rec->branchCode))
						$bcode = "-".$rec->branchCode;
						else $bcode = "";
						
		$this->table->add_row($count,$rec->dateOfTransaction,$rec->bankCode.$bcode,number_format($rec->amount,2),$rec->type,date("h:i A", strtotime($rec->timeofDeposit)), $rec->notes, $act);
		$total += $rec->amount;
		$count++;
	}
	$this->table->add_row('','<b>Total Deposits</b>','','<b>'.number_format($total,2).'</b>','','', '', '');
	echo $this->table->generate();
} ?>
</div>