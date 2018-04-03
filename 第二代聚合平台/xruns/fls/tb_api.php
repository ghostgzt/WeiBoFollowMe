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


if (!function_exists('gzdecode')) {      
    function gzdecode ($data) {      
        $flags = ord(substr($data, 3, 1));      
        $headerlen = 10;      
        $extralen = 0;      
        $filenamelen = 0;      
        if ($flags & 4) {      
            $extralen = unpack('v' ,substr($data, 10, 2));      
            $extralen = $extralen[1];      
            $headerlen += 2 + $extralen;      
        }      
        if ($flags & 8) // Filename      
            $headerlen = strpos($data, chr(0), $headerlen) + 1;      
        if ($flags & 16) // Comment      
            $headerlen = strpos($data, chr(0), $headerlen) + 1;      
        if ($flags & 2) // CRC at end of file      
            $headerlen += 2;      
        $unpacked = @gzinflate(substr($data, $headerlen));      
        if ($unpacked === FALSE)      
              $unpacked = $data;      
        return $unpacked;      
     }      
} 
function cleandz($gz,$html,$rp=null,$wz=null){
preg_match_all($gz, $html, $match);
if(!$rp){
$a=array_unique($match[(($wz)?0:1)]);
}else{
$a=($match[(($wz)?0:1)]);
}
$s=array();
for ($i=0;$i<count($a);$i++){
if($a[$i]){
$s[count($s)]=$a[$i];
}
}
return ($s);
}
function gpcore($url,$type,$content,$sid,$suy,$uid){

 $header = "GET: HTTP/1.1\r\n";
 $header .= "Host: www.xuanker.com\r\n"; 
 $header .= "Connection: keep-alive\r\n"; 
 $header .= "Cache-Control: max-age=0\r\n"; 
 $header .= "User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.89 Safari/537.1\r\n"; 
 $header .= "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n";  
 $header .= "Accept-Encoding: gzip,deflate,sdch\r\n";  
 $header .= "Accept-Language: zh-CN,zh;q=0.8\r\n";  
 $header .= "Accept-Charset: GBK,utf-8;q=0.7,*;q=0.3\r\n";  
 $header .= "Cookie: DEFAULT_USER_KEY=weibo-$uid;SPRING_SECURITY_REMEMBER_ME_COOKIE=$suy;JSESSIONID=$sid\r\n";  
 $opts = array(
   'http'=>array(
     'method'=>"$type",
     'header'=>$header,
     'content'=>$content,
   )
  );
  $context = stream_context_create($opts);
  $string = @file_get_contents($url, 'r', $context);
  $scon=($string);
return $scon;
}
function wbscheck($date,$sid,$suy,$uid){

 $scon= gpcore("http://www.xuanker.com/user/publish/ajaxSearchRepostRecord?datetime=",'GET','',$sid,$suy,$uid);
 
 return gzdecode( cleandz('/<table\ width\=\"100\%\" border\=\"0\" cellspacing\=\"0\"\ cellpadding\=\"0\">(.*?)<\/table>/is',$scon,0,1));

}
function getweibo($tid,$type,$page,$sid,$suy,$uid){

  
 $scon= gzdecode(gpcore("http://www.xuanker.com/user/publish/getRepost?t=$type&i=$tid&page=$page",'GET','',$sid,$suy,$uid));
  
  
  
  $ids=( cleandz('/\"mine\_pinglun\"\ id\=\"(.*?)\"/is',$scon));
$cons=( cleandz('/<\/a>\：<span>(.*?)<\/span>/is',$scon));
$sref=str_replace('target="_blank">','|',cleandz('/<a\ href\=\"http\:\/\/app\.weibo\.com\/t\/feed\/(.*?)<\/a>/is',$scon,1));
$wnm=( cleandz('/id\=\"hid\_userName\"\ value\=\"(.*?)\"/is',$scon));

$wpc=( cleandz('/<img\ blogimg\=\"null\"\ class\=\"null\"\ src\=\"(.*?)\"/is',$scon));
for($i=0;$i<count($sref);$i++){
$ssw=split('\|',$sref[$i]);
$sref[$i]= trim($ssw[1]);
}
//var_dump($sref);
$wbs=array();
$wbs['uid']=$uid;
$wbs['user']=$wnm[0];
$wbs['pic']=$wpc[0];
for($i=0;$i<count($ids);$i++){
$ii=count($wbs['list']);
$wbs['list'][$ii]['id']=$ids[$i];
$wbs['list'][$ii]['content']=$cons[$i];
$wbs['list'][$ii]['type']=$sref[$i];
}
  return ($wbs);
}

function runwbs($tid,$word,$content,$uid,$upic,$uname,$sid,$suy){

 $scon= gpcore("http://www.xuanker.com/user/publish/ajaxTimingAddBlog",'POST','_text='.urlencode($word).'&_rootBlogId='.$tid.'&_rootContent='.urlencode($content).'&_rootUserId='.$uid.'&_rootUserImage='.urlencode($upic).'&_rootUserName='.urlencode($uname).'&_timeInterval=10&_comment=true',$sid,$suy,$uid);
return $scon;
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

function curlFetch($url, $phpsessid = "", $referer = "", $data = null)
    {/*
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
        return $str;*/
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
/*$r= curlFetch(HF_HOST.'space',$sid,HF_HOST,null);
$r1=explode('|',str_replace('" title=','|',str_replace('id="play_0_','|',$r)));
return $r1[1];*/
}
function gettask($page,$sid)
{
//return curlFetch(HF_HOST.'task/reposttuis/p/'.$page,$sid,HF_HOST,null);
}
function relay($sid,$tid,$msg)
{
//return curlFetch(HF_HOST.'task/dorepost',$sid,HF_HOST,'is_comment=1&reason='.$msg.rand(0,9).'&status_id='.$tid);
}
function share($sid,$content)
{
//return curlFetch(HF_HOST.'task/dopost',$sid,HF_HOST,'content='.$content);
}
function signin($sid)
{
//return curlFetch(HF_HOST.'task/signin',$sid,HF_HOST,null);
}
function active($sid,$tid,$tf)
{
//return curlFetch(HF_HOST.'task/setactive',$sid,HF_HOST,'active='.$tf.'&tid='.$tid.'&type=0');
}
function getscorebackall($sid)
{
//return curlFetch(HF_HOST.'viewfl/getflbackall',$sid,HF_HOST,null);
}

function getps($gz,$al=null,$hb=null){
/*if(HF_PROXY&&HF_PROXY!='HF_PROXY'){
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
}*/
}

function getpoints(){
/*
$r=getps('/<span\ style\=\'color\:#fff\;\'>(.*?)<\/span>/is',null,'/href\=\"javascript\:\;\"\>\积\分\:(.*?)<\/a>/is');
return $r[0];
}
function gettgpoints(){
$r=getps('/\关\注\推\广<\/td><td>(.*?)<\/td>/is','space','/\收\听\推\广<\/td><td>(.*?)<\/td>/is');
return $r[0];
*/
return 'None';
}

function postpoints($sid,$score){

/*if(HF_PROXY&&HF_PROXY!='HF_PROXY'){
if(!file_exists('config_proxy.php')){file_put_contents('config_proxy.php','<?php $i_proxy=0; ?>');}
include('config_proxy.php');
if($i_proxy){
$proxy=$i_proxy;
}
}
if(!$proxy){$proxy=null;}
$uid=getps('/<tr\ id\=\"f\_(.*?)\"/is','space');
$r=curlFetch(HF_HOST.'task/updatefollowtui',$sid,HF_HOST,"tid=".$uid[0]."&points=".$score."&max_num=0&gender=0&fans=0",$proxy);
return $r;*/
return 'None';
}

function getcontents($sid,$page,$type){
/*
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
return ($aaa);*/
}
//run('3203151133','64CFAEAA4F320DE3FBF93A0FEA8A79BE','log/run/t_3203151133.txt','n',10,1);
function run($uid,$sid,$ks,$type,$int,$suy){


sleeps($uid,'run',$ks,$int);







$ai=array();
include('post_list.php');
while(true){

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




if($type=='u'){
if($uid=='3145790273'){
$wbua=array('3203151133','1843533784');
}else{
$wbua=array('3203151133','1843533784','3145790273');
}
$wtp=rand(0,count($wbua)-1);
$wbh=$wbua[$wtp];
}else{
$wbna=array('1644395354');
$wtp=rand(0,count($wbna)-1);
$wbh=$wbna[$wtp];
}

$wbs=( getweibo($wbh,'3','1',$sid,$suy,$uid));
//$mbs=( getweibo('3203151133','3','1','BBFB8A420E9BBD0A81AF70E85C03C9AA'));



if(!file_exists($ks)){
@file_put_contents($ks,date('y-m-d h:i:s').' Starting',FILE_APPEND);
}
$tt=@file_get_contents($ks);
$sjs=-1;
for($i=0;$i<count($wbs['list']);$i++){
if(strstr($tt,$wbs['list'][$i]['id'])||strstr($tt,(strstr($tt,$wbs['list'][$i]['id'])))){




if($sjs==-1){
$r= '木有更新！';
}
}else{
/*file_get_contents('123.txt',$wbs);
  echo str_repeat(" ",1024);//写满IE有默认的1k buffer
  ob_flush();//将缓存中的数据压入队列

    flush();//输出缓存队列中的数据
*/



if($sjs==-1){
if($uid=='3145790273'){
if((($type=='u')&&($wbs['list'][$i]['type']=='云中小鸟'))||($type=='n')){

$sjs=$i;
//$r='Yes';
$r=(runwbs($wbs['list'][$sjs]['id'],'[lxhx喵][lxhx喵]'.$ai[rand(0,count($ai)-1)].rand(0,9),$wbs['list'][$sjs]['content'],$uid,$wbs['pic'],$wbs['user'],$sid,$suy));

}

}else{
if((($type=='u')&&(($wbs['list'][$i]['type']=='爱淘宝')||($wbs['list'][$i]['type']=='冒泡网')||($wbs['list'][$i]['type']=='不愁网')||($wbs['list'][$i]['type']=='云中小鸟')||($wbs['list'][$i]['type']=='哈皮士网')))||($type=='n')){

$sjs=$i;
//$r='kYes';
$r=(runwbs($wbs['list'][$sjs]['id'],'[lxhx喵]'.$ai[rand(0,count($ai)-1)].rand(0,9),$wbs['list'][$sjs]['content'],$uid,$wbs['pic'],$wbs['user'],$sid,$suy));

}

}

}
//break 1;
}
}
//$r=$wbs['list'][$sjs]['type'];


@file_put_contents($ks,'
'.date('y-m-d h:i:s').' '.$sjs.'/'.count($wbs['list']).' '.$wbs['list'][$sjs]['id'].' ('.$wbh.' To '.$uid.') {'.$wbs['list'][$sjs]['content'].'} '.$wbs['list'][$sjs]['type'].' '.$r,FILE_APPEND);
sleeps($uid,'run',$ks,$int);

















/*
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
sleeps($uid,'run',$ks,TIME_ALL);*/
}
}
?>