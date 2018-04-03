<?php
require('config_sql.php');
//echo SQL_DB;
//---- 查表 ----//
function chksql(){
$link=mysql_connect(SQL_HOST, SQL_USER, SQL_PASSWD); 
if($link){

$s=mysql_select_db(SQL_DB, $link);
if(!$s){
@mysql_query("CREATE DATABASE ".SQL_DB,$link);
mysql_select_db(SQL_DB, $link);
}

$sql = "CREATE TABLE IF NOT EXISTS ".SQL_TABLE." 
(

PRIMARY KEY(uid),
uid varchar(15),
sid varchar(256),
type varchar(256),
ints varchar(256),
page varchar(256),
pic varchar(256)
)";
 mysql_query($sql,$link);
  } 
  mysql_close($link);
  //var_dump($sql);
//die();
}
//---- 读表 ----//
function readsql($params = array()){

$link=mysql_connect(SQL_HOST, SQL_USER, SQL_PASSWD); 
if($link){
try{
mysql_select_db(SQL_DB, $link);
$result = mysql_query("SELECT * from ".SQL_TABLE." WHERE uid='".$params['uid']."'",$link);

$rxx =  mysql_fetch_array($result);

  // mysql_close($link);
return $rxx;
 } catch (Exception $e) {
 mysql_close($link);
  return false;
  }
  }else{
     mysql_close($link);
return false;
  }
 
}
//---- 插入 ----//
function wrtsql($params = array()){
$link=mysql_connect(SQL_HOST, SQL_USER, SQL_PASSWD); 
if($link){
try{
mysql_select_db(SQL_DB, $link);
$sql = "INSERT INTO ".SQL_TABLE." (uid, sid, type, ints, page,  pic) VALUES ('".$params['uid']."','".$params['sid']."','".$params['type']."','".$params['ints']."','".$params['page']."','".$params['pic']."')";
//die($sql);
//echo $sql;
$result = mysql_query($sql,$link);
    //  mysql_close($link);
      if($result){
    return true;
   }else{
   return false;
	}
	 } catch (Exception $e) {
 mysql_close($link);
  return false;
  }
}else{
     mysql_close($link);
return false;
  }
}



//---- 更新 ----//
function refsql($params = array()){
$link=mysql_connect(SQL_HOST, SQL_USER, SQL_PASSWD); 
if($link){
try{
mysql_select_db(SQL_DB, $link);
$rtt = mysql_query("SELECT * from ".SQL_TABLE." WHERE uid='".$params['uid']."'",$link);
//var_dump(mysql_fetch_array($rtt));
if (!mysql_fetch_array($rtt)){
wrtsql(array(
'uid'=>$params['uid'],'sid'=>'','type'=>'','ints'=>'','page'=>'','pic'=>'','sql_host'=>SQL_HOST, 'sql_user'=>SQL_USER, 'sql_passwd'=>SQL_PASSWD,'db'=>SQL_DB
));
}
//die(str_replace("int=","ints=",$params['data']));
$sql="update ".SQL_TABLE." set ".str_replace("int=","ints=",$params['data'])." where uid='".$params['uid']."'";
   $result=mysql_query($sql);
   //mysql_close($link);
   if($result){
    return true;
   }else{
   return false;
	}
	 } catch (Exception $e) {
 mysql_close($link);
  return false;
  }
  }else{
     mysql_close($link);
return false;
  }
  }
    //---- 读行 ----//
	function readrow(){
$link=mysql_connect(SQL_HOST, SQL_USER, SQL_PASSWD); 
if($link){
try{
mysql_select_db(SQL_DB, $link);
$result = mysql_query("SELECT * FROM ".SQL_TABLE."");
$axx=array();
$i=0;
while($row = mysql_fetch_array($result))
  {
    $row["int"]=$row["ints"];
  $axx[$i]=$row;

  $i++;
  }
  //var_dump($axx);
  return $axx;
   } catch (Exception $e) {
 mysql_close($link);
  return false;
  }
  }else{
    mysql_close($link);
return false;
  }
  }
  //---- 删除 ----//
function delsql($params = array()){
$link=mysql_connect(SQL_HOST, SQL_USER, SQL_PASSWD); 
if($link){
try{
mysql_select_db(SQL_DB, $link);
$result = mysql_query("DELETE from ".SQL_TABLE." WHERE uid='".$params['uid']."'",$link);

  // mysql_close($link);
return $rxx;
	 } catch (Exception $e) {
 mysql_close($link);
  return false;
  }
  }else{
    mysql_close($link);
return false;
  }
}
  
  chksql();
  
include("init_sql.php");
?>