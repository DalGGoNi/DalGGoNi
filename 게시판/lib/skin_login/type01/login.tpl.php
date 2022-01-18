<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/include.php";
?>
<html>
<head>
<title>(주)하이테크엔지니어링</title>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<link rel="stylesheet" type="text/css" href="css/style_main.css">
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
}
#Layer1 {
	position:absolute;
	width:379px;
	height:137px;
	z-index:1;
}
-->
</style>
<SCRIPT LANGUAGE="JavaScript">
<!--
	function bluring(){
    if(event.srcElement.tagName=="A"||event.srcElement.tagName=="IMG") document.body.focus();
     }
    document.onfocusin=bluring;

	function fn_Login(sUrl){

	 var	w = screen.availWidth;
	 var	h = screen.availHeight - 50 ;
		oWin = window.open("main.htm","yswebmap","status=yes,toolbar=no,menubar=no,location=no,fullscreen=no,top=0,left=0,resizable=no,scrollbars=no,width="+w+",height="+h);
			oWin.focus();

				if (oWin == null)
				{
					alert('현재사이트의 팝업을 항상 허용 해주십시오.');

					//locaton.href='';
				}
				else
				{
					//self.opener=self;
					//self.close();
				}

			}
//-->
</SCRIPT>
</head>
<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="middle"><table width="100%" border="0" cellpadding="0" cellspacing="0" background="images/bg.gif">
      <tr>
        <td align="center"><table width="680" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
          <tr>
            <td align="center">
			<!-- 이미지타이틀 시작 -->
			<img src="images/mi_top.gif" width="680" height="307"></td>
          </tr>
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="22%" valign="top"><img src="images/mi_groupware.gif" width="149" height="128" border="0"></td>
                <td width="78%" valign="top"><table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td><img src="images/mi_logintop.gif" width="509" height="8"></td>
                  </tr>
                  <tr>
                    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="3%"><img src="images/mi_loginleft.gif" width="16" height="110"></td>
                        <td width="53%" align="center">
						<!-- 로그인폼 시작 -->
                          <!--
                           * Created on 2007. 2. 14
                           * Script by jhpark
                          -->
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
						<!--
						<table width="90%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="20%"><img src="images/mi_id.gif" width="48" height="34"></td>
                            <td width="52%"><input name="id" type="text" class="input"  style="width:120"></td>
                            <td width="28%" rowspan="2"><a href="biz_list.php"><img src="images/mi_loginbutton.gif" width="68" height="61" border="0"></a></td>
                          </tr>
                          <tr>
                            <td><img src="images/mi_pwd.gif" width="48" height="33"></td>
                            <td><input name="pwd" type="password" class="input"  style="width:120"></td>
                            </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td><input name="saveid" type="checkbox"> 아이디기억</td>
                            <td>&nbsp;</td>
                          </tr>
                        </table>
                        -->
						<!-- 로그인폼 끝 -->
						</td>
                        <td width="44%">
						<!-- 시스템 사용안내 시작 -->
						<img src="images/mi_login_helf.gif" width="224" height="110"></td>
                        </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td><img src="images/mi_loginbottom.gif" width="509" height="10"></td>
                  </tr>
                </table></td>
                <td width="0%" align="right" valign="top"><img src="images/mi_loginright.gif" width="22" height="128"></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><img src="images/mi_down.gif" width="680" height="48"></td>
          </tr>
        </table></td>
        </tr>
    </table></td>
  </tr>
</table>
</body>
