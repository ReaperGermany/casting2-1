<?php
require_once("php/core.php");
header('Content-Type: text/html; charset=utf-8');
$objCore = new Core();

$objCore->initSessionInfo();
$objCore->initFormController();

if($objCore->getSessionInfo()->isLoggedIn() && $objCore->isAdmin()){
  	$usersdata = $objCore->getUsersData(); 
  	echo "<a href=\"php/corecontroller.php?logoutaction=1\">Выход</a>";
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
		<script src="js/jquery.smooth-scroll.js"></script>
		<script src="js/boxer.js"></script>
		<link href='http://fonts.googleapis.com/css?family=Lobster&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
        <script type="text/javascript" language="javascript" src="javascript/index.js"></script>
		<link rel="stylesheet" type="text/css" href="css/style.css" />   
        <script type="text/javascript" language="javascript" src="javascript/jquery-1.3.2.js"></script>
        <script type="text/javascript" language="javascript" src="javascript/admin.js"></script>
        <script type="text/javascript" src="http://www.google.com/jsapi"></script>
  		<script type="text/javascript">
    		google.load('visualization', '1', {packages: ['geomap']});
    	</script>
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
		<a href="public_html" class="backlink">Вернуться</a>
		<h1>Управление Пользователями</h1>
		<div id="adminpanel" class="adminpanel">
	        
        	
			<h3>Registered Users Data - <span id="countusers"><?php echo count($usersdata)?></span> Registered Users</h3>
			<div id="mapstart" style="cursor:pointer;">map</div>
			<div id="visualization" class="map" style="display:none;" style="height:370px;"></div>
			<br/>
			<?php if(count($usersdata)>0){?>
			<table id="userslist" class="admin">
			<thead>
        	<tr>
        		<th>
        			E-Mail
        		</th>
        		<th>
        			Имя
        		</th>
        		<th>
        			Страна
        		</th>
        		<th>
        			IP
        		</th>
        		<th>
        			no. of logins
        		</th>
        		<th>
        			Дата Регистрации
        		</th>
        		<th>
        			Заблокирован
        		</th>
        		<th>
        			Админ
        		</th>
        		
        	</tr>
			</thead>
			<tbody>
			<?php 
        	for ($i=0;$i<count($usersdata);$i++){
        		if($usersdata[$i]['usr_is_blocked']=='1'){?>
        			<tr id="tr_<?php echo $usersdata[$i]['pk_user'];?>" class="statusblocked"> 
        		<?php }
        		else{
        			if($usersdata[$i]['usr_is_admin']=='1'){
        			?>
        				<tr id="tr_<?php echo $usersdata[$i]['pk_user'];?>" class="statusadmin">
        			<?php }else{?>
        				<tr id="tr_<?php echo $usersdata[$i]['pk_user'];?>">
        			<?php }}?>	 
        		
        		<td>
        			<?php echo $usersdata[$i]['email']; ?>
        		</td>
        		<td>
        			<?php echo $usersdata[$i]['flname']; ?>
        		</td>
        		<td>
        			<?php echo $usersdata[$i]['country_name']; ?>
        		</td>
        		<td>
        			<?php echo $usersdata[$i]['usr_ip']; ?>
        		</td>
        		<td>
        			<?php echo $usersdata[$i]['usr_nmb_logins']; ?>
        		</td>
        		<td>
        			<?php echo $usersdata[$i]['usr_signup_date']; ?>
        		</td>
        		<td>
        			<div class="admin_no"><?php echo $usersdata[$i]['usr_is_blocked']; ?></div>
        			<div class="_op_admin admin_change"></div>
        			<input type="hidden" value="<?php echo $usersdata[$i]['pk_user'];?>" />
        			<input type="hidden" value="block" />
        		</td>
        		<td>
        			<div class="admin_no"><?php echo $usersdata[$i]['usr_is_admin']; ?></div>
        			<div class="_op_admin admin_change"></div>
        			<input type="hidden" value="<?php echo $usersdata[$i]['pk_user'];?>" />
        			<input type="hidden" value="admin" />
        		</td>
        		<td>
        			<div class="_op_admin admin_delete"></div>
        			<input type="hidden" value="<?php echo $usersdata[$i]['pk_user'];?>" />
        			<input type="hidden" value="delete" />
        		</td>
        	</tr>
        	<?php
        	}	
        	?>
			</tbody>
        	</table>
        	<?php }?>
        </div>
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
<?php	
}
else{
  	header("Location: public_html/index.php");
}
unset($objCore);
?>