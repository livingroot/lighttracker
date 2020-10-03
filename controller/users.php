<?php
include_once($_SERVER['DOCUMENT_ROOT']."/inc/func.php");
//if($_SERVER['X-Requested-With'] != "XMLHttpRequest"){ die("Доступ запрещён"); }
if($ustatus != 2){ die("{\"message\":\"Нет доступа.\"}"); }
if(isset($_POST['adduser']) && isset($_POST['login']) && isset($_POST['password'])){
	$usrlogin = $db->real_escape_string($_POST['login']);
	$usrstatus = (int)$_POST['status'];
	$usrpass = hash("sha256",$_POST['password']);
	$checkuser = $db->query("SELECT `id` FROM `users` WHERE `login` = '$usrlogin'");
	if($checkuser->num_rows > 0){
		die("{\"message\":\"Пользователь с таким ником уже существует.\"}");
	}
	$db->query("INSERT INTO `users` (`id`, `login`, `pass`, `sid`, `status`) VALUES (NULL, '".$usrlogin."', '".$usrpass."', NULL, '".$usrstatus."')");
	echo "{\"rediect\":\"/users.php\"}";
} else if(isset($_POST['accdel']) && isset($_POST['userid'])){
	$userid = (int)$_POST["userid"];
	if($userid == $uid){ die("{\"message\":\"Нельзя удалить собственный аккаунт.\"}"); }
	$db->query("DELETE FROM `users` WHERE `id` = '$userid' LIMIT 1");
	echo "{\"rediect\":\"/users.php\"}";
} else if(isset($_POST['edituser']) && isset($_POST['userid']) && isset($_POST['login']) && isset($_POST['password'])){
	$userid = (int)$_POST["userid"];
	$usrlogin = $db->real_escape_string($_POST['login']);
	$usrstatus = (int)$_POST['status'];
	if($_POST['password'] != ""){
		$usrpass = hash("sha256",$_POST['password']);
		$db->query("UPDATE `users` SET `login` = '$usrlogin',`pass`='$usrpass',`status`='$usrstatus' WHERE `id` = '".$userid."' LIMIT 1");
	} else {
		$db->query("UPDATE `users` SET `login` = '$usrlogin',`status`='$usrstatus' WHERE `id` = '".$userid."' LIMIT 1");
	}
	echo "{\"message\":\"Изменения применены.\"}";
}
?>
