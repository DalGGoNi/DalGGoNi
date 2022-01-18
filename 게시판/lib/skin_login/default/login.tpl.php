<!--
 * Created on 2007. 2. 14
 * Script by jhpark
-->
<link rel="stylesheet" type="text/css" href="css/style_main.css" />
<table width="250" border="0" cellspacing="0" cellpadding="0">
<form id="frmOutLogin" name="frmOutLogin" action="{로그인페이지}" method="POST">
  <tr>
    <td style="width:20%"><img alt="" src="images/mi_id.gif" width="48" height="34" /></td>
    <td style="width:52%"><input name="userid" type="text" class="loginbox"  style="width:120" tabindex="1" value="{입력한ID}" /></td>
    <td style="width:28%" rowspan="2"><input alt="로그인" type="image" src="images/mi_loginbutton.gif" width="68" height="61" tabindex="3" /></td>
  </tr>
  <tr>
    <td><img alt="" src="images/mi_pwd.gif" width="48" height="33" /></td>
    <td><input name="userpwd" type="password" class="loginbox"  style="width:120"  tabindex="2" /></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input name="saveid" type="checkbox" tabindex="4" /><span class="loginSaveIDTxt">아이디기억</span></td>
    <td>&nbsp;</td>
  </tr>
</form>
</table>
