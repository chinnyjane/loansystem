<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<?php if(isset($error)) echo '<div class="alert alert-danger">'.$error.'</div>';
if(isset($success)) echo '<div class="alert alert-success">'.$success.'</div>';
echo validation_errors();
/*foreach($cmctrans->result() as $tr){
	$transdate = $tr->dateTransaction;
	$opendate = $tr->dateOpen;
	$cmcstatus = $tr->status;
	$branchid = $tr->branchID;
}
if($_POST){
echo "<pre>";
print_r($_POST);
echo "</pre>";
}*/

$branch = $branch->row();
?><?php 
		$tmpl = array ('table_open'          => '<table class="table table-bordered">' );
		$this->table->set_template($tmpl);
		?>
<div class="panel panel-primary"><div class="panel-heading"><b><?php echo $branch->branchname;?> </b> Transactions</div>
<div class="panel-body">
CMC Date: <?php echo date("F d, Y", strtotime($transdate));?>
</div>	
	<!-- LINKS -->
	<ul class="nav nav-tabs" id="myTab">
		<li class="active"><a href="#banks" data-toggle="tab">Banks</a></li>
		<li><a href="#collections" data-toggle="tab">Collections</a></li>
		<li><a href="#disbursement" data-toggle="tab">Disbursements</a></li>
		<li><a href="#adjustments" data-toggle="tab">Adjustments</a></li>
	</ul>
	<script>
	  $(function () {
		$('#myTab a').click(function (e) {
		  e.preventDefault()
		  $(this).tab('show')
		})
		$('#myTab a:first').tab('show') // Select first tab
	  })	  
	</script>
	<div class="tab-content">	
	  <div class="tab-pane active" id="banks"><!-- BANKS -->
		<div class="panel panel-info"><div class="panel-heading">&nbsp;<?php if($this->auth->perms("Cash.Bank Accounts", $this->auth->user_id(), 1) == true) { ?><a href="<?php echo base_url();?>cash/branches/details/<?php echo $branchid;?>" class="btn btn-primary btn-xs" >Add Bank Account</a> <?php } ?></div>	
		<?php
		$banks = $this->Cashmodel->getbanklistonbranch($branchid);
		if($banks->num_rows() > 0 ){
			$this->table->set_heading("#", "Bank", "Beginning Balance", "Total Collections", "Total Disbursement", "Total Adjustment","Total End Balance");
			$count = 1;
			$date = $transdate;
			$beginbal = 0; //totalbegin
			$tc = 0; //totaltc
			$td = 0; //totaltd
			$te = 0; //totalend
			$ta = 0;
			foreach($banks->result() as $bal){
				$beg = $this->Cashbalance->EndOfDateBalance($date,$bal->branchBankID)->row();
				$adj = $beg->TotalAdjadd + (-1 * $beg->TotalAdjless);
				$end = $beg->BeginBal + $beg->TotalCol - $beg->TotalDis + $adj;
				$this->table->add_row($count, $bal->bankCode,number_format($beg->BeginBal,2), number_format($beg->TotalCol,2), number_format($beg->TotalDis,2), number_format($adj,2), number_format($end,2));
				$beginbal += $beg->BeginBal;
				$tc += $beg->TotalCol; 
				$td += $beg->TotalDis; //totaltd
				$ta += $adj;
				$te += $end; //totalend
				$count++;
			}
			$this->table->add_row("","TOTAL", number_format($beginbal,2), number_format($tc,2), number_format($td,2),  number_format($ta,2),number_format($te,2));
			echo $this->table->generate();
		 }else { echo '<div class="alert alert-danger">'."No banks associated yet. ".'</div>'; }?>
		</div></div>
	  <div class="tab-pane" id="collections"><!-- TOTAL COLLECTIONS -->
	<div class="panel panel-success"><div class="panel-heading">&nbsp;<?php if($this->auth->perms("Cash.Collections", $this->auth->user_id(), 1) == true) { ?><button class="btn btn-success btn-xs" data-toggle="modal" data-target="#collection" data-backdrop="static" >Add Collection</button> <?php } ?></div>
	<div class="table-responsive">
			<?php
			$banktrans = $this->Cashmodel->getCMCTransactions($transid,"collection");
			$this->table->set_heading('#', 'Bank Code', 'OR No','PN','Name','Type','Cash', 'Check', 'Online', 'Collections');			
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
						echo $this->table->add_row($count, $bt->bankCode, $bt->referenceNo, $bt->PN, $bt->Particulars,$bt->transType,number_format($cash,2),number_format($check,2), number_format($online,2),number_format($in,2));				
						//$totalcoll[$bt->bankCode][$bt->paymentType][] = $bt->Amount_IN;
						$totalin += $in;
						$totalcash += $cash;
						$totalcheck += $check;
						$totalonline += $online;
						$count++;										
				}
				echo $this->table->add_row('', '<b>TOTAL</b>','-', '-','-','-', number_format($totalcash,2),number_format($totalcheck,2), number_format($totalonline,2), number_format($totalin,2) );	
				echo $this->table->generate();
			}else echo 'No transactions yet.';
			
			?>
		</div>
	
	</div>
	</div>
	<div class="tab-pane" id="disbursement"><!-- TOTAL DISBURSEMENT -->	
	<div class="panel panel-danger"><div class="panel-heading">&nbsp; <?php if($this->auth->perms("Cash.Disbursements", $this->auth->user_id(), 1) == true) { ?> <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#disburse" data-backdrop="static" >Add Disbursement</button> <?php } ?></div>
	<div class="table-responsive">
			<?php
			$banktrans = $this->Cashmodel->getCMCTransactions($transid,"disbursement");
			
			$this->table->set_heading('#', 'Bank Code', 'CV No', 'Check No', 'Particulars','Explanation','Expenses', 'Releases', 'Fund Transfer', ' Total Disbursement');			
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
					echo $this->table->add_row($count, $bt->bankCode, $bt->referenceNo,$bt->Checkno, $bt->Particulars, $bt->explanation, number_format($exp,2),number_format($rel,2), number_format($ft,2),number_format($out,2));				
					//$totalcoll[$bt->bankCode][$bt->paymentType][] = $bt->Amount_IN;
					$totalout += $out;
					$totalexp += $exp;
					$totalrel += $rel;
					$totalft += $ft;
					$count++;
										
				}
				echo $this->table->add_row('', '','', '','','<b>TOTAL</b>', '<b>'.number_format($totalexp,2).'</b>','<b>'.number_format($totalrel,2).'</b>', '<b>'.number_format($totalft,2).'</b>', '<b>'.number_format($totalout,2).'</b>' );	
				echo $this->table->generate();
			}else echo 'No transactions yet.';
			
			?>
		</div>
	</div>
	</div>	  
	<div class="tab-pane" id="adjustments"><!-- TOTAL DISBURSEMENT -->	
	<div class="panel panel-warning"><div class="panel-heading">&nbsp; <?php if($this->auth->perms("Cash.Adjustments", $this->auth->user_id(), 1) == true) { ?> <button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#adjust" data-backdrop="static" >Add Adjustment</button> <?php } ?></div>
	<?php $banktrans = $this->Cashmodel->getCMCTransactions($transid,"adjustment"); ?>
	<div class="table-responsive">
			<?php
			$this->table->set_heading('#', 'JV No', 'Bank Code', 'Particulars','Adjustment Type', 'Amount',' Total Adjustment', 'Action');			
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
					 if($this->auth->perms("Cash.Adjustments", $this->auth->user_id(), 4) == true and $cmcstatus == 'open') { $del = '<button class="btn btn-danger btn-xs" onclick="javascript:delete($bt->BanktransID);">X</button>'; }
					 else $del = 'n/a';
					echo $this->table->add_row($count, $bt->referenceNo, $bt->bankCode, $bt->Particulars, $bt->transType, number_format($amount,2),  number_format($amount,2),$del);				
					//$totalcoll[$bt->bankCode][$bt->paymentType][] = $bt->Amount_IN;
					
					$count++;
					}					
				//echo $this->table->add_row('', '', '<b>TOTAL</b>', '<b>'.number_format($totalexp,2).'</b>','<b>'.number_format($totalrel,2).'</b>', '<b>'.number_format($totalft,2).'</b>', '<b>'.number_format($totalout,2).'</b>' );	
				echo $this->table->generate();
			}else echo 'No transactions yet.';
			
			?>
		</div>
	</div>
	</div>	  
	
	</div>	
<div class="panel-footer">
<form action="" method="post">
		<?php
		if( $this->auth->perms("Cash.Transactions", $this->auth->user_id(), 3) == true) {
		if($cmcstatus == 'open' ) { ?>
		Transaction is still open.
		<input type="hidden" name="lock[<?php echo $transid;?>]" value="lock">
		<input name="submit" type="submit" value="LOCK CMC" class="btn btn-danger btn-xs">
		<?php } /*else { ?>
		Transaction is already locked.
		<input type="hidden" name="lock[<?php echo $transid;?>]" value="open">
		<input name="submit" type="submit" value="OPEN CMC" class="btn btn-success btn-xs">
		<?php } */
		}?> &nbsp; <a href="<?php echo base_url();?>cash/daily/report/<?php echo $transid;?>" class="btn btn-warning btn-xs" target="_blank">Print CMC</a></form> 
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
<?php } ?>

