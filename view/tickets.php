<?php
include_once($_SERVER['DOCUMENT_ROOT']."/inc/func.php");
if($ustatus != 0){
	echo '<a href="/newticket.php" onclick="return navgo(this);"><button style=" float: right; ">+</button></a>';
}
$curcat = "";
$filters = "";
if(isset($_GET["category"])){
	$needcat = (int)$_GET["category"];
	$selectcat = $db->query("SELECT `name` FROM `categories` WHERE `id` = '".$needcat."'");
	if($selectcat->num_rows>0){
		$cname = $selectcat->fetch_row()[0];
		$curcat = " в категории ".htmlspecialchars($cname);
		$filters = " AND `category` = '".$needcat."'";
	}
}
?>
<h2>Список задач<?=$curcat?></h2>
<div class="tickets">
	<?php
	$pg = 0;
	if(isset($_GET['page'])){ $pg = (int)$_GET["page"]; }
	$per_page = 100;
	$pgoffset = $pg*$per_page;
	if(isset($_GET['sortstatus'])){
		$statuses = "";
		foreach ($_GET['sortstatus'] as $s) {
			if($statuses != ""){
				$statuses .= " OR";
			}
			$statuses .= " `status` = '".(int)$s."'";
		}
		$filters .= " AND (".$statuses.")";
	}
	if(isset($_GET['sortdate'])){
		if($_GET['sortdate'] == "older"){
			$filters .= " ORDER BY `createtime` DESC";
		} else if($_GET['sortdate'] == "newer"){
			$filters .= " ORDER BY `createtime` ASC";
		}
	} else {
		$filters .=" ORDER BY `status` ASC, `createtime` DESC";
	}
	$gettickes = $db->query("SELECT `tickets`.`id`,`category`,`title`,`status`,`createtime`,`createdby`,`categories`.`name`,`categories`.`color`,(SELECT `login` FROM `users` WHERE `users`.`id` = `tickets`.`createdby` LIMIT 1) as `usrname` FROM `tickets` LEFT JOIN `categories` ON `tickets`.`category` = `categories`.`id` WHERE 1 ".$filters. " LIMIT ".$pgoffset.",".$per_page);
	while($row = $gettickes->fetch_array()){
		$status = getstatusname($row['status']);
		$category = "";
		if($row['name'] != ""){
			$category = "<span class=\"tcategory\" style=\"color:#".htmlspecialchars($row['color']).";\">".htmlspecialchars($row['name'])."</span>";
		}
		echo "
			<a href=\"/ticket.php?id=".$row['id']."\" onclick=\"return navgo(this);\">
				<div class=\"ticket\">
					".$category."
					<div class=\"ttitle\">".htmlspecialchars($row['title'])."</div>
					".$status."
					<span class=\"ttime\">".htmlspecialchars($row['usrname'])." - ".when($row['createtime'])."</span>
				</div>
			</a>";
	}
	$totalrows = (int)$db->query("SELECT COUNT(`id`) FROM `tickets` WHERE 1 ".$filters)->fetch_row()[0];
	$pages = ceil($totalrows/$per_page);
	if($pages > 1){
		echo "<div align='center'>";
		for($i = 0; $i < $pages; $i++){
			echo "<a onclick=\"return updtickets(filters,".$i.");\" href='?page=".$i."'><button>".$i."</button></a>";
		}
		echo "</div>";
	}
	?>
