<?php
include 'functions.php';
include 'privileges.php';
session_start();
if(!isset($_SESSION['username'])){
    echo 'error';
    session_destroy();
    exit();
}
if(!check_privilege(PRI_ACCESS_TEXTS)){
	echo 'ungranted';
	exit();
}
$mysqli=getDB();
$stmt=$mysqli->prepare('SELECT COUNT(*) FROM unread_texts WHERE text_receiver=?');
$stmt->bind_param('s',$_SESSION['username']);
$ncount=null;$stmt->bind_result($ncount);
if(!$stmt->execute()){
	$stmt->close();
	$mysqli->close();
	echo 'error';
	exit();
}
if(!$stmt->fetch()){
    $stmt->close();
    $mysqli->close();
    echo 'error';
    exit();
}
$stmt->close();
$mysqli->close();
echo $ncount;
exit();
?>