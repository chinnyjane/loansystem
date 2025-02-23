<div class="row">
    <div class="col-md-4 col-md-offset-4">
    <div align="center"><img src="<?php echo base_url();?>assets/img/logo.png" /></div><br/>
        <div class="login-panel panel panel-default">
            <div class="panel-heading">                
                <h3 class="panel-title">Please Sign In</h3>
            </div>
            <div class="panel-body">
            <?php if(isset($loginerror)) { ?>
            <div class="alert alert-danger" role="alert">
                  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                  <span class="sr-only">Error:</span>
                  <?php echo $loginerror;?>
                </div>
                <?php } ?>
                <form role="form" method="post" action="">
                    <fieldset>
                        <div class="form-group">
                            <input class="form-control" placeholder="Username" name="username" type="text" autofocus>
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="Password" name="password" type="password" value="">
                        </div>                        
                        <!-- Change this to a button or input when using this as a form -->
                        <input type="submit"class="btn btn-lg btn-success btn-block" name="submit" value="Login"/>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>

