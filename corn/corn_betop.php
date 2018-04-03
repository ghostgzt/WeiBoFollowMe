

<?php



ignore_user_abort(true);//设置与客户机断开是否会终止脚本的执行。
set_time_limit(0); //设置脚本超时时间，为0时不受时间限制
ob_end_clean();//清空缓存
ob_start();//开始缓冲数据
include( '../config.php' );
include( 'hfpd_api.php' );
include( '../lib_tools.php' );


if(isset($_REQUEST['uid'])){$uid=$_REQUEST['uid'];}else{die('缺少参数！');}
if(isset($_REQUEST['sid'])){$sid=$_REQUEST['sid'];}else{die('缺少参数！');}

if((file_exists('start_betop_'.$uid.'.ini')&&isset($_REQUEST['verify']))){}else{die('无效请求！');}
if($_REQUEST['verify']==file_get_contents('start_betop_'.$uid.'.ini')){
unlink('start_betop_'.$uid.'.ini');
}else{
die();
}


$action='0';



$kn='b_'.$uid.'.txt';
$kd='log/betop/';
creatdir($kd);
creatdir('config');
creatdir('json');
file_put_contents('config/config_betop_'.$uid.'.php','<?php $ckg=1;?>');
$ks=$kd.$kn;
file_put_contents($ks,$kn.' '.date('y-m-d h:i:s'));
be_top($action,$uid,$ks,$sid)

?>