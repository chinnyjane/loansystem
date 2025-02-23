<?php
$config['base_url'] = base_url()."client";				
$config['per_page'] = 10;
$col = $this->Clientmgmt->get_clients($name,'','');
$config['total_rows'] = $col->num_rows();
$config['uri_segment'] = 2;
$this->pagination->initialize($config);
$segment = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
$module = $this->Clientmgmt->get_clients($name, 10000 , null);
$tmpl = array ('table_open' => '<table class="table table-bordered table-hover">' );
$this->table->set_template($tmpl);
$count = $segment + 1;
$num = $segment + $config['per_page'];
?>
<script type="text/javascript">
	$(document).ready(function() {
	
		// Support for AJAX loaded modal window.
		// Focuses on first input textbox after it loads the window.
	$('[data-toggle="modal"]').click(function(e) {
		e.preventDefault();
		var url = $(this).attr('href');
		if (url.indexOf('#') == 0) {
			$(url).modal('open');
		} else {
			$.get(url, function(data) {
				$('<div class="modal fade" id="collection" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">' + data + '</div>').modal();
			}).success(function() { $('input:text:visible:first').focus(); });
		}
	});
	});
</script>
 
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                          <b>CLIENT MANAGEMENT</b> &nbsp; <a class="btn btn-sm btn-danger" href="<?php echo base_url();?>client/addnew"> <i class="fa fa-plus"></i> New Client</a>  
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
						
                            <div class="table-responsive">
							<?php
						$tmpl = array ('table_open'  => '<table class="table  table-striped table-bordered table-hover" id="tableclients">');
						$this->table->set_template($tmpl);
						$this->table->set_heading("Action", "Branch","Last Name", "First Name",  "Date of Birth");
						if ($module->num_rows() > 0){
							foreach($module->result() as $cl){
								//$act = "<a href='".base_url()."client/page/profile/".$cl->ClientID."' title='View' data-target='#' data-toggle='modal'><span class='glyphicon glyphicon-list-alt'></span></a> &nbsp;";
								$act = "<a href='".base_url()."client/profile/".$cl->ClientID."' title='Update' ><i class='fa fa-list-alt'></i> View</a> &nbsp;";
								//$this->table->add_row($count,$act,$cl->ClientID, $cl->LastName ,$cl->firstName, $cl->dateOfBirth, substr($cl->address,0,50));
								$count++;
							}
						}else{
							$this->table->add_row( "","No results found.","","","","");
						}
						echo $this->table->generate();
						?>
                                </div>
                            <!-- /.table-responsive -->
                            
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>


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
						return '<a href="<?php echo base_url();?>client/profile/'+full[0]+'"><i class="fa fa-folder-open"></i>View Profile</a>';
				  }
				}
				
				],
	        "aoColumns": [
				
				{ "bVisible": true, "bSearchable": true, "bSortable": true },
				{ "bVisible": true, "bSearchable": true, "bSortable": true },
				{ "bVisible": true, "bSearchable": true, "bSortable": true },
				{ "bVisible": true, "bSearchable": true, "bSortable": true },
				{ "bVisible": true, "bSearchable": true, "bSortable": true }
					
				
	        ],			
		});
    });
 </script>
