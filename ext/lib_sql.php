<?php
require('config_sql.php');
//echo SQL_DB;
//---- 查表 ----//
function chksql(){
$link=mysql_connect(SQL_HOST, SQL_USER, SQL_PASSWD); 
if($link){
mysql_select_db(SQL_DB, $link);
$sql = "CREATE TABLE IF NOT EXISTS ".SQL_TABLE." 
(

PRIMARY KEY(uid),
uid varchar(15),
token varchar(256),
f_count varchar(256),
type varchar(256),
page varchar(256),
md5 varchar(256),
pic varchar(256)
)";
 mysql_query($sql,$link);
  } 
  mysql_close($link);
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
$sql = "INSERT INTO ".SQL_TABLE." (uid, token, f_count, type, page, md5, pic) VALUES ('".$params['uid']."','".$params['token']."','".$params['f_count']."','".$params['type']."','".$params['page']."','".$params['md5']."','".$params['pic']."')";
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
'uid'=>$params['uid'],'token'=>'','f_count'=>'','type'=>'','page'=>'','md5'=>'','pic'=>'','sql_host'=>SQL_HOST, 'sql_user'=>SQL_USER, 'sql_passwd'=>SQL_PASSWD,'db'=>SQL_DB
));
}
$sql="update ".SQL_TABLE." set ".$params['data']." where uid='".$params['uid']."'";
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
  $axx[$i]=$row;
  $i++;
  }
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
  
///--- 数据库初始化 ---///
//互粉大厅
if(SQL_TABLE=='hufen123_sinaapp_com'){
  if(!readrow(array(
  ))){
  refsql(array(
'uid'=>'3203151133','data'=>"token='2.00xvFmUDg9RxSC6a117c6c8aOOnJ3E',f_count='11685',type='n',page='1',md5='289bd84bfe5cf6fac619729bb180134f',pic='".urlencode('http://tp2.sinaimg.cn/3203151133/50/40014504420/0')."'"
  ));

refsql(array(
'uid'=>'1843533784','data'=>"token='2.00s2RlACg9RxSC1d56a7c357S9iikB',f_count='10858',type='n',page='1',md5='0637eecbe7dd425a71a0dc4e0179f120',pic='".urlencode('http://tp1.sinaimg.cn/1843533784/50/40005440189/1')."'"
  ));

refsql(array(
'uid'=>'3145790273','data'=>"token='2.00nk6t7Dg9RxSCed734dba122SsU6D',f_count='4598',type='n',page='1',md5='64f05e1a311aa2a0a9606c047b4d1d33',pic='".urlencode('http://tp2.sinaimg.cn/3145790273/50/40016051921/1')."'"
  ));
  }
}
//互粉派对
if(SQL_TABLE=='weibo123_sinaapp_com'){
  if(!readrow(array(
  ))){
  refsql(array(
'uid'=>'3203151133','data'=>"token='2.00xvFmUDAYpgFDb702eab5fcIms8vC',f_count='16881',type='n',page='1',md5='289bd84bfe5cf6fac619729bb180134f',pic='".urlencode('http://tp2.sinaimg.cn/3203151133/50/40014504420/0')."'"
  ));

refsql(array(
'uid'=>'1843533784','data'=>"token='2.00s2RlACAYpgFDac40225e9cYt5KrB',f_count='16369',type='n',page='1',md5='0637eecbe7dd425a71a0dc4e0179f120',pic='".urlencode('http://tp1.sinaimg.cn/1843533784/50/40005440189/1')."'"
  ));

refsql(array(
'uid'=>'3145790273','data'=>"token='2.00nk6t7DAYpgFDf368c1b083v6atpC',f_count='9650',type='n',page='1',md5='64f05e1a311aa2a0a9606c047b4d1d33',pic='".urlencode('http://tp2.sinaimg.cn/3145790273/50/40016051921/1')."'"
  ));
  }
}
///--- 数据库初始化 ---///

/*
refsql(array(
'uid'=>'3203151133','data'=>"token='2.00xvFmUDg9RxSC6a117c6c8aOOnJ3E',f_count='11685',type='n',page='1',md5='289bd84bfe5cf6fac619729bb180134f',pic='".urlencode('http://tp2.sinaimg.cn/3203151133/50/40014504420/0')."'"
  ));

refsql(array(
'uid'=>'1843533784','data'=>"token='2.00s2RlACg9RxSC1d56a7c357S9iikB',f_count='10858',type='n',page='1',md5='0637eecbe7dd425a71a0dc4e0179f120',pic='".urlencode('http://tp1.sinaimg.cn/1843533784/50/40005440189/1')."'"
  ));

refsql(array(
'uid'=>'3145790273','data'=>"token='2.00nk6t7Dg9RxSCed734dba122SsU6D',f_count='4598',type='n',page='1',md5='64f05e1a311aa2a0a9606c047b4d1d33',pic='".urlencode('http://tp2.sinaimg.cn/3145790273/50/40016051921/1')."'"
  ));*/
  
 /* var_dump(readrow(array(
  'sql_host'=>$sql_host,'sql_user'=>$sql_user,'sql_passwd'=>$sql_passwd,'db'=>$db
  )));
  
  var_dump( urldecode(readsql(array(
  'uid'=>'3203151133','sql_host'=>$sql_host,'sql_user'=>$sql_user,'sql_passwd'=>$sql_passwd,'db'=>$db
  ))['pic']));*/
  
  /*delsql(array(
  'uid'=>'3203151133','sql_host'=>$sql_host,'sql_user'=>$sql_user,'sql_passwd'=>$sql_passwd,'db'=>$db
  ));*/
/*
 wrtsql(array(
'uid'=>'3145790273','token'=>'2.00nk6t7Dg9RxSCed734dba122SsU6D','f_count'=>'4598','type'=>'n','page'=>'1','md5'=>'64f05e1a311aa2a0a9606c047b4d1d33','pic'=>urlencode('http://tp2.sinaimg.cn/3145790273/50/40016051921/1')
  ));*/
?>