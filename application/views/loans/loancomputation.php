<!--<div class="panel panel-default">
		<div class="panel-heading">			
			  Loan Computation 
		</div>
		<div class="panel-body">
		<div class="col-md-6 "  id="feedetails">
		<?php 
		$fees = $loans['fees'];
		$loaninfo = $loans['loaninfo']->row();
		$principal = floatval(str_replace(",","",$loaninfo->principalAmount));
		$netproceeds = floatval(str_replace(",","", $loaninfo->netproceeds));
		
		if($fees->num_rows() > 0){ ?>
		<table class="table table-hover table-condensed table-bordered" width="50%" align="center">
			
			<tr>
				<td><label>Principal: </label></td>
				<td align="right"><?php
				//if($loaninfo->computation == 'net')
					//echo ($loaninfo->approvedAmount ? number_format($loaninfo->approvedAmount / ((100+$loaninfo->interest) / 100),2) :  number_format($principal ,2));
					//echo number_format($principal,2);
				//else 
					echo ($loaninfo->approvedAmount ? number_format($loaninfo->approvedAmount,2) :  number_format($principal,2));?></td>
			</tr>
			<tr>
				<td><label>Interest: </label></td>
				<td align="right"><?php echo $loaninfo->interest;?> %</td>
			</tr>
			<tr>
				<td colspan="2"><label>FEES: </label></td>
			</tr>
			<?php 
			$total = 0;			
			foreach($fees->result() as $fee) { 
			$f=  floatval(str_replace(",","",$fee->value));
			$total += $f;		
			
			?>
			<tr>
				<td><label><?php echo $fee->feeName;?> </label></td>
				<td align="right"><?php echo number_format($f,2);?></td>
			</tr>
			<?php } ?>
			<tr>
				<td><label>Total Fees</label></td>
				<td align="right"><?php echo number_format($total,2);?></td>
			</tr>
			<tr style="color:red">
				<td><label>NET PROCEEDS</label></td>
				<td align="right"><h3><?php echo number_format($netproceeds,2);?></h3></td>
			</tr>
		</table>
			
		<?php }else{
			if(!in_array($loaninfo->status, $donestatus))
				echo "NOTE: Fee details was recorded on Old System (Linux).";
		}
		?>
		</div>
		</div>
		<div class="panel-footer">
			<?php if($updateloan == true){ ?>
			<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#upfeesform" data-backdrop="static"  href="#">Update Fees</button>
			<?php } ?>
		</div>
	</div>	

	-->
	<!-- UPDATE FEES -->
<div class="modal fade" id="upfeesform" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4>UPDATE FEES</h4>
			</div>
			<form action="<?php echo base_url();?>loans/application/updatefees" method="post" id="updatefee">
			<div class="modal-body">
				<div class="row form-group"  style="margin: 5px;">
					<div class="col-md-4">
						Principal/Gross
					</div>
					<div class="col-md-8">
						<input type="text" name="principal" id="principal" style="font-weight:bold; text-align: right" value="<?php echo ($loaninfo->approvedAmount ? $loaninfo->approvedAmount : $principal);?>"  class="input-sm form-control number"  readonly>
					</div>
				</div>
				<?php 
					$totalfees = 0;
					$count = 1;					
					foreach($fees->result() as $fee) { 
						$f=  floatval(str_replace(",","",$fee->value)); 
						$totalfees += $f;
						?>
						<div class="row form-group" style="margin: 5px;">
							<div class="col-md-4">
								<?php echo $fee->feeName;?>
							</div>
							<div class="col-md-8">
								<?php 
								if($fee->comptype == 'fixed')
									$enable = "";
								else $enable = "";
								?>
								<input type="text" name="feeid[<?php echo $fee->loanfeeID;?>]"  value="<?php echo $fee->value;?>" <?php echo $enable;?> class="input-sm form-control number feeeeee" style="text-align: right" <?php if($updateloan == false) echo 'readonly';?>>
							</div>
						</div>
				<?php
					$count ++;
				}?>
				<div class="row form-group"  style="margin: 5px;">
					<div class="col-md-4">
						Total Fees
					</div>
					<div class="col-md-8">
						<input type="text" name="totalfees" id="finaltotal" value="<?php echo $totalfees;?>" style="text-align: right"   class="input-sm form-control number" readonly>
					</div>
				</div>
				<hr/>
				<div class="row form-group"  style="margin: 5px;">
					<div class="col-md-4">
						Net Proceeds
					</div>
					<div class="col-md-8">
						<input type="text" name="netproceeds" id="netpro" value="<?php echo $netproceeds;?>"  style="text-align: right" class="input-sm form-control number" readonly>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<input type="hidden" name="loanid" value="<?php echo $loanid;?>">
				<input type="hidden" name="loanstatus" value="<?php echo $loaninfo->status;?>">
				<button  class="btn btn-sm btn-default" data-dismiss="modal" data-toggle="close">Close</button>
				<?php if($updateloan == true ) { ?><button class="btn btn-sm btn-primary" type="button" id="savefees">Save Fees</button> <?php } ?>
			</div>
			</form>
		</div>
	</div>

</div>