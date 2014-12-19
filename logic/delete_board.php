<?php
include 'functions.php';
include 'privileges.php';
session_start();
if(!isset($_SESSION['username'])){
    session_destroy();
    exit();
}
if(!check_privilege(PRI_BOARD)){
	echo "ungranted";
	exit();
}
$mysqli=getDB();
$stmt=$mysqli->prepare('SELECT user FROM board WHERE no=?');
$stmt->bind_param('i',get_para('no'));
$author=null;$stmt->bind_result($author);
if(!$stmt->execute()){
    $stmt->close();
    $mysqli->close();
    echo 'error';
    exit();
}
if($author!=$_SESSION['username'] && !check_privilege(PRI_DELETE_OTHERS_BOARD)){
	$stmt->close();
	$mysqli->close();
	echo 'ungranted';
	exit();
}
$stmt->close();
$stmt=$mysqli->prepare('DELETE FROM board WHERE no=?');
$stmt->bind_param('i',get_para('no'));
if(!$stmt->execute()){
    $stmt->close();
    $mysqli->close();
    echo 'error';
    exit();
}
if($mysqli->affected_rows!=1){
	$stmt->close();
    $mysqli->close();
    echo 'error';
    exit();
}
$stmt->close();
$mysqli->close();
echo 'success';
exit();
?>