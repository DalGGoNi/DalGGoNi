<?php
@ini_set("display_errors", "On");
// @error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
if (! defined("_INCLUDE_"))
    require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/include.php";
@error_reporting(E_ALL^E_WARNING^E_DEPRECATED);
include './_box.php';

$uno = $_REQUEST["uno"] ?? 5;
$db = new DB();
$sql = "WITH A AS
(
SELECT
    CO.*,
    IM.IMGURL,
    IM.IMAGE
FROM EX_CONTENTS CO
    ,EX_IMAGE IM
WHERE 1 = 1
    AND CO.UNO=IM.UNO(+)
)
SELECT * FROM A
WHERE 1 = 1
    AND UNO = {$uno}
";

$db->query($sql);
$db->next_record();
$member = $db->Record;
// Fun::print_r($row);
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
<script type="text/javascript" src="bootstrap-5.1.3-dist/js/bootstrap.js"></script>
<!--<meta http-equiv="refresh" content="0;url=http://www.daum.net">-->
<title>게시물</title>
<link rel="shortcut icon" href="android-icon.ico">
<link rel="stylesheet" type="text/css" href="content_style.css?after">
<script>
    function goList(){
    	location.href = "list.php";
    }
    function goListStudy(){
    	location.href = "study_list.php";
    }
    function goModify(uno){
    	location.href = "<?php echo $_modify_uri?>&uno=" + uno;
    }
</script>
<style>
   .glanlink{
        text-decoration:none;
        color:black;
    }
    img {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 5px;
    }
    .line1-ellipsis {
        display: block;/* 블록태그로 만들어준다 */

        white-space: pre-wrap;
        word-break:break-all;
    }
</style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="list.php">SCPark</a>
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
                        <input type='submit' title='정보수정' class='btn btn-secondary' value='정보수정');\"/>
                        </form></table>";
            }
         ?>
        </div>
    </div>
</nav>
	<div class="container">
		<br />
		
		<div id="header_box">
		
		</div>
		<h1 class="con" align="center" >본&nbsp&nbsp&nbsp&nbsp문</h1>
		<hr>
		<section class="article-detail table-common con row" >
			<table class="cell" border="1" style="table-layout: fixed; width: 100%;">
				<colgroup>
					<col width="10">
					<col width="50">
					<col width="10">
					<col width="10">
					<col width="10">
					<col width="10">
				</colgroup>
				<tbody>
					<tr class="article-title">
						<th>ID: <?php echo $member["user_name"]; ?></th>
						<td colspan="5"><div class="line1-ellipsis"><h2>제목: <?php echo $title = $member["title"]; $title = Fun::html2char($title); htmlspecialchars($title); ?></h2></div></td>
					</tr>
					<tr class="article-info">
						<th>날짜</th>
						<td><?php echo $member["REG_DATE"]; ?></td>
						<th width=70px>조회수</th>
						<td><?php echo $member["hit"]; ?></td>
						<th width=70px>좋아요</th>
						<td><?php
						$db = new DB();
						$sql = "SELECT COUNT(CO_ID) AS COUNT
                                FROM EX_LIKE
                                WHERE CO_ID = {$uno}";
						$db->query($sql);
						$db->next_record();
						$like = $db->Record;
						echo $like["count"]; ?></td>
					</tr>
					<tr>
						<td>첨부파일:</td>
						<td colspan="5"><a href="file_download.php?filepath=\<?php echo $member["image"];?>"><?php echo $member["image"];?></a>
					</tr>
					<tr class="article-body">
						<td colspan="6" style="vertical-align: top;"><div class="line1-ellipsis"><?php  $CONTENT = $member["content"];$CONTENT= Fun::html2char($CONTENT); htmlspecialchars($CONTENT??null);echo $CONTENT; ?></div></td>
					</tr>
					<tr>
						<td colspan="6" align="center"><?php if ($member["imgurl"] != "")
						{
						    if (preg_match("/\.(gif|jpg|jpeg|png|PNG)$/i", $member["imgurl"]))
						    {
						        echo "<img src=". str_replace(" ","_",$member['imgurl']). " style='max-width: 100%; height: auto;' class='image'>";
						    }
						    else
						    {
						        echo null;
						    }
						}
						else{
						    echo null; 
						}?></td>
					</tr>
				</tbody>
			</table>
		</section>
		<br />
		<div align="center">
		<form action="<?php echo $_action_uri;?>"method="post">
		<input type="hidden" id="co_id" name="co_id" value="<?php 
					$db = new DB();
					$uno = $_REQUEST["uno"] ?? 5;
					$sql = "WITH A AS
                        (
                        SELECT
                            CO.UNO
                        FROM EX_CONTENTS CO
                            , EX_TEST TE
                        WHERE 1 = 1
                            AND CO.UNO=TE.UNO
                        )
                        SELECT * FROM A
                        WHERE 1 = 1
                            AND UNO = {$uno}
                        ";
					$db->query($sql);
					$db->next_record();
					echo $uno;?>"/>
			<input type="hidden" id="us_id" name="us_id" value="
			<?php  
			if (!isset($row['USER_ID'])){
			    echo null;
			}
			echo $row['UNO']; ?>"/>

			
			<input type="submit" class="btn btn-warning" value="좋아요" id="like" name="like" title="좋아요" required>
			
		</form>
		</div>

		<div class="con reply">
			<h5 class="">댓글 입력</h5>
			<section class="reply-form">
				<form id="frmComment" name="frmComment" action="insert.php"method="post" onsubmit="return false;">
					<div>
					<input type="hidden" id="uno" name="uno" value="<?php echo $uno;?>" />
					<input type="hidden" id="user_name" name="user_name" value="<?php
                      $db_username = "EXERCISE1";
                      $db_password = "exe880323";
                      $db = "oci:dbname=testdb.htenc.com:1521/ORCL";
                      $conn = new PDO($db,$db_username,$db_password);
                      $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
                      
                      $sql = $conn -> prepare("SELECT USER_ID, USER_PWD, USER_NAME, ROLE FROM EX_USER_SET WHERE USER_ID=:USER_ID");
                      $sql -> bindParam("USER_ID",$_SESSION['USER_ID']);
        
                      $sql -> execute();
                      $row = $sql -> fetch();

                if(!isset($_SESSION['USER_ID'])) {
                    echo null;
                } else {
                    $row['USER_NAME'] = iconv("CP949","UTF-8", $row['USER_NAME']);
                    echo $row['USER_NAME'];
                }
             ?>" />
					</div>
					<div align="right">
					<?php 
					if(!isset($_SESSION['USER_ID'])) {
					    echo null;
					} else {
					    echo "<textarea name = content ></textarea>
						<input type='submit' class='btn btn btn-warning' title='입력' value='입력' onclick='goSubmit();'>";
					}
					?>
						
					</div>
				</form>
			</section>
			
			<h5>댓글 목록</h5>

				<table class="table table-hover" style="table-layout: fixed; width: 100%;">
				<script>
				
    				function goDelete(num){
    				if (confirm("정말 삭제 하시겠습니까?")==true){
                        	location.href = "<?php echo $_delete_uri?>&num=" + num;
                        }else{
                        	return;
                        }
                    }
                    
                    function openPop(uno, num){

                    	var popupWidth = 800;
                        var popupHeight = 300;
                        var pop_title = "popupOpener" ;

                        var popupX = (window.screen.width / 2) - (popupWidth / 2);                     
                        var popupY= (window.screen.height / 2) - (popupHeight / 2);

// 						var frmData = document.frmData ;
// 						frmData.method = "post";
//                         frmData.target = pop_title ;
//                     	frmData.num.value = num;
//                         frmData.action = "comment_modify.php" ;
                        var url = 'comment_modify.php?uno='+uno+'&num='+num;
                        window.open(url, pop_title, 'status=no, height=' + popupHeight  + ', width=' + popupWidth  + ', left='+ popupX + ', top='+ popupY);
//                         frmData.submit() ;
					}
						function goSubmit(){
                    		var frm = document.forms["frmComment"];
                    	
                        	if(!frm.content.value){
                                alert("내용을 입력하여 주세요.");
                                frm.content.focus();
                            }else if(frm.content.value.replace(/^\s+|\s+$/g, '' ) == "" ){
                                alert("내용에 공백만 입력되었습니다.");
                                frm.content.focus();
                            }
                            else {
                                frm.submit();
                            }
                    	}
				</script>
					<colgroup>
						<col width="100px">
					</colgroup>

					<tbody>
					 <?php 

					 $db = new DB();
					 require_once  _LIB_PATH_ . "nav_page.class.php";
					 $nav = new NavPage;
					 $nav->start(5,10);
					 $main_sql = "SELECT
                                    UNO, NUM, USER_NAME, CONTENT, REG_DATE     
                                FROM EX_TEST TE  
                                WHERE 1 = 1
                                     AND UNO = {$uno}
                                ORDER BY NUM desc
                                ";
                                    $sql = "WITH A as (
                                  {$main_sql}
                                )
                                SELECT COUNT(*) AS CNT FROM A";
                      
                      $count = $db->query_one($sql);
                      $str_page_bar = $nav->navpage($count);
                      $sql = "SELECT
                                    TE.UNO, TE.NUM, TE.USER_NAME AS USER_NAME, TE.CONTENT, TE.REG_DATE, A.ROLE     
                                FROM EX_TEST TE, EX_USER_SET A  
                                WHERE 1 = 1
                                     AND TE.USER_NAME=A.USER_NAME
                                     AND TE.UNO = {$uno}
                                ORDER BY NUM desc
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
                              $no = $count - $nav->start_row - $i;
                              //$list_query = Fun::getParamUrl();
                              if($member["role"]=='ADMIN'){
                                  $user_name=$db->f("user_name")."&nbsp&nbsp&nbsp<span class='badge rounded-pill bg-primary'>운영자</span>";
                              }else{
                                  $user_name=$db->f("user_name");
                              }
                              if (!isset($row['USER_ID'])){
                                  $delete=null;
                                  $modify=null;
                              }
                              else if(($row['USER_NAME'])==$member['user_name'] || $row['ROLE']=='ADMIN') {
                                  
                                  $delete="<button title=\"삭제\" class='btn btn-warning' onclick=\"goDelete({$member["num"]});\" style=\"cursor: pointer\" />삭제";
                                  $modify="<button title=\"수정\" class='btn btn-warning' onclick=\"openPop({$member["uno"]},{$member["num"]});\" />수정";
                              }
                              else {
                                  $modify=null;
                                  $delete=NULL;
                              }
                              
                              $str_data_row .= "
                                <tr>
                                    <td style=\"text-align: center; width:10%;\">{$no}</td>
                                    <td style=\"text-align: left; width:15%;\">{$user_name}</td>
                                    <td style=\"text-align: left; width:47%;\"><div class='line1-ellipsis'>{$member["content"]}</div></td>
                                    <td style=\"text-align: right; width:10%;\"><div class='line1-ellipsis'>{$member["reg_date"]}</div></td>
                                    <td style=\"text-align: right; width:9%\">{$modify}</td>
                                    <td style=\"text-align: right; width:9%\">{$delete}</td>                                   
                                </tr>
                                   "  ;
                              $i++;
                          }
                      } else {
                          $str_data_row = '
                                <tr>
                                    <td colspan="6" style="text-align: center;">작성된 댓글이 없습니다.</td>
                                </tr>
                              ';
                      }
                      echo $str_data_row;?>
						 
					</tbody>
					<tfoot>
						<tr>
							<td colspan="6" style="text-align:center"><?php echo $str_page_bar ?></td>
						</tr>
						<tr>

    				<td align="right" colspan="6">
    				<?php 
    				if (!isset($row['USER_ID'])){
    				    echo null;
    				}
                    else if(($row['USER_NAME'])==$member['user_name'] || $row['ROLE']=='ADMIN') {
                        
                        echo "
                            <form action={$_modify_uri} method='post'>
                            <input type='hidden' id='uno' name='uno' value='{$_REQUEST['uno']}'>
                            <button class='btn btn-warning' title='수정' />수정
                            </form>

                            ";
                    }
                    else {
                        echo NULL;
                    }
                    ?>
                    </td>
                    </tr>
                    <table style="table-layout: fixed; width: 100%;">
                    <tr>
                    <td>
						<form action="<?php if($member['tag']==null){echo $_pre_uri;}else{echo $_stu_pre_uri;}?>" method="post">
						<input type="hidden" id="uno" name="uno" value="<?php echo $uno;?>" />
						<input type="submit" title="이전글" class="btn btn-warning" value = "이전글" />
						</form>
					</td>
					<td align="center">
						<?php if($member["tag"] == 'study'){
						    echo '<button class="btn btn-warning"  onclick="goListStudy();" title="목록" />목록';
						} else{
						    echo '<button class="btn btn-warning"  onclick="goList();" title="목록" />목록';
						}?>

    				</td>
					<td align="right">
						<form action="<?php if($member['tag']==null){echo $_odd_uri;}else{echo $_stu_odd_uri;}?>" method="post">
						<input type="hidden" id="uno" name="uno" value="<?php echo $uno;?>" />
						<input type="submit" title="다음글" class="btn btn-warning" value = "다음글" />
						</form>
					</td>
                    </tr>
                    </table>
					</tfoot>
				</table>
		</div>
		

	</div>

</body>
</html>