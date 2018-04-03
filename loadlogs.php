<?php if($_GET['del']=='1'){include_once('lib_tools.php');deldir('corn/log');creatdir('corn/log');creatdir('corn/log/army');creatdir('corn/log/run');creatdir('corn/log/betop');creatdir('corn/log/unfollow');die('所有服務日誌已經被清空~');} ?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <title>Load Logs</title>
	<script>var id='<?php if($_GET['uid']){echo $_GET['uid'];}else{die('缺少参数');}?>';</script>
	  <script src="hcd/js/jquery.js"></script>
    <script src="hcd/js/jquery.idTabs.js"></script>
	   <script src="hcd/js/xml.js"></script>
	    <script>
function load(sid,t){
loadXMLDoc('corn/log/'+t+'_'+id+'.txt','content');
}
    </script>
    <style>
      * { margin:0; padding:0; border:0; outline:0; }
      body { font:25pt Tahoma; background:#FFF; color:#000; }
      .idTabs { margin:40px; }
      .idTabs ul { background:#222; padding:5px; float:left; }
      .idTabs li { list-style:none; /*Try deleting this float*/ float:left; }
      .idTabs a { display:block; background:#222; color:snow; padding:0 13px; font:bold 25pt Arial; text-decoration:none; }
      .idTabs a.selected { background:#FFF; color:#000; }
      .items>div { display:none; float:left; margin:0.1em 0 0 0.5em; }
      .idTabs ul, .idTabs a { border-radius:4px; -moz-border-radius:4px; }
    </style>
  </head>
  <body style="color:white;background:black;">
    <div class="idTabs">
      <ul>
        <li><a  href="#one" onclick="load(id,'run/t');">1</a></li>
        <li><a  href="#two" onclick="load(id,'army/a');">2</a></li>
        <!-- Try adding this <br/> tag here -->
        <li><a href="#three" onclick="load(id,'betop/b');">3</a></li>
		<li><a href="#four" onclick="load(id,'unfollow/u');">4</a></li>
       <!--   <li><a href="#four">4</a></li>-->
      </ul>
      <div class="items">
        <div id="one">Corn_Run Logs Of <script>document.writeln(id);</script></div>
        <div id="two">Corn_Army Logs Of <script>document.writeln(id);</script></div>
        <div id="three">Corn_Betop Logs Of <script>document.writeln(id);</script></div>
		<div id="four">Unfollow Logs Of <script>document.writeln(id);</script></div>
      <!--  <div class="four">The only limit,</div>
        <div class="four">is your imagination.</div>-->
      </div>
    </div>
	<br/><br/><div align="center">
	<div align="left" style="font-size: 5pt;margin-left:40px" id="content"></div>
	</div>
	<div style="position:fixed;bottom:5%;right:5%;"><p><a style="text-decoration:none;font-size: 20px;color:white;" href="javascript:void();" onclick="scroll(0,0)">Top</a></p><p><a style="text-decoration:none;font-size: 20px;color:white;" href="javascript:void();" onclick="loadXMLDoc(xurl,xid)">Reload</a></p><p><a style="text-decoration:none;font-size: 20px;color:white;" href="javascript:void();" onclick="scroll(0,document.body.scrollHeight)">Down</a></p><div>
  </body>
</html>
