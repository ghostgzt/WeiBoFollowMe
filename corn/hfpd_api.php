<?php
error_reporting(0);
include( '../config.php' );
function pp($lx,$tt){
if(!is_dir('pp')){mkdir('pp');}
try 
{
$t=file_get_contents('pp/time_'.$lx.'.pp');
} 
catch(Exception $e) 
{}
if(time()<($t+$tt)){
die('进行中！ 请'.intval($t+$tt-time()).'秒后刷新');}
file_put_contents('pp/time_'.$lx.'.pp',time());
}
function sleeps($uid,$type,$ks,$time){
$tt=time()+$time;
while(true){
include( 'config_'.$type.'.php' );
if(is_file('config/config_'.$type.'_'.$uid.'.php')){
include( 'config/config_'.$type.'_'.$uid.'.php' );
}else{
try 
{
file_put_contents($ks,'
'.date('y-m-d h:i:s').' 终止',FILE_APPEND);
} 
catch(Exception $e) 
{}
die('终止');
}
if(!$kg||!$ckg){

try 
{
file_put_contents($ks,'
'.date('y-m-d h:i:s').' 终止',FILE_APPEND);
} 
catch(Exception $e) 
{}
die('终止');

}
try 
{
$ss='Waiting...';
file_put_contents('json/corn_'.$type.'_'.$uid.'.json','{"time":"'.time().'","err":"'.$ss.'"}');
} 
catch(Exception $e) 
{}
if(time()>$tt){
return true;
}
sleep(TIME_ALL/1.5);
}
}
function ffopen($url){
       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL,$url);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($ch, CURLOPT_TIMEOUT, 2);   //只需要设置一个秒的数量就可以
       $output = curl_exec($ch);
       curl_close($ch);
	   return $output;
}
function getres($url,$reff,$post_data,$phpsessid){
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
if(HF_PROXY&&HF_PROXY!='HF_PROXY'){
if(!file_exists('config_proxy.php')){file_put_contents('config_proxy.php','<?php $i_proxy=0; ?>');}
include('config_proxy.php');
if($i_proxy){
curl_setopt($ch, CURLOPT_PROXY, $i_proxy);
}
}
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)"); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:8.8.8.8', 'CLIENT-IP:8.8.8.8'));
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
		if (!empty($phpsessid))
		{
        curl_setopt($ch,CURLOPT_COOKIE,'PHPSESSID='.$phpsessid);
		}
//POST数据！
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_REFERER, $reff);
//把post的变量加上
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

$output = curl_exec($ch);
curl_close($ch);
//返回的结果
return $output;
}
function cleandz($gz,$html){
preg_match_all($gz, $html, $match);
$i=0;
$html='{"ii":[';
for($i=0;$i<count($match[1]);$i++){
$html=$html.',"'.$match[1][$i].'"';
}
$html=$html.'],"num":'.$i.'}';
return str_replace('{"ii":[,','{"ii":[',$html);
}
function daysay($sid){
$post_data=null;
getres(HF_HOST.'index.php?m=ex&visit=1',HF_HOST,$post_data,$sid);
$r=json_decode(getres(HF_HOST.'index.php?m=ex&a=prize',HF_HOST,$post_data,$sid),true);
if($r['status']){
return true;
}else{
return false;
}
}
function respost($id,$sid){
$post_data="id=".$id."&comment_id=-1";

return getres(HF_HOST.'index.php?m=repost&a=action',HF_HOST,$post_data,$sid);
}
function load_page($type,$page,$sid){
$post_data=null;

return getres(HF_HOST.'index.php?m=repost&a=load&zone='.$type.'&page='.$page,HF_HOST,$post_data,$sid);
}
function getgz($sid){
$post_data=null;
return cleandz('/hf\.mall\.pian\((.*?)\)/is',getres(HF_HOST.'index.php?m=mall&a=pian',HF_HOST,$post_data,$sid));
}
//var_dump(json_decode(getgz('63073aa051f7a9b9e24d7bdbcc921cd2'),true)['ii'][0]);
function unfollowone($sid,$cursor){
$post_data='fan_num=1000000&cursor='.$cursor;
return getres(HF_HOST.'index.php?m=follow&a=pian',HF_HOST,$post_data,$sid);
}
function unfollow($uid,$sid){
$z=array();
$s0=json_decode(getgz($sid),true);
$s1=$s0['ii'][0];
while($s1>9){
$s1-=10;
require ('config/config_unfollow_'.$uid.'.php');
if(!$ckg){die('計劃重啓！');}
$r=json_decode(unfollowone($sid,$s1),true);
$r0=$r['people'];

for($i=0;$i<count($r0);$i++){
$r1=$r0[$i];
if($r1[1]==3){
$z[count($z)]=$r1[0];
}
}
}
file_put_contents('log/unfollow/'.'u_'.$uid.'.txt',date('Y-m-d H:i:s').' '.json_encode($z).'
',FILE_APPEND);
return $z;
}
function be_top($action,$uid,$ks,$sid){
//$scc=$action;

while(true){
include( 'config_betop.php' );

if(is_file('config/config_betop_'.$uid.'.php')){
include( 'config/config_betop_'.$uid.'.php' );
}else{
try 
{
file_put_contents($ks,'
'.date('y-m-d h:i:s').' 终止',FILE_APPEND);
} 
catch(Exception $e) 
{}
die('终止');
}
if(!$kg||!$ckg){
try 
{
file_put_contents($ks,'
'.date('y-m-d h:i:s').' 终止',FILE_APPEND);
} 
catch(Exception $e) 
{}
die('终止');
}
//$post_data="action=".$scc;
$post_data="hp_each=16";
    echo str_repeat(" ",1024);//写满IE有默认的1k buffer
  ob_flush();//将缓存中的数据压入队列
    flush();//输出缓存队列中的数据
//$sx= getres(HF_HOST.'index.php?m=mall&a=ajaxBeTop',HF_HOST,$post_data,$sid);
$sx= getres(HF_HOST.'index.php?m=mall&a=topnew',HF_HOST,$post_data,$sid);
echo $sx.' ';
try 
{
//$ss=json_decode($sx,true);
file_put_contents('json/corn_betop_'.$uid.'.json','{"time":"'.time().'","err":"None"}');
} 
catch(Exception $e) 
{}
try 
{
file_put_contents($ks,'
'.date('y-m-d h:i:s').' 操作完成！',FILE_APPEND);
} 
catch(Exception $e) 
{}
/*if($scc=='0'){
$scc='1';
}else{
$scc='0';*/
sleeps($uid,'betop',$ks,TIME_BETOP);
//}
}
}
function army($uid,$ks,$sid){

while(true){
include( 'config_army.php' );

if(is_file('config/config_army_'.$uid.'.php')){
include( 'config/config_army_'.$uid.'.php' );
}else{
try 
{
file_put_contents($ks,'
'.date('y-m-d h:i:s').' 终止',FILE_APPEND);
} 
catch(Exception $e) 
{}
die('终止');
}
if(!$kg||!$ckg){
try 
{
file_put_contents($ks,'
'.date('y-m-d h:i:s').' 终止',FILE_APPEND);
} 
catch(Exception $e) 
{}
die('终止');}
$post_data=null;
    echo str_repeat(" ",1024);//写满IE有默认的1k buffer
  ob_flush();//将缓存中的数据压入队列
    flush();//输出缓存队列中的数据
$sx= getres(HF_HOST.'index.php?m=king&a=joinArmy',HF_HOST,$post_data,$sid);
echo $sx.' ';
try 
{
$ss=json_decode($sx,true);
file_put_contents('json/corn_army_'.$uid.'.json','{"time":"'.time().'","type":"'.$ss['type'].'"}');
} 
catch(Exception $e) 
{}
try 
{
file_put_contents($ks,'
'.date('y-m-d h:i:s').' '.$sx,FILE_APPEND);
} 
catch(Exception $e) 
{}
sleeps($uid,'army',$ks,TIME_ARMY);
}
}
//echo cleandz('/repost\(this\,\'(.*?)\'\)\;/is',load_page('n','1','3145790273'));
function run($type,$page,$uid,$ks,$sid){
$tt=0;
while(true){

if(time()>$tt){ //if tt
$ees=0;
$html=cleandz('/hf\.repost\.action\(\'(.*?)\'/is',load_page($type,$page,$sid));
$j=json_decode($html,true);
$n=$j['num'];
for($x=0;$x<$n;$x++){
include( 'config_run.php' );
if(is_file('config/config_run_'.$uid.'.php')){
include( 'config/config_run_'.$uid.'.php' );
}else{
try 
{
file_put_contents($ks,'
'.date('y-m-d h:i:s').' 终止',FILE_APPEND);
} 
catch(Exception $e) 
{}
die('终止');
}
if(!$kg||!$ckg){

try 
{
file_put_contents($ks,'
'.date('y-m-d h:i:s').' 终止',FILE_APPEND);
} 
catch(Exception $e) 
{}
die('终止');

}
    echo str_repeat(" ",1024);//写满IE有默认的1k buffer
  ob_flush();//将缓存中的数据压入队列

    flush();//输出缓存队列中的数据
	$sx=respost($j['ii'][$x],$sid);
echo $sx.' ';
$ees=0;
try 
{
$ss=json_decode($sx,true);
file_put_contents('json/corn_run_'.$uid.'.json','{"time":"'.time().'","msg":"'.$ss['msg'].'"}');
//file_put_contents('1111.txt',strlen($ss['error']));

if(strlen($ss['msg'])==40){$ees=1;}else{$ees=0;}
} 
catch(Exception $e) 
{}
try 
{
file_put_contents($ks,'
'.date('y-m-d h:i:s').' '.($x+1).'/'.$n.' '.$j['ii'][$x].' '.$sx,FILE_APPEND);
} 
catch(Exception $e) 
{}
//if tt
if($ees){
try 
{
file_put_contents($ks,'
'.date('y-m-d h:i:s').' 待机'.' 一小时后继续... ',FILE_APPEND);
} 
catch(Exception $e) 
{}
$tt=time()+3600;
$page=1;
break 1;
}
//if tt
if($ss['status']==1){
sleeps($uid,'run',$ks,TIME_RUN);
}else{
sleeps($uid,'run',$ks,TIME_ALL);
}
}
if(!$ees){
if (intval($page)>1){$page=intval($page)-1;}else{$page=RUN_PAGE;}
//run($type,$page,$weibo_uid,$token,$uid,$f_count,$ks);


try 
{
file_put_contents($ks,'
'.date('y-m-d h:i:s').' 翻页...'.' 下页'.$page.'页'.' ',FILE_APPEND);
} 
catch(Exception $e) 
{}


if(!$j['num']){
$sx='获取数据失败！';
try 
{
file_put_contents('json/corn_run_'.$uid.'.json','{"time":"'.time().'","msg":"'.$sx.'"}');
} 
catch(Exception $e) 
{}
include( 'config_run.php' );
include( 'config/config_run_'.$uid.'.php' );
if(!$kg||!$ckg){

try 
{
file_put_contents($ks,'
'.date('y-m-d h:i:s').' 终止',FILE_APPEND);
} 
catch(Exception $e) 
{}
die('终止');

}
sleeps($uid,'run',$ks,TIME_RUN);
}
}

}else{

include( 'config_run.php' );
if(is_file('config/config_run_'.$uid.'.php')){
include( 'config/config_run_'.$uid.'.php' );
}else{
try 
{
file_put_contents($ks,'
'.date('y-m-d h:i:s').' 终止',FILE_APPEND);
} 
catch(Exception $e) 
{}
die('终止');
}
if(!$kg||!$ckg){

try 
{
file_put_contents($ks,'
'.date('y-m-d h:i:s').' 终止',FILE_APPEND);
} 
catch(Exception $e) 
{}
die('终止');

}

try 
{

file_put_contents('json/corn_run_'.$uid.'.json','{"time":"'.time().'","msg":"Waiting..."}');
} 
catch(Exception $e) 
{}
sleeps($uid,'run',$ks,TIME_RUN);
}//if tt

}
}
?>