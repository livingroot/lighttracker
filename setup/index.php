<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="UTF-8"/>
		<meta name="viewport" content="height=device-height, width=device-width, initial-scale=1, user-scalable=0"/>
		<script type="text/javascript" src="/res/js/jquery.min.js"></script>
		<script type="text/javascript" src="/res/js/main.js"></script>
		<link rel="stylesheet" href="/res/css/style.css" media="screen"/>
		<title>LightTracker - установка</title>
		<style>
		h4{ text-align: left;}
		</style>
	</head>
	<body>
		<div>
			<div id="auth" class="centered">
				<h2>Установка</h2>
				<?php
				include_once($_SERVER["DOCUMENT_ROOT"]."/inc/config.php");
				if(!FIRSTRUN){
					die("<p class='alert alert-red'>Уже установлен.</p>");
				}
				session_start();
				$DB_HOST = "localhost";
				$DB_USER = "root";
				$DB_PASSWORD = "";
				$DB_BASE = "lighttracker";
				$dump = file_get_contents("lighttracker.sql");
				if(!$dump){
					die("<p class='alert alert-red'>Нет доступа к дампу бд lighttracker.sql</p>");
				}
				if(isset($_POST['db_host']) && isset($_POST['db_user']) && isset($_POST['db_password']) && isset($_POST['db_table'])){
					$DB_HOST = $_POST['db_host'];
					$DB_USER = $_POST['db_user'];
					$DB_PASSWORD = $_POST['db_password'];
					$DB_BASE = $_POST['db_table'];
					$db = new mysqli($DB_HOST,$DB_USER,$DB_PASSWORD);
					if (!$db->connect_error) {
						echo "Подключение к бд успешно.</br>";
						//$createdb = true;
						$createdb = $db->query("CREATE DATABASE `lighttracker`;");
						if($createdb){
							echo "Создали БД.</br>";
							$db->select_db("lighttracker");
							$import = $db->multi_query($dump);
							if($import){
								echo "БД импортирована.</br>";
								$config = "<?php\nconst SITENAME = \"".$_POST['sitename']."\";\nconst WELCOMEMSG = \"".$_POST['welcomemsg']."\";\n//mysql\nconst DB_HOST = \"".$DB_HOST."\";\nconst DB_USER = \"".$DB_USER."\";\nconst DB_PASSWORD = \"".$DB_PASSWORD."\";\nconst DB_BASE = \"".$DB_BASE."\";\nconst FIRSTRUN = false;\n?>";
								$cfgfile = fopen($_SERVER["DOCUMENT_ROOT"]."/inc/config.php","w");
								if(!$cfgfile){
									die("<p class='alert alert-red'>Всё ок, но не удалось записать файл конфигурации.</br>Нужно в папке /inc/ создать файл config.php со следующим содержимым:</p><pre class='container'>".$config."</pre>");
								}
								fwrite($cfgfile,$config);
								fclose($cfgfile);
								if(isset($_POST['adminlogin']) && isset($_POST['adminpass'])){
									$login = $db->real_escape_string($_POST['adminlogin']);
									$pass = hash("sha256",$_POST['adminpass']);
									$db->query("UPDATE `users` SET `login`= '".$login."', `pass`='".$pass."' WHERE `id` = 1");
								}
								echo "<p class='alert alert-green'>Всё готово.</p>";
								mkdir($_SERVER["DOCUMENT_ROOT"]."/userfiles");
								unlink($_SERVER["DOCUMENT_ROOT"]."/setup/index.php");
								unlink($_SERVER["DOCUMENT_ROOT"]."/setup/lighttracker.sql");
								header("Location: /");
							} else {
								echo "<p class='alert alert-red'>Ошибка создания БД. ".$db->error."</p>";
							}
						} else {
							echo "<p class='alert alert-red'>Ошибка создания БД. ".$db->error."</p>";
						}
					} else {
						echo "<p class='alert alert-red'>Ошибка подключения к БД: ".$db->connect_error."</p>";
					}
				}
				?>
				<form method="post">
					<h3>База данных mysql</h3>
					<h4>Хост</h4>
					<input name="db_host" type="text" required placeholder="Хост" value="<?=$DB_HOST?>"/>
					<h4>Пользователь</h4>
					<input name="db_user" type="text" required placeholder="Пользователь" value="<?=$DB_USER?>"/>
					<h4>Пароль</h4>
					<input autocomplete="new-password" name="db_password" type="password" placeholder="Пароль"/>
					<h4>Таблица</h4>
					<input name="db_table" type="text" required placeholder="Таблица" value="<?=$DB_BASE?>"/>
					<h3>Аккаунт администратора</h3>
					<h4>Логин</h4>
					<input name="adminlogin" required type="text" value="admin"/>
					<h4>Пароль</h4>
					<input name="adminpass" required type="text" value="admin"/>
					<h3>Прочее</h3>
					<h4>Название сайта</h4>
					<input name="sitename" required type="text" placeholder="Название сайта" value="LightTracker"/>
					<h4>Сообщение при авторизации</h4>
					<input name="welcomemsg" required type="text" placeholder="Сообщение на странице авторизации" value="<h1>Hello</h1>"/>
					<button type="submit">Сохранить</button>
				</form>
				<p class="smalltext">Файл конфигурации: /inc/config.php</p>
			</div>
		</div>
	</body>
</html>
