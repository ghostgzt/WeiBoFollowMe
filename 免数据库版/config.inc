<?php
//--- user ---//
define(USER,"USER");
define(PASSWD,"PASSWD");
//--- web ---//
define(HOME_PATH,"http://".$_SERVER["HTTP_HOST"].str_replace('//','/',str_replace($_SERVER['DOCUMENT_ROOT'],'',str_replace('/mnt','',str_replace('\\','/',getcwd())).'/')));
//--- send_msg ---//
define(PHONE,"PHONE");
define(FTPW,"FTPW");
//--- send_email ---//
define(REMAIL,"REMAIL");
define(EMHOST,"EMHOST");
define(SEMAIL,"SEMAIL");
define(SEPW,"SEPW");
define(FEMAIL,"FEMAIL");
//--- time ---//
define(TIME_RUN,"TIME_RUN");
define(TIME_BETOP,"TIME_BETOP");
define(TIME_ARMY,"TIME_ARMY");
define(TIME_ALL,"TIME_ALL");
//--- run_page ---//
define(RUN_PAGE,"RUN_PAGE");
//--- hf_host ---//
define(HF_HOST,"HF_HOST");
define(HF_PROXY,"HF_PROXY");//'http://95-31-19-43.broadband.corbina.ru:8080'
//--- debug ---//
define(DEBUG,"DEBUG");
//--- time_zone ---//
date_default_timezone_set("PRC");
?>