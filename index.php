<!DOCTYPE html>
<html lang="ru">
	<?php
	include_once("inc/func.php");
	//check_auth();
	?>
	<head>
		<meta charset="UTF-8"/>
		<meta name="viewport" content="height=device-height, width=device-width, initial-scale=1, user-scalable=0"/>
		<script type="text/javascript" src="/res/js/jquery.min.js"></script>
		<script type="text/javascript" src="/res/js/main.js?v=6"></script>
		<link rel="stylesheet" href="/res/css/style.css" media="screen"/>
		<title><?=SITENAME?></title>
	</head>
	<body>
		<div id="topmenu">
			<span><?=SITENAME?></span>
			<span class="hamburger" onclick="$('#menu').toggle();$(this).toggleClass('active');"><div></div><div></div><div></div></span>
		</div>
		<div id="loader-progress"></div>
		<?php
		include("inc/menu.php");
		?>
		<div id="content">
			<?php
			if(defined("CURPAGE")){
				switch (CURPAGE) {
					case 'settings':
						include("view/settings.php");
						break;
					case 'ticket':
						include("view/ticket.php");
						break;
					case 'user':
						include("view/user.php");
						break;
					case 'users':
						include("view/users.php");
						break;
					case 'newticket':
						include("view/newticket.php");
						break;
					default:
						include("view/index.php");
						break;
				}
			} else {
				include("view/index.php");
			}
			?>
		</div>
	</body>
</html>
