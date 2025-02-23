<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<div id="scroll" class="content" >
<h4><a href="<?php echo base_url();?>user">Users</a> > Roles</h4>
<?php if($this->auth->perms("User.Roles",$this->auth->user_id(),1) == TRUE){ ?>
<?php echo validation_errors();?>
<form class="pure-form pure-form-aligned" method="post" action="">
<div class="panel panel-success"><div class="panel-heading">Add New Role</div>
	<div class="panel-body">
		<div class="row form-group">                  
        <div class="col-md-4"><input id="role" name="role" type="text" class="form-control input-sm" placeholder="Role" required></div>
		<div class="col-md-4"><input id="description" name="description" type="text"  class="form-control input-sm"  placeholder="Description" required></div>
		<div class="col-md-4"><button type="submit" class="btn btn-success " >Add Role</button></div>
		</div>
	</div>
</div>
</form>
<?php } ?>

<div class="panel panel-info"><div class="panel-heading">Roles/Groups</div>
<?php 
$roles = $this->UserMgmt->get_roles();
if($roles->num_rows() >0){ ?>
     <table class="table table-bordered table-hover">
        <thead>
        <tr>
        <th class="column-check"><input class="check-all" type="checkbox" /></th>
        <th>Role</th>
        <th># of Users</th>               
        <th>Description</th>
        </tr>
        </thead>
    <tbody>
    <?php foreach($roles->result() as $role): ?>
    <tr>
        <td ><input type="checkbox" name="checked[]"  class="case" value="<?php echo $role->group_id; ?>" /></td>
        <td><a href="<?php echo base_url();?>user/roles/edit/<?php echo $role->group_id;?>"><?php echo $role->name;  //go to permissions of modules?></a></td>
        <td><?php echo $this->UserMgmt->count_role($role->group_id);?></td>
        <td><?php echo $role->description;?></td>
    </tr>
    <?php endforeach;?>	
    </tbody>
    </table>
</div>
<?php if($this->auth->perms("User.Roles",$this->auth->user_id(),4) == TRUE){ ?>
		<div style="margin: 10px;"><input type="submit" class="btn btn-danger" name="submit" value="Delete Role(s)"></div><?php } ?>
<?php }else{
	echo "No Roles yet.";
}
?>
</div>
</div>
