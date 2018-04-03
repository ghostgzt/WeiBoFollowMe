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
function getid($sid)
{
$r= curlFetch(HF_HOST.'space',$sid,HF_HOST,null);
$r1=explode('|',str_replace('" title=','|',str_replace('id="play_0_','|',$r)));
return $r1[1];
}
function gettask($page,$sid)
{
return curlFetch(HF_HOST.'task/reposttuis/p/'.$page,$sid,HF_HOST,null);
}
function relay($sid,$tid,$msg)
{
return curlFetch(HF_HOST.'task/dorepost',$sid,HF_HOST,'is_comment=1&reason='.$msg.rand(0,9).'&status_id='.$tid);
}
function share($sid,$content)
{
return curlFetch(HF_HOST.'task/dopost',$sid,HF_HOST,'content='.$content);
}
function signin($sid)
{
return curlFetch(HF_HOST.'task/signin',$sid,HF_HOST,null);
}
function active($sid,$tid,$tf)
{
return curlFetch(HF_HOST.'task/setactive',$sid,HF_HOST,'active='.$tf.'&tid='.$tid.'&type=0');
}
function getscorebackall($sid)
{
return curlFetch(HF_HOST.'viewfl/getflbackall',$sid,HF_HOST,null);
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
$r=getps('/<span\ style\=\'color\:#fff\;\'>(.*?)<\/span>/is',null,'/href\=\"javascript\:\;\"\>\积\分\:(.*?)<\/a>/is');
return $r[0];
}
function gettgpoints(){
$r=getps('/\关\注\推\广<\/td><td>(.*?)<\/td>/is','space','/\收\听\推\广<\/td><td>(.*?)<\/td>/is');
return $r[0];
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
$uid=getps('/<tr\ id\=\"f\_(.*?)\"/is','space');
$r=curlFetch(HF_HOST.'task/updatefollowtui',$sid,HF_HOST,"tid=".$uid[0]."&points=".$score."&max_num=0&gender=0&fans=0",$proxy);
return $r;
}

function getcontents($sid,$page,$type){
$aaa=array();
$ddd=explode('|', str_replace("<div class='repost clearfix'>",'|',gettask($page,$sid)));
for ($i=1;$i<count($ddd);$i++){
if((!strstr($ddd[$i],'http:\\/\\/t.cn\\/')&&!strstr($ddd[$i],'\/\/u7c89\/\/u4e1d')&&!strstr($ddd[$i],'\/\/u6263\/\/u6263')&&!strstr($ddd[$i],'qq')&&!strstr($ddd[$i],'QQ')&&!strstr($ddd[$i],'\/\/u6dd8\/\/u5b9d'))||$type=='u'){
$bbb=explode('|', str_replace('\\" class=\'btn btn-primary\'','|',str_replace('<a id=\"repost_btn_','|',$ddd[$i])));
if($bbb[1]){
$aaa[count($aaa)]= $bbb[1];
}
}
}
return ($aaa);
}
function run($uid,$sid,$ks,$type){
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
$ss=json_decode($sx,true);
file_put_contents('json/corn_run_'.$uid.'.json','{"time":"'.time().'","err":"'.$ss['info'].'"}');

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
if($ss['status']){
sleeps($uid,'run',$ks,TIME_RUN);
}else{
sleeps($uid,'run',$ks,TIME_ALL);
}
}

if($page<(RUN_PAGE+1)){
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