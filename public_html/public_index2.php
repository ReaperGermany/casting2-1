<?php
header('Content-Type: text/html; charset=utf-8');
require_once("php/core.php");

$objCore = new Core();

$objCore->initSessionInfo();
$objCore->initFormController();
?>
<!DOCTYPE html>
<html lang="ru">
  <head>
		<title>Личный кабинет | Casting@Online</title>
		<meta name="description" content="casting">
		<meta name="author" content="Reaper Germany">
		<link href="css/bootstrap.css" rel="stylesheet">
		<link href='https://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:700,600' rel='stylesheet' type='text/css'>
		<link href='css/boxer.css' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="css/elusive-webfont.css">
		<link rel="stylesheet" type="text/css" href="css/style.css" />
        <script src="js/jquery.min.js" type="text/javascript"></script>
		<script src="js/bootstrap.min.js" type="text/javascript"></script>
		<script src="js/jquery.smooth-scroll.js"></script>
		<script src="js/boxer.js"></script>
		<link href='http://fonts.googleapis.com/css?family=Lobster&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
        <script type="text/javascript" language="javascript" src="javascript/jquery-1.3.2.js"></script>
        <script type="text/javascript" language="javascript" src="javascript/index.js"></script>
		<link rel="stylesheet" type="text/css" href="css/style.css" />
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
          <a class="brand" href="index.php">Casting@Online</a>
<?php
    if($objCore->getSessionInfo()->isLoggedIn())
    {
        echo '<ul class="nav boxer_nav" style="float:right">
                <li><a href="faq.php">Помощь</a></li>';
        
        if($objCore->isAdmin())
            echo '<li><a href="admin.php">Админка</a></li>';
        
        echo '  <li><a href="php/corecontroller.php?logoutaction=1">Выход</a></li>
              </ul>';
    }
?>
            
                
            
       </div>
      </div>
    </div>
<?php
    if($objCore->getSessionInfo()->isLoggedIn())
    {
        echo '<div class="container">
			<div class="tabbable tabs-left" style="border:1px solid; width: 250px; float: left">
				<ul class="nav nav-pills nav-stacked" style="margin-top:10px; text-align:left;width:170px">
					<li class=""><a href="#rA" data-toggle="tab">Моя Страница</a></li>
					<li class=""><a href="#rB" data-toggle="tab">Мои Фотографии</a></li>
					<li class=""><a href="#rC" data-toggle="tab">Мои Видеозаписи</a></li>
					<li class=""><a href="#rD" data-toggle="tab">Мои Аудиозаписи</a></li> 
					<li class=""><a href="#rE" data-toggle="tab">Мои Кастинги</a></li>
					<li class=""><a href="#rF" data-toggle="tab">Мои Настройки</a></li>
			</ul>
			</div>
			 <div class="tab-content" style="border:0px solid; width: 675px; text-align: left">
				<div id="rA" class="hide fade">
					<form action="handler.php">
						  <p><b>Как по вашему мнению расшифровывается аббревиатура &quot;ОС #1&quot;?</b></p>
						  <p><input type="radio" name="answer" value="a1">Офицерский состав<Br>
						  <input type="radio" name="answer" value="a2">Операционная система<Br>
						  <input type="radio" name="answer" value="a3">Большой полосатый мух</p>
						  <p><input type="submit"></p>
					</form>
                </div>
                <div id="rB" class="hide fade">
					<form action="handler.php">
						  <p><b>Как по вашему мнению расшифровывается аббревиатура &quot;ОС #2&quot;?</b></p>
						  <p><input type="radio" name="answer" value="a1">Офицерский состав<Br>
						  <input type="radio" name="answer" value="a2">Операционная система<Br>
						  <input type="radio" name="answer" value="a3">Большой полосатый мух</p>
						  <p><input type="submit"></p>
					</form>
                </div> 
			</div>';
	}
	else
	{
?>
        <h1>Вход</h1>	 
            <form name="login" id="login" action="php/corecontroller.php" method="POST" class="login">
                <label>E-Mail</label>
                <input class="inplaceError" style="width:140px;" type="text" id="email" name="email" maxlength="120" value="<?php echo $objCore->getFormController()->value("email"); ?>"/>
                <span></span>
                <label>password</label>
                <input class="inplaceError" style="width:140px;" type="password" id="pass" name="pass" maxlength="20" value="<?php echo $objCore->getFormController()->value("pass"); ?>"/>
                <span></span>
                <div class="login_row">
                    <label>Запомнить</label><input type="checkbox" name="remember" <?php if($objCore->getFormController()->value("remember") != ""){ echo "checked"; } ?>/> 
				</div>
				<input type="hidden" name="loginaction" value="1"/> <br>
				<a class="button" id="login_button">Вход</a>
                <div id="loginerror" class="error">
                <?php echo $objCore->getFormController()->error("email"); ?>
                <?php echo $objCore->getFormController()->error("pass"); ?>
            </div>
                <p>Забыли пароль? <a href="password_forget.php">Восстановление</a></p>
				<p>Нет аккаунта?  <a href="register/index.php">Регистрация</a></p>
            </form>
        <?php
		}
        unset($objCore);
        ?>
		  <div class="boxer_footer">
      <div class="container">
        <div class="row">
          <div class="span12">
            <div class="boxer_copyright">
              &copy; 2013 Casting@Online
            </div>
          </div>
        </div>
      </div>
    </div>
    </body>
</html>