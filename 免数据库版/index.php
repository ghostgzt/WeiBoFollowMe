<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>推兔类应用互粉服務控制後臺面板 - 天狼星の破曉</title>
	<link rel="icon" href="hcd/img/royal.png" />
	<script src="hcd/js/md5.js"></script>
	<script src="hcd/js/cookie.js"></script>
    <script src="hcd/artDialog/artDialog.source.js?skin=idialog"></script>
    <script src="hcd/artDialog/plugins/iframeTools.source.js"></script>
<?php
error_reporting(0);
include_once( 'lib_tools.php' );
include_once( 'lib_sql.php' );
include_once( 'config.php' );
$user=USER;
$passwd=PASSWD;
if($_POST['uid']&&$_POST['sid']&&$_POST['type']&&$_POST['pic']){
refsql(array(
'uid'=>$_POST['uid'],'data'=>"sid='".$_POST['sid']."',type='".$_POST['type']."',pic='".urlencode($_POST['pic'])."'"
  ));
  include('manage.inc');
die();
}
if($_POST['auid']&&$_POST['asid']&&$_POST['atype']&&$_POST['apic']){
refsql(array(
'uid'=>$_POST['auid'],'data'=>"sid='".$_POST['asid']."',type='".$_POST['atype']."',pic='".urlencode($_POST['apic'])."'"
  ));
  include('manage.inc');
die();
}
if($_POST['default']){
file_put_contents('config.php',file_get_contents('config_default.php'));
header('Location: index.php');
  include('manage.inc');
die();
}
if($_POST['user']&&$_POST['passwd']&&$_POST['phone']&&$_POST['ftpw']&&$_POST['remail']&&$_POST['emhost']&&$_POST['semail']&&$_POST['sepw']&&$_POST['femail']&&$_POST['timerun']&&$_POST['timeall']&&$_POST['runpage']&&$_POST['hfhost']){
$sc=file_get_contents('config.inc');
$sc=str_replace('"USER"','"'.$_POST['user'].'"',$sc);
$sc=str_replace('"PASSWD"','"'.$_POST['passwd'].'"',$sc);
$sc=str_replace('"HF_PROXY"','"'.$_POST['hfproxy'].'"',$sc);
$sc=str_replace('"PHONE"','"'.$_POST['phone'].'"',$sc);
$sc=str_replace('"FTPW"','"'.$_POST['ftpw'].'"',$sc);
$sc=str_replace('"REMAIL"','"'.$_POST['remail'].'"',$sc);
$sc=str_replace('"EMHOST"','"'.$_POST['emhost'].'"',$sc);
$sc=str_replace('"SEMAIL"','"'.$_POST['semail'].'"',$sc);
$sc=str_replace('"SEPW"','"'.$_POST['sepw'].'"',$sc);
$sc=str_replace('"FEMAIL"','"'.$_POST['femail'].'"',$sc);
$sc=str_replace('"TIME_RUN"',$_POST['timerun'],$sc);
$sc=str_replace('"TIME_ALL"',$_POST['timeall'],$sc);
$sc=str_replace('"RUN_PAGE"',$_POST['runpage'],$sc);
$sc=str_replace('"HF_HOST"','"'.$_POST['hfhost'].'"',$sc);
if ($_POST['debug']=='1'){$s=1;}else{$s=0;}
$sc=str_replace('"DEBUG"',$s,$sc);
if ($_POST['hfproxy']=='1'){$z=1;}else{$z=0;}
$sc=str_replace('"HF_PROXY"',$z,$sc);
file_put_contents('config.php',$sc);
header('Location: index.php');
  //include('manage.inc');
die();
}
if($_POST['xuid']){
delsql(array(
'uid'=>$_POST['xuid']
  ));
  include('manage.inc');
die();
}
if($_POST['refresh']){
xffopen(HOME_PATH.'checkstatus.php');
include('manage.inc');
die();
}
if($_POST['username']==$user&&$_POST['password']==$passwd){

include('manage.inc');
die();
}
$xspm=base64_encode(time());
?>
<script>
var cnm=0;
var xspm='<?php echo $xspm;?>';
var xuser='<?php echo md5($xspm.$user.$xspm);?>';
var xpasswd='<?php echo md5($xspm.$passwd.$xspm);?>';

function login(){
art.dialog.open('hcd/artDialog/login.html', {
    lock: true,
    title: '登录',
    // 在open()方法中，init会等待iframe加载完毕后执行
    init: function () {
    	var iframe = this.iframe.contentWindow;
    	var top = art.dialog.top;// 引用顶层页面window对象
		var form = iframe.document.getElementById('login-form');
        var username = iframe.document.getElementById('login-form-username');
		var password = iframe.document.getElementById('login-form-password');
        username.value = '';
		password.value = '';
        setTimeout(function () {
        	username.select();
        }, 80);
        top.document.title = '登錄 - 推兔互粉服務控制後臺面板 - 天狼星の破曉';
    },
    ok: function () {
    	var iframe = this.iframe.contentWindow;
    	if (!iframe.document.body) {
        	alert('iframe还没加载完毕呢')
        	return false;
        };
    	var form = iframe.document.getElementById('login-form'),
            username = iframe.document.getElementById('login-form-username'),
    		password = iframe.document.getElementById('login-form-password');
        if (check(username) && check(password)){
		
		if(hex_md5(xspm+username.value+xspm)==xuser&&hex_md5(xspm+password.value+xspm)==xpasswd){
		setCookie('xuser',username.value,3600*24);
		setCookie('xpasswd',password.value,3600*24);
		form.submit();
		}else{
		if (cnm<2){
		art.dialog.tips('操你妹！', 3);
		
		setTimeout("login()",3000);
		cnm=cnm+1;
		}else{setTimeout("window.close()",3000);art.dialog.tips('把你妹操了三次了，B都操黑了，滚吧！', 3);}	
		
		} 
		}
	
       	return false;
		
    },
    cancel: false
});

// 表单验证
var check = function (input) {
    if (input.value === '') {
        inputError(input);
        input.focus();
        return false;
    } else {
        return true;
    };
};

// 输入错误提示
var inputError = function (input) {
    clearTimeout(inputError.timer);
    var num = 0;
    var fn = function () {
        inputError.timer = setTimeout(function () {
            input.className = input.className === '' ? 'login-form-error' : '';
            if (num === 5) {
                input.className === '';
            } else {
                fn(num ++);
            };
        }, 150);
    };
    fn();
};
}

</script>
</head>
<body style="background:url(hcd/img/B304B.png);" oncontextmenu="return false">
<form id="xd" action="" method="post"><input value="" style="display:none;" id="xu" name="username">
<input value="" id="xp" style="display:none;" name="password"></form>
<script>
if(hex_md5(xspm+getCookie('xuser')+xspm)==xuser&&hex_md5(xspm+getCookie('xpasswd')+xspm)==xpasswd){
art.dialog.tips('正在登錄...', 10);
document.getElementById('xu').value=getCookie('xuser');
document.getElementById('xp').value=getCookie('xpasswd');
document.getElementById('xd').submit();
}else{
login();
}
</script>
</body>
</html>