<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/include.php";
@session_start();
unset($_SESSION["user"]);
Fun::goPage($_SERVER['HTTP_REFERER']);
?>