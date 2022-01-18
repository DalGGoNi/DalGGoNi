<?php if(!defined("_INCLUDE_")) require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/include.php";
//@ini_set("display_errors", "On");
//@error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
//@error_reporting(E_ALL);
require_once _LIB_PATH_ . "nav_page.class.php";
$nav = new NavPage;
?><!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="ko"> <![endif]-->
<!--[if IE 7]> <html class="lt-ie9 lt-ie8" lang="ko"> <![endif]-->
<!--[if IE 8]> <html class="lt-ie9" lang="ko"> <![endif]--> 
<!--[if gt IE 8]><!--><html class="no-js" lang="ko"><!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!--<meta http-equiv="refresh" content="0;url=http://www.daum.net">-->
        <title>List Page</title>
        <link rel="stylesheet" type="text/css" href="http://www.htenc.co.kr/css/style.css">
        <style type="text/css">
            .styleguide th
            {
                text-align: center;
                vertical-align:middle;
                justify-content:center;
                align-items:center;
            }
            .styleguide td
            {
                padding: 5px;
                text-align: left;
                vertical-align:middle;
                
            }
        </style>
    </head>
<body>
<?php
$db = new DB;
$_table_user = "EX_USER_SET";
$SQL = "SELECT COUNT(0) AS CNT FROM {$_table_user}";
$count = $db->query_one($SQL);
$strNavPageBar = $nav->navpage($count);
?>
<div class="styleguide">
    <table summary="Project Reference">
        <colgroup>
            <col style="width:50px" />
            <col style="width:120px" />
            <col style="width:200px" />
            <col style="width:200px" />
            <col />
        </colgroup>
        <caption>사업 Reference</caption>
        <thead>
        <tr>
            <th>No.</th>
            <th>ID</th>
            <th>사용자명</th>
            <th>부서코드</th>
            <th>비고</th>
        </tr>
        </thead>
        <tbody>
<?php
//$db->Debug = true;
if($count > 0)
{
    //$nav->start(2, 3);
    $SQL = "SELECT * FROM {$_table_user}";
    $db->query_limit($SQL, $nav->start_row, $nav->row_scale);
    
    $nCurrRow = $db->nf();
    $i = 0;
    while($db->next_record())
    {
        $rec = $db->Record;
        $index = $count - $nav->start_row - $i;
?>
        <tr>
            <td style="text-align: center;"><?php echo $index; ?></td>
            <td><?php echo $db->f("user_id"); ?></td>
            <td><?php echo $db->f("user_name"); ?></td>
            <td style="text-align: center;"><?php echo $rec["dept_id"]; ?></td>
            <td><?php echo $rec["user_name"]?></td>
        </tr>
<?php
        $i++;
    }
}
else
{
?>
        <tr>
            <td style="text-align: center;" colspan="5">직원 정보를 찾을수가 없습니다.</td>
        </tr>
<?php
}
?>
        </tbody>
    </table>
</div>
</body>
</html>