<?php include __DIR__ . '/_inc.php';
require_once _LIB_PATH_ . "page.class.php";
$page = new Page();
$page->pageLogin();
require_once  _LIB_PATH_ . "nav_page.class.php";
$nav = new NavPage;
$nav->start(5,10);

$db = new DB;
$mWhere = null;
$sel_opt = array();
$search_options = array(
    "loc_no" => "관리번호"
    , "loc_name" => "지역 명칭"
    , "loc_code" => "지역 코드"
);
$search_options_html = "";
foreach($search_options as $_col => $_txt)
{
    $opt_sel = "";
    if(@$post["s_type"] == $_col){
        $opt_sel = " selected";
        $mWhere = " AND {$_col} LIKE '%{$post["s_word"]}%'";
    }
    $search_options_html .= "<option value=\"{$_col}\" {$opt_sel}>{$_txt}</option>";
}

if(@$post["mode"] == "search" && @trim($post["s_word"]) != ""){
    
}

$main_sql = "SELECT * FROM {$_table_loc_code}
    WHERE 1 = 1
    {$mWhere}
";
$sql = "WITH A as (
  {$main_sql}
)
SELECT COUNT(*) AS CNT FROM A";
//echo nl2br(str_replace(" ", "&nbsp;", $sql));
//echo nl2br( str_replace("\t", "&nbsp;&nbsp;&nbsp;&nbsp;", $sql) ); //str_replace, substr, explode, strpos
//Fun::print_($sql);

////$db->query($sql);
//$db->next_record();
//$count = $db->f(0);
$count = $db->query_one($sql);
$str_page_bar = $nav->navpage($count);
//$db->Debug = true;
$sql = "WITH A as (
  {$main_sql}
)
SELECT * FROM A
WHERE 1 = 1 
ORDER BY loc_no desc
";

$db->query_limit($sql, $nav->start_row, $nav->row_scale);
$query_row_count = $db->nf();

$str_data_row = null;
if($query_row_count > 0)
{
    $i = 0;
    while($db->next_record())
    {
        $rec = $db->Record;
        $no = $count - $nav->start_row - $i;
        //$list_query = Fun::getParamUrl();
        $str_data_row .= "              
            <tr>
                <td style=\"text-align: center;\">{$no}</td>
                <td style=\"text-align: left;\"><a href='{$_view_uri}&loc_no={$rec["loc_no"]}'>{$db->f("loc_code")}</a></td>
                <td style=\"text-align: left;padding-left:20px\">{$db->f("loc_name")}</td>
                <td style=\"text-align: center;\">{$rec["loc_code"]}</td>
                <td style=\"text-align: center;\">
                    <img src=\"/lib/images/btn_simple_view.gif\" title=\"조회\" onclick=\"goView({$rec["loc_no"]});\" style=\"cursor: pointer\" />
                    <img src=\"/lib/images/btn_simple_mod.gif\" title=\"수정\" onclick=\"goModify({$rec["loc_no"]});\" style=\"cursor: pointer\" />
                </td>
            </tr>
               "  ;
        $i++;
    }
} else {
    $str_data_row = '      
            <tr>
                <td colspan="5" style="text-align: center;">코드 정보를 찾을수가 없습니다.</td>
            </tr>
          ';
}
?><!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="ko"> <![endif]-->
<!--[if IE 7]> <html class="lt-ie9 lt-ie8" lang="ko"> <![endif]-->
<!--[if IE 8]> <html class="lt-ie9" lang="ko"> <![endif]--> 
<!--[if gt IE 8]><!--> <html class="no-js" lang="ko"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--<meta http-equiv="refresh" content="0;url=http://www.daum.net">-->
    <title>List Page</title>
    <link rel="stylesheet" type="text/css" href="http://www.htenc.co.kr/css/style.css" />
    <style>
        .styleguide{
            display:'';
            text-align: center;
            -webkit-text-size-adjust: 100%;
            -webkit-tap-highlight-color: rgba(0,0,0,0);
            font-family: Roboto,'Noto Sans Korean',sans-serif;
            font-weight: 400;
            letter-spacing: -1px;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            color: #222;
            word-wrap: break-word;
            word-break: keep-all;
            box-sizing: border-box;
            border: 0;
            font-size: 100%;
            vertical-align: baseline;
            margin: 0;
            padding: 0;
        }
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
    <script type="text/javascript">
        function trim(str){
            //정규 표현식을 사용하여 화이트스페이스를 빈문자로 전환
            str = str.replace(/^\s*/,'').replace(/\s*$/, '');
            return str; //변환한 스트링을 리턴.
        }
        function goView(code_no){
            location.href = "<?php echo $_view_uri?>&loc_no=" + code_no;
        }
        function goModify(code_no){
            location.href = "<?php echo $_modify_uri?>&loc_no=" + code_no;
        }
        function goSearch(f){
            if(f == null || !f){
                alert("선언되지 않은 잘못된 접근");
                return;
            }
            //alert(f.name);
            //var word = rtrim(ltrim(f.s_word.value));
            if(!trim(f.s_word.value)){
                alert("검색어를 입력하여 주세요.;")
                f.s_word.focus();
                return;
            }
            else{
                f.submit();
            }
        }
    </script>
</head>
<body>
<div class="styleguide">
    <h3 style="text-align: left"><img src="/lib/images/title_De.gif">코드 목록</h3>
    <?php
    @session_start();
    if($_SESSION["user"]["uno"] != ""){
        echo $_SESSION["user"]["user_name"];
        echo " / <a href='/lib/logout.php'>로그아웃</a>";
    }
    ?>
    <div style="text-align:right">
    <?php
    echo "총 게시물 : {$nav->total_row} / 전체 페이지 : {$nav->total_page} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    ?>
    </div>
    <table style="text-align: center;width: 100%">
	<colgroup>
            <col style="width:50px" />
            <col style="width:150px" />
            <col />
            <col style="width:150px" />
            <col style="width:100px" />
	</colgroup>
	<caption>사업 Reference</caption>
	<thead>
            <tr>
                <th>No.</th>
                <th>관리번호</th>
                <th>지역 명칭</th>
                <th>지역 코드</th>
                <th>관리</th>
            </tr>
	</thead>
	<tbody>
        <?php echo $str_data_row;?>
	</tbody>
        <tfoot>
            <tr >
                <td colspan="4" style="text-align:center"><?php echo $str_page_bar; ?></td>
                <td style="text-align: center"><a href="<?php echo $_write_uri; ?>"><img src="/lib/images/btn_simple_add.gif" title="추가" /></a></td>
            </tr>
        </tfoot>
    </table>
    <form id="frmSearch" name="frmSearch" method="get" aciton="<?php echo $_SERVER["SCRIPT_NAME"]?>" onsubmit="goSearch(this); return false;">
        <input type="hidden" id="mode" name="mode" value="search" />
        <select id="s_type" name="s_type">
            <?php echo $search_options_html; ?>
        </select>
        <input type="text" id="s_word" name="s_word" value="<?php echo @trim($post["s_word"]);?>" />
        <input type="button" id="btnSearch" name="btnSearch" value=" 검색 " onclick="goSearch(this.form);" />
        <input type="button" id="btnSearch" name="btnSearch" value=" 초기화 " onclick="this.form.reset()" />
        <input type="button" id="btnSearch" name="btnSearch" value=" 검색 취소 " onclick="location.href='<?php echo $_SERVER["SCRIPT_NAME"]?>'" />
    </form>
</div>
</body>
</html>