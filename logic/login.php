<?php
include 'functions.php';
include 'privileges.php';
session_start();
if(isset($_SESSION['username'])){
    echo 'success';
    exit();
}
$username=post_para('username');
$passwd=post_para('passwd');
try{
    $mysqli=getDB();
    $stmt=$mysqli->prepare('SELECT name,privilege,attributes FROM shadow WHERE username=? AND passwd=?');
    if(!$stmt)
        throw new Exception('error in initiation of SQL statement',1);
    $stmt->bind_param("ss",$username,saltilize($passwd));
    $name=NULL;$privilege=NULL;$attributes=NULL;
    $stmt->bind_result($name,$privilege,$attributes);
    if(!$stmt->execute())
        throw new Exception('error in preparation of SQL statement',1);
}catch(Exception $e){
    echo 'error';
    $mysqli->close();
    exit();
}
switch($stmt->fetch()){
    case true:
        $_SESSION['privilege']=$privilege;
        if(!check_privilege(PRI_LOGIN)){
            session_destroy();
            echo 'ungranted';
            break;
        }
        $_SESSION['username']=$username;
        $_SESSION['name']=$name;
        $_SESSION['attributes']=$attributes;
        echo 'success';
        break;
    case false:
        echo 'fail';
        session_destroy();
        break;
    case NULL:
        echo 'error';
        session_destroy();
        break;
}
$stmt->close();
$mysqli->close();
exit();
?>