<?php
ignore_user_abort(true);//设置与客户机断开是否会终止脚本的执行。
set_time_limit(0); //设置脚本超时时间，为0时不受时间限制
include( '../config.php' );
include_once( '../lib_tools.php' );



if(isset($_REQUEST['uid']))//执行一个
{



include( 'config/config_run_'.$_REQUEST['uid'].'.php' );
if($ckg){
$dirx='';
file_put_contents($dirx.'config/config_run_'.$_REQUEST['uid'].'.php','<?php $ckg=0;?>');
$xfile=null;
/*
$xx=sendzip('army','log/army/');
$xfile=$xx;

$date='Corn_Army 服务于'.date('y-m-d h:i:s').'关闭！';
sendmsg($date);
sendmail($xfile,'Corn_Army 服务关闭',$date);*/
sleep(TIME_ALL);
/*
if(file_exists($xx)){
unlink($xx);
}*/

die('Corn_Run_'.$_REQUEST['uid'].' 服务完成关闭！');
}else{
die('Corn_Run_'.$_REQUEST['uid'].' 服务沒有運行！');
}









}else{


xpp('close_run',TIME_ALL);









include( 'config_run.php' );
if($kg){
$dirx='';
file_put_contents($dirx.'config_run.php','<?php $kg=0;?>');
$xfile=null;

$xx=sendzip('run','log/run/');
$xfile=$xx;

$date='Corn_Run 服务于'.date('y-m-d h:i:s').'关闭！'.' 控制面板: '.HOME_PATH;
sendmsg($date);
sendmail($xfile,'Corn_Run 服务关闭',$date);
sleep(TIME_ALL);

if(file_exists($xx)){
unlink($xx);
}

die('Corn_Run 服务完成关闭！');
}else{
die('Corn_Run 服务沒有運行！');
}





}
?>