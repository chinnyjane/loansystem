<?php 
if($_POST)
$date = $_POST['date'];
else
$date = date("Y-m-d");
$tmpl = array ('table_open'          => '<table class="table  table-condensed table-bordered table-hover tablesorter" id="dcrrtable" >',
			'thead_open' => '<thead class="header">'	); 
$this->table->set_template($tmpl); 
?>
	<div class="row form-group">		
		<div class='col-md-4'>		
			<div class="form-group">
				<form action="" method="post">
				<div class='input-group' >
					<?php echo $this->form->datetoday("date", date("Y-m-d"));?>
					<span class="input-group-btn">				
						<button class="btn btn-default btn-sm" type="submit"><i class="fa fa-search"></i></button>
					</span>
				</div>
				</form>
			</div>
		</div>
		</div>		
<div class="panel panel-default">
	<div class="panel-heading"><b>Daily Collection</b></div>
	<div class="panel-body">	
	 <div class="table-responsive">
	<?php 
		$sched = $this->Loansmodel->dcrr($date);
		$schedold = $this->Loansmodel->dcrr_old($date);
		
		if($sched->num_rows() > 0){
			$count = 1;
			$total = 0;
			$dueto =0;
			foreach($sched->result() as $sch){
				$this->table->add_row($count,$sch->CNO,$sch->lname.", ".$sch->fname,$sch->bankCode, $sch->Bankaccount, array("align"=>"right", "data"=>number_format($sch->mo_pension,2)), array("align"=>"right","data"=>number_format($sch->due,2)), "<a href='".base_url()."client/profile/".$sch->CNO."/pension/".$sch->PensionID."#loanslist'>View Details</a>");
				$total +=$sch->mo_pension;
				$dueto += $sch->due;
				$count ++;
			}
			//$this->table->add_row(array("colspan"=>'5', "data"=>"TOTAL"), array("align"=>"right", "data"=>number_format($total,2)), array("align"=>"right", "data"=>number_format($dueto,2)), "");
			$this->table->set_heading("#","Client ID","Name of Pensioner", "Bank", "Account","Monthly Pension", "Amount Due", "View Details");
			
			echo $this->table->generate();
		}
		
		if($schedold->num_rows() > 0){
			$count = 1;
			$total = 0;
			$dueto =0;
			foreach($schedold->result() as $sch){
				$this->table->add_row($count,$sch->CNO,$sch->lname, $sch->fname, array("align"=>"right", "data"=>number_format($sch->mo_pension,2)), array("align"=>"right","data"=>number_format($sch->due,2)), "<a href='".base_url()."client/profile/".$sch->CNO."/pension/".$sch->PensionID."#loanslist'>View Details</a>");
				$total +=$sch->mo_pension;
				$dueto += $sch->due;
				$count ++;
			}
			//$this->table->add_row(array("colspan"=>'4', "data"=>"TOTAL"), array("align"=>"right", "data"=>number_format($total,2)), array("align"=>"right", "data"=>number_format($dueto,2)), "");
			$this->table->set_heading("#","Client ID","Last Name", "First Name","Monthly Pension", "Amount Due", "View Details");
			
			//echo $this->table->generate();
		}
		
		else{
			echo "No Schedule Today";
		}
		
		?>
</div>
</div>
<script>
    $(document).ready(function() {
        $('#dcrrtable').dataTable({
			"dom": 'T<"clear">lfrtip',
			"tableTools": {
				"sSwfPath": "/swf/copy_csv_xls_pdf.swf"
			}
		});
    });
   </script>