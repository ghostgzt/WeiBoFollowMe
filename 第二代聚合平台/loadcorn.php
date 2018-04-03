<?php
if($_REQUEST['corn']){
define(HF_CRON,'xruns/'.$_REQUEST['corn']);
}else{
include_once('choose.inc');
die();
}
if(!file_exists('xruns')){mkdir('xruns');}
if(!file_exists(dirname(__FILE__).'/'.HF_CRON.'/config.php')){
include_once( 'lib_tools.php' );
if(!file_exists(HF_CRON)){xCopy("corn",HF_CRON,0);}
}
include( dirname(__FILE__).'/'.HF_CRON.'/config.php' );
?>