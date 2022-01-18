<?php include __DIR__ . '/_inc.php';
//Fun::print_r($post);
$db = new DB;

Fun::trimAll($post);
//Fun::convVal2DBAll($post);
function GetUserIDCheck($code_id){
    global $db;
    global $_table_loc_code;
    if($code_id){
        $n = $db->query_one("SELECT COUNT(*) AS CNT FROM {$_table_loc_code} WHERE LOC_CODE = '{$code_id}'");
        if($n > 0){
            return "<font style='color:red'>중복되셨어요</font>";
        }
        else
        {
            return null;
        }
    }
    else{
        return "<font style='color:red'>아이가 입력되지 않음.</font>";
    }
}

if($post["mode"] == "id_check" && @$post["loc_code"]){
    $code_id = $post["loc_code"];
    echo GetUserIDCheck($code_id);
    exit;
}
if($post["mode"] == "modify" && $post["loc_no"] != ""){
    
    if(!$post["loc_code"]){
        echo Fun::Msg_Box("오류!", "코드 정보 누락!");
        exit;
    }
    else if(!$post["loc_name"]){
        echo Fun::Msg_Box("오류!", "명칭 정보 누락!");
        exit;
    }
    else{
        $db->BeginTransaction();
        try{
            $sql = "UPDATE {$_table_loc_code} SET "
                . "   LOC_NAME = '{$post["loc_name"]}' "
                //. " , MOD_DATE = SYSDATE "
                . " WHERE LOC_NO = '{$post["loc_no"]}'";
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
    
    if(!$post["loc_code"]){
        echo Fun::Msg_Box("오류!", "코드 정보 누락!");
        exit;
    }
    else if(!$post["loc_name"]){
        echo Fun::Msg_Box("오류!", "명칭 정보 누락!");
        exit;
    }
    
    $resultChecked = GetUserIDCheck($post["loc_code"]);
    if($resultChecked){
        echo $resultChecked;
        exit;
    }
    $db->BeginTransaction();
        try{
            $NO = $db->nextid("SEQ_" . $_table_loc_code);
            $sql = "INSERT INTO {$_table_loc_code} ("
                    . " LOC_NO, LOC_NAME, LOC_CODE "
                    . ") VALUES ("
                    . " {$NO}, '{$post["loc_name"]}', '{$post["loc_code"]}' "
                    . ")";
            $db->query($sql);
            $db->commit();
        } catch (Exception $ex) {
            $db->rollback();
            echo $ex;
            exit;
        }
        $db->EndTransaction();
        Fun::alert("정상적으로 회원을 추가 완료하였습니다.", "list.php");
    
}
else{
    Fun::alert("정상적인 방법으로 접근하여 주세요. 참조 오류 입니다.");
}
?>