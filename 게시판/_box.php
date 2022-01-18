<?php
//define(_EX_USER_SET_, "EX_USER_SET");
$_table_user = "EX_USER_SET";
$_table_dept = "V_EX_DEPT_SET";
$_table_duty = "EX_DUTY_SET";
$_table_test = "EX_TEST";

$_query_string = Fun::getParamUrl("uno,mode");
$_list_uri = "list.php?" . $_query_string;
$_view_uri = "contents.php?" . $_query_string;
$_modify_uri = "contents_add.php?mode=modify" . $_query_string;
$_write_uri = "contents_add.php?mode=write" . $_query_string;
$_write_uri1 = "contents_add.php?mode=write1" . $_query_string;
$_write_uri2 = "study_add.php?mode=write1" . $_query_string;
$_login_uri = "new_member.php?mode=write" . $_query_string;
$_login_midify_uri="new_member.php?mode=modify".$_query_string;
$_pre_uri = "action.php?mode=pre".$_query_string;
$_odd_uri = "action.php?mode=odd".$_query_string;
$_insert_uri= "insert.php?" . $_query_string;
$_action_uri = "action.php?mode=like" . $_query_string;
$_query_string1 = Fun::getParamUrl("num,mode");
$_delete_uri = "action.php?mode=delete" . $_query_string1;
$_modify_comment_uri = "comment_modify.php" . $_query_string1;
$_delete_content_uri = "action.php?mode=delete_content" . $_query_string;
$_delete_id_uri = "login_delete.php?mode=delete_id" . $_query_string;
$_delete_file_uri = "action.php?mode=delete_file" . $_query_string;