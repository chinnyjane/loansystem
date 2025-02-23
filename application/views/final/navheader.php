<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo base_url();?>">YCFC</a>
        </div>
        <div class="navbar-collapse collapse" style="height: 1px;">
          <ul class="nav navbar-nav navbar-right">
		  <?php if($header->num_rows() > 0){
				foreach ($header->result() as $nav){
					$title = str_replace('Header.', '', $nav->module_name);
					$link = base_url().$nav->module_link; 
					if($this->auth->perms($nav->module_name,$this->auth->user_id(),2) == TRUE){
					echo '<li><a href="'.$link.'">'.$title.'</a></li>';
					}
				}
			}?>
          </ul>
        </div>
      </div>
    </div>