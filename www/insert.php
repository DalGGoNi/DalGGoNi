<?PHP
session_start();
@ini_set("display_errors", "On");
// @error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
if (! defined("_INCLUDE_"))
    require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/include.php";
@error_reporting(E_ALL^E_WARNING);
include "_box.php";
$pdb = new DB();
$NAME = $_POST['user_name'];
$CONTENT = $_POST['content'];
$CONTENT = Fun::convVal2DB($CONTENT);
$UNO = $_POST['uno'];
$password = $post["password"];
$num = $post["num"];
$role = $post["role"];
if($post["mode"]=="update"){
    try {
        $NAME = iconv("UTF-8", "CP949", $NAME);
        $db_username = "EXERCISE1";
        $db_password = "exe880323";
        $db = "oci:dbname=testdb.htenc.com:1521/ORCL";
        $conn = new PDO($db,$db_username,$db_password);
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        

        $sql = $conn -> prepare("SELECT UNO,USER_ID, USER_PWD, USER_NAME, ROLE FROM EX_USER_SET WHERE USER_ID=:USER_ID");
        $sql -> bindParam("USER_ID",$_SESSION['USER_ID']);
        
        $sql -> execute();
        $row = $sql -> fetch();
        if($password == $row["USER_PWD"] || $role == "ADMIN"){
            $sql = "UPDATE EX_TEST SET CONTENT = '{$CONTENT}'
                    WHERE NUM = '{$num}'";
            $pdb->query($sql);
            $pdb->commit();
            echo "<script>alert('수정 되었습니다.');</script>";
            echo "<script>opener.location.href ='contents.php?&uno=$UNO';</script>";
            echo "<script>window.close();</script>";         
        }
        else{
            echo Fun::alert("비밀번호가 다릅니다.");
        }
    } catch (Exception $e) {
        echo 'Connect failed : ' . $e->getMessage() . '';
        
    }
}
else{
    try {
        if ($CONTENT=='') {
            echo "<script>alert('정보를 입력하세요.')</script>";
            echo "<script>location.href='javascript:history.back()'</script>";
        } else {
            $sql = "INSERT INTO {$_table_test}(UNO, USER_NAME, CONTENT) VALUES ('$UNO','$NAME', '$CONTENT')";
            $pdb->query($sql);
            $pdb->commit();
            echo "<script>location.href='{$_view_uri}&uno={$post["uno"]}'</script>";
        }
    } catch (Exception $e) {
        echo 'Connect failed : ' . $e->getMessage() . '';
        
    }
}

?>
<script>
	function alert_replace($target='contents.php',$close)
</script>