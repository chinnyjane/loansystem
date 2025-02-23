<?php $adj = $this->Cashbalance->getTransactionType("Recap of Deposits");?>
<form class="form-horizontal" method="post" action="">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Recap of Deposit</h4>
      </div>
      <div class="modal-body">
	  <div class="row form-group">
	  <div class="col-xs-6"><label>Bank</label>
			<select name="bankID" class="form-control input-sm" required>
				<?php foreach($banks->result() as $ba){ ?>
					<option value="<?php echo $ba->branchBankID;?>"><?php echo $ba->bankCode."-".$ba->bankBranch;?></option>
				<?php } ?>
			</select>
		</div>		
		<div class="col-xs-6"><label>Type of Deposit</label>
			<select name="transtype" class="form-control input-sm" required>
				<option disabled selected>Select</option>
				<?php 
				if($adj->num_rows() >0){
					foreach($adj->result() as $d){
						echo "<option value='".$d->transTypeID."'>".$d->transType."</option>";
					}
				}
				?>
			</select>
		</div>			
		</div>
		<div class="row form-group">
		<div class="col-xs-4"><label>Amount</label>
		<input type="text" name="amount" placeholder="00.00"  class="form-control input-sm" required>
		</div>	
        <div class="col-md-4"><label>Date Deposited</label>
        	<input type="date" id="transdate" name="transdate" placeholder="mm-dd-yyyy" class="form-control input" required>		
		
        </div>
		<div class="col-xs-4"><label>Time Deposited</label>
		<div class='input-group date' id='datetimepicker4'>
                    <input type='time' class="form-control" name="timedep" placeholder="<?php echo $this->auth->mtime();?>" required/>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span>
                    </span>
        </div>
	
		</div>
		
	  </div>
		<div class="row form-group">
		<div class="col-xs-12"><label>Notes <i>(optional)</i></label>
		<textarea class="form-control input" name="notes"></textarea>
		</div>
		</div>
	  </div>
	   <div class="modal-footer">
	   <!--<input type="hidden" name="transdate" value="<?php //if (isset($transdate)) echo $transdate; else echo $this->auth->localdate();?>">-->
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
       <input type="submit" class="btn btn-sm btn-warning " name="submit" value="Add Deposit">
      </div>
	</div>
</form>
	