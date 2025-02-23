<div id="body">
<div style="margin: 20px auto ; width: 260px; padding: 20px" >
<h2>Welcome! Login Here</h2>
<?php if(isset($loginerror)) echo "<div style='color: red'>".$loginerror."</div>";?>
<form class="pure-form pure-form-aligned" method="post" action="">
<fieldset>
    <div class="pure-control-group" >                    
        <input name="username" type="text" placeholder="Username" required>
    </div>
    <div class="pure-control-group"> 
                
        <input  name="password" type="password" placeholder="Password" required>
    </div>   
    <div class="pure-control-group">          
        <button type="submit" class="pure-button pure-button-primary" >Login</button> <a href="<?php echo base_url();?>home/resetpassword">Forgot Password?</a>
    </div>
</fieldset>
</form>
</div>
</div>
