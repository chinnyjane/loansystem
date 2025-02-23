<script type="text/javascript" src="<?php echo base_url();?>assets/js/modaljs.js"></script>
<script src="<?php echo base_url();?>assets/js/loansprocess.js" type="text/javascript"></script>
<link href="<?php echo base_url();?>assets/css/jasny-bootstrap.min.css" rel="stylesheet">
<?php
$c = $client->row();
$clientid = $this->uri->segment(3);
if($client->num_rows() > 0){
	$name = $c->LastName.", ".$c->firstName;
}else{
	$name = 'Add New Client';
}
?>
<h2><?php echo $name;?> <small><?php echo $this->UserMgmt->get_branch_by_id($c->branchID); ?> Branch</small></h2>
<div class="panel panel-default with-nav-tabs	">
<div class="panel-heading" style="margin-bottom: 0px; padding-bottom:0px;">	
	<ul class="nav nav-tabs" style="margin-bottom: 0px;">
		<li class="active"><a data-toggle="tab" href="#personal" ><i class="fa fa-user"></i> Personal Information</a></li>
		<li><a data-toggle="tab" href="#finance" ><i class="fa fa-user"></i> Financial Information</a></li>
		<li><a data-toggle="tab" href="#loaninfo"><i class="fa fa-money"></i> Loan Information</a></li>
		<li><a data-toggle="tab" href="#collateral"  ><i class="fa fa-calendar"></i> Collateral Information</a></li>
		<li><a data-toggle="tab" href="#statement"  ><i class="fa fa-book"></i> Statements of Account</a></li>    
	</ul>
</div>



<div class="tab-content">
	<div class="tab-pane active" id="personal">
		<div class="panel-body">
		<?php $this->load->view('client/personalinfo'); ?>
		</div>
    </div>
    <div class="tab-pane " id="loaninfo">
		<div class="panel-body">
		<?php $this->load->view('client/loaninfo'); ?>
		</div>
    </div>
    <div class="tab-pane " id="finance">
		<div class="panel-body">
		<?php $this->load->view('client/financeinfo'); ?>
		</div>
    </div>
     <div class="tab-pane " id="collateral">
		<div class="panel-body">
			 <h4>PENSION ACCOUNTS</h4>
			 <hr/>
			<?php $this->load->view($pension); 
			//echo $pension;
			echo "<h4>REM/CM COLLATERAL</h4><hr/>";
			$collateral = $this->Products->getCollateralsbyClient($clientid,'');	
			if($collateral){
			if($collateral->num_rows() > 0){
				$count = 1;
				foreach($collateral->result() as $col){
				
					$this->table->add_row($count, $col->productCode, $col->collateralname, $col->value);
				}
				$this->table->set_heading("#", "Collateral Type", "Name", "Description");
				echo $this->table->generate();
			}
			}
			?>
		</div>
    </div>
    <div class="tab-pane " id="statement">
		<div class="panel-body">
		<?php $this->load->view('client/statement'); ?>
		</div>
    </div>
</div>
   
</div>

<div class="modal fade" id="imageupload" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
    <?php echo form_open_multipart(base_url().'client/profile/updateinfo',' method="post" id="fileupload"');?>
    <div class="modal-content">
        	<div class="modal-header">
            	Upload Image
            </div>
            <div class="modal-body">
            	<div class="fileinput fileinput-new" data-provides="fileinput">
                  <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"></div>
                  <div>
                    <span class="btn btn-default btn-file"><span class="fileinput-new">Select image</span><span class="fileinput-exists">Change</span><input type="file" name="userfile"></span>
                    <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                  </div>
                </div>
            </div>
            <div class="modal-footer">
            	<input type="hidden" name="client" value="<?php echo $clientid;?>">
                <input type="hidden" name="info" value="image">
            	<input type="submit" name="submit" value="Upload Image" class="btn btn-primary"></div>
            </div>
      </form>
        </div>
	</div>

<script src="<?php echo base_url();?>assets/js/jasny-bootstrap.min.js" type="text/javascript"></script>	

<script type='text/javascript'>
// jquery / javascript codes will be here

$(document).ready(function(){
	$('#lock').on('switchChange.bootstrapSwitch', function(){
		alert('lock change');
	});
});
</script>

<!-- 

<ul class="nav nav-tabs" id="myTab">
			<li class="active"><a href="#cliente" data-toggle="tab">Client Information</a></li>
			<li><a href="#loaninfo" data-toggle="tab">Loan Information</a></li>	
		</ul>
		<div class="tab-content">
			<div class="tab-pane fade in active" id="cliente">
				
			<div class="tab-pane fade " id="loaninfo">
				
				<div class="well">
				<?php if (isset($p['alert'])){ ?>
				<div class="alert alert-danger alert-dismissible" role="alert">
				  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				  <?php echo $p['alert'];?>
				</div>
				<?php } ?>
				<?php // $this->load->view('client/loaninfo', $p);?>	
				<?php //$this->load->view('loans/loanform', $p);?>	
				</div>
			</div>
		</div>	 -->
