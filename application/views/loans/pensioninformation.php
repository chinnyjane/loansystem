 <?php
 $pension=$this->Loansmodel->get_pensioninfo($pensionid);
 if($pension->num_rows() > 0){
 $disabled = '';
 $readonly = "";
	foreach($pension->result() as $p){
		$pensiontype = $p->PensionType;
		$pensionNum = $p->PensionNum;
		$pensionDate = $p->pensionDate;
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
		$pensionDate = '';
}
 ?>
 <form action="" method="post">
 <div class="panel panel-primary">	
<div class="panel-heading">PENSION INFORMATION</div> 
	<div class="panel-body">
		<div class="row form-group">
			<div class="col-md-6"><label>Pension by: </label>
				<select name="pensiontype" id="pensiontype" class="input-sm form-control" <?php echo $disabled ;?> >
				<option value="sss" <?php if($pensiontype == 'sss') $stat = true; else $stat = false;
				echo set_value('pensiontype', $pensiontype, $stat); ?>
				>SSS</option>
				<option value="gsis" <?php if($pensiontype == 'gsis') $stat = true; else $stat = false;
				echo set_value('pensiontype', $pensiontype, $stat); ?>>GSIS</option>
				<option value="afp" <?php if($pensiontype == 'afp') $stat = true; else $stat = false;
				echo set_value('pensiontype', $pensiontype, $stat); ?>>AFP</option>
				<option value="bfp" <?php if($pensiontype == 'bfp') $stat = true; else $stat = false;
				echo set_value('pensiontype', $pensiontype, $stat); ?>>BFP</option>
				<option value="pnp" <?php if($pensiontype == 'pnp') $stat = true; else $stat = false;
				echo set_value('pensiontype', $pensiontype, $stat); ?>>PNP</option>
			</select></div>
			<div class="col-md-6"><label>Pension Type</label> <select name="pensionstatus" id="pensionstatus" class="input-sm form-control"  <?php echo $disabled ;?> >
					<option value="survivorship" <?php if($pstatus == 'survivorship') $stat = true; else $stat = false;
				echo set_value('pensionstatus', $pstatus, $stat); ?>>Survivorship</option>
						<option value="retirement" <?php if($pstatus == 'retirement') $stat = true; else $stat = false;
				echo set_value('pensionstatus', $pstatus, $stat); ?>>Retirement</option>
						<option value="itf" <?php if($pstatus == 'itf' ) $stat = true; else $stat = false;
				echo set_value('pensionstatus', $pstatus, $stat); ?>>ITF</option>
						<option value="partialdisability" <?php if($pstatus == 'partialdisability' ) $stat = true; else $stat = false;
				echo set_value('pensionstatus', $pstatus, $stat); ?>>Partial Disability</option>
						<option value="permanentdisability" <?php if($pstatus == 'disability' or $pstatus == 'permanentdisability') $stat = true; else $stat = false;
				echo set_value('pensionstatus', $pstatus, $stat); ?>>Permanent Disability</option>					
				</select></div>
				</div>
		<div class="row form-group">
			<div class="col-md-12"><label>SSS/GSIS #</label><input type="text" class="form-control input-sm" value="<?php echo set_value('sss',$pensionNum);?>" placeholder="SSS/GSIS number" name="sss" required  <?php echo $readonly ;?>></div>
		</div>
		<div class="row form-group">
			<div class="col-md-6"><label>Monthly Pension</label><div class="input-group">
					<span class="input-group-addon">Php</span>
					<input type="text" name="pension" id="pension" value="<?php echo set_value("pension", $monthly, 0);?>" placeholder="00.00" class="form-control input-sm" required  <?php echo $readonly ;?>>		  
					</div></div>	
		
			<div class="col-md-6"><label>Pension Date Received</label>
			<input type="text" id="sssdate"  class="form-control input-sm" value="<?php echo set_value("sssdate", $pensionDate);?>"  placeholder="dd" name="sssdate" required  <?php echo $readonly ;?>>
           </div>
		 </div>
		 <div class="row form-group">
			<div class="col-md-6"><label>Bank</label>
			<?php 
				$data = array("active"=>1);
				$banks = $this->Loansmodel->get_data_from('banks', $data);
				if($banks->num_rows() > 0){ ?>
				<select name="bank" class="form-control input-sm"  ><?php foreach ($banks->result() as $b){
				 if($bank == $b->bankID) $stat = true; else $stat = false;
				?>				
				<option value="<?php echo $b->bankID;?>" <?php echo set_select('bank', $bank, $stat); ?>><?php echo $b->bankCode;?></option>
				<?php } ?></select>
				<?php }?>
			</div>
			<div class="col-md-6"><label>Branch</label><input type="text" class="form-control input-sm" placeholder="Branch" value="<?php echo set_value("branch", $bankBranch);?>" name="branch" required  <?php echo $readonly ;?>></div>
			<div class="col-md-12"><label>Bank Account No.</label><input type="text" class="form-control input-sm" placeholder="Bank Account Number" value="<?php echo set_value("accountnum", $bankacct);?>" name="accountnum" required  <?php echo $readonly ;?>></div>
			</div>			
			<div class="row form-group">
				<div class="col-md-6">
					<label>ATM/Passbook?</label>
					<select name="atm_pb" class="input-sm form-control">
						<option value="A">ATM</option>
						<option value="1">Passbook</option>												
					</select>
				</div>
				<div class="col-md-6">
					<label>ATM/Passbook #</label>
					<input type="text" name="atmnum" class="input-sm form-control" value="<?php echo set_value("atmnum",$p->atmnum);?>">
				</div>
			</div>
		</div>
		
	<div class="panel-footer">
		<input type="hidden" name="pensionid" value="<?php echo $pensionid;?>">
		<input type="submit" name="submit" value="Update Pension" class="btn btn-success">
	</div>
</div>
</form>
