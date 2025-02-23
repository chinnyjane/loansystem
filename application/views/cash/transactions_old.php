<script type="text/javascript" src="<?php echo base_url();?>assets/js/modaljs.js"></script>
<?php 
if(isset($error)) echo '<div class="alert alert-danger">'.$error.'</div>';
if($action == 'update' and $actionid == '1') echo '<div class="alert alert-success">Transaction was updated.</div>';
elseif($action == 'remove' and $actionid == '1') echo '<div class="alert alert-danger">Transaction was removed.</div>';
if(isset($success)) echo '<div class="alert alert-success">'.$success.'</div>';
echo validation_errors();

$branch = $branch->row();
$tmpl = array ('table_open'          => '<table class="table table-bordered table-condensed table-hover table-fixed-header">',
					'thead_open' => '<thead class="header">');
$this->table->set_template($tmpl);
?>
<div class="panel panel-primary"><div class="panel-heading"><b><?php echo $branch->branchname;?> </b> Transactions as of <?php echo date("F d, Y", strtotime($transdate));?></div>
	
	<!-- LINKS -->
	<ul class="nav nav-tabs" id="myTab">
		<li class="active"><a href="#banks" data-toggle="tab">Banks</a></li>
		<li><a href="#collections" data-toggle="tab">Collections</a></li>
		<li><a href="#disbursement" data-toggle="tab">Disbursements</a></li>
		<li><a href="#adjustments" data-toggle="tab">Adjustments</a></li>
		<li><a href="#recap" data-toggle="tab">Recap of Deposits</a></li>
	</ul>
	
	<div class="tab-content">	
	  <div class="tab-pane active" id="banks"><!-- BANKS -->
		<div class="panel panel-info"><div class="panel-heading">&nbsp;<?php if($this->auth->perms("Cash.Bank Accounts", $this->auth->user_id(), 1) == true) { ?><a href="<?php echo base_url();?>cash/branches/details/<?php echo $branchid;?>" class="btn btn-primary btn-xs" >Add Bank Account</a> <?php } ?></div>	
		<?php echo $this->cash->banksByTransID($branchid, $transid, $cmcstatus, $transdate);?>
		</div>
	</div>

	<div class="tab-pane" id="collections"><!-- TOTAL COLLECTIONS -->
		<div class="panel panel-success">
		<div class="panel-heading">&nbsp;<?php if($this->auth->perms("Cash.Collections", $this->auth->user_id(), 1) == true  and $cmcstatus == 'open') { ?><button class="btn btn-success btn-xs" data-toggle="modal" data-target="#collection">Add Collection</button> <?php } ?></div>	
			<?php	echo $this->cash->collectionByTransID($transid, $cmcstatus);?>
		</div>
	</div>
	
	<div class="tab-pane" id="disbursement"><!-- TOTAL DISBURSEMENT -->	
		<div class="panel panel-danger"><div class="panel-heading">&nbsp; <?php if($this->auth->perms("Cash.Disbursements", $this->auth->user_id(), 1) == true  and $cmcstatus == 'open') { ?> <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#disburse">Add Disbursement</button> <?php } ?></div>
		<?php echo $this->cash->disbursementByTransID($transid, $cmcstatus);?>
		</div>
	</div>	
	
	<div class="tab-pane" id="adjustments"><!-- TOTAL ADJUSTMENT -->	
		<div class="panel panel-warning"><div class="panel-heading">&nbsp; <?php if($this->auth->perms("Cash.Adjustments", $this->auth->user_id(), 1) == true  and $cmcstatus == 'open') { ?> <button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#adjust">Add Adjustment</button> <?php } ?></div>
		<?php echo $this->cash->adjustmentByTransID($transid, $cmcstatus);?>	
		</div>
	</div>	
	
	<div class="tab-pane" id="recap">
		<div class="panel panel-warning">
			<div class="panel-heading">
			<?php if($this->auth->perms("Cash.Recap of Deposits", $this->auth->user_id(), 1) == true  and $cmcstatus == 'open') { ?><button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#deposit">Add Deposit</button> <?php } ?>
			</div>
			<?php echo $this->load->view('cash/recap');?>
		</div>
	</div>
	</div>	
	
<div class="panel-footer">

<?php switch ($cmcstatus){
		case "open":
			echo "Transaction is still open.";
			if( $this->auth->perms("Cash.Transactions", $this->auth->user_id(), 3) == true) {
				echo '<form action="'.current_url().'" id="managetrans" method="post">';
				$data = array(
				  'type'        => 'hidden',
				  'name'          => 'transid',
				  'value'       =>  $transid
				);
				echo form_input($data);
				
				$data2 = array(
				  'type'        => 'hidden',
				  'name'          => 'update',
				  'value'       =>  "LOCK CMC");
				echo form_input($data2);
				
				$data3 = array(
				  'type'        => 'submit',
				  'name'          => 'update',
				  'value'       =>  "LOCK CMC",
				  'id' => 'locktrans',
				  'class' => "btn btn-danger btn-xs"
				);
				echo form_input($data3);
				echo "&nbsp;";
				echo "&nbsp;";
				echo "<a href='".base_url()."cash/daily/report/".$transid."' class='btn btn-warning btn-xs' target='_blank'>Preview CMC</a>";
				echo '</form>';
			}
		break;
		case "lock":
			echo "Locked : ";
			echo date("m-d-Y h:i:s",strtotime($closedate));
			echo "&nbsp;";
			echo "&nbsp;";
			if( $this->auth->perms("Cash.Transactions", $this->auth->user_id(), 3) == true) {
			echo '<form action="'.current_url().'" id="opentransaction" method="post">';
				$data = array(
				  'type'        => 'hidden',
				  'name'          => 'transid',
				  'value'       =>  $transid
				);
				echo form_input($data);
				
				$data2 = array(
				  'type'        => 'hidden',
				  'name'          => 'update',
				  'value'       =>  "OPEN CMC");
				echo form_input($data2);
				
				$data3 = array(
				  'type'        => 'submit',
				  'name'          => 'update',
				  'value'       =>  "OPEN CMC",
				   'id' => 'opentrans',
				  'class' => "btn btn-danger btn-xs"
				);
				echo form_input($data3);
				
				echo "&nbsp;";
				echo "&nbsp;";
				echo "<a href='".base_url()."cash/daily/report/".$transid."' class='btn btn-warning btn-xs' target='_blank'>Preview CMC</a>";
				echo '</form>';
			}
			echo "&nbsp;";
			echo "&nbsp;";
			
			if ($this->auth->perms("Verify CMC", $this->auth->user_id(), 3) == true) {
			echo '<form action="'.current_url().'" id="verifytransaction" method="post">';
				$data = array(
				  'type'        => 'hidden',
				  'name'          => 'transid',
				  'value'       =>  $transid
				);
				echo form_input($data);
				$data2 = array(
				  'type'        => 'hidden',
				  'name'          => 'update',
				  'value'       =>  "Verify CMC"
				);
				echo form_input($data2);
				$data3 = array(
				  'type'        => 'submit',
				  'name'          => 'update',
				  'value'       =>  "Verify CMC",
				  'id' => 'verifytrans',
				  'class' => "btn btn-success btn-xs"
				);
				echo form_input($data3);	
				echo '</form>';
			}
		break;
		case "verified":
			echo "Locked : ";
			echo date("m-d-Y h:i:s",strtotime($closedate));
			echo "&nbsp;";
			echo "&nbsp;";
			echo "Verified by : ";
			echo "<b>".$verifiedby."</b>, ";
			echo date("m-d-Y h:i:s",strtotime($verifydate));
			echo "&nbsp;";
			echo "&nbsp;";
			if ($this->auth->perms("Audit", $this->auth->user_id(), 3) == true) {
			echo '<form action="'.current_url().'" id="managetrans" method="post">';
				$data = array(
				  'type'        => 'hidden',
				  'name'          => 'transid',
				  'value'       =>  $transid
				);
				echo form_input($data);
				
				$data2 = array(
				  'type'        => 'hidden',
				  'name'          => 'update',
				  'value'       =>  "Approve CMC"
				);
				echo form_input($data2);
				
				$data3 = array(
				  'type'        => 'submit',
				  'name'          => 'update',
				  'value'       =>  "Approve CMC",
				   'class' => "btn btn-success btn-xs"
				);
				echo form_input($data3);
				echo '</form>';
			}
			echo "&nbsp;";
			echo "&nbsp;";
			echo "<a href='".base_url()."cash/daily/report/".$transid."' class='btn btn-warning btn-xs' target='_blank'>Preview CMC</a>";
		break;
		case "approved";
			echo "Locked : ";
			echo date("m-d-Y h:i:s",strtotime($closedate));
			echo "&nbsp;";
			echo "&nbsp;";
			echo "Verified by : ";
			echo "<b>".$verifiedby."</b>, ";
			echo date("m-d-Y h:i:s",strtotime($verifydate));
			echo "&nbsp;";
			echo "&nbsp;";
			echo "Approved by : ";
			echo "<b>".$approvedby."</b>, ";
			echo date("m-d-Y h:i:s",strtotime($approvedate));
			echo "&nbsp;";
			echo "&nbsp;";
			echo "<a href='".base_url()."cash/daily/report/".$transid."' class='btn btn-warning btn-xs' target='_blank'>Preview CMC</a>";
		break;
	}
	
	/*
	if( $this->auth->perms("debug", $this->auth->user_id(), 3) == true) {
				$data = array(
				  'type'        => 'hidden',
				  'name'          => 'transid',
				  'value'       =>  $transid
				);
				echo form_input($data);
				
				$data2 = array(
				  'type'        => 'hidden',
				  'name'          => 'update',
				  'value'       =>  "Recompute CMC");
				echo form_input($data2);
				
				$data3 = array(
				  'type'        => 'submit',
				  'name'          => 'update',
				  'value'       =>  "Recompute CMC",
				  'id' => 'locktrans',
				  'class' => "btn btn-danger btn-xs"
				);
				echo form_input($data3);
				echo "&nbsp;";
				echo "&nbsp;";
				
			}*/ ?>
 </div>
	</div>


<?php if($cmcstatus == 'open') { ?>
<!-- COLLECTIONS -->
<div class="modal fade" id="collection" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
	<?php $this->load->view('cash/forms/collections');?>
</div>
</div>
<!-- END OF COLLECTIONS-->

<!-- DISBURSEMENTS -->
<div class="modal fade" id="disburse" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
	<?php $this->load->view('cash/forms/disbursements');?>
  </div>
</div>
<!-- BANKS -->
<?php $this->load->view('cash/forms/bank');?>

<div class="modal fade" id="adjust" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
  <?php $this->load->view('cash/forms/adjustment');?>
 </div>
</div>

<div class="modal fade" id="deposit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
  <?php $this->load->view('cash/forms/recap');?>
 </div>
</div>
<?php } ?>
