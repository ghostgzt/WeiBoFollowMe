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
function bzsc($str,$gx=null){
if(!$gx){
$r=$str;
$ss=json_decode($r,1);
$r=json_encode(array('status'=>$ss['s'],'info'=>$ss['msg']));
}else{
$r=json_encode(array('status'=>1,'info'=>$gx));
}
//$r=json_encode(array('status'=>$ss['s'],'info'=>$ss['msg']));
return $r;
}
function getid($sid,$nw=null)
{
if(!$nw){
$r=curlFetch(HF_HOST.'my/loadTask',$sid,HF_HOST,null);
$r1=json_decode($r,1);
return $r1['list'][0]['id'];
}else{
$r= curlFetch(HF_HOST.'my',$sid,HF_HOST,null);
$r1=cleandz('/UID:(.*?)</is',$r);
return $r1[0];
}
}
//var_dump(getid('k7cabh3rcsi74d90f134u266f6'));
function gettask($page,$sid)
{
return curlFetch(HF_HOST.'task/reposttask?cat=0&p='.$page,$sid,HF_HOST,null);
}
//echo gettask(1,'c5c676cdc331f7ce20443dd9942f819e');//http://hutuidaren.sinaapp.com/reward_website/
function relay($sid,$tid,$msg)
{
return curlFetch(HF_HOST.'task/dorepost?tid='.trim($tid).'&c='.$msg,$sid,HF_HOST,null);
}
//echo relay('k7cabh3rcsi74d90f134u266f6','24280','123');
function share($sid,$content)
{
$uid=getid($sid,1);
return bzsc(curlFetch(HF_HOST.'site/sign?uid='.$uid,$sid,HF_HOST,'uid='.$uid.'&type=1&content='.$content));
}
function signin($sid)
{
return bzsc(curlFetch(HF_HOST.'invite/invite',$sid,HF_HOST,'atuser='));
}
function active($sid,$tid,$tf)
{

//var_dump($r1[0]);
$ss=curlFetch(HF_HOST.'my/starttask/'.getid($sid).'?type=1',$sid,HF_HOST.'my/loadTask',null);
return  bzsc($ss);
}
//echo active('01e3118aef820a6d6c0333e328517f90','','');
function getscorebackall($sid)
{
$tids=array();
$page=1;
$sx=1;
while($sx){
$p0=curlFetch(HF_HOST.'my/checkList?page='.$page,$sid,HF_HOST,null);
$p1=json_decode($p0,1);
$p2=($p1['list']);
for($i=0;$i<count($p2);$i++){
if($p2[$i]['disabledname']!='1'){
$tids[count($tids)]=$p2[$i]['id'];
}
}
if($p1['nextpage']>$page){$page=$p1['nextpage'];}else{$sx=0;}
}
$rs=array();
if($tids){
for($a=0;$a<count($tids);$a++){
$i0=$tids[$a];
$s0=curlFetch(HF_HOST.'my/docheck?tid='.$i0,$sid,HF_HOST,null);
$rs[count($rs)]=$s0;
}
return $rs;
}
}
//var_dump(getscorebackall('k7cabh3rcsi74d90f134u266f6'));
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
$r=getps('/\可\用\积\分\：(.*?)\"/is','my/score','/\可\用\积\分\：(.*?)\"/is');
return $r[0];
}
function gettgpoints(){
$r=curlFetch(HF_HOST.'my/loadTask',$_GET['sid'],HF_HOST,null);
$r1=json_decode($r,1);
echo ($r1['list'][0]['score']['score']);
//return $r1['list'][0]['score']['score'];
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
$r=curlFetch(HF_HOST.'my/setfollow',$sid,HF_HOST,'id='.getid($sid).'&score='.$score.'&times=0&head=0&fans=0&sex=0&province=0&city=0&tag=',$proxy);
return bzsc($r,'操作成功！');
}

function getcontents($sid,$page,$type){
$aaa=array();
$ddd=json_decode(gettask($page,$sid),1);
$ddd=$ddd['list'];
for ($i=0;$i<count($ddd);$i++){
if((!strstr($ddd[$i]['introduction'],'http://t.cn/')&&!strstr($ddd[$i]['introduction'],'http://url.cn/')&&!strstr($ddd[$i]['introduction'],'粉丝')&&!strstr($ddd[$i]['introduction'],'扣扣')&&!strstr($ddd[$i]['introduction'],'qq')&&!strstr($ddd[$i]['introduction'],'QQ')&&!strstr($ddd[$i]['introduction'],'天猫')&&!strstr($ddd[$i]['introduction'],'淘宝'))||$type=='u'){
$bbb=$ddd[$i]['id'];
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

file_put_contents('json/corn_run_'.$uid.'.json','{"time":"'.time().'","err":"'.$sx['msg'].'"}');

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
$sx=json_decode($sx,1);
if($sx['s']==0){
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