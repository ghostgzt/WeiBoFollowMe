<?php
@include( dirname(__FILE__).'/loadcorn.php' );
@include_once( 'lib_tools.php' );
ignore_user_abort(true);
set_time_limit(0);
xpp('restart',TIME_ALL,1);
$dirx=HF_CRON.'/';

file_put_contents($dirx.'config_run.php','<?php $kg=0;?>');



deldir(HF_CRON.'/config');creatdir(HF_CRON.'/config');
deldir(HF_CRON.'/json');creatdir(HF_CRON.'/json');



$xfile=null;

$xx=sendzip('all',HF_CRON.'/log/');
$xfile=$xx;

$aad='
控制面板: '.HOME_PATH.'
开启所有服务: '.HOME_PATH.'xcorn.php?corn='.getcorn(HF_CRON).'
重启所有服务: '.HOME_PATH.'restart.php?corn='.getcorn(HF_CRON).' '.'
关闭所有服务: '.HOME_PATH.'close.php?corn='.getcorn(HF_CRON);
$date='所有服务服务于'.date('y-m-d h:i:s').'重启！'.$aad;
sendmsg($date);
sendmail($xfile,'所有服务服务重启',$date);
sleep(TIME_ALL);

if(file_exists($xx)){
unlink($xx);
}

//@include_once( 'xcorn.php' );
xffopen(HOME_PATH.'xcorn.php?corn='.getcorn(HF_CRON));
die('所有服务完成重启！');
?>