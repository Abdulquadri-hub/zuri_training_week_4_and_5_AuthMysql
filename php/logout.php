<?php
function logout(){
    session_start();
    if (isset($_SESSION)) {
        session_unset();
        session_destroy();
        echo "<script> window.open('../forms/login.html','_self') </script>";
    }
}
logout();