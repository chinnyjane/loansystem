<?php 
$cmcrec = $this->Cashmodel->consolidatedCMC($date);
//$filename ="CMC".$date.".xls";
//header('Content-type: application/ms-excel');
//header('Content-Disposition: attachment; filename='.$filename);

$tmpl = array ('table_open'          => '<table class="reporttable">' );
$this->table->set_template($tmpl);

?>
<style>
.reporttable 
{
border-collapse:collapse;
width: 100%;
font-size: 10px;
}
.reporttable table, td, th
{
border:1px solid;
padding:2px;
}
</style>
<div align="center">Consolidated Daily Cash Movement<br/>
<?php echo date("l, F d, Y", strtotime($date));?></div>
<table class="reporttable" style="font-family: 'Arial Narrow'">
	<thead>
		<th width="150">&nbsp;</th>
		<?php 
			foreach($cmcrec->result() as $br){
				echo "<th>".$br->branchname."</th>";
			}
		?>
		<th>TOTAL</th>
	</thead>
	<tbody>
		<tr style="font-weight: bold;" bgcolor="#ccc">
			<td>BEGINNING BALANCE</td>
				<?php
				$begin = 0;
			foreach($cmcrec->result() as $br){
				$sum = $this->Cashbalance->EndOfDateSummary($br->branchID, $br->dateTransaction);
				echo "<td align='right'>".number_format($sum['begin'],2)."</td>";
				$begin += $sum['begin'];
			}
		?>
		<td align='right'><?php echo number_format($begin,2);?></td>		
		</tr>
		<tr>
			<td>Cash</td>	
			<?php
			foreach($cmcrec->result() as $br){	
				$banktrans = $this->Cashmodel->getCMCTransactions($br->transID,"collection"); 
				if($banktrans->num_rows() >0){
					$totalcash = 0;
					$totalcheck = 0;
					$totalonline = 0;
					$totalin = 0;
					$totalpos = 0;
					foreach($banktrans->result() as $bt){
						$in = ($bt->Amount_IN ? $bt->Amount_IN : 0);
						$cash=0;
						$check=0;
						$online =0;	
						$pos	=0;
						if($bt->paymentType== 'cash')
						$cash = $in;
						elseif($bt->paymentType== 'check')
						$check = $in;
						elseif($bt->paymentType== 'online')
						$online = $in;
						elseif($bt->paymentType== 'POS')
						$pos = $in;	
						
						$totalin += $in;
						$totalcash += $cash;
						$totalcheck += $check;
						$totalonline += $online;
						$totalpos += $pos;
					}
					
					$brin[] = $totalin;
					$brcash[] = $totalcash;
					$brcheck[] = $totalcheck;
					$bronline[] = $totalonline;
					$brpos[] = $totalpos;
				}else{
					$brin[] = 0;
					$brcash[] = 0;
					$brcheck[] = 0;
					$bronline[] = 0;
					$brpos[] = 0;
				}
				
			$banktrans = $this->Cashmodel->getCMCTransactions($br->transID,"disbursement");
				
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
						
						$totalout += $out;
						$totalexp += $exp;
						$totalrel += $rel;
						$totalft += $ft;
						$totalrfpl += $rfpl;								
				}
				
				$brout[] = $totalout;
				$brexp[] = $totalexp;
				$brrel[] = $totalrel;
				$brft[] =$totalft;
				$brrfpl[] = $totalrfpl;
			}else{
				$brout[] = 0;
				$brexp[] = 0;
				$brrel[] = 0;
				$brft[] = 0;
				$brrfpl[] = 0;
			}
			
			$banktrans = $this->Cashmodel->getCMCTransactionsbyDate($date,"adjustment");
				
				
				foreach($banktrans->result() as $adj){
					$banktrans[$adj->transtype][$adj->branchID][] = array('in'=>$adj->Amount_IN, 'out'=>$adj->Amount_OUT);
				}
				
				
			
			}
			$totalcash =0;
			foreach($brcash as $cash){
				$cashin = ($cash ? number_format($cash,2) : "-");
				echo "<td align='right'>".$cashin."</td>";
				$totalcash +=$cash;
			}
		echo "<td align='right'>".number_format($totalcash,2)."</td>";	
			?>
		</tr>
		<tr>
			<td>Check</td>	
				<?php 
				$totalcheck=0;
				foreach($brcheck as $check){
				$checkin = ($check ? number_format($check,2) : "-");
				echo "<td align='right'>".$checkin."</td>";
				$totalcheck +=$check;
			}	
			$totalcheck = ($totalcheck ? number_format($totalcheck,2) : "-");
			echo "<td align='right'>".$totalcheck."</td>";	 ?>
		</tr>
		<tr>
			<td>Online</td>
			<?php 
				$totalcheck=0;
				foreach($bronline as $check){
				$checkin = ($check ? number_format($check,2) : "-");
				echo "<td align='right'>".$checkin."</td>";
				$totalcheck +=$check;
			}	
			$totalcheck = ($totalcheck ? number_format($totalcheck,2) : "-");
			echo "<td align='right'>".$totalcheck."</td>";	 ?>			
		</tr>
		<tr>
			<td>POS</td>
			<?php 
				$totalcheck=0;
				foreach($brpos as $check){
				$checkin = ($check ? number_format($check,2) : "-");
				echo "<td align='right'>".$checkin."</td>";
				$totalcheck +=$check;
			}	
			$totalcheck = ($totalcheck ? number_format($totalcheck,2) : "-");
			echo "<td align='right'>".$totalcheck."</td>";	 ?>		
		</tr>
		<tr>
			<td>Adjustments</td>
			<?php 
				foreach($cmcrec->result() as $br){
					echo "<td align='right'>&nbsp;</td>";
				}echo "<td align='right'>&nbsp;</td>";
				?>		
		</tr>	
		<?php
	
	
			if($banktrans->num_rows() >0){	
				foreach($banktrans->result() as $adj){
					$title = $adj->transtype." - ".$adj->explanation;
					if($adj->Amount_IN > 0) $amount = $adj->Amount_IN;
					else if($adj->Amount_OUT > 0) $amount = -1 * $adj->Amount_OUT;
					$totalamount = 0;
					echo "<tr>";
					echo "<td>".$title."</td>";
					foreach($cmcrec->result() as $br){
						if($br->branchID == $adj->branchID)
						echo "<td align='right'>".number_format($amount,2)."</td>";
						else
						echo "<td align='right'></td>";
						$totalamount =$amount;
					}
					echo "<td align='right'>".number_format($totalamount,2)."</td>";
					echo "</tr>";
				}
			}		
		?>
	
		<?php
		$overall = 0;
		foreach($cmcrec->result() as $dep){
			$totalcol[] = $dep->TotalCollections + $dep->TotalAdjustment;
			$cashonbank[] = $dep->BeginningBal + $dep->TotalCollections + $dep->TotalAdjustment;
		}		
	
		?>
		<tr  style="font-weight: bold;" bgcolor="#ccc">
		<td>Total Deposits</td>
		<?php foreach($totalcol as $col){
			echo "<td align='right'>".number_format($col,2)."</td>";
			$overall += $col;
		}
			echo "<td align='right'>".number_format($overall,2)."</td>";
		?>
		</tr>
		<tr  style="font-weight: bold;" bgcolor="#ccc">
		<td>Total Cash on Hand</td>
		<?php 
		$overall = 0;
		foreach($cashonbank as $col){
			echo "<td align='right'>".number_format($col,2)."</td>";
			$overall += $col;
			}
			echo "<td align='right'>".number_format($overall,2)."</td>";
		?>
		</tr>
		<tr>
			<td>Less Disbursement</td>
			<?php 
				foreach($cmcrec->result() as $br){
					echo "<td align='right'>&nbsp;</td>";
				}
				echo "<td align='right'>&nbsp;</td>";
				?>		
		</tr>
		<tr>
			<td>Expenses</td>	
				<?php 
				$totalcheck=0;
				foreach($brexp as $out){
				$bout = ($out ? number_format($out,2) : "-");
				echo "<td align='right'>".$bout."</td>";
				$totalcheck +=$out;
			}	
			$totalcheck = ($totalcheck ? number_format($totalcheck,2) : "-");
			echo "<td align='right'>".$totalcheck."</td>";	 ?>
		</tr>
		<tr>
			<td>Release</td>	
				<?php 
				$totalcheck=0;
				foreach($brrel as $out){
				$bout = ($out ? number_format($out,2) : "-");
				echo "<td align='right'>".$bout."</td>";
				$totalcheck +=$out;
			}	
			$totalcheck = ($totalcheck ? number_format($totalcheck,2) : "-");
			echo "<td align='right'>".$totalcheck."</td>";	 ?>
		</tr>
		<tr>
			<td>Fund transfer</td>	
				<?php 
				$totalcheck=0;
				foreach($brft as $out){
				$bout = ($out ? number_format($out,2) : "-");
				echo "<td align='right'>".$bout."</td>";
				$totalcheck +=$out;
			}	
			$totalcheck = ($totalcheck ? number_format($totalcheck,2) : "-");
			echo "<td align='right'>".$totalcheck."</td>";	 ?>
		</tr>
		<tr>
			<td>RFPL</td>	
				<?php 
				$totalcheck=0;
				foreach($brrfpl as $out){
				$bout = ($out ? number_format($out,2) : "-");
				echo "<td align='right'>".$bout."</td>";
				$totalcheck +=$out;
			}	
			$totalcheck = ($totalcheck ? number_format($totalcheck,2) : "-");
			echo "<td align='right'>".$totalcheck."</td>";	 ?>
		</tr>
		<tr  style="font-weight: bold;" bgcolor="#ccc">
			<td>Total Disbursements</td>	
				<?php 
				$totalcheck=0;
				foreach($brout as $out){
				$bout = ($out ? number_format($out,2) : "-");
				echo "<td align='right'>".$bout."</td>";
				$totalcheck +=$out;
			}	
			$totalcheck = ($totalcheck ? number_format($totalcheck,2) : "-");
			echo "<td align='right'>".$totalcheck."</td>";	 ?>
		</tr>
		<tr  style="font-weight: bold;" bgcolor="#ccc">
			<td>Cash in Bank</td>
			<?php 
			$overall=0;
			foreach($cmcrec->result() as $dep){
				$cashinb = ($dep->EndingBal ? number_format($dep->EndingBal,2) : "-" );
				echo "<td align='right'>".$cashinb."</td>";
				$overall += $dep->EndingBal;
			} 
			$overall = ($overall ? number_format($overall,2) : "-");
			echo "<td align='right'>".$overall."</td>";	
			?>
		</tr>
		
		<?php 
		
		$sql = "select distinct(bankofbranch.bankID), bankCode from bankofbranch join banks on banks.bankID = bankofbranch.bankID";
		$q = $this->db->query($sql);
		
		if($q->num_rows() >0)
		{
			$overall = 0;
			foreach($q->result() as $bank){
				echo "<tr>";
				echo "<td>".$bank->bankCode."</td>";
				
				$te = 0;
					foreach($cmcrec->result() as $br){
					$data = array("branchID"=>$br->branchID,
									"bankID"=>$bank->bankID,
									"isdeleted"=>NULL);
					$banks = $this->Loansmodel->get_data_from("bankofbranch", $data);
					$totalEnd = "";
					if($banks->num_rows() > 0){
						foreach($banks->result() as $bk){
							if(!empty($bk->branchCode))
							$bcode =$bk->branchCode."-";
							else $bcode = "";
							$beg = $this->Cashbalance->EndOfDateBalance($date,$bk->branchBankID)->row();
							$TotalAdjadd = ($beg->TotalAdjadd ? $beg->TotalAdjadd : 0);
							$TotalAdjless = ($beg->TotalAdjless ? $beg->TotalAdjless : 0);
							$TotalCol = ($beg->TotalCol ? $beg->TotalCol : 0);
							$TotalDis = ($beg->TotalDis ? $beg->TotalDis : 0);
							$adj = $TotalAdjadd + (-1 * $TotalAdjless);
							$end = $beg->BeginBal + $TotalCol - $TotalDis + $adj;
							$actual = ($beg->actualbalance) ? $beg->actualbalance : 0;
							$totalEnd .= $bcode.($end ? number_format($end ,2) : "-")."<br/>";
							$te += $end;
						}						
						echo "<td align='right'>".$totalEnd."</td>";	 
					}else{
						echo "<td align='right'>".$totalEnd."</td>";
					}
				}
				echo "<td align='right'>".number_format($te,2)."</td>";	
				echo "</tr>";
				$overall += $te;
			}
		}
		?>
		<tr  style="font-weight: bold;" bgcolor="#ccc">
			<td>TOTAL</td>
			<?php 
			$overall=0;
			foreach($cmcrec->result() as $dep){
				$cashinb = ($dep->EndingBal ? number_format($dep->EndingBal,2) : "-" );
				echo "<td align='right'>".$cashinb."</td>";
				$overall += $dep->EndingBal;
			} 
			$overall = ($overall ? number_format($overall,2) : "-");
			echo "<td align='right'>".$overall."</td>";	
			?>
		</tr>
	</tbody>
</table>
