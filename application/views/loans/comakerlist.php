<form action="<?php echo base_url();?>loans/info/update" method="post" class="jquerypost">
<table class="table table-hover table-condensed">
	<thead>
		<tr>
			<th>Remove</th>
			<th>#</th>
			<th>Comaker's Name</th>
			<th>Comaker Statement</th>
		<tr>
	</thead>
	<tbody>
	<?php	
		//echo "<ol id='comakerlist'>";		
			if(!empty($comaker)){				
				if($comaker->num_rows() > 0){
					$count = 1;
					foreach($comaker->result() as $com){ 
					
						$clientinfo = $this->Clientmgmt->getclientinfoByID($com->clientID)->row();
						?>
						<tr>
							<td><input type="checkbox" name="comakerID[]" value="<?php echo $com->comakerID;?>"></td>
							<td><?php echo $count;?></td>
							<td><a href="<?php echo base_url();?>client/profile/<?php echo $com->clientID;?>" target="_blank"><?php echo $clientinfo->LastName.", ".$clientinfo->firstName;?></a></td>
							<td><a href="<?php echo base_url();?>forms/comaker/<?php echo $loanid;?>/<?php echo $com->clientID;?>" target="_blank">Co-maker's Statement</a></td>
						</tr>
						
						<?php 
						$count++;
					}							
				}					
			}			
	//echo "</ol>";
?>		
	</tbody>
	
</table>
<input type="hidden" name="updateinfo" value="comaker">
<input type="submit" name="submit" value="Remove Comaker" class="btn btn-sm btn-warning">
</form>