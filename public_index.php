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
		 <?php
            #   AJAX
            if($objCore->getSessionInfo()->isLoggedIn())
            {
                echo '
                <script type="text/javascript" language="javascript">
                    function show(info)
                    {
                        $.ajax({
                            type: "POST",
                            url: "ajax.php",
                            data: "showContent=1&cnt=" + info,
                            success: function(data){
                                $("#cnt").html(data);
                            }
                        });
                    }
                </script>
                ';
            }
        ?>
        
		<link rel="stylesheet" type="text/css" href="css/style.css"/>
	<!--[if lt IE 9]>
		<script src="js/respond.min.js"></script>
	<![endif]-->
		<style>
			body{
				padding-top:50px;
					background: url(img/bg_body.jpg) no-repeat;
					-moz-background-size: 100%; /* Firefox 3.6+ */
					-webkit-background-size: 100%; /* Safari 3.1+ и Chrome 4.0+ */
					-o-background-size: 100%; /* Opera 9.6+ */
					background-size: 120%; /* Современные браузеры */
				}
			
			nav  {
				display: block;
				width: 100%;
				overflow: hidden;
				
				}
			nav ul {
				margin: -4px 0 20px 0;
				padding: 1.7em;
				float: left;
				list-style: none;
				background: rgba(0,0,0,.2);
				-moz-border-radius: .5em;
				-webkit-border-radius: .5em;
				border-radius: .5em;    
				-moz-box-shadow: 0 1px 0 rgba(255,255,255,.2), 0 2px 1px rgba(0,0,0,.8) inset;
				-webkit-box-shadow: 0 1px 0 rgba(255,255,255,.2), 0 2px 1px rgba(0,0,0,.8) inset;
				box-shadow: 0 1px 0 rgba(255,255,255,.2), 0 2px 1px rgba(0,0,0,.8) inset;
				height:100%;
				}
			nav li {
				float:left;
					}
			nav a {
				float:left;
				padding: .8em 1.5em;
				color: #555;
				text-shadow: 0 1px 0 rgba(255,255,255,.5);
				font: normal 1.1em/1 'Open+Sans';
				letter-spacing: 1px;
				text-transform: uppercase;
				border-width: 1px;
				border-style: solid;
				border-color: #fff #ccc #999 #eee;
				background: #c1c1c1;
				background: -moz-linear-gradient(#f5f5f5, #c1c1c1);
				background: -webkit-gradient(linear, left top, left bottom, from(#f5f5f5), to(#c1c1c1));
				background: -webkit-linear-gradient(#f5f5f5, #c1c1c1);
				background: -o-linear-gradient(#f5f5f5, #c1c1c1);
				background: -ms-linear-gradient(#f5f5f5, #c1c1c1);
				background: linear-gradient(#f5f5f5, #c1c1c1);
				width: 100px;
				text-decoration: none;				
				}
			nav a:hover, nav a:focus {
				outline: 0;
				color: #fff;
				text-shadow: 0 1px 0 rgba(0,0,0,.2);
				background: #fac754;
				background: -moz-linear-gradient(#fac754, #f8ac00);
				background: -webkit-gradient(linear, left top, left bottom, from(#fac754), to(#f8ac00));
				background: -webkit-linear-gradient(#fac754, #f8ac00);
				background: -o-linear-gradient(#fac754, #f8ac00);
				background: -ms-linear-gradient(#fac754, #f8ac00);
				background: linear-gradient(#fac754, #f8ac00);
									}
			nav a:active {
				-moz-box-shadow: 0 0 2px 2px rgba(0,0,0,.3) inset;
				-webkit-box-shadow: 0 0 2px 2px rgba(0,0,0,.3) inset;
				box-shadow: 0 0 2px 2px rgba(0,0,0,.3) inset;
						}
			nav li:first-child a {
				border-left: 0;
				-moz-border-radius: 4px 0 0 4px;
				-webkit-border-radius: 4px 0 0 4px;
				border-radius: 4px 0 0 4px;            
								}
			nav li:last-child a {
				border-right: 0;
				-moz-border-radius: 0 4px 4px 0;
				-webkit-border-radius: 0 4px 4px 0;
				border-radius: 0 4px 4px 0;            
								}
								
			  .invertedshiftdown{
				padding: 0;
				width: 100%;
				border-top: 5px solid #D10000; /*Red color theme*/
				background: transparent;
				voice-family: "\"}\"";
				voice-family: inherit;
				}

				.invertedshiftdown ul{
				margin:0;
				margin-left: 40px; /*margin between first menu item and left browser edge*/
				padding: 0;
				list-style: none;
				}

				.invertedshiftdown li{
				display: inline;
				margin: 0 2px 0 0;
				padding: 0;
				text-transform:uppercase;
				}

				.invertedshiftdown a{
				float: left;
				display: block;
				font: bold 12px Arial;
				color: black;
				text-decoration: none;
				margin: 0 1px 0 0; /*Margin between each menu item*/
				padding: 5px 10px 9px 10px; /*Padding within each menu item*/
				background-color: white; /*Default menu color*/

				/*BELOW 4 LINES add rounded bottom corners to each menu item.
				  ONLY WORKS IN FIREFOX AND FUTURE CSS3 CAPABLE BROWSERS
				  REMOVE IF DESIRED*/
				-moz-border-radius-bottomleft: 5px;
				border-bottom-left-radius: 5px;
				-moz-border-radius-bottomright: 5px;
				border-bottom-right-radius: 5px;
				}

				.invertedshiftdown a:hover{
				background-color: #D10000; /*Red color theme*/
				padding-top: 9px; /*Flip default padding-top value with padding-bottom */
				padding-bottom: 5px; /*Flip default padding-bottom value with padding-top*/
				color: white;
				}

				.invertedshiftdown .current a{ /** currently selected menu item **/
				background-color: #D10000; /*Red color theme*/
				padding-top: 9px; /*Flip default padding-top value with padding-bottom */
				padding-bottom: 5px; /*Flip default padding-bottom value with padding-top*/
				color: white;
				}

				#myform{ /*CSS for sample search box. Remove if desired */
				float: right;
				margin: 0;
				margin-top: 2px;
				padding: 0;
				}

				#myform .textinput{
				width: 190px;
				border: 1px solid gray;
				}

				#myform .submit{
				font: normal 12px Verdana;
				height: 22px;
				border: 1px solid #D10000;
				background-color: black;
				color: white;
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
            if($objCore->isAdmin())
            echo'<ul class="nav boxer_nav" style="float:right">
			<li><a href="admin.php">Админка</a></li>';
 
    }
?>
            
                
            
       </div>
      </div>
    </div>
<?php
    if($objCore->getSessionInfo()->isLoggedIn())
    {
		echo'<div class="container">
        <table border="0" width="100%">
            <tr valign="top">
                <td rowspan="2" width="200">
                    <div>
					    <nav>
						    <ul>
							    <li><a href="#" onclick="show(1)">Анкета</a></li><br>
							    <li><a href="#" onclick="show(2)">Медиа</a></li><br>
							    <li><a href="#" onclick="show(3)">Кастинги</a></li><br>				
							    <li><a href="#" onclick="show(4)">Помощь</a></li><br>
							    <li><a href="#" onclick="show(5)">Настройка</a></li><br>
							    <li><a href="php/corecontroller.php?logoutaction=1">Выход</a></li><br>
						    </ul>
					    </nav>
					</div>
                    
                                      
                </td>
            </tr>
            <tr valign="top">
                <td width="200" >
                    <div style="background: rgba(0,0,0,.2);-moz-border-radius: .5em;-webkit-border-radius: .5em;"></div>
                </td>
                <td>
                    <div style="background: #c1c1c1;-moz-border-radius: .5em;-webkit-border-radius: .5em; text-align:left;" id="cnt">
						<div class="tabbable"> 
							  <ul class="nav nav-tabs">
								<li class="active"><a href="#tab1" data-toggle="tab">Основное</a></li>
								<li><a href="#tab2" data-toggle="tab">Контакты</a></li>
								<li><a href="#tab3" data-toggle="tab">Интересы</a></li>
								<li><a href="#tab4" data-toggle="tab">Образование</a></li>
								<li><a href="#tab5" data-toggle="tab">Карьера</a></li>
							  </ul>
							  <div class="tab-content">
								<div class="tab-pane active" id="tab1">
								  <p>Я в Разделе 1.</p>
								</div>
								<div class="tab-pane" id="tab2">
								  <p>Привет, я в Разделе 2.</p>
								</div>
								<div class="tab-pane" id="tab3">
								  <p>Привет, я в Разделе 3.</p>
								</div>
								<div class="tab-pane" id="tab4">
								  <p>Привет, я в Разделе 4.</p>
								</div>
								<div class="tab-pane" id="tab5">
								  <p>Привет, я в Разделе 5.</p>
								</div>
							  </div>
					</div>
					
                        </div>
				    </div>
               
             </td>
            </tr>
        </table>
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
    </body>
</html>