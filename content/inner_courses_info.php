<?php
include '../logic/functions.php';
include '../logic/privileges.php';
session_start();
if(!isset($_SESSION['username'])){
    session_destroy();
    exit();
}
if(!check_privilege(PRI_ACCESS_COURSES)){
    echo 'ungranted';
    exit();
}
$mysqli=getDB();

$stmt=$mysqli->prepare('SELECT enrollment FROM user_info WHERE username=?');
$stmt->bind_param('s',$_SESSION['username']);
$chosen=-1;
$stmt->bind_result($chosen);
if(!$stmt->execute()){
    echo 'error';
    exit();
}
if(!$stmt->fetch()){
    echo 'error';
    exit();
}
$stmt->close();
$query_string='SELECT no,name,sponsor,occupied,total FROM courses WHERE name LIKE ? AND sponsor LIKE ? ';
if(get_para('av')=='true')
    $query_string=$query_string.'AND occupied<total ';
$query_string=$query_string.'ORDER BY no LIMIT ?,15';
$stmt=$mysqli->prepare($query_string);
$page=(get_para('page')-1)*15;
$sname='%'.get_para('kw').'%';$ssponsor='%'.get_para('sp').'%';
$stmt->bind_param('ssi',$sname,$ssponsor,$page);
$no=NULL;$name=NULL;$sponsor=NULL;$occupied=NULL;$total=NULL;
$stmt->bind_result($no,$name,$sponsor,$occupied,$total);

if(!$stmt->execute()){
    echo 'error';
    exit();
}

$outputed=false;
while($stmt->fetch()){
    $outputed=true;
    if($chosen==$no)
        echo '<tr class=success>';
    elseif($occupied<$total*0.8)
        echo '<tr>';
    elseif($occupied<$total){
        echo '<tr class="warning">';
    }else{
        echo '<tr class="danger">';
    }
    echo '<td>'.$no.'</td>';
    echo '<td>'.$name.'</td>';
    echo '<td>'.$sponsor.'</td>';
    echo '<td>'.$occupied.' / '.$total.'</td>';
    if(!check_privilege(PRI_ENROLL_COURSE)){
        echo '<td><button class="btn disabled">报名(没有权限)</button></td>';
    }
    elseif($chosen!=$no){
        echo '<td><button onclick="enroll('.$no.',this)" ';
        if($occupied<$total)
            echo ' class="btn btn-primary">报名</button></td>';
        else
            echo ' class="btn disabled">已满</button></td>';
    }elseif(!check_privilege(PRI_ENROLL_COURSE)){
        echo '<td><button class="btn disabled">退出报名(没有权限)</button></td>';
    }else{
        echo '<td><button class="btn btn-warning" onclick="unenroll(this)">退出报名</button></td>';
    }
    echo '</tr>';
}
if(!$outputed)
    echo '<h1 class="text-info">(empty)</h1>';
$stmt->close();
$mysqli->close();
?>