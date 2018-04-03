<?php
ignore_user_abort(true);//设置与客户机断开是否会终止脚本的执行。
set_time_limit(0); //设置脚本超时时间，为0时不受时间限制
include( '../config.php' );
include_once( '../lib_tools.php' );




if(isset($_REQUEST['uid']))//执行一个
{



$dirx='';
file_put_contents($dirx.'config/config_run_'.$_REQUEST['uid'].'.php','<?php $ckg=0;?>');
$xfile=null;

/*
$xx=sendzip('run','log/run/');
$xfile=$xx;

$date='Corn_Run 服务于'.date('y-m-d h:i:s').'重启！';
sendmsg($date);
sendmail($xfile,'Corn_Run 服务重启',$date);
*/
sleep(TIME_ALL);

/*
if(file_exists($xx)){
unlink($xx);
}*/

$dirx='';
$htm = HOME_PATH;
xffopen($htm.$dirx.'xcorn_run.php?uid='.$_REQUEST['uid']);
//include_once( 'xcorn_run.php' );
die('Corn_Run_'.$_REQUEST['uid'].' 完成重启！');












}else{

xpp('restart_run',TIME_ALL);

$dirx='';
file_put_contents($dirx.'config_run.php','<?php $kg=0;?>');
$xfile=null;


$xx=sendzip('run','log/run/');
$xfile=$xx;

$date='Corn_Run 服务于'.date('y-m-d h:i:s').'重启！'.'
控制面板: '.HOME_PATH;
sendmsg($date);
sendmail($xfile,'Corn_Run 服务重启',$date);
sleep(TIME_ALL);


if(file_exists($xx)){
unlink($xx);
}

$dirx='corn/';
$htm = HOME_PATH;
xffopen($htm.$dirx.'xcorn_run.php');
//include_once( 'xcorn_run.php' );
die('Corn_Run 完成重启！');


}
?>