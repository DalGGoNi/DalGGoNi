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
<title>로그인</title>
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
				<h1>로그인</h1>
				<hr>
			</div>
		</div>									
    		<fieldset>
    		<table align="center" border="0" cellspacing="0" width="300">
    			<form method="post" action="login_check.php">
    			<input type="hidden" id="mode" name="mode" value="login" />
    			<tr>
        			<td width="200" colspan="1"> 
            		 <div class="form-floating mb-3">
                        <input id="user_id" name="user_id" type="text" class="form-control" placeholder="name@example.com">
                        <label for="floatingInput">ID</label>
                      </div>
        			</td>
        			<td rowspan="2" align="center" width="100" > 
            		<button type="submit" id="login-submit" class="btn btn-primary">로그인</button>
        			</td>
    			</tr>
        		<tr>
        			<td width="200" colspan="1"> 
            		  <div class="form-floating">
                        <input id="password" name="password" type="password" class="form-control" placeholder="Password">
                        <label for="floatingPassword">Password</label>
                      </div>
                     </td>
    			</tr>
    			</form>
    			<tr align="center">
					<td colspan=2><a href="find_id.php" class="glanlink">아이디 찾기</a> | <a href="find_password.php" class="glanlink">비밀번호 찾기</a></td>
    			</tr>
    			<tr>
       				<td colspan="3" align="center" class="mem"> 
       				<a class='glanlink' href="<?php echo $_login_uri; ?>">
          			<button type="button" class="btn btn-primary">회원가입</button></a>
       				</td>
    			</tr>
    
    		</table>
			</fieldset>		
</body>
</html>