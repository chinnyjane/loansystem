<div><section class="logincontainer" >	<div align="center"><img src="<?php echo base_url();?>assets/img/logo.png" /></div>
<form class="pure-form" method="post" action="">
<?php if(isset($loginerror)) echo "<div style='color: red'>".$loginerror."</div>";?>
	<fieldset class="pure-group">
            <input type="text" class="pure-input-1" id="username" name="username" placeholder="Username">
            <input type="password" class="pure-input-1" name="password" placeholder="Password">
            <input type="submit" class="pure-button pure-input-1" value="Login">
        </fieldset>
		<!--<a href="<?php echo base_url();?>home/resetpassword" class="link" required>Forgot Password?</a>-->
    </form>
	
	
</section>

<script language="javascript">
	$(document).ready(function(){
		$('#username').focus();
		$('#cancel').click(function(){
			$('#shadowing').fadeOut('fast');		
			$('#popup').fadeOut('fast');			
		});	
		$('#shadowing').click(function(){
			$('#shadowing').fadeOut('slow');		
			$('#popup').fadeOut('slow');		
		});
		$('.link').click(function(){
			page = $(this).attr('href');
			$('#content').load(page + '#content'); // just load the content, not the whole page!
			if (page != location.href) {
			window.history.pushState({page:page},"", page); // 
			}
			return false;
		});
	});
	</script>
</div>