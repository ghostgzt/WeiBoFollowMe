<?php
ignore_user_abort(true);//设置与客户机断开是否会终止脚本的执行。
set_time_limit(0); //设置脚本超时时间，为0时不受时间限制
include_once( '../config.php' );
include_once( 'apis.php' );
include_once( '../lib_sql.php' );

$dirx='';
$htm = HOME_PATH;


if(isset($_REQUEST['uid']))//执行一个
{
//die( $_REQUEST['uid']);

$rf=readsql(array('uid'=>$_REQUEST['uid']));
if($rf){
include( 'config/config_run_'.$_REQUEST['uid'].'.php' );
if(!$ckg){
  file_put_contents('config_run.php','<?php $kg=1;?>');
  file_put_contents('config/config_run_'.$_REQUEST['uid'].'.php','<?php $ckg=1;?>');
$uid=$rf['uid'];
$sid=$rf['sid'];
$type=$rf['type'];
$vfy=time();file_put_contents('start_run_'.$uid.'.ini',$vfy);
ffopen($htm.$dirx.'corn_run.php?uid='.$uid.'&sid='.$sid.'&type='.$type.'&verify='.$vfy);

die('Corn_Run_'.$_REQUEST['uid'].' 设置完成！');
}else{
ffopen($htm.$dirx.'restart_run.php?uid='.$_REQUEST['uid']);
die('Corn_Run_'.$_REQUEST['uid'].' 重启动！');
}
}else{
die('Corn_Run_'.$_REQUEST['uid'].' 设置失败！');
}




}else{



pp('xcorn_run',TIME_ALL);





$rf=readrow();
if($rf){
include( 'config_run.php' );
if(!$kg){
  file_put_contents('config_run.php','<?php $kg=1;?>');
for ($i=0;$i<count($rf);$i++){
$uid=$rf[$i]['uid'];
$sid=$rf[$i]['sid'];
$type=$rf[$i]['type'];
$vfy=time();file_put_contents('start_run_'.$uid.'.ini',$vfy);
ffopen($htm.$dirx.'corn_run.php?uid='.$uid.'&sid='.$sid.'&type='.$type.'&verify='.$vfy);
}
die('Corn_Run 设置完成！');
}else{
ffopen($htm.$dirx.'restart_run.php');
die('Corn_Run 重启动！');
}
}else{
die('Corn_Run 设置失败！');
}









}
?>