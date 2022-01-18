
<?php
@ini_set("display_errors", "On");
//@error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
if(!defined("_INCLUDE_")) require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/include.php";
@error_reporting(E_ALL^ E_WARNING);
session_start();
ini_set("display_errors", 1);

$USER_ID = $_POST['user_id'];
$PASSWORD = $_POST['password'];
$EMAIL = $_POST['email'];
$USER_NAME = $_POST['user_name'];
$db_username = "EXERCISE1";
$db_password = "exe880323";
$db = "oci:dbname=testdb.htenc.com:1521/ORCL";
$conn = new PDO($db,$db_username,$db_password);
$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );



if($post["mode"]=='login'){
    $stmt = $conn->prepare("SELECT USER_ID, USER_PWD, USER_NAME, EMAIL FROM EX_USER_SET WHERE USER_ID=:USER_ID");
    $stmt->bindParam(':USER_ID', $USER_ID, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    try {
        if($USER_ID=="" || $PASSWORD==""){
            echo "<script>alert('빈칸을 확인해주세요.');</script>";
            echo "<script>location.href='login.php';</script>";    
        } 
        if($USER_ID==$row['USER_ID'] && $PASSWORD==$row['USER_PWD']){
    
            echo "<script>alert('로그인 되었습니다!');</script>";
            echo "<script>location.href='list.php';</script>";
        }
        else{
            echo "<script>alert('아이디와 비밀번호를 확인해주세요.');</script>";
            echo "<script>history.back();</script>";
        }
        $_SESSION['USER_ID']=$row['USER_ID'];
        $_SESSION['USER_NAME']=$row['USER_NAME'];
    
    } catch (Exception $e) {
        echo 'Connect failed : ' . $e->getMessage() . '';
    
    }
}
else if($post["mode"]=='find_id'){
    try {
        $USER_NAME = iconv("UTF-8", "CP949", $USER_NAME);
        $stmt = $conn->prepare("SELECT USER_ID, USER_PWD, USER_NAME, EMAIL FROM EX_USER_SET WHERE USER_NAME=:USER_NAME");
        $stmt->bindParam(':USER_NAME', $USER_NAME, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($USER_NAME=="" || $EMAIL==""){
            echo "<script>alert('빈칸을 확인해주세요.');</script>";
            echo "<script>location.href='find_id.php';</script>";
        }
        if($USER_NAME==$row['USER_NAME'] && $EMAIL==$row['EMAIL']){
            echo  Fun::alert("아이디는 {$row['USER_ID']} 입니다.", "login.php");
        }
        else{
            echo "<script>alert('이름과 이메일을 확인해주세요.');</script>";
            echo "<script>history.back();</script>";
        }
        $_SESSION['USER_ID']=$row['USER_ID'];
        $_SESSION['USER_NAME']=$row['USER_NAME'];
        
    } catch (Exception $e) {
        echo 'Connect failed : ' . $e->getMessage() . '';
        
    }
}
else if($post["mode"]=='find_password'){
    try {
        $USER_NAME = iconv("UTF-8", "CP949", $USER_NAME);
        $stmt = $conn->prepare("SELECT USER_ID, USER_PWD, USER_NAME, EMAIL FROM EX_USER_SET WHERE USER_ID=:USER_ID");
        $stmt->bindParam(':USER_ID', $USER_ID, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($USER_ID="" || $USER_NAME=="" || $EMAIL==""){
            echo "<script>alert('빈칸을 확인해주세요.');</script>";
            echo "<script>history.back();</script>";
        }
        else{

            if($post["user_id"]==$row['USER_ID'] && $USER_NAME==$row['USER_NAME'] && $EMAIL==$row['EMAIL']){
                echo  Fun::alert("비밀번호는 {$row['USER_PWD']} 입니다.", "login.php");
            }
            else{
                echo "<script>alert('이름과 이메일을 확인해주세요.');</script>";
                echo "<script>history.back();</script>";
            }
            $_SESSION['USER_ID']=$row['USER_ID'];
            $_SESSION['USER_NAME']=$row['USER_NAME'];
            }
    } catch (Exception $e) {
        echo 'Connect failed : ' . $e->getMessage() . '';
        
    }
}
?>