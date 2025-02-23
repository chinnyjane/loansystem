<?php 
$clientid = $this->session->userdata('clientid');
$loantype = $this->session->userdata('loantype');
$pension = $this->Loansmodel-> get_pensioninfo($clientid);
if($pension->num_rows() > 0){
 $disabled = 'disabled';
 $readonly = "readonly";
	foreach($pension->result() as $p){
		$pensiontype = $p->PensionType;
		$pensionNum = $p->PensionNum;
		$monthly = $p->monthlyPension;
		$pstatus = $p->PensionStatus;
		$bank = $p->BankID;
		$bankacct = $p->Bankaccount;
		$bankBranch = $p->bankBranch;
	}
}else{
$disabled = '';
 $readonly = "";
 $pensiontype = '';
		$pensionNum = '';
		$monthly = 0;
		$pstatus = '';
		$bank = '';
		$bankacct = '';
		$bankBranch = '';
}
 ?>
 <div class="panel panel-default">	
	<div id="monthlypen" class="panel-collapse collapse in">
	<div class="panel-body">
		<div class="row form-group">
			<div class="col-md-3"><label>Pension by: </label>
				<select name="pension[pensiontype]" id="pensiontype" class="input-sm form-control" <?php echo $disabled ;?> >
				<option value="sss" <?php if($pensiontype == 'sss') $stat = true; else $stat = false;
				echo set_value('pensiontype', $pensiontype, $stat); ?>
				>SSS</option>
				<option value="gsis" <?php if($pensiontype == 'gsis') $stat = true; else $stat = false;
				echo set_value('pensiontype', $pensiontype, $stat); ?>>GSIS</option>
				<option value="afp" <?php if($pensiontype == 'afp') $stat = true; else $stat = false;
				echo set_value('pensiontype', $pensiontype, $stat); ?>>AFP</option>
			</select></div>
			<div class="col-md-3"><label>Pension Type</label> <select name="pension[pensionstatus]" id="pensionstatus" class="input-sm form-control"  <?php echo $disabled ;?> >
					<option value="retirement" <?php if($pstatus == 'retirement') $stat = true; else $stat = false;
				echo set_value('pensionstatus', $pstatus, $stat); ?>>Retirement</option>
					<option value="survivorship" <?php if($pstatus == 'survivorship') $stat = true; else $stat = false;
				echo set_value('pensionstatus', $pstatus, $stat); ?>>Survivorship</option>
					<option value="disability" <?php if($pstatus == 'disability') $stat = true; else $stat = false;
				echo set_value('pensionstatus', $pstatus, $stat); ?>>Disability</option>
				</select></div>
			<div class="col-md-3"><label>SSS/GSIS #</label><input type="text" class="form-control input-sm" value="<?php echo set_value('sss',$pensionNum);?>" placeholder="SSS/GSIS number" name="pension[sss]" required  <?php echo $readonly ;?>></div>
			<div class="col-md-3"><label>Monthly Pension</label><div class="input-group">
					<span class="input-group-addon">Php</span>
					<input type="text" name="pension[pension]" id="pension" value="<?php echo set_value("pension", $monthly, 0);?>" placeholder="00.00" class="form-control input-sm" required  <?php echo $readonly ;?>>		  
					</div></div>
		</div>
		
			<div class="row form-group">
			<div class="col-md-3"><label>Pension Date Received</label>
			<input type="text" id="pension[sssdate]"  class="form-control input-sm" value="<?php echo set_value("sssdate");?>"  placeholder="dd" name="pension[sssdate]" required  <?php echo $readonly ;?>>
           </div>
			<div class="col-md-3"><label>Bank</label>
			<?php 
				$data = array("active"=>1);
				$banks = $this->Loansmodel->get_data_from('banks', $data);
				if($banks->num_rows() > 0){ ?>
				<select name="pension[bank]" class="form-control input-sm"  ><?php foreach ($banks->result() as $b){
				 if($bank == $b->bankID) $stat = true; else $stat = false;
				?>				
				<option value="<?php echo $b->bankID;?>" <?php echo set_select('bank', $bank, $stat); ?>><?php echo $b->bankCode;?></option>
				<?php } ?></select>
				<?php }?>
			</div>
			<div class="col-md-3"><label>Branch</label><input type="text" class="form-control input-sm" placeholder="Branch" value="<?php echo set_value("branch", $bankBranch);?>" name="pension[branch]" required  <?php echo $readonly ;?>></div>
			<div class="col-md-3"><label>Account No.</label><input type="text" class="form-control input-sm" placeholder="Bank Account Number" value="<?php echo set_value("accountnum", $bankacct);?>" name="pension[accountnum]" required  <?php echo $readonly ;?>></div>
			</div>	
			
		</div>
		
		</div>		
</div>		
