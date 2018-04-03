﻿<?php
ignore_user_abort(true);
set_time_limit(0);
if(!$_GET['corn']){
@include_once('api_list.php');
for($i=0;$i<count($api_list);$i++){
$ssr=$api_list[$i];
$corn=$ssr['corn'];
echo $corn.': ';
echo file_get_contents("http://".$_SERVER["HTTP_HOST"].str_replace('//','/',str_replace($_SERVER['DOCUMENT_ROOT'],'',str_replace('/mnt','',str_replace('\\','/',getcwd())).'/')).'checkstatus.php?corn='.$corn).'<br>';
}
die();
}

@include( dirname(__FILE__).'/loadcorn.php' );
@include_once( 'lib_tools.php' );
xpp('checkstatus',60,1);

function reandwaring($uid,$type,$intr){
$dirx=HF_CRON.'/';
$htm = HOME_PATH;
$es=checkjson($uid,$type);
if ($es['time']){
if(time()<(1.5*(intval($intr))+intval($es['time'])))
{return array($uid=>true);}else{
$mm=HF_CRON.'/config_'.$type.'.php';
@include($mm);
if ($kg){
//return $htm.$dirx.'restart_'.$type.'.php?uid='.$uid;
xffopen($htm.$dirx.'restart_'.$type.'.php?uid='.$uid);
$date=$uid.'的'.$type.' Of '.getcorn(HF_CRON).' 服务因异常于'.date('y-m-d h:i:s').'重启！
('.$es['err'].')
控制面板: '.HOME_PATH;
sendmsg($date);
sendmail(null,'服务因异常重启',$date);
}else{return array($uid=>false);}
}
}else{return array($uid=>false);}
}

if(isset($_REQUEST['uid']))//执行一个
{
$uid=$_REQUEST['uid'];
echo json_encode(reandwaring($uid,'run',TIME_ALL));
}else{



@include_once( 'lib_sql.php' );

$rf=readrow();
if($rf){

for ($i=0;$i<count($rf);$i++){
$uid=$rf[$i]['uid'];
echo json_encode(reandwaring($uid,'run',TIME_ALL));
}
}


}
?>