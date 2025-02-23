<div class="panel panel-default with-nav-tabs" id="plcollection">
	<div class="panel-heading" style="margin-bottom: 0px; padding-bottom:0px;">	
		<ul class="nav nav-tabs" style="margin-bottom: 0px;">
			<li ><a data-toggle="tab" href="#schedule" ><i class="fa fa-user"></i> Scheduled for Collection</a></li>
			<li class="active"><a data-toggle="tab" href="#all" ><i class="fa fa-users"></i> All PL Pensions</a></li>  
		</ul>
	</div>
	<div class="tab-content">
		<div class="tab-pane " id="schedule">
			<div class="panel-body">
			<p>Today is <b><?php echo date("l jS \of F Y", strtotime($this->auth->localdate()));?> </b></p>
			<?php
			$date = date("j", strtotime($this->auth->localdate()));
			$tmpl = array ('table_open'  => '<table class="table  table-striped table-bordered table-hover " id="scpension">' );
			$this->table->set_template($tmpl);
			$allpension = $this->Clientmgmt->getScheduledPension($this->auth->branch_id(), $date);
			//echo $this->db->last_query();
			if($allpension->num_rows() > 0){
					$count=1;
					foreach($allpension->result() as $ap){
						$cname=$ap->lastname.", ".$ap->firstname;
						$cid = $ap->cid;
						$pid  = $ap->pensionID;
						$this->table->add_row($count,$ap->lastname.", ".$ap->firstname, $ap->bankCode, $ap->Bankaccount, $this->numbers->ordinal($ap->pensionDate),$ap->monthlyPension, "<div class='particulars display-none'>{$cname}</div>"."<div class='clientID display-none'>{$cid}</div>".
						"<div class='pensionID display-none'>{$pid}</div>".
						"<div class='btn btn-info edit-btn margin-right-1em'>".
						"<span class='glyphicon glyphicon-edit'></span> Add Collection".
						"</div>");
						
                    
						$count++;
					}
					$this->table->set_heading("#", "Name","Bank", "Bank account", "Schedule of Collection", "Monthly Pension","View Pension");
					echo '<div class="table-responsive">';
					echo $this->table->generate();
					echo '</div>';
				}else{
					echo "No Pensions";
				}
			?>
			</div>
		</div>
		<div class="tab-pane active " id="all">
			<div class="panel-body">
			<?php
			$tmpl = array ('table_open'  => '<table class="table  table-striped table-bordered table-hover " id="allpension">' );
			$this->table->set_template($tmpl);
				$allpension = $this->Clientmgmt->getAllPension($this->auth->branch_id());
								
				if($allpension->num_rows() > 0){
					$count=1;
					foreach($allpension->result() as $ap){
						$cname=$ap->lastname.", ".$ap->firstname;
						$cid = $ap->cid;
						$pid  = $ap->pensionID;
						$this->table->add_row($count,$ap->lastname.", ".$ap->firstname, $ap->bankCode, $ap->Bankaccount, $this->numbers->ordinal($ap->pensionDate),$ap->monthlyPension,"<form action='".base_url()."cash/collections/addpl' method='post'><input type='hidden' name='particulars' value='".$cname."'><input type='hidden' name='clientID' value='".$cid."'><input type='hidden' name='pensionID' value='".$pid."'><input type='submit' name='submit' value='Add Collection'></form>");
						$count++;
					}
					$this->table->set_heading("#", "Name","Bank", "Bank account", "Schedule of Collection", "Monthly Pension", "Action");
					echo '<div class="table-responsive">';
					echo $this->table->generate();
					echo '</div>';
				}else{
					echo "No Pensions";
				}
			?>
			</div>
		</div>
	</div>
</div>

<script>
    $(document).ready(function() {
        $('#allpension').dataTable({
			"dom": 'T<"clear">lfrtip',
			"tableTools": {
				"sSwfPath": "/swf/copy_csv_xls_pdf.swf"
			}
		});
		 $('#scpension').dataTable({
			"dom": 'T<"clear">lfrtip',
			"tableTools": {
				"sSwfPath": "/swf/copy_csv_xls_pdf.swf"
			}
		});
    });
   </script>