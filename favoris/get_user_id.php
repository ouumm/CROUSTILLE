<?php
session_start();

if (isset($_SESSION['ID'])  ) {
    echo $_SESSION['ID'];
} else {
    echo 'null';
}