<?php
include( '../config.php' );
define(SQL_TABLE,str_replace('.','_',str_replace('/','',str_replace('http://','',HF_HOST))));
$api_list=array();
include('../api_list.php');
for($i=0;$i<count($api_list);$i++){
if(SQL_TABLE==$api_list[$i]['table']){include($api_list[$i]['api']);}
}
?>