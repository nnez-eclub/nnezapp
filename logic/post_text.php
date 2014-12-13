<?php
include 'functions.php';
include 'privileges.php';
session_start();
if(!isset($_SESSION['username'])){
    session_destroy();
    exit();
}
if(!check_privilege(PRI_TEXT)){
	echo 'fail';
	exit();
}
$mysqli=getDB();
$stmt=$mysqli->prepare('INSERT INTO unread_texts (sender,sender_name,text_receiver,text_content,send_time) VALUES (?,?,?,?,NOW())');
$content=check_privilege(PRI_HTML_CODE)?post_para('text_content'):htmlspecialchars_decode(post_para('text_content'));
$stmt->bind_param('ssss',$_SESSION['username'],$_SESSION['name'],post_para('text_receiver'),$content);
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