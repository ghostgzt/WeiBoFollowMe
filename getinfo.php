<?php
include('config.php');
function curlFetch($url, $phpsessid = "", $referer = "", $data = null)
    {
        $ch = curl_init($url);
		if(HF_PROXY&&HF_PROXY!='HF_PROXY'){
if(!file_exists('corn/config_proxy.php')){file_put_contents('corn/config_proxy.php','<?php $i_proxy=0; ?>');}
include('corn/config_proxy.php');
if($i_proxy){
curl_setopt($ch, CURLOPT_PROXY, $i_proxy);
}
}
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 返回字符串，而非直接输出
        curl_setopt($ch, CURLOPT_HEADER, false);   // 不返回header部分
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);   // 设置socket连接超时时间
		curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
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
        set_time_limit(120); // 设置自己服务器超时时间
        $str = curl_exec($ch);
        curl_close($ch);
        return $str;
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
if($_GET['points']&&$_GET['sid']){
$r=curlFetch(HF_HOST,$_GET['sid'],HF_HOST,null);
$d= cleandz('/\您\的\体\力\：(.*?)\ \点/is',$r);
die($d[0]);
}
?>