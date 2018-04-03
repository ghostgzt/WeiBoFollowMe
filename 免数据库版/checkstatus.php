<?php
include_once( 'lib_tools.php' );
include_once( 'config.php' );

xpp('checkstatus',60,1);

function reandwaring($uid,$type,$intr){
$dirx='corn/';
$htm = HOME_PATH;
$es=checkjson($uid,$type);
if ($es['time']){
if(time()<(1.5*(intval($intr))+intval($es['time'])))
{return true;}else{
$mm='corn/config_'.$type.'.php';
include($mm);
if ($kg){
//return $htm.$dirx.'restart_'.$type.'.php?uid='.$uid;
xffopen($htm.$dirx.'restart_'.$type.'.php?uid='.$uid);
$date=$uid.'的'.$type.'服务因异常于'.date('y-m-d h:i:s').'重启！
('.$es['err'].')
控制面板: '.HOME_PATH;
sendmsg($date);
sendmail(null,'服务因异常重启',$date);
}else{return false;}
}
}else{return false;}
}

if(isset($_REQUEST['uid']))//执行一个
{
$uid=$_REQUEST['uid'];
var_dump(reandwaring($uid,'run',TIME_ALL));
}else{

include_once( 'lib_sql.php' );

$rf=readrow();
if($rf){

for ($i=0;$i<count($rf);$i++){
$uid=$rf[$i]['uid'];
var_dump(reandwaring($uid,'run',TIME_ALL));
}
}

}
?>