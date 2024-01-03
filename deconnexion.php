<?php
session_start();

if (isset($_GET['deco'])) {

    $_SESSION = array();


    session_destroy();


    header('Location: login.html');
    exit();
}
?>

