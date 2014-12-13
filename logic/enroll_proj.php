<?php
include 'functions.php';
include 'privileges.php';
session_start();
if(!isset($_SESSION['username'])){
    session_destroy();
    exit();
}
if(!check_privilege(PRI_ENROLL_PROJECT)){
    echo 'fail';
    exit();
}
$mysqli=getDB();

//get current course
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
if($chosen==get_para('no')){
    $stmt->close();
    $mysqli->close();
    echo 'error';
    exit();
}
//check if course is available
$stmt=$mysqli->prepare('SELECT occupied FROM projects WHERE no=? AND occupied<total');
$stmt->bind_param('i',get_para('no'));
$occupied=NULL;
$stmt->bind_result($occupied);
if(!$stmt->execute()){
    $stmt->close();
    $mysqli->close();
    echo 'error';
    exit();
}
$rst='fail';
if($stmt->fetch()){
    $stmt->close();
    $rst='success';
    //inc count of chosing course
    $stmt=$mysqli->prepare('UPDATE projects SET occupied=? WHERE no=?');
    $occupied++;
    $stmt->bind_param('ii',$occupied,get_para('no'));
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
    //dec count of chose course
    if($chosen!=-1){
        //  1.get current number of enrollment
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
        //  2.dec and update
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
    $stmt=$mysqli->prepare('UPDATE user_info SET current_project=? WHERE username=?');
    $stmt->bind_param('is',get_para('no'),$_SESSION['username']);
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
echo $rst;
$mysqli->close();
?>