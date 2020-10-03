<div class="main-block container col">
	<?php  include("tickets.php"); ?>
	</div>
</div>
<div class="sidebar col">
	<?php include_once("sidebar.php"); ?>
	<form method="GET" id="filters" class="container" onsubmit="return updtickets(this,0);">
		<h4>Фильтры</h4>
		<div>
			<div><input checked name="sortstatus[]" value="3" type="checkbox" id="sortcheck-solved"/><label for="sortcheck-solved">Решено</label></div>
			<div><input checked name="sortstatus[]" value="2" type="checkbox" id="sortcheck-rejected"/><label for="sortcheck-rejected">Отклонено</label></div>
			<div><input checked name="sortstatus[]" value="1" type="checkbox" id="sortcheck-accepted"/><label for="sortcheck-accepted">Принято к исправлению</label></div>
			<div><input checked name="sortstatus[]" value="0" type="checkbox" id="sortcheck-new"/><label for="sortcheck-new">На рассмотрении</label></div>
		</div>
		<select name="sortdate">
			<option value="older">Старше</option>
			<option value="newer">Новее</option>
		</select>
		<button type="submit">Применить</button>
	</form>
</div>
