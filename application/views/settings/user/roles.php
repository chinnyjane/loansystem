<?php echo validation_errors();?>
<form class="pure-form pure-form-aligned" method="post" action="">
<div class="panel panel-green"><div class="panel-heading">Add New Role</div>
	<div class="panel-body">
		<div class="row form-group">                  
        <div class="col-md-4"><input id="role" name="role" type="text" class="form-control input-sm" placeholder="Role" required></div>
		<div class="col-md-4"><input id="description" name="description" type="text"  class="form-control input-sm"  placeholder="Description" required></div>
		<div class="col-md-4"><button type="submit" class="btn btn-success " >Add Role</button></div>
		</div>
	</div>
</div>
</form>


<div class="panel panel-green"><div class="panel-heading">Roles/Groups</div>
<?php 
$config['base_url'] = base_url()."settings/user/roles";				
$config['per_page'] = 5;
$config['total_rows'] = $this->UserMgmt->get_total_records('roles', 1);
$config['uri_segment'] = 4;
$this->pagination->initialize($config);
$segment = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
$roles = $this->UserMgmt->get_records_from('roles' ,$config['per_page'], $segment, 1);
if($roles->num_rows() >0){
$count = $segment +1;
 ?>
     <table class="table table-bordered table-hover">
        <thead>
        <tr>
        <th class="column-check"><input class="check-all" type="checkbox" /></th>
		<th>#</th>
        <th>Role</th>
        <th># of Users</th>               
        <th>Description</th>
        </tr>
        </thead>
    <tbody>
    <?php foreach($roles->result() as $role): ?>
    <tr>
        <td ><input type="checkbox" name="checked[]"  class="case" value="<?php echo $role->group_id; ?>" /></td>
		<td><?php echo $count;?></td>
        <td><a href="<?php echo $config['base_url'];?>/edit/<?php echo $role->group_id;?>"><?php echo $role->name;  //go to permissions of modules?></a></td>
        <td><?php echo $this->UserMgmt->count_role($role->group_id);?></td>
        <td><?php echo $role->description;?></td>
    </tr>
    <?php $count++;
	endforeach;?>	
    </tbody>
    </table>
<?php } ?>
</div>
<div style="margin: 10px;"><input type="submit" class="btn btn-danger" name="submit" value="Delete Role(s)"><div style="float: right; "><?php echo $this->pagination->create_links(); ?></div></div>
</div>
</div>
