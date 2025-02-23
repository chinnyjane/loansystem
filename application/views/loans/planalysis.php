<?php
$pensionid = $loanid;

$pension = $this->Loansmodel->get_pensioninfo($pensionid);
$pd = $pension->row();
$monthly = $pd->monthlyPension;
$pl = $this->Loans->PLSchedule($pensionid);

$tmpl = array ('table_open'          => '<table class="table  table-border  " >'); 
	$this->table->set_template($tmpl);	
$monthyear[] = 'PN';
foreach($pl->result() as $p){
	$monthyear[] = date("M-y", strtotime($p->DueDate));
	$data[$p->PN][date("M-y", strtotime($p->DueDate))] = ($p->AmountDue-$p->Paid);	
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

$this->table->add_row($co);
$this->table->add_row($month);
echo "<h4>Name of Borrower : ".$client->LastName.", ".$client->firstName."</h4>";
echo "<br/>";
echo "Pension Account : ".$pd->PensionType."- ".$pd->PensionType." - ". $pd->PensionNum;
echo "<br/>";
echo "Monthly Pension : ".$pd->monthlyPension;
echo "<br/>";
if(isset($data)){
if($data->num_rows() > 0){
foreach($data as $pn=>$date){
	$pntotal = 0;
	
	
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

$client = $client->row();



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
		
		echo $pndata;
		$this->table->add_row($subtotal);
		$this->table->add_row($mon);
		$this->table->add_row($excess);
		echo $this->table->generate();
	}
}?>
