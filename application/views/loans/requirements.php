<?php $clientid = $this->session->userdata('clientid');
	$pid = $this->session->userdata('loantype');?>

<div class="panel-body">
<?php $tmpl = array ('table_open'          => '<table class="gridView table  table-condensed table-hover tablesort" >',
			'thead_open' => '<thead class="header">'	); 
				$this->table->set_template($tmpl); 
		$this->table->set_heading("Select", "Requirement", "Date Submitted");
?>
				
<?php $reqs = $this->Loansmodel->getreqs($pid); 
		if($reqs->num_rows() > 0){
				$count = 1;
			foreach($reqs->result() as $req){
				$this->table->add_row("<input type='hidden' name='reqID[".$req->reqID."]' value='0'><input type='checkbox' name='reqID[".$req->reqID."]' value='1'>", $req->requirement, '');				
			$count++;
			}
			
			echo $this->table->generate();
		} 		
?>
</div>