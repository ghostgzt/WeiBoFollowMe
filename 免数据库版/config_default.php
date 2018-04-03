<?php
//--- user ---//
define(USER,"Admin");
define(PASSWD,"admin");
//--- web ---//
define(HOME_PATH,"http://".$_SERVER["HTTP_HOST"].str_replace('//','/',str_replace($_SERVER['DOCUMENT_ROOT'],'',str_replace('/mnt','',str_replace('\\','/',getcwd())).'/')));
//--- send_msg ---//
define(PHONE,"13XXXXXXXXX");
define(FTPW,"XXXXXX");
//--- send_email ---//
define(REMAIL,"XXXXXX@qq.com");
define(EMHOST,"smtp.qq.com");
define(SEMAIL,"XXXXXX@qq.com");
define(SEPW,"XXXXXX");
define(FEMAIL,"XXXXXX@qq.com");
//--- time ---//
define(TIME_RUN,900);
define(TIME_BETOP,600);
define(TIME_ARMY,1200);
define(TIME_ALL,60);
//--- run_page ---//
define(RUN_PAGE,3);
//--- hf_host ---//
define(HF_HOST,"http://hufen123.sinaapp.com/");
define(HF_PROXY,"0");//'http://95-31-19-43.broadband.corbina.ru:8080'
//--- debug ---//
define(DEBUG,1);
//--- time_zone ---//
date_default_timezone_set("PRC");
?>