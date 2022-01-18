<?php
@ini_set("display_errors", "On");
@error_reporting(E_ALL);
//@error_reporting(E_ALL & ~E_DEPRECATED);
//@error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
//@error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT); //기본
//@error_reporting(E_ALL ^ E_NOTICE);
//@error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
//@error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE);
//@error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE & ~E_WARNING);
// W3C P3P 규약설정
@header("P3P : CP=\"ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC\"");
@header("Content-Type: text/html; charset=UTF-8");
/* 캐쉬비우는 Header정의 일단 주석처리
@header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
@header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
@header("Cache-Control: no-store, no-cache, must-revalidate");
@header("Cache-Control: post-check=0, pre-check=0", false);
@header("Pragma: no-cache");
*/
//@header("Content-type: text/html; charset=euc-kr");
// Session Start
//@session_cache_limiter('no-cache, must-revalidate');
//@session_start();
$_SERVER["DOCUMENT_ROOT"] = str_replace("\\","/", $_SERVER["DOCUMENT_ROOT"]);
define("_INCLUDE_"          , "Include OK");
define("_LIB_PATH_"         , str_replace("\\","/", dirname(__FILE__)) . "/");
define("_LIB_URL_"          , str_replace("\\", "/", str_replace($_SERVER["DOCUMENT_ROOT"], "", _LIB_PATH_))); // str_replace("\\", "/", "") : Microsoft Windows에서 경로를 \로 표시되는것을 /로 변경....
define("_ROOT_PATH_"        , str_replace("/lib", "", _LIB_PATH_)); // str_replace("\\", "/", "") : Microsoft Windows에서 경로를 \로 표시되는것을 /로 변경....
define("_ROOT_URL_"         , str_replace("/lib", "", _LIB_URL_)); // str_replace("\\", "/", "") : Microsoft Windows에서 경로를 \로 표시되는것을 /로 변경....
define("_STORAGE_"          , _ROOT_PATH_ . "upload/");
define("_HOME_PATH_"        ,  _ROOT_PATH_ . "home/");
define("_HOME_URL_"         ,  _ROOT_URL_ . "home/");
define("_THIS_PATH_"        , dirname($_SERVER["SCRIPT_FILENAME"]) . "/");
define("_THIS_URL_"         , dirname($_SERVER["SCRIPT_NAME"]) . "/");
define("_TPL_LIB_PATH_"     , _LIB_PATH_ . "Template_/");
define("_TEMP_PATH_"        , _ROOT_PATH_ . "../temp/");
define("_TPL_COM_PATH_"     , _TEMP_PATH_ . "Template_/");
define("_DB_Class_"   , "oci8"); // mysql, oracle(oracle 7.x 이하), oci8(oracle 8.x 이상)
define("_DB_Host_"    , false);
define("_DB_User_"    , "EXERCISE1");
define("_DB_Pass_"    , "exe880323");
//define("_DB_Name_"    , "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=testdb.htenc.com)(PORT=1521))(CONNECT_DATA=(SERVICE_NAME=ORCL)))");
define("_DB_Name_"    , "testdb.htenc.com/ORCL");

require_once _LIB_PATH_ . "mime.php";

//require_once _LIB_PATH_ . "db_" . _DB_Class_ . ".inc";
require_once _LIB_PATH_ . "common.class.php";
require_once _LIB_PATH_ . "fun.class.php";
require_once _TPL_LIB_PATH_ . "Template_.class.php";
class Template extends Template_{
    //var $template_dir = _TPL_SKIN_PATH_;
    //var $compile_dir  = _TPL_COM_PATH_ . _THIS_URL_;
    //var $skin         = _tpl_skin_;
    var $prefilter    = "adjustPath";
    var $caching      = true;
}

$Tpl = null;
$User = null;
Fun::init();
/*
if (isset($Fun))
{
    //$Fun = new Fun;
    //global $Fun;
}
if (isset($Fun))
{
    //$post = $Fun->getVars();
}
 */
$post = Fun::getVars();
if(isset($post) != true){
    $post = array();
}

require_once _LIB_PATH_ . "db_" . _DB_Class_ . ".php";
require_once _LIB_PATH_ . "local.php";

//print_r($Fun);
require_once _LIB_PATH_ . "user.class.php";
//require_once _LIB_PATH_ . "page.class.php";