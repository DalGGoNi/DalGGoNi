<?php
@ini_set("display_errors", "On");
//@error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);

if(!defined("_INCLUDE_")) require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/include.php";
@error_reporting(E_ALL^ E_WARNING^E_DEPRECATED);

include "_box.php";
//Fun::print_r($post);
$db = new DB;

Fun::trimAll($post);
$CONTENT = $post["content"];
$title = $post["title"];
$tag = $post["tag"];
if($tag == 'null'){
    $tag=null;
}
$CONTENT = Fun::convVal2DB($CONTENT);
$title = Fun::convVal2DB($title);
//Fun::convVal2DBAll($post);
if($post["mode"] == "modify" && $post["uno"] != ""){


        $db->BeginTransaction();
        try{
            $target_dir = "./upload/";
            $target_file = $target_dir . basename(str_replace(" ","_",($_FILES["fileToUpload"]["name"])));
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            if(isset($_POST["submit"])) {
                $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                if($check !== false) {
                    echo "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                    echo "File is not an image.";
                    $uploadOk = 0;
                }
            }
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    $filename = $_FILES["fileToUpload"]["name"];
                    $imgurl = "upload/". $_FILES["fileToUpload"]["name"];
                    $size = $_FILES["fileToUpload"]["size"];
                    $sql = "SELECT  UNO,IMAGE
                            FROM EX_IMAGE 
                            WHERE UNO={$post["uno"]}";
                    $db->query($sql);
                    $db->next_record();
                    $row = $db->Record;
//                     echo $post['uno'];
//                     echo $row['uno'];
//                     exit();
                    if($post["uno"] != $row["uno"])
                    {   
                        $sql = "insert into EX_IMAGE(UNO, IMAGE, IMGURL, IMG_SIZE) values({$post["uno"]},'$filename','$imgurl','$size')";
                        $db->query($sql);
                        $sql = "UPDATE EX_CONTENTS SET "
                            . "  CONTENT = '{$CONTENT}' "
                            . " , TITLE = '{$title}' "
                            . " , TAG = '{$tag}' "
                            . " WHERE UNO = '{$post["uno"]}'";
                        $db->query($sql);
                        $db->commit();
                    }
                    else
                    {
                    $sql = "UPDATE EX_IMAGE SET "
                        . "  IMAGE = '$filename' "
                        . " , IMGURL = '$imgurl' "
                        . " , IMG_SIZE = '$size' "
                        . " WHERE UNO = '{$post["uno"]}'";
                    $db->query($sql);
                    $sql = "UPDATE EX_CONTENTS SET "
                        . "  CONTENT = '{$CONTENT}' "
                        . " , TITLE = '{$title}' "
                        . " , TAG = '{$tag}' "
                        . " WHERE UNO = '{$post["uno"]}'";
                    $db->query($sql);
                    $db->commit();
                    }
                }else{
                $sql = "UPDATE EX_CONTENTS SET "
                    . "  CONTENT = '{$CONTENT}' "
                    . " , TITLE = '{$title}' "
                    . " , TAG = '{$tag}' "
                    . " WHERE UNO = '{$post["uno"]}'";
                    
                $db->query($sql);
                $db->commit();
                }
            }
        } catch (Exception $ex) {
            $db->rollback();
            echo $ex;
            exit;
        }
        $db->EndTransaction();
        Fun::alert("정상적으로 수정 완료하였습니다.", "{$_view_uri}&uno={$post['uno']}");
}
else if($post["mode"] == "write1"){
    

    try{
        $target_dir = "./upload/";
        $target_file = $target_dir . basename(str_replace(" ","_",($_FILES["fileToUpload"]["name"])));
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $filename = $_FILES["fileToUpload"]["name"];
                $imgurl = "upload/". $_FILES["fileToUpload"]["name"];
                $size = $_FILES["fileToUpload"]["size"];
                $NO = $db->nextid("SEQ_" . $_table_user);
                $sql = "INSERT INTO EX_CONTENTS ("
                    . " USER_NAME,UNO, CONTENT, TITLE, REG_DATE, HIT, TAG "
                    . ") VALUES ("
                    . " '{$post["user_name"]}',{$NO},'{$CONTENT}', '{$title}', SYSDATE, 0, '{$tag}'"
                    . ")";
                    $db->query($sql);
                $sql = "insert into EX_IMAGE(UNO, IMAGE, IMGURL, IMG_SIZE) values({$NO},'$filename','$imgurl','$size')";
                    $db->query($sql);
                    $db->commit();
                }
                else{
                    $NO = $db->nextid("SEQ_" . $_table_user);
                    $sql = "INSERT INTO EX_CONTENTS ("
                        . " USER_NAME,UNO, CONTENT, TITLE, REG_DATE, HIT, TAG "
                        . ") VALUES ("
                        . " '{$post["user_name"]}',{$NO},'{$CONTENT}', '{$title}', SYSDATE, 0, '{$tag}'"
                        . ")";
                    $db->query($sql);
                    $db->commit();
                }
            }
    } catch (Exception $ex) {
        $db->rollback();
        echo $ex;
        exit;
    }
    $db->EndTransaction();
    Fun::alert("게시물이 등록 되었습니다.", "list.php");

}
else if($post["mode"] == "like"){
    try{
        if($post["like"]){
            $us_id = $post['us_id'];
            $co_id = $post['co_id'];
            if($us_id==null){
                echo $us_id;
                echo "<script>location.href='javascript:history.back()'</script>";
                exit();
            }
            
            
                $sql = "MERGE 
                         INTO EX_LIKE LI
                        USING dual
                           ON (LI.CO_ID = $co_id AND LI.US_ID=$us_id)
                         WHEN MATCHED THEN
                              UPDATE SET LI. RG_DATETIME = SYSDATE
                              DELETE WHERE(LI.CO_ID=$co_id)
                         WHEN NOT MATCHED THEN
                              INSERT (LI.CO_ID, LI.US_ID)
                              VALUES ($co_id, $us_id)";
                $db->query($sql);
                $db->commit();
                echo "<script>location.href='javascript:history.back()'</script>";
            
        }
    }catch(Exception $ex){
        $db->rollback();
        echo $ex;
        exit;
    }
}
else if($post["mode"] == "hit"){
    try{
            $UNO = $post['uno'];
            $sql = "UPDATE EX_CONTENTS SET HIT = HIT+1
                    WHERE UNO = '$UNO'";
            $db->query($sql);
            $db->commit();
            echo "<script>location.href='{$_view_uri}&uno={$post["uno"]}'</script>";
    }catch(Exception $ex){
        $db->rollback();
        echo $ex;
        exit;
    }
}
else if($post["mode"] == "delete"){
    try{
        $UNO = $post['uno'];
        $NUM = $post['num'];
        $sql = "DELETE FROM EX_TEST 
                    WHERE NUM = '$NUM'";
        $db->query($sql);
        $db->commit();
        echo "<script>location.href='contents.php?&uno={$post["uno"]}'</script>";

    }catch(Exception $ex){
        $db->rollback();
        echo $ex;
        exit;
    }
}
else if($post["mode"] == "delete_content"){
    try{
        $UNO = $post['uno'];
        $NUM = $post['num'];
        $sql = "DELETE FROM EX_CONTENTS
                    WHERE UNO = '$UNO'";
        $db->query($sql);
        $db->commit();
        echo "<script>location.href='list.php'</script>";
        
    }catch(Exception $ex){
        $db->rollback();
        echo $ex;
        exit;
    }
}
else if($post["mode"] == "pre"){
    try{
        $UNO = $post['uno'];
        $sql = "SELECT * 
                FROM EX_CONTENTS 
                WHERE UNO=(
                            SELECT MAX(UNO) 
                            FROM EX_CONTENTS
                            WHERE UNO < '$UNO'
                            AND TAG IS NULL)
                ";
        $db->query($sql);
        $db->next_record();
        $member = $db->Record;
        if(!$member["uno"]){
            echo Fun::alert("이전글이 존재하지 않습니다.");
        }else{
            echo "<script>location.href='contents.php?&uno={$member["uno"]}'</script>";
        }
    }catch(Exception $ex){
        $db->rollback();
        echo $ex;
        exit;
    }
}
else if($post["mode"] == "odd"){
    try{
        $UNO = $post['uno'];
        $sql = "SELECT *
                FROM EX_CONTENTS
                WHERE UNO=(
                            SELECT MIN(UNO)
                            FROM EX_CONTENTS
                            WHERE UNO > '$UNO'
                            AND TAG IS NULL)
                ";
        $db->query($sql);
        $db->next_record();
        $member = $db->Record;
        if(!$member["uno"]){
            echo Fun::alert("다음글이 존재하지 않습니다.");
        }else{
            echo "<script>location.href='contents.php?&uno={$member["uno"]}'</script>";
        }
    }catch(Exception $ex){
        $db->rollback();
        echo $ex;
        exit;
    }
}
else if($post["mode"] == "stupre"){
    try{
        $UNO = $post['uno'];
        $sql = "SELECT *
                FROM EX_CONTENTS
                WHERE UNO=(
                            SELECT MAX(UNO)
                            FROM EX_CONTENTS
                            WHERE UNO < '$UNO'
                            AND TAG = 'study')
                ";
        $db->query($sql);
        $db->next_record();
        $member = $db->Record;
        if(!$member["uno"]){
            echo Fun::alert("이전글이 존재하지 않습니다.");
        }else{
            echo "<script>location.href='contents.php?&uno={$member["uno"]}'</script>";
        }
    }catch(Exception $ex){
        $db->rollback();
        echo $ex;
        exit;
    }
}
else if($post["mode"] == "stuodd"){
    try{
        $UNO = $post['uno'];
        $sql = "SELECT *
                FROM EX_CONTENTS
                WHERE UNO=(
                            SELECT MIN(UNO)
                            FROM EX_CONTENTS
                            WHERE UNO > '$UNO'
                            AND TAG = 'study')
                ";
        $db->query($sql);
        $db->next_record();
        $member = $db->Record;
        if(!$member["uno"]){
            echo Fun::alert("다음글이 존재하지 않습니다.");
        }else{
            echo "<script>location.href='contents.php?&uno={$member["uno"]}'</script>";
        }
    }catch(Exception $ex){
        $db->rollback();
        echo $ex;
        exit;
    }
}
else if($post["mode"] == "delete_file"){
    try{
        $UNO = $post['uno'];
        $NUM = $post['num'];
        $sql = "DELETE FROM EX_IMAGE
                    WHERE UNO = '$UNO'";
        $db->query($sql);
        $db->commit();
        echo "<script>location.href='{$_modify_uri}&uno={$post["uno"]}'</script>";
    }catch(Exception $ex){
        $db->rollback();
        echo $ex;
        exit;
    }
}
else if($post["mode"] == "modify_comment"){
    try{
        $sql = "UPDATE EX_TEST SET "
            . "  CONTENT = '{$CONTENT}' "
            . " , REG_DATE = SYSDATE "
            . " WHERE UNO = '{$post["num"]}'";
        $db->query($sql);
        $db->commit();
        echo "<script>location.href='{$_view_uri}&uno={$post["uno"]}'</script>";
    }catch(Exception $ex){
        $db->rollback();
        echo $ex;
        exit;
    }
}
else{
    Fun::alert("정상적인 방법으로 접근하여 주세요. 참조 오류 입니다.");
}
?>