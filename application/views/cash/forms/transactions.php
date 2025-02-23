<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script type="text/javascript">
		$(document).ready(function() {
			
		// Support for AJAX loaded modal window.
		// Focuses on first input textbox after it loads the window.
		$('[data-toggle="modal"]').click(function(e) {
			e.preventDefault();
			var url = $(this).attr('href');
			if (url.indexOf('#') == 0) {
				$(url).modal('open');
			} else {
				$.get(url, function(data) {
					$('<div class="modal fade" id="collection" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">' + data + '</div>').modal();
				}).success(function() { $('input:text:visible:first').focus(); });
			}
		});
		
		$('[data-toggle="remove"]').click(function(e) {
			e.preventDefault();
			var url = $(this).attr('href');
			bootbox.dialog({
			  message: "Are you sure you want to remove this transaction?",
			  title: "Remove Transaction",
			  buttons: {
				success: {
				  label: "Yes",
				  className: "btn-danger",
				  callback: function() {
					window.location= url;
				  }
				},
				danger: {
				  label: "No",
				  className: "btn-default",
				}
			  }
			});
		});
		
		$('#myTab a').click(function (e) {
		  e.preventDefault()
		  $(this).tab('show')
		})
		$('#myTab a:first').tab('show') // Select first tab
		
		});
		</script>
<?php 
if(isset($error)) echo '<div class="alert alert-danger">'.$error.'</div>';
if($action == 'update' and $actionid == '1') echo '<div class="alert alert-success">Transaction was updated.</div>';
elseif($action == 'remove' and $actionid == '1') echo '<div class="alert alert-danger">Transaction was removed.</div>';
if(isset($success)) echo '<div class="alert alert-success">'.$success.'</div>';
echo validation_errors();

$branch = $branch->row();
$tmpl = array ('table_open'          => '<table class="table table-bordered table-condensed table-hover">' );
$this->table->set_template($tmpl);
?>
<div class="panel panel-primary"><div class="panel-heading"><b><?php echo $branch->branchname;?> </b> Transactions as of <?php echo date("F d, Y", strtotime($transdate));?></div>
	
	<!-- LINKS -->
	<ul class="nav nav-tabs" id="myTab">
		<li class="active"><a href="#banks" data-toggle="tab">Banks</a></li>
		<li><a href="#collections" data-toggle="tab">Collections</a></li>
		<li><a href="#disbursement" data-toggle="tab">Disbursements</a></li>
		<li><a href="#adjustments" data-toggle="tab">Adjustments</a></li>
		<li><a href="#recap" data-toggle="tab">Recap of Deposits</a></li>
	</ul>
	
	<div class="tab-content">	
	  <div class="tab-pane active" id="banks"><!-- BANKS -->
		<div class="panel panel-info"><div class="panel-heading">&nbsp;<?php if($this->auth->perms("Cash.Bank Accounts", $this->auth->user_id(), 1) == true) { ?><a href="<?php echo base_url();?>cash/branches/details/<?php echo $branchid;?>" class="btn btn-primary btn-xs" >Add Bank Account</a> <?php } ?></div>	
		<?php
		$banks = $this->Cashmodel->getbanklistonbranch($branchid);
		if($banks->num_rows() > 0 ){
			$this->table->set_heading("#", "Bank", "Beginning Balance", "Total Collections", "Total Disbursement", "Total Adjustment","Total End Balance","Balance on Bank","Add Balance");
			$count = 1;
			$date = $transdate;
			$beginbal = 0; //totalbegin
			$tc = 0; //totaltc
			$td = 0; //totaltd
			$te = 0; //totalend
			$ta = 0;
			$ab=0;
			foreach($banks->result() as $bal){
				$beg = $this->Cashbalance->EndOfDateBalance($date,$bal->branchBankID)->row();
				$adj = $beg->TotalAdjadd + (-1 * $beg->TotalAdjless);
				$end = $beg->BeginBal + $beg->TotalCol - $beg->TotalDis + $adj;
				$actual = ($beg->actualbalance) ? $beg->actualbalance : 0;
				if($this->auth->perms("Cash.Transactions", $this->auth->user_id(), 3) == true and $cmcstatus == 'open'){
				$but = '<a href="'.base_url().'cash/page/forms/actualbalance/'.$transid.'/'.$bal->branchBankID.'" id="teh" data-target="#" data-toggle="modal">Update Balance</a>';
				}else $but = "n/a";
				$this->table->add_row($count, '<a href="'.base_url().'cash/daily/transaction/'.$transid.'/'.$bal->branchBankID.'">'.$bal->bankCode.'</a>',number_format($beg->BeginBal,2), number_format($beg->TotalCol,2), number_format($beg->TotalDis,2), number_format($adj,2), number_format($end,2),number_format($actual,2), $but);
				$beginbal += $beg->BeginBal;
				$tc += $beg->TotalCol; 
				$td += $beg->TotalDis; //totaltd
				$ta += $adj;
				$te += $end; //totalend
				$ab += $actual ; //totalend
				$count++;
			}
			$this->table->add_row("","TOTAL", number_format($beginbal,2), number_format($tc,2), number_format($td,2),  number_format($ta,2),number_format($te,2),number_format($ab,2));
			echo '<div class="table-responsive">';
				echo $this->table->generate();
				echo '</div>';
		 }else { echo '<div class="alert alert-danger">'."No banks associated yet. ".'</div>'; }?>
		</div>
	</div>
		
	  <div class="tab-pane" id="collections"><!-- TOTAL COLLECTIONS -->
	<div class="panel panel-success"><div class="panel-heading">&nbsp;<?php if($this->auth->perms("Cash.Collections", $this->auth->user_id(), 1) == true) { ?><button class="btn btn-success btn-xs" data-toggle="modal" data-target="#collection">Add Collection</button> <?php } ?></div>
	<div class="table-responsive">
			<?php
			$banktrans = $this->Cashmodel->getCMCTransactions($transid,"collection");
			$this->table->set_heading('#', 'Bank Code', 'OR #','PN','Name','Type','Cash', 'Check', 'Online', 'Collections','Action');			
			if($banktrans->num_rows() >0){
				$count=1;
				$totalcoll = array();				
				$totalcash = 0;
				$totalcheck = 0;
				$totalonline = 0;
				$totalin = 0;
				foreach($banktrans->result() as $bt){
					$in = ($bt->Amount_IN) ? $bt->Amount_IN : 0;
					$cash=0;
					$check=0;
					$online =0;					
						if($bt->paymentType== 'cash')
						$cash = $in;
						elseif($bt->paymentType== 'check')
						$check = $in;
						elseif($bt->paymentType== 'online')
						$online = $in;
						if($this->auth->perms("Cash.Collections", $this->auth->user_id(), 3) == true and $cmcstatus == 'open'){
						//$act = "<a href='".base_url()."cash/daily/update/collection/".$bt->BanktransID."' title='Update'><span class='glyphicon glyphicon-pencil'></span></a> &nbsp;";
						$act = "<a href='".base_url()."cash/page/forms/modifycollections/".$transid."/".$bt->BanktransID."' title='Update' data-target='#' data-toggle='modal'><span class='glyphicon glyphicon-pencil'></span></a> &nbsp;";
						if($this->auth->perms("Cash.Collections", $this->auth->user_id(), 4) == true)
						$act .= "<a href='".base_url()."cash/daily/remove/".$transid."/".$bt->BanktransID."' title='Remove'  data-target='#' data-toggle='remove'><span class='glyphicon glyphicon-remove'></span></a>";
						}else $act='n/a';
						echo $this->table->add_row($count, $bt->bankCode, $bt->referenceNo, $bt->PN, $bt->Particulars,$bt->transType,number_format($cash,2),number_format($check,2), number_format($online,2),number_format($in,2), $act);				
						//$totalcoll[$bt->bankCode][$bt->paymentType][] = $bt->Amount_IN;
						$totalin += $in;
						$totalcash += $cash;
						$totalcheck += $check;
						$totalonline += $online;
						$count++;										
				}
				echo $this->table->add_row('', '<b>TOTAL</b>','-', '-','-','-', number_format($totalcash,2),number_format($totalcheck,2), number_format($totalonline,2), number_format($totalin,2),'' );	
				echo '<div class="table-responsive">';
				echo $this->table->generate();
				echo '</div>';
			}else echo 'No transactions yet.';
			
			?>
		</div>
	
	</div>
	</div>
	<div class="tab-pane" id="disbursement"><!-- TOTAL DISBURSEMENT -->	
	<div class="panel panel-danger"><div class="panel-heading">&nbsp; <?php if($this->auth->perms("Cash.Disbursements", $this->auth->user_id(), 1) == true) { ?> <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#disburse">Add Disbursement</button> <?php } ?></div>
	<div class="table-responsive">
			<?php
			$banktrans = $this->Cashmodel->getCMCTransactions($transid,"disbursement");
			
			$this->table->set_heading('#', 'Bank', 'CV #', 'Check#', 'Particulars','Explanation','Expenses', 'Releases', 'FundTransfer', ' Total', 'Action');			
			if($banktrans->num_rows() >0){
				$count=1;
				$totalcoll = array();				
				$totalexp = 0;
				$totalrel = 0;
				$totalft = 0;
				$totalout = 0;
				foreach($banktrans->result() as $bt){
					$out = ($bt->Amount_OUT) ? $bt->Amount_OUT : 0;
					$exp=0;
					$rel=0;
					$ft =0;
					
						if(strtolower($bt->transType)== 'expenses')
						$exp = $out;
						elseif(strtolower($bt->transType)== 'releases')
						$rel = $out;
						elseif(strtolower($bt->transType)== 'fund transfer')
						$ft = $out;
						if($this->auth->perms("Cash.Disbursements", $this->auth->user_id(), 3) == true and $cmcstatus == 'open'){
						//$act = "<a href='".base_url()."cash/daily/update/disbursement/".$bt->BanktransID."' title='Update'><span class='glyphicon glyphicon-pencil'></span></a> &nbsp;";
						$act = "<a href='".base_url()."cash/page/forms/modifydisbursements/".$transid."/".$bt->BanktransID."' title='Update' data-target='#' data-toggle='modal'><span class='glyphicon glyphicon-pencil'></span></a> &nbsp;";
						if($this->auth->perms("Cash.Disbursements", $this->auth->user_id(), 4) == true)
						$act .= "<a href='".base_url()."cash/daily/remove/".$transid."/".$bt->BanktransID." 'title='Remove' data-target='#' data-toggle='remove'><span class='glyphicon glyphicon-remove'></span></a>";
						}else $act='n/a';
						
					echo $this->table->add_row($count, $bt->bankCode, $bt->referenceNo,$bt->Checkno, $bt->Particulars, $bt->explanation, number_format($exp,2),number_format($rel,2), number_format($ft,2),number_format($out,2), $act);				
					//$totalcoll[$bt->bankCode][$bt->paymentType][] = $bt->Amount_IN;
					$totalout += $out;
					$totalexp += $exp;
					$totalrel += $rel;
					$totalft += $ft;
					$count++;
										
				}
				echo $this->table->add_row('', '','', '','','<b>TOTAL</b>', '<b>'.number_format($totalexp,2).'</b>','<b>'.number_format($totalrel,2).'</b>', '<b>'.number_format($totalft,2).'</b>', '<b>'.number_format($totalout,2).'</b>' );	
				echo '<div class="table-responsive">';
				echo $this->table->generate();
				echo '</div>';
			}else echo 'No transactions yet.';
			
			?>
		</div>
	</div>
	</div>	  
	<div class="tab-pane" id="adjustments"><!-- TOTAL DISBURSEMENT -->	
	<div class="panel panel-warning"><div class="panel-heading">&nbsp; <?php if($this->auth->perms("Cash.Adjustments", $this->auth->user_id(), 1) == true) { ?> <button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#adjust">Add Adjustment</button> <?php } ?></div>
	<?php $banktrans = $this->Cashmodel->getCMCTransactions($transid,"adjustment"); ?>
	<div class="table-responsive">
			<?php
			$this->table->set_heading('#', 'JV #', 'Bank Code', 'Particulars','Adjustment Type', 'Amount',' Total Adjustment', 'Action');			
			if($banktrans->num_rows() >0){
				$count=1;
				$totalcoll = array();				
				$totalexp = 0;
				$totalrel = 0;
				$totalft = 0;
				$totalout = 0;
				foreach($banktrans->result() as $bt){
					if($bt->Amount_IN > 0) $amount = $bt->Amount_IN;
					else if($bt->Amount_OUT > 0) $amount = -1 * $bt->Amount_OUT; 
					if($this->auth->perms("Cash.Adjustments", $this->auth->user_id(), 3) == true and $cmcstatus == 'open'){
						//$act = "<a href='".base_url()."cash/daily/update/adjustment/".$bt->BanktransID."' title='Update'><span class='glyphicon glyphicon-pencil'></span></a> &nbsp;";
						$act = "<a href='".base_url()."cash/page/forms/modifyadjustment/".$transid."/".$bt->BanktransID."' title='Update' data-target='#' data-toggle='modal'><span class='glyphicon glyphicon-pencil'></span></a> &nbsp;";
						if($this->auth->perms("Cash.Adjustments", $this->auth->user_id(), 4) == true)
						$act .= "<a href='".base_url()."cash/daily/remove/".$transid."/".$bt->BanktransID."' title='Remove' data-target='#' data-toggle='remove'><span class='glyphicon glyphicon-remove'></span></a>";
						}else $act='n/a';
					echo $this->table->add_row($count, $bt->referenceNo, $bt->bankCode, $bt->Particulars, $bt->transType, number_format($amount,2),  number_format($amount,2),$act);				
					//$totalcoll[$bt->bankCode][$bt->paymentType][] = $bt->Amount_IN;
					
					$count++;
					}					
				//echo $this->table->add_row('', '', '<b>TOTAL</b>', '<b>'.number_format($totalexp,2).'</b>','<b>'.number_format($totalrel,2).'</b>', '<b>'.number_format($totalft,2).'</b>', '<b>'.number_format($totalout,2).'</b>' );
				echo '<div class="table-responsive">';
				echo $this->table->generate();
				echo '</div>';
			}else echo 'No transactions yet.';
			
			?>
		</div>
	</div>
	</div>	  
	<div class="tab-pane" id="recap">
		<div class="panel panel-warning">
			<div class="panel-heading">
			<?php if($this->auth->perms("Cash.Recap of Deposits", $this->auth->user_id(), 1) == true) { ?><button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#deposit">Add Deposit</button> <?php } ?>
			</div>
			<?php echo $this->load->view('cash/recap');?>
		</div>
	</div>
	</div>	
<div class="panel-footer">
<form action="" method="post">
		<?php if($cmcstatus == 'open' ) { ?>
			Transaction is still open.
			<?php if( $this->auth->perms("Cash.Transactions", $this->auth->user_id(), 3) == true) { ?>
			<input type="hidden" name="lock[<?php echo $transid;?>]" value="lock">
			<input name="submit" type="submit" value="LOCK CMC" class="btn btn-danger btn-xs">
			<?php } ?>
			<?php } else { ?>
			<input type="checkbox" checked disabled > Locked 
			<?php if($cmcstatus == "verified") { ?>
				<input type="checkbox" checked disabled > Verified by <?php echo $verifiedby;?>
			<?php }else{ 
				if ($this->auth->perms("Verify CMC", $this->auth->user_id(), 3) == true) { ?>		
				<input type="hidden" name="transid" value="<?php echo $transid;?>">
				<input name="submit" type="submit" value="Verify CMC" class="btn btn-success btn-xs">
				<?php }	if( $this->auth->perms("Cash.Transactions", $this->auth->user_id(), 3) == true) { ?>
				<input type="hidden" name="lock[<?php echo $transid;?>]" value="open">
				<input name="submit" type="submit" value="OPEN CMC" class="btn btn-danger btn-xs">
			<?php } 
			}
		}
		?> &nbsp; <a href="<?php echo base_url();?>cash/daily/report/<?php echo $transid;?>" class="btn btn-warning btn-xs" target="_blank">Preview CMC</a></form> 
</div>
	</div>


<?php if($cmcstatus == 'open') { ?>
<!-- COLLECTIONS -->
<div class="modal fade" id="collection" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
	<?php $this->load->view('cash/forms/collections');?>
</div>
</div>
<!-- END OF COLLECTIONS-->

<!-- DISBURSEMENTS -->
<div class="modal fade" id="disburse" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
	<?php $this->load->view('cash/forms/disbursements');?>
  </div>
</div>
<!-- BANKS -->
<?php $this->load->view('cash/forms/bank');?>

<div class="modal fade" id="adjust" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
  <?php $this->load->view('cash/forms/adjustment');?>
 </div>
</div>

<div class="modal fade" id="deposit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
  <?php $this->load->view('cash/forms/recap');?>
 </div>
</div>
<?php } ?>
