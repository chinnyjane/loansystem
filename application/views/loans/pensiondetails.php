<script type="text/javascript" src="<?php echo base_url();?>assets/js/modaljs.js"></script>
<script src="<?php echo base_url();?>assets/js/loansprocess.js" type="text/javascript"></script>
<?php 
$client = $client->row();

 if($_POST){
 
	if($_POST['submit']=="Update Pension"){
	
		$updatepension = $this->loansetup->update_pension();
		
		if($updatepension == false){
			echo "<div class='alert alert-danger'>".validation_errors()."</div>";
		}else{
			echo "<div class='alert alert-success'>Pension info was updated.</div>";
		}
	}else{	
		$this->cash->validatecollection();
	}
 }
 
 $tmpl = array ('table_open'   => '<table class="table  table-condensed " >',
			'thead_open' => '<thead class="header">'	); 
$this->table->set_template($tmpl); 
 ?>
 <h3><a href="<?php echo base_url();?>client/profile/<?php echo $clientid;?>"><i class="fa fa-arrow-circle-left"></i> <?php echo $client->LastName.", ".$client->firstName;?> Profile</a> &nbsp;&nbsp;</h3>
 
 <div class="col-md-12">	
	<div class="panel panel-success">	
		<div class="panel-heading"><b>PENSION INFORMATION</b></div> 		
			<?php echo $this->loansetup->pensioninfo($pensionid);?>		
		<div class="panel-footer"><button class="btn btn-sm btn-primary"  data-toggle="modal" data-target="#updatepension" ><i class="fa fa-check-circle"></i> Update Pension</button></div>
	</div>
 </div>
 
 <div class="col-md-12">	
	<div class="panel panel-default">
        <div class="panel-heading">Pension Loans</div>
        <?php
        $loans = $this->Loansmodel->pensionloan($pensionid);
          if($loans->num_rows() > 0){					
          $count = 1;
          $close = 0;
          $active = 0;
              foreach($loans->result() as $loan){
                  if($loan->status != 'closed' and $loan->status != 'canceled' and $loan->status != 'cancelled'){
                  $this->table->add_row( $count,"<a href='".base_url()."client/profile/".$clientid."/loan/".$loan->loanID."'>".$loan->PN."</a>", $loan->status, number_format($loan->AmountApplied,2));
                  $active++;
                  }else $close++;
                  $count++;
              }
              if($active > 0){
              $this->table->set_heading("#", "PN", "Status", "Amount Applied");
              echo $this->table->generate();
              }else echo "<div class='panel-body'>No active Loans.</div>";
          }else{
              echo "No Loans yet";
          }
        ?>
        <div class="panel-footer">
        	<a  class="btn btn-sm btn-default" href="<?php echo base_url();?>forms/planalysis/<?php echo $this->uri->segment(5);?>/<?php echo $client->ClientID;?>" target="_blank">Generate PL Analysis</a>
        </div>
	</div>
  	
 </div>
 <div class="col-md-12">	
	<?php	$this->load->view('client/pensiondue');	?>		
 </div>
 <div class="col-md-12">	
	<div class="panel panel-info">
		<div class="panel-heading">PL Collections</div>
		<div class="panel-body">
			<?php $this->load->view('loans/plcollection'); ?>
		</div>
	</div>
 </div>
 


<div class="modal fade" id="collection" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
  <?php $this->load->view('forms/plcollection');?>
 </div> 
</div>

<div class="modal fade" id="planalysis" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
  <?php $this->load->view('loans/planalysis');?>
</div>
</div>

<div class="modal fade" id="updatepension" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
	<?php	$this->load->view('forms/updatepension');	?>
</div>
</div>