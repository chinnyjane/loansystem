	<?php 
	//$users = $this->UserMgmt->get_users();
	$tmpl = array ('table_open'          => '<table class="table table-bordered table-condensed table-hover " id="tableuser">' );
	$this->table->set_template($tmpl);
	if($users->num_rows() > 0)
	{	
	?>
	<form method="post"  >
	 
	 <div class="panel panel-default">
	 <div class="panel-heading">Users Management &nbsp; <a href="<?php echo base_url();?>profile/create" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Create New User</a></div>
	<div class="panel-body">
		
	<div class="table-responsive">
		<?php $this->table->set_heading( "ID", "Name", "Username", "Email", "Branch", "Role", "Last Login", "Status");
			foreach($users->result() as $u){
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
		?>
		
	</div>
	</div>
			<div class="panel-footer">
				<div class="row form-group">
					<div class="col-md-9">
						<input type="submit" name="submit" value="Activate" class="btn btn-sm btn-success"> 
						<input type="submit" name="submit" value="Deactivate" class="btn btn-sm btn-danger">			
						<!--<input type="submit" name="submit" value="Delete" class="btn btn-sm btn-default">
						<input type="submit" name="submit" value="Permanent Delete" class="btn btn-sm btn-default">-->
					</div>
				
					
				</div>
			</div>
			</div>
			
			<br/>
			<div>
			
			<div style="float: right">
			
			</div>
			</div>
			
			</form>			
	<?php 
	
	}else echo "No Users yet.";
	?>
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
