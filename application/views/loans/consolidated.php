<script src="<?php echo base_url();?>assets/js/loansprocess.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/modaljs.js" type="text/javascript"></script>

<div class="row">
	<div class="col-lg-3 col-md-3 col-sm-3  col-xs-6" >
		<!-- small box -->
		  <div class="small-box bg-red">
			<div class="inner">
			  <h3 id='processing'><img src='<?php echo base_url(); ?>assets/img/ajax-loader.gif' id='loader-processing'></h3>
			  <p>Processing</p>
			</div>
			<div class="icon">
			  <i class="fa fa-spinner "></i>
			</div>
			<a href="<?php echo base_url();?>loans/status/processing" class="small-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
		  </div>
		
	</div>
    
	<div class="col-lg-3 col-md-3 col-sm-3  col-xs-6">
		<!-- small box -->
		  <div class="small-box bg-green">
			<div class="inner">
			  <h3 id='approval'><img src='<?php echo base_url(); ?>assets/img/ajax-loader.gif' id='loader-approval'></h3>
			  <p>For Approval</p>
			</div>
			<div class="icon">
			  <i class="fa fa-legal"></i>
			</div>
			<a href="<?php echo base_url();?>loans/status/approval" class="small-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
		  </div>		
	</div>
	<div class="col-lg-3 col-md-3 col-sm-3  col-xs-6">
		<!-- small box -->
		  <div class="small-box bg-blue">
			<div class="inner">
			  <h3 id='approved'><img src='<?php echo base_url(); ?>assets/img/ajax-loader.gif' id='loader-approved'></h3>
			  <p>Approved</p>
			</div>
			<div class="icon">
			  <i class="fa fa-check-square-o"></i>
			</div>
			<a href="<?php echo base_url();?>loans/status/approved" class="small-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
		  </div>	
		
	</div>
	<div class="col-lg-3 col-md-3 col-sm-3  col-xs-6">
		<!-- small box -->
		  <div class="small-box bg-yellow">
			<div class="inner">
			  <h3 id='granted'><img src='<?php echo base_url(); ?>assets/img/ajax-loader.gif' id='loader-granted'></h3>
			  <p>Granted</p>
			</div>
			<div class="icon">
			  <i class="fa fa-money"></i>
			
			</div>
			<a href="<?php echo base_url();?>loans/status/granted" class="small-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
		  </div>
		
	</div>
	
</div>


<?php
$submod = strtolower($status);

if($submod == 'granted'){
	if($_POST)
		$date = $_POST['date'];
	else
		$date = $this->auth->localdate();
	//if($this->auth->allbranch() == 1)
	if($this->auth->perms("CMC ALL Branches",$this->auth->user_id(),3) == true)
		$where = array("loanapplication.status"=>$status,
						"loanapplication.active"=>1,
						"DateDisbursed like "=>"%".$date."%");
	else{
		$where = array("loanapplication.status"=>$status,
							"loanapplication.branchID"=> $this->auth->branch_id(),
							"loanapplication.active"=>1,
							"DateDisbursed like "=>"%".$date."%");
	}
}else{
	//if($this->auth->allbranch() == 1)
	if($this->auth->perms("CMC ALL Branches",$this->auth->user_id(),3) == true)
		$where = array("loanapplication.status"=>$status,
						"loanapplication.active"=>1
						);
	else{
		$where = array("loanapplication.status"=>$status,
							"loanapplication.branchID"=> $this->auth->branch_id(),
							"loanapplication.active"=>1);
	}
}
$loans = $this->Loansmodel->getLoansByStatus($where);
//echo $this->db->last_query();

switch ($submod){
	case 'processing':
		$color = 'danger';
		$action = "Continue Processing";
		$i = 'fa fa-spinner';
		
	break;
	case 'approval':
		$color = 'success';
		$action = "Approve Loan";
		$i = 'fa fa-eye';
		
	break;
	case 'release':
		$color = 'warning';
		$action = "Release Check";
		$i = 'fa fa-money';
		
	break;
	case 'ci':
		$color = 'info';
		$action = "Create CI Report";
		$i = 'fa fa-money';
		
	break;
	case 'approved':
		$color = 'primary';
		$action = "Prepare Check Voucher";
		$i = 'fa fa-check-square-o';
		
	break;
	
	case 'granted':
		$color = 'primary';
		$action = "View Loan Info";
		$i = 'fa fa-check-square-o';
		
	break;
	
	default:
		$color = 'primary';
		$action = "View Loan Details";
		$i = 'fa fa-check-square-o';		
	break;
}
/*
echo "<pre>";
print_r($loans->result());
echo "</pre>"; */

$tmpl = array ('table_open'  => '<table class="table  table-bordered table-striped table-hover" id="loanstatustable" style="font-size: 14px">');
$this->table->set_template($tmpl);

?>

	<div class="panel panel-<?php echo $color;?>">
		<div class="panel-heading">
			<i class="<?php echo $i;?>"></i> LOANS <?php echo strtoupper($submod);?>
		</div>
		<div class="panel-body">
		
		<?php
		
		if($submod == 'granted')
		{ 
			echo "<div class='row'><div class='col-md-12'>Loans Granted as of ".date("F d, Y", strtotime($date))."</div></div>";
			?>
			<form action="" method="post">
			
			<div class="row">
				 <div class="col-md-4">
					<div class="input-group">
						<input type="date" name="date" class="input-sm form-control"  >
						<span class="input-group-btn">
						<input type="submit" value="Find" class="btn btn-sm btn-primary">
						</span>
					</div>
				</div>
			</div>
			
			</form>
		<?php }
		
		if($loans){
		if($loans->num_rows() > 0){	
			//$act =  '<a href="'.base_url().'loans/popup/checkrelease/'.$loan->loanID.'" data-toggle="modal"  data-target="#modal" >'.$action.'</a>';	
			
			$count =1;
			if( $submod == 'processing'){
				$this->table->set_heading('#', 'Branch',  'Client Name', 'Loan Type ', 'Amount', 'Terms','Date Applied','Processed by','Action');
				
			}elseif($submod == 'approval'){
				$this->table->set_heading('#', 'Branch',  'Client Name', 'Loan Type ', 'Amount', 'Terms','Date Applied','Action');
				
				
			}
			else
				$this->table->set_heading('#', 'Branch', 'PN#', 'Client Name', 'Loan Type ', 'Amount', 'Terms','Date Applied','Date Approved', 'Approved by', 'Action');
			foreach($loans->result() as $loan){
				$sym = '';
				
				$loancode = $loan->LoanSubCode;
					switch($loancode){
						case 'N':
							$loansub = "New";
						break;
						case 'E':
							$loansub = "Extension";
						break;
						case 'A':
							$loansub = "Additional";
						break;
						case 'R':
							$loansub = "Renewal";
						break;
					}
					
					if($loan->paymentmethod == "M") $loansub .= " / Monthly"; else $loansub .= " / Lumpsum";
				
				
				//$agent = $this->UserMgmt->get_user_byid($loan->addedBy);
				//$a = $agent->row();
				if($loan->approvedAmount <= 0)
					$principal = floatval(str_replace(",","",$loan->principalAmount));
				else $principal = floatval(str_replace(",","",$loan->approvedAmount));
				
				if($loan->productCode == 'PL'){
					$PLBal = $this->Loans->PLBalance($loan->pensionID);
					if($PLBal->num_rows() >0){
						$plb = $PLBal->row();
						$totalBal = $plb->totalPL-$plb->totalPaid;						
					}
				}else{
					$totalBal = $principal;
					
				}
			
			
				if($submod == 'approval'){
				
					if($this->auth->perms('Approve Loan',$this->auth->user_id(),3) == true and $this->auth->loanapproval($loan->loanTypeID, $this->auth->user_id(), $loan->branchID, $totalBal) == true){
						$color = 'success';
						$action = "Approve Loan";
					}else{
						$color = 'danger';
						$action = "Pending for Approval";
					}
				
				}else{
					$color = 'primary';
				}
				$act =  '<a class="btn btn-sm btn-'.$color.' " href="'.base_url().'client/profile/'.$loan->ClientID.'/loan/'.$loan->loanID.'"  >'.$sym.$action.'</a>';
				if($submod == 'processing'){
					$this->table->add_row($count, $loan->branchname,  "<a href='".base_url()."client/profile/".$loan->ClientID."' title='Update' >".$loan->clname.", ".$loan->cfname.'</a>', strtoupper(substr($loan->pensionType, 0,1)).$loan->productCode."/".$loansub , number_format($loan->AmountApplied,2),  $loan->Term.($loan->extension ? " - ".$loan->extension." mos ext." : ''), $loan->dateApplied, $loan->ulname,$act);
				}elseif($submod == 'approval'){
					
					
					
					$this->table->add_row($count, $loan->branchname,  "<a href='".base_url()."client/profile/".$loan->ClientID."' title='Update' >".$loan->clname.", ".$loan->cfname.'</a>', strtoupper(substr($loan->pensionType, 0,1)).$loan->productCode."-".$loansub , number_format($loan->AmountApplied,2),  $loan->Term.($loan->extension ? " - ".$loan->extension." mos ext." : ''), date('m/d/Y', strtotime($loan->dateApplied)), $act);
					
					
				}elseif($submod == 'approved' or $submod == 'granted' or $submod == 'current'){
					
					$appr = $this->UserMgmt->get_user_byid($loan->ApprovedBy);
					if(isset($appr)){
						if($appr->num_rows() > 0 ){
							$a = $appr->row();
							$ag= substr($a->firstname, 0,1).$a->lastname;
							}else $ag = '';
					}else $ag='';
					$this->table->add_row($count, $loan->branchname, $loan->PN, "<a href='".base_url()."client/profile/".$loan->ClientID."' title='Update' >".$loan->clname.", ".$loan->cfname.'</a>', strtoupper(substr( $loan->pensionType, 0,1)).$loan->productCode."-".$loansub , number_format($loan->AmountApplied,2),  $loan->Term.($loan->extension ? " - ".$loan->extension." mos ext." : ''), $loan->dateApplied,$loan->dateApproved, $ag, $act);
				
				}
				$count++;
			}
			echo '<div class="table-responsive">';
			echo $this->table->generate();
			echo '</div>';
		}else{
		
			echo "<div class='panel-body'>";
			echo "No loans - ".$submod;
			echo '</div>';
			
		}
		}else{
		
			echo "<div class='panel-body'>";
			echo "No loans - ".$submod;
			echo '</div>';
			
		}
		?>
	</div>
	</div>
	<script>
    $(document).ready(function() {
		
		
		showCount('processing');
		$('#loader-processing').show(); 
		
		showCount('approval');
		$('#loader-approval').show(); 
		
		showCount('approved');
		$('#loader-approved').show(); 
		
		showCount('granted');
		$('#loader-granted').show(); 
		
        $('#loanstatustable').dataTable({
			"processing": true,
			"oLanguage": {
                "sProcessing": "<img src='<?php echo base_url(); ?>assets/img/ajax-loader.gif'>"
            },
			"dom": 'T<"clear">lfrtip',
			"tableTools": {
				"sSwfPath": "/swf/copy_csv_xls_pdf.swf"
			}
		});
		
		function showCount(status){
			//showloader(status);
			
			$('#'+status).load('<?php echo base_url();?>loancount/'+status, function(){
				// hide loader image
				$('#loader-'+status).hide(); 
				 
				// fade in effect
				$('#'+status).fadeIn('slow');
		
			});	
		}
		
		function showloader(status){
			$('#'+status).html("<img src='<?php echo base_url(); ?>assets/img/ajax-loader.gif'>");
		}
    });
   </script>