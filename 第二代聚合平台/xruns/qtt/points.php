<?php 
include( 'apis.php' );
if ($_GET['point']&&$_GET['sid']){
die (getpoints());

}else{
if ($_GET['tgpoint']&&$_GET['sid']){

die (gettgpoints());

}else{
if (isset($_GET['postpoint'])&&$_GET['sid']){
die(postpoints($_GET['sid'],$_GET['postpoint']));
}
}
}
?>