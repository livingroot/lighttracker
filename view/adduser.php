<?php
include_once($_SERVER['DOCUMENT_ROOT']."/inc/func.php");
if($ustatus != 2){
	die("<p class='servmsg msg-error'>Недостаточно прав.</p>");
}
?>
<div class="main-block container col" align="center">
	<h2>Добавить аккаунт</h2>
	<form class="wideform" method="POST" action="/controller/users.php" onsubmit="return submitform(this,event);">
		<label>Логин</label>
		<input required autocomplete="off" type="text" name="login" placeholder="Логин"/>
		<label>Пароль</label>
		<input required autocomplete="off" type="password" name="password" placeholder="Пароль"/>
		<label>Статус</label>
		<select id="user-status" name="status">
			<option value="0">Гость</option>
			<option value="1">Пользователь</option>
			<option value="2">Администратор</option>
		</select>
		<button type="submit" name="adduser">Сохранить</button>
		<p id="server-answer" class="alert"></p>
	</form>
</div>
<div class="sidebar col">
	<?php include_once("sidebar.php"); ?>
</div>
