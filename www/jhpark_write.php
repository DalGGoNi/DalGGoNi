<?php
@ini_set("display_errors", "On");
//@error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
if(!defined("_INCLUDE_")) require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/include.php";
@error_reporting(E_ALL);
include "_inc.php";

$db = new DB;

$NO = null;

$sql = "WITH A AS
(
SELECT 
    U.*
FROM EX_USER_SET U 

WHERE 1 = 1
)
SELECT * FROM A
WHERE 1 = 1
";

if($post["mode"] == "write")
{
    $rec = array();
} 
else if($post["mode"] == "modify")
{
    $NO = $_REQUEST["uno"];
    $sql .= " AND UNO = {$NO}";
	//Fun::print_($sql);
	//exit;
	$db->query($sql);
	$db->next_record();
	$rec = $db->Record;
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
        <!--<meta http-equiv="refresh" content="0;url=http://www.daum.net">-->
        <title>List Page</title>
        <link rel="stylesheet" type="text/css" href="http://www.htenc.co.kr/css/style.css" />
        <style type="text/css">
            .styleguide th
            {
                background-color: #c0c0c0;
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
            function goList(){
                location.href = "<?php echo $_list_uri; ?>";
            }
            function goSubmit(){
                var frm = document.forms["frmname01"];
<?php
if($post["mode"] == "write")
{
                echo 'if(frm.mode.value == "write" && !frm.user_id.value){
                    alert("사용자 ID를 입력하여 주세요.");
                    frm.user_id.focus();
                    return;
                }
                else if(frm.user_id_check.value == ""){
                    alert("사용자 ID중복을 체크하여 주세요.");
                    frm.user_id.focus();
                    return;
                }
                else if(frm.user_id_check.value == "false"){
                    alert("사용자 ID가 중복됩니다.다른 아이디를 입력 후 중복체크하여 주세요.");
                    frm.user_id.focus();
                    return;
                }';
}
?>
                
                if(!frm.user_name.value){
                    alert("사용자 명을 입력하여 주세요.");
                    frm.user_name.focus();
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
                xhttp.open("GET", "jhpark_action.php?mode=userid_check&user_id=" + document.getElementById("user_id").value );
                xhttp.send();
            }
        </script>
    </head>
<body>
<form id="frmid01" name="frmname01" method="post" action="jhpark_action.php" onsubmit="return false;">
<input type="hidden" id="mode" name="mode" value="<?php echo $post["mode"];?>" />
<input type="hidden" id="uno" name="uno" value="<?php echo $NO;?>" />
<input type="hidden" id="user_id_check" name="user_id_check" value="" />
    <div class="styleguide" style="text-align:center">
        <center>
            <h3 style="text-align: left; width: 800px"><img src="/lib/images/title_De.gif">직원 정보 <?php echo ($post["mode"] == "modify" ? " - 수정" : " - 추가"); ?></h3>
        <table style="width:800px">
            <colgroup>
                <col style="width:250px" />
                <col />
            </colgroup>
            <caption>사업 Reference</caption>
            <tbody>
                <tr>
                    <th class="th_col">USER_ID</th>
                    <td>
                        <?php
                        if($post["mode"] == "write"){
                            echo '<input type="text" id="user_id" name="user_id" value="" required /> <input type="button" id="btnUserIdCheck" name="btnUserIdCheck" value="중복체크" onclick="GoUserIdCheck(this);" />';
                        }
                        else {
                            echo $rec["user_id"]??null;
                        }
                        ?>
                        <div id="ajax_message">&nbsp;</div>
                    </td>
                </tr>
                <tr>
                    <th>사용자명</th>
                    <td><input type="text" id="user_name" name="user_name" value="<?php echo $rec["user_name"]??null; ?>" required /></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td><img src="/lib/images/btn_simple_list.gif" onclick="goList()" title="목록"/></td>
                    <td style="text-align:right"><img src="/lib/images/btn_simple_save.gif" onclick="goSubmit()" title="저장" align="right" /></td>
                </tr>
            </tfoot>
        </table>
        </center>
    </div>
	<!--<input type="submit" value="Submit" />-->
</form>
</body>
</html>