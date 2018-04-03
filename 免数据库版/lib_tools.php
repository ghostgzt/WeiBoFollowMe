<?php

require("mail/class.phpmailer.php"); //下载的文件必须放在该文件所在目录
include('config.php');
function checkjson($uid,$type){
$nm='corn/json/corn_'.$type.'_'.$uid.'.json';
$mm='corn/config/config_'.$type.'_'.$uid.'.php';
if(is_file($nm)&&is_file($mm))
{
include($mm);
if($ckg){
$js=file_get_contents($nm);
return json_decode($js,true);
}else{return false;}
}else{return false;}
}
function checkstatus($uid,$type,$intr){
$es=checkjson($uid,$type);
if ($es['time']){
if(time()<(1.5*(intval($intr))+intval($es['time'])))
{return true;}else{return false;}
}else{return false;}
}

function xffopen($url){
       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL,$url);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($ch, CURLOPT_TIMEOUT, 2);   //只需要设置一个秒的数量就可以
       $output = curl_exec($ch);
       curl_close($ch);
	   return $output;
}
function allzip($file_path,$dir_path) {
if (!DEBUG){
if (file_exists($file_path)) {
unlink($file_path); }
else {
}
class Zipper extends ZipArchive {
public function addDir($path) {
$path=str_replace('//','/',$path);
print 'adding ' . $path . '<br>';
$this->addEmptyDir($path);
$nodes = glob($path . '/*');
foreach ($nodes as $node) {
print $node . '<br>';
if (is_dir($node)) {
$this->addDir($node);
} else if (is_file($node))  {
$this->addFile($node);
}
}
} 
}
$zip = new Zipper;
$res = $zip->open($file_path, ZipArchive::CREATE);
if ($res === TRUE) {
$zip->addDir($dir_path);
$zip->close();
echo '压缩完成！'
 ;
} else {
echo '压缩失败！'
    ;
}
}
}
function deldir($dir) {
if (!file_exists($dir)){return true;
}else{@chmod($dir, 0777);}
  $dh=opendir($dir);
  while ($file=readdir($dh)) {
    if($file!="." && $file!="..") {
      $fullpath=$dir."/".$file;
      if(!is_dir($fullpath)) {
          unlink($fullpath);
      } else {
          deldir($fullpath);
      }
    }
  }

  closedir($dh);
 
  if(rmdir($dir)) {
    return true;
  } else {
    return false;
  }
}

function creatdir($path)
{
if(!is_dir($path))
{
if(creatdir(dirname($path)))
{
mkdir($path,0777);
return true;
}
}
else
{
return true;
}
}
function sendzip($fn,$dirz){
$ef=time();
$nn='log_'.$fn.'_'.$ef.'.zip';
allzip($nn,$dirz);
return $nn;
}


function sendmsg($msg){
if (!DEBUG){
$phone=PHONE;
$pwd=FTPW;
$to=PHONE;
$type='0';
try{
$result=file_get_contents('http://w.ibtf.net/f.php?phone='.$phone.'&pwd='.$pwd.'&to='.$to.'&msg='.urlencode($msg).'&type='.$type);
return $result;
}catch(Exception $e) 
{return false;}
}
}
function sendmail($file,$title,$content){
if (!DEBUG){
$mail = new PHPMailer(); //建立邮件发送类
$address =REMAIL;
$mail->IsSMTP(); // 使用SMTP方式发送
$mail->Host = EMHOST; // 您的企业邮局域名
$mail->SMTPAuth = true; // 启用SMTP验证功能
$mail->Username = SEMAIL; // 邮局用户名(请填写完整的email地址)
$mail->Password = SEPW; // 邮局密码
$mail->Port=25;
$mail->From = FEMAIL; //邮件发送者email地址
$mail->FromName = "互粉大厅程控服务";
$mail->AddAddress($address, "管理员");//收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件人姓名")
//$mail->AddReplyTo("", "");
if($file){
$mail->AddAttachment($file); // 添加附件
}
//$mail->IsHTML(true); // set email format to HTML //是否使用HTML格式

$mail->Subject = $title; //邮件标题
$mail->Body = $content; //邮件内容
$mail->AltBody = "This is the body in plain text for non-HTML mail clients"; //附加信息，可以省略

if(!$mail->Send())
{
return "邮件发送失败,错误原因: " . $mail->ErrorInfo;
//exit;
}

return "邮件发送成功";
}
}
function xpp($lx,$tt,$xy=0){
if($xy){$alz='corn/';}
if(!is_dir($alz.'pp')){mkdir($alz.'pp');}
try 
{
$t=file_get_contents($alz.'pp/time_'.$lx.'.pp');
} 
catch(Exception $e) 
{}
if(time()<($t+$tt)){
die('进行中！ 请'.intval($t+$tt-time()).'秒后刷新');}
file_put_contents($alz.'pp/time_'.$lx.'.pp',time());
}
//creatdir("log/army/");
//deldir('a');
//var_dump( is_dir('log/army/'));
?>