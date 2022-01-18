<?php
@ini_set("display_errors", "On");
//@error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
if(!defined("_INCLUDE_")) require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/include.php";
@error_reporting(E_ALL^E_WARNING);
include "_box.php";

$db = new DB;

$uno = null;

$sql = "WITH A AS
(
SELECT 
    CO.*
    ,IM.IMAGE  
FROM EX_CONTENTS CO
    ,EX_IMAGE IM
WHERE 1 = 1
    AND CO.UNO=IM.UNO(+)
)
SELECT * FROM A
WHERE 1 = 1
";

if($post["mode"] == "write1")
{
    session_start();
    
    $db_username = "EXERCISE1";
    $db_password = "exe880323";
    $db = "oci:dbname=testdb.htenc.com:1521/ORCL";
    $conn = new PDO($db,$db_username,$db_password);
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    
    $sql = $conn -> prepare("SELECT USER_ID, USER_PWD, USER_NAME FROM EX_USER_SET WHERE USER_ID=:USER_ID");
    $sql -> bindParam("USER_ID",$_SESSION['USER_ID']);
    
    $sql -> execute();
    $row = $sql -> fetch();
    $duty_id = null;
    $dept_id = null;
} 
else if($post["mode"] == "modify")
{
    $uno = $_REQUEST["uno"];
    $sql .= " AND UNO = {$uno}";
	//Fun::print_($sql);
	//exit;
	$db->query($sql);
	$db->next_record();
	$member = $db->Record;
        $title = $member["title"];
        $content = $member["content"];
}
else
{
    Fun::alert("정상적인 방법으로 접속하여 주세요.");
    exit;
}
//Fun::print_r($row);
//$query_string = Fun::getParamUrl("uno");
//Fun::print_r($_GET);
//exit;

?><!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="ko"> <![endif]-->
<!--[if IE 7]> <html class="lt-ie9 lt-ie8" lang="ko"> <![endif]-->
<!--[if IE 8]> <html class="lt-ie9" lang="ko"> <![endif]--> 
<!--[if gt IE 8]><!--> <html class="no-js" lang="ko"> <!--<![endif]-->
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Style-Type" content="text/css">
<link rel="stylesheet" href="bootstrap-5.1.3-dist/css/bootstrap.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> 
<script type="text/javascript" src="bootstrap-5.1.3-dist/js/bootstrap.js"></script>
<link rel="shortcut icon" href="android-icon.ico">

        <?php if($post["mode"] == "write1"){
		    echo "<title>게시물 추가</title>";
		}else{
		    echo "<title>게시물 수정</title>";
		    
		}?>
  
        <script type="text/javascript">
            function goList(){
                location.href = "<?php echo $_list_uri; ?>";
            }
            function goDelete(uno){
            if (confirm("정말 삭제 하시겠습니까?")==true){
                	location.href = "<?php echo $_delete_content_uri?>&uno=" + uno;
            	}else{
            		return;
            	}
            }
            function goFiledelete(uno){
            	if (confirm("정말 삭제 하시겠습니까?")==true){
                	location.href = "<?php echo $_delete_file_uri?>&uno=" + uno;
            	}else{
            		return;
            	}
            }
            function goSubmit(){
                var frm = document.forms["frmname01"];
                
               if(!frm.title.value){
                    alert("제목을 입력하여 주세요.");
                    frm.title.focus();
                }else if(frm.title.value.replace(/^\s+|\s+$/g, '' ) == "" ){
                    alert("제목에 공백만 입력되었습니다.");
                    frm.content.focus();
                }
                else if(!frm.content.value){
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
            function GoUserIdCheck(obj){
                document.getElementById("user_id_check").value = "";
                document.getElementById("ajax_message").innerHTML = "";
                //console.log(obj);
                //alert(obj);
                // Create an XMLHttpRequest object
                var xhttp = new XMLHttpRequest();

                // Define a callback function
                xhttp.onload = function() {
                  // Here you can use the Data
                    if(this.responseText != "")
                    {
                        document.getElementById("user_id_check").value = "false";
                        document.getElementById("ajax_message").innerHTML =  this.responseText;
                    }
                    else 
                    {
                        document.getElementById("user_id_check").value = "true";
                    }
                }

                // Send a request
                xhttp.open("GET", "action.php?mode=userid_check&user_id=" + document.getElementById("user_id").value );
                xhttp.send();
            }
        </script>
        <style>
            textarea{
                width:600px;
                height:200px;
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
            <a class="dropdown-item" href="best_view.php">View</a>
            <a class="dropdown-item" href="best_like.php">Like</a>
          </div>
        </li>
      </ul>
    </div>
  </div>
</nav>
<br />
<form id="frmid01" name="frmname01" method="post" action="action.php" onsubmit="return false;" enctype="multipart/form-data">
<input type="hidden" id="mode" name="mode" value="<?php echo $post["mode"];?>" />
<input type="hidden" id="uno" name="uno" value="<?php echo $uno;?>" />
<input type="hidden" id="user_id_check" name="user_id_check" value="" />
    <div align="center" class="container">
        <center>
            <h3 style="text-align: left; width: 800px"><img src="/lib/images/title_De.gif">게시판 글 <?php echo ($post["mode"] == "modify" ? " - 수정" : " - 추가"); ?></h3>
            <br />
        <table style="width:800px" class="table  table-hover">
            <colgroup>
                <col style="width:200px" />
                <col />
                <col style="width:107px"/>
            </colgroup>
				<caption>SCP COMPANY</caption>
            <tbody>
                <tr>
                    <th class="th_col">닉네임</th>
                    <td colspan="2">
                        <?php
                        if($post["mode"] == "write1"){
                            $row['USER_NAME'] = iconv("CP949","UTF-8", $row['USER_NAME']);
                            echo $row["USER_NAME"];
                            echo "<input type='hidden' value='{$row["USER_NAME"]}' id = 'user_name' name='user_name'";
                            
                        }
                        else {
                            echo $member["user_name"]??null;
                        }
                        ?>
                        <div id="ajax_message">&nbsp;</div>
                    </td>
                </tr>
                
                <tr>
                    <th>제목</th>
                    <td colspan="2"><input type="text" style="width:600px" maxlength="25" class="form-control" id="title" name="title" value="<?php echo $member["title"]??null; ?>" required /></td>
                </tr>
                <tr>
                	<th>내용</th>
                	<td colspan="2">
                	<textarea name = "content" id="content" class="form-control" required ><?php echo $member["content"]??null; if(!isset($member)){echo null;} ?></textarea>
                    <br />
                    </td>
                </tr>
                <tr>
                <td></td>
                <td><input type="file" name="fileToUpload" id="fileToUpload" class="form-control">
                    <?php echo $member["image"]??null;?>
                </td>
                <td>
                	<?php if($post["mode"] == "modify"){
                	    echo "<button type='button' class='btn btn-success' onclick='goFiledelete({$member["uno"]});'title='파일삭제' />파일삭제";
                	}else{
                	   echo null;
                	}?>
                </td>
                </tr>
                <tr>
                <th>태그</th>
                	<td>
                    <select class="form-select" id="exampleSelect1" name="tag">
                        <option value="null">태그를 선택해 주세요</option>
                        <option value = "study">Study</option>
                    </select>
                	</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td><button class="btn btn-success"  onclick="goList();" title="목록">목록</td>
                    <td align="right"><?php if($post["mode"] == "modify"){
                        echo "<button class='btn btn-success' onclick='goDelete({$member["uno"]});' title='삭제'>삭제";}?>
                    </td>
                    <td style="text-align:right"><button class="btn btn-success" type="submit" onclick="goSubmit();" title="저장">저장</td>
                </tr>
            </tfoot>
        </table>
        </center>
    </div>
	<!--<input type="submit" value="Submit" />-->
</form>
</body>
</html>