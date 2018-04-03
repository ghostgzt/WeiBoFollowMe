<?php
include_once( 'config.php' );
include_once( 'lib_tools.php' );
ignore_user_abort(true);//设置与客户机断开是否会终止脚本的执行。
set_time_limit(0); //设置脚本超时时间，为0时不受时间限制
xpp('restart',TIME_ALL,1);
$dirx='corn/';

file_put_contents($dirx.'config_run.php','<?php $kg=0;?>');



deldir('corn/config');creatdir('corn/config');
deldir('corn/json');creatdir('corn/json');



$xfile=null;

$xx=sendzip('all','corn/log/');
$xfile=$xx;

$aad='
控制面板: '.HOME_PATH.'
开启所有服务: '.HOME_PATH.'xcorn.php'.'
重启所有服务: '.HOME_PATH.'restart.php'.' '.'
关闭所有服务: '.HOME_PATH.'close.php';
$date='所有服务服务于'.date('y-m-d h:i:s').'重启！'.$aad;
sendmsg($date);
sendmail($xfile,'所有服务服务重启',$date);
sleep(TIME_ALL);

if(file_exists($xx)){
unlink($xx);
}

include_once( 'xcorn.php' );
die('所有服务完成重启！');
?>