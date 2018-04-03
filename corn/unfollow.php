<?php
function doo($uid){
$r0='';
$r1='';
$r2='kg';
if($uid){
if(!is_dir('config')){mkdir('config');}
$r0='config/';
$r1='_'.$uid;
$r2='ckg';
}
if(is_file($r0.'config_unfollow'.$r1.'.php')){
unlink($r0.'config_unfollow'.$r1.'.php');
}
sleep(10);
file_put_contents($r0.'config_unfollow'.$r1.'.php','<?php $'.$r2.'=1;?>');
}
function stop(){
$r0='';
$r1='';
$r2='所有賬號';
if($_REQUEST['uid']){
if(!is_dir('config')){mkdir('config');}
$r0='config/';
$r1='_'.$_REQUEST['uid'];
$r2=$_REQUEST['uid'].' ';
}
if(is_file($r0.'config_unfollow'.$r1.'.php')){
unlink($r0.'config_unfollow'.$r1.'.php');
}
die($r2.' 已經停止計劃了！');
}
if($_REQUEST['stop']){stop();}

if(!is_dir('log')){mkdir('log');}
if(!is_dir('log/unfollow')){mkdir('log/unfollow');}

include( 'hfpd_api.php' );
include_once( '../lib_sql.php' );
ignore_user_abort(true);
set_time_limit(0); 

if($_REQUEST['uid']){
$rf=readsql(array('uid'=>$_REQUEST['uid']));
if($rf){
$uid=$rf['uid'];
$sid=$rf['sid'];
doo($uid);
var_dump(unfollow($uid,$sid));
}
}else{
pp('unfollow',60);
$rf=readrow();
doo();
for ($i=0;$i<count($rf);$i++){
require ('config_unfollow.php');
if(!$kg){die('計劃重啓！');}
$uid=$rf[$i]['uid'];
$sid=$rf[$i]['sid'];
doo($uid);
var_dump(unfollow($uid,$sid));
}
}
?>