<?php
error_reporting(0);
include( dirname(__FILE__).'/config.php' );
function pp($lx,$tt){
if(!is_dir('pp')){mkdir('pp');}
try 
{
$t=@file_get_contents('pp/time_'.$lx.'.pp');
} 
catch(Exception $e) 
{}
if(time()<($t+$tt)){
die('进行中！ 请'.intval($t+$tt-time()).'秒后刷新');}
file_put_contents('pp/time_'.$lx.'.pp',time());
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
function cleandz($gz,$html){
preg_match_all($gz, $html, $match);
$a=array_unique($match[1]);
$s=array();
for ($i=0;$i<count($a);$i++){
if($a[$i]){
$s[count($s)]=$a[$i];
}
}
return ($s);
}
function curlFetch($url, $phpsessid = "", $referer = "", $data = null)
    {
	        $ch = curl_init($url);
if(HF_PROXY&&HF_PROXY!='HF_PROXY'){
if(!file_exists('config_proxy.php')){file_put_contents('config_proxy.php','<?php $i_proxy=0; ?>');}
include('config_proxy.php');
if($i_proxy){
curl_setopt($ch, CURLOPT_PROXY, $i_proxy);
}
}
        curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)"); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:8.8.8.8', 'CLIENT-IP:8.8.8.8'));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 返回字符串，而非直接输出
        curl_setopt($ch, CURLOPT_HEADER, false);   // 不返回header部分
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);   // 设置socket连接超时时间
        if (!empty($referer))
        {
            curl_setopt($ch, CURLOPT_REFERER, $referer);   // 设置引用网址
        }
		if (!empty($phpsessid))
		{
        curl_setopt($ch,CURLOPT_COOKIE,'PHPSESSID='.$phpsessid);
		}
        if (is_null($data))
        {
            // GET
        }
        else if (is_string($data))
        {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            // POST
        }
        else if (is_array($data))
        {
            // POST
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        //set_time_limit(120); // 设置自己服务器超时时间
        $str = curl_exec($ch);
        curl_close($ch);
        return $str;
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
}else{
sleep(TIME_ALL/1.5);
}
}
}
function getid($sid,$nw=null)
{
if(!$nw){
return false;
}else{
$r= curlFetch(HF_HOST.'earn_score',$sid,HF_HOST,null);
$r1=explode('|',str_replace('",onpunchHandle','|',str_replace('punch/','|',$r)));
return $r1[1];
}
}
function gettask($page,$sid)
{
return curlFetch(HF_HOST.'earn_jump/post/0/0/0/0/0/'.(($page-1)*5),$sid,HF_HOST,null);
}
//echo gettask(1,'c5c676cdc331f7ce20443dd9942f819e');//http://hutuidaren.sinaapp.com/reward_website/
function relay($sid,$tid,$msg)
{
return curlFetch(HF_HOST.'task_execute_new/2/',$sid,HF_HOST,'tid='.$tid.'&pcause='.$msg.rand(0,9).'&ic=1');
}
function share($sid,$content)
{
return curlFetch(HF_HOST.'share_weibo/',$sid,HF_HOST,'msg='.$content);
}
function signin($sid)
{
return curlFetch(HF_HOST.'punch/'.getid($sid,1),$sid,HF_HOST,null);
}
function active($sid,$tid,$tf)
{
$r=curlFetch(HF_HOST.'my_reward',$sid,HF_HOST,null);
$r1=cleandz('/taskSwitch\(\'(.*?)\'/is',$r);
//var_dump($r1[0]);
$ss=curlFetch(HF_HOST.'task_manage/1/1/'.$r1[0],$sid,HF_HOST.'my_reward',null);
if($ss=='ok2'){
$ss=curlFetch(HF_HOST.'task_manage/1/1/'.$r1[0],$sid,HF_HOST.'my_reward',null);
}
return  $ss;
}
//echo active('01e3118aef820a6d6c0333e328517f90','','');
function getscorebackall($sid)
{
return curlFetch(HF_HOST.'join_mutual/1/100',$sid,HF_HOST,null);
}

function getps($gz,$al=null,$hb=null){
if(HF_PROXY&&HF_PROXY!='HF_PROXY'){
if(!file_exists('config_proxy.php')){file_put_contents('config_proxy.php','<?php $i_proxy=0; ?>');}
include('config_proxy.php');
if($i_proxy){
$proxy=$i_proxy;
}
}
if(!$proxy){$proxy=null;}
$r=curlFetch(HF_HOST.$al,$_GET['sid'],HF_HOST,null,$proxy);
$s=cleandz($gz,$r);
if($s){
return $s;
}else{
return cleandz($hb,$r);
}
}

function getpoints(){
$r=getps('/\"u\_score\"\:(.*?)\,/is','my_reward','/\"u\_score\"\:(.*?)\,/is');
return $r[0];
}
function gettgpoints(){
$ss=cleandz('/<td>\微\博\求\关\注<\/td>(.*?)<\/td>/is',$tts);
$r=getps('/<td>\微\博\求\关\注<\/td>(.*?)<\/td>/is','my_reward','/<td>\微\博\求\收\听<\/td>(.*?)<\/td>/is');
return trim(str_replace('<td>','',$r[0]));
}

function postpoints($sid,$score){

if(HF_PROXY&&HF_PROXY!='HF_PROXY'){
if(!file_exists('config_proxy.php')){file_put_contents('config_proxy.php','<?php $i_proxy=0; ?>');}
include('config_proxy.php');
if($i_proxy){
$proxy=$i_proxy;
}
}
if(!$proxy){$proxy=null;}
$r=curlFetch(HF_HOST.'send_task',$sid,HF_HOST,'setextendScore='.$score.'&settaskLimit=0&setfansnum=0&setcreateData=0&extendFollowSexV=0&extendFollowProvinceV=0&extendFollowCityV=0&WeiboFollowTypeV=1&extendTypeFormV=1&postWeiboIdV=0',$proxy);
return $r;
}

function getcontents($sid,$page,$type){
$aaa=array();
$ddd=json_decode(gettask($page,$sid),1);
$ddd=$ddd['task_list'];
for ($i=0;$i<count($ddd);$i++){
if((!strstr($ddd[$i]['weibo_content'],'http://t.cn/')&&!strstr($ddd[$i]['weibo_content'],'http://url.cn/')&&!strstr($ddd[$i]['weibo_content'],'粉丝')&&!strstr($ddd[$i]['weibo_content'],'扣扣')&&!strstr($ddd[$i]['weibo_content'],'qq')&&!strstr($ddd[$i]['weibo_content'],'QQ')&&!strstr($ddd[$i]['weibo_content'],'天猫')&&!strstr($ddd[$i]['weibo_content'],'淘宝'))||$type=='u'){
$bbb=$ddd[$i]['task_id'];
if($bbb){
$aaa[count($aaa)]= $bbb;
}
}
}
return ($aaa);
}
function run($uid,$sid,$ks,$type,$int,$pages){
$page=1;
$ai=array();
include('post_list.php');
while(true){
$j=getcontents($sid,$page,$type);
for ($i=0;$i<count($j);$i++){
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

$sx=relay($sid,$j[$i],$ai[rand(0,count($ai)-1)]);

echo $sx.' ';

try 
{

file_put_contents('json/corn_run_'.$uid.'.json','{"time":"'.time().'","err":"'.$sx.'"}');

} 
catch(Exception $e) 
{}
try 
{
file_put_contents($ks,'
'.date('y-m-d h:i:s').' '.($i+1).'/'.(count($j)).' '.$j[$i].' '.$sx,FILE_APPEND);
} 
catch(Exception $e) 
{}
if(trim($sx)=='ok'){
sleeps($uid,'run',$ks,$int);
}else{
sleeps($uid,'run',$ks,TIME_ALL);
}
}

if($page<($pages+1)){
$page=$page+1;}else{$page=1;}
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
file_put_contents('json/corn_run_'.$uid.'.json','{"time":"'.time().'","err":"'.$sx.'"}');
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
}
sleeps($uid,'run',$ks,TIME_ALL);
}
}
?>