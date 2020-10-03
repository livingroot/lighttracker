<?php
include_once($_SERVER['DOCUMENT_ROOT']."/inc/func.php");
if($ustatus != 2){
	die("<p class='servmsg msg-error'>Недостаточно прав.</p>");
}
$getcat = $db->query("SELECT * FROM `categories`");
?>
<div class="main-block container col">
	<h2>Настройка категорий</h2>
	<form align="center" method="POST" onsubmit="return submitform(this,event);" action="/controller/settings.php">
		<table class="bigtable" id="categories">
			<tr><th>Категория</th><th>Цвет</th><th></th></tr>
			<?php
			while($row = $getcat->fetch_array(MYSQLI_ASSOC)){
				echo "<tr><td><input type='hidden' name='cat_flag[".$row['id']."]' class='flagsinput'><input placeholder='".htmlspecialchars($row["name"])."' name=\"cat_name[".$row['id']."]\" value='".htmlspecialchars($row["name"])."'/></td><td><input name=\"cat_color[".$row['id']."]\" value='#".htmlspecialchars($row["color"])."' type='color'/></td><td><button type='button' class='redbtn' onclick='markdel(this);'>X</button></td></tr>";
			}
			?>
		</table>
		<button type="button" onclick='newcategory();'>Добавить категорию</button>
		</br>
		<button>Сохранить</button>
	</form>
	<p id="server-answer" class="alert"></p>
</div>
<div class="sidebar col">
	<?php include_once("sidebar.php"); ?>
</div>
