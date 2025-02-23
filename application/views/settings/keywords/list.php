<?php
	$keywords = $this->UserMgmt->get_records_from('keywords' ,10, 0, 'all');
	$tmpl = array ('table_open' => '<table class="table table-bordered table-hover" id="tablemodule">' );
$this->table->set_template($tmpl); 
?>
<div class="panel panel-default">
	<div class="panel-heading">
    	Keywords &nbsp; <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addkeyword"><i class="fa fa-plus"></i> New Keyword</button>
    </div>
    <div class="panel-body">
    <?php
	if($keywords->num_rows() > 0){
		
		foreach($keywords->result() as $key){
			$this->table->add_row(array("class"=>"column-check","data"=>'<input  type="checkbox" name="module[]"  class="case" value="'.$key->keyID.'" />'),'<a href="">'.$key->keyword.'</a>', $key->description);
		}
		
		$this->table->set_heading(array("class"=>"column-check","data"=>'<input class="check-all" type="checkbox" />'),"Keywords", "Description");
		echo $this->table->generate();
	}	
	?> 
    </div> 
    <div class="panel-footer">
    	<input type="submit" class="btn btn-success btn-sm" name="submit" value="Activate"> &nbsp;<input type="submit" class="btn btn-danger btn-sm" name="submit" value="Deactivate">
    </div>    
</div>

<!-- ADD MODULE -->
<div class="modal fade" id="addkeyword" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
  <form class="formpost" method="post" action="<?php echo base_url();?>settings/keywords/addkeyword" name="addmodule">
	 <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Add New Keyword</h4>
      </div>
      <div class="modal-body">
	
	<div class="panel-body"> 
	<div class="row form-group">
		<div class="col-md-4"><label>Keyword</label>
		<input id="module" name="keyword" type="text" class="form-control input-sm" placeholder="Keyword" required>
		</div>	
		<div class="col-md-8"><label>Description</label>
		<input id="link" name="description" type="text" class="form-control input-sm" placeholder="keyword Description" required>
		</div>
	</div> 
		<input type="hidden" name="submit" value="Add Keyword">
		  
	</div>
	
	</div>
	<div class="modal-footer">
		 <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary btn-sm" >Add Keyword</button>
	</div>
</div>
</form>
</div>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/modaljs.js"></script>
<script>
    $(document).ready(function() {
        $('#tablemodule').dataTable({
			"dom": 'T<"clear">lfrtip',
			"tableTools": {
				"sSwfPath": "/swf/copy_csv_xls_pdf.swf"
			}
		});
    });
   </script>