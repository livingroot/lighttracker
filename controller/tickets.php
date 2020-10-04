<?php
include_once($_SERVER['DOCUMENT_ROOT']."/inc/func.php");

if(isset($_POST['ticket']) && isset($_POST['comment'])){
	if($ustatus != 2){ die("{\"message\":\"Нет доступа.\"}"); }
	$ticketid = (int)$_POST['ticket'];
	$status = "NULL";
	$newcat = "NULL";
	$addition = "";
	if(isset($_POST['newstatus']) && $_POST['newstatus'] != ""){
		$status = (int)$_POST['newstatus'];
		$db->query("UPDATE `tickets` SET `status` = '".$status."' WHERE `id` = '".$ticketid."';");
	}
	if(isset($_POST['ticket']) && $_POST['newcat'] != ""){
		$newcat = (int)$_POST['newcat'];
		$db->query("UPDATE `tickets` SET `category` = '".$newcat."' WHERE `id` = '".$ticketid."';");
		$catinfo = $db->query("SELECT * FROM `categories` WHERE `id` = '".$newcat."' LIMIT 1")->fetch_row();
		if(isset($catinfo[1])){
			$addition = "<p class='smalltext'>&gt; Перемещение в категорию <span style='color:#".htmlspecialchars($catinfo[2]).";'>".htmlspecialchars($catinfo[1])."</span></p>";
		}
	}
	$msg = $db->real_escape_string($_POST['comment']);
	$ins = $db->query("INSERT INTO `log` (`ticket`, `msg`, `usrid`, `time`, `newstatus`, `newcat`) VALUES ('".$ticketid."','".$msg."','".$uid."','".time()."',".$status.",".$newcat.")");
	if($ins){
		echo '{ "newmsg":"<div class=\"logmsg\"> <span>'.$uname.'</span> <span class=\"logtime\">'.str_replace("\"","\\\"",getstatusname($status)).' | только что</span> <p>'.htmlspecialchars($_POST['comment']).'</p>'.$addition.'</div>" }';
	}
}
else if(isset($_POST['newticket']) && isset($_POST['text']) && isset($_POST['subject']) && isset($_POST['category'])){
	if($ustatus == 0){ die("<p class='servmsg msg-error'>Недостаточно прав.</p>"); }
	$category = (int)$_POST['category'];
	$text = $db->real_escape_string($_POST['text']);
	$subject = $db->real_escape_string($_POST['subject']);
	$admintext = $db->real_escape_string($_POST['admintext']);
	$files_list = [];
	foreach ($_FILES['files']['tmp_name'] as $k => $f) {
		if(substr($_FILES['files']['type'][$k],0,6) == "image/" && $_FILES['files']['error'][$k] == 0){
			$filename = $uid.$k.time().".png";
			$move = move_uploaded_file($f, $_SERVER['DOCUMENT_ROOT']."/userfiles\/".$filename);
			array_push($files_list,$filename);
			if(!$move){
			 	die("{\"message\":\"Не удалось сохранить файлы. Нет доступа к папке /userfiles ?\"}");
			}
		}
	}
	$files_list = json_encode($files_list);
	$ins = $db->query("INSERT INTO `tickets`(`category`, `title`, `body`, `privatemsg`, `status`, `createtime`, `createdby`, `files`) VALUES ('".$category."','".$subject."','".$text."','".$admintext."','0','".time()."','".$uid."','".$files_list."')");
	if($ins){
		echo "{\"rediect\":\"/ticket.php?id=".$db->insert_id."\"}";
	} else {
		echo "{\"message\":\"что-то пошло не так\"}";
	}
}
?>
