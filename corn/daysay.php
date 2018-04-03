<?php
ignore_user_abort(true);//设置与客户机断开是否会终止脚本的执行。
set_time_limit(0); //设置脚本超时时间，为0时不受时间限制
include( 'hfpd_api.php' );
include_once( '../lib_sql.php' );








if(isset($_REQUEST['uid']))//执行一个
{
//die( $_REQUEST['uid']);

$rf=readsql(array('uid'=>$_REQUEST['uid']));
if($rf){
$uid=$rf['uid'];
$sid=$rf['sid'];
$sd=daysay($sid);
if($sd){
echo($uid.' 完成签到！'.'<br/>');
}else{
echo($uid.' 签到失败！'.'<br/>');
}
/*
$uid=$rf['uid'];
$text='What a nice day! '.rand(0,99);
$token=$rf['token'];

$sd=daysay($token,$uid,$text);
$ss=json_decode($sd,true);
if($ss["error"]){
die($_REQUEST['uid'].' '.$ss["error"]);

}else{
die($_REQUEST['uid'].' 完成签到！');

}
*/


}else{
die($_REQUEST['uid'].' 签到失败！');
}




}else{


pp('daysay',60);


$rf=readrow();
if($rf){

for ($i=0;$i<count($rf);$i++){
$uid=$rf[$i]['uid'];
$sid=$rf[$i]['sid'];
$sd=daysay($sid);
if($sd){
echo($uid.' 完成签到！'.'<br/>');


}else{

echo($uid.' 签到失败！'.'<br/>');
}
/*
$uid=$rf[$i]['uid'];
$text='What a nice day! '.rand(0,99);
$token=$rf[$i]['token'];
$sd=daysay($token,$uid,$text);
$ss=json_decode($sd,true);
if($ss["error"]){
echo($uid.' '.$ss["error"].'<br/>');

}else{
echo($uid.' 完成签到！'.'<br/>');

}*/
}
die('所有签到完成！');

}else{
die('所有签到失败！');
}






}









?>