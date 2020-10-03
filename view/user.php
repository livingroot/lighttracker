<?php
include_once($_SERVER['DOCUMENT_ROOT']."/inc/func.php");
if($ustatus != 2){
	die("<p class='alert alert-red'>Недостаточно прав.</p>");
}
if(!isset($_GET["id"])){
	die("<p class='alert alert-red'>ошибка.</p>");
}
?>
<div class="main-block container col" align="center">
	<?php
	$usrid = (int)$_GET["id"];
	$getuser = $db->query("SELECT `id`,`login`,`status` FROM `users` WHERE `id` = '$usrid'");
	if($getuser->num_rows > 0){
		$userinfo = $getuser->fetch_array(MYSQLI_ASSOC);
		?>
		<h2>Редактировать аккаунт</h2>
		<form class="wideform" method="POST" action="/controller/users.php" onsubmit="return submitform(this,event);">
			<input type="hidden" name="userid" value="<?=$usrid?>"/>
			<label>Логин</label>
			<input autocomplete="off" type="text" name="login" placeholder="Логин" value="<?=htmlspecialchars($userinfo["login"])?>"/>
			<label>Пароль</label>
			<input autocomplete="off" type="password" name="password" placeholder="Пароль"/>
			<label>Статус</label>
			<select id="user-status" name="status">
				<option value="0">Гость</option>
				<option value="1">Пользователь</option>
				<option value="2">Администратор</option>
			</select>
			<button type="submit" name="edituser">Сохранить</button>
			<button type="submit" class="redbtn" name="accdel" onclick="return confirm('Удалить аккаунт?');">Удалить аккаунт</button>
			<p id="server-answer" class="alert"></p>
		</form>
		<?php
		echo "<script>$('#user-status').val(".(int)$userinfo['status'].");</script>";
	} else {
		echo "<p class='alert alert-red'>Пользователь не найден.</p>";
	}
	?>
</div>
<div class="sidebar col">
	<?php include_once("sidebar.php"); ?>
</div>
