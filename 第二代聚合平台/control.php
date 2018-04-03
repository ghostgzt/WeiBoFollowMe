<?php
ignore_user_abort(true);
set_time_limit(0);
@include_once('api_list.php');
@define(HF_CRON,'corn');
@include_once('lib_tools.php');
//xpp('control',60,1);
$htm = "http://".$_SERVER["HTTP_HOST"].str_replace('//','/',str_replace($_SERVER['DOCUMENT_ROOT'],'',str_replace('/mnt','',str_replace('\\','/',getcwd())).'/'));
if($_GET['type']=='run'){$tname='xcorn';xpp('control-xcorn',60,1);}
if($_GET['type']=='restart'){$tname='restart';xpp('control-restart',60,1);}
if($_GET['type']=='close'){$tname='close';xpp('control-close',60,1);}
if($_GET['type']=='iproxy'){$tname='iproxy';xpp('control-iproxy',60,1);}
if($_GET['type']=='signin'){$tname='signin';xpp('control-signin',60,1);}
if($_GET['type']=='check'){$tname='check';xpp('control-check',60,1);}
if($tname){
if(!file_exists('xruns')){mkdir('xruns');}
if($tname=='check'){
xffopen($htm.'checkstatus.php');
die('All '.$tname.' Setted!');
}
for($i=0;$i<count($api_list);$i++){
$ssr=$api_list[$i];
if(!file_exists(dirname(__FILE__).'/'.'xruns/'.$ssr['corn'].'/config.php')){
if(!file_exists('xruns/'.$ssr['corn'])){xCopy("corn",'xruns/'.$ssr['corn'],0);}
}
if($tname=='signin'){
xffopen($htm.'xruns/'.$ssr['corn'].'/'.$tname.'.php');
}else{
if($_GET['check']){$cck='&check=1';}else{$cck='';}
xffopen($htm.$tname.'.php?corn='.$ssr['corn'].$cck);
}
sleep(60);
}
die('All '.$tname.' Setted!');
}else{
die('Nothing!');
}
?>