<?php
ignore_user_abort(true);//设置与客户机断开是否会终止脚本的执行。
set_time_limit(0); //设置脚本超时时间，为0时不受时间限制
include( 'config.php' );
include_once( 'lib_tools.php' );
xpp('xcorn',TIME_ALL,1);
$dirx='corn/';
$htm = HOME_PATH;
//echo $htm;
deldir('corn/config');creatdir('corn/config');
deldir('corn/json');creatdir('corn/json');
deldir('corn/log');creatdir('corn/log');creatdir('corn/log/army');creatdir('corn/log/run');creatdir('corn/log/betop');
xffopen($htm.$dirx.'iproxy.php');
xffopen($htm.$dirx.'daysay.php');
xffopen($htm.$dirx.'xcorn_run.php');
xffopen($htm.$dirx.'xcorn_betop.php');
xffopen($htm.$dirx.'xcorn_army.php');
//$date='所有服务服务于'.date('y-m-d h:i:s').'开启！';
//sendmsg($date);
//sendmail($xfile,'所有服务服务开启',$date);
die('Corn设置完成！');
?>