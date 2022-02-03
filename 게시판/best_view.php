<?php
@ini_set("display_errors", "On");
//@error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
if(!defined("_INCLUDE_")) require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/include.php";
@error_reporting(E_ALL^ E_WARNING^E_DEPRECATED);
include './_box.php';
require_once  _LIB_PATH_ . "nav_page.class.php";
$nav = new NavPage;
$nav->start(10,10);
$s_word = $post["s_word"];
$s_word = Fun::convVal2DB($s_word);
$db = new DB;
$mWhere = null;
$sel_opt = array();
if(@$post["mode"] == "search" && @trim($s_word) != ""){
    $col_name = null;
    if($post["s_type"] == "all"){
        $sel_opt["all"] = " selected";
        $mWhere = " AND (";
        $mWhere .= " C.USER_NAME LIKE '%{$s_word}%'";
        $mWhere .= " OR TITLE LIKE '%{$s_word}%'";
        $mWhere .= ")";
    }
    else if($post["s_type"] == "user_name"){
        $sel_opt["user_name"] = " selected";
        $mWhere = " AND C.USER_NAME LIKE '%{$s_word}%'";
    }
    else if($post["s_type"] == "title"){
        $sel_opt["title"] = " selected";
        $mWhere = " AND TITLE LIKE '%{$s_word}%'";
    }
    
}

$main_sql = "SELECT
    *
FROM EX_CONTENTS
WHERE 1 = 1
    AND TITLE IS NOT NULL
    {$mWhere}
ORDER BY CONT_NUM desc
";
    $sql = "WITH B AS(
    SELECT COUNT(CO_ID) AS COUNT, CO_ID
    FROM EX_LIKE
    GROUP BY CO_ID
),
D AS(
SELECT COUNT(UNO) UNO_COUNT, UNO
FROM EX_TEST
GROUP BY UNO
),
A as (
        SELECT
            C.*
            ,B.COUNT
            ,A.ROLE
            ,D.UNO_COUNT
            ,E.IMAGE
        FROM EX_CONTENTS C, B B, EX_USER_SET A, D D, EX_IMAGE E
        WHERE 1 = 1
            AND C.UNO = B.CO_ID(+)
            AND C.USER_NAME = A.USER_NAME(+)
            AND C.UNO=D.UNO(+)
            AND C.UNO=E.UNO(+)
            AND TITLE IS NOT NULL
            AND TAG IS NULL
            {$mWhere}
        ORDER BY HIT desc
)
SELECT COUNT(*) AS CNT FROM A";
            
            $count = $db->query_one($sql);
            $str_page_bar = $nav->navpage($count);
            
            $sql = "WITH B AS(
    SELECT COUNT(CO_ID) AS COUNT, CO_ID
    FROM EX_LIKE
    GROUP BY CO_ID
)
, D AS(
select count(uno) UNO_COUNT, UNO
from EX_TEST
GROUP BY UNO
)
SELECT
    C.*
    ,B.COUNT
    ,A.ROLE
    ,D.UNO_COUNT
    ,E.IMAGE
FROM EX_CONTENTS C, B B, EX_USER_SET A, D D, EX_IMAGE E
WHERE 1 = 1
    AND C.UNO = B.CO_ID(+)
    AND C.USER_NAME = A.USER_NAME(+)
    AND C.UNO=D.UNO(+)
    AND C.UNO=E.UNO(+)
    AND TAG IS NULL
    AND TITLE IS NOT NULL
{$mWhere}
ORDER BY HIT desc
";
$db->query_limit($sql, $nav->start_row, $nav->row_scale);
$query_row_count = $db->nf();

$str_data_row = null;
if($query_row_count > 0)
{
    $i = 0;
    while($db->next_record())
    {
        $member = $db->Record;
        if($member["count"]==null){
            $member["count"] = 0;
        }
        if($member["uno_count"]==null){
            $member["uno_count"] = 0;
        }
        $no = $count - $nav->start_row - $i;
        //$list_query = Fun::getParamUrl();
        if($member["role"]=='ADMIN'){
            $user_name=$db->f("user_name")."&nbsp&nbsp&nbsp<span class='badge rounded-pill bg-primary'>운영자</span>";
        }else{
            $user_name=$db->f("user_name");
        }
        if($member["image"]==null){
            $image = "<img src='file-earmark-text.svg'>";
        }else{
            $image="<img src='file-earmark-image.svg'>";
        }
        
        $str_data_row .= "
            <style>
               .glanlink{
                    text-decoration:none;
                    color:black;
                }
            </style>
            <tr>
                <td style=\"text-align: center;\">{$no}</td>
                <td style=\"text-align: left;\">{$user_name}</td>
                <td style=\"text-align: left;\"><h5><div class='line1-ellipsis'><a class='glanlink' href='action.php?mode=hit&uno={$member["uno"]}'>$image {$member["title"]} [{$member["uno_count"]}]</div></h5></td>
                <td style=\"text-align: center;\"><div class='line1-ellipsis'>{$member["reg_date"]}</div></td>
                <td style=\"text-align: center;\">{$member["count"]}</td>
                <td style=\"text-align: center;\">{$member["hit"]}</td>
            </tr>
               "  ;
        $i++;
    }
} else {
    $str_data_row = '
            <tr>
                <td colspan="6" style="text-align: center;">게시판 정보를 찾을수가 없습니다.</td>
            </tr>
          ';
}
?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="ko"> <![endif]-->
<!--[if IE 7]> <html class="lt-ie9 lt-ie8" lang="ko"> <![endif]-->
<!--[if IE 8]> <html class="lt-ie9" lang="ko"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="ko">
<!--<![endif]-->
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Style-Type" content="text/css">
<link rel="stylesheet" href="bootstrap-5.1.3-dist/css/bootstrap.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<!--<meta http-equiv="refresh" content="0;url=http://www.daum.net">-->
<link rel="shortcut icon" href="android-icon.ico">
<title>게시판 리스트</title>
<script type="text/javascript">

        function trim(str){
            //정규 표현식을 사용하여 화이트스페이스를 빈문자로 전환
            str = str.replace(/^\s*/,'').replace(/\s*$/, '');
            return str; //변환한 스트링을 리턴.
        }
        function goView(uno){
            location.href = "<?php echo $_view_uri?>&uno=" + uno;
        }
        function goModify(uno){
            location.href = "<?php echo $_modify_uri?>&uno=" + uno;
        }
        function goList(){
            location.href = "list.php";
        }
        function goSearch(f){
            if(f == null || !f){
                alert("선언되지 않은 잘못된 접근");
                return;
            }
            //alert(f.name);
            //var word = rtrim(ltrim(f.s_word.value));
//             if(!trim(f.s_word.value)){
//                 alert("검색어를 입력하여 주세요.")
//                 f.s_word.focus();
//                 return;
//             }
//             else{
                f.submit();
//             }
        }
    </script>
    <style>

   .glanlink{
        text-decoration:none;
        color:black;
    }
    .line1-ellipsis {
        display: block;/* 블록태그로 만들어준다 */
        text-overflow:ellipsis;/* 말줄임 css */
        white-space:pre-wrap;/*글자를 한줄로 모아준다*/
        overflow:hidden; 
    }
</style>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="list.php" title="홈">SCPark</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarColor03">
      <ul class="navbar-nav me-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Best</a>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="best_view.php" title="조회수 순">View</a>
            <a class="dropdown-item" href="best_like.php" title="좋아요 순">Like</a>
          </div>
        </li>
         <li class="nav-item">
          <a class="nav-link" href="study_list.php">Study</a>
        </li>
      </ul>
    </div>
	<div align="right">
      <?php              
      session_start();

      $db_username = "EXERCISE1";
      $db_password = "exe880323";
      $db = "oci:dbname=testdb.htenc.com:1521/ORCL";
      $conn = new PDO($db,$db_username,$db_password);
      $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
      
      $sql = $conn -> prepare("SELECT UNO,USER_ID, USER_PWD, USER_NAME FROM EX_USER_SET WHERE USER_ID=:USER_ID");
      $sql -> bindParam("USER_ID",$_SESSION['USER_ID']);

      $sql -> execute();
      $row = $sql -> fetch();

        if(!isset($_SESSION['USER_ID'])) {
            echo '<td><form method="post" action="login.php"><button type="submit" class="btn btn-secondary" title="로그인"/>로그인</form></td>';
        } else {
            $row['USER_NAME'] = iconv("CP949","UTF-8", $row['USER_NAME']);
            echo "<table><td><strong>{$row['USER_NAME']}</strong>님 환영합니다.</td>";
            echo "<td><form method='post' action='logout.php'><button type='submit' class='btn btn-secondary' title='로그아웃'/>로그아웃</a></form></td>";
            echo "<td><form action={$_login_midify_uri} method='post'>
                    <input type='hidden' id='uno' name='uno' value='{$row['UNO']}'>
                    <input type='submit' class='btn btn-secondary' value='정보수정' title='정보수정');\"/>
                    </form></table>";
        }
     ?>
	</div>
  </div>
</nav>
	<div align="center" class="container">
		<div id="header_box">
			<br />
			<div class="bbs_title">
				<h1>게시판</h1>
				<hr>
			</div>
		</div>
		<table>
		<colgroup>
            <col style="width:30%" />
            <col style="width:70%" />
        </colgroup>
		<tr>
		<td width="600px" align ="left">
		<div class="btn-group" role="group" aria-label="Button group with nested dropdown">
          <button type="button" class="btn btn-light" title="목록" onclick="goList();">List</button>
          <div class="btn-group" role="group">
            <button id="btnGroupDrop1" type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
              <a class="dropdown-item" href="best_view.php" title="조회수 순">View</a>
              <a class="dropdown-item" href="best_like.php" title="좋아요 순">Like</a>
            </div>
          </div>
        </div>
		</td>
		<td align="right"><?php
		if($_REQUEST["cur_page"]==null){
		    $_REQUEST["cur_page"]=1;
		}
        echo "총 게시물 : {$nav->total_row} / 전체 페이지 : {$nav->total_page} / 현제 페이지 : {$_REQUEST["cur_page"]}";
        ?>
        </td>
        </tr>
		</table>
		<table class="table  table-hover" style="table-layout: fixed; width: 100%;">
    		<colgroup>
                <col style="width:10%" />
                <col style="width:15%" />
                <col style="width:52%" />
                <col style="width:9%px" />
                <col style="width:7%px" />
                <col style="width:7%px" />
            </colgroup>
               <thead>
                <tr class="table-dark" style="text-align:center">
                    <th>No.</th>
                    <th>글쓴이</th>
                    <th>제목</th>
                    <th>등록일</th>
                    <th>추천</th>
                    <th>조회수</th>
                </tr>
    		</thead>

			<tbody>
                    <?php echo  $str_data_row; ?>
			</tbody>
			<tfoot>
        </tfoot>
		</table>
		
                <div style="text-align: left; border: 0px;">
                <?php 
                if(!isset($_SESSION['USER_ID'])) {

                } else {
                    echo "<a class='glanlink' href='{$_write_uri1}'>
                <button type='button' class='btn btn-secondary' title='추가'/>추가</a>";
                }
                ?>
                
                </div>

		<?php
        echo $str_page_bar;
        ?>
        <br />
        <div style="width:500px">
            <form class="d-flex" id="frmSearch" name="frmSearch" method="get" aciton="<?php echo $_SERVER["SCRIPT_NAME"]?>" onsubmit="goSearch(this); return false;">
          	<input type="hidden" id="mode" name="mode" value="search" />
          	<select id="s_type" name="s_type" class="form-select" style="width:150px">
                <option value="all"<?php echo @$sel_opt["all"]; ?>>전체</option>
                <option value="user_name"<?php echo @$sel_opt["user_name"]; ?>>사용자 명</option>
                <option value="title"<?php echo @$sel_opt["title"]; ?>>제목</option>
            </select>
          	&nbsp
            <input class="form-control me-sm-2" type="text" id="s_word" name="s_word" value="<?php echo @trim($s_word);?>" />
            <button class="btn btn-secondary my-2 my-sm-0" type="submit" id="btnSearch" name="btnSearch" title="검색" onclick="goSearch(this.form);">Search</button>
          	</form>
        </div>
	</div>
	
</body>
</html>
