<?php
if(!defined("_INCLUDE_")) include_once $_SERVER["DOCUMENT_ROOT"] . "/lib/include.php";
/*
if(isset($Fun) != true){
	global $Fun;
}
if (isset($Fun) != true)
{
    $Fun = new Fun();
}
 * */

if(isset($post) != true){
	global $post;
}

if(isset($_COOKIE) && is_array($_COOKIE) && isset($_COOKIE["save_id"]) && isset($_COOKIE["save_user_id"]) && $_COOKIE["save_id"] && $_COOKIE["save_user_id"]){
    $login_save_id_checked = " checked";
    $login_save_id = $_COOKIE["save_user_id"];
} else {
    $login_save_id = $_POST["login_user_id"]??null;
    $login_save_id_checked = "";
}
$login_mode = $_REQUEST["login_mode"]??null;
if(!$login_mode){
    $login_mode = "login";
}
if(isset($_inc) != true){
	$_inc = array();
	//$Fun->print_r($_inc);
}
$_inc["login_mode"] = $login_mode??"";
$_inc["login_url"]  = Fun::getExpUrl("login_mode,login_user_id,login_password,input_user_id,input_password")??null;
$_inc["actMode"]    = "";
$_inc["action_mode"]= "";
if(isset($_inc["login_mode"]) == true){
	$_inc["actMode"] 	 = $_inc["login_mode"] . "_action";
	$_inc["action_mode"] = $_inc["login_mode"] . "_action";
}
?>
<style>
/* BEGIN 공통 */
/*
body {
  margin-left: 0px;
  margin-top: 0px;
}*/
/* CSS Document */

/* Main Positioning Containers */
body {font-size: 11px; font-family: 굴림체, Arial, Helvetica, sans-serif; padding:0; margin:0; color:#666666}
td {font-size: 11px; font-family: 굴림체, Arial, Helvetica, sans-serif;}
h1{font-family: 굴림, Arial, Helvetica, sans-serif;}
html,body{margin-left:10px; padding:0;}
img {border:0;}

/* Basic Formatting */
a {color:#666666; text-decoration:none; font-weight:normal;}
a:hover {color:#0784d5; text-decoration:underline; }
a:visited {color:#0784d5;}

.bluetext {color: #0784d5;}
.bluetextstrong {color: #0784d5;font-size:12px; font-weight:bold;}
.blacktext {color:#000000;}
.blacktextstrong {color:#000000; font-size:12px; font-weight:bold;}
.gray12px  {color:#666666; font-size:12px; font-family: Gulim, Arial, Helvetica, sans-serif;}
.bg-norepeat{background-repeat:no-repeat}
.bg-repeatx{background-repeat:repeat-x}
.bg-repeaty{background-repeat:repeat-y}

/* Search  */
.input {ime-mode:active;border:1px solid #c2c2c2; vertical-align:top; color:#666; font-size:12px;}
.shtab {color:#000000; font-size:12px; font-weight:bold; background-repeat:no-repeat}

/* 2depth  */
.top_sub{height:24px; padding-right:20px; color:#666666; font-size:12px; font-family: Gulim, Arial, Helvetica, sans-serif;}

  #submn01 {position:absolute; left:200px; top:100px; width:423px; height:30px; z-index:1; visibility: hidden;}
  #submn02 {position:absolute; left:288px; top:100px; width:423px; height:30px; z-index:1; visibility: hidden;}
  #submn03 {position:absolute; left:378px; top:100px; width:550px; height:30px; z-index:1; visibility: hidden;}
  #submn04 {position:absolute; left:470px; top:100px; width:314px; height:30px; z-index:1; visibility: hidden;}
  #submn05 {position:absolute; left:560px; top:100px; width:176px; height:30px; z-index:1; visibility: hidden;}

/* table  */
.total_text {color:#000000; font-size:11px; font-family:Arial}
.total_text a {color:#333399; font-size:11px; font-family:Arial}
.tb_title {font-size: 12px; font-weight: bold; color: #333399; background-color: #eaedff; text-align: center; padding-top: 13px; padding-bottom: 7px;vertical-align: text-bottom;}
.tb_text  {font-size: 12px; background-color: #ffffff; padding-top: 13px; padding-left: 4px;padding-bottom: 7px;vertical-align: text-bottom;}

.td1 {
  background-color: #EDF4F8;
  padding:2px;
  font-size:12px;
}
.td2 {
  background-color: #FFFFFF;
  padding:2px;
  font-size:12px;
}
.td3 {
  background-color: #EFEFEF;
  text-align: center;
  font-size:12px;
}
.td4 {
  background-color: #EFEFEF;
  text-align: left;
  font-size:12px;
}
.td5 {
  background-color: #EDF4F8;
  padding:2px;
  text-align: center;
  font-size:12px;
}
.td6 {
  background-color: #FFE4B5;
  padding:2px;
  text-align: center;
  font-size:12px;
}
.td7 {
  background-color: #FFFAFA;
  padding:2px;
  text-align: center;
  font-size:12px;
}
.small a:hover { font-family: "돋움", "arial"; font-size: 9pt; color: #5DC011; text-decoration: underline}
.small a:link { font-family: "돋움", "arial"; font-size: 9pt; color: #ff0000; text-decoration: underline}

.bar_vline {
  background-attachment: fixed;
  background-image: url('images/bar_vline.gif');
  background-repeat: repeat-x;
  background-position: left bottom;
}
.bar_under_line {
  background-image: url('/images/under_line.gif');
  background-repeat: repeat-x;
  background-position: left bottom;
}
.HeadTitle {
  font-size: 16px;
  font-weight: bold;
}
.footer {color:#000000; font-size:11px; font-family:Arial}

.about_alpha{list_style:lower_alpha;}
p{line-height:120%}
</style>
<script language="javascript" type="text/javascript" src="/lib/js/md5.min.js"></script>
<script language="javascript" type="text/javascript">

function ccc(){
	alert('aa');
}
function checkSaveId(obj){
	//alert(obj);
    if (obj && obj.checked){
        if (confirm('아이디 저장을 사용하시면 다음부터 회원아이디를 입력하실 필요가 없습니다.\n\n\공공장소에서는 개인정보가 유출될 수 있으니 사용을 자제하여 주십시오.\n\n아이디 저장을 사용하시겠습니까?')) {
            obj.checked = true;
        } else {
            obj.checked = false;
        }
    }
}
function checkLoginSubmit(f){
	
	if(f == null){
		//alert("null");
		return false;
	}
	
	
	f.login_user_id.value = "";
	f.login_password.value = "";
    if(!f.input_user_id.value.trim()){
        alert("회원아이디를 입력하여주세요.");
		//f.login_user_id.value = f.login_user_id.value.trim();
        f.input_user_id.focus();
        return false;
    } else if(!f.input_password.value){
        alert("비밀번호를 입력하여주세요.");
        f.input_password.focus();
        return false;
    } else {
		f.login_user_id.value = f.input_user_id.value;
		//var strMD5 = md5(f.input_password.value);
		f.login_password.value = f.input_password.value;
		//alert(strMD5);
        return true;
    }
    return false;
}
function LoginFormOnLoad(){
	document.forms["loginForm"].login_user_id.value = "";
	document.forms["loginForm"].login_password.value = "";
    if(document.forms["loginForm"].login_save_id.checked || "<?php echo $login_save_id; ?>" != ""){
        document.forms["loginForm"].input_user_id.value = "<?php echo $login_save_id; ?>";
        document.forms["loginForm"].input_password.focus();
	} else {
        document.forms["loginForm"].input_user_id.value = "<?php echo $login_save_id; ?>";
        document.forms["loginForm"].input_user_id.focus();
    }
}
try{
    window.onload = function(){
        //이넘이 먼저 실행
        LoginFormOnLoad();
    }
	/*
    function window::onload(){
        //이넘이 뒤에 실행
        //LoginFormOnLoad();
    };
	*/
} catch(e){
    ;;
}

</script>
<div id="layerLogin">
<script type="text/javascript" src="/lib/js/capslock.js"></script>
<form style="margin: 0px" name="loginForm" onsubmit="return checkLoginSubmit(this);" action="<?php echo $_inc["login_url"];?>" method="post" autocomplete="off">
<input type="hidden" id="login_mode" name="login_mode" value="login" />
<input type="hidden" id="login_user_id" name="login_user_id" value="" />
<input type="hidden" id ="login_password" name="login_password" value="" />
<table height="302" cellspacing="0" cellpadding="0" width="100%" border="0">
  <tr>
<td style="padding-top: 129px" vAlign="top" align="middle">
<table cellSpacing="0" cellPadding="0" width="532" border="0">
<tr>
<?php
if( isset($_REQUEST) && is_array($_REQUEST) && isset($_REQUEST["status"]) && $_REQUEST["status"] === "popup"){
	echo '<td style="PADDING-LEFT: 31px; PADDING-TOP: 75px" vAlign="top" background="/lib/images/login/box_popup.png" height="302">';
} else {
	echo '<td style="PADDING-LEFT: 31px; PADDING-TOP: 75px" vAlign="top" background="/lib/images/login/box.png" height="302">';
}
?>

<table cellspacing="0" cellpadding="0" width="470" border="0">
<tr>
<td vAlign="top" height="184"><img height="28" src="/lib/images/login/bar.gif" width="164" /><br />
<table cellspacing="0" cellpadding="0" width="270" border="0">
<tr>
<td class="bluetext" style="padding-left: 16px" vAlign="bottom" width="86" height="44"><img height="5" alt="화살표" src="/lib/images/login/aw_blue.gif" width="3" align="absMiddle" /> 아이디</td>
<td vAlign="bottom" width="133"><input id="input_user_id" name="input_user_id" style="ime-mode: inactive; width: 123px; height: 19px" tabIndex="1" minlength="4" required="" itemname="아이디" /></td>
<td vAlign="bottom" align="right" width="21" rowSpan="2"><input tabIndex="3" type="image" height="47" alt="로그인" width="51" src="/lib/images/login/bt_login.gif" /></td>
</tr>
<tr>
<td class="bluetext" style="padding-left: 16px" vAlign="bottom" height="26"><img height="5" alt="화살표" src="/lib/images/login/aw_blue.gif" width="3" align="absMiddle" /> 비밀번호</td>
<td vAlign="bottom"><input type="password" id="input_password" name="input_password" value="" onkeypress="check_capslock(event, 'input_password');" style="IME-MODE: inactive; WIDTH: 123px; HEIGHT: 19px" tabIndex="2"  minlength="4" required="" itemname="패스워드" /></td>
</tr>
<tr>
<td vAlign="bottom" align="middle" colSpan="3" height="33"><input type="checkbox" id="login_save_id" onclick="checkSaveId(this)" value="1" name="login_save_id" <?php echo $login_save_id_checked;?> /><font color="#7d7d7d">아이디 저장</font>
<!--&nbsp;&nbsp;<a href="#"><font color="#00aeef">회원가입</font></a><font color="#c3ecfb">|</font><a href="#"><font color="#00aeef">ID/PW찾기</font></a>-->
</td>
</tr>
</table>
<table width="400">
<tr>
<td align="center"><br><br>시스템 오류 문의 : 기술연구소</td>
</tr>
</table>
</td>
</tr>
<tr>
<td class="footer" align="middle" background="/lib/images/login/bg_copy.gif" height="24"><span class="footer">Copyright (c) 2009-<?php echo date("Y"); ?> <b><font color="#0072bc">Hi-Tech Engineering Co., Ltd.</font></b> All Rights Reserved.</span></td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
</form>
</div>

<?php
//if(defined("_HEADER_INCLUDE_")){
    //@require dirname(__FILE__) . "/../footer.php";
//}
?>