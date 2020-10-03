<?php
include_once($_SERVER['DOCUMENT_ROOT']."/inc/func.php");
$getstats = $db->query("SELECT COUNT(`id`),`status` FROM `tickets` GROUP BY `status`");
$stats = [ "0"=>0,"1"=>0,"2"=>0,"3"=>0 ];
while($row = $getstats->fetch_array(MYSQLI_NUM)){
	$stats[$row[1]] = (int)$row[0];
}
?>
<div class="container">
	<h4>status</h4>
	<ul>
		<li class="mrk-green">Решено: <?=$stats[3]?></li>
		<li class="mrk-yellow">На рассмотрении: <?=$stats[0]?></li>
		<li class="mrk-blue">Принято к исправлению: <?=$stats[1]?></li>
		<li class="mrk-red">Отклонено: <?=$stats[2]?></li>
	</ul>
</div>
