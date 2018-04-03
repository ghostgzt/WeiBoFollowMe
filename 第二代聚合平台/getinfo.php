<?php
function cleandz($gz,$html){
preg_match_all($gz, $html, $match);
return $match[1][0];
}
function cleandx($gz,$html){
preg_match_all($gz, $html, $match);
return $match[1];
}
 function unescape($str)
    {
        $str = rawurldecode($str);
        preg_match_all("/%u.{4}|&#x.{4};|&#d+;|.+/U",$str,$r);
        $ar = $r[0];
        foreach($ar as $k => $v) {
            if(substr($v,0,2) == "%u") {
                $restr = substr($v, -4);
                  if (!eregi("WIN", PHP_OS)){
                    $restr=substr($restr, 2, 2).substr($restr, 0, 2);
                }
                $ar[$k] = iconv("UCS-2", $this->encode, pack("H4", $restr));
             } elseif (substr($v, 0, 3) == "&#x") {
                $ar[$k] = iconv("UCS-2", $this->encode, pack("H4",substr($v,3,-1)));
             } elseif(substr($v, 0, 2) == "&#") {
                 $ar[$k] = iconv("UCS-2", $this->encode, pack("n",substr($v,2,-1)));
             }
        }
        return join('',$ar); 
    }
function fromCharCode($code){
$c="&#".$code.";";
$c=iconv("UTF-8","GB2312",$c);
return $c;
}
function utf82unicode($str)  
{
    return iconv("utf-8", "UCS-2BE", $str); }
 
function decode($str) { 
preg_match_all("/(\d{2,5})/", $str,$a);
$a = $a[0];
foreach ($a as $dec){
if ($dec < 128) { 
$utf .= chr($dec); 
} else if ($dec < 2048) { 
$utf .= chr(192 + (($dec - ($dec % 64)) / 64)); 
$utf .= chr(128 + ($dec % 64)); 
} else { 
$utf .= chr(224 + (($dec - ($dec % 4096)) / 4096)); 
$utf .= chr(128 + ((($dec % 4096) - ($dec % 64)) / 64)); 
$utf .= chr(128 + ($dec % 64)); 
} 
}
return $utf;
} 
function charCodeAt($code,$i){
preg_match_all("/[\x80-\xff]?./",$code,$ar);
$c='';
$c=utf82unicode(iconv("GB2312","UTF-8",$ar[0][$i]));
return $c;
}
function curlFetch($url, $phpsessid = "", $referer = "", $data = null,$proxy=null)
    {
	        $ch = curl_init($url);

if($proxy){
curl_setopt($ch, CURLOPT_PROXY, $proxy);
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
function uniord($c,$i) {
        $h = ord($c{$i});
        if ($h <= 0x7F) {
            return $h;
        } else if ($h < 0xC2) {
            return false;
        } else if ($h <= 0xDF) {
            return ($h & 0x1F) << 6 | (ord($c{1}) & 0x3F);
        } else if ($h <= 0xEF) {
            return ($h & 0x0F) << 12 | (ord($c{1}) & 0x3F) << 6
                                     | (ord($c{2}) & 0x3F);
        } else if ($h <= 0xF4) {
            return ($h & 0x0F) << 18 | (ord($c{1}) & 0x3F) << 12
                                     | (ord($c{2}) & 0x3F) << 6
                                     | (ord($c{3}) & 0x3F);
        } else {
            return false;
        }
    }   
	function getproxylist($p=null,$l=null){
if(isset($p)&&isset($l)){$p="?".$p;$l='&t='.$l;}else{
	if(isset($p)){$p='?'.$p;}if(isset($l)){$l='?t='.$l;}}
	$rs=curlFetch('http://www.proxies.by/raw_free_db.htm'.$p.$l,null,'http://www.proxies.by/',null);
$s=unescape(trim(str_replace(');','',str_replace('\'','',str_replace('hideTxt(','',cleandz('/hideTxt\((.*?)\'\)\;/is',str_replace('hideTxt(str)','',$rs)))))));

$x=round(sqrt(cleandz('/Math\.sqrt\((.*?)\)/is',$rs)));
for($i=0;$i<strlen($s);$i++){
$t.=fromCharCode(uniord($s,$i)^($i%2?$x:0));
}
$r=str_replace('%37','\'',str_replace('$3b','=',str_replace('$27','"',str_replace('%2e','>',str_replace('%2c','<',str_replace('|','</td><td><a href="/cgi-bin/shdb.pl?key=',str_replace('!','</td></tr><tr class="dbodd"><td>',str_replace('~','</td></tr><tr class="dbeven"><td>',str_replace('^','" title="',str_replace('*','</td><td>',decode($t)))))))))));
$k=cleandx('/stat">(.*?)<\/a>/is',$r);
for ($i=0;$i<count($k);$i++){
$k[$i]=mb_convert_encoding($k[$i], "UTF-8", "HTML-ENTITIES"); 
}
//var_dump($k);
if($_GET['t']==0){$d='ALL';}if($_GET['t']==1){$d='HTTP';}if($_GET['t']==2){$d='CONNECT';}if($_GET['t']==3){$d='SOCKS';}if($_GET['t']==4){$d='SOCKS';}
$b=array('type'=>$d,'page'=>$_GET['p']?$_GET['p']:0,'count'=>count($k),'data'=>$k);
return json_encode($b);
}


if ($_GET['proxy']){
echo getproxylist($_GET['p'],$_GET['t']);
}else{
if ($_GET['url']){
header("content-type: image/png");
echo file_get_contents($_GET['url']);
}
}
?>