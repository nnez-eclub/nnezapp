<?php
include 'functions.php';
include 'privileges.php';
session_start();
if(!isset($_SESSION['username'])){
    session_destroy();
    exit();
}
if(!check_privilege(PRI_ENROLL_PROJECT)){
    echo 'ungranted';
    exit();
}
$mysqli=getDB();
//inc count of chose course
//  1.get enrollment
$stmt=$mysqli->prepare('SELECT current_project FROM user_info WHERE username=?');
$stmt->bind_param('s',$_SESSION['username']);
$chosen=-1;
$stmt->bind_result($chosen);
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
if($chosen!=-1){
    //  2.get current nenrollment
    $stmt=$mysqli->prepare('SELECT occupied FROM projects WHERE no=?');
    $stmt->bind_param('i',$chosen);
    $occupied=NULL;
    $stmt->bind_result($occupied);
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
    //  3.dec and update
    $stmt=$mysqli->prepare('UPDATE projects SET occupied=? WHERE no=?');
    $occupied--;
    $stmt->bind_param('ii',$occupied,$chosen);
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
}
//set user's current enrollment
$stmt=$mysqli->prepare('UPDATE user_info SET current_project=-1 WHERE username=?');
$stmt->bind_param('s',$_SESSION['username']);
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
?>