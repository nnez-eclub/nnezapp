<?php
include 'functions.php';
include 'privileges.php';
session_start();
if(!isset($_SESSION['username'])){
    session_destroy();
    exit();
}
if(!check_privilege(PRI_BOARD)){
	echo 'ungranted';
    exit();
}
if(post_para('title')=='' && post_para('content')==''){
    echo "fail";
    exit();
}
$mysqli=getDB();
$stmt=$mysqli->prepare('INSERT INTO board (title,content,time,user,name) VALUES (?,?,NOW(),?,?)');
$content=post_para('content')==''?'null':(check_privilege(PRI_HTML_CODE)?post_para('content'):htmlspecialchars(post_para('content'),ENT_QUOTES));
$title=post_para('title')==''?'null':(check_privilege(PRI_HTML_CODE)?post_para('title'):htmlspecialchars(post_para('title'),ENT_QUOTES));
$stmt->bind_param('ssss',$title,$content,$_SESSION['username'],$_SESSION['name']);
if(!$stmt->execute()){
    echo 'error';
    $stmt->close();
	$mysqli->close();
    exit();
}
if($mysqli->affected_rows!=1){
    echo 'error';
    $stmt->close();
	$mysqli->close();
    exit();
}
$stmt->close();
$mysqli->close();
echo 'success';
exit();
?>