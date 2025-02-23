<style>
#pltable th, #pltable td { white-space: nowrap; }
    div.dataTables_wrapper {
        width: 800px;
        margin: 0 auto;
    }
</style>
<?php 
if($pensionid != ''){
$pension = $this->Loansmodel->get_pensioninfo($pensionid);
if($pension->num_rows() > 0){
$pd = $pension->row();
$monthly = $pd->monthlyPension;
$tmpl = array ('table_open'          => '<table class="table  table-bordered " id="pltable">'); 
	$this->table->set_template($tmpl);	
$monthyear[] = 'PN';
foreach($pl->result() as $p){
	$monthyear[] = date("M-y", strtotime($p->DueDate));
	if($p->PN == '')
		$data[$p->loanID][date("M-y", strtotime($p->DueDate))] = ($p->AmountDue - $p->Paid);	
	else
		$data[$p->PN][date("M-y", strtotime($p->DueDate))] = ($p->AmountDue - $p->Paid);	
}
$monthyear[] = "Total";
$count = 1;

//$month = array_unique($monthyear);

//$moTotal = array();
$grandtotal = 0;

$co[]='Count';
$month[]='PN';
while($count <= 30){
	$co[]=$count;
	//$month[] = date("M-y", strtotime($this->auth->localdate(). "+".$count." months")).$count;
	$date = strtotime(date("M-y", strtotime($this->auth->localdate())) . " +".$count." month");
	$date = date("M-y",$date);
	$month[] = $date;
	$count++;
}
$month[]='Total';
$co[]='';

$this->table->set_heading($co);
$this->table->add_row($month);


foreach($data as $pn=>$date){
	$pntotal = 0;
	if($pn == $thispn or $pn == $loanID){		
		if($pn == '')
			$pn = "<font color='red'>This Loan Application</font>";
		else
			$pn = "<font color='red'>".$pn."</font>";
	}
	if($pn == '')
		$pn = "PROCESSING";
	
	foreach($month as $m){
		if($m=='PN'){
			$d[$pn][]=$pn;
			//$moTotal['pn']='';
			$value = '';
			$mototal[$m][]='';
			
		}elseif($m=='Total'){
			$value = '';
			$mototal[$m][]=$grandtotal;	
				
		}else{
			$value = (element($m, $date) ? element($m, $date) : '') ;
			$d[$pn][] = ($value ? number_format($value,2) : '');
			$mototal[$m][] = ($value ? $value : '');	
			
			
		}		
		$pntotal += element($m, $date);
		
	}
	$d[$pn][] = number_format($pntotal,2);	
	//$moTotal['total']=$grandtotal;
	$grandtotal += $pntotal;
	$pndata = $this->table->add_row($d[$pn]);
}


		//echo $grandtotal;
		$totalexcess=0;
		$count=1;
		foreach($mototal as $mo=>$date){
			if($mo != 'PN' and $mo != "Total"){
				$sub[] = array_sum($date);
				$subtotal[] = number_format(array_sum($date),2);
				$co[] = $count;
				if(strpos($mo, 'Dec') !== false ){
					$mont[] = $monthly*2;
					$mon[] = number_format($monthly*2,2);
					$ex[] = ($monthly*2)-array_sum($date);	
					$excess[] = number_format(($monthly*2)-array_sum($date),2);
				}else{	
					$mont[] = $monthly;
					$mon[] = number_format($monthly,2);
					$ex[] = $monthly-array_sum($date);	
					$excess[] = number_format($monthly-array_sum($date),2);
				}			
				
			}elseif($mo =='PN'){
				$subtotal[] = "Subtotal";
				$mon[] = "Monthly Pension";
				$excess[] = 'Excess';
				$co[] = "Count";
			}else{
				$subtotal[] = "<b>".number_format(array_sum($sub),2)."</b>";
				$mon[] = "<b>".number_format(array_sum($mont),2)."</b>";
				$excess[] = "<b>".number_format(array_sum($ex),2)."</b>";
				$co[] ='';
			}
			$count++;
		}
		
		//echo $pndata;
		$this->table->add_row($subtotal);
		$this->table->add_row($mon);
		$this->table->add_row($excess);
		echo $this->table->generate();
		//$npl = $pl->row();
		//echo $npl->lastDate;
}else{
	echo "Pension Info was not yet updated. Please check Pension information and Loan Schedule";
}
}else{
	echo "Pension Info was not yet migrated.";
}?>
		
