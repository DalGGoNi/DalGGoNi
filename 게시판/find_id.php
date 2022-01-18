<?php
@ini_set("display_errors", "On");
//@error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
if(!defined("_INCLUDE_")) require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/include.php";
@error_reporting(E_ALL);
include './_box.php';
?>
<!DOCTYPE html>
<html lang="ko">

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Style-Type" content="text/css">
<link rel="stylesheet" href="bootstrap-5.1.3-dist/css/bootstrap.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> 
<script type="text/javascript" src="bootstrap-5.1.3-dist/js/bootstrap.js"></script>
<title>아이디 찾기</title>
<link rel="shortcut icon" href="android-icon.ico">
</head>

<body>
<input type="hidden" id="mode" name="mode" value="write" />
<input type="hidden" id="login_mode" name="login_mode" value="login" />
<input type="hidden" id="login_user_id" name="login_user_id" value="" />
<input type="hidden" id ="login_password" name="login_password" value="" />
<style>
   .glanlink{
        text-decoration:none;
    }
</style>
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
<div align="center" class="container" border='1'>
		<div id="header_box">
			<br />
			<div class="bbs_title">
				<h1>아이디 찾기</h1>
				<hr>
			</div>
		</div>									
    		<fieldset>
    		<table align="center" border="0" cellspacing="0" width="300">
    			<form method="post" action="login_check.php">
    			<input type="hidden" id="mode" name="mode" value="find_id" />
    			<tr>
        			<td width="200" colspan="2"> 
            		 <div class="form-floating mb-3">
                        <input id="user_name" name="user_name" type="text" class="form-control" placeholder="Name">
                        <label for="floatingInput">이름</label>
                      </div>
        			</td>

    			</tr>
        		<tr>
        			<td width="200" colspan="2"> 
            		  <div class="form-floating mb-3">
                        <input id="email" name="email" type="email" class="form-control" placeholder="name@example.com">
                        <label for="floatingPassword">이메일</label>
                      </div>
                     </td>
    			</tr>

    			<tr align="center">
					<td colspan=2><a href="find_password.php" class="glanlink">비밀번호 찾기</a></td>
    			</tr>
    			<tr>
       				<td align="left" class="mem"> 
       				<a class='glanlink' href="<?php echo $_login_uri; ?>">
          			<button type="button" class="btn btn-primary">회원가입</button></a>
       				</td>
       				<td align="right" width="100" > 
            		<input type="submit" id="login-submit" class="btn btn-primary" value="입력"></button>
        			</td>
    			</tr>	
    			</form>
    		</table>
			</fieldset>		
</body>
</html>