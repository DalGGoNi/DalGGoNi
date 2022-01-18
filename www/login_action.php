<?php
@ini_set("display_errors", "On");
//@error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
if(!defined("_INCLUDE_")) require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/include.php";
@error_reporting(E_ALL^ E_WARNING);

include "_inc.php";
//Fun::print_r($post);
$db = new DB;

Fun::trimAll($post);
//Fun::convVal2DBAll($post);
function GetUserIDCheck($user_id){
    global $db;
    global $_table_user;
    if($user_id){
        $n = $db->query_one("SELECT COUNT(*) AS CNT FROM {$_table_user} WHERE USER_ID = '{$user_id}'");
        if($n > 0){
            return "<font style='color:red'>중복된 아이디 입니다.</font>";
        }
        else
        {
            return "<font style='color:green'>중복되지 않은 아이디 입니다.</font>";
        }
    }
    else{
        return "<font style='color:red'>아이디가 입력되지 않음.</font>";
    }
}
if($post["mode"] == "userid_check" && @$post["user_id"]){
    $user_id = $post["user_id"];
    echo GetUserIDCheck($user_id);
    exit;
}

function GetUserNameCheck($user_name){
    global $db;
    global $_table_user;
    if($user_name){
        $n = $db->query_one("SELECT COUNT(*) AS CNT FROM {$_table_user} WHERE USER_NAME = '{$user_name}'");
        if($n > 0){
            return "<font style='color:red'>중복된 닉네임 입니다.</font>";
        }
        else
        {
            return "<font style='color:green'>사용 가능한 닉네임 입니다.</font>";
        }
    }
    else{
        return "<font style='color:red'>아이디가 입력되지 않음.</font>";
    }
}

if($post["mode"] == "username_check" && @$post["user_name"]){
    $user_name = $post["user_name"];
    echo GetUserNameCheck($user_name);
    exit;
}
if($post["mode"] == "modify" && $post["uno"] != ""){
    
    if(!$post["user_pwd"]){
        echo Fun::Msg_Box("오류!", "비밀번호 참조 오류!");
        exit;
    }
    else if(!$post["email"]){
        echo Fun::Msg_Box("오류!", "이메일 참조 오류!");
        exit;
    }
    else{
        $db->BeginTransaction();
        try{
            $sql = "UPDATE {$_table_user} SET "
                . " USER_PWD = '{$post["user_pwd"]}' "
                . " , EMAIL = '{$post["email"]}' "
                . " , MOD_DATE = SYSDATE "
                . " WHERE UNO = '{$post["uno"]}'";
            $db->query($sql);
            $db->commit();
        } catch (Exception $ex) {
            $db->rollback();
            echo $ex;
            exit;
        }
        $db->EndTransaction();
        Fun::alert("정상적으로 회원 정보를 수정 완료하였습니다.", "list.php");
    }
}
else if($post["mode"] == "write"){
    
    if(!trim($post["user_id"])){
        echo Fun::Msg_Box("오류!", "사용자 ID 참조 오류!");
        exit;
    }
    else if(!$post["user_pwd"]){
        echo Fun::Msg_Box("오류!", "비밀번호 참조 오류!");
        exit;
    }
    else if(!$post["user_name"]){
        echo Fun::Msg_Box("오류!", "사용자명 참조 오류!");
        exit;
    }
    else if(!$post["email"]){
        echo Fun::Msg_Box("오류!", "이메일 참조 오류!");
        exit;
    }
    $sql="SELECT * FROM EX_USER_SET WHERE USER_ID='{$post["user_id"]}' OR USER_NAME='{$post["user_name"]}'";
    $db->query($sql);
    $db->next_record();
    $member = $db->Record;
    if($post["user_id"]==$member["user_id"]){
        echo Fun::alert("중복된 아이디입니다.");
        exit;
    }elseif($post["user_name"]==$member["user_name"]){
        echo Fun::alert("중복된 닉네임입니다.");
        exit;
    }
    $db->BeginTransaction();
        try{
            $NO = $db->nextid("SEQ_" . $_table_user);
            $sql = "INSERT INTO {$_table_user} ("
            . " UNO, USER_ID, USER_NAME, USER_PWD, REG_DATE, MOD_DATE, EMAIL "
                . ") VALUES ("
                    . " {$NO}, '{$post["user_id"]}', '{$post["user_name"]}', '{$post["user_pwd"]}', SYSDATE, SYSDATE, '{$post["email"]}' "
                    . ")";
            $db->query($sql);
            $db->commit();
            
        } catch (Exception $ex) {
            $db->rollback();
            echo $ex;
            exit;
        }
        $db->EndTransaction();
        Fun::alert("정상적으로 회원을 추가 완료하였습니다.", "login.php");
    
}
else if($post["mode"]=="delete_id"){
    $uno=$post["uno"];
    $sql = "DELETE FROM EX_USER_SET
                WHERE UNO= '$uno'";
    $db->query($sql);
    $db->commit();
    session_start();
    session_destroy();
    echo "<script>location.href='list.php'</script>";
}
else{
    Fun::alert("정상적인 방법으로 접근하여 주세요. 참조 오류 입니다.");
}
?>