<?php
$c = $client->row();
$products = $this->Loansmodel->get_productcodes();
?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/modaljs.js"></script>
<script src="<?php echo base_url();?>assets/js/loansprocess.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/jasny-bootstrap.min.js" type="text/javascript"></script>	


<form action="<?php echo base_url();?>loans/application/submit" method="post"  id="loanamount">
<div class="row form-group">
<div class="col-md-8">
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
  <div class="panel panel-primary">
    <div class="panel-heading" role="tab" id="headingOne">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
         I. Loan Information
        </a>
      </h4>
    </div>
    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
      <div class="panel-body">
        <?php $this->load->view("loans/forms/addloaninfo"); ?>	
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingTwo">
      <h4 class="panel-title">
        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          II. Collateral Information
        </a>
      </h4>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">
      <div class="panel-body">
       <?php $this->load->view("loans/forms/addcollateral");?>
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingThree">
      <h4 class="panel-title">
        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          III. Requirements
        </a>
      </h4>
    </div>
    <div id="collapseThree" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingThree">
      <div class="panel-body">
       <?php $this->load->view('loans/forms/requirements');?>
      </div>
    </div>
  </div> 
</div>
</div>
	
<div class="col-sm-4">
	<div class="panel panel-success">
		<div class="panel-heading">
		Fee Details
		</div>
		<div class="panel-body"  id="feedetails">
		<?php 
		$this->load->view('loans/forms/feedetails');
		?>
		</div>
	</div>
</div>
</div>
<div class="panel-footer">
		<input type="hidden" name="clientid" id="clientid" value="<?php echo $c->ClientID;?>">
		<input type="hidden" name="branchID" id="branchID" value="<?php echo $c->branchID;?>">
		<input type="hidden" name="cno" id="cno" value="<?php echo $c->CNO;?>">
		<input type="hidden" name="pid" id="pid" value="">
		<input type="submit" name="submit" id="saveloan" value="Save Loan Information" class="btn btn-sm btn-primary">
</div>
</form>

<script>
$(document).ready(function(){
	$("#saveloan").on("click", function(e){
		e.preventDefault();
		var form = $("#loanamount");
		var btn = $(this);
		btn.button('loading');
		bootbox.confirm("Are you sure you want to submit this application?", function(result){
			if(result)
			{
				bootbox.alert("Loan Application will be submitted", function(){
					$.ajax({
						type: "POST",
						url: $('#loanamount').attr('action'), //process to mail
						data: $('#loanamount').serialize(),
						success: function(msg){
							if(msg['stat'] == 1)
								bootbox.alert(msg['data'], function(){
									location.href = msg['url'];									
								});
							else
								bootbox.alert(msg['data']);
							btn.button('reset');
						},
						error: function(msg){
							bootbox.alert(msg);
							btn.button('reset');
						}
					});
				});
				
			}
			else
				btn.button('reset');
		});
	});	
	
});
</script>
