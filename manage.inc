<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css' />
<link href='http://fonts.googleapis.com/css?family=Shanti' rel='stylesheet' type='text/css' />
<link rel="stylesheet" href="hcd/css/style.css" />
<link rel="stylesheet" href="hcd/css/button.css" />
<script>
function fwsz(){
	var throughBox = art.dialog.through;
throughBox({
    content: document.getElementById('box4').innerHTML,
    lock: true
});
}
function fileman(){
art.dialog.open('gftp.php', {title: 'Gentle FTP', width: 800, height: 500,lock: true});
}

function tick(uid){
art.dialog.load('corn/daysay.php?uid='+uid,{lock: true});
}

function check(uid){
var throughBox = art.dialog.through;
throughBox({
    content: '請選擇對'+uid+'進行的操作<br/><div class="buttons"><button type="submit" class="positive" onclick="closeart();art.dialog.load(\'corn/unfollow_run.php?uid='+uid+'&run=1\',{lock: true});"><img src="hcd/img/apply2.png" alt=""/>自動計劃</button><a href="javascript:void();" onclick="closeart();art.dialog.load(\'corn/unfollow_run.php?uid='+uid+'&stop=1\',{lock: true});" class="negative"><img src="hcd/img/cross.png" alt=""/>停止計劃</a></div>',
    lock: true
});
}

function tickout(){
art.dialog.load('corn/daysay.php',{lock: true});
}

function checkout(){
var throughBox = art.dialog.through;
throughBox({
    content: '請選擇對所有帳號進行的操作<br/><div class="buttons"><button type="submit" class="positive" onclick="closeart();art.dialog.load(\'corn/unfollow_run.php?run=1\',{lock: true});"><img src="hcd/img/apply2.png" alt=""/>自動計劃</button><a href="javascript:void();" onclick="closeart();art.dialog.load(\'corn/unfollow_run.php?stop=1\',{lock: true});" class="negative"><img src="hcd/img/cross.png" alt=""/>停止計劃</a></div>',
    lock: true
});
}

function runshow(url){
art.dialog.load(url,{lock: true,init: function () {setTimeout('document.getElementById("xd").submit()',1000);}});
}

function runsevice(uid,sname){
runshow('corn/restart_'+sname+'.php?uid='+uid);
}
function closesevice(uid,sname){
runshow('corn/close_'+sname+'.php?uid='+uid);
}
function sg(uid){
var throughBox = art.dialog.through;
throughBox({
    content: document.getElementById('box1').innerHTML,
    lock: true
});
document.getElementById('uid').value=uid;
document.getElementById('did').innerHTML=uid;
document.getElementById('sid').value=document.getElementById(uid).rows[1].cells[3].innerHTML;

document.getElementById('type').value=document.getElementById(uid).rows[1].cells[4].innerHTML;

document.getElementById('pic').value=document.getElementById(uid).rows[1].cells[1].getElementsByTagName('img')[0].src;
}
function del(uid){
var throughBox = art.dialog.through;
throughBox({
    content: document.getElementById('box2').innerHTML,
    lock: true
});
document.getElementById('xuid').value=uid;
}

function clearlogs(){
art.dialog.confirm('你确认删除操作？', function(){
 art.dialog.load('loadlogs.php?del=1', {lock: true});
}, function(){
    art.dialog.tips('你取消了操作');
});
}
    function getlogs(uid){
    art.dialog.open('loadlogs.php?uid='+uid,
    {title: 'Corn Logs', width: 900, height: 400,lock: true});
	}
	function showifm(url,t,w,h,l){
	art.dialog.tips('请稍等,正在處理~', 1.5);
    setTimeout("art.dialog.open(url,{title: "+t+", width: "+w+", height: "+h+",lock:"+ l+"})",2000);
	}
	function closeart(){
	/*document.getElementsByClassName('aui_content')[0].innerHTML='';
	document.getElementsByClassName('   aui_state_focus aui_state_lock')[0].style.display='none';
    document.getElementsByClassName('   aui_state_focus aui_state_lock')[0].ClassName='';
	var divs = document.getElementsByTagName('div'); divs[divs.length-2].remove();*/
	var list = art.dialog.list;
for (var i in list) {
    list[i].close();
};
	}
function getpoints(sid,ele){
document.getElementById(ele).innerHTML='-';
	$.ajax({
		url: "getinfo.php?points=1&sid="+sid,
		data: "",
		type: "GET",
		success: function( response ){
			if( response.trim() ) {
		
			document.getElementById(ele).innerHTML=response;
			}else{
			document.getElementById(ele).innerHTML='0';
			}
		},
		error: function(){
		art.dialog.tips('获取失败，请重试！！！', 1.5);
		document.getElementById(ele).innerHTML='-';
		}
	});
}
function wbshow(uid){
art.dialog.open('getinfo.php?uid='+uid, {title: 'WB Show', width: 240, height: 220,lock: true});
}
function hufen(sid){
art.dialog.open('getinfo.php?sid='+sid, {title: 'HF Show', width: 820, height: 575,lock: true});
}
</script>
</head>
<body style="background:url(hcd/img/B304B.png);">
<div align="center">
<div style="width:1080px;margin-top:-20px;background:rgba(20, 16, 16, 0.67);">
<br/>
<br/>
<br/>
<?php
echo '<h1>互粉服务器: '.str_replace("_sinaapp_com","", SQL_TABLE).'&nbsp;<a style="color:white" title="启用代理" href="javascript:void();" onclick="runshow(\'corn/iproxy.php\');">+</a></h1>';
if(!file_exists('corn/config_proxy.php')){file_put_contents('corn/config_proxy.php','<?php $i_proxy=0; ?>');}
include('corn/config_proxy.php');
if($i_proxy&&HF_PROXY&&HF_PROXY!='HF_PROXY'){
echo '<h1>代理服务器: <a href="javascript:void();" title="自动检测" onclick="runshow(\'corn/iproxy.php\');">'.$i_proxy.'</a>&nbsp;<a style="color:black" title="取消代理" href="javascript:void();" onclick="runshow(\'corn/iproxy.php?del=1\');">X</a></h1>';
}
$arr=readrow(array(
  'sql_host'=>$sql_host,'sql_user'=>$sql_user,'sql_passwd'=>$sql_passwd,'db'=>$db
  ));
  echo'<h2><br/><a href="javascript:scroll(0,0);"> 用戶列表</a></h2>';
  for($i=0;$i<count($arr);$i++){
  echo '
  <table id="'.$arr[$i]['uid'].'" border="1" cellpadding="3" cellspacing="0" style="text-align:center;width: 1024px;margin:auto">
  <tr>
    <th>Status</th>
    <th>Picture</th>
    <th>UID</th>
	<th>Sid</th>
	<th>Type</th>
	<th>Points</th>
	<th>Operation</th>
  </tr>
  <tr>
  <td>'.(checkstatus($arr[$i]['uid'],'run',TIME_ALL) ? "<a title='轉發服務' href='javascript:void();' onclick='closesevice(\"".$arr[$i]['uid']."\",\"run\");'><img src='hcd/img/apply2.png' height='16px' width='16px' /></a>" : "<a title='轉發服務' href='javascript:void();' onclick='runsevice(\"".$arr[$i]['uid']."\",\"run\");'><img src='hcd/img/cross.png' height='16px' width='16px' /></a>" ).(checkstatus($arr[$i]['uid'],'betop',TIME_ALL) ? "<a title='置頂服務' href='javascript:void();' onclick='closesevice(\"".$arr[$i]['uid']."\",\"betop\");'><img src='hcd/img/apply2.png' height='16px' width='16px' /></a>" : "<a title='置頂服務' href='javascript:void();' onclick='runsevice(\"".$arr[$i]['uid']."\",\"betop\");'><img src='hcd/img/cross.png' height='16px' width='16px' /></a>").(checkstatus($arr[$i]['uid'],'army',TIME_ALL) ? "<a title='反對服務' href='javascript:void();' onclick='closesevice(\"".$arr[$i]['uid']."\",\"army\");'><img src='hcd/img/apply2.png' height='16px' width='16px' /></a>" : "<a title='反對服務' href='javascript:void();' onclick='runsevice(\"".$arr[$i]['uid']."\",\"army\");'><img src='hcd/img/cross.png' height='16px' width='16px' /></a>").'</td>
  <td><a title="打開微博" target="_blank" href="http://weibo.com/'.$arr[$i]['uid'].'"><img src="'.urldecode($arr[$i]['pic']).'"/></a></td><td>'.$arr[$i]['uid'].'</td> <td>'.$arr[$i]['sid'].'</td>  <td>'.$arr[$i]['type'].'</td>
  <td><a id="p_'.$arr[$i]['uid'].'" href="javascript:void();" onclick="getpoints(\''.$arr[$i]['sid'].'\',\'p_'.$arr[$i]['uid'].'\');">-<script>getpoints("'.$arr[$i]['sid'].'","p_'.$arr[$i]['uid'].'");</script></a></td>
  <td><a href="javascript:void();" onclick="sg(\''.$arr[$i]['uid'].'\');">修改</a>&nbsp;<a href="javascript:void();" onclick="del(\''.$arr[$i]['uid'].'\');">刪除</a>&nbsp;<a href="javascript:void();" onclick="tick(\''.$arr[$i]['uid'].'\');">簽到</a>&nbsp;<a href="javascript:void();" onclick="check(\''.$arr[$i]['uid'].'\');">檢測</a>&nbsp;<a href="javascript:void();" onclick="getlogs(\''.$arr[$i]['uid'].'\');">日誌</a></td>
    </tr>
</table>
  ';}
?>
 <table id="'.$arr[$i]['uid'].'" border="1" cellpadding="3" cellspacing="0" style="text-align:center;width: 1024px;margin:auto">
  <tr>
    <th><a href="javascript:void();" onclick="
	var throughBox = art.dialog.through;
throughBox({
    content: document.getElementById('box3').innerHTML,
    lock: true
});">添加賬戶</a></th>
    <th><form id="xd" action="" method="post"><input value="1" style="display:none;" name="refresh"><a href="javascript:void();" onclick="document.getElementById('xd').submit();">刷新列表</a></form></th>
	<th><a href="javascript:void();" onclick="tickout();">每日簽到</a></th>
	<th><a href="javascript:void();" onclick="checkout();">檢測互粉</a></th>
	<th><a href="javascript:void();" onclick="clearlogs();">清空日誌</a></th>
	<th><a href="javascript:void();" onclick="fwsz();">服務配置</a></th>
	<th><a href="javascript:void();" onclick="fileman();">文件管理</a></th>	
	<th><a target="_blank" href="<?php echo HF_HOST;?>">更多操作</a></th>
  </tr>
  </table>
<br/>
<h2><a href="javascript:scroll(0,document.body.scrollHeight);">控制面板</a></h2>
<div>
  <table id="'.$arr[$i]['uid'].'" border="1" cellpadding="3" cellspacing="0" style="text-align:center;width: 1024px;margin:auto">
  <tr>
    <th style="border: 1px solid #d0d0d0;">Service</th>
    <th style="border: 1px solid #d0d0d0;">Status</th>
	<th style="border: 1px solid #d0d0d0;">Boot</th>
	<th style="border: 1px solid #d0d0d0;">Reboot</th>
	<th style="border: 1px solid #d0d0d0;">End</th>
  </tr>
  <tr>
  <td style="border: 1px solid #d0d0d0;">所有服務</td>
  <td style="border: 1px solid #d0d0d0;"> - </td> 
  <td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="runshow('xcorn.php');">全部啟動</a></td> 
  <td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="runshow('restart.php');">重新啟動</a></td>
  <td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="runshow('close.php');">全部終止</a></td>
    </tr>
  <tr>
  <td style="border: 1px solid #d0d0d0;">轉發微博</td>
  <td style="border: 1px solid #d0d0d0;"><?php include( 'corn/config_run.php' );if ($kg){echo '運行標記';}else{echo '終止標記';}?></td> 
  <td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="runshow('corn/xcorn_run.php');">啟動轉發</a></td> 
  <td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="runshow('corn/restart_run.php');">重新啟動</a></td>
  <td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="runshow('corn/close_run.php');">終止轉發</a></td>
    </tr>
	 <tr>
  <td style="border: 1px solid #d0d0d0;">置頂搶位</td>
  <td style="border: 1px solid #d0d0d0;"><?php include( 'corn/config_betop.php' );if ($kg){echo '運行標記';}else{echo '終止標記';}?></td> 
  <td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="runshow('corn/xcorn_betop.php');">啟動置頂</a></td> 
  <td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="runshow('corn/restart_betop.php');">重新啟動</a></td>
  <td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="runshow('corn/close_betop.php');">終止置頂</a></td>
    </tr>
	 <tr>
  <td style="border: 1px solid #d0d0d0;">推翻反對</td>
  <td style="border: 1px solid #d0d0d0;"><?php include( 'corn/config_army.php' );if ($kg){echo '運行標記';}else{echo '終止標記';}?></td> 
  <td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="runshow('corn/xcorn_army.php');">啟動反對</a></td> 
  <td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="runshow('corn/restart_army.php');">重新啟動</a></td>
  <td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="runshow('corn/close_army.php');">終止反對</a></td>
    </tr>
</table>
</div>
<br/>
<br/>
<br/>
<h2><a href="http://www.godwolfs.asia/" target="_blank" title="天狼星の破曉">Made By Gentle In 2013</a></h2>
<br/>
</div>
</div>
<div align="center" id="box1" style="display:none;">

<form method="post" action="">
<table><input id="uid" name="uid" style="display:none;width:250px" value=""  />
<tr><td style="border: 1px solid #d0d0d0;">信息修改</td><td style="border: 1px solid #d0d0d0;">
<div class="buttons">
    <button type="submit" class="positive" name="save">
        <img src="hcd/img/apply2.png" alt=""/> 
        Save
    </button>
	<a href="javascript:void();" onclick="closeart();"  class="negative"><!-- class="regular"-->
        <img src="hcd/img/cross.png" alt=""/> 
        Cancel
    </a>
	<a href="javascript:void();" onclick="window.open('<?php echo HF_HOST;?>','_blank');"  class="regular"><!-- class="regular"-->
        <img src="hcd/img/textfield_key.png" alt=""/> 
        Main
    </a>
</div>	
</td></tr>
<tr><td style="border: 1px solid #d0d0d0;">UID:</td><td style="border: 1px solid #d0d0d0;"><span id='did'></span></td></tr>
<tr><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="alert('PHPSESSID')">Sid</a>:</td><td style="border: 1px solid #d0d0d0;"><input name="sid" id="sid" style="width:310px" value="" /></td></tr>

<tr><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="alert('有链接为a,无连接为b')">Type</a>:</td><td style="border: 1px solid #d0d0d0;"><input id="type" name="type" style="width:310px" value=""  /></td></tr>

<tr><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="alert('头像图片的url')">Pic</a>:</td><td style="border: 1px solid #d0d0d0;"><input name="pic" id="pic" style="width:310px" value="" /></td></tr>
<tr>
</table>
</form></div>
<div align="center" id="box2" style="display:none;">
確定刪除?
<form method="post" action="">
<input id="xuid" name="xuid" readonly="true" style="width:155px" value=""  />
<div class="buttons">
    <button type="submit" class="positive" name="save">
        <img src="hcd/img/apply2.png" alt=""/> 
        Save
    </button>
	<a href="javascript:void();" onclick="closeart();"  class="negative"><!-- class="regular"-->
        <img src="hcd/img/cross.png" alt=""/> 
        Cancel
    </a>
</div>
</form>
</div>
<div align="center" id="box3" style="display:none;">
<form method="post" action="">
<table >
<tr><td style="border: 1px solid #d0d0d0;">添加賬戶</td><td style="border: 1px solid #d0d0d0;">

<div class="buttons">
    <button type="submit" class="positive" name="save">
        <img src="hcd/img/apply2.png" alt=""/> 
        Save
    </button>
	<a href="javascript:void();" onclick="closeart();"  class="negative"><!-- class="regular"-->
        <img src="hcd/img/cross.png" alt=""/> 
        Cancel
    </a>
	<a href="javascript:void();" onclick="window.open('<?php echo HF_HOST;?>','_blank');"  class="regular"><!-- class="regular"-->
        <img src="hcd/img/textfield_key.png" alt=""/> 
        Main
    </a>
</div>		




</td></tr>
<tr><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="alert('用户的UID')">UID</a>:</td><td style="border: 1px solid #d0d0d0;"><input name="auid" id="auid" style="width:310px" value="" /></td></tr>
<tr><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="alert('PHPSESSID')">Sid</a>:</td><td style="border: 1px solid #d0d0d0;"><input name="asid" id="asid" style="width:310px" value="" /></td></tr>

<tr><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="alert('有链接为u,无连接为n')">Type</a>:</td><td style="border: 1px solid #d0d0d0;"><input id="atype" name="atype" style="width:310px" value=""  /></td></tr>

<tr><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="alert('头像图片的url')">Pic</a>:</td><td style="border: 1px solid #d0d0d0;"><input name="apic" id="apic" style="width:310px" value="" /></td></tr>
</table>
</form></div>
<form id="dd" action="" method="post"><input value="1" style="display:none;" name="default"></form>
<div align="center" id="box4" style="display:none;">
<form method="post" action="">
<table >
<tr><td style="border: 1px solid #d0d0d0;">服務配置</td><td style="border: 1px solid #d0d0d0;">
<div align="center">

<div class="buttons">
    <button type="submit" onclick="art.dialog.tips('重启服務後生效！');" class="positive" name="save">
        <img src="hcd/img/apply2.png" alt=""/> 
        Save
    </button>
	<a href="javascript:void();" onclick="closeart();"  class="negative"><!-- class="regular"-->
        <img src="hcd/img/cross.png" alt=""/> 
        Cancel
    </a>
	
	<a href="javascript:void();" onclick="document.getElementById('dd').submit();"  class="negative"><!-- class="regular"-->
        <img src="hcd/img/textfield_key.png" alt=""/> 
        Default
    </a>
	
</div>	





</div>

</td><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="document.getElementById('debug').value='<?php echo DEBUG;?>'">DEBUG</a>:</td><td style="border: 1px solid #d0d0d0;"><input name="debug" id="debug" style="width:250px" value="<?php echo DEBUG;?>" /></td></tr>
<tr><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="document.getElementById('hfhost').value='<?php echo HF_HOST;?>'">HF_HOST</a>:</td><td style="border: 1px solid #d0d0d0;"><input name="hfhost" id="hfhost" style="width:250px" value="<?php echo HF_HOST;?>" /></td><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="document.getElementById('hfproxy').value='<?php echo HF_PROXY;?>'">HF_PROXY</a>:</td><td style="border: 1px solid #d0d0d0;"><input name="hfproxy" id="hfproxy" style="width:250px" value="<?php echo HF_PROXY;?>" /></td></tr>
<tr><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="document.getElementById('runpage').value='<?php echo RUN_PAGE;?>'">RUN_PAGE</a>:</td><td style="border: 1px solid #d0d0d0;"><input name="runpage" id="runpage" style="width:250px" value="<?php echo RUN_PAGE;?>" /></td><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="document.getElementById('remail').value='<?php echo REMAIL;?>'">REMAIL</a>:</td><td style="border: 1px solid #d0d0d0;"><input id="remail" name="remail" style="width:250px" value="<?php echo REMAIL;?>"  /></td></tr>
<tr><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="document.getElementById('emhost').value='<?php echo EMHOST;?>'">EMHOST</a>: </td><td style="border: 1px solid #d0d0d0;"><input name="emhost" id="emhost" style="width:250px" value="<?php echo EMHOST;?>"  /></td><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="document.getElementById('semail').value='<?php echo SEMAIL;?>'">SEMAIL</a>:</td><td style="border: 1px solid #d0d0d0;"><input name="semail" id="semail" style="width:250px" value="<?php echo SEMAIL;?>" /></td></tr>

<tr><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="document.getElementById('sepw').value='<?php echo SEPW;?>'">SEPW</a>:</td><td style="border: 1px solid #d0d0d0;"><input id="sepw" name="sepw" style="width:250px" value="<?php echo SEPW;?>"  /></td><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="document.getElementById('femail').value='<?php echo FEMAIL;?>'">FEMAIL</a>: </td><td style="border: 1px solid #d0d0d0;"><input name="femail" id="femail" style="width:250px" value="<?php echo FEMAIL;?>"  /></td></tr>

<tr><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="document.getElementById('timerun').value='<?php echo TIME_RUN;?>'">TIME_RUN</a>:</td><td style="border: 1px solid #d0d0d0;"><input name="timerun" id="timerun" style="width:250px" value="<?php echo TIME_RUN;?>" /></td><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="document.getElementById('timebetop').value='<?php echo TIME_BETOP;?>'">TIME_BETOP</a>:</td><td style="border: 1px solid #d0d0d0;"><input name="timebetop" id="timebetop" style="width:250px" value="<?php echo TIME_BETOP;?>" /></td></tr>

<tr><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="document.getElementById('timearmy').value='<?php echo TIME_ARMY;?>'">TIME_ARMY</a>:</td><td style="border: 1px solid #d0d0d0;"><input name="timearmy" id="timearmy" style="width:250px" value="<?php echo TIME_ARMY;?>" /></td><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="document.getElementById('timeall').value='<?php echo TIME_ALL;?>'">TIME_ALL</a>:</td><td style="border: 1px solid #d0d0d0;"><input name="timeall" id="timeall" style="width:250px" value="<?php echo TIME_ALL;?>" /></td></tr>

<tr><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="document.getElementById('phone').value='<?php echo PHONE;?>'">PHONE</a>:</td><td style="border: 1px solid #d0d0d0;"><input name="phone" id="phone" style="width:250px" value="<?php echo PHONE;?>" /></td><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="document.getElementById('ftpw').value='<?php echo FTPW;?>'">FTPW</a>:</td><td style="border: 1px solid #d0d0d0;"><input id="ftpw" name="ftpw" style="width:250px" value="<?php echo FTPW;?>"  /></td></tr>

<tr><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="document.getElementById('user').value='<?php echo USER;?>'">USER</a>:</td><td style="border: 1px solid #d0d0d0;"><input name="user" id="user" style="width:250px" value="<?php echo USER;?>" /></td><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="document.getElementById('passwd').value='<?php echo PASSWD;?>'">PASSWD</a>:</td><td style="border: 1px solid #d0d0d0;"><input name="passwd" id="passwd" style="width:250px" value="<?php echo PASSWD;?>" /></td></tr>
</table>
</form></div><script>var sz=0;</script><div id="toTop" onclick="if(!sz){scroll(0,document.body.scrollHeight);sz=1;}else{scroll(0,0);sz=0;}">&nbsp;↑ ↓&nbsp;</div>
</body>
</html>