
<div class="form-group row">
<form action="" method="post">
	<div class="col-md-3">  
    	<label>Select Branch</label>
        <?php
		if($this->auth->perms("CMC All Branches", $this->auth->user_id(), 1) == true){?>
        <select name="branch" class="input-sm form-control">
        <option>Select Branch</option>
        	<?php 
			$br = $this->UserMgmt->get_branches();
			foreach($br->result() as $b){
				echo '<option value="'.$b->id.'">'.$b->branchname.'</option>';
			}
			?>
        </select>  
        <?php			
		}else {
			echo '<input type="text" name="branchname" value="'.$this->auth->branchname().'" class="input-sm form-control" readonly>';
			echo '<input type="hidden" name="branch" value="'.$this->auth->branch_id().'" >';
		 } ?>      
        </div>
	<div class="col-md-3">
    	<label>Select Bank:</label>
        <?php
		if($banks->num_rows() >0) {
			echo '<select name="bank" class="input-sm form-control">';
			foreach($banks->result() as $ba){
			  if(!empty($ba->branchCode))
			  $bcode = "-".$ba->branchCode;
			  else $bcode = "";
			  echo '<option value="'.$ba->branchBankID.'">'.$ba->bankCode.$bcode.'</option>';
			}
			echo "</select>";
		}
		?>
    </div>
    <div class="col-md-1">
    	<label> &nbsp;</label>
        <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Search</button>
    </div>
    </form>
</div>
