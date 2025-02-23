<?php 
$tmpl = array ('table_open'          => '<table class="table  table-noborder  " >'); 
$this->table->set_template($tmpl);

$branch = $branch->row();

$this->table->set_heading("DATE : ".date("l, F d, Y", strtotime($transdate)),"BRANCH : ". $branch->branchname, array("align"=>"right","data"=>"STATUS : ". strtoupper($cmcstatus)));
echo $this->table->generate();
?>

	 <!-- TOTAL COLLECTIONS< -->
	
	<?php
		$tmpl = array ('table_open'          => '<table class="table  table-border  " >'); 
		$this->table->set_template($tmpl);
		$banktrans = $this->Cashmodel->getCMCTransactions($transid,"collection");
		$this->table->add_row(array("colspan"=>'12', "bgcolor"=>'#ccc','data'=>'COLLECTIONS'));
		$this->table->add_row('#', 'Bank Code', 'OR No','PN',array("colspan"=>'2','data'=>'Name'),'Type',array("align"=>"right","data"=>'Cash'), array("align"=>"right","data"=>'Check'), array("align"=>"right","data"=>'Online'),array("align"=>"right","data"=>'POS'), array("align"=>"right","data"=>'Collections'));
							
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
								
					
					$this->table->add_row($count, $bt->bankCode, $bt->referenceNo, $bt->PN, array("colspan"=>'2','data'=>$bt->Particulars), $bt->transType, array("align"=>"right","data"=>$cashin),array("align"=>"right","data"=>$checkin), array("align"=>"right","data"=>$onlinein),array("align"=>"right","data"=>$posin),array("align"=>"right","data"=>number_format($in,2)));				
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
			
				
				echo $this->table->add_row(array("colspan"=>"7","data"=>"TOTAL"), array("align"=>"right","data"=>$totalcash),array("align"=>"right","data"=>$totalcheck), array("align"=>"right","data"=>$totalonline), array("align"=>"right","data"=>$totalpos), array("align"=>"right","data"=>number_format($totalin,2)) );	
				//echo $this->table->generate();
			}else $this->table->add_row(array("colspan"=>'12','data'=>'No Collections.'));
			
			?>	
	
	
	<!-- TOTAL DISBURSEMENT -->	
	

			<?php
			$banktrans = $this->Cashmodel->getCMCTransactions($transid,"disbursement");		
			$this->table->add_row(array("colspan"=>'12', "bgcolor"=>'#ccc','data'=>''));		
			$this->table->add_row(array("colspan"=>'12', "bgcolor"=>'#ccc','data'=>'DISBURSEMENT'));
			$this->table->add_row('#', 'Bank Code', 'CV No','PN No', 'Check No',  'Particulars', 'Explanation',array("align"=>"right","data"=>'RFPL'),array("align"=>"right","data"=>'Expenses'), array("align"=>"right","data"=>'Releases'), array("align"=>"right","data"=>'FT'), array("align"=>"right","data"=>'Total'));			
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
						
						if(is_numeric($exp))
						$exp = ($exp ? $exp : 0);
						else
						$exp = 0;
						
						if(is_numeric($out))
						$out = ($out ? $out : 0);
						else
						$out = 0;
						
						$expout = ($exp ? number_format($exp,2) : "-");
						$relout = ($rel ? number_format($rel,2) : "-");
						$ftout = ($ft ? number_format($ft,2) : "-");
						$rfplout = ($rfpl ? number_format($rfpl,2) : "-");
						
						if(!empty($bt->branchCode))
						$bcode = "-".$bt->branchCode;
						else $bcode = "";
						
				$this->table->add_row($count, $bt->bankCode,  $bt->referenceNo, $bt->PN,  $bt->Checkno, $bt->Particulars, htmlspecialchars( $bt->explanation, ENT_QUOTES), array("align"=>"right","data"=>$rfplout),array("align"=>"right","data"=>$expout),array("align"=>"right","data"=>$relout), array("align"=>"right","data"=>$ftout),array("align"=>"right","data"=>number_format($out,2)));				
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
				
				$this->table->add_row(array("colspan"=>"7","data"=>"<b>TOTAL</b>"), array("align"=>"right","data"=>'<b>'.$totalrfpl.'</b>'),array("align"=>"right","data"=>'<b>'.$totalexp.'</b>'),array("align"=>"right","data"=>'<b>'.$totalrel.'</b>'), array("align"=>"right","data"=>'<b>'.$totalft.'</b>'), array("align"=>"right","data"=>'<b>'.number_format($totalout,2).'</b>') );	
				//echo $this->table->generate();
			}else $this->table->add_row(array("colspan"=>'12','data'=>'No Disbursements.'));
			
			?>
	

	<!-- TOTAL ADJUSTMENT -->	
	
	<?php $banktrans = $this->Cashmodel->getCMCTransactions($transid,"adjustment"); 
	$totaladj = 0;
		$this->table->add_row(array("colspan"=>'12','data'=>''));
		$this->table->add_row(array("colspan"=>'12','data'=>'ADJUSTMENT'));
			$this->table->add_row('#', 'JV No', 'Bank', array('colspan'=>'2','data'=>'Particulars'),array('colspan'=>'3','data'=>'Explanation'),array('colspan'=>'2','data'=>'Adjustment Type'), array("align"=>"right",'colspan'=>'2',"data"=>' Total Adjustment'));
		
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
					else $amount = 0;
					
					if(!empty($bt->branchCode))
						$bcode = "-".$bt->branchCode;
						else $bcode = "";
						
					$amount = ($amount ? $amount : 0);
				
					echo $this->table->add_row($count, $bt->referenceNo, $bt->bankCode, array('colspan'=>'2','data'=>$bt->Particulars), array('colspan'=>'3','data'=>$bt->explanation), array('colspan'=>'2','data'=>$bt->transType), array("align"=>"right","colspan"=>'2',"data"=>number_format($amount,2)));				
				
					$totaladj += $amount;
					$count++;
					}					
				$this->table->add_row(array("colspan"=>"10","data"=>"<b>TOTAL</b>"), array("colspan"=>"2","align"=>"right","data"=>'<b>'.number_format($totaladj,2).'</b>') );	
				
				
			}else $this->table->add_row(array("colspan"=>'12','data'=>'No Adjustments.'));
			echo $this->table->generate();
			?>
		
	
	<!-- BANKS -->
		<b>Banks</b>
		<?php
		$banks = $this->Cashmodel->getbanklistonbranch($branchid);
		if($banks->num_rows() > 0 ){
			$this->table->add_row("#", "Bank", array("align"=>"right","data"=>"Beginning Balance"), array("align"=>"right","data"=>"Total Collections"), array("align"=>"right","data"=>"Total Disbursement"), array("align"=>"right","data"=>"Total Adjustment"),array("align"=>"right","data"=>"Total End Balance"),array("align"=>"right","data"=>"Difference"), array("align"=>"right","data"=>"Bal on Bank"));
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
				
				$this->table->add_row($count, $bal->bankCode.$bcode,array("align"=>"right","data"=>$begbal), array("align"=>"right","data"=>$totalCol), array("align"=>"right","data"=>$totalDis), array("align"=>"right","data"=>$totalAdj), array("align"=>"right","data"=>$totalEnd), array("align"=>"right","data"=>$totalDif), array("align"=>"right","data"=>$totalAct));
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
			//echo '<table class="reporttable">';
			//echo $tr;
			//echo '</table>';
			$this->table->add_row(array("colspan"=>"2","data"=>"<b>TOTAL</b>"), array("align"=>"right","data"=>number_format($beginbal,2)), array("align"=>"right","data"=>number_format($tc,2)), array("align"=>"right","data"=>number_format($td,2)),  array("align"=>"right","data"=>number_format($ta,2)), array("align"=>"right","data"=>number_format($te,2)), array("align"=>"right","data"=>"<b>".number_format($di,2)."</b>"),array("align"=>"right","data"=>"<b>".number_format($ab,2)."</b>"));
			echo $this->table->generate();
		 }else { echo '<div class="alert alert-danger">'."No banks associated yet. ".'</div>'; }?>
		
		 <!-- RECAP OF DEPOSITS -->
		 
		 <h4>Recap of Deposits</h4>
		<?php if($recap->num_rows() > 0){
				$this->table->set_heading("#","Date Deposited","Bank",array("align"=>"right","data"=>"Amount"),"Type of Deposit","Time Deposited","Notes");
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
				$this->table->add_row($count,$rec->dateOfTransaction,$rec->bankCode.$bcode,array("align"=>"right","data"=>number_format($rec->amount,2)),$rec->type,date("h:i A", strtotime($rec->timeofDeposit)), $rec->notes);
				$count++;
			}
				$tr .= "<tr>";
				$tr .= "<td colspan='3'></td>";			
				$tr .= "<td align='right'>".number_format($total,2)."</td>";
				$tr .= "<td colspan='3'></td>";
				$tr .= "</tr>";
				$this->table->add_row(array("colspan"=>"3","data"=>"Total Deposit"), array("align"=>"right","data"=>number_format($total,2)),array("colspan"=>"3","data"=>"")  );
				echo $this->table->generate();
			//echo '<table class="reporttable">';
			//echo $tr;
			//echo '</table>';
		} ?>
	
		<?php 		
			//$this->table->set_heading(array("colspan"=>'3', "data"=>"<b>STATUS: ".strtoupper($cmcstatus)."</b>"));
			$this->table->add_row("Prepared By: ","Verified By: ","Approved By: ");
			if(!isset($closedBy)){
				$closedBy = '';
				$closedate = '';
			}
			if (!isset($verifiedby))
				$verifiedby = '';
			$closedBy = ($closedBy ? $closedBy : "-");
			$closedate = ($closedate ? date("F d, Y h:i:s",strtotime($closedate)) : "-");
			$verifiedby = ($verifiedby ? $verifiedby : "-");
			$verifydate = ($verifydate ? date("F d, Y h:i:s",strtotime($verifydate)) : "-");
			$approvedby = ($approvedby ? $approvedby : "-");
			$approvedate = ($approvedate ? date("F d, Y h:i:s",strtotime($approvedate)) : "-");				
			
			$this->table->add_row($closedBy."<br/>".$closedate,$verifiedby."<br/>".$verifydate,$approvedby.'<br/>'.$approvedate);
			echo $this->table->generate();		
		?>
		
		</div>
	 

