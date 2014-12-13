<?php
function browser_jump($url){
    echo '<script language=\'javascript\' type=\'text/javascript\'>';
    echo 'window.location.href=\''.$url.'\'';
    echo '</script>';
    exit();
}
function server_jump($url){
    header('Location: /'.$url);
    exit();
}
function post_para($name){
    if(isset($_POST[$name]))
        return $_POST[$name];
    else
        throw new Exception('Required POST parameter \''.$name.' doesn\'t exist.');
}
function get_para($name){
    if(isset($_GET[$name]))
        return $_GET[$name];
    else
        throw new Exception('Required GET parameter \''.$name.' doesn\'t exist.');
}
function saltilize($s){
    return $s;
}
function getDB(){
    $mysqli=new mysqli(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
    if(!$mysqli){
        echo 'error';
        exit();
    }
    $mysqli->query('use '.SAE_MYSQL_DB);
    return $mysqli;
}
function checkAttribute($attStr,$att){
    $l=explode(' ',$attStr);
    foreach($l as $i => $v)
        if($attStr[$i]==$att)
            return true;
    return false;
}
function addAttribute($attStr,$att){
    $l=explode(' ',$attStr);
    foreach($l as $i => $v){
        if($v==$att)
            return $attStr;
    }
    return $attStr.' '.$att;
}
function removeAttribute($attStr,$att){
    $l=explode(' ',$attStr);
    $ans='';
    foreach($l as $i => $v)
        if($v!=$att)
            $ans=$ans.' '.$v;
    return $ans;
}
function toggleAttribute($attStr,$att){
    $l=explode(' ',$attStr);
    $ans='';
    $bexist=false;
    foreach($l as $i => $v)
        if($v!=$att)
            $ans=$ans.' '.$v;
        else
            $bexist=true;
    if($bexist)
        return $ans;
    else return $attStr.' '.$att;
}

function add_log($stype,$info){
    $type=NULL;
    switch($stype){
        case 'error':
            $type=1;
            break;
        case 'warning':
            $type=2;
            break;
        case 'normal':
            $type=0;
            break;
        default:
            throw new Exception("Unrecognized error type",1);
            break;
    }
    $mysqli=getDB();
    $stmt=$mysqli->prepare('INSERT INTO logs (user,type,info,time) VALUES (?,?,?,NOW())');
    if(!$stmt)
        return false;
    $stmt->bind_param('sis',(isset($_SESSION['username'])?$_SESSION['username']:'unlogined'),$type,$passwd);
    if(!$stmt->execute())
        return false;
    $stmt->close();
    $mysqli->close();
}

?>