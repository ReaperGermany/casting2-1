<?php
    header('Content-Type: text/html; charset=utf-8');

?>
<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <title>Главная | Casting@Online</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <script src="js/jquery.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/interface.js"></script>
    <style>
      body {
        padding-top: 50px; /* Only include this for the fixed top bar */
      }
    </style>
	<!--[if lt IE 7]>
 <style type="text/css">
 .dock img { behavior: url(iepngfix.htc) }
 </style>
<![endif]-->

<link href="style_menu.css" rel="stylesheet" type="text/css" />
  </head>
  <body id="top">
		<div class="dock" id="dock2">
  <div class="dock-container2">
  <a class="dock-item2" href="http://aresurs.com/" target="_blank"><span>Home</span><img src="images/home.png" alt="home" /></a> 
  <a class="dock-item2" href="http://aresurs.com/" target="_blank"><span>Contact</span><img src="images/email.png" alt="contact" /></a> 
  <a class="dock-item2" href="http://aresurs.com/" target="_blank"><span>Portfolio</span><img src="images/portfolio.png" alt="portfolio" /></a> 
  <a class="dock-item2" href="http://aresurs.com/" target="_blank"><span>Music</span><img src="images/music.png" alt="music" /></a> 
  <a class="dock-item2" href="http://aresurs.com/" target="_blank"><span>Video</span><img src="images/video.png" alt="video" /></a> 
  <a class="dock-item2" href="http://aresurs.com/" target="_blank"><span>History</span><img src="images/history.png" alt="history" /></a> 
  <a class="dock-item2" href="http://aresurs.com/" target="_blank"><span>Calendar</span><img src="images/calendar.png" alt="calendar" /></a> 
  <a class="dock-item2" href="http://aresurs.com/" target="_blank"><span>Links</span><img src="images/link.png" alt="links" /></a> 
  <a class="dock-item2" href="http://aresurs.com/" target="_blank"><span>RSS</span><img src="images/rss.png" alt="rss" /></a> 
  <a class="dock-item2" href="http://aresurs.com/" target="_blank"><span>RSS2</span><img src="images/rss2.png" alt="rss" /></a> 
  </div>
</div>

<!--dock menu JS options -->
<script type="text/javascript">
	
	$(document).ready(
		function()
		{
			$('#dock2').Fisheye(
				{
					maxWidth: 60,
					items: 'a',
					itemsText: 'span',
					container: '.dock-container2',
					itemWidth: 40,
					proximity: 80,
					alignment : 'left',
					valign: 'bottom',
					halign : 'center'
				}
			)
		}
	);

</script>
  </body>
</html>
