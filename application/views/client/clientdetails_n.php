<script type="text/javascript" src="<?php echo base_url();?>assets/js/modaljs.js"></script>
<script src="<?php echo base_url();?>assets/js/loansprocess.js" type="text/javascript"></script>	
<?php
$c = $client->row();
?>
<div class="col-md-5">
	<div class="wells">
		
			<?php $this->load->view('client/clientinfo');?>
		
	</div>
</div>
<div class="col-md-7">
	<div class="panel panel-primary">
		<div class="panel-heading"><b>Client's Pension</b></div>
		<div class="panel-body">
			<?php $this->load->view($pension);?>
		</div>
	</div>
</div>
<div class="col-md-7">
	<div class="panel panel-red">
		<div class="panel-heading"><b>Loans</b></div>
		<div class="panel-body">
			<?php $this->load->view($loaninfo);?>
		</div>
	</div>
	 <script>
    $(document).ready(function() {
        $('#loanabletable').dataTable({
			"dom": 'T<"clear">lfrtip',
			"tableTools": {
				"sSwfPath": "/swf/copy_csv_xls_pdf.swf"
			}
		});
    });
   </script>
</div>



<div class="modal fade  " id="loan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel">New Loan</h4>
		</div>
		<div class="modal-body" >
		<form method="post" action="<?php echo base_url();?>loans/new">
			<div class="row form-group" align="center">
				<label> Choose Loan Product</label>
			</div>
			<div class="row  form-group" align="center">
				<?php $loan = $this->Loansmodel->get_products(1);
				if($loan->num_rows() > 0){
					echo "<select name='loantype' id='loantype' class=' input-lg'>";
						echo "<option disabled selected>Select Loan Type</option>";
					foreach($loan->result() as $lt){
						
						echo "<option value='".$lt->loanTypeID."'>".$lt->LoanName."</option>";
					}
					echo "</select>";
				}
				?>
			</div>
			<div class="row  form-group" align="center">
				<input type="hidden" name="clientid" value="<?php echo $c->ClientID;?>">
				<input type="submit" name="submit" value="Proceed to Loan Application" class="btn btn-success" disabled id="loansubmit">
			</div>
			</form>
		</div>  
		</div>
	</div>
 </div>
 
<!--
<div class="panel panel-green">
	<div class="panel-heading"><b>Client Name :  <?php echo $c->LastName.", ".$c->firstName;?></b></div>
	<div class="panel-body">
		
		<ul class="nav nav-tabs" role="tablist">
		  <li class="active"><a href="#personaltab" role="tab" data-toggle="tab">Personal Info</a></li>  
		  <li><a href="#pensiontab" role="tab" data-toggle="tab">Pension Info</a></li>
		  <li><a href="#loantab" role="tab" data-toggle="tab">Loan Info</a></li>
		
		</ul>

		
		<div class="tab-content">
			<div class="tab-pane active" id="personaltab">
				<div class="panel-body">
				<?php //$this->load->view('client/clientinfo');?>
				</div>  
			</div>  
			<div class="tab-pane" id="pensiontab">
				<div class="panel-body">
					<?php //$this->load->view($pension);?>
				</div>
			</div>
			<div class="tab-pane" id="loantab">
				<div class="panel-body">
					<?php //$this->load->view($loaninfo);?>
				</div>
			</div>
		
		</div>
	</div>
</div>-->

