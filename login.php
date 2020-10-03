<!DOCTYPE html>
<html lang="ru">
	<?php
	include("inc/func.php");
	?>
	<head>
		<meta charset="UTF-8"/>
		<meta name="viewport" content="height=device-height, width=device-width, initial-scale=1, user-scalable=0"/>
		<script type="text/javascript" src="/res/js/jquery.min.js"></script>
		<script type="text/javascript" src="/res/js/main.js"></script>
		<link rel="stylesheet" href="/res/css/style.css" media="screen"/>
		<title><?=SITENAME?></title>
	</head>
	<body>
		<div>
			<form id="auth" class="centered" method="post" action="/controller/login.php" onsubmit="return submitform(this,event);">
				<h1>🔬</h1>
				<?=WELCOMEMSG?>
				<input required type="text" name="login" placeholder="Логин"/>
				<input required type="password" name="password" placeholder="Пароль"/>
				<button type="submit">Войти</button>
				<p id="server-answer" class="alert alert-red"></p>
			</form>
		</div>
	</body>
</html>
