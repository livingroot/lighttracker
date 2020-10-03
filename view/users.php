<?php
include_once($_SERVER['DOCUMENT_ROOT']."/inc/func.php");
if($ustatus != 2){
	die("<p>Нет доступа</p>");
}
?>
<div class="main-block container col" align="center">
	<h2>Список пользователей</h2>
	<table class="bigtable">
		<tr><th>Логин</th><th>Статус</th><th>Действие</th></tr>
		<?php
			$getusers = $db->query("SELECT `id`,`login`,`status` FROM `users`");
			while($row = $getusers->fetch_array(MYSQLI_ASSOC)){
				$status = "Гость";
				if($row['status'] == 1){
					$status = "Пользователь";
				} else if($row['status'] == 2){
					$status = "Администратор";
				}
				echo "<tr><td>".$row['login']."</td><td>".$status."</td><td><a class='link' href=\"/user.php?id=".$row['id']."\" onclick='return navgo(this);'>✏️Редактировать</a></td></tr>";
			}
		?>
	</table>
	<a href='/adduser.php' onclick='return navgo(this);'><button>Добавить</button></a>
</div>
<div class="sidebar col">
	<?php include_once("sidebar.php"); ?>
</div>
