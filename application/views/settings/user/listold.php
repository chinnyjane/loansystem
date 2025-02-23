<ul class="nav nav-tabs">
	<?php $this->settings->submenu($submod,$active);?>
</ul>
	<?php 
	//$users = $this->UserMgmt->get_users();
	if($users->num_rows() > 0)
	{	
	?>
	<form method="post"  class="pure-form">
	 
	 <div class="panel panel-primary">
	 <div class="panel-heading">Users Management</div>
	<div class="panel-body">
		<div class="row form-group">
		<div class="col-md-4">
		<div class="input-group">
		  <input type="text" class="form-control input-sm" name="search" placeholder="Search User...">
		  <span class="input-group-btn">
			<button class="btn btn-primary btn-sm" type="button">Search</button>
		  </span>
		</div>
		</div>
		<div class="col-md-4"><a href="<?php echo base_url();?>profile/create" class="btn btn-primary btn-sm">Create New User</a></div>
		</div>
	</div>
	<div class="table-responsive">
	 <table class="table table-hover table-bordered">	
		<thead>
		<tr>
		<th class="column-check"><input class="check-all" type="checkbox" /></th>
		<th>ID</th><th>Name</th><th>Email</th><th>Branch</th><th>Role</th><th>Last Login</th><th>Status</th>
		</tr>
		</thead>
		<tbody>
			<?php foreach($users->result() as $u): ?>
		<tr>
		<td class="column-check"><input type="checkbox"  class="case" name="user[]" value="<?php echo $u->id; ?>" /></td>
		<td><?php echo $u->id; ?></td>
		<td><a href="<?php echo base_url();?>profile/overview/<?php echo $u->id;?>"><?php echo $u->lastname.", ".$u->firstname; ?></a></td>
		<td><?php echo $u->email; ?></td>
		<td><?php  $br = $this->UserMgmt->get_branch_by_id($u->branch_id); 
		echo $br;
		?></td>
		<td><?php $gr = $this->UserMgmt->get_role_byid($u->group_id); 
		echo $gr;		
		?></td>
		<td><?php echo $u->last_login;?></td>
		<td><?php
			if($u->deleted == 1) echo "Deleted";
			elseif($u->active == 1) echo "Active";
			elseif($u->active == 0 ) echo "Deactivated";
		?></td>
		</tr>
			<?php endforeach;?>
					
			</tbody>
			</table>
			</div>
			<div class="panel-footer">
				<div class="row form-group">
					<div class="col-md-9">
						<input type="submit" name="submit" value="Activate" class="btn btn-sm btn-success"> 
						<input type="submit" name="submit" value="Deactivate" class="btn btn-sm btn-danger">			
						<!--<input type="submit" name="submit" value="Delete" class="btn btn-sm btn-default">
						<input type="submit" name="submit" value="Permanent Delete" class="btn btn-sm btn-default">-->
					</div>
					<?php echo $this->pagination->create_links(); ?>
					
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
	
