<?php
require_once("../php/core.php");
$objCore = new Core();
$objCore->initSessionInfo();
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="ru">
  <head>
		<title>Регистрация | Casting@Online</title>
		<meta name="description" content="casting">
		<meta name="author" content="Reaper Germany">
		<link href="../css/bootstrap.css" rel="stylesheet">
		<link href='https://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:700,600' rel='stylesheet' type='text/css'>
		<link href='../css/boxer.css' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="../css/elusive-webfont.css">
		<script src="../js/jquery.min.js" type="text/javascript"></script>
		<script src="../js/jquery.smooth-scroll.js"></script>
		<script src="../js/boxer.js"></script>
		<link href='http://fonts.googleapis.com/css?family=Lobster&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
        <script type="text/javascript" language="javascript" src="../javascript/index.js"></script>
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
        <link rel="stylesheet" href="../css/autosuggest/autosuggest_inquisitor.css" type="text/css" media="screen" charset="utf-8" />
        <script type="text/javascript" language="javascript" src="../javascript/jquery-1.3.2.js"></script>
        <script type="text/javascript" language="javascript" src="../javascript/autosuggest/bsn.AutoSuggest_2.1.3.js" charset="utf-8"></script>
        <script type="text/javascript" language="javascript" src="../javascript/register/register.js"></script>
   <style>
			body {
				padding-top: 50px; /* Only include this for the fixed top bar */
			  }
		</style>
  </head>
      <body id="top">
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="#top">Casting@Online</a>
       </div>
      </div>
    </div>
        <div id="main">
		<a class="backlink" href="../index.php");">Вернуться</a>
            <?
            /**
            * The user is already logged in, not allowed to register.
            */
            if($objCore->getSessionInfo()->isLoggedIn()) {
            echo "<h1>Registered</h1>";
            echo "<p>We're sorry <b>$session->username</b>, but you've already registered. "
                ."<a href=\"../public_html/\">Main</a>.</p>";
            }
            else {
            ?>
            <div id="reg">
                <h1 style="margin-top: 10px;">Регистрация</h1>
                <form name="form_register" id="form_register" action="" method="" class="register">
                    <fieldset class="fieldset1">
                        <legend>Персональные данные</legend>
                        <label>Фамилия и Имя</label>
                        <input type="text" class="inplaceError" id="flname" name="flname" maxlength="100" value=""/>
                        <div class="error" id="flname_error"></div>
                        <label>Страна</label>
                        <input class="inplaceError" style="width: 140px" type="text" id="country" name="country" value=""/>
						<input type="hidden" name="country_code" id="country_code" value="-1"/>
                        <div style="height:25px;" class="error" id="country_error"></div>
                        <p>Для ввода страны,введите начальные буквы  и ожидайте выбора. В выпадающем меню выберите свою страну.</p>
                     </fieldset>
                     <fieldset class="fieldset1">  
                        <legend>Детали Аккаунта</legend>
                        <label>E-Mail</label>
                        <input type="text" class="inplaceError" id="email" name="email" maxlength="120" value=""/>
                        <div class="error" id="email_error"></div>
                        <?php 
                    	if(REPEAT_EMAIL){
                    	?>
                    	<label>Повтор E-Mail</label>
                        <input type="text" class="inplaceError" id="confemail" name="confemail" maxlength="120" value=""/>
                        <div class="error" id="confemail_error"></div>
                        <?php 
                    	}
                        ?>
                        <label>Пароль</label>
                        <input type="password" class="inplaceError" id="pass" name="pass" maxlength="20" value=""/>
                        <div class="error" id="pass_error"></div>
                    	<?php 
                    	if(REPEAT_PASSWORD){
                    	?>
                    	<label>Повтор Пароля</label>
                        <input type="password" class="inplaceError" id="confpass" name="confpass" maxlength="20" value=""/>
                        <div class="error" id="confpass_error"></div>
                        <?php 
                    	}
                        ?>
                    </fieldset>
                   
                    <fieldset class="fieldset2">
                        <legend>Проверка, что Вы не робот</legend>
                         <script>
								var RecaptchaOptions = {
								   theme: 'clean',
								   lang: 'en'
								};
						</script>
						
						<?php            
						require_once('../php/recaptchalib.php');
						$publickey = PUBLICKEY; // you got this from the signup page
						echo recaptcha_get_html($publickey);
						?>
						<div class="error" id="recaptcha_response_field_error"></div>
                    </fieldset>
                    <input type="hidden" name="registeractionx" value="1"/>
					 <a id="_register_btt" class="button">Продолжить</a>
                <img class="ajaxload" style="display:none;" id="ajaxld" src="../images/ajax-loader.gif"/>
                </form>
               
            </div>

        </div>

        <?php
        }
        unset($objCore);
        ?>
		<script type="text/javascript">
			var options_country = {
				script:"../php/suggestion.php?json=true&limit=10&field=country&",
				varname:"input",
				json:true,
				shownoresults:false,
				maxresults:10,
				callback: function (obj) { $('#country_code').val(obj.id); }
			};
			var as_json_country = new bsn.AutoSuggest('country', options_country);
		</script>
  <div class="boxer_footer">
      <div class="container">
        <div class="row">
          <div class="span12">
            <div class="boxer_copyright" style="margin-top:40px">
              &copy; 2013 Casting@Online
            </div>
          </div>
        </div>
      </div>
    </div>		
    </body>
</html>
