<?php
include_once($_SERVER['DOCUMENT_ROOT']."/inc/func.php");
if($ustatus == 0){
	die("<p class='servmsg msg-error'>Недостаточно прав.</p>");
}
?>
<div class="main-block container col">
	<h2>Добавить задачу</h2>
	<form method="POST" action="/controller/tickets.php" onsubmit="return submitform(this,event);" class="doubleside" enctype="multipart/form-data">
		<div>
			<textarea style="height: 150px;" required type="text" name="text" placeholder="Описание..."></textarea>
			<input multiple type="file" name="files[]"/>
		</div>
		<div>
			<label>Краткое описание</label>
			<input required type="text" name="subject" placeholder="..."/>
			<label>Сообщение для администратора</label>
			<textarea type="text" name="admintext" placeholder="..."></textarea>
			<label>Категория</label>
			<select name="category">
				<?php
				$getc = $db->query("SELECT * FROM `categories`");
				while($row = $getc->fetch_array(MYSQLI_ASSOC)){
					$color = "";
					if($row['color'] != ""){
						$color = "style=\"color:#".htmlspecialchars($row['color']).";\"";
					}
					echo "<option value='".(int)$row['id']."' ".$color.">".htmlspecialchars($row['name'])."</option>";
				}
				?>
			</select>
		</div>
		<button style="float:right;" type="submit" name="newticket">Сохранить</button>
		<p id="server-answer" class="alert alert-red"></p>
	</form>
</div>
<div class="sidebar col">
	<?php include_once("sidebar.php"); ?>
</div>
