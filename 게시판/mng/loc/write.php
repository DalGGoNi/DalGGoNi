<?php include __DIR__ . '/_inc.php';

$db = new DB;

$NO = null;

$sql = "SELECT * FROM {$_table_loc_code} WHERE 1 = 1
";

if($post["mode"] == "write")
{
    $duty_id = null;
    $dept_id = null;
    $rec = array();
} 
else if($post["mode"] == "modify")
{
    $NO = $_REQUEST["loc_no"];
    $sql .= " AND LOC_NO = {$NO}";
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
                
                if(!frm.loc_name.value){
                    alert("지역 명칭을 입력하여 주세요.");
                    frm.loc_name.focus();
                }
                else if(!frm.loc_code.value){
                    alert("지역 코드를 입력하여 주세요.");
                    frm.loc_code.focus();
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
<form id="frmid01" name="frmname01" method="post" action="action.php" onsubmit="return false;">
<input type="hidden" id="mode" name="mode" value="<?php echo $post["mode"];?>" />
<input type="hidden" id="loc_no" name="loc_no" value="<?php echo $NO;?>" />
<input type="hidden" id="id_check" name="id_check" value="" />
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
                    <th class="th_col">관리번호</th>
                    <td>
                        <?php echo $rec["loc_no"]??"&nbsp;(추가모드)"; ?>
                    </td>
                </tr>
                <tr>
                    <th>지역 명칭</th>
                    <td><input type="text" id="loc_name" name="loc_name" value="<?php echo $rec["loc_name"]??null; ?>" required /></td>
                </tr>
                <tr>
                    <th>지역 코드</th>
                    <td><input type="text" id="loc_code" name="loc_code" value="<?php echo $rec["loc_code"]??null; ?>" required /></td>
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