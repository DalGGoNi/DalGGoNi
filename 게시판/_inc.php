<?php
//define(_EX_USER_SET_, "EX_USER_SET");
$_table_user = "EX_USER_SET";
$_table_dept = "V_EX_DEPT_SET";
$_table_duty = "EX_DUTY_SET";

$_query_string = Fun::getParamUrl("uno,mode");
$_list_uri = "jhpark_list.php?" . $_query_string;
$_view_uri = "jhpark_view.php?" . $_query_string;
$_modify_uri = "jhpark_write.php?mode=modify" . $_query_string;
$_write_uri = "jhpark_write.php?mode=write" . $_query_string;