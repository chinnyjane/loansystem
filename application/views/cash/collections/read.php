List of Clients Here. View Outstanding Loan Balances Only
<?php
	$tmpl = array ('table_open'  => '<table class="table  table-striped table-bordered table-hover" id="tableclients">');
	$this->table->set_template($tmpl);
	$this->table->set_heading("Action", "Branch","Last Name", "First Name");
	
	echo $this->table->generate();
	?>
<script>
    $(document).ready(function() {      	  	
		$('#tableclients').dataTable({		
			
	        "ajax": "<?php echo base_url();?>client/overview/getclient",
			"oLanguage": {
				"sProcessing": "<p align='center'><img src='<?php echo base_url();?>assets/img/ajax-loader.gif'></p>"
			},
	        "iDisplayStart": 1,
	        "iDisplayLength": 10,
	        "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
	        "aaSorting": [[0, 'asc']],
			"aoColumnDefs": [ {
				  "aTargets": [0],
				  "mData": "download_link",
				  "mRender": function ( data, type, full ) {
						return "<div class='clientID' style='display:none'>"+full[0]+"</div><div class='btn btn-success view-btn margin-right-1em'><span class='glyphicon glyphicon-edit'></span> View Loans</div>";
				  }
				}
				
				],
	        "aoColumns": [
				
				{ "bVisible": true, "bSearchable": true, "bSortable": true },
				{ "bVisible": true, "bSearchable": true, "bSortable": true },
				{ "bVisible": true, "bSearchable": true, "bSortable": true },
				{ "bVisible": true, "bSearchable": true, "bSortable": true }
					
				
	        ],			
		});
    });
 </script>