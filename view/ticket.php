<?php
include_once($_SERVER['DOCUMENT_ROOT']."/inc/func.php");
if(!isset($_GET["id"])){ die("<p class='alert alert-red'>Не указан обязательный параметр</p>"); }
$ticketid = (int)$_GET["id"];
$getticket = $db->query("SELECT *,(SELECT `login` FROM `users` WHERE `users`.`id` = `tickets`.`createdby` LIMIT 1) as `user` FROM `tickets` LEFT JOIN `categories` ON `tickets`.`category` = `categories`.`id` WHERE `tickets`.`id` = '".$ticketid."'");
if($getticket->num_rows < 0){ die("<p class='alert alert-red'>Тикет не найден</p>");  }
$ticket = $getticket->fetch_array(MYSQLI_ASSOC);
?>
<div class="main-block col">
	<div class="container">
	<?php
	$category = "";
	if($ticket['name'] != ""){
		$category = "<p class='tcategory' style=\"color:#".$ticket["color"].";\">".$ticket['name']."</p>";
	}
	echo $category."
		<h2>Тикет #".$ticketid."</h2>
		<h3>".htmlspecialchars($ticket['title'])."</h3>
		<p>".htmlspecialchars($ticket['body'])."</p>";
	if($ustatus == 2){
		echo "<h4>Приватное сообщение:</h4><p>".$ticket['privatemsg']."</p>";
	}
	if($ticket['files'] != ""){
		echo "<div class='images'>";
		$filenames = json_decode($ticket['files']);
		foreach ($filenames as $file) {
			echo "<img src='/userfiles/img/".$file."' class='pic' onclick=\"modalimg(this);\"/>";
		}
		echo "</div>";
	}
	echo "<div class=\"ticketstatus\">
			".getstatusname($ticket['status'])."
			<span style=\"float:right;\">Создан ".$ticket['user']." ".when($ticket['createtime'])."</span>
		</div>";

	?>
	</div>
	<div class="container" id="logmsgs">
		<?php
		$getlog = $db->query("SELECT *,(SELECT `login` FROM `users` WHERE `users`.`id` = `log`.`usrid`) as `user` FROM `log` WHERE `ticket` = '".$ticketid."'");
		if($getlog->num_rows>0){
			while($log = $getlog->fetch_array()){
				$newcat = "";
				if($log['newcat'] != ""){
					$catinfo = $db->query("SELECT * FROM `categories` WHERE `id` = '".(int)$log['newcat']."' LIMIT 1")->fetch_row();
					if(isset($catinfo[1])){
						$newcat = "<p class='smalltext'>&gt; Перемещение в категорию <span style='color:#".htmlspecialchars($catinfo[2]).";'>".htmlspecialchars($catinfo[1])."</span></p>";
					} else {
						$newcat = "<p class='smalltext'>&gt; Перемещение в другую категорию</p>";
					}
				}
				echo "<div class='logmsg'>
						<span>".htmlspecialchars($log['user'])."</span>
						<span class='logtime'>".getstatusname($log['newstatus'])." | ".when($log['time'])."</span>
						<p>".htmlspecialchars($log['msg'])."</p>
						".$newcat."
					</div>";
			}
		} else {
			echo '<h2 class="ghosth">Действий нет</h2>';
		}
		?>
	</div>
	<?php
	if($ustatus == 2){
	?>
	<form class="container" type="GET" onsubmit="return submitform(this,event,addcomment);" action="/controller/tickets.php">
		<h3>Изменить статус</h3>
		<input type="hidden" name="ticket" value="<?=$ticketid?>"/>
		<textarea required style="width:95%;" placeholder="комментарий" name="comment"></textarea>
		<div>
			<select name="newstatus" id="status-select" style="display:inline-block;">
				<option value="" disabled selected>Новый статус</option>
				<option value="0">На рассмотрении</option>
				<option value="1">Принято к исправлению</option>
				<option value="3">Решено</option>
				<option value="2">Отклонено</option>
			</select>
			<select name="newcat" style="display:inline-block;">
				<option value="" disabled selected>Категория</option>
				<?php
				$getc = $db->query("SELECT `id`,`name`,`color` FROM `categories`");
				while($row = $getc->fetch_array(MYSQLI_NUM)){
					echo "<option value=\"".$row[0]."\" style='color:#".htmlspecialchars($row[2]).";'>".htmlspecialchars($row[1])."</option>";
				}
				?>
			</select>
			<button style="float:right;" type="submit">Применить</button>
		</div>
		<p id="server-answer" class="alert"></p>
	</form>
	<?php
	echo "<script>$('#status-select').val(".(int)$ticket['status'].");</script>";
	}
	?>
</div>
<div class="sidebar col">
	<?php include_once("sidebar.php"); ?>
</div>
