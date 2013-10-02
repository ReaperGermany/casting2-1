<?php $this->load->view('login_header')?>
<script type="text/javascript">
    function change(lang)
	{
		location.href = base_url+'user/login/lang/'+lang;
	}
	function forgot()
	{
		$('div.login').css({'display' : 'none'});
		$('div.registration').css({'display' : 'none'});
		$('div.forgot').css({'display' : 'block'});
	}
	function back()
	{
		$('div.login').css({'display' : 'block'});
		$('div.registration').css({'display' : 'block'});
		$('div.forgot').css({'display' : 'none'});
	}
	
	function pass()
{
	if ($('#email').val()!="" && $('#login2').val()!='') {
	$.post(base_url+'user/forgot', {login:$('#login2').val(), email:$('#email').val()}, parce, 'json');
	}
	else 
	{
		alert('Enter Login and Email.');
	}
	
	function parce(resp)
{
    if (resp.status=='OK') {
        //alert('User approved.');
		back();
		alert(resp.message);
    }
    if (resp.status=='ERROR') {
        alert(resp.message);
    }
}
}
	
</script>
<div class="login">
<h2><?php echo CI_Language::line('account');?></h2>

<div class="lang">
	<select onchange="change(this.value)">
	  <option value="russian" <?php if ($lang=='russian') echo 'selected'?>>Русский</option>
	  <option value="english" <?php if ($lang=='english') echo 'selected'?>>English</option>
	  <!--option value="spanish" <?php if ($lang=='spanish') echo 'selected'?>>Español</option-->
	</select>
</div>

<form action="" method="POST">
    <label for="login" id="log"><?php echo CI_Language::line('login');?></label>
    <input id="login" class="text" name="login" type="text" />

    <label for="pass" id="pass"><?php echo CI_Language::line('password');?></label>
    <input id="pass" class="text" name="password" type="password" />

    <input type="submit" class="btn" name="submit" id="sub" value="<?php echo CI_Language::line('log_in');?>"/>
</form>
	<a nohref onclick="forgot()" style = 'cursor: pointer;'><?php echo CI_Language::line('forgot');?></a>
</div>
<div class="registration">
<h2><?php echo CI_Language::line('new');?></h2>
<a href="<?php echo site_url("user/registration"); ?>"><?php echo CI_Language::line('registration');?></a>
</div>
<div class="forgot" style="display:none;">
<h2><?php echo CI_Language::line('forgot');?></h2>
<form id="pass_change" onsubmit="pass(); return false;">
    <label for="login2" id="log"><?php echo CI_Language::line('login');?></label>
    <input id="login2" class="text" name="login2" type="text" />

    <label for="email" id="email_l">E-mail</label>
    <input id="email" class="text" name="email" type="text" />

    <input type="submit" class="btn" name="submit" id="sub1" value="<?php echo CI_Language::line('send');?>" style="margin-left: 120px;"/>
	<input type="button" class="btn" id="sub2" value="<?php echo CI_Language::line('back');?>" onclick="back()" style="float: left;margin: 10px;position: relative;top: -70px;"/>
</form>
</div>
<div class="clear"></div>
            </div>
        </div>       
    </body>
</html>
