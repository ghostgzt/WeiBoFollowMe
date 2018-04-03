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
function bzsc($str){
$r=$str;
$ss=json_decode($r,1);
$r=json_encode(array('status'=>$ss['s'],'info'=>$ss['msg']));
return $r;
}
function getid($sid)
{
$r= curlFetch(HF_HOST.'index.php?app=my',$sid,HF_HOST,null);

$r1=cleandz('/dataid="(.*?)"/is',$r);
return $r1[0];
}
function gettask($page,$sid)
{
return curlFetch(HF_HOST.'index.php?app=ajax&c=zhuan&d=2&type=0&page='.$page,$sid,HF_HOST,null);
}
function relay($sid,$tid,$msg)
{
return curlFetch(HF_HOST.'index.php?app=ajax&c=zhuan&d=4&tid='.$tid.'&reason='.$msg.rand(0,9),$sid,HF_HOST,null);
}
function share($sid,$content)
{
return bzsc(curlFetch(HF_HOST.'index.php?app=ajax&c=index&d=9',$sid,HF_HOST,'wbcontent='.$content));
}
function signin($sid)
{
return bzsc(curlFetch(HF_HOST.'index.php?app=ajax&c=index&d=1',$sid,HF_HOST,null));
}
function active($sid,$tid=null,$tf)
{
if($tf){$tt='start';}else{$tt='stop';}
return bzsc(curlFetch(HF_HOST.'index.php?app=ajax&c=my&d=1&status='.$tt,$sid,HF_HOST,null));
}
function getscorebackall($sid)
{
$tp=1;
while(true){
$r0=curlFetch(HF_HOST.'index.php?app=ajax&c=my&d=11&page='.$tp,$sid,HF_HOST,null);
$r0=json_decode($r0,true);
$r0=$r0["tasklist"];
$p=$r0['allpage'];
$ls=$r0['list'];
for($s=0;$s<count($ls);$s++){if(!$u1){$u1=$ls[$s]['id'];}else{$u1.=','.$ls[$s]['id'];}}
$rs0=curlFetch(HF_HOST.'index.php?app=ajax&c=my&d=13&checktids='.$u1,$sid,HF_HOST,null);
$tp++;
if($tp>$p){return true;}
}
return false;
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
if(isset($s)){
return $s;
}else{
return cleandz($hb,$r);
}
}

function getpoints(){
$r=getps('/"myscore">(.*?)<\/span>/is',null,null);
return $r[0];
}
function gettgpoints(){
$r=getps('/<td>(.*?)<\/td>/is','index.php?app=my',null);
return $r[1];
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
$r=curlFetch(HF_HOST.'index.php?app=ajax&c=my&d=5',$sid,HF_HOST,"taskid=".getid($sid)."&score=".$score."&sex=0&head=0&fansnum=0&times=0",$proxy);
$ss=json_decode($r,1);
$r=json_encode(array('status'=>$ss['s'],'info'=>$ss['msg']));
return $r;
}







function getcontents($sid,$page,$type){
$aaa=json_decode(gettask($page,$sid),1);
$r=$aaa['data'];
$rr=array();
for($i=0;$i<count($r);$i++){
$rs=$r[$i]['zbstr'];
$sr=cleandz('/repost\((.*?)\,/is', $rs);
$rr[count($rr)]=$sr[0];
}
return $rr;
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
file_put_contents('json/corn_run_'.$uid.'.json','{"time":"'.time().'","msg":"'.$ss['msg'].'"}');

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
if($ss['s']){
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
}
sleeps($uid,'run',$ks,TIME_ALL);
}
}
?>