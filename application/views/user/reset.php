<div id="body">
<div style="margin: 20px  ; width: 260px; padding: 20px" >
<h2>Reset Password</h2>
<?php if(isset($error)) echo "<div style='color: red'>".$error."</div>";
echo validation_errors("<div style='color: red'>","</div>");
?>
<form class="pure-form pure-form-aligned" method="post" >
<fieldset>
    <div class="pure-control-group" >                    
        <input name="email" type="text" placeholder="Email Address" required value="<?php echo set_value('email');?>">
    </div>
    <div class="pure-control-group">        	              
            <input id="newpassword" name="newpassword" type="password" placeholder="New Password" required>
        </div>
          <div class="pure-control-group">          	             
            <input id="confirmpassword" name="confirmpassword" type="password" placeholder="Confirm Password" required>
        </div>   
    <div class="pure-control-group">          
        <button type="submit" class="pure-button pure-button-primary" >Update Password</button> or <a href="<?php echo base_url();?>">Cancel</a>
    </div>
</fieldset>
</form>
</div>
</div>
