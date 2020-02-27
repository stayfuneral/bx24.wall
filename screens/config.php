<?php

global $SQL_HOST,$SQL_USER,$SQL_PASSWORD,$SQL_DATABASE,$db;

$SQL_HOST='localhost';
$SQL_USER='oriflame_vote';
$SQL_PASSWORD='QQc2ElSm';
$SQL_DATABASE='oriflame_voting';
$addon="";

$LIMIT_LINES=10;
define("USESEO",101);
define("USEGD",0);
define("WIDTH",350);
define("LARGEPREFIX",'large_');
define("LARGEWIDTH",600);
define("BANNERWIDTH",1920);
define("SMALLPREFIX",'sm_');
define("SMALLWIDTH",198);
define("COLORPREFIX",'cl_');
define("COLORWIDTH",100);
define("HOMEPREFIX",'hp_');
define("HOMEWIDTH",516);
define("HOMEHEIGHT",323);
define("FEATPREFIX",'feat_');
define("FEATWIDTH",293);
define("TOPWIDTH",940);

$waytocfg=$_SERVER[DOCUMENT_ROOT].$addon.'/admin';
$website_name='http://'.$_SERVER[SERVER_NAME].$addon;
$manageable_images_dirroot=$_SERVER[DOCUMENT_ROOT]."/pictures/";
$manageable_images_dirweb="/pictures/";

require_once $waytocfg.'/_inc/db_mysql.php';
require_once $waytocfg.'/_inc/functions.php';
//require_once $waytocfg.'/_inc/validate.inc.php';
require_once $waytocfg.'/_inc/class.file_upload.php';

db_connect();
#$db->query("SET NAMES 'cp1251'");
$db->query("set names 'utf8'");
$general=array();
$days=array(1=>'Пн',2=>'Вт',3=>'Ср',4=>'Чт',5=>'Пт',6=>'Сб',7=>'Вс');
session_start();
if (is_array($_SESSION[basket][items])) {
 $_SESSION[basket][qty_total]=$_SESSION[basket][total]=0;
 foreach ($_SESSION[basket][items] as $b=>$basket_product) {
  $_SESSION[basket][qty_total]+=$basket_product['qty'];
  $_SESSION[basket][total]+=$basket_product['qty']*$basket_product['price'];
 }
}
?>