<?php 
$tmpl = array ('table_open'          => '<table class="reporttable">' );
$this->table->set_template($tmpl);

$branch = $branch->row();

?>
<b><?php echo $branch->branchname;?>  CMC as of <?php echo date("F d, Y", strtotime($transdate));?> </b>
	 <!-- TOTAL COLLECTIONS -->
	<div class="panel panel-success"><div><b>Collections</b></div>
	
			<?php
			$banktrans = $this->Cashmodel->getCMCTransactions($transid,"collection");
			//$this->table->set_heading('#', 'Bank Code', 'OR No','PN','Name','Type','Cash', 'Check', 'Online', 'Collections');
				$tr = "<tr>";
					$tr .= "<th>#</th>";
					$tr .= "<th>Bank</th>";
					$tr .= "<th>OR No</th>";
					$tr .= "<th>PN No</th>";
					$tr .= "<th>Name</th>";
					$tr .= "<th>Type</th>";
					$tr .= "<th align='right'>Cash</th>";
					$tr .= "<th align='right'>Check</th>";
					$tr .= "<th align='right'>Online</th>";
					$tr .= "<th align='right'>POS</th>";
					$tr .= "<th align='right'>Collections</th>";
					$tr .= "</tr>";					
			if($banktrans->num_rows() >0){
				$count=1;
				$totalcoll = array();				
				$totalcash = 0;
				$totalcheck = 0;
				$totalonline = 0;
				$totalpos = 0;
				$totalin = 0;
				foreach($banktrans->result() as $bt){
					$in = ($bt->Amount_IN) ? $bt->Amount_IN : 0;
					$cash=0;
					$check=0;
					$online =0;
					$pos =0;
					
						if($bt->paymentType== 'cash')
						$cash = $in;
						elseif($bt->paymentType== 'check')
						$check = $in;
						elseif($bt->paymentType== 'online')
						$online = $in;
						elseif($bt->paymentType== 'POS')
						$pos = $in;
						
						$cashin = ($cash ? number_format($cash,2) : "-");
						$checkin = ($check ? number_format($check,2) : "-");
						$onlinein = ($online ? number_format($online,2) : "-");
						$posin = ($pos ? number_format($pos,2) : "-");
						
						if(!empty($bt->branchCode))
						$bcode = "-".$bt->branchCode;
						else $bcode = "";
						
					$tr .= "<tr>";
					$tr .= "<td>".$count."</td>";
					$tr .= "<td>".$bt->bankCode.$bcode."</td>";
					$tr .= "<td>".$bt->referenceNo."</td>";
					$tr .= "<td>".$bt->PN."</td>";
					$tr .= "<td>".$bt->Particulars."</td>";
					$tr .= "<td>".$bt->transType."</td>";
					$tr .= "<td align='right'>".$cashin."</td>";
					$tr .= "<td align='right'>".$checkin."</td>";
					$tr .= "<td align='right'>".$onlinein."</td>";
					$tr .= "<td align='right'>".$posin."</td>";
					$tr .= "<td align='right'>".number_format($in,2)."</td>";
					$tr .= "</tr>";					
					
					//echo $this->table->add_row($count, $bt->bankCode, $bt->referenceNo, $bt->PN, $bt->Particulars, $bt->transType, number_format($cash,2),number_format($check,2), number_format($online,2),number_format($in,2));				
					//$totalcoll[$bt->bankCode][$bt->paymentType][] = $bt->Amount_IN;
					$totalin += $in;
					$totalcash += $cash;
					$totalcheck += $check;
					$totalonline += $online;
					$totalpos += $pos;
					$count++;
										
				}
				
				$totalcash = ($totalcash ? number_format($totalcash,2) : "-");
				$totalcheck = ($totalcheck ? number_format($totalcheck,2) : "-");
				$totalonline = ($totalonline ? number_format($totalonline,2) : "-");
				$totalpos = ($totalpos ? number_format($totalpos,2) : "-");
						
				$tr .= "<tr>";
					$tr .= "<td></td>";
					$tr .= "<td>Total</td>";
					$tr .= "<td></td>";
					$tr .= "<td></td>";
					$tr .= "<td></td>";
					$tr .= "<td></td>";
					$tr .= "<td align='right'>".$totalcash."</td>";
					$tr .= "<td align='right'>".$totalcheck."</td>";
					$tr .= "<td align='right'>".$totalonline."</td>";
					$tr .= "<td align='right'>".$totalpos."</td>";
					$tr .= "<td align='right'>".number_format($totalin,2)."</td>";
					$tr .= "</tr>";
				echo '<table class="reporttable">';
				echo $tr;
				echo '</table>';
				//echo $this->table->add_row('', '<b>TOTAL</b>','-', '-', '-','-', number_format($totalcash,2),number_format($totalcheck,2), number_format($totalonline,2), number_format($totalin,2) );	
				//echo $this->table->generate();
			}else echo 'No transactions yet.';
			
			?>
		
	
	</div>
	<!-- TOTAL DISBURSEMENT -->	
	<div class="panel panel-danger"><div><b>Disbursements</b></div>

			<?php
			$banktrans = $this->Cashmodel->getCMCTransactions($transid,"disbursement");
				
			$tr = "<tr>";
			$tr .= "<th>#</th>";
			$tr .= "<th>Bank</th>";
			$tr .= "<th>CV No</th>";
			$tr .= "<th>PN No</th>";
			$tr .= "<th>Check No</th>";
			$tr .= "<th>Particulars</th>";
			$tr .= "<th>Explanation</th>";
			$tr .= "<th align='right'>RFPL</th>";
			$tr .= "<th align='right'>Expenses</th>";
			$tr .= "<th align='right'>Releases</th>";
			$tr .= "<th align='right'>Fund Transfer</th>";
			$tr .= "<th align='right'>Total</th>";
			$tr .= "</tr>";	
			//$this->table->set_heading('#', 'Bank Code', 'CV No', 'Check No',  'Particulars', 'Explanation','Expenses', 'Releases', 'Fund Transfer', ' Total Disbursement');			
			if($banktrans->num_rows() >0){
				$count=1;
				$totalcoll = array();				
				$totalexp = 0;
				$totalrel = 0;
				$totalft = 0;
				$totalout = 0;
				$totalrfpl = 0;
				foreach($banktrans->result() as $bt){
					$out = ($bt->Amount_OUT) ? $bt->Amount_OUT : 0;
					$exp=0;
					$rel=0;
					$ft =0;
					$rfpl =0;
					
						if(strtolower($bt->transType)== 'expenses')
						$exp = $out;
						elseif(strtolower($bt->transType)== 'releases')
						$rel = $out;
						elseif(strtolower($bt->transType)== 'fund transfer')
						$ft = $out;
						elseif(strtolower($bt->transType)== 'rfpl')
						$rfpl = $out;
						
						$expout = ($exp ? number_format($exp,2) : "-");
						$relout = ($rel ? number_format($rel,2) : "-");
						$ftout = ($ft ? number_format($ft,2) : "-");
						$rfplout = ($rfpl ? number_format($rfpl,2) : "-");
						
						if(!empty($bt->branchCode))
						$bcode = "-".$bt->branchCode;
						else $bcode = "";
						
					$tr .= "<tr>";
					$tr .= "<td>".$count."</td>";
					$tr .= "<td>".$bt->bankCode.$bcode."</td>";
					$tr .= "<td>".$bt->referenceNo."</td>";
					$tr .= "<td>".$bt->PN."</td>";
					$tr .= "<td>".$bt->Checkno."</td>";
					$tr .= "<td>".$bt->Particulars."</td>";
					$tr .= "<td>".$bt->explanation."</td>";
					$tr .= "<td align='right'>".$rfplout."</td>";
					$tr .= "<td align='right'>".$expout."</td>";
					$tr .= "<td align='right'>".$relout."</td>";
					$tr .= "<td align='right'>".$ftout."</td>";
					$tr .= "<td align='right'>".number_format($out,2)."</td>";
					$tr .= "</tr>";	
					
					//echo $this->table->add_row($count, $bt->bankCode,  $bt->referenceNo,  $bt->Checkno, $bt->Particulars, $bt->explanation, number_format($exp,2),number_format($rel,2), number_format($ft,2),number_format($out,2));				
					//$totalcoll[$bt->bankCode][$bt->paymentType][] = $bt->Amount_IN;
					$totalout += $out;
					$totalexp += $exp;
					$totalrel += $rel;
					$totalft += $ft;
					$totalrfpl += $rfpl;
					$count++;
										
				}
				
				$totalexp = ($totalexp ? number_format($totalexp,2) : "-");
				$totalrel = ($totalrel ? number_format($totalrel,2) : "-");
				$totalft = ($totalft ? number_format($totalft,2) : "-");
				$totalrfpl = ($totalrfpl ? number_format($totalrfpl,2) : "-");
				
				$tr .= "<tr>";
					$tr .= "<td></td>";
					$tr .= "<td>Total</td>";
					$tr .= "<td></td>";
					$tr .= "<td></td>";
					$tr .= "<td></td>";
					$tr .= "<td></td>";
					$tr .= "<td></td>";
					$tr .= "<td align='right'><b>".$totalrfpl."</b></td>";
					$tr .= "<td align='right'><b>".$totalexp."</b></td>";
					$tr .= "<td align='right'><b>".$totalrel."</b></td>";
					$tr .= "<td align='right'><b>".$totalft."</b></td>";
					$tr .= "<td align='right'><b>".number_format($totalout,2)."</b></td>";
					$tr .= "</tr>";
				echo '<table class="reporttable">';
				echo $tr;
				echo '</table>';
				//echo $this->table->add_row('', '', '', '', '','<b>TOTAL</b>', '<b>'.number_format($totalexp,2).'</b>','<b>'.number_format($totalrel,2).'</b>', '<b>'.number_format($totalft,2).'</b>', '<b>'.number_format($totalout,2).'</b>' );	
				//echo $this->table->generate();
			}else echo 'No transactions yet.';
			
			?>
	
	</div>
	<!-- TOTAL DISBURSEMENT -->	
	<div class="panel panel-warning"><div><b>Adjustments</b></div>
	<?php $banktrans = $this->Cashmodel->getCMCTransactions($transid,"adjustment"); ?>
	
			<?php
			//$this->table->set_heading('#', 'JV No', 'Bank Code', 'Particulars','Explanation','Adjustment Type', 'Amount',' Total Adjustment');
			$tr = "<tr>";
			$tr .= "<th>#</th>";
			$tr .= "<th>JV No</th>";
			$tr .= "<th>Bank</th>";
			$tr .= "<th>Particulars</th>";
			$tr .= "<th>Explanation</th>";
			$tr .= "<th>Adjustment Type</th>";
			$tr .= "<th align='right'>Amount</th>";
			$tr .= "<th align='right'>Total Adjustment</th>";
			$tr .= "</tr>";	
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
					
					if(!empty($bt->branchCode))
						$bcode = "-".$bt->branchCode;
						else $bcode = "";
					
					$tr .= "<tr>";
					$tr .= "<td>".$count."</td>";					
					$tr .= "<td>".$bt->referenceNo."</td>";
					$tr .= "<td>".$bt->bankCode.$bcode."</td>";
					$tr .= "<td>".$bt->Particulars."</td>";
					$tr .= "<td>".$bt->explanation."</td>";
					$tr .= "<td>".$bt->transType."</td>";
					$tr .= "<td align='right'>".number_format($amount,2)."</td>";
					$tr .= "<td align='right'>".number_format($amount,2)."</td>";
					$tr .= "</tr>";	
					//echo $this->table->add_row($count, $bt->referenceNo, $bt->bankCode, $bt->Particulars, $bt->explanation, $bt->transType, number_format($amount,2),  number_format($amount,2));				
					//$totalcoll[$bt->bankCode][$bt->paymentType][] = $bt->Amount_IN;
					
					$count++;
					}					
				//echo $this->table->add_row('', '', '<b>TOTAL</b>', '<b>'.number_format($totalexp,2).'</b>','<b>'.number_format($totalrel,2).'</b>', '<b>'.number_format($totalft,2).'</b>', '<b>'.number_format($totalout,2).'</b>' );	
				//echo $this->table->generate();
				echo '<table class="reporttable">';
				echo $tr;
				echo '</table>';
			}else echo 'No transactions yet.';
			
			?>
		
	</div>
	<!-- BANKS -->
		<div class="panel panel-info"><div>Banks</div>	
		<?php
		$banks = $this->Cashmodel->getbanklistonbranch($branchid);
		if($banks->num_rows() > 0 ){
			//$this->table->set_heading("#", "Bank", "Beginning Balance", "Total Collections", "Total Disbursement", "Total Adjustment","Total End Balance");
			$tr = "<tr>";
			$tr .= "<th>#</th>";
			$tr .= "<th>Bank</th>";
			$tr .= "<th align='right'>Beginning Balance</th>";
			$tr .= "<th align='right'>Total Collections</th>";
			$tr .= "<th align='right'>Total Disbursement</th>";
			$tr .= "<th align='right'>Total Adjustment</th>";
			$tr .= "<th align='right'>Total End Balance</th>";
			$tr .= "<th align='right'>Difference</th>";
			$tr .= "<th align='right'>Bal on Bank</th>";
			$tr .= "</tr>";
			$count = 1;
			$date = $transdate;
			$beginbal = 0; //totalbegin
			$tc = 0; //totaltc
			$td = 0; //totaltd
			$te = 0; //totalend
			$ta = 0;
			$di = 0;
			$ab = 0;
			foreach($banks->result() as $bal){
				$beg = $this->Cashbalance->EndOfDateBalance($date,$bal->branchBankID)->row();
				$adj = $beg->TotalAdjadd + (-1 * $beg->TotalAdjless);
				$end = $beg->BeginBal + $beg->TotalCol - ($beg->TotalDis) + ($adj);
				$actual = ($beg->actualbalance) ? $beg->actualbalance : 0;
				$dif = $end - $actual;
				
				$begbal = ($beg->BeginBal ? number_format($beg->BeginBal,2) : "-");
				$totalCol = ($beg->TotalCol ? number_format($beg->TotalCol ,2) : "-");
				$totalDis = ($beg->TotalDis ? number_format($beg->TotalDis ,2) : "-");
				$totalAdj = ($adj ? number_format($adj ,2) : "-");
				$totalEnd = ($end ? number_format($end ,2) : "-");
				$totalDif = ($dif ? number_format($dif,2) : "-");
				$totalAct = ($actual ? number_format($actual,2) : "-");
				
				if(!empty($bal->branchCode))
						$bcode = "-".$bal->branchCode;
						else $bcode = "";
				
				//$this->table->add_row($count, $bal->bankCode,number_format($beg->BeginBal,2), number_format($beg->TotalCol,2), number_format($beg->TotalDis,2), number_format($adj,2), number_format($end,2));
				$tr .= "<tr>";
				$tr .= "<td>".$count."</td>";
				$tr .= "<td>".$bal->bankCode.$bcode."</td>";
				$tr .= "<td align='right'>".$begbal."</td>";
				$tr .= "<td align='right'>".$totalCol."</td>";
				$tr .= "<td align='right'>".$totalDis."</td>";
				$tr .= "<td align='right'>".$totalAdj."</td>";
				$tr .= "<td align='right'>".$totalEnd."</td>";
				$tr .= "<td align='right'>".$totalDif."</td>";
				$tr .= "<td align='right'>".$totalAct."</td>";
				$tr .= "</tr>";
				$beginbal += $beg->BeginBal;
				$tc += $beg->TotalCol; 
				$td += $beg->TotalDis; //totaltd
				$ta += $adj;
				$te += $end; //totalend
				$di += $dif; //totalend
				$ab += $actual ; //totalend
				$count++;
			}
			$tr .= "<tr>";
			$tr .= "<td></td>";
			$tr .= "<td><b>Total</b></td>";
			$tr .= "<td align='right'><b>".number_format($beginbal,2)."</b></td>";
			$tr .= "<td align='right'><b>".number_format($tc,2)."</b></td>";
			$tr .= "<td align='right'><b>".number_format($td,2)."</b></td>";
			$tr .= "<td align='right'><b>".number_format($ta,2)."</b></td>";
			$tr .= "<td align='right'><b>".number_format($te,2)."</b></td>";
			$tr .= "<td align='right'><b>".number_format($di,2)."</b></td>";
			$tr .= "<td align='right'><b>".number_format($ab,2)."</b></td>";
			$tr .= "</tr>";
			echo '<table class="reporttable">';
			echo $tr;
			echo '</table>';
			//$this->table->add_row("","TOTAL", number_format($beginbal,2), number_format($tc,2), number_format($td,2),  number_format($ta,2),number_format($te,2));
			//echo $this->table->generate();
		 }else { echo '<div class="alert alert-danger">'."No banks associated yet. ".'</div>'; }?>
		 </div>
		 <!-- RECAP OF DEPOSITS -->
		 
		 <div class="panel panel-info"><div>Recap of Deposits</div>
		<?php if($recap->num_rows() > 0){
				$this->table->set_heading("#","Date Deposited","Bank","Amount","Type of Deposit","Time Deposited","Notes", "Action");
				$tr = "<tr>";
				$tr .= "<th>#</th>";
				$tr .= "<th>Date Deposited</th>";
				$tr .= "<th>Bank</th>";
				$tr .= "<th align='right'>Amount</th>";
				$tr .= "<th>Type of Deposit</th>";
				$tr .= "<th>Time Deposited</th>";
				$tr .= "<th>Notes</th>";
				$tr .= "</tr>";
				$count=1;
				$total = 0;
			foreach($recap->result() as $rec) {
			
				if(!empty($rec->branchCode))
						$bcode = "-".$rec->branchCode;
						else $bcode = "";
			
				$tr .= "<tr>";
				$tr .= "<td>".$count."</td>";
				$tr .= "<td>".$rec->dateOfTransaction."</td>";
				$tr .= "<td>".$rec->bankCode.$bcode."</td>";
				$tr .= "<td align='right'>".number_format($rec->amount,2)."</td>";
				$tr .= "<td>".$rec->type."</td>";
				$tr .= "<td>".date("h:i A", strtotime($rec->timeofDeposit))."</td>";
				$tr .= "<td>".$rec->notes."</td>";
				$tr .= "</tr>";
				$total += $rec->amount;
				//$this->table->add_row($count,$rec->dateOfTransaction,$rec->bankCode,$rec->amount,$rec->type,date("h:i A", strtotime($rec->timeofDeposit)), $rec->notes, $act);
				$count++;
			}
				$tr .= "<tr>";
				$tr .= "<td colspan='3'></td>";			
				$tr .= "<td align='right'>".number_format($total,2)."</td>";
				$tr .= "<td colspan='3'></td>";
				$tr .= "</tr>";
			echo '<table class="reporttable">';
			echo $tr;
			echo '</table>';
		} ?>
		</div>
		<div class="panel-footer">
		<?php
		switch ($cmcstatus){
		case "open":
			echo "Transaction is still open.";
			break;
		case "lock":
			echo "Prepared by : ";
			echo "<b>".$closedBy."</b>, ";
			echo date("m-d-Y h:i:s",strtotime($closedate));
			echo "&nbsp;";
			echo "&nbsp;";
		break;
		case "verified":
			echo "Prepared by : ";
			echo "<b>".$closedBy."</b>, ";
			echo date("m-d-Y h:i:s",strtotime($closedate));
			echo "&nbsp;";
			echo "&nbsp;";
			echo "Verified by : ";
			echo "<b>".$verifiedby."</b>, ";
			echo date("m-d-Y h:i:s",strtotime($verifydate));
			echo "&nbsp;";
			echo "&nbsp;";
		break;
		case "approved";
			echo "Prepared by : ";
			echo "<b>".$closedBy."</b>, ";
			echo date("m-d-Y h:i:s",strtotime($closedate));
			echo "&nbsp;";
			echo "&nbsp;";
			echo "Verified by : ";
			echo "<b>".$verifiedby."</b>, ";
			echo date("m-d-Y h:i:s",strtotime($verifydate));
			echo "&nbsp;";
			echo "&nbsp;";
			echo "Approved by : ";
			echo "<b>".$approvedby."</b>, ";
			echo date("m-d-Y h:i:s",strtotime($approvedate));
			echo "&nbsp;";
			echo "&nbsp;";
			
		break;
	} ?>
		
		</div>
		</div>
	 

