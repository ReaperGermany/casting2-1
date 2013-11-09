<?php $this->load->view('header')?>
<div class="top">
    <div class="content">
        <h1 id="login2"><a href="<?php echo base_url()?>"></a></h1>        
        <div class="clear"></div>
    </div>
</div>
<div class="wellcome">
	<div class="content">
    	<h2>Registration </h2>
    </div>
</div>        
<div class="registration">
<script type="text/javascript" src="<?php echo base_url() ?>skin/js/registration.js"></script>

<form id="registration_form" onsubmit="registre(); return false;">
    <div class="acaunt">
        <label for="login" id="login_l">Логин:</label>
        <input id="login" class="text" name="login" type="text" />

        <label for="pass" id="pass_l">Пароль:</label>
        <input id="pass" class="text" name="password" type="password" />
        <label for="pass_comfirm" style="width:130px" id="pass_comfirm_l">Подтверждение пароля:</label>
        <input id="pass_comfirm" class="text" name="password_comfirm" type="password" />
		<div class="clear"></div>
		<label for="skype" id="skype_l">Skype</label>
        <input id="skype" class="text" name="skype" type="text" />
        <label for="email" id="email_l">E-mail</label>
        <input id="email" class="text requred" name="email" type="mail" />
        <div class="clear"></div>
		<label for="family" id="family_l">Фамилия</label>
		<input id="family" class="text requred" name="family" type="text" />
        <label for="name" id="name_l">Имя</label>
        <input id="name" class="text requred" name="name" type="text" />
		<label for="syrname" id="syrname_l">Отчество</label>
        <input id="syrname" class="text requred" name="syrname" type="text" />
		<div class="clear"></div>
		<label for="gander" id="gander_l">Пол</label>
        <select id="gander" class="text" name="gander"/>
		<option></option>
		<option value="1">Мужской</option>
		<option value="2">Женский</option>
		</select>
		<div class="clear"></div>
		<label for="birthday" id="birthday_l">Дата рождения</label>
        <input id="birthday" class="text" name="birthday" type="date" />		
        <div class="clear"></div>
    </div>
	
    <div class="clear"></div>
    <input id="but1" name="but1" class="btn" type="submit" value="Register"/>
</form>
</div>
<?php $this->load->view('footer')?>
