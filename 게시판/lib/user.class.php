<?php
require_once _LIB_PATH_ . "db_oci8.php";
class UserDB extends DB {
    var $Host     = _DB_Host_;
    var $Database = _DB_Name_;
    var $User     = _DB_User_;
    var $Password = _DB_Pass_;
    var $Debug    = 0;
}
//$userdb = new UserDB;
class User {
    var $db;
    var $_table = array();
    var $_col   = array();
    var $uno;
    var $user_id;
    /**
     * PHP5용 Class 생성자
     */
    function __construct(){
        $this->User();
    }
    function __destruct(){
        if($this->db != null && !$this->db->PConnect) {
          unset($this->db);
        }
        unset($_table);
        unset($_col);
      //echo "__destruct";
    }
    /**
     * Class 생성자
     */
    function User() {
        //global $Fun;
        //$this->db = new UserDB;
        //$this->db->Debug = 0;
        //$this->Fun = &$Fun;
        $this->_table["user"]   = "ex_user_set";
        $this->_col["user"]["uno"]       = "uno";
        $this->_col["user"]["user_id"]   = "user_id";
        $this->_col["user"]["user_name"] = "user_name";
        $this->_col["user"]["user_pwd"]  = "user_pwd";
        $this->_col["user"]["dept_id"]   = "dept_id";
        $this->_col["user"]["team_id"]   = "team_id";
        $this->_col["user"]["duty_id"]   = "duty_id";
		$this->_col["user"]["is_active"] = "is_active";

        $this->_table["dept"] = "ex_dept_set";
        $this->_col["dept"]["dept_id"]   = "dept_no";
        $this->_col["dept"]["dept_name"] = "dept_name";

        $this->_table["team"]   = "ex_dept_set";
        $this->_col["team"]["team_id"]   = "dept_no";
        $this->_col["team"]["team_name"] = "dept_name";

        $this->_table["duty"]   = "ex_duty_set";
        $this->_col["duty"]["duty_id"]   = "duty_no";
        $this->_col["duty"]["duty_name"] = "duty_name";
        $this->uno = $this->getUNo();
        $this->user_id = $this->getUserID();
    }
    /**
     * 사용자 정보 존재 여부
     * @Param $Auser_id {String} : 사용자 ID
     * @Rerurn {Boolean}
     */
    function isUser($Auser_id){
        $SQL = "SELECT COUNT(*) FROM v_member WHERE jigweon_cd = '" . $Auser_id . "'";
        $this->db->query($SQL);
        $this->db->next_record();
        if ($this->db->f(0) > 0) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * 쿼리
     * @Param $AQty_Type {String} : 쿼리 타입
     * @Return {String}
     */
    function getQueryString($AQty_Type){
        if (!$this->db){
            $this->db = new UserDB;
        }
        if (!$this->db){
            $this->db = new DB;
        }
        switch (strtoupper($AQty_Type)) {
            case "INFO" :
            case "LOGIN" :
                $SQL = "SELECT "
                      . $this->_col["user"]["uno"]               . " uno"
                      . ", " . $this->_col["user"]["user_id"]    . " user_id"
                      . ", " . $this->_col["user"]["user_name"]  . " user_name"
                      . ", " . $this->_col["user"]["user_pwd"]   . " user_pwd"
                      . ", " . $this->_col["user"]["dept_id"]    . " dept_id"
                      //. ", " . $this->_col["user"]["team_id"]    . " team_id"
                      . ", " . $this->_col["user"]["duty_id"]    . " duty_id"
                      . ", " . $this->_col["user"]["is_active"] . " is_active"
                      . " FROM " . $this->_table["user"] . " users"
                      . " WHERE 1 = 1 "
                    ;
                if($_SERVER["REMOTE_ADDR"] == "61.41.17.41"){
                //echo $SQL;
                //exit;
                }
                break;
            case "DEPT";
                $SQL = "SELECT "
                      . $this->_col["dept"]["dept_id"]          . " dept_id"
                      . ", " . $this->_col["dept"]["dept_name"] . " dept_name"
                      . " FROM " . $this->_table["dept"] . " dept"
                      . " WHERE 1 = 1 ";
                break;
            case "TEAM";
                $SQL = "SELECT "
                      . $this->_col["team"]["team_id"]          . " team_id"
                      . ", " . $this->_col["team"]["team_name"] . " team_name"
                      . " FROM " . $this->_table["team"] . " team"
                      . " WHERE 1 = 1 ";
                break;
            case "DUTY";
                $SQL = "SELECT "
                      . $this->_col["duty"]["duty_id"]          . " duty_id"
                      . ", " . $this->_col["duty"]["duty_name"] . " duty_name"
                      . " FROM " . $this->_table["duty"] . " duty"
                      . " WHERE 1 = 1 ";
                break;
            default :
              $SQL = NULL;
        }
        return $SQL;
    }
    /**
     * 부서명
     * @Param $ACode {String} : 부서 Code(ID)
     * @Return {String}
     */
    function getDeptName($ACode){
        $SQL = $this->getQueryString("DEPT");
        $SQL .= " AND " . $this->_col["dept"]["dept_id"] . " = '" . $ACode . "'";
        $this->db->query($SQL);
        if ($this->db->nf() > 0){
            $this->db->next_record();
            return $this->db->f("dept_name");
        } else {
            return NULL;
        }
    }
    /**
     * 팀명
     * @Param $ACode {String} : 부서 Code(ID)
     * @Return {String}
     */
    function getTeamName($ACode){
        $SQL = $this->getQueryString("TEAM");
        $SQL .= " AND " . $this->_col["team"]["team_id"] . " = '" . $ACode . "'";
        //echo $SQL;
        $this->db->query($SQL);
        if ($this->db->nf() > 0){
            $this->db->next_record();
            return $this->db->f("team_name");
        } else {
            return NULL;
        }
    }
    /**
     * 팀명
     * @Param $ACode {String} : 부서 Code(ID)
     * @Return {String}
     */
    function getDutyName($ACode){
        $SQL = $this->getQueryString("DUTY");
        $SQL .= " AND " . $this->_col["duty"]["duty_id"] . " = '" . $ACode . "'";
        //echo $SQL;
        $this->db->query($SQL);
        if ($this->db->nf() > 0){
            $this->db->next_record();
            return $this->db->f("duty_name");
        } else {
            return NULL;
        }
    }
    /**
     * 로그인(로그인 폼 이용시)
     * @Param $input_user_id {String} : 사용자 ID
     * @Param $input_user_pwd {String} : 사용자 Password(기본값 : Null)
     * @Param &$Amsg {PString} : 메세지 문자열 => &변수이므로 그값을 그대로 가짐(포인터 변수)
     * @Return {Boolean}
     */
    function setLogin($input_user_id, $input_user_pwd = NULL, &$return_message = NULL){
        //echo $this->getUNo();
        //if (!$this->getUNo()){
        //echo $input_user_pwd;
        //echo "<br />";
        //echo md5($input_user_pwd);
        //echo "<br />";
        //echo md5("1234");
        //exit;
        $input_user_pwd = md5($input_user_pwd);
            unset($_SESSION["user"]);
            $input_user_id = trim($input_user_id);
            if (!is_null($input_user_pwd)){
                $input_user_pwd = trim($input_user_pwd);
            }
            $SQL = $this->getQueryString("LOGIN");
            $SQL .= " AND " . $this->_col["user"]["user_id"] . " = '" . $input_user_id . "'";
            //echo $SQL;
            $this->db->query($SQL);
            //Fun::print_r($this->db->Record);
            $n = $this->db->nf();
            if ($n == 1) {
                $this->db->next_record();
                $row = $this->db->Record;
                //Fun::print_r($row);
                if ($row["is_active"] != "Y"){
                    $return_message = "사용이 허가된 사용자가 아닙니다. 관리자에게 문의하세요.";
                    return false;
                } else if (!is_null($input_user_pwd) && $row["is_active"] == "Y" && $input_user_pwd != $row["user_pwd"]) {
                    $return_message = "사용자 비밀번호가 틀렸습니다. 다시 한번 확인하시기 바랍니다.";
                    return false;
                } else {
                    try{
                        @session_start();
                        unset($_SESSION["user"]);
                        unset($_COOKIE["uno"]);
                        unset($_COOKIE["user_id"]);
                        @setCookie("uno", $row["uno"], 0, "/");
                        @setCookie("user_id", $row["user_id"], 0, "/");
                        
                        $this->uno = $row["uno"];
                        $this->user_id = $row["user_id"];
                        $_SESSION["user"]["uno"]       = $row["uno"];
                        $_SESSION["user"]["user_id"]   = $row["user_id"];
                        $_SESSION["user"]["user_name"] = $row["user_name"];
                        $_SESSION["user"]["dept_id"]   = $row["dept_id"];
                        $_SESSION["user"]["dept_name"] = $this->getDeptName($row["dept_id"])??null;
                        $_SESSION["user"]["team_id"]   = $row["team_id"]??null;
                        $_SESSION["user"]["team_name"] = @$this->getTeamName($row["team_id"])??null;
                        $_SESSION["user"]["duty_id"]   = $row["duty_id"];
                        $_SESSION["user"]["duty_name"] = $this->getDutyName($row["duty_id"]);
                        
                        /*
                        $SQL = "SELECT JOB_AUTH FROM JOB_USER_SET WHERE UNO = '" . $row["uno"] . "' AND is_use = 'Y' GROUP BY JOB_AUTH";
                        $this->db->query($SQL);
                        if($this->db->nf() > 0){
                            while($this->db->next_record()){
                                $_SESSION["user"][$this->db->f("job_auth")] = "Y";
                            }
                        }
                        $SQL = "SELECT COUNT(*) AS CNT FROM JOB_INFO WHERE JOB_PM = '" . $row["uno"] . "'";
                        $n = $this->db->query_one($SQL);
                        //echo $n;
                        if($n > 0){
                            //echo $n;
                            $_SESSION["user"]["PM"] = "Y";
                        }
                         * */
                        //Fun::print_r($_SESSION);
                        //exit;
                        $return_message = "사용이 허가 되었습니다.";
                        return true;
                    } catch (Exception $e) {
                        $return_message = "사용자 정보 생성 중 오류가 발생하였습니다. 관리자에게 문의하세요.(" . $e->getMessage() . ")";
                        return flase;
                    }
                }
            } else if ( $n > 1){
                $return_message = "사용자 정보가 2개이상 입니다. 관리자에게 문의하세요.";
                return false;
            } else {
                $return_message = "사용자 정보가 존재 하지 않습니다.";
                return false;
            }
        //} else {
            return true;
        //}
    }
    /**
     * 로그인(WebBiz 이용 통해서 접속 시)
     * @Param $user_id {String} : 사용자 ID
     * @Param &$msg {PString} : 메세지 문자열 => &변수이므로 그값을 그대로 가짐(포인터 변수)
     * @Return {Boolean}
     */
    function setWebBizLogin($Auser_id, &$Amsg = NULL){
        return $this->setLogin($Auser_id, NULL, $Amsg);
    }
    /**
     * 사용자 UNO 정보
     * @Return {String}
     */
    function getUNo(){
        $str = null;
        //$str = $_COOKIE["uno"];
        if ( !$str && isset($_SESSION["user"]["uno"]) == true ){
            $str = $_SESSION["user"]["uno"];
        }
        if (!$str){
            $str = $this->uno;
        }
        $this->uno = $str;
        return $str;
    }
    /**
     * 사용자 USER ID 정보
     * @Return {String}
     */
    function getUserID(){
        $str = "";
        //$str = $_COOKIE["user_id"];
        if (!$str && isset($_SESSION["user"]["uno"]) == true){
            $str = $_SESSION["user"]["user_id"];
        }
        if (!$str){
            $str = $this->user_id;
        }
        $this->user_id = $str;
        return $str;
    }
    /**
     * 사용자 정보
     * @Param $ACol {String} : 사용자 ID
     * @Return {String}
     */
    function getUserInfo($ACol){
        $ACol = strtolower($ACol);
        switch($ACol){
            case "uno" :
                $str = $this->getUNo();
                break;
            case "user_id" :
                $str = $this->getUserID();
                break;
            default :
                $str = $_SESSION["user"][$ACol];
        }
        return $str;
    }
    /**
     * 사용자 정보
     * @Param $ACol {String} : 사용자 ID
     * @Return {String}
     */
    function UserInfo($ACol){
        return $this->getUserInfo($ACol);
    }
}
$user = new User;
if($_POST["webbiz_user_id"]??"" != ""){
    //unset($_SESSION["user"]);
    //Fun::print_r($_SESSION);
    //Fun::print_r($_POST);
    $user->setWebBizLogin($_POST["webbiz_user_id"],$msg);
} else {
    //Fun::print_r($_SESSION);
    //Fun::print_r($_POST);
}
//Fun::print_r($_SESSION);
?>