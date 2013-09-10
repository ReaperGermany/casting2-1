<?php
require_once("php/core.php");
header('Content-Type: text/html; charset=utf-8');
$objCore = new Core();

$objCore->initSessionInfo();
$objCore->initFormController();

if($objCore->getSessionInfo()->isLoggedIn()){
  	$userdata = $objCore->getUserAccountDetails();
  	echo "<a href=\"php/corecontroller.php?logoutaction=1\">[Выход]</a>";
?>
<!DOCTYPE html>
<html lang="ru">
  <head>
		<title>Редактирование Аккаунта | Casting@Online</title>
		<meta name="description" content="casting">
		<meta name="author" content="Reaper Germany">
		<link href="css/bootstrap.css" rel="stylesheet">
		<link href='https://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:700,600' rel='stylesheet' type='text/css'>
		<link href='css/boxer.css' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="css/elusive-webfont.css">
		<script src="js/jquery.min.js" type="text/javascript"></script>
		<script src="js/jquery.smooth-scroll.js"></script>
		<script src="js/boxer.js"></script>
		<link href='http://fonts.googleapis.com/css?family=Lobster&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
        <script type="text/javascript" language="javascript" src="javascript/jquery-1.3.2.js"></script>
        <script type="text/javascript" language="javascript" src="javascript/index.js"></script>
		<link rel="stylesheet" type="text/css" href="css/style.css" />
        <link rel="stylesheet" href="css/autosuggest/autosuggest_inquisitor.css" type="text/css" media="screen" charset="utf-8" />
        <script type="text/javascript" language="javascript" src="javascript/jquery-1.3.2.js"></script>
        <script type="text/javascript" language="javascript" src="javascript/autosuggest/bsn.AutoSuggest_2.1.3.js" charset="utf-8"></script>
        <script type="text/javascript" language="javascript" src="javascript/editaccount.js"></script>
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

		<div id="reg">
		
        <h1 style="margin-top: 10px;">Аккаунт</h1>
        <form name="form_edit" id="form_edit" action="" method="" class="editaccount">
        	<fieldset>
	            <legend>Персональные данные</legend>
	            <label>Фамилия и Имя</label>
	            <input type="text" class="inplaceError" id="flname" name="flname" maxlength="100" value="<?php echo $userdata['flname'];?>"/>
	            <div class="error" id="flname_error"></div>
	            <label>Страна</label>
	            <input class="inplaceError" style="width: 140px" type="text" id="country" name="country" value="<?php echo $userdata['country_name'];?>"/>
				<input type="hidden" name="country_code" id="country_code" value="-1"/>
	            <div style="height:25px;" class="error" id="country_error"></div>
			</fieldset>
			<fieldset>
	            <legend>Детали Аккаунта</legend>
	            <label>E-Mail</label>
	            <input type="text" class="inplaceError" id="email" name="email" maxlength="120" value="<?php echo $userdata['email'];?>"/>
	            <div class="error" id="email_error"></div>
	            
	            <label>Старый пароль</label>
	            <input type="password" class="inplaceError" id="currpass" name="currpass" maxlength="20" value=""/>
	            <div class="error" id="currpass_error"></div>
	            <label>Новый Пароль</label>
	            <input type="password" class="inplaceError" id="pass" name="pass" maxlength="20" value=""/>
	            <div class="error" id="pass_error"></div>
	            <label>Подтверждение пароля</label>
	            <input type="password" class="inplaceError" id="confpass" name="confpass" maxlength="20" value=""/>
	            <div class="error" id="confpass_error"></div>
	            
            </fieldset>
            <input type="hidden" name="editaccountactionx" value="1"/>
		    
           <p>После изменения своих данный сохраните их, нажав кнопку ниже.</p>
        </form>
			<a href="public_html" class="backlink">Назад</a>
			<a id="_editor_btt" class="button" href="#">Сохранить</a>
            <img class="ajaxload" style="display:none;" id="ajaxld" src="images/ajax-loader.gif"/>
        </div>
		<div id="editaccountmessage" class="message_success"></div>
        
        <script type="text/javascript">
			var options_country = {
				script:"php/suggestion.php?json=true&limit=10&field=country&",
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
<?php	
}
else{
  	header("Location: public_html/index.php");
}
unset($objCore);
?>