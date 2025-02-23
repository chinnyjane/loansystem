<div id="COLForm">

<?php 
if(isset($_POST['submit'])){
	if($_POST['submit'] == "Save Loan Information"){
		if(isset($_POST['col'])){
			$colname = $_POST['colname'];
			foreach($_POST['col'] as $colid=>$value){ ?>
				<div class="row form-group">
					<div class="col-md-4">
					<label><?php echo $colname[$colid];?></label>
					</div>
					<div class="col-md-6">
					<input type="text" name="col[<?php echo $colid;?>]" value="<?php echo $value;?>" class="input-sm form-control" />
					</div>
				</div>
			<?php }
		}
		
		if(isset($_POST['PL'])){
			$this->load->view('loans/forms/plform');
		}
	}
}	?>

</div>