<?php
@ini_set("display_errors", "On");
//@error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
if(!defined("_INCLUDE_")) require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/include.php";
@error_reporting(E_ALL);

$_table_loc_code = "EX_LOC_CODE";
$_query_string = Fun::getParamUrl("uno,mode");
$_list_uri = "list.php?" . $_query_string;
$_view_uri = "view.php?" . $_query_string;
$_modify_uri = "write.php?mode=modify" . $_query_string;
$_write_uri = "write.php?mode=write" . $_query_string;