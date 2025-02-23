<?php $date = $this->auth->localdate();
	$now = strtotime($this->auth->localtime());
	$enddate = date("Y-m-d", strtotime($date."+1 months -1 day"));
	$due = $this->Loansmodel->clientpensiondue($pensionid, $enddate);
	?>
<h4>Due as of <?php echo date("F d, Y");?> </h4>

<?php
	$tmpl = array ('table_open'  => '<table class="table table-bordered table-condensed table-hover">');
	$this->table->set_template($tmpl);	
	
	if($due->num_rows() > 0){
		
		$count=1;
		$total = 0;
		foreach($due->result() as $d){
			
			$duedate = strtotime($d->DDUE);
			$datediff = $now - $duedate;
			$aging =  floor($datediff/(60*60*24));
			if($aging <= 0)
			$aging = 0;
			$this->table->add_row("<input type='checkbox' name='' class='' value='".$d->INSTAMT."' checked>",$count, "<a href='".base_url()."client/profile/".$clientid."/loan/".$d->loanID."'>".$d->PN, $d->DDUE, number_format($d->INSTAMT,2));
			$count++;
			$total += $d->INSTAMT;
		}
		
			$this->table->set_heading("Pay","#", "PN", "Due Date", "Amount Due");
			//$this->table->add_row(array("colspan"=>4, "data"=>"<label>TOTAL DUE</label>"), '<input type="text" class="input-sm form-control pull-right" style="text-align: right; font-weight: bold" name="totaldue" value="'.number_format($total,2).'" readonly>');
			?>
	
		<div class="panel panel-default">
			<?php echo $this->table->generate();?>			
		</div>
		<?php		
			}else{
				echo "No due for this month.";
			}
		?>
		<div class="row form-group">
					<div class="col-md-6">
						<label> TOTAL DUE </label>
					</div>
					<div class="col-md-6">
						<input type="text" class="input-sm form-control pull-right" style="text-align: right; font-weight: bold" name="totaldue" value="<?php echo number_format($total,2)?>" readonly>
					</div>
		</div>
		
		<hr/>
		<div class="row form-group">
			<div class="col-md-6">
				<label>EXCESS </label>
			</div>
			<div class="col-md-6">
				<input type="text" class="input-sm form-control pull-right" style="text-align: right; font-weight: bold" name="excess" value="" readonly>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-6">
				<label>TO Bank </label>
			</div>
			<div class="col-md-6">
				<select name="bank" class="input-sm form-control">
				<?php 
					foreach($banks->result() as $bank){
						echo "<option value='".$bank->branchBankID."'>".$bank->bankCode."</optio>";
					}
				?>
				</select>
			</div>
		</div>
	
