<script type="text/javascript" src="<?php echo base_url();?>assets/js/modaljs.js"></script>
<script src="<?php echo base_url();?>assets/js/loansprocess.js" type="text/javascript"></script>
<?php
$br = $branch;
$details = $br['details']->row();
$emps = $br['emps'];
$num = $emps->num_rows();
$banks = $br['banks'];
$tmpl = array ('table_open'          => '<table class="table table-bordered">' );
$this->table->set_template($tmpl);

if(isset($msg)){
	if($status == false){
		$alert = 'danger';
		$sign = 'exclamation-circle';
	}else{
		$alert = 'success';
		$sign = 'check-circle';
	}
	echo '<div class="alert alert-'.$alert.'"> <i class="fa fa-'.$sign.'"> '.$msg.'</i></div>';
}
?>

<div class="panel panel-primary">
	<div class="panel-heading">
		<b><?php echo $details->branchname;?></b>
	</div>
	<div class="panel-body">
		<form action="" method="post">
		<div class="row form-group ">
			<div class="col-md-3">
				<label>Branch Name</label>
				<input type="text" name="branchname" value="<?php echo $details->branchname;?>" class="input-sm form-control required">
			</div>
			<div class="col-md-3">
				<label>Province</label>
				<?php echo $this->form->provincefield("province",  $details->province);?>
			</div>
			<div class="col-md-3">
				<label>City</label>
				<?php echo $this->form->cityfield("city",  $details->city,  $details->province);?>
			</div>
			<div class="col-md-3">
				<label>Address</label>
				<input type="text" name="address" value="<?php echo $details->address;?>" class="input-sm form-control" required>
			</div>
		</div>
		<div class="row form-group ">
			<div class="col-md-3">				
				<input type="submit" name="submit" value="Update Branch Details" class="btn btn-primary">
			</div>
		</div>
		</form>
		
		<ul class="nav nav-tabs" id="myTab">
			<li><a data-toggle="tab" href="#employees" class="list-group-item "><i class="fa fa-users"></i> Employees</a></li>
			<li><a data-toggle="tab" href="#banks" class="list-group-item "><i class="fa fa-money"></i> Banks</a></li>			
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="employees">
				<?php
				$tmuser = array ('table_open'          => '<table class="table table-bordered" id="tableuser">' );
				$this->table->set_template($tmuser);
				if($num > 0){
					$this->table->set_heading( "ID", "Name", "Username", "Email", "Branch", "Role", "Last Login", "Status");
					foreach($emps->result() as $u){
						$check = '<input type="checkbox"  class="case" name="user[]" value="'.$u->id.'" />';
						$name = '<a href="'.base_url().'profile/overview/'.$u->id.'">'.$u->lastname.", ".$u->firstname.'</a>';
						$br = $this->UserMgmt->get_branch_by_id($u->branch_id); 
						$gr = $this->UserMgmt->get_role_byid($u->group_id); 
						if($u->deleted == 1) $stat = "Deleted";
							elseif($u->active == 1) $stat=  "Active";
							elseif($u->active == 0 ) $stat = "Deactivated";
						$this->table->add_row($check, $name, $u->username, $u->email, $br, $gr, $u->last_login,  $stat);
					}
					
					echo $this->table->generate();
					
				}else{
					echo "No Employees.";
				}
				?>
			</div>
			<div class="tab-pane panel-default" id="banks">			
			<?php if($banks->num_rows() >0) {   ?>
				<div class="table-responsive">
					<?php 
					$this->table->set_template($tmpl);
					$this->table->set_heading('<input class="check-all" type="checkbox" />','#', 'Bank Code','Bank Account', 'Bank Branch', 'Bank Address', 'Beginning Balance', 'Beginning Date');
					$count = 1;
					$total = 0;
					foreach($banks->result() as $ba){
						if(!empty($ba->branchCode))
						$bcode = "-".$ba->branchCode;
						else $bcode = "";
						$this->table->add_row('<input type="checkbox" name="checked[]"  class="case" value="'.$ba->branchBankID.'" />',$count, $ba->bankCode.$bcode,  '<a href="'.base_url().'cash/branches/bankAccount/'.$branchid.'/'.$ba->branchBankID.'">'.$ba->bankAccount.'</a>',   $ba->bankBranch, $ba->bankAddress,  number_format($ba->BeginBalance,2),  $ba->BeginDate );
						//$total += $ba->bankBalance;
						//$total = $ba->TotalBal;
						$count++;
					}
					$total = $this->Cashmodel->getbanktotal($branchid);
					$t = $total->row()->TotalBal;
					$t = $t ? $t : 0;
					//$this->table->add_row(array("colspan"=>6, "data"=>'<b>Total Cash in Bank</b>'),number_format($t,2), 0);
					echo $this->table->generate();
					?>
				</div>
				<?php }else { echo '<div class="alert alert-danger">'."No banks associated yet. ".'</div>'; }?>
			</div>
		</div>
	</div>	
</div>

<script>
    $(document).ready(function() {
        $('#tableuser').dataTable({
			"dom": 'T<"clear">lfrtip',
			"tableTools": {
				"sSwfPath": "/swf/copy_csv_xls_pdf.swf"
			}
		});
    });
   </script>
