<div id="menu">
	<div class='logo-container'>
		<?=SITENAME?>
	</div>
	<div class="menu-links">
		<a href="/" onclick="return navgo(this);">Список задач</a>
		<?php
		echo "<div class=\"submenu\">";
		$getc = $db->query("SELECT `id`,`name` FROM `categories`");
		while($row = $getc->fetch_array(MYSQLI_NUM)){
			echo "<a href=\"/?category=".$row[0]."\" onclick=\"return navgo(this);\">".htmlspecialchars($row[1])."</a>";
		}
		echo "</div>";
		if($ustatus == 2){
			echo "<a href=\"/users.php\" onclick=\"return navgo(this);\">Пользователи</a>
					<a href=\"/settings.php\" onclick=\"return navgo(this);\">Настройки</a>";
		}
		?>
		<a href="/?logout">Выход</a>
	</div>
</div>
