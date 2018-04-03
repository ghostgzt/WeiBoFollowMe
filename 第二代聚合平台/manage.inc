<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css' />
<link href='http://fonts.googleapis.com/css?family=Shanti' rel='stylesheet' type='text/css' />
<link rel="stylesheet" href="hcd/css/style.css" />
<link rel="stylesheet" href="hcd/css/button.css" />
<script>
var corn='<?php echo HF_CRON;?>';
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
art.dialog.load(corn+'/signin.php?uid='+uid,{lock: true});
}

function tickout(){
art.dialog.load(corn+'/signin.php',{lock: true});
}

function runshow(url){
art.dialog.load(url,{lock: true,init: function () {setTimeout('document.getElementById("xd").submit()',1000);}});
}

function runsevice(uid,sname){
runshow(corn+'/restart_'+sname+'.php?uid='+uid);
}
function closesevice(uid,sname){
runshow(corn+'/close_'+sname+'.php?uid='+uid);
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
document.getElementById('int').value=document.getElementById(uid).rows[1].cells[6].innerHTML;
document.getElementById('page').value=document.getElementById(uid).rows[1].cells[7].innerHTML;
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
 art.dialog.load('loadlogs.php?del=1'+'&corn=<?php echo getcorn(HF_CRON);?>', {lock: true});
}, function(){
    art.dialog.tips('你取消了操作');
});
}
    function getlogs(uid){
    art.dialog.open('loadlogs.php?uid='+uid+'&corn=<?php echo getcorn(HF_CRON);?>',
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
		url: corn+"/points.php?point=1&sid="+sid,
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
function tgpoint(uid,sid){
	$.ajax({
		url: corn+"/points.php?tgpoint=1&sid="+sid,
		data: "",
		type: "GET",
		success: function( response ){
			if( response ) {
			var throughBox = art.dialog.through;
throughBox({
    content: document.getElementById('box5').innerHTML,
    lock: true
});
document.getElementById('tgsid').innerHTML=sid;
document.getElementById('tguid').innerHTML=uid;
document.getElementById('tgpoint').value=response;
			}
		},
		error: function(){
		art.dialog.tips('获取失败，请重试！！！', 1.5);
			
		}
	});
}
function posttg(sid,point){
point=point.replace(/﻿/ig,'');
	$.ajax({
		url: corn+"/points.php?postpoint="+point+"&sid="+sid,
		data: "",
		type: "GET",
		success: function( response ){
		closeart();
		response=response.replace(/﻿/ig,'');
		response=JSON.parse(response);
			if( response.status ) {
art.dialog.tips('修改成功！！！', 1.5);
			}else{
			art.dialog.tips('获取失败，请重试！！！<br/>'+response.info, 1.5);
			}
		},
		error: function(){
		art.dialog.tips('获取失败，请重试！！！', 1.5);
			
		}
	});
}
</script>
</head>
<body style="background:url(hcd/img/B304B.png);">
<iframe style="margin-top:-20px" iframeborder="0" srolling="no" height="150px" width="100%" src="index.php?icon=<?php echo getcorn(HF_CRON);?>"></iframe><br/><br/>
<div align="center">
<div style="width:1080px;margin-top:-20px;background:rgba(20, 16, 16, 0.67);">
<br/>
<br/>
<?php
function ym(){
@include('api_list.php');for($i=0;$i<count($api_list);$i++){$ssr=$api_list[$i];if(HF_HOST==$ssr['url']){if($ssr['type']=='w'){return "weibo";}else{return "t.qq";}}}
}
@include('api_list.php');for($i=0;$i<count($api_list);$i++){$ssr=$api_list[$i];if(HF_HOST==$ssr['url']){$dqtitle=$ssr['name'];}}
echo '<h1 style="color:white;">'.$dqtitle.'</h1><span style="font-size:14px;">[<a href="javascript:void();" onclick="art.dialog.open(\'gftp.php?op=edit&fename=api_list.php&folder=./\', {title: \'Gentle FTP\', width: 800, height: 500,lock: true});">编辑</a>][<a href="javascript:void();" onclick="art.dialog.open(\'gftp.php?op=edit&fename=post_list.php&folder=./'.HF_CRON.'/\', {title: \'Gentle FTP\', width: 800, height: 500,lock: true});">转发语</a>]</span>';
echo '<h1>互粉服务器: '.str_replace("_sinaapp_com","", SQL_TABLE).'&nbsp;<a style="color:white" title="更新代理" href="javascript:void();" onclick="runshow(\'iproxy.php?corn='.getcorn(HF_CRON).'\');">+</a>&nbsp;<a style="color:white" title="检查代理" href="javascript:void();" onclick="runshow(\'iproxy.php?corn='.getcorn(HF_CRON).'&check=1\');">+</a></h1>';
if(!file_exists(HF_CRON.'/config_proxy.php')){file_put_contents(HF_CRON.'/config_proxy.php','<?php $i_proxy=0; ?>');}
@include_once(HF_CRON.'/config_proxy.php');
if($i_proxy&&HF_PROXY&&HF_PROXY!='HF_PROXY'){
echo '<h1>代理服务器: <a href="javascript:void();" title="自动检测" onclick="runshow(\'/iproxy.php?corn='.getcorn(HF_CRON).'\');">'.$i_proxy.'</a>&nbsp;<a style="color:black" title="取消代理" href="javascript:void();" onclick="runshow(\'/iproxy.php?corn='.getcorn(HF_CRON).'&del=1\');">X</a></h1>';
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
	<th>SID</th>
    <th>Type</th>
	<th>Points</th>
	<th>Interval</th>
	<th>Pages</th>
	<th>Operation</th>
  </tr>
  <tr>
  <td>'.(checkstatus($arr[$i]['uid'],'run',TIME_ALL) ? "<a title='轉發服務' href='javascript:void();' onclick='closesevice(\"".$arr[$i]['uid']."\",\"run\");'><img src='hcd/img/apply2.png' height='16px' width='16px' /></a>" : "<a title='轉發服務' href='javascript:void();' onclick='runsevice(\"".$arr[$i]['uid']."\",\"run\");'><img src='hcd/img/cross.png' height='16px' width='16px' /></a>" ).'</td>
  <td><a title="打開此微博" target="_blank" href="http://'.ym().'.com/'.$arr[$i]['uid'].'"><img src="'.urldecode($arr[$i]['pic']).'"/></a></td><td>'.$arr[$i]['uid'].'</td> <td>'.$arr[$i]['sid'].'</td>  <td>'.$arr[$i]['type'].'</td>  <td><a id="p_'.$arr[$i]['uid'].'" href="javascript:void();" onclick="getpoints(\''.$arr[$i]['sid'].'\',\'p_'.$arr[$i]['uid'].'\');">-<script>getpoints("'.$arr[$i]['sid'].'","p_'.$arr[$i]['uid'].'");</script></a></td>

  <td>'.$arr[$i]['int'].'</td>  <td style="word-break:break-all">'.$arr[$i]['page'].'</td>  
  
  <td><a href="javascript:void();" onclick="sg(\''.$arr[$i]['uid'].'\');">修改</a>&nbsp;<a href="javascript:void();" onclick="del(\''.$arr[$i]['uid'].'\');">刪除</a>&nbsp;<a href="javascript:void();" onclick="tick(\''.$arr[$i]['uid'].'\');">簽到</a>&nbsp;<a href="javascript:void();" onclick="tgpoint(\''.$arr[$i]['uid'].'\',\''.$arr[$i]['sid'].'\');">推广</a>&nbsp;<a href="javascript:void();" onclick="getlogs(\''.$arr[$i]['uid'].'\');">日誌</a></td>
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
    <th><form id="xd" action="" method="post"><input name="corn" style="display:none;" value="<?php echo getcorn(HF_CRON);?>" /><input value="1" style="display:none;" name="refresh"><a href="javascript:void();" onclick="document.getElementById('xd').submit();">刷新列表</a></form></th>
	<th><a href="javascript:void();" onclick="tickout();">每日签到</a></th>
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
  <td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="runshow('control.php?type=check');">一键检查</a></td> 
  <td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="runshow('control.php?type=run');">全部啟動</a></td> 
  <td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="runshow('control.php?type=restart');">全部啟動</a></td>
  <td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="runshow('control.php?type=close');">全部終止</a></td>
    </tr>
  <tr>
  <td style="border: 1px solid #d0d0d0;"><?php echo $dqtitle;?></td>
  <td style="border: 1px solid #d0d0d0;"><?php @include_once( HF_CRON.'/config_run.php' );if ($kg){echo '運行標記';}else{echo '終止標記';}?></td> 
  <td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="runshow(corn+'/xcorn_run.php');">啟動轉發</a></td> 
  <td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="runshow(corn+'/restart_run.php');">重新啟動</a></td>
  <td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="runshow(corn+'/close_run.php');">終止轉發</a></td>
    </tr>
</table>
</div>
<br/>
<br/>
<br/>
<h2><a href="http://www.gwfs.ml/" target="_blank" title="天狼星の破曉">Made By Gentle In 2013</a></h2>
<br/>
</div>
</div>
<div align="center" id="box1" style="display:none;">

<form method="post" action="">
<table><input id="uid" name="uid" style="display:none;width:250px" value=""  />
<input name="corn" style="display:none;" value="<?php echo getcorn(HF_CRON);?>" />
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
	<a href="javascript:void();" onclick="window.open('<?php echo HF_HOST;?>','_blank');" style="margin-right:0px;float:right;" class="regular"><!-- class="regular"-->
        <img src="hcd/img/textfield_key.png" alt=""/> 
        Main
    </a>

</div>	
</td></tr>
<tr><td style="border: 1px solid #d0d0d0;">UID:</td><td style="border: 1px solid #d0d0d0;"><span id='did'></span></td></tr>
<tr><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="alert('复制粘贴PHPSESSID');">SID</a>:</td><td style="border: 1px solid #d0d0d0;"><input name="sid" id="sid" style="width:310px" value="" /></td></tr>

<tr><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="alert('指定有无链接,不一定有效')">Type</a>:</td><td style="border: 1px solid #d0d0d0;">
<select style="width:315px;border:0;" name="type" id="type" value=""><option value="n">无链接</option><option value="u">有链接</option></select>
</td></tr>
<tr><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="alert('运行的间隔时间')">Interval</a>:</td><td style="border: 1px solid #d0d0d0;"><input name="int" id="int" style="width:310px" value="" /></td></tr>
<tr><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="alert('潜行页数')">Pages</a>:</td><td style="border: 1px solid #d0d0d0;"><input name="page" id="page" style="width:310px" value="" /></td></tr>
<tr><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="alert('头像图片网络链接')">Pic</a>:</td><td style="border: 1px solid #d0d0d0;"><input name="pic" id="pic" style="width:310px" value="" /></td></tr>
<tr>
</table>
</form></div>
<div align="center" id="box2" style="display:none;">
確定刪除?
<form method="post" action="">
<input id="xuid" name="xuid" readonly="true" style="width:155px" value=""  />
<input name="corn" style="display:none;" value="<?php echo getcorn(HF_CRON);?>" />
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
<input name="corn" style="display:none;" value="<?php echo getcorn(HF_CRON);?>" />
<div class="buttons">
    <button type="submit" class="positive" name="save">
        <img src="hcd/img/apply2.png" alt=""/> 
        Save
    </button>
	<a href="javascript:void();" onclick="closeart();"  class="negative"><!-- class="regular"-->
        <img src="hcd/img/cross.png" alt=""/> 
        Cancel
    </a>
	<a href="javascript:void();" onclick="window.open('<?php echo HF_HOST;?>','_blank');" style="margin-right:0px;float:right;" class="regular"><!-- class="regular"-->
        <img src="hcd/img/textfield_key.png" alt=""/> 
        Main
    </a>

</div>		




</td></tr>
<tr><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="alert('微博的UID')">UID</a>:</td><td style="border: 1px solid #d0d0d0;"><input name="auid" id="auid" style="width:310px" value="" /></td></tr>
<tr><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="alert('复制粘贴PHPSESSID');">SID</a>:</td><td style="border: 1px solid #d0d0d0;"><input name="asid" id="asid" style="width:310px" value="" /></td></tr>

<tr><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="alert('指定有无链接,不一定有效')">Type</a>:</td><td style="border: 1px solid #d0d0d0;">
<select style="width:315px;border:0;" name="atype" id="atype" value=""><option value="n">无链接</option><option value="u">有链接</option></select>
</td></tr>
<tr><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="alert('运行的间隔时间')">Interval</a>:</td><td style="border: 1px solid #d0d0d0;"><input name="aint" id="aint" style="width:310px" value="" /></td></tr>
<tr><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="alert('潜行页数')">Pages</a>:</td><td style="border: 1px solid #d0d0d0;"><input name="apage" id="apage" style="width:310px" value="" /></td></tr>
<tr><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="alert('头像图片网络链接')">Pic</a>:</td><td style="border: 1px solid #d0d0d0;"><input name="apic" id="apic" style="width:310px" value="" /></td></tr>
</table>
</form></div>
<form id="dd" action="" method="post"><input value="1" style="display:none;" name="default"><input name="corn" style="display:none;" value="<?php echo getcorn(HF_CRON);?>" /></form>
<div align="center" id="box4" style="display:none;">
<form method="post" action="">
<input name="corn" style="display:none;" value="<?php echo getcorn(HF_CRON);?>" />
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

</td><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="document.getElementById('debug').value='<?php echo DEBUG;?>'">DEBUG</a>:</td><td style="border: 1px solid #d0d0d0;"><select style="width:255px;border:0;" name="debug" id="debug" value="<?php echo DEBUG;?>"><option value="1" <?php 
if(DEBUG){echo 'selected';}?> >开启</option><option value="0" <?php 
if(!DEBUG){echo 'selected';}?> >关闭</option></select></td></tr>
<tr><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="document.getElementById('remail').value='<?php echo REMAIL;?>'">REMAIL</a>:</td><td style="border: 1px solid #d0d0d0;"><input id="remail" name="remail" style="width:250px" value="<?php echo REMAIL;?>"  /></td>
<td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="document.getElementById('hfproxy').value='<?php echo HF_PROXY;?>'">HF_PROXY</a>:</td><td style="border: 1px solid #d0d0d0;"><input name="hfproxy" id="hfproxy" style="width:250px" value="<?php echo HF_PROXY;?>" /></td></tr>
<tr><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="document.getElementById('emhost').value='<?php echo EMHOST;?>'">EMHOST</a>: </td><td style="border: 1px solid #d0d0d0;"><input name="emhost" id="emhost" style="width:250px" value="<?php echo EMHOST;?>"  /></td><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="document.getElementById('semail').value='<?php echo SEMAIL;?>'">SEMAIL</a>:</td><td style="border: 1px solid #d0d0d0;"><input name="semail" id="semail" style="width:250px" value="<?php echo SEMAIL;?>" /></td></tr>

<tr><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="document.getElementById('sepw').value='<?php echo SEPW;?>'">SEPW</a>:</td><td style="border: 1px solid #d0d0d0;"><input id="sepw" name="sepw" style="width:250px" value="<?php echo SEPW;?>"  /></td><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="document.getElementById('femail').value='<?php echo FEMAIL;?>'">FEMAIL</a>: </td><td style="border: 1px solid #d0d0d0;"><input name="femail" id="femail" style="width:250px" value="<?php echo FEMAIL;?>"  /></td></tr>


<tr><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="document.getElementById('phone').value='<?php echo PHONE;?>'">PHONE</a>:</td><td style="border: 1px solid #d0d0d0;"><input name="phone" id="phone" style="width:250px" value="<?php echo PHONE;?>" /></td><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="document.getElementById('ftpw').value='<?php echo FTPW;?>'">FTPW</a>:</td><td style="border: 1px solid #d0d0d0;"><input id="ftpw" name="ftpw" style="width:250px" value="<?php echo FTPW;?>"  /></td></tr>

<tr><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="document.getElementById('user').value='<?php echo USER;?>'">USER</a>:</td><td style="border: 1px solid #d0d0d0;"><input name="user" id="user" style="width:250px" value="<?php echo USER;?>" /></td><td style="border: 1px solid #d0d0d0;"><a href="javascript:void();" onclick="document.getElementById('passwd').value='<?php echo PASSWD;?>'">PASSWD</a>:</td><td style="border: 1px solid #d0d0d0;"><input name="passwd" id="passwd" style="width:250px" value="<?php echo PASSWD;?>" /></td></tr>
</table>
</form>
</div>
<div align="center" id="box5" style="display:none;">
修改推广积分&nbsp;<span id="tguid" name="uid"></span><span style="display:none;" id="tgsid" name="uid"></span>
<br/>
<input id="tgpoint" name="point" style="width:155px" value=""  />
<div class="buttons">
    <button type="submit" class="positive" name="save" onclick="posttg(document.getElementById('tgsid').innerHTML,document.getElementById('tgpoint').value);">
        <img src="hcd/img/apply2.png" alt=""/> 
        Save
    </button>
	<a href="javascript:void();" onclick="closeart();"  class="negative"><!-- class="regular"-->
        <img src="hcd/img/cross.png" alt=""/> 
        Cancel
    </a>
</div>
</div>
<script>var sz=0;</script><div id="toTop" onclick="if(!sz){scroll(0,document.body.scrollHeight);sz=1;}else{scroll(0,0);sz=0;}">&nbsp;↑ ↓&nbsp;</div>
</body>
</html>