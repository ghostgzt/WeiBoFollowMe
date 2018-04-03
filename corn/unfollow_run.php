<?php
include_once( '../config.php' );
include( 'hfpd_api.php' );
$htm = HOME_PATH;
if($_GET['run']){
if($_GET['uid']){
ffopen($htm.'unfollow.php?uid='.$_GET['uid']);die($_GET['uid'].' 已經佈置檢測計劃！'.'<br/>');
}else{
ffopen($htm.'unfollow.php');die('所有帳號已經佈置檢測計劃！'.'<br/>');
}
}
if($_GET['stop']){
if($_GET['uid']){
ffopen($htm.'unfollow.php?stop=1&uid='.$_GET['uid']);die($_GET['uid'].' 已經佈置終止計劃！'.'<br/>');
}else{
ffopen($htm.'unfollow.php?stop=1');die('所有帳號已經佈置終止計劃！'.'<br/>');
}
ffopen($htm.'unfollow.php');die('所有帳號已經佈置檢測計劃！'.'<br/>');
}