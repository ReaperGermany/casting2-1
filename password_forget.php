<?php
    header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="ru">
  <head>
    <title>Восстановление пароля | Casting@Online</title>
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
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <script type="text/javascript" language="javascript" src="javascript/jquery-1.3.2.js"></script>
    <script type="text/javascript" language="javascript" src="javascript/register/passwprocess.js"></script>
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
        <div id="main" class="login">
			<div class="linkback"><a href="public_html/">Вернуться</a></div>
            <h1>Восстановление пароля</h1>
            <div id="pagecontent" class="forgotpw">
                <p>Введите Ваш e-mail:</p>
                <form action="" method="" name="form_passwprocess" id="form_passwprocess">
                    <label>E-Mail</label>
                    <input class="inplaceError" type="text" id="email" name="email" maxlength="120" value=""/>
                    <input type="hidden" name="forgetpasswordaction" value="1"/>
                    <div style="clear:both;"></div>
					<div id="email_error" class="error">
                        <!--div class="errorimg" style="display:none;">This is an error</div-->
                    </div>
                    <a id="_forgetpassw_btt" class="button">Отправить</a>                  
                    <img style="display:none;margin-bottom:15px;" class="ajaxload" id="ajaxld" src="images/ajax-loader.gif"/>                  
                </form>
                
            </div>
            
        </div>
        <div class="boxer_footer">
      <div class="container">
        <div class="row">
          <div class="span12">
             <div class="boxer_copyright" style="float:center; margin-top: 20px">
              &copy; 2013 Casting@Online
            </div>
          </div>
        </div>
      </div>
    </div>
    </body>
</html>
