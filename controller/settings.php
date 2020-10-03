<?php
include_once($_SERVER['DOCUMENT_ROOT']."/inc/func.php");
if($ustatus != 2){ die("{\"message\":\"Нет доступа.\"}"); }
if(isset($_POST['cat_name'])){
	$counters = [0,0,0];//added,updated,deleted
	foreach ($_POST['cat_name'] as $k => $v) {
		$cid = (int)$k;
		$cname = $db->real_escape_string($v);
		$color = $db->real_escape_string(substr($_POST['cat_color'][$k],1));
		if($_POST['cat_flag'][$k] == "del"){//delete
			$del = $db->query("DELETE FROM `categories` WHERE `id` = '$cid'");
			if($del){ $counters[2]++; }
		} else if($k < 0){//add
			if($v == "") { continue; }
			$add = $db->query("INSERT INTO `categories` (`id`, `name`, `color`) VALUES (NULL,'".$cname."','".$color."')");
			if($add){ $counters[0]++; }
		} else { //update
			if($v == "") { continue; }
			$upd = $db->query("UPDATE `categories` SET `name` = '".$cname."', `color` = '".$color."' WHERE `id` = '$cid'");
			if($upd){ $counters[1]++; }
		}
	}
	echo "{\"message\":\"Добавлено:".$counters[0]." Обновлено:".$counters[1]." Удалено:".$counters[2]."\"}";
}
?>
