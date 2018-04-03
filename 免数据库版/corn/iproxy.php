<?php
header("Content-Type: text/html;charset=utf-8");
ignore_user_abort(true);//设置与客户机断开是否会终止脚本的执行。
set_time_limit(0); //设置脚本超时时间，为0时不受时间限制
if($_GET['del']){unlink('config_proxy.php');die('已经取消代理！');}
include( '../config.php' );
if(!file_exists('config_proxy.php')){file_put_contents('config_proxy.php','<?php $i_proxy=0; ?>');}
include('config_proxy.php');
include( '../proxy_list.php' );
function getres($url,$reff,$post_data,$proxy){
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);

if($proxy){
curl_setopt ($ch, CURLOPT_PROXY, $proxy);
}
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)"); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:8.8.8.8', 'CLIENT-IP:8.8.8.8'));
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 120); 
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
if(HF_PROXY){
if(HF_PROXY!=='1'){
$result= getres(HF_HOST.'task/signin',HF_HOST,null,HF_PROXY);
if($result){
file_put_contents('config_proxy.php','<?php $i_proxy="'.HF_PROXY.'"; ?>');
die('代理服务器已经更换 '.HF_PROXY);
}
}

$result= getres(HF_HOST.'task/signin',HF_HOST,null,$i_proxy);
if(!$result){
$result= getres(HF_HOST.'task/signin',HF_HOST,null,null);
if(!$result){
for($i=0;$i<count($proxy_list);$i++){
$result= getres(HF_HOST.'task/signin',HF_HOST,null,$proxy_list[$i]);
if($result){
file_put_contents('config_proxy.php','<?php $i_proxy="'.$proxy_list[$i].'"; ?>');
die('代理服务器已经更换 '.$proxy_list[$i]);
}

}
}else{
file_put_contents('config_proxy.php','<?php $i_proxy=0; ?>');
}
}else{
die('不用更换代理服务器 '.$i_proxy);
}


}else{die('代理服务没开启！');}
?>