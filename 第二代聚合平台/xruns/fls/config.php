<?php
$ydir=dirname(dirname(dirname(__FILE__)));
$s=( split('\/',str_replace('\\','/',dirname(__FILE__))));
@include_once($ydir.'/api_list.php');for($i=0;$i<count($api_list);$i++){$ssr=$api_list[$i];if(($s[count($s)-1])==$ssr['corn']){$hf_host=$ssr['url'];}}
$ccon=$ydir.'/sql/'.str_replace('.','_',str_replace('/','',str_replace('http://','',$hf_host))).'.inc';
if(!file_exists($ccon)){
@mkdir($ydir.'/sql');
@file_put_contents($ccon,file_get_contents($ydir.'/config_default.php'));
}
//--- web ---//
define(HOME_PATH,"http://".$_SERVER["HTTP_HOST"].str_replace('//','/',str_replace($_SERVER['DOCUMENT_ROOT'],'',str_replace('/mnt','',str_replace('\\','/',getcwd())).'/')));
//--- time ---//
define(TIME_ALL,60);
//--- time_zone ---//
date_default_timezone_set("PRC");
//--- hf_host ---//
define(HF_HOST,$hf_host);
//--- load_config ---//
include_once($ccon);
?>