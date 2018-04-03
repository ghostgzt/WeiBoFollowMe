<?php
include( 'config.php' );
define(SQL_TABLE,str_replace('.','_',str_replace('/','',str_replace('http://','',HF_HOST))));
define(SQL_DD,dirname(__FILE__).'/sql/'.SQL_TABLE.'.json');

//---- 查表 ----//
function chksql(){
$path=dirname(__FILE__).'/sql';
if(!is_dir($path))
{
mkdir($path,0777);
}
if(!file_exists(SQL_DD)){
file_put_contents(SQL_DD,json_encode(array()));
}
}

//---- 读表 ----//
function readsql($params = array()){
chksql();
$r=file_get_contents(SQL_DD);
$rr=json_decode($r,true);
return $rr[$params['uid']];
}

//---- 插入 ----//
function wrtsql($params = array()){
 chksql();
$r=file_get_contents(SQL_DD);
$aa=json_decode($r,true);
$aa[$params['uid']]=$params;
file_put_contents(SQL_DD,json_encode($aa));
return true;
}

//---- 更新 ----//
function refsql($params = array()){
chksql();
$ii=explode(',',$params['data']);
$r=file_get_contents(SQL_DD);
$aa=json_decode($r,true);
$bb=array();
$bb['uid']=$params['uid'];
for($i=0;$i<count($ii);$i++){
$i0=$ii[$i];
$i1=explode('=',$i0);
$i2=$i1[0];
$i3=$i1[1];
$bb[$i2]=str_replace("'",'',$i3);
}
$aa[$params['uid']]=$bb;
file_put_contents(SQL_DD,json_encode($aa));
return true;
}
  
//---- 读行 ----//
function readrow(){
chksql();
$r=file_get_contents(SQL_DD);
$rr=json_decode($r,true);
return array_values($rr);
}
  
//---- 删除 ----//
function delsql($params = array()){
chksql();
$r=file_get_contents(SQL_DD);
$aa=json_decode($r,true);
unset($aa[$params['uid']]);
file_put_contents(SQL_DD,json_encode($aa));
return true;
}

include('init_sql.php');
?>