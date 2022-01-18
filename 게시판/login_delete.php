<?php
@ini_set("display_errors", "On");
//@error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
if(!defined("_INCLUDE_")) require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/include.php";
@error_reporting(E_ALL);
include "_box.php";

$db = new DB;

$uno = null;

$sql = "WITH A AS
(
SELECT 
    U.*
    , CO.CONTENT
    , CO.TITLE
    , CO.HIT
FROM EX_USER_SET U
    , EX_CONTENTS CO
WHERE 1 = 1
    AND U.USER_NAME = CO.USER_NAME(+)
)
SELECT * FROM A
WHERE 1 = 1
";

if($post["mode"] == "delete_id")
{
    $uno = $_REQUEST["uno"];
    $sql .= " AND UNO = {$uno}";
	//Fun::print_($sql);
	//exit;
	$db->query($sql);
	$db->next_record();
	$member = $db->Record;
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
<script type="text/javascript" src="smarteditor2-master/workspace/static/js/service/HuskyEZCreator.js" charset="utf-8"></script>
<link rel="shortcut icon" href="android-icon.ico">
<title>회원 삭제</title>
<script type="text/javascript">
    function goList(){
        location.href = "<?php echo $_list_uri; ?>";
    }
    function goDelete(){
        location.href = "<?php echo $_delete_id_uri; ?>";
    }
    function goSubmit(){
        var frm = document.forms["frmname01"];
        
        if(!frm.user_pwd.value){
            alert("비밀번호를 입력하여 주세요.");
            frm.user_pwd.focus();
        }
        else if(!frm.user_pwd_check.value){
            alert("비밀번호 확인을 입력해 주세요.");
            frm.user_pwd.focus();
        }
        else if(frm.user_pwd.value != frm.user_pwd_check.value){
            alert("비밀번호와 비밀번호 확인이 같지 않습니다.");
            frm.user_pwd.focus();
        }
        else if(confirm("정말 삭제 하시겠습니까?")==true){
        	frm.submit();
        }  
        else {
           return; 	
        }
    }
</script>
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
<form id="frmid01" name="frmname01" method="post" action="login_action.php" onsubmit="return false;">
<input type="hidden" id="mode" name="mode" value="<?php echo $post["mode"];?>" />
<input type="hidden" id="uno" name="uno" value="<?php echo $uno;?>" />
    <div align="center" class="container">
        <center>
            <h3 style="text-align: left; width: 800px"><img src="/lib/images/title_De.gif">회원<?php echo ($post["mode"] == "modify" ? " - 수정" : " - 추가"); ?></h3>
            <br />
        <table style="width:800px" class="table  table-hover">
            <colgroup>
                <col style="width:200px" />
                <col />
            </colgroup>
            <caption>SCP COMPANY</caption>
            <tbody>
                <tr>
                    <th class="th_col">USER_ID</th>
                    <td colspan="2">
                        <?php echo $member["user_id"]??null; ?>
                        <div id="ajax_message">&nbsp;</div>
                    </td>
                </tr>
                    <th>닉네임</th>
                    <td colspan="2">
                         <?php echo $member["user_name"]??null;?>
                        <div id="ajax_message1">&nbsp;</div>
                    </td>
                </tr>
				<tr>
                    <th>비밀번호</th>
                    <td colspan="2"><input type="password" id="user_pwd" name="user_pwd" required /></td>
                </tr>
                <tr>
                    <th>비밀번호 확인</th>
                    <td colspan="2"><input type="password" id="user_pwd_check" name="user_pwd_check"  required /></td>
                </tr>
                <tr>
                    <th>&nbsp; </th>
                    <td colspan="2"> </td>
                </tr><tr>
                    <th> &nbsp;</th>
                    <td colspan="2"> </td>
                </tr>
                
            </tbody>
            <tfoot>
                <tr>
                    <td><button class="btn btn-danger"  onclick="goList();" title="목록">목록</td>
                    <td style="text-align:right"><button class="btn btn-danger" type="submit" onclick="goSubmit();" title="저장">삭제</td>
                </tr>
            </tfoot>
        </table>
        </center>
    </div>
	<!--<input type="submit" value="Submit" />-->
</form>
</body>
</html>