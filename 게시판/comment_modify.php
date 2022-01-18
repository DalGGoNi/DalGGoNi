<?php
@ini_set("display_errors", "On");
//@error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
if(!defined("_INCLUDE_")) require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/include.php";
@error_reporting(E_ALL);
include './_box.php';
// echo $post["uno"];
// echo exit();
$uno=$post["uno"];
$num=$post["num"];
$db = new DB();
$sql = "SELECT
        NUM, USER_NAME, CONTENT, REG_DATE
    FROM EX_TEST TE
    WHERE 1 = 1
         AND NUM = {$post['num']}
    ";
$db->query($sql);
$db->next_record();
$member = $db->Record;
?>
<!DOCTYPE html>
<html lang="ko">

<head>
<script type="text/javascript">
	function goSubmit(){
		var frm = document.forms["frmname"];
	
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
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Style-Type" content="text/css">
<link rel="stylesheet" href="bootstrap-5.1.3-dist/css/bootstrap.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> 
<script type="text/javascript" src="bootstrap-5.1.3-dist/js/bootstrap.js"></script>
<title>댓글 수정</title>
<link rel="shortcut icon" href="android-icon.ico">
</head>

<body>
<input type="hidden" id="mode" name="mode" value="write" />
<input type="hidden" id="login_mode" name="login_mode" value="login" />
<input type="hidden" id="login_user_id" name="login_user_id" value="" />
<input type="hidden" id ="login_password" name="login_password" value="" />
<div align="left" class="container" border='1'>
		<div id="header_box">
		<br/>
			<div class="bbs_title">
				<h3>댓글 수정</h3>
				<hr>
			</div>
		</div>									
		<?php 
		session_start();
		
    		$db_username = "EXERCISE1";
    		$db_password = "exe880323";
    		$db = "oci:dbname=testdb.htenc.com:1521/ORCL";
    		$conn = new PDO($db,$db_username,$db_password);
    		$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    		
    		$sql = $conn -> prepare("SELECT UNO,USER_ID, USER_PWD, USER_NAME, ROLE FROM EX_USER_SET WHERE USER_ID=:USER_ID");
    		$sql -> bindParam("USER_ID",$_SESSION['USER_ID']);
    		
    		$sql -> execute();
    		$row = $sql -> fetch();
		?>
    		<table align="left" border="0" cellspacing="0">
    			<section class="reply-form">
				<form action="insert.php?mode=update" method="post" id="frmname" name="frmid" onsubmit="return false;">
				<input type="hidden" id="mode" name="mode" value="update"/>
				<input type="hidden" id="user_name" name="user_name" value="<?php echo $member["user_name"];?>" />
				<input type="hidden" id="uno" name="uno" value="<?php echo $uno;?>" />
				<tr>
					<td width='600px'>
					<input type="hidden" id="num" name="num" value="<?php echo $num;?>" />
					<h2>닉네임: <?php
                    echo $member['USER_NAME'];
                     ?></h2>
					</td>
				</tr>
				<tr>
				<td width="50" colspan="1"> 
            		  <div class="form-floating">
                        <input id="password" name="password" type="password" placeholder="Password" style="width:200px">
                      </div>
                 </td>
				</tr>
				<tr>
					<td>
    				<?php 
    				    echo "<textarea name='content' id='content' class='form-control' required >{$member["content"]}</textarea>";
    				?>
					</td>
					<td>
                     <button type="submit" class="btn btn-warning" onclick="goSubmit();" title="수정">수정</button></a>
                     </td>
				</tr>
				</form>
			</section>
    		</table>
	
</body>
</html>