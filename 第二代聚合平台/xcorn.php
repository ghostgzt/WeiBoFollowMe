<?php
ignore_user_abort(true);
set_time_limit(0);
@include( dirname(__FILE__).'/loadcorn.php' );
@include_once( 'lib_tools.php' );
xpp('xcorn',TIME_ALL,1);
$dirx=HF_CRON.'/';
$htm = HOME_PATH;
//echo $htm;
deldir(HF_CRON.'/config');creatdir(HF_CRON.'/config');
deldir(HF_CRON.'/json');creatdir(HF_CRON.'/json');
deldir(HF_CRON.'/log');creatdir(HF_CRON.'/log');creatdir(HF_CRON.'/log/run');
xffopen($htm.$dirx.'signin.php');
xffopen($htm.$dirx.'xcorn_run.php');
//$date='所有服务服务于'.date('y-m-d h:i:s').'开启！';
//sendmsg($date);
//sendmail($xfile,'所有服务服务开启',$date);
die('Corn设置完成！');
?>