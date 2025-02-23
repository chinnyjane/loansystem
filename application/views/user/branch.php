<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<div id="scroll" class="content" >
<?php if($this->auth->perms("User.Roles",$this->auth->user_id(),1) == TRUE){ ?>
<h4><a href="<?php echo base_url();?>user">Users</a> > Branches </h4>
<form class="form-horizontal" method="post" action="">
<div class="panel panel-success"><div class="panel-heading">Add Branches</div>
	<div class="panel-body">
		<div class="row form-group">
		<div class="col-md-4"><label>Branch Name</label>
        <input id="branch" name="branch" type="text" class="form-control input-sm" placeholder="Branch Name" required></div>
		<div class="col-md-4"><label>Branch Address</label>
		 <input id="address" name="address" type="text" class="form-control input-sm" placeholder="Address" required></div>
		 <div class="col-md-1"><label></label>
        <input type="submit" class="btn btn-success" value="Add Branch"></div>
		</div>
		</div>	
</div>
</form>
<?php  } ?>
<?php $branch = $this->UserMgmt->get_branches();	
if($branch->num_rows() >0){ ?>
<div class="panel panel-info"><div class="panel-heading">List of Branches</div>
	   <table class="table table-bordered">
        <thead>
            <tr>
                <th class="column-check"><input class="check-all" type="checkbox" /></th>
                <th>Branch ID</th>
                <th>Branch Name</th>               
               
            </tr>
        </thead>
        <tbody>
	<?php foreach ($branch->result() as $br): ?>
    	<tr>
        	<td ><input type="checkbox" name="checked[]"  class="case" value="<?php echo $br->id; ?>" /></td>
					<td><?php echo $br->id; ?></td>
					<td><?php echo $br->branchname; ?></td>
					
				</tr>
    <?php endforeach;?>	
    </tbody>
    </table>
</div>
	<?php 		
	if($this->auth->perms("User.Branches",$this->auth->user_id(),4) == TRUE){ ?><div style="margin: 10px;">
	<input type="submit" class="pure-button button-error" name="submit" value="Delete Branch(es)"></div><?php } ?>
<?php }else{
	echo "No branches yet";
}
?>     
</div>
</div>
