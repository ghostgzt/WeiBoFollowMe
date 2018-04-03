<?php
ignore_user_abort(true);//设置与客户机断开是否会终止脚本的执行。
set_time_limit(0); //设置脚本超时时间，为0时不受时间限制
include( 'apis.php' );
include_once( '../lib_sql.php' );








if(isset($_REQUEST['uid']))//执行一个
{
//die( $_REQUEST['uid']);

$rf=readsql(array('uid'=>$_REQUEST['uid']));
if($rf){

$uid=$rf['uid'];
$sid=$rf['sid'];
getscorebackall($sid);
$sd=signin($sid);
$ss=json_decode($sd,true);
if($ss["info"]){
echo $_REQUEST['uid'].' '.$ss["info"].'<br/>';

}else{
echo $_REQUEST['uid'].' 完成签到！<br/>';

}



}else{
echo $_REQUEST['uid'].' 签到失败！<br/>';
}




}else{


pp('signin',60);


$rf=readrow();
if($rf){

for ($i=0;$i<count($rf);$i++){
$uid=$rf[$i]['uid'];
$sid=$rf[$i]['sid'];
getscorebackall($sid);
$sd=signin($sid);
$ss=json_decode($sd,true);
if($ss["info"]){
echo $uid.' '.$ss["info"].'<br/>';

}else{
echo $uid.' 完成签到！<br/>';

}
}
echo '所有签到完成！<br/>';

}else{
echo '所有签到失败！<br/>';
}






}


if(isset($_REQUEST['uid']))//执行一个
{
//die( $_REQUEST['uid']);

$rf=readsql(array('uid'=>$_REQUEST['uid']));
if($rf){

$uid=$rf['uid'];
$sid=$rf['sid'];
$text='What a nice day! '.rand(0,99);


$sd=share($sid,$text);
active($sid,getid($sid),'0');
$sw=active($sid,getid($sid),'1');

$ss=json_decode($sd,true);
if($ss["info"]){
echo $_REQUEST['uid'].' '.$ss["info"].'<br/>';

}else{
echo $_REQUEST['uid'].' 完成分享！<br/>';

}
$ss=json_decode($sw,true);
if($ss["info"]){
echo $_REQUEST['uid'].' '.$ss["info"].'<br/>';

}else{
echo($_REQUEST['uid'].' 完成开启！'.'<br/>');

}


}else{
echo($_REQUEST['uid'].' 分享失败！');
}




}else{





$rf=readrow();
if($rf){

for ($i=0;$i<count($rf);$i++){
$uid=$rf[$i]['uid'];
$sid=$rf[$i]['sid'];
$text='What a nice day! '.rand(0,99);
$sd=share($sid,$text);
active($sid,getid($sid),'0');
$sw=active($sid,getid($sid),'1');

$ss=json_decode($sd,true);
if($ss["info"]){
echo $uid.' '.$ss["info"].'<br/>';

}else{
echo $uid.' 完成分享！<br/>';

}
$ss=json_decode($sw,true);
if($ss["info"]){
echo $uid.' '.$ss["info"].'<br/>';

}else{
echo($uid.' 完成开启！'.'<br/>');

}

}
die('所有分享完成！');

}else{
die('所有分享失败！');
}






}






?>