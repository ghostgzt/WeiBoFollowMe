<?php
///--- 数据库初始化 ---///
//互粉大厅
if(SQL_TABLE=='hufen123_sinaapp_com'){
if(!readrow(array(
  ))){
  refsql(array(
'uid'=>'3203151133','data'=>"sid='63073aa051f7a9b9e24d7bdbcc921cd2',type='b',page='1',pic='".urlencode('http://tp2.sinaimg.cn/3203151133/50/40014504420/0')."'"
  ));

refsql(array(
'uid'=>'1843533784','data'=>"sid='8a276ea265739ab2481efc1d16238ebc',type='b',page='1',pic='".urlencode('http://tp1.sinaimg.cn/1843533784/50/40005440189/1')."'"
  ));

refsql(array(
'uid'=>'3145790273','data'=>"sid='f1655bb9d6df5b998b41116924afc836',type='b',page='1',pic='".urlencode('http://tp2.sinaimg.cn/3145790273/50/40016051921/1')."'"
  ));
  }
}
//互粉派对
if(SQL_TABLE=='weibo123_sinaapp_com'){
  if(!readrow(array(
  ))){
  refsql(array(
'uid'=>'3203151133','data'=>"sid='cb3d2edb2aa0cc3ac985114f5f3577a3',type='b',page='1',pic='".urlencode('http://tp2.sinaimg.cn/3203151133/50/40014504420/0')."'"
  ));

refsql(array(
'uid'=>'1843533784','data'=>"sid='2ab93cf73f634afbab943757a5675c90',type='b',page='1',pic='".urlencode('http://tp1.sinaimg.cn/1843533784/50/40005440189/1')."'"
  ));

refsql(array(
'uid'=>'3145790273','data'=>"sid='87acb4c0b268ae132efff14be71e5e98',type='b',page='1',pic='".urlencode('http://tp2.sinaimg.cn/3145790273/50/40016051921/1')."'"
  ));
  }
}
///--- 数据库初始化 ---///
?>