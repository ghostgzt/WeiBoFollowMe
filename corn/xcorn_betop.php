<?php
ignore_user_abort(true);//设置与客户机断开是否会终止脚本的执行。
set_time_limit(0); //设置脚本超时时间，为0时不受时间限制
include_once( '../config.php' );
include_once( 'hfpd_api.php' );
include_once( '../lib_sql.php' );

$dirx='';
$htm = HOME_PATH;



if(isset($_REQUEST['uid']))//执行一个
{


$rf=readsql(array('uid'=>$_REQUEST['uid']));
if($rf){
include( 'config/config_betop_'.$_REQUEST['uid'].'.php' );
if(!$ckg){
  file_put_contents('config_betop.php','<?php $kg=1;?>');
  file_put_contents('config/config_betop_'.$_REQUEST['uid'].'.php','<?php $ckg=1;?>');
$uid=$rf['uid'];
$sid=$rf['sid'];
$vfy=time();file_put_contents('start_betop_'.$uid.'.ini',$vfy);
ffopen($htm.$dirx.'corn_betop.php?uid='.$uid.'&sid='.$sid.'&verify='.$vfy);

die('Corn_Betop_'.$_REQUEST['uid'].' 设置完成！');
}else{
ffopen($htm.$dirx.'restart_betop.php?uid='.$_REQUEST['uid']);
die('Corn_Betop_'.$_REQUEST['uid'].' 重启动！');
}
}else{
die('Corn_Betop_'.$_REQUEST['uid'].' 设置失败！');
}




}else{

pp('xcorn_betop',TIME_ALL);

$rf=readrow();
if($rf){
include( 'config_betop.php' );
if(!$kg){
  file_put_contents('config_betop.php','<?php $kg=1;?>');
for ($i=0;$i<count($rf);$i++){
$uid=$rf[$i]['uid'];
$sid=$rf[$i]['sid'];
$vfy=time();file_put_contents('start_betop_'.$uid.'.ini',$vfy);
ffopen($htm.$dirx.'corn_betop.php?uid='.$uid.'&sid='.$sid.'&verify='.$vfy);
sleep(TIME_ALL/3);
}
die('Corn_Betop 设置完成！');
}else{
ffopen($htm.$dirx.'restart_betop.php');
die('Corn_Betop 重启动！');
}
}else{
die('Corn_Betop 设置失败！');
}






}
?>