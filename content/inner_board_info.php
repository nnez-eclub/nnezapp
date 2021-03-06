<?php
session_start();
include '../logic/functions.php';
include '../logic/privileges.php';
if(!isset($_SESSION['username'])){
    session_destroy();
    exit();
}
?>
<?php
$mysqli=getDB();

$stmt=$mysqli->prepare('SELECT no,title,content,time FROM important_board');
$stmt->bind_result($no,$title,$content,$time);
if(!$stmt->execute()){
    echo 'error';
    exit();
}
while($stmt->fetch()){
    echo '<div class="panel panel-default">';
    echo '<div class="panel-heading">';
    echo '<h2 class="panel-title">';
    echo '<strong class="text-info">公告: </strong>'.$title.'</strong>';
    echo '</h2>';
    echo '</div>';
    echo '<div class="panel-body">';
    echo '<p class="lead">'.$content.'</p>';
    echo '</div>';
    echo '<div class="panel-footer">';
    echo '<button class="btn btn-link" onclick="unfold_inform_comments('.$no.')">评论</button>'.$time;
    echo '</div>';
    echo '</div>';
}
$stmt->close();

$stmt=$mysqli->prepare('SELECT no,title,content,time,name FROM board ORDER BY time DESC LIMIT ?,15');
$page=(get_para('page')-1)*15;
$stmt->bind_param('i',$page);
$stmt->bind_result($no,$title,$content,$time,$name);
if(!$stmt->execute()){
    echo 'error';
    exit();
}
while($stmt->fetch()){
    echo '<div class="panel panel-default">';
    echo '<div class="panel-heading">';
    echo '<h3 class="panel-title">';
    echo (check_privilege(PRI_DELETE_OTHERS_BOARD)?'<span class="glyphicon glyphicon-remove" onclick="delete_board_message('.$no.')"></span>':'').'<small>'.$no.'</small>.'.($title!='null'?$title:'<label class="text-muted" style="font-size:75%">(无标题)</label>');
    echo '</h3>';
    echo '</div>';
    if($content!='null'){
        echo '<div class="panel-body">';
        echo $content;
        echo '</div>';
    }
    echo '<div class="panel-footer">';
    echo '<button class="btn btn-link" onclick="unfold_message_comments('.$no.')">评论</button>'.'来自 <strong>'.$name.'</strong> 于 '.$time;
    echo '</div>';
    echo '</div>';
}
$stmt->close();
$mysqli->close();
?>