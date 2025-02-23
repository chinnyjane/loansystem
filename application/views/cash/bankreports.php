<?php 
$tmpl = array ('table_open'          => '<table class="table  table-noborder  " >'); 
$this->table->set_template($tmpl);

$branch = $branch->row();

$this->table->set_heading("DATE : ".date("l, F d, Y", strtotime($transdate)),"BRANCH : ". $branch->branchname, array("align"=>"right","data"=>"STATUS : ". strtoupper($cmcstatus)));
echo $this->table->generate();

$transtype = '';

$tmpl = array ('table_open' => '<table class="table  table-border  " >'); 
				$this->table->set_template($tmpl);

foreach($banks->result() as $bank){
	$trans = $this->Cashmodel->CMCbyBank($bank->branchBankID, $transid);
	
	$b = $bank->bankCode;
	if(!empty($bank->branchCode))
		$b .= "-".$bank->branchCode;
	$count = 0;
	$this->table->add_row(array("colspan"=>'12', "bgcolor"=>'#ccc','data'=>strtoupper('<b>BANK: '.$b.'</b>')));
	
	$totalcash = 0;
	$totalcheck = 0;
	$totalonline = 0;
	$totalpos = 0;
	$totalin = 0;
	$totalexp = 0;
	$totalrel = 0;
	$totalft = 0;
	$totalout = 0;
	$totalrfpl = 0;
	$totaladj = 0;
	$transtype = '';
	if($trans->num_rows() > 0 ){
		$colcontent = array();
		$discontent = array();
		$adjcontent = array();
		foreach($trans->result() as $tr){
			if($tr->transCatName != $transtype){ $transtype = $tr->transCatName; $count = 0;}
				if($tr->transCatName == 'collection'){
					$colheader = array('#',  'OR No','PN',array("colspan"=>'2','data'=>'Name'),'Type',array("align"=>"right","data"=>'Cash'), array("align"=>"right","data"=>'Check'), array("align"=>"right","data"=>'Online'),array("align"=>"right","data"=>'POS'), array("align"=>"right","data"=>'Collections'));
						$in = ($tr->Amount_IN) ? $tr->Amount_IN : 0;
						$cash=0;
						$check=0;
						$online =0;
						$pos =0;
						$count++;
						if($tr->typeOfPayment== 'cash')
						$cash = $in;
						elseif($tr->typeOfPayment== 'check')
						$check = $in;
						elseif($tr->typeOfPayment== 'online')
						$online = $in;
						elseif($tr->typeOfPayment== 'POS')
						$pos = $in;
						
						$cashin = ($cash ? number_format($cash,2) : "-");
						$checkin = ($check ? number_format($check,2) : "-");
						$onlinein = ($online ? number_format($online,2) : "-");
						$posin = ($pos ? number_format($pos,2) : "-");				
						$totalin += $in;
						$totalcash += $cash;
						$totalcheck += $check;
						$totalonline += $online;
						$totalpos += $pos;						
						
					$colcontent[] = array($count, $tr->referenceNo, $tr->PN, array("colspan"=>'2','data'=>$tr->Particulars), $tr->transType, array("align"=>"right","data"=>$cashin),array("align"=>"right","data"=>$checkin), array("align"=>"right","data"=>$onlinein),array("align"=>"right","data"=>$posin),array("align"=>"right","data"=>number_format($in,2)));
					
				}elseif ($tr->transCatName == 'disbursement'){
					$disheader = array('#',  'CV No','PN No', 'Check No',  'Particulars', 'Explanation',array("align"=>"right","data"=>'RFPL'),array("align"=>"right","data"=>'Expenses'), array("align"=>"right","data"=>'Releases'), array("align"=>"right","data"=>'FT'), array("align"=>"right","data"=>'Total'));
					
					$out = ($tr->Amount_OUT) ? $tr->Amount_OUT : 0;
					$exp=0;
					$rel=0;
					$ft =0;
					$rfpl =0;
					$count++;
					
						if(strtolower($tr->transType)== 'expenses')
						$exp = $out;
						elseif(strtolower($tr->transType)== 'releases')
						$rel = $out;
						elseif(strtolower($tr->transType)== 'fund transfer')
						$ft = $out;
						elseif(strtolower($tr->transType)== 'rfpl')
						$rfpl = $out;
						
						$expout = ($exp ? number_format($exp,2) : "-");
						$relout = ($rel ? number_format($rel,2) : "-");
						$ftout = ($ft ? number_format($ft,2) : "-");
						$rfplout = ($rfpl ? number_format($rfpl,2) : "-");
						
						$totalout += $out;
						$totalexp += $exp;
						$totalrel += $rel;
						$totalft += $ft;
						$totalrfpl += $rfpl;
						
						
					$discontent[] = array($count, $tr->referenceNo, $tr->PN,  $tr->Checkno, $tr->Particulars, $tr->explanation, array("align"=>"right","data"=>$rfplout),array("align"=>"right","data"=>$expout),array("align"=>"right","data"=>$relout), array("align"=>"right","data"=>$ftout),array("align"=>"right","data"=>number_format($out,2)));				
						
						
				}elseif($tr->transCatName == 'adjustment'){
					$adjheader = array('#', 'JV No', array('colspan'=>'2','data'=>'Particulars'),array('colspan'=>'3','data'=>'Explanation'),array('colspan'=>'2','data'=>'Adjustment Type'), array("align"=>"right",'colspan'=>'2',"data"=>' Total Adjustment'));
					if($tr->Amount_IN > 0) $amount = $tr->Amount_IN;
					else if($tr->Amount_OUT > 0) $amount = -1 * $tr->Amount_OUT; 
					$count++;
					$adjcontent[] = array($count, $tr->referenceNo, array('colspan'=>'2','data'=>$tr->Particulars), array('colspan'=>'3','data'=>$tr->explanation), array('colspan'=>'2','data'=>$tr->transType), array("align"=>"right","colspan"=>'2',"data"=>number_format($amount,2)));
					$totaladj += $amount;
				}					
			
		}				
		
		
		$totalcash = ($totalcash ? number_format($totalcash,2) : "-");
		$totalcheck = ($totalcheck ? number_format($totalcheck,2) : "-");
		$totalonline = ($totalonline ? number_format($totalonline,2) : "-");
		$totalpos = ($totalpos ? number_format($totalpos,2) : "-");
		
		$totalexp = ($totalexp ? number_format($totalexp,2) : "-");
		$totalrel = ($totalrel ? number_format($totalrel,2) : "-");
		$totalft = ($totalft ? number_format($totalft,2) : "-");
		$totalrfpl = ($totalrfpl ? number_format($totalrfpl,2) : "-");
				
				
		$this->table->add_row(array("colspan"=>'12', "bgcolor"=>'#ccc','data'=>strtoupper('<b>COLLECTIONS</b>')));
		
		if(count($colcontent) > 0)
		{
			$this->table->add_row($colheader);
			foreach($colcontent as $col){
				$this->table->add_row($col);
			}
			 $this->table->add_row(array("colspan"=>"6","data"=>"TOTAL"), array("align"=>"right","data"=>$totalcash),array("align"=>"right","data"=>$totalcheck), array("align"=>"right","data"=>$totalonline), array("align"=>"right","data"=>$totalpos), array("align"=>"right","data"=>number_format($totalin,2)) );
		}else $this->table->add_row(array("colspan"=>'12', 'data'=>strtoupper('No Transactions')));
		echo $this->table->generate();
		
		$this->table->add_row(array("colspan"=>'12', "bgcolor"=>'#ccc','data'=>strtoupper('<b>DISBURSEMENTS</b>')));
		
		if(count($discontent) > 0)
		{
			$this->table->add_row($disheader);
			foreach($discontent as $col){
				$this->table->add_row($col);
			}
			$this->table->add_row(array("colspan"=>"6","data"=>"<b>TOTAL</b>"), array("align"=>"right","data"=>'<b>'.$totalrfpl.'</b>'),array("align"=>"right","data"=>'<b>'.$totalexp.'</b>'),array("align"=>"right","data"=>'<b>'.$totalrel.'</b>'), array("align"=>"right","data"=>'<b>'.$totalft.'</b>'), array("align"=>"right","data"=>'<b>'.number_format($totalout,2).'</b>') );	
		}else $this->table->add_row(array("colspan"=>'12', 'data'=>strtoupper('No Transactions')));
		echo $this->table->generate();
		
		$this->table->add_row(array("colspan"=>'12', "bgcolor"=>'#ccc','data'=>strtoupper('<b>ADJUSTMENTS</b>')));
		
		if(count($adjcontent) > 0)
		{
			$this->table->add_row($adjheader);
			foreach($adjcontent as $col){
				$this->table->add_row($col);
			}
			$this->table->add_row(array("colspan"=>"9","data"=>"<b>TOTAL</b>"), array("colspan"=>"2","align"=>"right","data"=>'<b>'.number_format($totaladj,2).'</b>') );
		}else $this->table->add_row(array("colspan"=>'12', 'data'=>strtoupper('No Transactions')));
		echo $this->table->generate();
	}else{
		$this->table->add_row(array("colspan"=>'12', 'data'=>strtoupper('No Transactions')));
		echo $this->table->generate();
	}
	
} ?>
	
	<!-- BANKS -->
		
		<?php
		$this->table->add_row(array("colspan"=>'12', "bgcolor"=>'#ccc','data'=>strtoupper('<b>BANKS SUMMARY</b>')));
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
		 
		<?php 
		$this->table->add_row(array("colspan"=>'12', "bgcolor"=>'#ccc','data'=>strtoupper('<b>RECAP OF DEPOSITS</b>')));
		$this->table->add_row("#","Date Deposited","Bank",array("align"=>"right","data"=>"Amount"),"Type of Deposit","Time Deposited","Notes");
		if($recap->num_rows() > 0){
				
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
				
			//echo '<table class="reporttable">';
			//echo $tr;
			//echo '</table>';
		} 
		echo $this->table->generate();
		?>
	
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
	 

