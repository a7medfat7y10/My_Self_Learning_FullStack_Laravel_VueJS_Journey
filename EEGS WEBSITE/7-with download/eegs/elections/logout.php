<?php
//log out page
    session_start();
    session_unset();
    session_destroy();
    
    header('Location: login.php'); //redirect to login page
    exit();
    