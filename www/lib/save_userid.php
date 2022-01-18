<?php
if($_GET["user_id"] == "null"){
    $exTime = time() - 3600;
    @setcookie("save_id"     ,    "", $exTime, "/");
    @setcookie("save_user_id",    "", $exTime, "/");
    unset($_COOKIE["save_id"]);
    unset($_COOKIE["save_user_id"]);
} else {
    $exTime = time() + (3 * 24 *3600);
    @setcookie("save_id"     ,             true, $exTime, "/");
    @setcookie("save_user_id", $_GET["user_id"], $exTime, "/");
}
?>