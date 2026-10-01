<?php



ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);




    require_once "config/config.php";

    session_set_cookie_params($SECURITY_CONFIG["session"]);
    SessionHelper::garanteSessaoIniciada();

    require_once "controller/router.php";
?>
