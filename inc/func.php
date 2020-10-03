<?php
include_once("config.php");

session_start();
$db = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_BASE);
if ($db->connect_error) {
	die('DB connect error: '.$db->connect_error);
}

if(isset($_GET["logout"])){
	unset($_SESSION['id']);
	die(header("Location: /login.php"));
}
$auth = false;
$uname = "";
$ustatus = 0;
if(isset($_SESSION['id'])){
	$auth = true;
	$uid = (int)$_SESSION['id'];
	$userinfo = $db->query("SELECT * FROM `users` WHERE `id` = '$uid' LIMIT 1")->fetch_array();
	if($userinfo['sid'] != session_id()){
		unset($_SESSION['id']);
		$auth = false;
	}
	$uname = $userinfo['login'];
	$ustatus = $userinfo['status'];
} else {
	$auth = false;
}
if($_SERVER["SCRIPT_NAME"] != "/login.php" && $_SERVER["SCRIPT_NAME"] != "/controller/login.php"){
	check_auth();
}

function check_auth(){
	global $auth;
	if(!$auth){
		die(header("Location: /login.php"));
	}
}
function login($user,$pass){
	if($user == "" || $pass == ""){ return false; }
	global $db;
	$login = $db->real_escape_string($user);
	$hash = hash("sha256",$pass);
	$checkuser = $db->query("SELECT `id` FROM `users` WHERE `login` = '$login' AND `pass` = '$hash'");
	if($checkuser->num_rows > 0){
		$uid = (int)$checkuser->fetch_array()[0];
		$_SESSION['id'] = $uid;
		$db->query("UPDATE `users` SET `sid` = '".session_id()."' WHERE `id` = '$uid' LIMIT 1");
		return true;
	} else {
		return false;
	}
}
function when($time, $showtime = false){
	$datecode_now = date("zY");
	$datecode = date("zY",$time);
	if($time == 0){ return null; }
	else if($datecode == $datecode_now){ return "сегодня в ".date("G:i",$time); }
	else if((int)$datecode == (int)$datecode_now-10000){ return "вчера в ".date("G:i",$time); }
	else if(date("Y") == date("Y",$time)){
		$datestr = "";
		$day = date("j",$time);
		$month = date("m",$time);
		if($month == "01") { $datestr = $day." января"; }
		else if($month == "02") { $datestr = $day." февраля"; }
		else if($month == "03") { $datestr = $day." марта"; }
		else if($month == "04") { $datestr = $day." апреля"; }
		else if($month == "05") { $datestr = $day." мая"; }
		else if($month == "06") { $datestr = $day." июня"; }
		else if($month == "07") { $datestr = $day." июля"; }
		else if($month == "08") { $datestr = $day." августа"; }
		else if($month == "09") { $datestr = $day." сентября"; }
		else if($month == "10") { $datestr = $day." октября"; }
		else if($month == "11") { $datestr = $day." ноября"; }
		else if($month == "12") { $datestr = $day." декабря"; }
		if($showtime){
			$datestr .= " в ".date("G:i",$time);
		}
		return $datestr;
	}
	else { return date("d.m.Y",$time); }
}
function getstatusname($statusid){
	switch ($statusid) {
		case NULL:
			$status = "";
			break;
		case 0:
			$status = "<span class=\"tstatus marker mrk-yellow\">На рассмотрении</span>";
			break;
		case 1:
			$status = "<span class=\"tstatus marker mrk-blue\">Принято к исправлению</span>";
			break;
		case 2:
			$status = "<span class=\"tstatus marker mrk-red\">Отклонено</span>";
			break;
		case 3:
			$status = "<span class=\"tstatus marker mrk-green\">Решено</span>";
			break;
	}
	return $status;
}
?>
