<?php
class Page{
    var $User = null;
    //var $Fun = null;
    var $Tpl = null;
    /**
     * PHP5용 Class 생성자
     */
    function __construct(){
        $this->Page();
    }
    function __destruct(){
      //echo "__destruct";
    }
    /**
     * PHP4용 Class 생성자
     */
    function Page() {
        //global $Fun;
        global $User;
        global $Tpl;
        //if($Fun == null || !get_class($Fun)){
        //    $this->Fun = new Fun;
        //    $Fun = &$this->Fun;
        //} else {
        //    $this->Fun = &$Fun;
        //}
        if($User == null || !get_class($User)){
            $this->User = new User;
            $User = &$this->User;
        } else {
            $this->User = &$User;
        }
        if($Tpl == null || !get_class($Tpl)){
            $this->Tpl = new Template;
            $Tpl = &$this->Tpl;
        } else {
            $this->Tpl = &$Tpl;
        }
    }
    function logout(){
        $this->pageLogout();
    }
    function pageLogout(){
        unset($_SESSION["user"]);
    }
    function pageLogin($args = NULL, bool $isNotLoginedAlter = false){
        //global $post;
        /*
        if($_POST["webbiz_user_id"]){
            //Fun::print_r($_POST);
            unset($_SESSION["user"]);
            $this->User->setWebBizLogin($_POST["webbiz_user_id"],$msg);
        }*/
        /*
        if (!$_SESSION["user"]["uno"]) {
            //unset($_SESSION["user"]);
            //Fun::print_r($_POST);
            if($_POST["webbiz_user_id"]){
                $this->User->setWebBizLogin($_POST["webbiz_user_id"],$msg);
            } else {
                //echo $user->uno;
            }
        }*/
        /*
        if($_POST["login_mode"] == "login" && $_SERVER["REMOTE_ADDR"] == "61.41.17.41"){
            echo "<img src='/lib/save_userid.php?user_id=null' border=0 width=0 height=0 />";
            Fun::print_r($_POST);
            Fun::print_r($_COOKIE);
            exit;
        }
        */
        @session_start();
        if(@$_SESSION["user"]["uno"] != ""){
            return;
        }
        if($this->User == null || !$this->User->uno){
            if($isNotLoginedAlter == true)
            {
                Fun::alert("접속 권한이 없습니다.", "self");
            }
            if($_POST["login_mode"]??null == "login" && $_POST["login_user_id"]??null && $_POST["login_password"]??null){
                //Fun::print_r($_POST);
                //exit;
                if(!$this->User->setLogin($_POST["login_user_id"], $_POST["login_password"], $login_message)){
                    Fun::msg($login_message);
                    include dirname(__FILE__) . "/login_form.php";
                    exit;
                } else {
                    if($_SERVER["REMOTE_ADDR"] == "10.10.102.65" || $_SERVER["REMOTE_ADDR"] == "10.10.102.80" || $_SERVER["REMOTE_ADDR"] == "10.10.102.86"){
                        //Fun::print_r($_POST);
			//exit;
                    }
                    if(!@$_POST["login_save_id"]){
                        echo "<img src='/lib/save_userid.php?user_id=null' border=0 width=0 height=0 />";
                        //echo "dddd";
                        //exit;
                    } else {
                        echo "<img src='/lib/save_userid.php?user_id=" . $_POST["login_user_id"] . "' border=0 width=0 height=0 />";
                    }
                    Fun::msg($login_message);
                    $url = Fun::getExpUrl("login_mode,login_user_id,login_password", "");
                    echo $url;
                    //exit;
                    Fun::goPage($url);
                    
                }
            } else {
                include dirname(__FILE__) . "/login_form.php";
                exit;
            }
        }
        if($this->User != null || !$this->User->uno){
            Fun::alert("접속 권한이 없습니다.");
            return false;
        }
        $args = func_get_args();
        if ($args){
            $this->getPageAuth($args);
        }
    }
    function getPageAuth($args){
        $flag = true;
        if(!$args){
            $args = func_get_args();
        }
        if(count($args) > 0){
            $flag = false;
            foreach($args as $_key => $_val){
                $_val = strtoupper($_val);
                if($_SESSION["user"][$_val] == "Y"){
                    //echo $_val;
                    $flag = true;
                }
            }
        }
        if($_SESSION["user"]["AT"] == "Y"){
            $flag = true;
        }
        //Fun::print_r($args);
        //Fun::print_r($_SESSION["user"]);
        if($flag == false){
            Fun::alert("접속 권한이 없습니다.");
        }
        //
    }
    /**
     * 순수하게 PM권한만 가지고 있을경우 true 반환
     */
    function isAuthOnlyPM(){
        $flag = false;
        if($_SESSION["user"]["PM"] == "Y"){
            $flag = true;
        }
        if($_SESSION["user"]["AT"] == "Y"){
            $flag = false;
        }
        if($_SESSION["user"]["DT"] == "Y"){
            $flag = false;
        }
        if($_SESSION["user"]["MT"] == "Y"){
            $flag = false;
        }
        return $flag;
    }
    /**
     * 최고관리자인지 여부
     */
    function isAT(){
        return $this->isAdmin();
    }
    function isAdmin(){
        if($_SESSION["user"]["AT"] == "Y"){
            return true;
        } else {
            return false;
        }
    }
    /**
     * 관리자(운영자)인지 여부
     */
    function isMT(){
        return $this->isManager();
    }
    function isManager(){
        if($_SESSION["user"]["MT"] == "Y"){
            return true;
        } else {
            return false;
        }
    }
    /**
     * 팀장인지 여부
     */
    function isTM(){
        return $this->isTeamManager();
    }
    function isTeamManager(){
        if($_SESSION["user"]["TM"] == "Y"){
            return true;
        } else {
            return false;
        }
    }
    /**
     * PM인지 여부
     */
    function isPM(){
        return $this->isProjectManager();
    }
    function isProjectManager(){
        if($_SESSION["user"]["PM"] == "Y"){
            return true;
        } else {
            return false;
        }
    }
    /**
     * 임원진인지 여부
     */
    function isDT(){
        return $this->isDirectorManager();
    }
    function isDirectorManager(){
        if($_SESSION["user"]["DT"] == "Y"){
            return true;
        } else {
            return false;
        }
    }
}
$Page = new Page;