<?php

//--- sql ---//
define(SQL_HOST,"localhost");
define(SQL_USER,"root");
define(SQL_PASSWD,"");
define(SQL_DB,"tus");
define(SQL_TABLE,str_replace('.','_',str_replace('/','',str_replace('http://','',HF_HOST))));
//@include( 'sql/'. SQL_TABLE.'.inc');
?>