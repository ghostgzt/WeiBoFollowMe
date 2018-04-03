<?php
ignore_user_abort(true);//设置与客户机断开是否会终止脚本的执行。
set_time_limit(0); //设置脚本超时时间，为0时不受时间限制
@include( dirname(__FILE__).'/config.php' );
$s=( split('\/',str_replace('\\','/',dirname(__FILE__))));
define(HF_CRON,$s[count($s)-1]);
@include_once( '../../lib_tools.php' );




if(isset($_REQUEST['uid']))//执行一个
{




file_put_contents('config/config_run_'.$_REQUEST['uid'].'.php','<?php $ckg=0;?>');
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


$htm = HOME_PATH;
xffopen($htm.'xcorn_run.php?uid='.$_REQUEST['uid']);
//@include_once( 'xcorn_run.php' );
die('Corn_Run_'.$_REQUEST['uid'].' 完成重启！');












}else{

xpp('restart_run',TIME_ALL);

$dirx='';
file_put_contents($dirx.'config_run.php','<?php $kg=0;?>');
$xfile=null;


$xx=sendzip('run','log/run/');
$xfile=$xx;

$date='Corn_Run Of '.HF_CRON.' 服务于'.date('y-m-d h:i:s').'重启！'.'
控制面板: '.dirname(dirname(HOME_PATH));
sendmsg($date);
sendmail($xfile,'Corn_Run Of '.HF_CRON.' 服务重启',$date);
sleep(TIME_ALL);


if(file_exists($xx)){
unlink($xx);
}

$htm = HOME_PATH;
xffopen($htm.'xcorn_run.php');
//@include_once( 'xcorn_run.php' );
die('Corn_Run 完成重启！');


}
?>