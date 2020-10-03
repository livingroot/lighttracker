<?php
include_once($_SERVER['DOCUMENT_ROOT']."/inc/func.php");
//if($_SERVER['X-Requested-With'] != "XMLHttpRequest"){ die("Доступ запрещён"); }
if(!isset($_POST['login']) || !isset($_POST['password'])){ die(); }
if(login($_POST['login'],$_POST['password'])){
	echo "{\"rediect\":\"/\"}";
} else {
	echo "{\"message\":\"Неверный логин или пароль\"}";
}
?>
